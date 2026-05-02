from sqlmodel import create_engine, Session
import os
from dotenv import load_dotenv

# Load environment variables
load_dotenv()

# Ambil URL Database dari .env
DATABASE_URL = os.getenv("DATABASE_URL")

if not DATABASE_URL:
    raise RuntimeError("DATABASE_URL tidak ditemukan di .env")

engine = create_engine(DATABASE_URL)

def get_db():
    with Session(engine) as session:
        yield session

# Directory untuk upload
UPLOAD_DIR = "uploads"
if not os.path.exists(UPLOAD_DIR):
    os.makedirs(UPLOAD_DIR)
