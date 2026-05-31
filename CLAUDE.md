# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

**Sistem Monitoring Layanan Perbaikan Komputer — Big Party Computer**

A web-based computer repair service management system. It replaces manual paper-based processes (receipt books, verbal technician dispatch, calculator-based reporting) with a digital workflow covering device intake, technician queue management, customer status monitoring, transactions, and reporting.

## Stack

- **Backend**: Laravel 13.x (PHP 8.3+)
- **Frontend**: Livewire 4.x + Tailwind CSS 4.x + Alpine.js (bundled via `@livewireScripts`)
- **Build**: Vite 8 via `laravel-vite-plugin`
- **Excel export**: `maatwebsite/excel` ^3.1
- **PDF generation**: `barryvdh/laravel-dompdf` ^3.1 — used for invoice downloads via `Pdf::loadView()`
- **Database**: SQLite for local dev; production target is MySQL
- **Fonts**: Bunny fonts (Instrument Sans) via `vite.config.js`

## Commands

### Setup (first time)
```bash
composer setup
```
Runs: `composer install`, copies `.env`, generates app key, migrates, `npm install`, `npm run build`.

Seed default admin and teknisi accounts:
```bash
php artisan db:seed
# admin@bigpartycomputer.com / password
# teknisi@bigpartycomputer.com / password
```

### Development (all services)
```bash
composer dev
```
Starts concurrently: PHP dev server, queue worker, Pail log viewer, and Vite HMR.

### Tests
```bash
composer test                                # clears config cache then runs full suite
php artisan test                             # run full suite directly
php artisan test --filter ExampleTest        # run a single test by name
```
Tests use in-memory SQLite — no DB setup needed.

### Code formatting
```bash
vendor/bin/pint         # PHP formatter (Laravel Pint / PSR-12)
```

### Assets
```bash
npm run dev             # Vite dev server (HMR)
npm run build           # production build → public/build/
```

## Domain Model

Three actors, five core entities:

| Actor | Access |
|---|---|
| **Admin** | Full access — devices, queue, transactions, reports, user accounts |
| **Teknisi** (Technician) | Limited — view own assigned queue, input diagnosis, update status |
| **Pelanggan** (Customer) | Public, no login — check repair status via service receipt number |

**Entities & key relations:**
- `PELANGGAN` (`nama`, `telepon`, `alamat`) 1→M `PERANGKAT`
- `PERANGKAT` (`jenis_perangkat`, `merek`, `spesifikasi`, `keluhan`, `kelengkapan`) 1→1 `SERVIS`
- `PENGGUNA` (Teknisi) 1→M `SERVIS`
- `SERVIS` 1→1 `TRANSAKSI`
- `PENGGUNA` (Admin) 1→M `TRANSAKSI`

**Service status flow:** `Antri` → `Dalam Pengerjaan` → `Selesai`  
Status constants live on `Servis`: `STATUS_ANTRI`, `STATUS_DALAM_PENGERJAAN`, `STATUS_SELESAI`.

**`Servis` fields of note:**
- `diagnosa` — technician's diagnosis text
- `catatan` — technician's additional notes (separate from diagnosa)
- `nomor_nota` — auto-generated in the `creating` boot hook, format `BPC/YYYY/MM/NNNN`; not in `#[Fillable]` since it's set directly on the model instance. No `refresh()` needed after `Servis::create()`.

**`Transaksi` fields:** `biaya_jasa`, `biaya_sparepart`, `total`, `metode_bayar` (`cash`/`transfer`), `tanggal_bayar`, `catatan`.

## Modules

| Module | Key features |
|---|---|
| Autentikasi | Login/logout for Admin & Teknisi; Pelanggan has no login |
| Penerimaan Perangkat | Device intake form, auto-generate nomor nota |
| Antrian & Servis (Admin) | Assign device to technician, update status/diagnosa |
| Antrian & Servis (Teknisi) | View own queue, update diagnosa & status |
| Monitoring Status | **Public root page** — customer enters nomor nota, sees real-time status + invoice download if paid |
| Transaksi | Admin records payment (with metode_bayar), views transaction history |
| Laporan | Admin filters by date range, exports to XLSX |
| Kelola Pengguna | Admin CRUD for admin & teknisi accounts |
| Invoice PDF | Public `GET /invoice?nota=...` — dompdf PDF download, only accessible when servis has a transaksi |

## Architecture

### Layouts (`resources/views/components/layouts/`)

Each layout lives in its own subfolder with an `index.blade.php` entry point and partial files alongside it:

| Folder | Partials | Used by |
|---|---|---|
| `admin/` | `index`, `sidebar`, `navbar` | All authenticated Admin pages |
| `teknisi/` | `index`, `sidebar`, `navbar` | All authenticated Teknisi pages |
| `guest/` | `index` only | Login page |
| `public/` | `index` only | Public customer status page |

Admin and Teknisi Livewire components apply the layout in `render()`:
```php
return view('livewire.admin.foo')->layout('components.layouts.admin.index', ['heading' => 'Title']);
return view('livewire.teknisi.foo')->layout('components.layouts.teknisi.index', ['heading' => 'Title']);
```
`Login` uses the class-level `#[Layout('components.layouts.guest.index')]` attribute instead.  
`CekStatus` uses `'components.layouts.public.index'`.

