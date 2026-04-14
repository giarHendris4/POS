# Issue #3: POS Interface & Thermal Printer Integration

**Status:** 🔄 In Progress
**Priority:** Critical
**Assignee:** Windsurf (via prompt)
**Reviewer:** Senior Engineer
**Depends On:** Issue #1 ✅, Issue #2 ✅

## Deskripsi
Implementasi halaman POS (Point of Sale) dengan fitur scan barcode, keranjang belanja, checkout multi-payment, cetak struk thermal printer (Bluetooth/USB), history transaksi dengan filter tanggal, dan timezone WIB.

## Tasks Checklist

### Phase 3.1: POS Core Components
- [ ] Livewire component `PosScanner` untuk scan barcode
- [ ] Livewire component `Cart` untuk keranjang belanja
- [ ] Livewire component `PaymentModal` untuk checkout
- [ ] Quick add product modal saat barcode tidak ditemukan
- [ ] Auto-focus barcode input menggunakan Alpine.js

### Phase 3.2: Transaction Processing
- [ ] Generate invoice number format: `INV-{YYYYMMDD}-{XXXX}` 
- [ ] Simpan transaksi dengan atomic database transaction
- [ ] Update stok produk otomatis
- [ ] Catat stock movement (type: out)
- [ ] Generate cash flow (debit) otomatis via Model Event

### Phase 3.3: Thermal Printer Integration
- [ ] Service class `ThermalPrinterService` 
- [ ] Format struk 58mm/80mm
- [ ] Koneksi Bluetooth via Web Bluetooth API
- [ ] Koneksi USB via Web Serial API
- [ ] Fallback: Download struk sebagai TXT/PDF
- [ ] Tabel `printed_receipts` untuk tracking struk tercetak

### Phase 3.4: History Transaksi
- [ ] Halaman `/transactions` dengan filter tanggal
- [ ] Hanya owner dan super admin yang bisa akses
- [ ] Tampilkan hanya transaksi hari ini di halaman POS
- [ ] Filter by tanggal (dari - sampai) atau tanggal spesifik
- [ ] Total transaksi dan ringkasan barang keluar

### Phase 3.5: Timezone & Date Handling
- [ ] Set timezone ke `Asia/Jakarta` (WIB)
- [ ] Semua `created_at`, `transaction_date` menggunakan WIB
- [ ] Auto-archive transaksi kemarin ke history

### Phase 3.6: Testing
- [ ] Unit test untuk `PosScanner` component
- [ ] Unit test untuk `Cart` component
- [ ] Unit test untuk transaction processing
- [ ] Unit test untuk thermal printer service
- [ ] Unit test untuk history filter

## Definition of Done

- [ ] User bisa scan barcode dan produk masuk keranjang
- [ ] User bisa checkout dengan multi metode bayar
- [ ] Stok produk terupdate otomatis
- [ ] Cash flow tercatat otomatis
- [ ] Struk bisa dicetak ke thermal printer
- [ ] Struk tersimpan di `printed_receipts` 
- [ ] History transaksi bisa difilter by tanggal
- [ ] Timezone WIB diterapkan di semua timestamp
- [ ] Semua unit test passing
- [ ] Git commit & push

## Technical Specifications

### Invoice Number Format
```
INV-20260414-0001
     YYYYMMDD-XXXX (4 digit sequence per tenant per day)
```

### Thermal Printer Format (58mm)
```
================================
         WARUNG SEJAHTERA        
     Jl. Contoh No. 123, Jakarta  
     Telp: 0812-3456-7890        
================================
Tanggal : 14 Apr 2026 10:30 WIB
Kasir   : John Doe
No. Inv : INV-20260414-0001
--------------------------------
Produk          Qty   Harga  Total
--------------------------------
Indomie Goreng   2   3,500   7,000
Telur Ayam       1  30,000  30,000
Aqua 600ml       3   4,000  12,000
--------------------------------
Subtotal                49,000
Diskon                      0
Pajak                       0
TOTAL                   49,000
--------------------------------
Bayar : Cash          50,000
Kembali                1,000
================================
         TERIMA KASIH           
================================
```

### Printed Receipts Table
```sql
printed_receipts:
- id
- tenant_id
- branch_id (nullable)
- transaction_id
- receipt_number (unique per tenant)
- printed_by (user_id)
- printed_at (timestamp)
- receipt_data (JSON snapshot)
- total_amount
```

### Today's Transactions Query
```php
Transaction::where('tenant_id', auth()->user()->tenant_id)
    ->whereDate('transaction_date', now()->toDateString())
    ->get();
```
