from sqlmodel import SQLModel, Field, Relationship
from typing import Optional, List
from datetime import datetime
from enum import Enum as PyEnum
from pydantic import field_validator

# --- Enum Definitions ---

class UserRole(str, PyEnum):
    admin = "admin"
    officer = "officer"
    citizen = "citizen"

class ReportStatus(str, PyEnum):
    pending = "pending"
    verified = "verified"
    in_progress = "in_progress"
    resolved = "resolved"
    spam = "spam"

# --- Institution Model ---

class InstitutionBase(SQLModel):
    name: str
    description: Optional[str] = None
    address: str
    phone: str
    email: str = Field(unique=True)
    profile_photo: Optional[str] = None

class Institution(InstitutionBase, table=True):
    __tablename__ = "institution"
    id: Optional[int] = Field(default=None, primary_key=True)
    created_at: datetime = Field(default_factory=datetime.now)
    updated_at: datetime = Field(default_factory=datetime.now)
    
    users: List["User"] = Relationship(back_populates="institution")

class InstitutionRead(InstitutionBase):
    id: int
    created_at: Optional[datetime] = None

    @field_validator("profile_photo", mode="before")
    @classmethod
    def format_profile_photo(cls, v: Optional[str]) -> Optional[str]:
        if v:
            if v.startswith("/uploads/"):
                return f"/api/uploads/{v.replace('/uploads/', '')}" 
            elif not v.startswith("/"):
                return f"/api/uploads/institutions/{v}"
        return v

# --- User Model ---

class UserBase(SQLModel):
    name: str
    email: str = Field(unique=True, index=True)
    phone: str
    role: UserRole = Field(default=UserRole.citizen)
    profile_photo: Optional[str] = None
    institution_id: Optional[int] = Field(default=None, foreign_key="institution.id")

class User(UserBase, table=True):
    __tablename__ = "users"
    id: Optional[int] = Field(default=None, primary_key=True)
    password: str
    created_at: datetime = Field(default_factory=datetime.now)
    updated_at: datetime = Field(default_factory=datetime.now)
    
    institution: Optional[Institution] = Relationship(back_populates="users")
    reports: List["Report"] = Relationship(back_populates="author")
    feedbacks: List["Feedback"] = Relationship(back_populates="user")
    assignments: List["Assignment"] = Relationship(back_populates="officer")

class UserCreate(UserBase):
    password: str

class UserRead(UserBase):
    id: int
    created_at: datetime
    institution: Optional["InstitutionRead"] = None

    @field_validator("profile_photo", mode="before")
    @classmethod
    def format_profile_photo(cls, v: Optional[str]) -> Optional[str]:
        if v:
            if v.startswith("/uploads/"):
                return f"/api/uploads/{v.replace('/uploads/', '')}" 
            elif not v.startswith("/"):
                return f"/api/uploads/profiles/{v}"
        return v

class UserUpdate(SQLModel):
    name: Optional[str] = None
    phone: Optional[str] = None
    profile_photo: Optional[str] = None

class PasswordChange(SQLModel):
    old_password: str
    new_password: str

class PasswordReset(SQLModel):
    new_password: str

class ForgotPasswordRequest(SQLModel):
    email: str

class ResetPasswordRequest(SQLModel):
    token: str
    new_password: str

class Token(SQLModel):
    access_token: str
    token_type: str

# --- Category Model ---

class CategoryBase(SQLModel):
    name: str
    description: Optional[str] = None
    color_code: Optional[str] = Field(default=None, max_length=7)

class Category(CategoryBase, table=True):
    __tablename__ = "categories"
    id: Optional[int] = Field(default=None, primary_key=True)
    created_at: datetime = Field(default_factory=datetime.now)
    updated_at: datetime = Field(default_factory=datetime.now)
    
    reports: List["Report"] = Relationship(back_populates="category")

# --- Report Model ---

class ReportBase(SQLModel):
    user_id: int = Field(foreign_key="users.id")
    category_id: Optional[int] = Field(default=None, foreign_key="categories.id")
    description: str
    photo_url: str
    latitude: float
    longitude: float
    status: ReportStatus = Field(default=ReportStatus.pending)
    resolution_photo: Optional[str] = None

class Report(ReportBase, table=True):
    __tablename__ = "reports"
    id: Optional[int] = Field(default=None, primary_key=True)
    created_at: datetime = Field(default_factory=datetime.now)
    updated_at: datetime = Field(default_factory=datetime.now)
    
    author: User = Relationship(back_populates="reports")
    category: Optional[Category] = Relationship(back_populates="reports")
    assignments: List["Assignment"] = Relationship(back_populates="report")
    feedbacks: List["Feedback"] = Relationship(back_populates="report")

# --- Feedback Model ---

class FeedbackBase(SQLModel):
    report_id: int = Field(foreign_key="reports.id")
    user_id: int = Field(foreign_key="users.id")
    content: str
    rating: int = Field(ge=1, le=5)

class Feedback(FeedbackBase, table=True):
    __tablename__ = "feedbacks"
    id: Optional[int] = Field(default=None, primary_key=True)
    created_at: datetime = Field(default_factory=datetime.now)
    updated_at: datetime = Field(default_factory=datetime.now)
    
    report: "Report" = Relationship(back_populates="feedbacks")
    user: "User" = Relationship(back_populates="feedbacks")

class FeedbackCreate(SQLModel):
    content: str
    rating: int = Field(ge=1, le=5)

class FeedbackRead(FeedbackBase):
    id: int
    created_at: datetime
    user: Optional[UserRead] = None

# --- Report Schemas ---

class ReportCreate(SQLModel):
    category_id: Optional[int] = None
    description: str
    latitude: float
    longitude: float

class ReportRead(ReportBase):
    id: int
    created_at: datetime
    author: Optional[UserRead] = None
    category: Optional[CategoryBase] = None
    assignments: List["AssignmentRead"] = []
    feedbacks: List[FeedbackRead] = []

    @field_validator("photo_url", mode="before")
    @classmethod
    def format_photo_url(cls, v: Optional[str]) -> Optional[str]:
        if v:
            if v.startswith("/uploads/"):
                return f"/api/uploads/{v.replace('/uploads/', '')}" 
            elif not v.startswith("/"):
                return f"/api/uploads/reports/{v}"
        return v

    @field_validator("resolution_photo", mode="before")
    @classmethod
    def format_resolution_photo(cls, v: Optional[str]) -> Optional[str]:
        if v:
            if v.startswith("/uploads/"):
                return f"/api/uploads/{v.replace('/uploads/', '')}" 
            elif not v.startswith("/"):
                return f"/api/uploads/reports/{v}"
        return v

class ReportUpdateStatus(SQLModel):
    status: ReportStatus

# --- Assignment Model ---

class AssignmentBase(SQLModel):
    report_id: int = Field(foreign_key="reports.id")
    officer_id: int = Field(foreign_key="users.id")
    note: Optional[str] = None

class AssignmentRead(AssignmentBase):
    id: int
    assigned_at: datetime
    officer: Optional[UserRead] = None

class Assignment(AssignmentBase, table=True):
    __tablename__ = "assignments"
    id: Optional[int] = Field(default=None, primary_key=True)
    assigned_at: datetime = Field(default_factory=datetime.now)
    updated_at: datetime = Field(default_factory=datetime.now)
    
    report: Report = Relationship(back_populates="assignments")
    officer: User = Relationship(back_populates="assignments")



class AssignmentCreate(SQLModel):
    report_id: int
    officer_id: int
    note: Optional[str] = None
