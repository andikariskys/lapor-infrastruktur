from fastapi import APIRouter, Depends, HTTPException, status, Query, Form, File, UploadFile
from sqlmodel import Session, select
from typing import Annotated, List, Optional
import os
import uuid
import models
import auth_utils
from database import get_db, UPLOAD_DIR
from image_utils import compress_and_save_image

router = APIRouter(prefix="/api/users", tags=["Users"])

@router.get("", response_model=List[models.UserRead], summary="[Admin] 1. List Semua User", description="Menampilkan daftar seluruh pengguna yang ada di sistem.")
def get_all_users(
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.admin]))],
    role: Optional[str] = Query(None)
):
    statement = select(models.User)
    if role:
        statement = statement.where(models.User.role == role)
    return db.exec(statement).all()

@router.get("/officers", response_model=List[models.UserRead])
def get_officers(
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.get_current_user)]
):
    statement = select(models.User).where(models.User.role == models.UserRole.officer)
    return db.exec(statement).all()

@router.post("/officers", response_model=models.UserRead, summary="[Admin] 2. Tambah Petugas/Admin", description="Membuat akun petugas atau admin baru. Memerlukan input nama, email, password, dan pilihan instansi.")
async def create_officer(
    name: Annotated[str, Form()],
    email: Annotated[str, Form()],
    phone: Annotated[str, Form()],
    password: Annotated[str, Form()],
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.admin]))],
    role: Annotated[models.UserRole, Form()] = models.UserRole.officer,
    institution_id: Annotated[Optional[int], Form()] = None,
    image: Annotated[Optional[UploadFile], File()] = None
):
    existing_user = db.exec(select(models.User).where(models.User.email == email)).first()
    if existing_user:
        raise HTTPException(status_code=400, detail="Email sudah terdaftar")
    
    file_url = None
    if image:
        file_ext = image.filename.split(".")[-1]
        file_name = f"{uuid.uuid4()}.{file_ext}"
        file_path = os.path.join(UPLOAD_DIR, "profiles", file_name)
        await compress_and_save_image(image, file_path)
        file_url = file_name

    new_officer = models.User(
        name=name,
        email=email,
        phone=phone,
        password=auth_utils.get_password_hash(password),
        role=role,
        institution_id=institution_id,
        profile_photo=file_url
    )
    db.add(new_officer)
    db.commit()
    db.refresh(new_officer)
    return new_officer

@router.patch("/me", response_model=models.UserRead, summary="[User] Update Profil Saya", description="Mengubah data profil pengguna yang sedang login saat ini (nama, email, nomor telepon, foto profil).")
async def update_me(
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.get_current_user)],
    name: Annotated[Optional[str], Form()] = None,
    email: Annotated[Optional[str], Form()] = None,
    phone: Annotated[Optional[str], Form()] = None,
    image: Annotated[Optional[UploadFile], File()] = None
):
    if email and email != current_user.email:
        # Cek apakah email sudah terdaftar untuk user lain
        existing_user = db.exec(select(models.User).where(models.User.email == email)).first()
        if existing_user:
            raise HTTPException(status_code=400, detail="Email sudah terdaftar")
        current_user.email = email

    if name:
        current_user.name = name
    if phone:
        current_user.phone = phone

    if image:
        file_ext = image.filename.split(".")[-1]
        file_name = f"{uuid.uuid4()}.{file_ext}"
        file_path = os.path.join(UPLOAD_DIR, "profiles", file_name)
        await compress_and_save_image(image, file_path)
        current_user.profile_photo = file_name

    db.add(current_user)
    db.commit()
    db.refresh(current_user)
    return current_user

@router.patch("/{user_id}", response_model=models.UserRead, summary="[Admin] 3. Update Data User", description="Mengubah informasi profil pengguna lain (khusus Admin).")
async def admin_update_user(
    user_id: int,
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.admin]))],
    name: Annotated[Optional[str], Form()] = None,
    email: Annotated[Optional[str], Form()] = None,
    phone: Annotated[Optional[str], Form()] = None,
    institution_id: Annotated[Optional[int], Form()] = None,
    role: Annotated[Optional[models.UserRole], Form()] = None,
    image: Annotated[Optional[UploadFile], File()] = None
):
    user = db.get(models.User, user_id)
    if not user:
        raise HTTPException(status_code=404, detail="User tidak ditemukan")
    
    if name: user.name = name
    if email: user.email = email
    if phone: user.phone = phone
    if institution_id is not None: user.institution_id = institution_id
    if role: user.role = role
    
    if image:
        file_ext = image.filename.split(".")[-1]
        file_name = f"{uuid.uuid4()}.{file_ext}"
        file_path = os.path.join(UPLOAD_DIR, "profiles", file_name)
        await compress_and_save_image(image, file_path)
        user.profile_photo = file_name
        
    db.add(user)
    db.commit()
    db.refresh(user)
    return user

@router.patch("/me/change-password", summary="[User] Ganti Password Saya", description="Mengubah password akun yang sedang login saat ini. Harus menyertakan password lama dan password baru.")
def change_my_password(
    password_data: models.PasswordChange,
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.get_current_user)]
):
    # Verifikasi password lama
    if not auth_utils.verify_password(password_data.old_password, current_user.password):
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Password lama salah"
        )
    
    # Hash password baru
    current_user.password = auth_utils.get_password_hash(password_data.new_password)
    db.add(current_user)
    db.commit()
    return {"message": "Password Anda berhasil diperbarui"}

@router.patch("/{user_id}/reset-password", summary="[Admin/User] 4. Reset Password User", description="Admin bisa mereset password pengguna, atau pengguna bisa mengganti passwordnya sendiri.")
def reset_user_password(
    user_id: int,
    reset_data: models.PasswordReset,
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.get_current_user)]
):
    user = db.get(models.User, user_id)
    if not user:
        raise HTTPException(status_code=404, detail="User tidak ditemukan")
        
    # Cek izin: Harus admin ATAU user itu sendiri
    if current_user.role != models.UserRole.admin and current_user.id != user_id:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Anda tidak memiliki izin untuk mengubah kata sandi akun ini."
        )
    
    user.password = auth_utils.get_password_hash(reset_data.new_password)
    db.add(user)
    db.commit()
    return {"message": f"Password untuk {user.name} berhasil direset"}

@router.delete("/{user_id}", summary="[Admin] 5. Hapus User", description="Menghapus akun pengguna dari sistem secara permanen.")
def delete_user(
    user_id: int,
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.admin]))]
):
    user = db.get(models.User, user_id)
    if not user:
        raise HTTPException(status_code=404, detail="User tidak ditemukan")
    db.delete(user)
    db.commit()
    return {"message": "User berhasil dihapus"}
