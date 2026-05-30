Baik, revisi dulu bagian Laporan:

---

**Revisi ERD — hapus entitas LAPORAN:**

| Relasi | Kardinalitas | Keterangan |
|--------|-------------|------------|
| PELANGGAN — PERANGKAT | 1 : M | Satu pelanggan bisa menyerahkan banyak perangkat |
| PERANGKAT — SERVIS | 1 : 1 | Satu perangkat memiliki satu data servis aktif |
| PENGGUNA (Teknisi) — SERVIS | 1 : M | Satu teknisi bisa menangani banyak servis |
| SERVIS — TRANSAKSI | 1 : 1 | Satu servis menghasilkan satu transaksi |
| PENGGUNA (Admin) — TRANSAKSI | 1 : M | Satu admin bisa memproses banyak transaksi |

Entitas **LAPORAN dihapus** — fitur laporan cukup melakukan *query* langsung ke tabel **TRANSAKSI** dengan filter periode, tidak memerlukan tabel tersendiri.

Pada DFD, **D5 Data Laporan juga dihapus** — Proses 5.3 Generate Laporan langsung membaca dari **D4 Data Transaksi** dan hasilnya langsung ditampilkan/dicetak ke Admin tanpa disimpan ke datastore terpisah.

---

Sekarang lanjut ke PRD:

---

# PRD — Product Requirements Document
## Sistem Monitoring Layanan Perbaikan Komputer pada Big Party Computer

---

## 1. Overview Produk

| Item | Detail |
|------|--------|
| Nama Produk | Sistem Monitoring Layanan Perbaikan Komputer Big Party Computer |
| Tipe Produk | Aplikasi Web (*Web-based Application*) |
| Pengguna | Admin, Teknisi, Pelanggan |
| Tujuan Utama | Mendigitalisasi seluruh alur operasional layanan servis komputer dan menyediakan fitur monitoring status perbaikan *real-time* bagi pelanggan |
| Metode Pengembangan | SDLC Model Waterfall |

---

## 2. Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | PHP 8.x |
| Framework | Laravel 11.x |
| Frontend Reaktif | Livewire 3.x |
| CSS Framework | Tailwind CSS 3.x |
| Database | MySQL 8.x |
| DB Management | phpMyAdmin |
| Server Lokal | XAMPP (Apache) |
| Code Editor | Visual Studio Code |
| Version Control | Git |
| Browser Target | Chrome, Firefox, Edge (desktop & mobile) |

---

## 3. Aktor & Hak Akses

| Aktor | Autentikasi | Hak Akses |
|-------|-------------|-----------|
| **Admin** | Login (email + password) | CRUD semua data, distribusi antrian, transaksi, laporan |
| **Teknisi** | Login (email + password) | Lihat antrian, input diagnosa, update status servis |
| **Pelanggan** | Tanpa login | Cek status perbaikan via nomor nota servis |

---

## 4. Fitur & Modul Sistem

### 4.1 Modul Autentikasi
| Fitur | Aktor | Deskripsi |
|-------|-------|-----------|
| Login | Admin, Teknisi | Input email & password, validasi ke tabel `pengguna`, redirect sesuai role |
| Logout | Admin, Teknisi | Hapus session, redirect ke halaman login |
| Manajemen Akun | Admin | CRUD akun admin dan teknisi |

---

### 4.2 Modul Penerimaan Perangkat
| Fitur | Aktor | Deskripsi |
|-------|-------|-----------|
| Input data pelanggan | Admin | Isi form: nama, alamat, no. telepon |
| Input data perangkat | Admin | Isi form: jenis, merek, spesifikasi, keluhan, kelengkapan |
| Generate nomor nota servis | Sistem | Otomatis dibuat saat data disimpan, format: `BPC-YYYYMMDD-XXX` |
| Cetak / tampil nota servis | Admin | Nota digital ditampilkan, bisa dicetak atau dicatat pelanggan |
| Lihat daftar perangkat masuk | Admin | Tabel semua perangkat yang sedang dalam proses servis |

