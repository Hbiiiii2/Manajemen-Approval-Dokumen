# Dokumentasi Seeder - Sistem Manajemen Approval Dokumen

## Overview
Dokumentasi ini menjelaskan semua seeder yang digunakan dalam sistem manajemen approval dokumen. Seeder digunakan untuk mengisi database dengan data awal yang diperlukan untuk testing dan development.

## Daftar Seeder

### 1. DatabaseSeeder.php
**File**: `database/seeders/DatabaseSeeder.php`

Seeder utama yang menjalankan semua seeder lainnya secara berurutan.

**Urutan Eksekusi:**
1. RoleSeeder
2. ApprovalLevelSeeder  
3. UserSeeder
4. DocumentTypeSeeder
5. DocumentSeeder
6. ApprovalSeeder

**Cara Menjalankan:**
```bash
php artisan db:seed
```

### 2. RoleSeeder.php
**File**: `database/seeders/RoleSeeder.php`

Membuat role dasar untuk sistem.

**Data yang Dibuat:**
- `staff` - Role untuk staff yang hanya bisa membuat dokumen
- `manager` - Role untuk manager yang bisa membuat dan approve dokumen
- `section_head` - Role untuk section head yang hanya bisa approve dokumen
- `admin` - Role untuk admin yang bisa mengelola semua fitur termasuk user

**Cara Menjalankan:**
```bash
php artisan db:seed --class=RoleSeeder
```

### 3. ApprovalLevelSeeder.php
**File**: `database/seeders/ApprovalLevelSeeder.php`

Membuat level approval untuk sistem.

**Data yang Dibuat:**
- `Manager` (level 1) - Level approval manager
- `Section Head` (level 2) - Level approval section head

**Cara Menjalankan:**
```bash
php artisan db:seed --class=ApprovalLevelSeeder
```

### 4. UserSeeder.php
**File**: `database/seeders/UserSeeder.php`

Membuat user default untuk setiap role.

**Data yang Dibuat:**

| Email | Password | Role | Keterangan |
|-------|----------|------|------------|
| admin@softui.com | password | Admin | Super admin dengan akses penuh |
| staff@softui.com | password | Staff | Staff yang hanya bisa buat dokumen |
| manager@softui.com | password | Manager | Manager yang bisa buat dan approve |
| sectionhead@softui.com | password | Section Head | Section head yang hanya bisa approve |

**Cara Menjalankan:**
```bash
php artisan db:seed --class=UserSeeder
```

### 5. DocumentTypeSeeder.php
**File**: `database/seeders/DocumentTypeSeeder.php`

Membuat tipe dokumen yang tersedia dalam sistem.

**Data yang Dibuat:**
- Surat Permohonan
- Laporan Keuangan
- Proposal Bisnis
- Kontrak Kerja
- Laporan Aktivitas

**Cara Menjalankan:**
```bash
php artisan db:seed --class=DocumentTypeSeeder
```

### 6. DocumentSeeder.php
**File**: `database/seeders/DocumentSeeder.php`

Membuat dokumen sample dengan data yang realistis.

**Data yang Dibuat:**
1. Surat Permohonan Cuti Tahunan (pending)
2. Laporan Keuangan Q2 2024 (approved)
3. Proposal Pengembangan Sistem (pending)
4. Kontrak Kerja Karyawan Baru (approved)
5. Laporan Aktivitas Bulanan (rejected)
6. Surat Permohonan Reimbursement (pending)
7. Laporan Audit Internal (approved)
8. Proposal Budget 2025 (pending)
9. Kontrak Vendor (approved)
10. Laporan Evaluasi Kinerja (rejected)

**Cara Menjalankan:**
```bash
php artisan db:seed --class=DocumentSeeder
```

### 7. ApprovalSeeder.php
**File**: `database/seeders/ApprovalSeeder.php`

Membuat data approval untuk dokumen yang sudah approved/rejected.

**Fitur:**
- Membuat approval record untuk dokumen yang sudah approved/rejected
- Menggunakan user manager/section head sebagai approver
- Menambahkan notes sesuai status dokumen

**Cara Menjalankan:**
```bash
php artisan db:seed --class=ApprovalSeeder
```

### 8. TestUserSeeder.php
**File**: `database/seeders/TestUserSeeder.php`

Membuat user testing tambahan untuk setiap role.

**Data yang Dibuat:**

#### Admin Users:
- superadmin@test.com
- systemadmin@test.com

#### Staff Users:
- john.staff@test.com
- sarah.staff@test.com
- mike.staff@test.com

#### Manager Users:
- david.manager@test.com
- lisa.manager@test.com

#### Section Head Users:
- robert.sectionhead@test.com
- emma.sectionhead@test.com

**Password untuk semua user**: `password`

**Cara Menjalankan:**
```bash
php artisan db:seed --class=TestUserSeeder
```

## Cara Penggunaan

### 1. Fresh Installation
Untuk instalasi baru dengan data lengkap:
```bash
php artisan migrate:fresh --seed
```

### 2. Reset Database
Untuk reset database dan jalankan semua seeder:
```bash
php artisan migrate:refresh --seed
```

### 3. Jalankan Seeder Tertentu
Untuk menjalankan seeder tertentu saja:
```bash
php artisan db:seed --class=NamaSeeder
```

### 4. Jalankan Test User Seeder
Untuk menambahkan user testing tambahan:
```bash
php artisan db:seed --class=TestUserSeeder
```

## Struktur Data

### Roles
- **staff**: Hanya bisa membuat dokumen
- **manager**: Bisa membuat dan approve dokumen
- **section_head**: Hanya bisa approve dokumen
- **admin**: Akses penuh ke semua fitur

### Approval Levels
- **Manager** (level 1): Level approval manager
- **Section Head** (level 2): Level approval section head

### Document Status
- **pending**: Menunggu approval
- **approved**: Sudah disetujui
- **rejected**: Ditolak

### Document Types
- Surat Permohonan
- Laporan Keuangan
- Proposal Bisnis
- Kontrak Kerja
- Laporan Aktivitas

## Testing Credentials

### Default Users (UserSeeder)
| Role | Email | Password |
|------|-------|----------|
| Admin | admin@softui.com | password |
| Staff | staff@softui.com | password |
| Manager | manager@softui.com | password |
| Section Head | sectionhead@softui.com | password |

### Test Users (TestUserSeeder)
| Role | Email | Password |
|------|-------|----------|
| Admin | superadmin@test.com | password |
| Admin | systemadmin@test.com | password |
| Staff | john.staff@test.com | password |
| Staff | sarah.staff@test.com | password |
| Staff | mike.staff@test.com | password |
| Manager | david.manager@test.com | password |
| Manager | lisa.manager@test.com | password |
| Section Head | robert.sectionhead@test.com | password |
| Section Head | emma.sectionhead@test.com | password |

## Notes
- Semua password default adalah `password`
- Seeder dijalankan secara berurutan untuk memastikan dependency terpenuhi
- TestUserSeeder bisa dijalankan terpisah untuk menambah user testing
- ApprovalSeeder hanya membuat approval untuk dokumen yang sudah approved/rejected 