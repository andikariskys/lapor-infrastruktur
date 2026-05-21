from fastapi import Depends, HTTPException, status
from fastapi.security import OAuth2PasswordBearer
from passlib.context import CryptContext
from sqlmodel import Session, select
from jose import JWTError, jwt
from datetime import datetime, timedelta, timezone
import os
from dotenv import load_dotenv
from typing import Annotated
import models
from database import get_db

# Load environment variables
load_dotenv()

SECRET_KEY = os.getenv("SECRET_KEY")
ALGORITHM = os.getenv("ALGORITHM")
ACCESS_TOKEN_EXPIRE_MINUTES_RAW = os.getenv("ACCESS_TOKEN_EXPIRE_MINUTES")

if not SECRET_KEY or not ALGORITHM or not ACCESS_TOKEN_EXPIRE_MINUTES_RAW:
    raise ValueError("SECRET_KEY, ALGORITHM, atau ACCESS_TOKEN_EXPIRE_MINUTES tidak ditemukan di .env")

ACCESS_TOKEN_EXPIRE_MINUTES = int(ACCESS_TOKEN_EXPIRE_MINUTES_RAW)

# 1. Konfigurasi Hash Password & Security
pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")
oauth2_scheme = OAuth2PasswordBearer(tokenUrl="/api/auth/login")

def verify_password(plain_password, hashed_password):
    return pwd_context.verify(plain_password, hashed_password)

def get_password_hash(password):
    return pwd_context.hash(password)

def create_access_token(data: dict, expires_delta: timedelta | None = None):
    to_encode = data.copy()
    if expires_delta:
        expire = datetime.now(timezone.utc) + expires_delta
    else:
        expire = datetime.now(timezone.utc) + timedelta(minutes=ACCESS_TOKEN_EXPIRE_MINUTES)
    to_encode.update({"exp": expire})
    encoded_jwt = jwt.encode(to_encode, SECRET_KEY, algorithm=ALGORITHM)
    return encoded_jwt

async def get_current_user(
    token: Annotated[str, Depends(oauth2_scheme)], 
    db: Annotated[Session, Depends(get_db)]
):
    credentials_exception = HTTPException(
        status_code=status.HTTP_401_UNAUTHORIZED,
        detail="Could not validate credentials",
        headers={"WWW-Authenticate": "Bearer"},
    )
    try:
        payload = jwt.decode(token, SECRET_KEY, algorithms=[ALGORITHM])
        email: str = payload.get("sub")
        if email is None:
            raise credentials_exception
    except JWTError:
        raise credentials_exception
        
    statement = select(models.User).where(models.User.email == email)
    user = db.exec(statement).first()
    
    if user is None:
        raise credentials_exception
    return user

def check_role(allowed_roles: list[models.UserRole]):
    async def role_checker(current_user: Annotated[models.User, Depends(get_current_user)]):
        if current_user.role not in allowed_roles:
            raise HTTPException(
                status_code=status.HTTP_403_FORBIDDEN,
                detail="Anda tidak memiliki akses untuk melakukan aksi ini."
            )
        return current_user
    return role_checker

# --- In-Memory Reset Password Token Store ---
import secrets
from typing import Optional

reset_tokens = {}

def generate_reset_token(email: str) -> str:
    token = secrets.token_hex(32)
    # Token berlaku selama 15 menit
    expires_at = datetime.now() + timedelta(minutes=15)
    reset_tokens[token] = {"email": email, "expires_at": expires_at}
    return token

def verify_reset_token(token: str) -> Optional[str]:
    token_data = reset_tokens.get(token)
    if not token_data:
        return None
    # Pastikan belum kadaluarsa
    if datetime.now() > token_data["expires_at"]:
        reset_tokens.pop(token, None)
        return None
    return token_data["email"]

def invalidate_reset_token(token: str):
    reset_tokens.pop(token, None)
