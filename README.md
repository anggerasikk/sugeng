# Website Bus Sugeng Rahayu

Website booking transportasi bus modern dengan fitur lengkap.

## 🚀 Quick Start

### 1. Setup XAMPP
- Pastikan Apache dan MySQL running
- Akses: `http://localhost/UAS/`

### 2. Setup Database
1. Buka `http://localhost/phpmyadmin`
2. Buat database: `sugeng`
3. Import file: `database_schema.sql`
4. Atau jalankan: `http://localhost/UAS/setup.php`

## 📁 Struktur Folder

```
UAS/
├── beranda/           # Halaman utama
├── admin/            # Panel admin
├── booking/          # Sistem booking
├── user/             # Panel user
├── auth/             # Login/Register
├── includes/         # Functions & helpers
├── config.php        # Konfigurasi global
├── header.php        # Template header
├── footer.php        # Template footer
├── koneksi.php       # Koneksi database
└── *.php             # Halaman statis
```

## 🔧 Troubleshooting

### Error: "Cannot connect to database"
1. Pastikan MySQL running di XAMPP
2. Buat database `sugeng` di phpMyAdmin
3. Jalankan `setup.php`

### Error: "Header not found"
1. Pastikan `header.php` ada di root folder
2. Cek path include di file PHP

### Error: "Page not loading"
1. Cek Apache error log
2. Jalankan `php -l nama_file.php` untuk syntax check
3. Akses `test.php` untuk test PHP

## 📋 Fitur Utama

- ✅ Sistem booking tiket
- ✅ Panel admin & user
- ✅ Responsive design
- ✅ Search & filter jadwal
- ✅ Multi-payment support
- ✅ Real-time notifications

## 🛠️ Development

### Menambah Halaman Baru
1. Buat file PHP di root folder
2. Include `config.php` dan `header.php`
3. Ikuti pola struktur yang ada

### Database Query
```php
require_once 'config.php';
$result = $koneksi->query("SELECT * FROM schedules");
```

## 📞 Support

Email: support@sugengrrahayu.com
WhatsApp: +62 812-3456-7890

---

**Status**: ✅ Website Ready for Testing
**Last Update**: December 19, 2025
