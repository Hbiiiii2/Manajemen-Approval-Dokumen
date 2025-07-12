# API Documentation - Sistem Manajemen Approval Dokumen

## Overview
Dokumentasi API untuk sistem manajemen approval dokumen berbasis Laravel. API ini menyediakan endpoint untuk mengelola dokumen, approval, user, dan profile.

## Base URL
```
http://localhost:8000/api
```

## Authentication
API menggunakan Laravel Sanctum untuk authentication. Semua request (kecuali login/register) memerlukan Bearer token.

### Headers
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

## Endpoints

### Authentication

#### POST /login
Login user dan mendapatkan access token.

**Request:**
```json
{
    "email": "admin@softui.com",
    "password": "password"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@softui.com",
            "role": "admin"
        },
        "token": "1|abc123..."
    },
    "message": "Login berhasil"
}
```

#### POST /register
Register user baru.

**Request:**
```json
{
    "name": "New User",
    "email": "newuser@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 5,
            "name": "New User",
            "email": "newuser@example.com",
            "role": "staff"
        },
        "token": "2|def456..."
    },
    "message": "Register berhasil"
}
```

#### POST /logout
Logout user dan invalidate token.

**Response:**
```json
{
    "success": true,
    "message": "Logout berhasil"
}
```

### Documents

#### GET /documents
Mendapatkan daftar dokumen sesuai role user.

**Query Parameters:**
- `page` (optional): Halaman pagination
- `status` (optional): Filter by status (pending, approved, rejected)
- `type` (optional): Filter by document type
- `search` (optional): Search by title

**Response:**
```json
{
    "success": true,
    "data": {
        "documents": [
            {
                "id": 1,
                "title": "Surat Permohonan Cuti",
                "description": "Permohonan cuti tahunan",
                "file_path": "documents/file.pdf",
                "status": "pending",
                "type": "Surat Permohonan",
                "user": {
                    "id": 2,
                    "name": "Staff User"
                },
                "created_at": "2024-07-12T10:00:00Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 5,
            "per_page": 10,
            "total": 50
        }
    }
}
```

#### POST /documents
Membuat dokumen baru.

**Request:**
```json
{
    "title": "Laporan Keuangan Q3",
    "description": "Laporan keuangan triwulan ketiga",
    "document_type_id": 2,
    "file": "file_upload"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "document": {
            "id": 6,
            "title": "Laporan Keuangan Q3",
            "description": "Laporan keuangan triwulan ketiga",
            "file_path": "documents/laporan_q3.pdf",
            "status": "pending",
            "type": "Laporan Keuangan",
            "user": {
                "id": 2,
                "name": "Staff User"
            },
            "created_at": "2024-07-12T11:00:00Z"
        }
    },
    "message": "Dokumen berhasil dibuat"
}
```

#### GET /documents/{id}
Mendapatkan detail dokumen.

**Response:**
```json
{
    "success": true,
    "data": {
        "document": {
            "id": 1,
            "title": "Surat Permohonan Cuti",
            "description": "Permohonan cuti tahunan",
            "file_path": "documents/file.pdf",
            "status": "pending",
            "type": "Surat Permohonan",
            "user": {
                "id": 2,
                "name": "Staff User"
            },
            "approvals": [
                {
                    "id": 1,
                    "level": "Manager",
                    "status": "pending",
                    "notes": null,
                    "approver": null,
                    "created_at": "2024-07-12T10:00:00Z"
                }
            ],
            "created_at": "2024-07-12T10:00:00Z",
            "updated_at": "2024-07-12T10:00:00Z"
        }
    }
}
```

### Approvals

#### GET /approvals
Mendapatkan daftar approval yang perlu diproses.

**Query Parameters:**
- `page` (optional): Halaman pagination
- `status` (optional): Filter by status (pending, approved, rejected)

**Response:**
```json
{
    "success": true,
    "data": {
        "approvals": [
            {
                "id": 1,
                "document": {
                    "id": 1,
                    "title": "Surat Permohonan Cuti",
                    "description": "Permohonan cuti tahunan"
                },
                "level": "Manager",
                "status": "pending",
                "notes": null,
                "approver": null,
                "created_at": "2024-07-12T10:00:00Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 3,
            "per_page": 10,
            "total": 25
        }
    }
}
```

#### POST /approvals/{document}/approve
Approve dokumen.

**Request:**
```json
{
    "notes": "Dokumen sudah sesuai dengan prosedur"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Dokumen berhasil diapprove"
}
```

#### POST /approvals/{document}/reject
Reject dokumen.

**Request:**
```json
{
    "notes": "Dokumen tidak sesuai dengan prosedur"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Dokumen berhasil direject"
}
```

### Profile

#### GET /profile
Mendapatkan informasi profile user.

