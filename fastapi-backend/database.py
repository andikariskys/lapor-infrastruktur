from sqlmodel import create_engine, Session
import os
from dotenv import load_dotenv

# Load environment variables
load_dotenv()

db_type = os.getenv("DB_TYPE")
db_host = os.getenv("DB_HOST")
db_user = os.getenv("DB_USERNAME")
db_pass = os.getenv("DB_PASSWORD")
db_name = os.getenv("DB_NAME")

# Pastikan variabel wajib ada (minimal db_host dan db_name)
if not db_host or not db_name:
    raise RuntimeError("Konfigurasi Database (DB_HOST, DB_NAME) tidak ditemukan di .env")
    
database_url = f"{db_type}://{db_user}:{db_pass}@{db_host}/{db_name}"

engine = create_engine(database_url)

def get_db():
    with Session(engine) as session:
        yield session

# Directory untuk upload
UPLOAD_DIR = "uploads"
os.makedirs(UPLOAD_DIR, exist_ok=True)
os.makedirs(os.path.join(UPLOAD_DIR, "profiles"), exist_ok=True)
os.makedirs(os.path.join(UPLOAD_DIR, "reports"), exist_ok=True)
os.makedirs(os.path.join(UPLOAD_DIR, "institutions"), exist_ok=True)

# Schema Migration: Add missing columns
from sqlalchemy import text

def check_and_add_column(table: str, column: str, column_def: str):
    """Cek dan tambahkan kolom jika belum ada di tabel."""
    try:
        with Session(engine) as session:
            session.execute(text(f"SELECT {column} FROM {table} LIMIT 1"))
    except Exception:
        try:
            with Session(engine) as session:
                session.execute(text(f"ALTER TABLE {table} ADD COLUMN {column} {column_def}"))
                session.commit()
                print(f"Column '{column}' successfully added to '{table}' table.")
        except Exception as e:
            print(f"Error migrating database ({table}.{column}): {e}")

check_and_add_column("reports", "resolution_photo", "VARCHAR(255) NULL")
check_and_add_column("reports", "officer_reply", "TEXT NULL")
