# Dokumentasi Fitur Profile - Sistem Manajemen Approval Dokumen

## Overview
Fitur Profile memungkinkan semua user (staff, manager, section head, admin) untuk mengelola informasi profile mereka, termasuk mengganti password dan mengupload foto profile.

## Fitur yang Tersedia

### 1. **Informasi Profile**
- Menampilkan informasi user:
  - Nama lengkap
  - Email
  - Role
  - Tanggal bergabung
- Foto profile (jika sudah diupload)
- Tombol edit profile untuk mengubah informasi

### 2. **Ganti Password**
- Form untuk mengganti password dengan validasi:
  - Password saat ini (required)
  - Password baru (required, min 8 karakter)
  - Konfirmasi password baru (required)
- Validasi password saat ini sebelum mengubah
- Pesan feedback setelah berhasil/gagal

### 3. **Upload Foto Profile**
- Upload foto profile dengan format JPG, PNG, GIF
- Maksimal ukuran file 2MB
- Preview foto yang sudah diupload
- Otomatis menghapus foto lama saat upload foto baru
- Fallback ke foto default jika belum upload

### 4. **Edit Profile**
- Modal untuk mengedit informasi profile:
  - Nama lengkap
  - Email
  - Upload foto profile
- Validasi data yang diinput
- Pesan feedback setelah update

## Akses dan Keamanan

### **Role yang Dapat Akses**
- **Semua Role**: Staff, Manager, Section Head, Admin
- Setiap user hanya bisa mengedit profile mereka sendiri

### **Middleware Protection**
- `auth`: Hanya user yang sudah login

### **Keamanan Tambahan**
- Validasi password saat ini sebelum mengubah password
- Validasi format dan ukuran file foto
- Otomatis menghapus foto lama saat upload foto baru
- Email harus unique (kecuali untuk user yang sedang diedit)

## Struktur File

### **Controller**
- `app/Http/Controllers/ProfileController.php`
  - `index()`: Menampilkan halaman profile
  - `updatePassword()`: Proses ganti password
  - `updateProfile()`: Proses update profile dan foto
  - `updatePhoto()`: Proses upload foto profile

### **Views**
- `resources/views/profile.blade.php`: Halaman profile lengkap

### **Routes**
```php
Route::get('profile', [ProfileController::class, 'index'])->name('profile');
Route::post('profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
Route::post('profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
Route::post('profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
```

### **Database**
- Kolom `profile_photo` di tabel `users`
- Migration: `2025_07_12_040456_add_profile_photo_to_users_table.php`

## Cara Penggunaan

### **1. Akses Profile**
1. Login ke sistem
2. Klik menu "Profile" di sidebar
3. Halaman akan menampilkan informasi profile

### **2. Ganti Password**
1. Scroll ke bagian "Ganti Password"
2. Isi password saat ini
3. Isi password baru (minimal 8 karakter)
4. Konfirmasi password baru
5. Klik "Ganti Password"
6. Pesan sukses akan muncul jika berhasil

### **3. Upload Foto Profile**
1. Scroll ke bagian "Foto Profile"
2. Klik "Choose File" dan pilih foto
3. Pastikan format JPG, PNG, atau GIF
4. Pastikan ukuran maksimal 2MB
5. Klik "Upload Foto"
6. Foto akan langsung ditampilkan

### **4. Edit Profile**
1. Klik icon edit di bagian "Informasi Profile"
2. Modal akan muncul dengan form edit
3. Isi nama dan email yang baru
4. Upload foto jika ingin mengubah
5. Klik "Update Profile"
6. Profile akan diupdate

## Tampilan Interface

### **Halaman Profile**
- Header dengan foto profile dan informasi user
- 3 card utama:
  - **Informasi Profile**: Menampilkan data user dengan tombol edit
  - **Ganti Password**: Form untuk mengganti password
  - **Foto Profile**: Upload dan preview foto profile

### **Modal Edit Profile**
- Form dengan field: Nama, Email, Upload Foto
- Validasi real-time
- Tombol Batal dan Update Profile

### **Navbar Integration**
- Foto profile user ditampilkan di navbar
- Fallback ke icon default jika belum upload foto
- Nama dan role user ditampilkan

## Validasi Data

### **Update Password**
- Password saat ini: Required
- Password baru: Required, min 8 karakter
- Konfirmasi password: Required, harus sama dengan password baru

### **Update Profile**
- Nama: Required, max 255 karakter
- Email: Required, valid email, unique (kecuali user yang sedang diedit)
- Foto: Optional, image, max 2MB, format JPG/PNG/GIF

### **Upload Photo**
- Foto: Required, image, max 2MB, format JPG/PNG/GIF

## File Storage

### **Konfigurasi Storage**
- Menggunakan Laravel Storage dengan disk 'public'
- Symbolic link: `php artisan storage:link`
- Path file: `storage/app/public/profile-photos/`

### **File Management**
- File disimpan dengan nama unik
- Otomatis menghapus foto lama saat upload baru
- Fallback ke foto default jika belum upload

## Pesan Feedback

### **Success Messages**
- "Password berhasil diubah!" - Setelah ganti password berhasil
- "Profile berhasil diupdate!" - Setelah update profile berhasil
- "Foto profile berhasil diupdate!" - Setelah upload foto berhasil

### **Error Messages**
- "Password saat ini tidak sesuai." - Jika password lama salah
- Pesan validasi untuk setiap field yang salah
- Pesan error jika upload file gagal

## Testing

### **User Testing yang Tersedia**
```bash
# Admin users
admin@softui.com
superadmin@test.com
systemadmin@test.com

# Staff users  
staff@softui.com
john.staff@test.com
sarah.staff@test.com
mike.staff@test.com

# Manager users
manager@softui.com
david.manager@test.com
lisa.manager@test.com

# Section Head users
sectionhead@softui.com
robert.sectionhead@test.com
emma.sectionhead@test.com
```

### **Password untuk semua user**: `password`

## Notes
- Fitur ini tersedia untuk semua role
- Setiap user hanya bisa mengedit profile mereka sendiri
- Foto profile ditampilkan di navbar dan halaman profile
- File foto disimpan di storage dengan path yang aman
- Validasi ketat untuk keamanan data user 