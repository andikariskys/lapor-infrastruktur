from sqlmodel import SQLModel, Field
from typing import Optional
from datetime import datetime

class User(SQLModel, table=True):
    __tablename__ = "users"
    id: Optional[int] = Field(default=None, primary_key=True)
    username: str = Field(unique=True, index=True)
    password: str
    name: str
    email: str = Field(unique=True, index=True)
    role: str = Field(default="citizen")
    created_at: datetime = Field(default_factory=datetime.now)
