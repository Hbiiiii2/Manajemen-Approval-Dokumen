# Admin Seeder Guide - Sistem Manajemen Approval Dokumen

## Overview
Dokumentasi untuk seeder admin yang baru dibuat. Seeder ini memungkinkan Anda membuat akun admin dengan berbagai opsi konfigurasi.

## Daftar Admin Seeder

### 1. AdminSeeder.php
**File**: `database/seeders/AdminSeeder.php`

Seeder sederhana yang membuat satu akun admin dengan kredensial default.

**Fitur:**
- Membuat satu akun admin
- Kredensial default: admin@softui.com / password
- Cek duplikasi sebelum membuat
- Feedback di console

**Cara Menjalankan:**
```bash
php artisan db:seed --class=AdminSeeder
```

**Output:**
```
Admin account created successfully!
Email: admin@softui.com
Password: password
```

### 2. CustomAdminSeeder.php
**File**: `database/seeders/CustomAdminSeeder.php`

Seeder yang membuat multiple akun admin dengan kredensial yang dapat dikustomisasi.

**Fitur:**
- Membuat 3 akun admin berbeda
- Kredensial yang dapat dikustomisasi
- Cek duplikasi untuk setiap akun
- Feedback detail di console

**Data yang Dibuat:**
| Name | Email | Password |
|------|-------|----------|
| Super Administrator | superadmin@softui.com | password |
| System Administrator | systemadmin@softui.com | password |
| IT Administrator | itadmin@softui.com | password |

**Cara Menjalankan:**
```bash
php artisan db:seed --class=CustomAdminSeeder
```

**Output:**
```
Admin account created: superadmin@softui.com
Admin account created: systemadmin@softui.com
Admin account created: itadmin@softui.com
All admin accounts processed!
Default password for all accounts: password
```

### 3. SingleAdminSeeder.php
**File**: `database/seeders/SingleAdminSeeder.php`

Seeder yang membuat satu akun admin dengan kredensial dari environment variables.

**Fitur:**
- Kredensial dapat dikustomisasi melalui .env
- Fallback ke kredensial default
- Cek duplikasi sebelum membuat
- Feedback detail di console

**Environment Variables:**
```env
ADMIN_NAME=Administrator
ADMIN_EMAIL=admin@softui.com
ADMIN_PASSWORD=password
```

**Cara Menjalankan:**
```bash
# Dengan kredensial default
php artisan db:seed --class=SingleAdminSeeder

# Dengan kredensial custom (set di .env terlebih dahulu)
ADMIN_NAME="John Admin" ADMIN_EMAIL="john@example.com" ADMIN_PASSWORD="secret123" php artisan db:seed --class=SingleAdminSeeder
```

**Output:**
```
Admin account created successfully!
Name: Administrator
Email: admin@softui.com
Password: password
```

## Cara Penggunaan

### 1. Menjalankan Seeder Tertentu
```bash
# Menjalankan AdminSeeder
php artisan db:seed --class=AdminSeeder

# Menjalankan CustomAdminSeeder
php artisan db:seed --class=CustomAdminSeeder

# Menjalankan SingleAdminSeeder
php artisan db:seed --class=SingleAdminSeeder
```

### 2. Menjalankan dengan Environment Variables
```bash
# Set environment variables dan jalankan
ADMIN_NAME="Custom Admin" ADMIN_EMAIL="custom@example.com" ADMIN_PASSWORD="mypassword" php artisan db:seed --class=SingleAdminSeeder
```

### 3. Menjalankan di DatabaseSeeder
Edit file `database/seeders/DatabaseSeeder.php`:
```php
public function run(): void
{
    $this->call([
        RoleSeeder::class,
        // Uncomment salah satu admin seeder
        AdminSeeder::class,           // Untuk satu admin default
        // CustomAdminSeeder::class,  // Untuk multiple admin
        // SingleAdminSeeder::class,  // Untuk admin dengan .env
    ]);
}
```

Kemudian jalankan:
```bash
php artisan db:seed
```

## Kustomisasi

### 1. Mengubah Kredensial di CustomAdminSeeder
Edit file `database/seeders/CustomAdminSeeder.php`:
```php
$adminData = [
    [
        'name' => 'Your Admin Name',
        'email' => 'your-email@example.com',
        'password' => 'your-password',
    ],
    // Tambahkan admin lain jika diperlukan
];
```

### 2. Mengubah Environment Variables
Edit file `.env`:
```env
ADMIN_NAME="Your Admin Name"
ADMIN_EMAIL="your-email@example.com"
ADMIN_PASSWORD="your-secure-password"
```

### 3. Membuat Seeder Baru
Buat file seeder baru dengan template:
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class YourAdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        User::create([
            'name' => 'Your Admin Name',
            'email' => 'your-email@example.com',
            'password' => Hash::make('your-password'),
            'role_id' => $adminRole->id,
            'email_verified_at' => now(),
        ]);
    }
}
```

## Keamanan

### 1. Password Security
- Gunakan password yang kuat
- Jangan gunakan password default di production
- Ganti password setelah deployment

### 2. Environment Variables
- Jangan commit file .env ke repository
- Gunakan .env.example untuk template
- Set environment variables di server production

### 3. Access Control
- Admin memiliki akses penuh ke sistem
- Monitor aktivitas admin
- Implement audit log jika diperlukan

## Troubleshooting

### 1. Seeder Tidak Berjalan
```bash
# Clear cache
php artisan config:clear
php artisan cache:clear

# Cek database connection
php artisan tinker
DB::connection()->getPdo();
```

### 2. Role Tidak Ditemukan
```bash
# Jalankan RoleSeeder terlebih dahulu
php artisan db:seed --class=RoleSeeder

# Atau jalankan migration
php artisan migrate:fresh
```

### 3. Duplicate Entry
Seeder sudah menangani duplikasi, tapi jika masih error:
```bash
# Cek user yang sudah ada
php artisan tinker
App\Models\User::where('email', 'admin@softui.com')->first();

# Hapus user jika diperlukan
App\Models\User::where('email', 'admin@softui.com')->delete();
```

## Best Practices

### 1. Development
- Gunakan AdminSeeder untuk development
- Password default: 'password'
- Email: admin@softui.com

### 2. Testing
- Gunakan CustomAdminSeeder untuk testing
- Multiple admin untuk testing berbagai skenario
- Password yang sama untuk kemudahan testing

### 3. Production
- Gunakan SingleAdminSeeder dengan environment variables
- Password yang kuat dan unik
- Email yang valid dan aman
- Ganti password setelah deployment

## Support

Untuk bantuan teknis:
- **Developer**: [Hbiiiii2](https://github.com/Hbiiiii2)
- **Email**: [hbiiiii2@gmail.com](mailto:hbiiiii2@gmail.com)
- **Repository**: [Manajemen-Approval-Dokumen](https://github.com/Hbiiiii2/Manajemen-Approval-Dokumen) 