**Response:**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@softui.com",
            "role": "admin",
            "profile_photo": "profile-photos/admin.jpg",
            "created_at": "2024-07-12T09:00:00Z"
        }
    }
}
```

#### POST /profile/update
Update informasi profile.

**Request:**
```json
{
    "name": "Updated Name",
    "email": "updated@example.com"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Profile berhasil diupdate"
}
```

#### POST /profile/password
Ganti password.

**Request:**
```json
{
    "current_password": "oldpassword",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Password berhasil diubah"
}
```

#### POST /profile/photo
Upload foto profile.

**Request:**
```
Content-Type: multipart/form-data
photo: file_upload
```

**Response:**
```json
{
    "success": true,
    "message": "Foto profile berhasil diupdate"
}
```

### User Management (Admin Only)

#### GET /users
Mendapatkan daftar semua user (Admin only).

**Query Parameters:**
- `page` (optional): Halaman pagination
- `role` (optional): Filter by role
- `search` (optional): Search by name or email

**Response:**
```json
{
    "success": true,
    "data": {
        "users": [
            {
                "id": 1,
                "name": "Admin User",
                "email": "admin@softui.com",
                "role": "admin",
                "created_at": "2024-07-12T09:00:00Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 2,
            "per_page": 10,
            "total": 15
        }
    }
}
```

#### POST /users
Membuat user baru (Admin only).

**Request:**
```json
{
    "name": "New User",
    "email": "newuser@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role_id": 1
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 6,
            "name": "New User",
            "email": "newuser@example.com",
            "role": "staff",
            "created_at": "2024-07-12T12:00:00Z"
        }
    },
    "message": "User berhasil dibuat"
}
```

#### PUT /users/{id}
Update user (Admin only).

**Request:**
```json
{
    "name": "Updated User",
    "email": "updated@example.com",
    "role_id": 2
}
```

**Response:**
```json
{
    "success": true,
    "message": "User berhasil diupdate"
}
```

#### DELETE /users/{id}
Hapus user (Admin only).

**Response:**
```json
{
    "success": true,
    "message": "User berhasil dihapus"
}
```

### Dashboard

#### GET /dashboard
Mendapatkan statistik dashboard.

**Response:**
```json
{
    "success": true,
    "data": {
        "statistics": {
            "total_documents": 50,
            "pending_documents": 15,
            "approved_documents": 25,
            "rejected_documents": 10,
            "total_users": 15,
            "recent_activities": [
                {
                    "id": 1,
                    "action": "Document Created",
                    "description": "Surat Permohonan Cuti created by Staff User",
                    "created_at": "2024-07-12T11:00:00Z"
                }
            ]
        }
    }
}
```

## Error Responses

### Validation Error
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["Email sudah digunakan"],
        "password": ["Password minimal 8 karakter"]
    }
}
```

### Authentication Error
```json
{
    "success": false,
    "message": "Unauthenticated"
}
```

### Authorization Error
```json
{
    "success": false,
    "message": "Unauthorized"
}
```

### Not Found Error
```json
{
    "success": false,
    "message": "Resource not found"
}
```

### Server Error
```json
{
    "success": false,
    "message": "Internal server error"
}
```

## Rate Limiting

API menggunakan rate limiting untuk mencegah abuse:

- **Authentication endpoints**: 5 requests per minute
- **Other endpoints**: 60 requests per minute

## File Upload

### Supported Formats
- **Documents**: PDF, DOC, DOCX, XLS, XLSX
- **Images**: JPG, PNG, GIF

### File Size Limits
- **Documents**: Maximum 10MB
- **Profile Photos**: Maximum 2MB

## Testing

### Postman Collection
Import collection berikut ke Postman:
```json
{
    "info": {
        "name": "Sistem Manajemen Approval Dokumen API",
        "description": "API collection untuk testing"
    },
    "item": [
        {
            "name": "Authentication",
            "item": [
                {
                    "name": "Login",
                    "request": {
                        "method": "POST",
                        "url": "{{base_url}}/login",
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"email\": \"admin@softui.com\",\n    \"password\": \"password\"\n}",
                            "options": {
                                "raw": {
                                    "language": "json"
                                }
                            }
                        }
                    }
                }
            ]
        }
    ]
}
```

### cURL Examples

#### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@softui.com",
    "password": "password"
  }'
```

#### Get Documents (with token)
```bash
curl -X GET http://localhost:8000/api/documents \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

## Versioning

API menggunakan semantic versioning:
- **v1**: Current stable version
- Future versions will be available at `/api/v2/`

## Support

Untuk bantuan teknis atau pertanyaan tentang API:
- **Developer**: [Hbiiiii2](https://github.com/Hbiiiii2)
- **Email**: [hbiiiii2@gmail.com](mailto:hbiiiii2@gmail.com)
- **Repository**: [Manajemen-Approval-Dokumen](https://github.com/Hbiiiii2/Manajemen-Approval-Dokumen) 