# Fast Print - Sistem Manajemen Produk

Aplikasi web untuk manajemen produk Fast Print yang dibangun menggunakan Laravel 11 dengan database PostgreSQL.

## 📋 Deskripsi

Aplikasi ini memungkinkan pengguna untuk:

- Mengambil data produk dari API Fast Print
- Menampilkan daftar semua produk
- Menampilkan produk yang bisa dijual
- Menambah, mengedit, dan menghapus produk
- Mencari produk berdasarkan nama atau kategori

## 🛠️ Teknologi yang Digunakan

- **Framework:** Laravel 11
- **Database:** PostgreSQL
- **Frontend:** Bootstrap (Corporate UI Dashboard)
- **Icons:** Font Awesome 6

## 📦 Struktur Database

### Tabel Produk

| Kolom       | Tipe    | Deskripsi                     |
| ----------- | ------- | ----------------------------- |
| id_produk   | BIGINT  | Primary Key                   |
| nama_produk | VARCHAR | Nama produk                   |
| harga       | DECIMAL | Harga produk                  |
| kategori_id | BIGINT  | Foreign Key ke tabel kategori |
| status_id   | BIGINT  | Foreign Key ke tabel status   |

### Tabel Kategori

| Kolom         | Tipe    | Deskripsi     |
| ------------- | ------- | ------------- |
| id_kategori   | BIGINT  | Primary Key   |
| nama_kategori | VARCHAR | Nama kategori |

### Tabel Status

| Kolom       | Tipe    | Deskripsi                                         |
| ----------- | ------- | ------------------------------------------------- |
| id_status   | BIGINT  | Primary Key                                       |
| nama_status | VARCHAR | Nama status (bisa dijual, tidak bisa dijual, dll) |

## ⚙️ Persyaratan Sistem

- PHP >= 8.2
- Composer
- PostgreSQL
- Ekstensi PHP:
    - pdo_pgsql
    - pgsql
    - zip
    - curl

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd test_junior_programmer_fast_print
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=fastprint_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Buat Database

Buat database PostgreSQL dengan nama `fastprint_db`

### 6. Jalankan Migrasi

```bash
php artisan migrate
```

### 7. Jalankan Server

```bash
php artisan serve
```

Akses aplikasi di: `http://127.0.0.1:8000`

## 📱 Fitur Aplikasi

### 1. Semua Produk (`/produk`)

- Menampilkan daftar semua produk
- Fitur pencarian berdasarkan nama produk atau kategori
- Tombol tambah, edit, dan hapus produk

### 2. Bisa Dijual (`/produk/bisa-dijual`)

- Menampilkan produk dengan status "bisa dijual" saja
- Fitur pencarian
- Tombol tambah, edit, dan hapus produk

### 3. Sync dari API

- Mengambil data produk dari API Fast Print
- Otomatis membuat kategori dan status jika belum ada
- Update produk yang sudah ada berdasarkan nama

### 4. Tambah Produk (`/produk/create`)

- Form dengan validasi:
    - Nama produk wajib diisi
    - Harga wajib berupa angka
    - Kategori dan status wajib dipilih

### 5. Edit Produk (`/produk/{id}/edit`)

- Form edit dengan validasi yang sama
- Redirect kembali ke halaman asal setelah update

### 6. Hapus Produk

- Konfirmasi sebelum menghapus
- Redirect kembali ke halaman asal setelah hapus

## 🔗 API Integration

Aplikasi ini terintegrasi dengan API Fast Print:

- **URL:** `https://recruitment.fastprint.co.id/tes/api_tes_programmer`
- **Method:** POST
- **Authentication:** Username dan Password (MD5)

## 📁 Struktur Folder

```
├── app/
│   ├── Http/Controllers/
│   │   └── ProdukController.php
│   ├── Models/
│   │   ├── Kategori.php
│   │   ├── Produk.php
│   │   └── Status.php
│   └── Services/
│       └── ApiService.php
├── database/
│   └── migrations/
│       ├── 2026_02_05_000001_create_kategori_table.php
│       ├── 2026_02_05_000002_create_status_table.php
│       └── 2026_02_05_000003_create_produk_table.php
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php
│   └── produk/
│       ├── index.blade.php
│       ├── bisa-dijual.blade.php
│       ├── create.blade.php
│       └── edit.blade.php
└── routes/
    └── web.php
```

## 🛣️ Routes

| Method | URI                   | Aksi                      |
| ------ | --------------------- | ------------------------- |
| GET    | `/`                   | Redirect ke `/produk`     |
| GET    | `/produk`             | Daftar semua produk       |
| GET    | `/produk/bisa-dijual` | Daftar produk bisa dijual |
| GET    | `/produk/create`      | Form tambah produk        |
| POST   | `/produk`             | Simpan produk baru        |
| GET    | `/produk/{id}/edit`   | Form edit produk          |
| PUT    | `/produk/{id}`        | Update produk             |
| DELETE | `/produk/{id}`        | Hapus produk              |
| POST   | `/produk/sync`        | Sync dari API             |
