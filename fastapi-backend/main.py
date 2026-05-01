<<<<<<< HEAD
from fastapi import FastAPI, Depends, HTTPException, status
from fastapi.security import OAuth2PasswordRequestForm
from fastapi.middleware.cors import CORSMiddleware
from sqlmodel import SQLModel, Session, select
from database import engine, get_db
import auth_utils, models
=======
from fastapi import FastAPI
import bcrypt
>>>>>>> origin/main

# Create tables in database (if they don't exist)
SQLModel.metadata.create_all(bind=engine)

app = FastAPI(
    title="FastAPI - Auth + JWT DB",
    description="Fokus pada Auth (JWT) yang terkoneksi ke Database",
    version="1.1.0"
)

# Setup CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.get("/")
async def root():
<<<<<<< HEAD
    return {"message": "API Autentikasi JWT + Database siap!"}

@app.post("/api/auth/register")
def register(user: models.User, db: Session = Depends(get_db)):
    # Hash password sebelum simpan
    user.password = auth_utils.get_password_hash(user.password)
    db.add(user)
    db.commit()
    db.refresh(user)
    return user

@app.post("/api/auth/login")
def login(form_data: OAuth2PasswordRequestForm = Depends(), db: Session = Depends(get_db)):
    """
    Endpoint login untuk mendapatkan access_token.
    """
    statement = select(models.User).where(models.User.username == form_data.username)
    user = db.exec(statement).first()
    
    if not user or not auth_utils.verify_password(form_data.password, user.password):
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Username atau password salah",
            headers={"WWW-Authenticate": "Bearer"},
        )
        
    access_token = auth_utils.create_access_token(data={"sub": user.username})
    return {"access_token": access_token, "token_type": "bearer"}

@app.get("/api/auth/me")
def get_user_info(current_user: models.User = Depends(auth_utils.get_current_user)):
    """
    Endpoint ini sekarang mengecek user langsung dari DATABASE
    menggunakan HTTP Basic Auth.
    """
    return current_user
=======
    return {"message": "Selamat datang di Lapor Infrastruktur API!"}

# Contoh endpoint untuk hashing password menggunakan bcrypt
@app.get("/hash-password/")
async def hash_password():
    # Anggap saja kita memiliki password yang ingin di-hash
    password = "password@123"
    # Hash password menggunakan bcrypt
    hashed_password = bcrypt.hashpw(password.encode('utf-8'), bcrypt.gensalt())
    hashed_password = hashed_password.decode('utf-8')  # Hasil hash ini simpan ke database
    # Verifikasi password (contoh)
    is_valid = bcrypt.checkpw(password.encode('utf-8'), hashed_password.encode('utf-8'))
    return {"password": password, "hashed_password": hashed_password, "is_valid": is_valid}
>>>>>>> origin/main
