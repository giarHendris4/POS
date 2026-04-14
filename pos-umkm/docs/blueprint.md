# Blueprint Aplikasi POS Warung UMKM
# Scan Barcode & Laporan Cash Flow

**Versi:** 1.0.0
**Status:** Development - Phase 1
**Stack:** Laravel 13 + Livewire 3 + MySQL 8.0

---

## 🎯 Unique Value Proposition

Aplikasi POS untuk UMKM yang memanfaatkan **barcode bawaan distributor** 
sehingga pemilik warung **tidak perlu input data manual**.

| Masalah | Solusi |
|---------|--------|
| Input produk manual | Scan barcode distributor |
| Stok tidak akurat | Auto-update stok setiap transaksi |
| Laporan keuangan ribet | Cash flow otomatis |
| Software POS mahal | SaaS mulai 49rb/bulan |

---

## 🗄️ Struktur Database

### Core Tables

1. **tenants** - Multi-tenancy data isolation
2. **users** - Authentication dengan role (owner/cashier)
3. **products** - Master produk dengan barcode unique per tenant
4. **product_categories** - Kategori produk
5. **stock_movements** - Tracking pergerakan stok
6. **transactions** - Header transaksi POS
7. **transaction_items** - Detail item transaksi
8. **cash_flows** - Laporan keuangan otomatis
9. **carts** - Keranjang temporary
10. **subscription_plans** - Paket berlangganan

### Relasi Kunci

```
tenant_id ada di SEMUA tabel (multi-tenancy)
products.barcode UNIQUE per tenant
transactions.invoice_number UNIQUE per tenant
```

---

## 🔄 Alur Scan Barcode

1. User buka halaman `/pos` 
2. Scan barcode produk → Auto-focus ke hidden input
3. Livewire cari produk via AJAX
4. Jika ditemukan → Tambah ke keranjang
5. Jika tidak ditemukan → Quick add form modal
6. Selesai transaksi → Auto-record cash flow

---

## 📦 Fitur per Paket

### Basic (49rb/bulan)
- Scan barcode
- Laporan penjualan dasar
- Maks 500 produk
- Maks 2 user

### Pro (99rb/bulan)
- Semua fitur Basic
- Laporan laba rugi
- Export Excel
- Maks 2000 produk
- Maks 5 user

### Enterprise (249rb/bulan)
- Semua fitur Pro
- Multi cabang
- API access
- Unlimited produk & user

---

## 🛠️ Tech Stack Detail

- **Backend**: Laravel 13
- **Frontend**: Livewire 3 + Alpine.js + Tailwind CSS
- **Database**: MySQL 8.0
- **Scanner**: HTML5 Camera API / Browser autofocus input
- **Auth**: Laravel Jetstream (Livewire stack)
- **Multi-tenancy**: Single DB shared schema dengan tenant_id

---

## 📝 Development Phases

1. ✅ Phase 1: Setup project & dokumentasi
2. 🔄 Phase 2: Database migration & models
3. ⏳ Phase 3: Authentication & tenant middleware
4. ⏳ Phase 4: POS interface & barcode scanner
5. ⏳ Phase 5: Cash flow automation
6. ⏳ Phase 6: Reports & dashboard
7. ⏳ Phase 7: Subscription & billing
