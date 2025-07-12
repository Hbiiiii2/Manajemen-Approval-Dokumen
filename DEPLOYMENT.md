# Deployment Guide - Sistem Manajemen Approval Dokumen

## Overview
Panduan lengkap untuk deployment sistem manajemen approval dokumen ke production environment.

## Prerequisites

### Server Requirements
- **OS**: Ubuntu 20.04+ / CentOS 8+ / Debian 11+
- **PHP**: 8.1+ dengan extensions berikut:
  - BCMath PHP Extension
  - Ctype PHP Extension
  - cURL PHP Extension
  - DOM PHP Extension
  - Fileinfo PHP Extension
  - JSON PHP Extension
  - Mbstring PHP Extension
  - OpenSSL PHP Extension
  - PCRE PHP Extension
  - PDO PHP Extension
  - Tokenizer PHP Extension
  - XML PHP Extension
- **Database**: MySQL 8.0+ atau PostgreSQL 13+
- **Web Server**: Nginx atau Apache
- **SSL Certificate**: Let's Encrypt atau commercial certificate

### Software Requirements
- **Composer**: 2.0+
- **Node.js**: 16+ (untuk asset compilation)
- **Git**: Untuk version control
- **Supervisor**: Untuk queue management (opsional)

## Deployment Methods

### Method 1: Manual Deployment

#### 1. Server Setup
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install nginx mysql-server php8.1-fpm php8.1-mysql php8.1-xml php8.1-curl php8.1-mbstring php8.1-zip php8.1-gd php8.1-bcmath php8.1-json php8.1-tokenizer php8.1-fileinfo php8.1-dom php8.1-pcre unzip git composer nodejs npm -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

#### 2. Database Setup
```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```

```sql
CREATE DATABASE approval_dokumen;
CREATE USER 'approval_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON approval_dokumen.* TO 'approval_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### 3. Application Setup
```bash
# Create application directory
sudo mkdir -p /var/www/approval-dokumen
sudo chown $USER:$USER /var/www/approval-dokumen

# Clone repository
cd /var/www/approval-dokumen
git clone https://github.com/Hbiiiii2/Manajemen-Approval-Dokumen.git .

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Set permissions
sudo chown -R www-data:www-data /var/www/approval-dokumen
sudo chmod -R 755 /var/www/approval-dokumen
sudo chmod -R 775 /var/www/approval-dokumen/storage
sudo chmod -R 775 /var/www/approval-dokumen/bootstrap/cache
```

#### 4. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit .env file
nano .env
```

**Environment Configuration:**
```env
APP_NAME="Sistem Manajemen Approval Dokumen"
APP_ENV=production
APP_KEY=base64:your_generated_key_here
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=approval_dokumen
DB_USERNAME=approval_user
DB_PASSWORD=strong_password_here

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

#### 5. Database Migration
```bash
# Run migrations
php artisan migrate --force

# Seed database
php artisan db:seed --force

# Create storage link
php artisan storage:link
```

#### 6. Nginx Configuration
```bash
# Create Nginx configuration
sudo nano /etc/nginx/sites-available/approval-dokumen
```

**Nginx Configuration:**
```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/approval-dokumen/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/approval-dokumen /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

#### 7. SSL Configuration (Let's Encrypt)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obtain SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

### Method 2: Docker Deployment

#### 1. Create Dockerfile
```dockerfile
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Build assets
RUN npm install && npm run build

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
```

#### 2. Create docker-compose.yml
```yaml
version: '3'
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: approval-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
    networks:
      - approval-network

  webserver:
    image: nginx:alpine
    container_name: approval-nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./docker/nginx/conf.d/:/etc/nginx/conf.d/
    networks:
      - approval-network

  db:
    image: mysql:8.0
    container_name: approval-db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: approval_dokumen
      MYSQL_ROOT_PASSWORD: your_mysql_root_password
      MYSQL_PASSWORD: your_mysql_password
      MYSQL_USER: approval_user
    volumes:
      - dbdata:/var/lib/mysql
    networks:
      - approval-network

networks:
  approval-network:
    driver: bridge

volumes:
  dbdata:
    driver: local
```

