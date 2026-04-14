# POS UMKM - Aplikasi Kasir dengan Scan Barcode

![Laravel](https://img.shields.io/badge/Laravel-13.x-red)
![Livewire](https://img.shields.io/badge/Livewire-3.x-purple)
![License](https://img.shields.io/badge/License-Proprietary-blue)

Aplikasi Point of Sale untuk UMKM dengan fitur scan barcode produk distributor 
dan laporan keuangan cash flow otomatis.

## ✨ Fitur Utama

- 📷 **Scan Barcode** - Gunakan barcode bawaan distributor, tidak perlu print ulang
- 💰 **Cash Flow Otomatis** - Laporan keuangan tercatat setiap transaksi
- 📊 **Laporan Lengkap** - Omzet, laba rugi, stok, produk terlaris
- 🏢 **Multi-Tenancy** - Satu aplikasi untuk banyak warung (SaaS ready)
- 📱 **Mobile Friendly** - Optimal untuk HP kasir

## 🚀 Quick Start

```bash
# Clone repository
git clone git@github.com:giarHendris4/POS.git
cd POS

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database di .env
# DB_DATABASE=pos_umkm_dev

# Run migration
php artisan migrate

# Run seeder (optional)
php artisan db:seed

# Start server
php artisan serve
```

## 📚 Dokumentasi

- [Blueprint Arsitektur](docs/blueprint.md)
- [Issues & Development Tracking](https://github.com/giarHendris4/POS/issues)

## 🏗️ Tech Stack

- **Framework**: Laravel 13
- **Frontend**: Livewire 3 + Alpine.js + Tailwind CSS
- **Database**: MySQL 8.0
- **Auth**: Laravel Jetstream

## 📦 Paket Subscription (SaaS)

| Paket | Harga/Bulan | Produk | User |
|-------|-------------|--------|------|
| Basic | Rp 49.000 | 500 | 2 |
| Pro | Rp 99.000 | 2000 | 5 |
| Enterprise | Rp 249.000 | Unlimited | Unlimited |

## 📝 Lisensi

Proprietary - All Rights Reserved
