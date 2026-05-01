from fastapi import FastAPI
import bcrypt

app = FastAPI()

@app.get("/")
async def root():
    return {"message": "Selamat datang di Lapor Infrastruktur API!"}

# Contoh endpoint untuk hashing password menggunakan bcrypt
@app.get("/hash-password/")
async def hash_password():
    # Anggap saja kita memiliki password yang ingin di-hash
    password = "password@123"
    # Hash password menggunakan bcrypt
    hashed_password = bcrypt.hashpw(password.encode('utf-8'), bcrypt.gensalt())
    hashed_password = hashed_password.decode('utf-8')  # Hasil hash ini simpan ke database
    # Verifikasi password (contoh)
    is_valid = bcrypt.checkpw(password.encode('utf-8'), hashed_password.encode('utf-8'))
    return {"password": password, "hashed_password": hashed_password, "is_valid": is_valid}