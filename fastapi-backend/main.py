from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from fastapi.staticfiles import StaticFiles
from database import engine, UPLOAD_DIR
from routers import auth, users, reports, categories, institutions, officer
import models

# Inisialisasi Database
models.SQLModel.metadata.create_all(engine)

app = FastAPI(
    title="Lapor Infrastruktur API",
    description="API untuk sistem pelaporan kerusakan infrastruktur",
    version="1.1.0"
)

# Konfigurasi CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Serve file upload statis
app.mount("/api/uploads", StaticFiles(directory=UPLOAD_DIR), name="uploads")

# Mendaftarkan Router (Include Routers)
app.include_router(auth.router)
app.include_router(users.router)
app.include_router(reports.router)
app.include_router(categories.router)
app.include_router(institutions.router)
app.include_router(officer.router)

@app.get("/", tags=["General"])
async def root():
    return {"message": "Selamat datang di Lapor Infrastruktur API!"}