The `index.blade.php` files include their partials via `@include('components.layouts.admin.sidebar')` etc. The `$heading` prop and `sidebarOpen` Alpine state defined on `<body>` are both available in partials automatically through `@include` scope.

### Routes (`routes/web.php`)

| Path | Handler | Middleware |
|---|---|---|
| `GET /` | `Pelanggan\CekStatus` (named `cek-status`) | — |
| `GET /invoice` | `InvoiceController@download` (query: `?nota=`) | — |
| `GET /login` | `Auth\Login` | `guest` |
| `POST /logout` | closure | `auth` |
| `GET /admin/*` | `Admin\*` Livewire | `auth`, `role:admin` |
| `GET /teknisi/dashboard` | `Teknisi\Dashboard` | `auth`, `role:teknisi` |
| `GET /teknisi/antrian-servis` | `Teknisi\AntarianServis` | `auth`, `role:teknisi` |

The invoice route uses a query parameter (`?nota=BPC/2026/05/0001`) instead of a path segment to avoid URL encoding issues with the slash-separated nomor_nota format.

### Role middleware
`role` alias registered in `bootstrap/app.php` → `App\Http\Middleware\RoleMiddleware`.  
Usage: `->middleware('role:admin')` or `->middleware('role:admin,teknisi')`.  
After login, `Login` redirects by role: `match(Auth::user()->role)`.

### Models (`app/Models/`)
- `User` — maps to `PENGGUNA`. `role` column: `admin`/`teknisi`. Helper methods: `isAdmin()`, `isTeknisi()`. Constants: `User::ROLE_ADMIN`, `User::ROLE_TEKNISI`.
- Laravel 13 Eloquent uses PHP attribute syntax: `#[Fillable([...])]` and `#[Hidden([...])]` instead of `$fillable`/`$hidden` array properties. Follow this pattern for all models.
- `Servis` boot hook sets `tanggal_masuk` and `nomor_nota` in `creating` (before INSERT). `nomor_nota` is not in `#[Fillable]` — it's set directly on the model instance inside the hook.

### Livewire components (`app/Livewire/`)

Namespaced by role: `Admin\`, `Teknisi\`, `Pelanggan\`. All are full-page components.

**Common patterns across Admin and Teknisi components:**
- **Pagination**: `use WithPagination`; call `$this->resetPage()` in `updating*` hooks for `$search` / `$filterStatus`. Do NOT add `#[Url]` to these properties — search terms must not appear in the URL for authenticated pages.
- **Modal pattern**: `bool $showModal`, `bool $showDeleteModal`, `?int $editId`, `?int $deleteId`.
- **Alert pattern**: `string $message`, `string $messageType` (`'success'`/`'error'`).
- **Inline validation**: `$this->validate([...])` with Indonesian error messages. Only `Login` uses the `#[Validate]` attribute.

**Teknisi scope**: All Teknisi queries filter by `WHERE teknisi_id = Auth::id()` — teknisi can only see and update their own assigned items.

**`Pelanggan\CekStatus`**: public, no auth. Stores the query result as `?array $result` (not an Eloquent model — Livewire cannot serialize models as public properties reliably). The array includes a `transaksi` key (null or nested array with `biaya_jasa`, `biaya_sparepart`, `total`, `metode_bayar`, `tanggal_bayar`, `catatan`) — presence of this key drives showing the payment section and invoice download button.

**`Admin\PenerimaanPerangkat`**: create path wraps `Pelanggan::create()` + `Perangkat::create()` + `Servis::create()` in a single `DB::transaction()`. The `JENIS_PERANGKAT` constant on this component is the canonical list of device types used in the intake form.

### PDF Invoice (`app/Http/Controllers/InvoiceController.php`)
Fetches `Servis` with `['perangkat.pelanggan', 'teknisi', 'transaksi.admin']`, requires a transaksi to exist (404 otherwise), and streams a dompdf PDF from `resources/views/invoice.blade.php`. The invoice template uses `DejaVu Sans` (dompdf's built-in font) and table-based layout for reliable PDF rendering — avoid flexbox in this template.

### Excel Export (`app/Exports/LaporanTransaksiExport.php`)
Used by `Admin\Laporan::export()`. Implements `FromCollection`, `WithHeadings`, `WithMapping`, `WithStyles`, `WithEvents`, `ShouldAutoSize`. The `AfterSheet` event appends a bold summary row (`"Jumlah: N transaksi"` + totals) after all data rows. Monetary values are formatted as strings (`"Rp X.XXX"`) for consistent Indonesian locale display.

### Frontend
- Livewire views → `resources/views/livewire/{admin,teknisi,pelanggan}/`
- CSS: `resources/css/app.css` (Tailwind 4 CSS-first; `[x-cloak]` rule included)
- Tailwind utility classes only — no separate component CSS files
- Alpine.js for lightweight local state only; Livewire handles all reactive data

### Tests
`tests/Unit/` and `tests/Feature/`; in-memory SQLite, no fixtures needed.
