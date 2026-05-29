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

def migrate_resolution_to_completion_photo():
    """Migrasi aman untuk mengubah nama kolom resolution_photo menjadi completion_photo."""
    try:
        # Check if resolution_photo exists
        has_resolution_photo = False
        try:
            with Session(engine) as session:
                session.execute(text("SELECT resolution_photo FROM reports LIMIT 1"))
                has_resolution_photo = True
        except Exception:
            pass

        # Check if completion_photo exists
        has_completion_photo = False
        try:
            with Session(engine) as session:
                session.execute(text("SELECT completion_photo FROM reports LIMIT 1"))
                has_completion_photo = True
        except Exception:
            pass

        if has_resolution_photo and not has_completion_photo:
            with Session(engine) as session:
                session.execute(text("ALTER TABLE reports CHANGE COLUMN resolution_photo completion_photo VARCHAR(255) NULL"))
                session.commit()
                print("Column 'resolution_photo' successfully renamed to 'completion_photo'.")
        elif not has_completion_photo:
            with Session(engine) as session:
                session.execute(text("ALTER TABLE reports ADD COLUMN completion_photo VARCHAR(255) NULL"))
                session.commit()
                print("Column 'completion_photo' successfully added to 'reports' table.")
    except Exception as e:
        print(f"Error migrating resolution_photo to completion_photo: {e}")

migrate_resolution_to_completion_photo()
check_and_add_column("reports", "officer_reply", "TEXT NULL")

