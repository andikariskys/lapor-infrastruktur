from fastapi import APIRouter, Depends, HTTPException, status
from sqlmodel import Session, select
from typing import Annotated, List
from datetime import datetime
import models
import auth_utils
from database import get_db

router = APIRouter(prefix="/api/officer", tags=["Officer"])

@router.get("/tasks", response_model=List[models.ReportRead])
def get_officer_tasks(
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.officer]))]
):
    statement = select(models.Report).where(models.Report.officer_id == current_user.id)
    statement = statement.order_by(models.Report.created_at.desc())
    return db.exec(statement).all()

@router.patch("/reports/{report_id}/status", response_model=models.Report)
def officer_update_report_status(
    report_id: int,
    status_update: models.ReportUpdateStatus,
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.officer]))]
):
    report = db.get(models.Report, report_id)
    if not report or report.officer_id != current_user.id:
        raise HTTPException(status_code=403, detail="Bukan tugas Anda")
    
    if status_update.status not in [models.ReportStatus.in_progress, models.ReportStatus.resolved]:
        raise HTTPException(status_code=400, detail="Status tidak valid")

    report.status = status_update.status
    report.updated_at = datetime.now()
    db.add(report)
    db.commit()
    db.refresh(report)
    return report
