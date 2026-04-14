# Issue #1: Initial Project Setup & Core Migrations

**Status:** 🔄 In Progress
**Priority:** Critical
**Assignee:** Windsurf (via prompt)
**Reviewer:** Senior Engineer

## Deskripsi
Setup awal project Laravel 13 dengan multi-tenancy dan membuat semua migration 
untuk core tables POS UMKM.

## Tasks Checklist

- [x] Git repository initialized
- [x] Laravel 13 installed
- [x] Livewire 3 installed
- [x] Jetstream installed (Livewire stack, NO teams)
- [x] Session driver configured to `database` 
- [x] Database `pos_umkm_dev` created
- [ ] Migration: `tenants` table
- [ ] Migration: add `tenant_id` to `users` table
- [ ] Migration: `products` table
- [ ] Migration: `product_categories` table
- [ ] Migration: `stock_movements` table
- [ ] Migration: `transactions` table
- [ ] Migration: `transaction_items` table
- [ ] Migration: `cash_flows` table
- [ ] Migration: `carts` table
- [ ] Migration: `subscription_plans` table
- [ ] Run all migrations successfully
- [ ] Unit tests created and passing
- [ ] Git commit & push

## Unit Test Requirements

Test harus memverifikasi:
1. Koneksi database berhasil
2. Semua tabel migration ada
3. Foreign key constraints bekerja
4. Unique constraints per tenant bekerja

## Definition of Done

- [ ] Semua migration file ada di `database/migrations/` 
- [ ] `php artisan migrate` berjalan tanpa error
- [ ] `php artisan test` semua passing
- [ ] Commit dengan pesan: `Issue #1: Complete core migrations` 
- [ ] Push ke remote GitHub
