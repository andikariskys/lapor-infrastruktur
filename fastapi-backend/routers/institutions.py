from fastapi import APIRouter, Depends, HTTPException, status, Form, File, UploadFile
from sqlmodel import Session, select
from typing import Annotated, List, Optional
import os
import uuid
import models
import auth_utils
from database import get_db, UPLOAD_DIR

router = APIRouter(prefix="/api/institutions", tags=["Institutions"])

@router.get("", response_model=List[models.Institution], summary="[Semua] 1. List Semua Lembaga", description="Melihat daftar lembaga atau instansi pemerintah yang terdaftar.")
def get_institutions(db: Annotated[Session, Depends(get_db)]):
    statement = select(models.Institution)
    return db.exec(statement).all()

@router.post("", response_model=models.Institution, summary="[Admin] 2. Tambah Lembaga", description="Admin bisa mendaftarkan lembaga/instansi baru ke dalam sistem.")
async def create_institution(
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.admin]))],
    name: str = Form(...),
    description: str = Form(...),
    address: str = Form(...),
    phone: str = Form(...),
    email: str = Form(...),
    image: UploadFile = File(...)
):
    file_ext = image.filename.split(".")[-1]
    file_name = f"inst_{uuid.uuid4()}.{file_ext}"
    file_path = os.path.join(UPLOAD_DIR, file_name)
    with open(file_path, "wb") as f:
        f.write(await image.read())
    
    new_inst = models.Institution(
        name=name,
        description=description,
        address=address,
        phone=phone,
        email=email,
        profile_photo=f"/uploads/{file_name}"
    )
    db.add(new_inst)
    db.commit()
    db.refresh(new_inst)
    return new_inst

@router.patch("/{inst_id}", response_model=models.Institution, summary="[Admin] 3. Update Lembaga", description="Mengubah informasi profil lembaga (alamat, kontak, dll).")
async def update_institution(
    inst_id: int,
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.admin]))],
    name: Optional[str] = Form(None),
    description: Optional[str] = Form(None),
    address: Optional[str] = Form(None),
    phone: Optional[str] = Form(None),
    email: Optional[str] = Form(None),
    image: Optional[UploadFile] = File(None)
):
    db_inst = db.get(models.Institution, inst_id)
    if not db_inst:
        raise HTTPException(status_code=404, detail="Instansi tidak ditemukan")
    
    if name: db_inst.name = name
    if description: db_inst.description = description
    if address: db_inst.address = address
    if phone: db_inst.phone = phone
    if email: db_inst.email = email

    if image:
        file_ext = image.filename.split(".")[-1]
        file_name = f"inst_{uuid.uuid4()}.{file_ext}"
        file_path = os.path.join(UPLOAD_DIR, file_name)
        with open(file_path, "wb") as f:
            f.write(await image.read())
        db_inst.profile_photo = f"/uploads/{file_name}"

    db.add(db_inst)
    db.commit()
    db.refresh(db_inst)
    return db_inst

@router.delete("/{inst_id}", summary="[Admin] 4. Hapus Lembaga", description="Menghapus lembaga dari sistem.")
def delete_institution(
    inst_id: int,
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.admin]))]
):
    db_inst = db.get(models.Institution, inst_id)
    if not db_inst:
        raise HTTPException(status_code=404, detail="Instansi tidak ditemukan")
    db.delete(db_inst)
    db.commit()
    return {"message": "Instansi berhasil dihapus"}