---

### 4.3 Modul Manajemen Antrian & Servis
| Fitur | Aktor | Deskripsi |
|-------|-------|-----------|
| Lihat antrian perangkat | Admin, Teknisi | Daftar perangkat berdasarkan urutan masuk & prioritas |
| Distribusi pekerjaan | Admin | Assign perangkat ke teknisi tertentu |
| Input diagnosa | Teknisi | Isi catatan hasil pemeriksaan kerusakan perangkat |
| Update status servis | Teknisi | Ubah status: **Antri → Diagnosa → Dalam Perbaikan → Selesai** |
| Riwayat servis | Admin | Lihat histori semua pekerjaan yang pernah masuk |

---

### 4.4 Modul Monitoring Status (Publik)
| Fitur | Aktor | Deskripsi |
|-------|-------|-----------|
| Form cek status | Pelanggan | Input nomor nota servis di halaman publik (tanpa login) |
| Tampil status *real-time* | Pelanggan | Sistem menampilkan: nama pelanggan, nama perangkat, keluhan, status terkini, catatan teknisi, estimasi selesai |
| Indikator status visual | Pelanggan | Status ditampilkan dengan *badge* berwarna: Antri (abu), Diagnosa (biru), Dalam Perbaikan (kuning), Selesai (hijau) |

---

### 4.5 Modul Transaksi
| Fitur | Aktor | Deskripsi |
|-------|-------|-----------|
| Input pembayaran | Admin | Isi form: biaya jasa, biaya komponen, metode bayar, tanggal bayar |
| Nota transaksi digital | Admin | Sistem generate ringkasan transaksi yang bisa dicetak |
| Riwayat transaksi | Admin | Tabel semua transaksi dengan filter tanggal & status |

---

### 4.6 Modul Laporan
| Fitur | Aktor | Deskripsi |
|-------|-------|-----------|
| Filter periode laporan | Admin | Pilih rentang tanggal (harian, mingguan, bulanan) |
| Rekap total pendapatan | Admin | Total pemasukan dari semua transaksi sesuai periode |
| Rekap jumlah servis | Admin | Total perangkat masuk, selesai, dan sedang diproses |
| Cetak / ekspor laporan | Admin | Tampil di layar dan bisa dicetak langsung dari browser |

> Laporan diambil langsung dari query tabel `transaksi` — tidak ada tabel laporan tersendiri.

---

## 5. Detail Database

### Tabel: `pengguna`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id_pengguna | INT, PK, AI | Primary key |
| nama | VARCHAR(100) | Nama lengkap |
| email | VARCHAR(100), UNIQUE | Email login |
| password | VARCHAR(255) | Hash bcrypt |
| role | ENUM('admin','teknisi') | Hak akses |
| telepon | VARCHAR(20) | Nomor telepon |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

---

### Tabel: `pelanggan`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id_pelanggan | INT, PK, AI | Primary key |
| nama_pelanggan | VARCHAR(100) | Nama pelanggan |
| alamat | TEXT | Alamat pelanggan |
| no_telepon | VARCHAR(20) | Nomor telepon |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

---

### Tabel: `perangkat`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id_perangkat | INT, PK, AI | Primary key |
| id_pelanggan | INT, FK | Relasi ke tabel pelanggan |
| jenis_perangkat | VARCHAR(50) | Laptop / PC / Printer / dll |
| merek | VARCHAR(50) | Merek perangkat |
| spesifikasi | TEXT | Detail spesifikasi |
| keluhan | TEXT | Keluhan yang disampaikan pelanggan |
| kelengkapan | TEXT | Kelengkapan yang diserahkan (charger, tas, dll) |
| tanggal_masuk | DATE | Tanggal perangkat diterima |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

---

