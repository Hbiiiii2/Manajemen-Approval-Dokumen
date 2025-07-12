# Sistem Manajemen Approval Dokumen Laravel

![version](https://img.shields.io/badge/version-1.0.0-blue.svg) 
![license](https://img.shields.io/badge/license-MIT-blue.svg)
[![GitHub issues open](https://img.shields.io/github/issues/creativetimofficial/soft-ui-dashboard-laravel.svg)](https://github.com/creativetimofficial/soft-ui-dashboard-laravel/issues?q=is%3Aopen+is%3Aissue) 
[![GitHub issues closed](https://img.shields.io/github/issues-closed-raw/creativetimofficial/soft-ui-dashboard-laravel.svg)](https://github.com/creativetimofficial/soft-ui-dashboard-laravel/issues?q=is%3Aissue+is%3Aclosed)

*Frontend version*: Soft UI Dashboard v1.0.0. More info at https://www.creative-tim.com/product/soft-ui-dashboard

[<img src="https://s3.amazonaws.com/creativetim_bucket/products/602/original/soft-ui-dashboard-laravel.jpg" width="100%" />](https://soft-ui-dashboard-laravel.creative-tim.com/dashboard)
  
## Sistem Manajemen Approval Dokumen Berbasis Laravel
Sistem manajemen dokumen yang dikembangkan dengan Laravel dan Soft UI Dashboard untuk mengelola pengajuan, approval, dan tracking dokumen dalam organisasi.

**Developer**: [Hbiiiii2](https://github.com/Hbiiiii2)

## Fitur Utama

### 🔐 **Sistem Autentikasi & Role Management**
- Login/Register dengan validasi
- Role-based access control (Staff, Manager, Section Head, Admin)
- Forgot/Reset Password
- Profile management dengan upload foto

### 📄 **Manajemen Dokumen**
- Upload dokumen dengan berbagai format (PDF, DOC, DOCX, XLS, XLSX)
- Kategorisasi dokumen berdasarkan tipe
- Tracking status dokumen (Pending, Approved, Rejected)
- Dashboard dengan statistik real-time

### ✅ **Sistem Approval**
- Multi-level approval (Manager → Section Head)
- Approval history dengan catatan
- Notifikasi status approval
- Role-based approval workflow

### 👥 **User Management (Admin)**
- CRUD operasi untuk user
- Role assignment
- Password management
- User profile management

### 👤 **Profile Management**
- Edit informasi profile
- Upload foto profile
- Ganti password
- View profile history

## Role dan Permissions

| Role | Dokumen | Approval | User Management | Profile |
|------|---------|----------|-----------------|---------|
| **Staff** | ✅ Create, View Own | ❌ | ❌ | ✅ Own |
| **Manager** | ✅ Create, View All | ✅ Approve | ❌ | ✅ Own |
| **Section Head** | ✅ View All | ✅ Approve | ❌ | ✅ Own |
| **Admin** | ✅ View All | ✅ Approve | ✅ Full Access | ✅ Own |

## Prerequisites

Jika Anda belum memiliki environment Apache dengan PHP dan MySQL, gunakan link berikut:

-   Windows: https://updivision.com/blog/post/beginner-s-guide-to-setting-up-your-local-development-environment-on-windows
-   Linux & Mac: https://updivision.com/blog/post/guide-what-is-lamp-and-how-to-install-it-on-ubuntu-and-macos

Anda juga perlu menginstall:
- Composer: https://getcomposer.org/doc/00-intro.md  
- Laravel: https://laravel.com/docs/10.x

## Installation

1. Clone atau unzip repository ini
2. Copy folder ke direktori **projects** Anda dan rename sesuai nama project
3. Jalankan `composer install` di terminal
4. Copy `.env.example` ke `.env` dan update konfigurasi database
5. Jalankan `php artisan key:generate`
6. Jalankan `php artisan migrate --seed` untuk membuat tabel dan data awal
7. Jalankan `php artisan storage:link` untuk membuat symlink storage

## Default Users

Setelah menjalankan seeder, Anda dapat login dengan akun berikut:

| Email | Password | Role | Akses |
|-------|----------|------|-------|
| admin@softui.com | password | Admin | Full Access |
| staff@softui.com | password | Staff | Create Documents |
| manager@softui.com | password | Manager | Create & Approve |
| section_head@softui.com | password | Section Head | Approve Only |

## Usage

### Dashboard
- **Staff**: Melihat statistik dokumen pribadi
- **Manager/Section Head/Admin**: Melihat statistik semua dokumen dan approval yang perlu diproses

### Dokumen Management
1. **Create Document**: Upload dokumen dengan informasi lengkap
2. **View Documents**: Lihat daftar dokumen sesuai role
3. **Document Details**: Lihat detail dan status approval dokumen

### Approval Process
1. **Manager**: Approve dokumen level pertama
2. **Section Head**: Approve dokumen level kedua
3. **Status Update**: Otomatis update status dokumen

### User Management (Admin Only)
1. **List Users**: Lihat semua user dalam sistem
2. **Add User**: Tambah user baru dengan role
3. **Edit User**: Update informasi user
4. **Delete User**: Hapus user (dengan proteksi)

### Profile Management
1. **View Profile**: Lihat informasi profile
2. **Edit Profile**: Update informasi pribadi
3. **Change Password**: Ganti password dengan validasi
4. **Upload Photo**: Upload foto profile

## File Structure

```
app/
├── Http/Controllers/
│   ├── DashboardController.php      # Dashboard logic
│   ├── DocumentController.php       # Document CRUD
│   ├── ApprovalController.php       # Approval process
│   ├── HomeController.php          # Home page
│   ├── ProfileController.php       # Profile management
│   └── User Management Controllers
├── Models/
│   ├── User.php                    # User model
│   ├── Document.php                # Document model
│   ├── DocumentType.php            # Document type model
│   ├── Approval.php                # Approval model
│   ├── ApprovalLevel.php           # Approval level model
│   └── Role.php                    # Role model
├── Middleware/
│   └── AdminMiddleware.php         # Admin access control
database/
├── migrations/                     # Database migrations
├── seeders/                       # Data seeders
└── factories/                     # Model factories
resources/views/
├── dashboard.blade.php            # Dashboard view
├── documents/                     # Document views
├── approvals/                     # Approval views
├── profile.blade.php              # Profile view
└── laravel-examples/             # User management views
routes/
└── web.php                       # Application routes
```

## Database Schema

### Core Tables
- **users**: User information dan authentication
- **roles**: Role definitions (staff, manager, section_head, admin)
- **documents**: Document storage dan metadata
- **document_types**: Document categories
- **approvals**: Approval records dan history
- **approval_levels**: Approval level definitions

### Relationships
- User → Role (belongsTo)
- User → Documents (hasMany)
- User → Approvals (hasMany)
- Document → User (belongsTo)
- Document → DocumentType (belongsTo)
- Document → Approvals (hasMany)
- Approval → User (belongsTo)
- Approval → Document (belongsTo)
- Approval → ApprovalLevel (belongsTo)

## API Endpoints

### Authentication
- `POST /login` - User login
- `POST /register` - User registration
- `POST /logout` - User logout
- `POST /forgot-password` - Forgot password
- `POST /reset-password` - Reset password

### Documents
- `GET /documents` - List documents
- `POST /documents` - Create document
- `GET /documents/{id}` - Show document
- `POST /documents/{id}/approve` - Approve document

### Approvals
- `GET /approvals` - List approvals
- `POST /approvals/{document}/approve` - Approve document
- `POST /approvals/{document}/reject` - Reject document

### Profile
- `GET /profile` - Show profile
- `POST /profile/update` - Update profile
- `POST /profile/password` - Change password
- `POST /profile/photo` - Upload photo

### User Management (Admin)
- `GET /user-management` - List users
- `POST /user-management` - Create user
- `PUT /user-management/{id}` - Update user
- `DELETE /user-management/{id}` - Delete user

## Browser Support
Mendukung 2 versi terakhir dari browser berikut:

<img src="https://s3.amazonaws.com/creativetim_bucket/github/browser/chrome.png" width="64" height="64"> <img src="https://s3.amazonaws.com/creativetim_bucket/github/browser/firefox.png" width="64" height="64"> <img src="https://s3.amazonaws.com/creativetim_bucket/github/browser/edge.png" width="64" height="64"> <img src="https://s3.amazonaws.com/creativetim_bucket/github/browser/safari.png" width="64" height="64"> <img src="https://s3.amazonaws.com/creativetim_bucket/github/browser/opera.png" width="64" height="64">

## Development

### Menjalankan Development Server
```bash
php artisan serve
```

### Menjalankan Migration
```bash
php artisan migrate
```

### Menjalankan Seeder
```bash
php artisan db:seed
```

### Menjalankan Tests
```bash
php artisan test
```

## Dokumentasi Lengkap

- [User Management Documentation](README_USER_MANAGEMENT.md)
- [Profile Feature Documentation](README_PROFILE.md)
- [Seeder Documentation](README_SEEDERS.md)

## Reporting Issues
Kami menggunakan GitHub Issues untuk bug tracking. Beberapa saran untuk melaporkan issue:

1. Pastikan Anda menggunakan versi terbaru
2. Berikan langkah-langkah yang dapat direproduksi
3. Sebutkan browser yang digunakan jika relevan

## Licensing
- Copyright 2024 [Hbiiiii2](https://github.com/Hbiiiii2)
- MIT License

## Credits

- [Hbiiiii2](https://github.com/Hbiiiii2) - Developer & Maintainer
- [Creative Tim](https://creative-tim.com/?ref=sudl-readme) - UI Components (Soft UI Dashboard)
