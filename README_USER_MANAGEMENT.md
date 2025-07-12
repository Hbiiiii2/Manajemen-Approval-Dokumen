# Dokumentasi User Management - Sistem Manajemen Approval Dokumen

## Overview
Fitur User Management pada sistem ini dirancang khusus untuk admin dengan fokus pada pengelolaan user yang sudah ada. Admin dapat melihat daftar semua user, mengedit informasi user, dan menghapus user yang tidak diperlukan.

## Fitur yang Tersedia

### 1. **List Semua User**
- Menampilkan daftar lengkap semua user dalam sistem
- Informasi yang ditampilkan:
  - Nama user
  - Email
  - Role (staff, manager, section_head, admin)
  - Status (Current User / Active)
  - Tanggal pembuatan akun
- Pagination untuk data yang banyak

### 2. **Add User**
- Menambahkan user baru ke dalam sistem
- Form dengan field:
  - Nama lengkap (required)
  - Email (required, unique)
  - Password (required, min 8 karakter)
  - Konfirmasi password (required)
  - Role (required)
- Validasi data yang ketat
- Penjelasan setiap role untuk memudahkan pemilihan

### 3. **Edit User**
- Mengubah informasi user:
  - Nama lengkap
  - Email
  - Role
  - Password (opsional)
- Validasi data yang diinput
- Konfirmasi password jika ingin mengubah password

### 4. **Delete User**
- Menghapus user yang tidak diperlukan
- Proteksi untuk mencegah admin menghapus akun sendiri
- Konfirmasi modal sebelum penghapusan
- Pesan sukses/error setelah operasi

## Akses dan Keamanan

### **Role yang Dapat Akses**
- **Admin**: Akses penuh ke semua fitur user management
- **Staff, Manager, Section Head**: Tidak dapat mengakses fitur ini

### **Middleware Protection**
- `auth`: Hanya user yang sudah login
- `admin`: Hanya user dengan role admin

### **Keamanan Tambahan**
- Admin tidak dapat menghapus akun sendiri
- Validasi data input yang ketat
- Konfirmasi sebelum penghapusan

## Struktur File

### **Controller**
- `app/Http/Controllers/UserController.php`
  - `index()`: Menampilkan daftar user
  - `create()`: Form tambah user
  - `store()`: Proses tambah user
  - `edit()`: Form edit user
  - `update()`: Proses update user
  - `destroy()`: Proses hapus user

### **Views**
- `resources/views/users/index.blade.php`: Halaman list user
- `resources/views/users/create.blade.php`: Form tambah user
- `resources/views/users/edit.blade.php`: Form edit user

### **Routes**
```php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
```

## Cara Penggunaan

### **1. Akses User Management**
1. Login sebagai admin
2. Klik menu "User Management" di sidebar
3. Halaman akan menampilkan daftar semua user

### **2. Add User**
1. Klik tombol "Tambah User" di halaman list user, atau
2. Klik menu "Tambah User" di sidebar
3. Isi form dengan data yang benar
4. Klik "Tambah User" untuk menyimpan
5. User baru akan ditambahkan ke sistem

### **3. Edit User**
1. Klik tombol "Edit" pada user yang ingin diedit
2. Isi form dengan data yang benar
3. Klik "Update User" untuk menyimpan perubahan
4. Password bisa dikosongkan jika tidak ingin mengubah

### **4. Delete User**
1. Klik tombol "Hapus" pada user yang ingin dihapus
2. Konfirmasi penghapusan di modal yang muncul
3. Klik "Hapus" untuk konfirmasi final
4. User akan dihapus dari sistem

## Tampilan Interface

### **Halaman List User**
- Header dengan judul dan deskripsi
- Tombol "Tambah User" di header
- Tabel dengan kolom: User, Email, Role, Status, Tanggal Dibuat, Aksi
- Tombol Edit dan Hapus untuk setiap user
- Pagination untuk navigasi halaman
- Alert untuk pesan sukses/error

### **Halaman Add User**
- Form dengan field: Nama, Email, Password, Konfirmasi Password, Role
- Validasi real-time dengan feedback
- Penjelasan setiap role untuk memudahkan pemilihan
- Tombol Batal dan Tambah User
- Pesan error jika ada kesalahan input

### **Halaman Edit User**
- Form dengan field: Nama, Email, Role, Password (opsional)
- Validasi real-time
- Tombol Batal dan Update User
- Pesan error jika ada kesalahan input

## Validasi Data

### **Add User**
- Nama: Required, max 255 karakter
- Email: Required, valid email, unique
- Password: Required, min 8 karakter, harus dikonfirmasi
- Role: Required, harus ada di tabel roles

### **Update User**
- Nama: Required, max 255 karakter
- Email: Required, valid email, unique (kecuali user yang sedang diedit)
- Role: Required, harus ada di tabel roles
- Password: Optional, min 8 karakter, harus dikonfirmasi

### **Delete User**
- User tidak boleh menghapus akun sendiri
- Konfirmasi wajib sebelum penghapusan

## Pesan Feedback

### **Success Messages**
- "User berhasil ditambahkan!" - Setelah add berhasil
- "User berhasil diupdate!" - Setelah update berhasil
- "User berhasil dihapus!" - Setelah delete berhasil

### **Error Messages**
- "Tidak dapat menghapus akun sendiri!" - Jika admin mencoba hapus akun sendiri
- Pesan validasi untuk setiap field yang salah

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
- Fitur ini hanya untuk admin
- Admin dapat menambah, edit, dan hapus user
- User yang sudah login tidak bisa dihapus
- Semua operasi memerlukan konfirmasi
- Data user diambil dari seeder yang sudah dibuat sebelumnya 