### Tabel: `servis`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id_servis | INT, PK, AI | Primary key |
| id_perangkat | INT, FK | Relasi ke tabel perangkat |
| id_teknisi | INT, FK, NULL | Relasi ke tabel pengguna (role teknisi), nullable jika belum diassign |
| no_nota_servis | VARCHAR(30), UNIQUE | Format: BPC-YYYYMMDD-XXX |
| diagnosa | TEXT, NULL | Catatan diagnosa teknisi |
| status | ENUM('Antri','Diagnosa','Dalam Perbaikan','Selesai') | Status terkini perbaikan |
| estimasi_selesai | DATE, NULL | Estimasi tanggal selesai |
| tanggal_selesai | DATE, NULL | Tanggal aktual selesai |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

---

### Tabel: `transaksi`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id_transaksi | INT, PK, AI | Primary key |
| id_servis | INT, FK | Relasi ke tabel servis |
| id_admin | INT, FK | Relasi ke tabel pengguna (role admin) |
| biaya_jasa | DECIMAL(10,2) | Biaya jasa perbaikan |
| biaya_komponen | DECIMAL(10,2) | Biaya penggantian komponen |
| total_biaya | DECIMAL(10,2) | Total keseluruhan |
| metode_bayar | ENUM('Tunai','Transfer') | Metode pembayaran |
| status_bayar | ENUM('Lunas','Belum Lunas') | Status pembayaran |
| tanggal_bayar | DATE | Tanggal transaksi |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

---

## 6. Detail Flow per Fitur

### Flow 1 — Penerimaan Perangkat (Admin)
```
1. Admin login → dashboard admin
2. Pilih menu "Penerimaan Perangkat"
3. Klik tombol "Tambah Perangkat Masuk"
4. Isi form:
   - Data pelanggan (nama, alamat, telepon)
     → jika pelanggan sudah pernah ada, bisa search dari database
   - Data perangkat (jenis, merek, spesifikasi, keluhan, kelengkapan)
   - Tanggal masuk (default: hari ini)
5. Klik "Simpan"
6. Sistem:
   - INSERT ke tabel pelanggan (jika baru)
   - INSERT ke tabel perangkat
   - INSERT ke tabel servis dengan status = "Antri"
   - Generate no_nota_servis otomatis
7. Sistem tampilkan halaman nota servis digital
8. Admin informasikan nomor nota ke pelanggan
```

---

### Flow 2 — Distribusi Antrian ke Teknisi (Admin)
```
1. Admin buka menu "Manajemen Antrian"
2. Sistem tampilkan daftar perangkat berstatus "Antri"
   (diurutkan berdasarkan tanggal_masuk ASC)
3. Admin pilih perangkat → klik "Assign Teknisi"
4. Pilih nama teknisi dari dropdown
5. Klik "Simpan"
6. Sistem UPDATE tabel servis:
   - id_teknisi = teknisi yang dipilih
7. Antrian tampil di dashboard teknisi yang bersangkutan
```

---

### Flow 3 — Proses Servis oleh Teknisi
```
1. Teknisi login → dashboard teknisi
2. Dashboard menampilkan daftar perangkat yang di-assign ke teknisi ini
   (status: Antri / Dalam Perbaikan)
3. Teknisi pilih perangkat → klik "Mulai Diagnosa"
4. Sistem UPDATE status → "Diagnosa"
5. Teknisi isi form diagnosa (catatan kerusakan, estimasi selesai)
6. Klik "Simpan Diagnosa"
7. Teknisi klik "Mulai Perbaikan"
8. Sistem UPDATE status → "Dalam Perbaikan"
9. Setelah selesai, teknisi klik "Tandai Selesai"
10. Sistem UPDATE:
    - status → "Selesai"
    - tanggal_selesai = hari ini
```

---

