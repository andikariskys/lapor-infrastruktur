from fastapi import APIRouter, Depends, HTTPException, status
from sqlmodel import Session, select
from typing import Annotated, List
import models
import auth_utils
from database import get_db

router = APIRouter(prefix="/api/categories", tags=["Categories"])

@router.get("", response_model=List[models.Category], summary="[Semua] 1. List Semua Kategori", description="Melihat daftar kategori infrastruktur (Jalan, Jembatan, dll).")
def get_categories(db: Annotated[Session, Depends(get_db)]):
    statement = select(models.Category)
    return db.exec(statement).all()

@router.post("", response_model=models.Category, summary="[Admin] 2. Tambah Kategori", description="Admin bisa menambahkan kategori infrastruktur baru.")
def create_category(
    category_data: models.CategoryBase,
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.admin]))]
):
    new_category = models.Category(
        name=category_data.name,
        description=category_data.description,
        color_code=category_data.color_code
    )
    db.add(new_category)
    db.commit()
    db.refresh(new_category)
    return new_category

@router.patch("/{category_id}", response_model=models.Category, summary="[Admin] 3. Update Kategori", description="Mengubah nama kategori yang sudah ada.")
def update_category(
    category_id: int,
    category_data: models.CategoryBase,
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.admin]))]
):
    db_category = db.get(models.Category, category_id)
    if not db_category:
        raise HTTPException(status_code=404, detail="Kategori tidak ditemukan")
    
    db_category.name = category_data.name
    db_category.description = category_data.description
    db_category.color_code = category_data.color_code
    db.add(db_category)
    db.commit()
    db.refresh(db_category)
    return db_category

@router.delete("/{category_id}", summary="[Admin] 4. Hapus Kategori", description="Menghapus kategori dari sistem.")
def delete_category(
    category_id: int,
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.admin]))]
):
    db_category = db.get(models.Category, category_id)
    if not db_category:
        raise HTTPException(status_code=404, detail="Kategori tidak ditemukan")
    db.delete(db_category)
    db.commit()
    return {"message": "Kategori berhasil dihapus"}
