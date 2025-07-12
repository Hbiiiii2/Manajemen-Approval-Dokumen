# SweetAlert Integration Guide

## Overview
Sistem menggunakan SweetAlert2 untuk notifikasi yang lebih menarik dan user-friendly.

## Quick Start

### CDN Integration
```html
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
```

### Basic Usage
```javascript
// Success notification
showSuccess('Data berhasil disimpan!', 'Berhasil!');

// Error notification  
showError('Terjadi kesalahan!', 'Error!');

// Confirmation dialog
showDeleteConfirm('Apakah Anda yakin ingin menghapus?', function() {
    // Delete action
});
```

### Session Flash Messages
```php
return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diajukan!');
return back()->with('error', 'Terjadi kesalahan!');
return back()->with('warning', 'Peringatan!');
return back()->with('info', 'Informasi!');
```

## Available Functions

| Function | Description | Example |
|----------|-------------|---------|
| `showSuccess(message, title)` | Success toast notification | `showSuccess('Data tersimpan!', 'Berhasil!')` |
| `showError(message, title)` | Error toast notification | `showError('Terjadi error!', 'Error!')` |
| `showWarning(message, title)` | Warning toast notification | `showWarning('Peringatan!', 'Warning!')` |
| `showInfo(message, title)` | Info toast notification | `showInfo('Info penting!', 'Info!')` |
| `showConfirm(message, title, callback)` | Confirmation dialog | `showConfirm('Yakin?', 'Konfirmasi', callback)` |
| `showDeleteConfirm(message, callback)` | Delete confirmation | `showDeleteConfirm('Hapus data?', callback)` |

## Configuration

### Toast Position
- `top-end` (default): Pojok kanan atas
- `top-start`: Pojok kiri atas  
- `bottom-end`: Pojok kanan bawah
- `bottom-start`: Pojok kiri bawah

### Timer Settings
- Success: 3000ms
- Error: 4000ms  
- Warning: 3500ms
- Info: 3000ms

## Developer
SweetAlert diimplementasikan oleh [Hbiiiii2](https://github.com/Hbiiiii2) 