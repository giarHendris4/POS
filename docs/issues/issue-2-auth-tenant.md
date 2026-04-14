# Issue #2: Authentication & Multi-Tenancy Middleware

**Status:** 🔄 In Progress
**Priority:** Critical
**Assignee:** Windsurf (via prompt)
**Reviewer:** Senior Engineer
**Depends On:** Issue #1 ✅

## Deskripsi
Setup authentication dengan Laravel Jetstream (Livewire stack), implementasi multi-tenancy middleware untuk isolasi data per tenant, dan tenant registration flow dengan trial subscription.

## Tasks Checklist

### Phase 2.1: Authentication Setup
- [ ] Install Jetstream dengan Livewire (sudah dari Issue #1)
- [ ] Setup authentication views (login, register, dashboard)
- [ ] Customize registration form (tambah field: tenant_name, phone)
- [ ] Create Tenant model and relationships
- [ ] Auto-create tenant saat user register
- [ ] Set trial subscription (14 hari default)

### Phase 2.2: Multi-Tenancy Middleware
- [ ] Create `TenantMiddleware` 
- [ ] Auto-set tenant_id dari authenticated user
- [ ] Scope semua query ke current tenant
- [ ] Global scope trait `BelongsToTenant` 

### Phase 2.3: Role-Based Access
- [ ] Tambah kolom `role` di users table (sudah dari Issue #1)
- [ ] Create `RoleMiddleware` untuk proteksi route owner-only
- [ ] Gate policies untuk authorization

### Phase 2.4: Testing
- [ ] Unit test untuk registration flow
- [ ] Unit test untuk tenant isolation
- [ ] Unit test untuk role middleware

## Definition of Done

- [ ] User bisa register dan otomatis mendapat tenant
- [ ] Trial subscription 14 hari otomatis terpasang
- [ ] Data terisolasi per tenant (user A tidak bisa lihat data tenant B)
- [ ] Owner bisa invite cashier ke tenant
- [ ] Semua unit test passing
- [ ] Git commit & push

## Technical Specifications

### Tenant Registration Flow
```
User Register → Create Tenant → Create User (role: owner) → Set Trial (14 days) → Login
```

### Middleware Stack
```php
Route::middleware([
    'auth:sanctum',
    'verified',
    'tenant.active',      // Cek subscription active/trial
    'tenant.scope'        // Set scope ke current tenant
])->group(function () {
    // Protected routes
});
```

### Global Scope Trait
```php
trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope('tenant', function ($query) {
            if (auth()->check() && auth()->user()->tenant_id) {
                $query->where('tenant_id', auth()->user()->tenant_id);
            }
        });
        
        static::creating(function ($model) {
            if (auth()->check() && !$model->tenant_id) {
                $model->tenant_id = auth()->user()->tenant_id;
            }
        });
    }
}
```