### Flow 4 — Cek Status oleh Pelanggan (Tanpa Login)
```
1. Pelanggan buka website Big Party Computer
2. Masuk ke halaman "Cek Status Perbaikan"
3. Input nomor nota servis (contoh: BPC-20260510-001)
4. Klik "Cek Status"
5. Sistem query tabel servis JOIN perangkat JOIN pelanggan
   WHERE no_nota_servis = input
6. Jika ditemukan → tampilkan:
   - Nama pelanggan
   - Jenis & merek perangkat
   - Keluhan awal
   - Status terkini (badge berwarna)
   - Catatan diagnosa teknisi
   - Estimasi selesai
7. Jika tidak ditemukan → tampilkan pesan:
   "Nomor nota servis tidak ditemukan"
```

---

### Flow 5 — Transaksi Pembayaran (Admin)
```
1. Admin buka menu "Transaksi"
2. Pilih perangkat berstatus "Selesai" yang belum ada transaksinya
3. Klik "Buat Transaksi"
4. Isi form:
   - Biaya jasa
   - Biaya komponen (boleh 0)
   - Total otomatis terhitung
   - Metode bayar (Tunai / Transfer)
   - Status bayar
   - Tanggal bayar
5. Klik "Simpan"
6. Sistem INSERT ke tabel transaksi
7. Sistem tampilkan nota transaksi digital (siap cetak)
```

---

### Flow 6 — Generate Laporan (Admin)
```
1. Admin buka menu "Laporan"
2. Pilih filter:
   - Rentang tanggal (dari - sampai)
   - Atau pilih shortcut: Hari ini / Minggu ini / Bulan ini
3. Klik "Tampilkan Laporan"
4. Sistem query tabel transaksi
   JOIN servis JOIN perangkat JOIN pelanggan
   WHERE tanggal_bayar BETWEEN tanggal_awal AND tanggal_akhir
5. Sistem menampilkan:
   - Tabel detail semua transaksi pada periode tersebut
   - Total pendapatan (SUM total_biaya)
   - Total servis selesai (COUNT)
   - Ringkasan per metode pembayaran
6. Admin bisa cetak langsung via browser (print CSS)
```

---

## 7. Halaman / Route Sistem

| Halaman | Route | Aktor | Keterangan |
|---------|-------|-------|------------|
| Login | `/login` | Admin, Teknisi | Form login |
| Dashboard Admin | `/admin/dashboard` | Admin | Ringkasan data & statistik |
| Dashboard Teknisi | `/teknisi/dashboard` | Teknisi | Daftar antrian pekerjaan |
| Penerimaan Perangkat | `/admin/perangkat` | Admin | CRUD data penerimaan |
| Detail Perangkat | `/admin/perangkat/{id}` | Admin | Detail + nota servis |
| Manajemen Antrian | `/admin/antrian` | Admin | Assign teknisi |
| Manajemen Servis (Teknisi) | `/teknisi/servis` | Teknisi | Update diagnosa & status |
| Transaksi | `/admin/transaksi` | Admin | CRUD transaksi |
| Laporan | `/admin/laporan` | Admin | Filter & tampil laporan |
| Cek Status (Publik) | `/cek-status` | Pelanggan | Input nomor nota, tanpa login |
| Manajemen Pengguna | `/admin/pengguna` | Admin | CRUD akun admin & teknisi |

---

## 8. Komponen Livewire yang Dibutuhkan

| Komponen | Fungsi |
|----------|--------|
| `PenerimaanPerangkatForm` | Form input perangkat baru dengan validasi real-time |
| `AntrianTable` | Tabel antrian dengan filter & assign teknisi tanpa reload |
| `UpdateStatusServis` | Tombol update status oleh teknisi secara reaktif |
| `CekStatusForm` | Form publik cek status + tampil hasil tanpa reload halaman |
| `TransaksiForm` | Form transaksi dengan kalkulasi total otomatis |
| `LaporanFilter` | Filter periode laporan + render tabel hasil secara dinamis |