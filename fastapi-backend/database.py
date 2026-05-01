import os
from sqlmodel import create_engine, Session, SQLModel
from dotenv import load_dotenv

load_dotenv()

# Gunakan database MySQL Anda
DATABASE_URL = os.getenv("DATABASE_URL", "mysql+pymysql://root:@localhost/lapor_infrastruktur")

engine = create_engine(DATABASE_URL)

def get_db():
    with Session(engine) as session:
        yield session
