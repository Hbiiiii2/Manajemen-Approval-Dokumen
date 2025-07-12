# Dokumentasi SweetAlert - Sistem Manajemen Approval Dokumen

## Overview
Sistem manajemen approval dokumen menggunakan SweetAlert2 untuk memberikan notifikasi yang lebih menarik dan user-friendly dibandingkan alert default browser.

## Fitur SweetAlert yang Diimplementasikan

### 1. **Toast Notifications**
- **Success**: Notifikasi hijau untuk operasi berhasil
- **Error**: Notifikasi merah untuk error
- **Warning**: Notifikasi kuning untuk peringatan
- **Info**: Notifikasi biru untuk informasi

### 2. **Confirmation Dialogs**
- **Delete Confirmation**: Dialog konfirmasi untuk menghapus data
- **General Confirmation**: Dialog konfirmasi umum
- **Custom Callback**: Support untuk callback function

### 3. **Loading States**
- **Form Submission**: Loading state saat submit form
- **API Calls**: Loading state untuk operasi async

## Implementasi

### 1. **CDN Integration**
```html
<!-- CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
```

### 2. **Layout Integration**
```php
// resources/views/layouts/app.blade.php
@include('components.sweet-alert')
```

### 3. **Component File**
```php
// resources/views/components/sweet-alert.blade.php
// Berisi semua fungsi SweetAlert dan session handling
```

## Fungsi SweetAlert yang Tersedia

### 1. **showSuccess(message, title)**
```javascript
showSuccess('Data berhasil disimpan!', 'Berhasil!');
```

### 2. **showError(message, title)**
```javascript
showError('Terjadi kesalahan!', 'Error!');
```

### 3. **showWarning(message, title)**
```javascript
showWarning('Perhatikan input Anda!', 'Peringatan!');
```

### 4. **showInfo(message, title)**
```javascript
showInfo('Informasi penting!', 'Informasi!');
```

### 5. **showConfirm(message, title, callback)**
```javascript
showConfirm('Apakah Anda yakin?', 'Konfirmasi', function() {
    // Lakukan action
});
```

### 6. **showDeleteConfirm(message, callback)**
```javascript
showDeleteConfirm('Apakah Anda yakin ingin menghapus?', function() {
    // Lakukan delete
});
```

## Session Flash Messages

### 1. **Success Message**
```php
return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diajukan!');
```

### 2. **Error Message**
```php
return back()->with('error', 'Anda sudah melakukan approval pada level ini.');
```

### 3. **Warning Message**
```php
return back()->with('warning', 'Perhatikan data yang Anda input.');
```

### 4. **Info Message**
```php
return back()->with('info', 'Informasi penting untuk Anda.');
```

## Contoh Penggunaan

### 1. **Delete Confirmation**
```html
<button onclick="deleteUser(1, 'John Doe')" class="btn btn-danger">
    <i class="fas fa-trash"></i> Hapus
</button>

<script>
function deleteUser(userId, userName) {
    showDeleteConfirm(`Apakah Anda yakin ingin menghapus user "${userName}"?`, function() {
        window.location.href = `/user-management/${userId}/delete`;
    });
}
</script>
```

### 2. **Form Submission with Loading**
```html
<form onsubmit="showLoading()">
    <!-- form fields -->
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<script>
function showLoading() {
    Swal.fire({
        title: 'Mengirim Data...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}
</script>
```

### 3. **Custom Success Message**
```javascript
// Setelah operasi berhasil
showSuccess('Dokumen berhasil diapprove!', 'Approval Berhasil!');
```

## Konfigurasi Toast Notifications

### 1. **Position**
- `top-end`: Pojok kanan atas (default)
- `top-start`: Pojok kiri atas
- `bottom-end`: Pojok kanan bawah
- `bottom-start`: Pojok kiri bawah

### 2. **Timer**
- **Success**: 3000ms (3 detik)
- **Error**: 4000ms (4 detik)
- **Warning**: 3500ms (3.5 detik)
- **Info**: 3000ms (3 detik)

### 3. **Styling**
```css
.swal2-toast {
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
```

## Keuntungan SweetAlert

### 1. **User Experience**
- ✅ Tampilan yang menarik dan modern
- ✅ Animasi yang smooth
- ✅ Responsive design
- ✅ Customizable styling

### 2. **Developer Experience**
- ✅ API yang mudah digunakan
- ✅ Support untuk callback functions
- ✅ Promise-based
- ✅ Extensive documentation

### 3. **Browser Support**
- ✅ Chrome/Chromium
- ✅ Firefox
- ✅ Safari
- ✅ Edge
- ✅ Opera

## Customization

### 1. **Mengubah Warna**
```javascript
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: 'Data berhasil disimpan',
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33'
});
```

### 2. **Mengubah Icon**
```javascript
Swal.fire({
    icon: 'question', // success, error, warning, info, question
    title: 'Konfirmasi',
    text: 'Apakah Anda yakin?'
});
```

### 3. **Custom HTML Content**
```javascript
Swal.fire({
    title: 'Detail Dokumen',
    html: `
        <div class="text-left">
            <p><strong>Judul:</strong> Dokumen Penting</p>
            <p><strong>Status:</strong> Pending</p>
            <p><strong>Tanggal:</strong> 2024-01-15</p>
        </div>
    `
});
```

## Troubleshooting

### 1. **SweetAlert Tidak Muncul**
- Pastikan CDN SweetAlert sudah dimuat
- Periksa console browser untuk error
- Pastikan session flash message ada

### 2. **Callback Tidak Berfungsi**
- Pastikan function callback sudah didefinisikan
- Periksa scope function
- Gunakan arrow function untuk context yang benar

### 3. **Styling Tidak Sesuai**
- Periksa CSS conflicts
- Gunakan `customClass` untuk override
- Pastikan CSS SweetAlert dimuat setelah CSS aplikasi

## Developer

SweetAlert diimplementasikan oleh [Hbiiiii2](https://github.com/Hbiiiii2) untuk meningkatkan user experience sistem manajemen approval dokumen. 