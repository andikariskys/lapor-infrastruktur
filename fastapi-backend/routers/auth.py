from fastapi import APIRouter, Depends, HTTPException, status
from fastapi.security import OAuth2PasswordRequestForm
from sqlmodel import Session, select
from typing import Annotated
import models
import auth_utils
from database import get_db

router = APIRouter(prefix="/api/auth", tags=["Authentication"])

@router.post("/register", response_model=models.User, summary="[Auth] 1. Registrasi Akun Pelapor", description="Langkah pertama bagi warga yang ingin melapor. Gunakan ini untuk membuat akun baru.")
def register(user_data: models.UserCreate, db: Annotated[Session, Depends(get_db)]):
    hashed_password = auth_utils.get_password_hash(user_data.password)
    new_user = models.User(
        name=user_data.name,
        email=user_data.email,
        phone=user_data.phone,
        password=hashed_password,
        role=user_data.role
    )
    db.add(new_user)
    db.commit()
    db.refresh(new_user)
    return new_user

@router.post("/login", summary="[Auth] 2. Login & Dapatkan Token", description="Masukkan email dan password untuk mendapatkan Access Token (Bearer). Token ini wajib digunakan untuk mengakses endpoint lainnya.")
def login(
    form_data: Annotated[OAuth2PasswordRequestForm, Depends()],
    db: Annotated[Session, Depends(get_db)]
):
    user = db.exec(select(models.User).where(models.User.email == form_data.username)).first()
    if not user or not auth_utils.verify_password(form_data.password, user.password):
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Email atau password salah",
            headers={"WWW-Authenticate": "Bearer"},
        )
    
    access_token = auth_utils.create_access_token(data={"sub": user.email})
    return {"access_token": access_token, "token_type": "bearer"}

@router.get("/me", summary="[Auth] 3. Ambil Data Profil Saya", description="Mengecek informasi akun yang sedang login saat ini.")
def get_user_info(current_user: Annotated[models.User, Depends(auth_utils.get_current_user)]):
    return current_user
