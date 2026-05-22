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
if not os.path.exists(UPLOAD_DIR):
    os.makedirs(UPLOAD_DIR)
