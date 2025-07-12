# Technical Documentation - Sistem Manajemen Approval Dokumen

## Architecture Overview

### Technology Stack
- **Backend**: Laravel 10.x (PHP 8.1+)
- **Frontend**: Soft UI Dashboard (Bootstrap 5)
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Sanctum
- **File Storage**: Laravel Storage
- **Notifications**: SweetAlert2

### System Architecture
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   Backend       │    │   Database      │
│   (Soft UI)     │◄──►│   (Laravel)     │◄──►│   (MySQL)       │
└─────────────────┘    └─────────────────┘    └─────────────────┘
                              │
                              ▼
                       ┌─────────────────┐
                       │   File Storage  │
                       │   (Local/S3)    │
                       └─────────────────┘
```

## Database Schema

### Core Tables
```sql
-- Users and Authentication
users (id, name, email, password, role_id, profile_photo, created_at, updated_at)
roles (id, name, created_at, updated_at)

-- Documents and Approval
documents (id, title, description, file_path, user_id, document_type_id, status, created_at, updated_at)
document_types (id, name, created_at, updated_at)
approvals (id, document_id, user_id, approval_level_id, status, notes, created_at, updated_at)
approval_levels (id, name, level, created_at, updated_at)

-- Divisions (Optional)
divisions (id, name, created_at, updated_at)
division_roles (id, division_id, role_id, created_at, updated_at)
```

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
- `POST /api/login` - User login
- `POST /api/register` - User registration
- `POST /api/logout` - User logout

### Documents
- `GET /api/documents` - List documents
- `POST /api/documents` - Create document
- `GET /api/documents/{id}` - Show document

### Approvals
- `GET /api/approvals` - List approvals
- `POST /api/approvals/{document}/approve` - Approve document
- `POST /api/approvals/{document}/reject` - Reject document

### Profile
- `GET /api/profile` - Show profile
- `POST /api/profile/update` - Update profile
- `POST /api/profile/password` - Change password
- `POST /api/profile/photo` - Upload photo

### User Management (Admin)
- `GET /api/users` - List users
- `POST /api/users` - Create user
- `PUT /api/users/{id}` - Update user
- `DELETE /api/users/{id}` - Delete user

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── ApprovalController.php
│   │   ├── DashboardController.php
│   │   ├── DocumentController.php
│   │   ├── ProfileController.php
│   │   └── UserController.php
│   └── Middleware/
│       └── AdminMiddleware.php
├── Models/
│   ├── Approval.php
│   ├── Document.php
│   ├── User.php
│   └── Role.php
└── Providers/
    └── AppServiceProvider.php

database/
├── migrations/
│   ├── create_users_table.php
│   ├── create_documents_table.php
│   └── create_approvals_table.php
└── seeders/
    ├── DatabaseSeeder.php
    ├── UserSeeder.php
    └── DocumentSeeder.php

resources/
├── views/
│   ├── dashboard.blade.php
│   ├── documents/
│   ├── approvals/
│   └── profile.blade.php
└── js/
    └── app.js

routes/
└── web.php
```

## Configuration

### Environment Variables
```env
# Application
APP_NAME="Sistem Manajemen Approval Dokumen"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=approval_dokumen
DB_USERNAME=approval_user
DB_PASSWORD=strong_password

# File Upload
MAX_FILE_SIZE=10240
ALLOWED_FILE_TYPES=pdf,doc,docx,xls,xlsx
PROFILE_PHOTO_MAX_SIZE=2048

# Security
PASSWORD_MIN_LENGTH=8
LOGIN_ATTEMPTS_LIMIT=5
SESSION_LIFETIME=120
```

### File Upload Configuration
```php
// config/filesystems.php
'disks' => [
    'local' => [
        'driver' => 'local',
        'root' => storage_path('app'),
    ],
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
],
```

## Security Features

### Authentication
- Laravel Sanctum for API authentication
- Password hashing with bcrypt
- Rate limiting for login attempts
- Session management

### Authorization
- Role-based access control (RBAC)
- Middleware protection
- Permission validation at controller level

### Data Protection
- CSRF protection
- XSS prevention
- SQL injection protection
- Input validation and sanitization

### File Security
- File type validation
- Size limit enforcement
- Secure file storage
- Malware scanning (optional)

## Performance Optimization

### Caching
```bash
# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Database Optimization
```sql
-- Add indexes for better performance
ALTER TABLE documents ADD INDEX idx_user_status (user_id, status);
ALTER TABLE approvals ADD INDEX idx_document_status (document_id, status);
ALTER TABLE users ADD INDEX idx_role (role_id);
```

### Asset Optimization
```bash
# Compile assets for production
npm run build

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

## Testing

### Unit Tests
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter DocumentTest

# Run with coverage
php artisan test --coverage
```

### Feature Tests
```php
// Example test
public function test_user_can_create_document()
{
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $response = $this->post('/documents', [
        'title' => 'Test Document',
        'description' => 'Test Description',
        'document_type_id' => 1,
    ]);
    
    $response->assertStatus(302);
    $this->assertDatabaseHas('documents', [
        'title' => 'Test Document',
        'user_id' => $user->id,
    ]);
}
```

## Deployment

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure database credentials
- [ ] Set up SSL certificate
- [ ] Configure web server (Nginx/Apache)
- [ ] Set proper file permissions
- [ ] Run database migrations
- [ ] Seed initial data
- [ ] Configure backup strategy
- [ ] Set up monitoring

### Docker Deployment
```yaml
# docker-compose.yml
version: '3'
services:
  app:
    build: .
    ports:
      - "8000:8000"
    environment:
      - DB_HOST=db
    depends_on:
      - db
  
  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: approval_dokumen
      MYSQL_USER: approval_user
      MYSQL_PASSWORD: password
```

## Monitoring

### Log Management
```bash
# Monitor application logs
tail -f storage/logs/laravel.log

# Monitor error logs
tail -f storage/logs/laravel-*.log

# Clean old logs
find storage/logs -name "*.log" -mtime +30 -delete
```

### Performance Monitoring
```bash
# Check database performance
mysql -u root -p -e "SHOW PROCESSLIST;"

# Check disk usage
df -h

# Check memory usage
free -h
```

## Troubleshooting

### Common Issues

#### 1. Permission Errors
```bash
sudo chown -R www-data:www-data /var/www/approval-dokumen
sudo chmod -R 755 /var/www/approval-dokumen
sudo chmod -R 775 storage bootstrap/cache
```

#### 2. Database Connection
```bash
# Check MySQL status
sudo systemctl status mysql

# Test connection
mysql -u approval_user -p approval_dokumen
```

#### 3. File Upload Issues
```bash
# Check storage permissions
sudo chmod -R 775 storage/app/public

# Create storage link
php artisan storage:link
```

## Support

### Documentation
- [README.md](README.md) - Main documentation
- [API_DOCUMENTATION.md](API_DOCUMENTATION.md) - API reference
- [DEPLOYMENT.md](DEPLOYMENT.md) - Deployment guide

### Contact
- **Developer**: [Hbiiiii2](https://github.com/Hbiiiii2)
- **Email**: [hbiiiii2@gmail.com](mailto:hbiiiii2@gmail.com)
- **Repository**: [Manajemen-Approval-Dokumen](https://github.com/Hbiiiii2/Manajemen-Approval-Dokumen)

### Version History
- **v1.0.0**: Initial release with core features
- **v0.9.0**: Beta release with basic functionality
- **v0.8.0**: Alpha release with planning and setup 