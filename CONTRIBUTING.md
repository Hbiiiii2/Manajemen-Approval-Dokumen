# Contributing to Sistem Manajemen Approval Dokumen

Terima kasih atas minat Anda untuk berkontribusi pada proyek ini! Dokumen ini berisi panduan untuk berkontribusi.

## Cara Berkontribusi

### 1. Fork Repository
1. Fork repository ini ke akun GitHub Anda
2. Clone repository yang sudah di-fork ke local machine Anda

### 2. Setup Development Environment
```bash
# Clone repository
git clone https://github.com/YOUR_USERNAME/Manajemen-Approval-Dokumen.git
cd Manajemen-Approval-Dokumen

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate --seed

# Create storage link
php artisan storage:link
```

### 3. Buat Branch
```bash
# Buat branch baru untuk fitur/bugfix
git checkout -b feature/nama-fitur
# atau
git checkout -b bugfix/nama-bugfix
```

### 4. Development Guidelines

#### Coding Standards
- Ikuti PSR-12 coding standards untuk PHP
- Gunakan meaningful variable dan function names
- Tambahkan comments untuk kode yang kompleks
- Pastikan semua tests pass

#### Database Changes
- Buat migration untuk perubahan database
- Update seeder jika diperlukan
- Test migration di environment yang bersih

#### Frontend Changes
- Ikuti struktur CSS yang sudah ada
- Gunakan Soft UI Dashboard components
- Test di berbagai browser

### 5. Testing
```bash
# Jalankan tests
php artisan test

# Jalankan specific test
php artisan test --filter TestName
```

### 6. Commit Changes
```bash
# Add changes
git add .

# Commit dengan message yang jelas
git commit -m "feat: tambah fitur user management"
git commit -m "fix: perbaiki bug approval process"
git commit -m "docs: update dokumentasi API"
```

### 7. Push dan Pull Request
```bash
# Push ke repository Anda
git push origin feature/nama-fitur

# Buat Pull Request di GitHub
```

## Pull Request Guidelines

### Checklist Sebelum Submit PR
- [ ] Kode mengikuti coding standards
- [ ] Semua tests pass
- [ ] Dokumentasi diupdate jika diperlukan
- [ ] Tidak ada breaking changes tanpa dokumentasi
- [ ] Screenshot ditambahkan untuk UI changes

### PR Template
```markdown
## Deskripsi
Jelaskan perubahan yang Anda buat.

## Tipe Perubahan
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
Jelaskan bagaimana Anda menguji perubahan ini.

## Screenshots (jika ada)
Tambahkan screenshot untuk UI changes.

## Checklist
- [ ] Kode mengikuti coding standards
- [ ] Semua tests pass
- [ ] Dokumentasi diupdate
- [ ] Tidak ada breaking changes
```

## Issue Guidelines

### Bug Reports
- Jelaskan bug dengan jelas
- Berikan langkah-langkah reproduksi
- Sertakan environment details
- Tambahkan screenshot jika diperlukan

### Feature Requests
- Jelaskan fitur yang diinginkan
- Berikan use case
- Jelaskan manfaat fitur
- Sertakan mockup jika ada

## Development Setup

### Requirements
- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL 8.0+
- Laravel 10.x

### Local Development
```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
# Edit .env dengan database credentials

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate --seed

# Create storage link
php artisan storage:link

# Start development server
php artisan serve

# Compile assets (in another terminal)
npm run dev
```

### Testing
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter TestName

# Run with coverage
php artisan test --coverage
```

## Code Review Process

### Review Checklist
- [ ] Kode mengikuti best practices
- [ ] Tidak ada security vulnerabilities
- [ ] Performance considerations
- [ ] Error handling yang proper
- [ ] Documentation yang lengkap

### Review Timeline
- Response dalam 48 jam
- Review completion dalam 1 minggu
- Merge setelah approval dari maintainer

## Communication

### Channels
- **Issues**: GitHub Issues untuk bug reports dan feature requests
- **Discussions**: GitHub Discussions untuk general questions
- **Email**: Untuk sensitive matters

### Code of Conduct
- Bersikap sopan dan respectful
- Berikan feedback yang konstruktif
- Hormati waktu dan effort contributor lain
- Fokus pada kode, bukan pada orang

## Recognition

### Contributors
- Nama akan ditambahkan ke README.md
- Credit akan diberikan di CHANGELOG.md
- Access ke repository jika diperlukan

### Types of Contributions
- **Code**: Bug fixes, new features, improvements
- **Documentation**: README, API docs, guides
- **Testing**: Test cases, bug reports
- **Design**: UI/UX improvements, mockups

## Questions?

Jika Anda memiliki pertanyaan tentang berkontribusi, silakan:
1. Cek dokumentasi yang ada
2. Cari issue yang sudah ada
3. Buat issue baru jika diperlukan
4. Hubungi maintainer: [Hbiiiii2](https://github.com/Hbiiiii2)

Terima kasih atas kontribusi Anda! 🚀 