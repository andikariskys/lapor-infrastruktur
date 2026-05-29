from fastapi import APIRouter, Depends, HTTPException, status, Form, File, UploadFile, Query
from sqlmodel import Session, select
from sqlalchemy import func, cast, Date
from typing import Annotated, List, Optional
from datetime import datetime
import os
import uuid
import models
import auth_utils
from database import get_db, UPLOAD_DIR

router = APIRouter(prefix="/api", tags=["Reports"])

# Batas maksimal laporan per hari untuk role citizen (pelapor)
DAILY_REPORT_LIMIT = 3

@router.post("/reports", response_model=models.ReportRead, summary="[Pelapor] 1. Membuat Laporan Baru", description="Langkah pertama bagi warga untuk melaporkan kerusakan infrastruktur.")
async def create_report(
    description: Annotated[str, Form()],
    latitude: Annotated[float, Form()],
    longitude: Annotated[float, Form()],
    category_id: Annotated[int, Form()],
    image: Annotated[UploadFile, File()],
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.citizen]))]
):
    # --- Cek limit harian ---
    today = datetime.now().date()
    count_statement = select(func.count(models.Report.id)).where(
        models.Report.user_id == current_user.id,
        cast(models.Report.created_at, Date) == today
    )
    today_count = db.exec(count_statement).one()

    if today_count >= DAILY_REPORT_LIMIT:
        raise HTTPException(
            status_code=status.HTTP_429_TOO_MANY_REQUESTS,
            detail=f"Anda telah mencapai batas maksimal {DAILY_REPORT_LIMIT} laporan per hari. Silakan coba lagi besok."
        )
    # --- End cek limit harian ---

    file_ext = image.filename.split(".")[-1]
    file_name = f"{uuid.uuid4()}.{file_ext}"
    file_path = os.path.join(UPLOAD_DIR, "reports", file_name)
    
    with open(file_path, "wb") as f:
        f.write(await image.read())
    
    new_report = models.Report(
        description=description,
        latitude=latitude,
        longitude=longitude,
        category_id=category_id,
        user_id=current_user.id,
        photo_url=file_name,
        status=models.ReportStatus.pending
    )
    db.add(new_report)
    db.commit()
    db.refresh(new_report)
    return new_report

@router.get("/reports", response_model=List[models.ReportRead], summary="[Admin] 1. List Semua Laporan Masuk", description="Melihat seluruh laporan warga untuk segera divalidasi.")
def get_all_reports(
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.admin]))],
    status: Optional[str] = Query(None)
):
    statement = select(models.Report)
    if status and status.strip():
        statement = statement.where(models.Report.status == status)
    statement = statement.order_by(models.Report.created_at.desc())
    results = db.exec(statement).all()
    return results

@router.get("/reports/my", response_model=List[models.ReportRead], summary="[Pelapor] 2. List Laporan Saya", description="Warga bisa melihat riwayat laporan yang pernah mereka kirim.")
def get_my_reports(
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.citizen]))]
):
    return db.exec(select(models.Report).where(models.Report.user_id == current_user.id)).all()

@router.get("/reports/daily-limit", summary="[Pelapor] Cek Sisa Kuota Laporan Harian", description="Mengecek berapa laporan lagi yang bisa dibuat hari ini.")
def check_daily_limit(
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.citizen]))]
):
    today = datetime.now().date()
    count_statement = select(func.count(models.Report.id)).where(
        models.Report.user_id == current_user.id,
        cast(models.Report.created_at, Date) == today
    )
    today_count = db.exec(count_statement).one()

    return {
        "daily_limit": DAILY_REPORT_LIMIT,
        "reports_today": today_count,
        "remaining": max(0, DAILY_REPORT_LIMIT - today_count),
        "can_create": today_count < DAILY_REPORT_LIMIT
    }

@router.get("/reports/assigned", response_model=List[models.ReportRead], summary="[Petugas] 1. List Tugas Saya", description="Melihat daftar pekerjaan yang harus segera diselesaikan.")
def get_assigned_reports(
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.officer]))]
):
    # Join with Assignment to get reports assigned to this officer
    statement = select(models.Report).join(models.Assignment).where(models.Assignment.officer_id == current_user.id)
    return db.exec(statement).all()

@router.get("/reports/{report_id}", response_model=models.ReportRead, summary="[Semua] Detail Laporan", description="Mengambil informasi detail satu laporan, termasuk data pelapor, kategori, dan siapa petugas yang ditugaskan.")
def get_report_detail(
    report_id: int,
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.get_current_user)]
):
    statement = select(models.Report).where(models.Report.id == report_id)
    report = db.exec(statement).first()
    if not report:
        raise HTTPException(status_code=404, detail="Laporan tidak ditemukan")
    return report

@router.patch("/reports/{report_id}/verify", response_model=models.ReportRead, summary="[Petugas] 3. Selesaikan Laporan", description="Mengubah status laporan menjadi 'Resolved' jika pekerjaan sudah 100% selesai.")
def verify_report(
    report_id: int,
    status_update: models.ReportUpdateStatus,
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.admin, models.UserRole.officer]))]
):
    report = db.get(models.Report, report_id)
    if not report:
        raise HTTPException(status_code=404, detail="Laporan tidak ditemukan")
    
    report.status = status_update.status
    report.updated_at = datetime.now()
    db.add(report)
    db.commit()
    db.refresh(report)
    return report

