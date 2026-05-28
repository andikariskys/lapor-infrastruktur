import io
import os
from PIL import Image
from fastapi import UploadFile

# Determine the best resample filter based on Pillow version
try:
    resample_filter = Image.Resampling.LANCZOS
except AttributeError:
    resample_filter = Image.ANTIALIAS

async def compress_and_save_image(upload_file: UploadFile, target_path: str, max_size=(800, 800), quality=75):
    """
    Compresses and saves an uploaded image.
    If the image exceeds max_size, it will be scaled down preserving aspect ratio.
    If it is not a valid image, it falls back to saving the raw binary.
    """
    try:
        # Reset file pointer to the beginning
        await upload_file.seek(0)
        file_content = await upload_file.read()
        
        # Attempt to open as PIL Image
        img = Image.open(io.BytesIO(file_content))
        
        # Convert RGBA/Palette to RGB to support JPEG conversion if needed
        if img.mode in ("RGBA", "P"):
            img = img.convert("RGB")
            
        # Resize maintaining aspect ratio if larger than max_size
        img.thumbnail(max_size, resample_filter)
        
        # Determine the format based on file extension
        ext = target_path.split(".")[-1].lower()
        if ext == "png":
            img.save(target_path, format="PNG", optimize=True)
        else:
            img.save(target_path, format="JPEG", quality=quality, optimize=True)
            
    except Exception as e:
        # Fallback to saving raw binary data
        await upload_file.seek(0)
        with open(target_path, "wb") as f:
            f.write(await upload_file.read())