#### 3. Deploy with Docker
```bash
# Build and start containers
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate --force

# Seed database
docker-compose exec app php artisan db:seed --force

# Create storage link
docker-compose exec app php artisan storage:link
```

### Method 3: Cloud Deployment

#### AWS EC2 Deployment
```bash
# Launch EC2 instance (Ubuntu 20.04)
# Connect via SSH
ssh -i your-key.pem ubuntu@your-ec2-ip

# Follow Manual Deployment steps above
# Use AWS RDS for database
# Use AWS S3 for file storage
```

#### DigitalOcean App Platform
1. Connect GitHub repository
2. Configure build settings
3. Set environment variables
4. Deploy automatically

## Production Optimizations

### 1. Performance Optimization
```bash
# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize database
php artisan migrate --force
```

### 2. Security Hardening
```bash
# Set proper permissions
sudo chown -R www-data:www-data /var/www/approval-dokumen
sudo chmod -R 755 /var/www/approval-dokumen
sudo chmod -R 775 /var/www/approval-dokumen/storage
sudo chmod -R 775 /var/www/approval-dokumen/bootstrap/cache

# Disable debug mode
# Set APP_DEBUG=false in .env

# Configure firewall
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable
```

### 3. Monitoring Setup
```bash
# Install monitoring tools
sudo apt install htop iotop nethogs -y

# Setup log rotation
sudo nano /etc/logrotate.d/approval-dokumen
```

### 4. Backup Strategy
```bash
# Create backup script
nano /var/www/approval-dokumen/backup.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/approval-dokumen"

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u approval_user -p approval_dokumen > $BACKUP_DIR/db_backup_$DATE.sql

# Application backup
tar -czf $BACKUP_DIR/app_backup_$DATE.tar.gz /var/www/approval-dokumen

# Clean old backups (keep 7 days)
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

```bash
# Make executable
chmod +x /var/www/approval-dokumen/backup.sh

# Add to crontab
crontab -e
# Add: 0 2 * * * /var/www/approval-dokumen/backup.sh
```

## Maintenance

### 1. Regular Updates
```bash
# Update application
cd /var/www/approval-dokumen
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Database Maintenance
```bash
# Optimize database
mysql -u approval_user -p approval_dokumen -e "OPTIMIZE TABLE users, documents, approvals;"

# Check database size
mysql -u approval_user -p approval_dokumen -e "SELECT table_name, ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size (MB)' FROM information_schema.tables WHERE table_schema = 'approval_dokumen';"
```

### 3. Log Management
```bash
# Monitor logs
tail -f /var/www/approval-dokumen/storage/logs/laravel.log

# Clean old logs
find /var/www/approval-dokumen/storage/logs -name "*.log" -mtime +30 -delete
```

## Troubleshooting

### Common Issues

#### 1. Permission Errors
```bash
sudo chown -R www-data:www-data /var/www/approval-dokumen
sudo chmod -R 755 /var/www/approval-dokumen
sudo chmod -R 775 /var/www/approval-dokumen/storage
sudo chmod -R 775 /var/www/approval-dokumen/bootstrap/cache
```

#### 2. Database Connection Issues
```bash
# Check MySQL status
sudo systemctl status mysql

# Check connection
mysql -u approval_user -p -h localhost approval_dokumen
```

#### 3. Nginx Configuration Issues
```bash
# Test configuration
sudo nginx -t

# Check error logs
sudo tail -f /var/log/nginx/error.log
```

#### 4. PHP-FPM Issues
```bash
# Check PHP-FPM status
sudo systemctl status php8.1-fpm

# Restart PHP-FPM
sudo systemctl restart php8.1-fpm
```

## Support

Untuk bantuan deployment:
- **Developer**: [Hbiiiii2](https://github.com/Hbiiiii2)
- **Email**: [hbiiiii2@gmail.com](mailto:hbiiiii2@gmail.com)
- **Documentation**: [README.md](README.md)
- **Issues**: [GitHub Issues](https://github.com/Hbiiiii2/Manajemen-Approval-Dokumen/issues) 