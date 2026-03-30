# 🚀 Panduan Setup Sistem Inventory Teknisi WiFi

## 📁 Struktur Folder

```
inventory-teknisi/
├── .htaccess                 # ROOT htaccess (redirect ke public)
├── README.md
├── config/
├── includes/
├── functions/
├── assets/
├── sql/
└── public/
    ├── .htaccess            # PUBLIC htaccess (redirect ke login)
    ├── index.php            # Auto redirect
    ├── login.php
    ├── dashboard.php
    └── ...
```

## 🔧 Langkah-Langkah Setup

### 1. Ekstrak/Copy Folder ke Server

```bash
# Via XAMPP/WAMPP
C:/xampp/htdocs/barang_teknisi/

# Via Linux/Ubuntu
/var/www/html/barang_teknisi/
```

### 2. Buat 2 File .htaccess

#### A. `.htaccess` di ROOT (barang_teknisi/.htaccess)

```apache
# Redirect root to public folder
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Redirect root access to public folder
    RewriteRule ^$ public/ [L]

    # Redirect any request to public folder if file doesn't exist in root
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
</IfModule>

# Prevent directory listing
Options -Indexes

# Deny access to sensitive files
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>
```

#### B. `.htaccess` di PUBLIC (barang_teknisi/public/.htaccess)

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Redirect to login if accessing root
    RewriteRule ^$ login.php [L]

    # Redirect index.php to login.php
    RewriteRule ^index\.php$ login.php [L,R=301]
</IfModule>

# Disable error display in production
php_flag display_errors off

# Set timezone
php_value date.timezone "Asia/Jakarta"

# Increase upload limit if needed
php_value upload_max_filesize 10M
php_value post_max_size 10M
```

### 3. Setup Database

#### A. Buat Database Baru

```sql
CREATE DATABASE db_barang_teknisi;
```

#### B. Import File SQL

Via phpMyAdmin:

1. Buka phpMyAdmin
2. Pilih database `db_barang_teknisi`
3. Tab **Import**
4. Pilih file `database/db_barang_teknisi.sql`
5. Klik **Go**

Via Command Line:

```bash
mysql -u root -p barang_teknisi < database/barang_teknisi.sql
```

### 4. Konfigurasi Database

Edit file `config/database.php`:

```php
<?php
define('DB_HOST', 'localhost');      // Host database
define('DB_USER', 'root');           // Username database
define('DB_PASS', '');               // Password database
define('DB_NAME', 'db_barang_teknisi'); // Nama database

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
date_default_timezone_set('Asia/Jakarta');
?>
```

### 5. Set Permissions (Linux/Ubuntu)

```bash
# Set folder permissions
chmod -R 755 barang_teknisi/

# Set writable for uploads
chmod -R 777 barang teknisi/uploads/profil
```

### 6. Aktifkan mod_rewrite di Apache

#### XAMPP/WAMPP:

Edit `C:/xampp/apache/conf/httpd.conf`:

```apache
# Uncomment line ini (hapus tanda #)
LoadModule rewrite_module modules/mod_rewrite.so
```

Restart Apache dari XAMPP Control Panel

#### Linux/Ubuntu:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 7. Test Akses

Buka browser dan akses:

```
http://localhost/barang_teknisi/
```

**Otomatis redirect ke:**

```
http://localhost/barang_teknisi/public/login.php
```

## 🔐 Default Login

- **Username:** `admin`
- **Password:** `admin123`

**⚠️ PENTING:** Segera ubah password setelah login pertama!

## 📊 Alur Akses URL

```
http://localhost/barang_teknisi/
    ↓ (redirect via .htaccess root)
http://localhost/barang_teknisi/public/
    ↓ (redirect via .htaccess public)
http://localhost/barang_teknisi/public/login.php
    ↓ (after login)
http://localhost/barang_teknisi/public/dashboard.php
```

## 🐛 Troubleshooting

### Error: "Internal Server Error"

**Solusi:**

- Pastikan mod_rewrite aktif
- Periksa syntax .htaccess
- Cek log error di: `C:/xampp/apache/logs/error.log`

### Error: "Database Connection Failed"

**Solusi:**

- Periksa kredensial di `config/database.php`
- Pastikan MySQL service running
- Pastikan database sudah di-import

### Error: ".htaccess tidak bekerja"

**Solusi:**

- Pastikan `AllowOverride All` di httpd.conf:

```apache
<Directory "C:/xampp/htdocs">
    AllowOverride All
    Require all granted
</Directory>
```

### Tidak redirect ke login

**Solusi:**

- Clear browser cache
- Pastikan kedua .htaccess sudah dibuat
- Periksa `public/index.php` untuk redirect logic

### Permission Denied (Linux)

**Solusi:**

```bash
sudo chown -R www-data:www-data barang_teknisi/
sudo chmod -R 755 barang_teknisi/
sudo chmod -R 777 barang_teknisi/uploads/profil
```

## ✅ Checklist Setup

- [ ] Folder di-copy ke htdocs/www
- [ ] File .htaccess di ROOT dibuat
- [ ] File .htaccess di PUBLIC dibuat
- [ ] Database dibuat dan di-import
- [ ] config/database.php diisi dengan benar
- [ ] mod_rewrite Apache aktif
- [ ] Apache/MySQL service running
- [ ] Permissions di-set (Linux)
- [ ] Test akses via browser
- [ ] Login berhasil
- [ ] Dashboard muncul

## 🎯 URL yang Tersedia Setelah Setup

```
# Publik (tidak perlu login)
/login.php              → Halaman login

# Setelah login
/dashboard.php          → Dashboard utama
/barang/index.php       → Data barang
/teknisi/index.php      → Data teknisi
/transaksi/masuk.php    → Barang masuk
/transaksi/keluar.php   → Barang keluar
/transaksi/riwayat.php  → Riwayat transaksi
/laporan/laporan_pdf.php → Generate laporan
/user/index.php         → Kelola user (admin only)
/user/edit_profil.php   → Edit profil
/logout.php             → Logout
```

## 📞 Support

Jika mengalami kesulitan, periksa:

1. Apache error log
2. PHP error log
3. Browser console (F12)
4. Network tab untuk melihat request

---

**Selamat! Sistem sudah siap digunakan! 🎉**