@router.post("/reports/{report_id}/assign", summary="[Admin] Penugasan Petugas", description="Menugaskan petugas tertentu untuk menangani laporan yang sudah divalidasi.")
def assign_report(
    report_id: int,
    assignment_data: models.AssignmentCreate,
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.admin]))]
):
    report = db.get(models.Report, report_id)
    if not report or report.status != models.ReportStatus.verified:
        raise HTTPException(status_code=400, detail="Laporan tidak valid untuk ditugaskan")
    
    officer = db.get(models.User, assignment_data.officer_id)
    if not officer or officer.role != models.UserRole.officer:
        raise HTTPException(status_code=400, detail="User bukan petugas")

    new_assignment = models.Assignment(
        report_id=report_id,
        officer_id=assignment_data.officer_id,
        note=assignment_data.note
    )
    db.add(new_assignment)
    db.commit()
    return {"message": f"Laporan berhasil ditugaskan ke {officer.name}"}

@router.patch("/reports/{report_id}", response_model=models.ReportRead, summary="[Admin] 2. Validasi & Penugasan Petugas", description="Mengubah status laporan menjadi 'Verified' atau 'Spam' sekaligus menunjuk petugas pelaksana.")
def update_report(
    report_id: int,
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.admin]))],
    status: Annotated[Optional[str], Form()] = None,
    category_id: Annotated[Optional[int], Form()] = None,
    institution_id: Annotated[Optional[int], Form()] = None,
    officer_id: Annotated[Optional[int], Form()] = None,
    note: Annotated[Optional[str], Form()] = None
):
    report = db.get(models.Report, report_id)
    if not report:
        raise HTTPException(status_code=404, detail="Laporan tidak ditemukan")
    
    if status:
        report.status = status
    if category_id:
        report.category_id = category_id

    # Handle assignment if officer_id is provided
    if officer_id:
        # Check if already assigned to this officer
        statement = select(models.Assignment).where(
            models.Assignment.report_id == report_id,
            models.Assignment.officer_id == officer_id
        )
        existing_assignment = db.exec(statement).first()
        
        if not existing_assignment:
            new_assignment = models.Assignment(
                report_id=report_id,
                officer_id=officer_id,
                note=note or "Ditugaskan melalui update laporan"
            )
            db.add(new_assignment)
    
    report.updated_at = datetime.now()
    db.add(report)
    db.commit()
    db.refresh(report)
    return report

@router.post("/reports/{report_id}/progress", response_model=models.ReportRead, summary="[Petugas] 2. Update Progres Kerja", description="Memberikan catatan perkembangan pengerjaan di lapangan.")
async def add_work_progress(
    report_id: int,
    note: Annotated[str, Form()],
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.officer]))],
    image: Annotated[Optional[UploadFile], File()] = None
):
    report = db.get(models.Report, report_id)
    if not report:
        raise HTTPException(status_code=404, detail="Laporan tidak ditemukan")
    
    # Check if this officer is assigned to this report
    assignment = db.exec(
        select(models.Assignment).where(
            models.Assignment.report_id == report_id,
            models.Assignment.officer_id == current_user.id
        )
    ).first()
    
    if not assignment:
        raise HTTPException(status_code=403, detail="Anda tidak ditugaskan untuk laporan ini")

    # Update assignment note as progress
    assignment.note = note
    assignment.updated_at = datetime.now()

    # Simpan balasan petugas ke kolom officer_reply di laporan
    report.officer_reply = note
    
    # Handle optional resolution photo upload
    if image:
        import uuid
        file_ext = image.filename.split(".")[-1]
        file_name = f"res_{uuid.uuid4()}.{file_ext}"
        file_path = os.path.join(UPLOAD_DIR, "reports", file_name)
        with open(file_path, "wb") as f:
            f.write(await image.read())
        report.resolution_photo = file_name
    
    # Optionally update report status to in_progress if it was verified
    if report.status == models.ReportStatus.verified:
        report.status = models.ReportStatus.in_progress
    
    db.add(assignment)
    db.add(report)
    db.commit()
    db.refresh(report)
    return report

@router.post("/reports/{report_id}/feedback", response_model=models.FeedbackRead, tags=["Feedbacks"], summary="[Pelapor] 3. Memberi Ulasan/Rating", description="Setelah laporan berstatus 'RESOLVED', warga bisa memberikan penilaian kepuasan.")
def create_feedback(
    report_id: int,
    feedback_data: models.FeedbackCreate,
    db: Annotated[Session, Depends(get_db)],
    current_user: Annotated[models.User, Depends(auth_utils.check_role([models.UserRole.citizen]))]
):
    report = db.get(models.Report, report_id)
    if not report or report.status != models.ReportStatus.resolved:
        raise HTTPException(status_code=400, detail="Hanya untuk laporan selesai")

    new_feedback = models.Feedback(
        report_id=report_id,
        user_id=current_user.id,
        content=feedback_data.content,
        rating=feedback_data.rating
    )
    db.add(new_feedback)
    db.commit()
    db.refresh(new_feedback)
    return new_feedback
