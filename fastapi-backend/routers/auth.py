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

@router.post("/forgot-password", summary="[Auth] 4. Minta Token Lupa Password", description="Masukkan email untuk meminta token reset password. Token ini akan dikembalikan secara langsung (simulasi email) dan berlaku selama 15 menit.")
def forgot_password(
    request_data: models.ForgotPasswordRequest,
    db: Annotated[Session, Depends(get_db)]
):
    user = db.exec(select(models.User).where(models.User.email == request_data.email)).first()
    if not user:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="User dengan email tersebut tidak ditemukan"
        )
    
    # Buat token reset
    token = auth_utils.generate_reset_token(user.email)
    
    # Simulasi pengiriman email ke console server
    print(f"\n==================================================")
    print(f"SIMULASI EMAIL RESET PASSWORD:")
    print(f"Kepada: {user.email}")
    print(f"Halo {user.name}, berikut adalah token reset password Anda:")
    print(f"Token: {token}")
    print(f"Token ini berlaku selama 15 menit.")
    print(f"==================================================\n")
    
    return {
        "message": "Token reset password berhasil dibuat. Untuk keperluan simulasi, token dikembalikan pada response ini dan di console log server.",
        "email": user.email,
        "token": token
    }

@router.post("/reset-password", summary="[Auth] 5. Reset Password dengan Token", description="Gunakan token reset password yang valid untuk menetapkan password baru.")
def reset_password(
    request_data: models.ResetPasswordRequest,
    db: Annotated[Session, Depends(get_db)]
):
    email = auth_utils.verify_reset_token(request_data.token)
    if not email:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Token tidak valid atau sudah kadaluarsa"
        )
    
    user = db.exec(select(models.User).where(models.User.email == email)).first()
    if not user:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="User tidak ditemukan"
        )
    
    # Hash password baru dan update
    user.password = auth_utils.get_password_hash(request_data.new_password)
    db.add(user)
    db.commit()
    
    # Hapus token dari in-memory store agar tidak bisa digunakan lagi
    auth_utils.invalidate_reset_token(request_data.token)
    
    return {"message": f"Password untuk akun {user.email} berhasil diperbarui"}
