# E-Commerce Marketplace (Mini Project)

Mini project Laravel untuk kebutuhan tes seleksi: modul sederhana **E-Commerce Marketplace** tanpa payment gateway.

Fokus utama:
- **Struktur data** (Seller, Product)
- **Create + Read**
- **Validasi & business rule**

---

## Setup & Menjalankan Project

### 1) Install dependency
```bash
composer install
```

### 2) Setup environment
Copy `.env.example` ke `.env`, lalu set konfigurasi database.

```bash
php artisan key:generate
```

### 3) Migrasi + seed
```bash
php artisan migrate --seed
```

### 4) Jalankan aplikasi
```bash
php artisan serve
```

### 5) (Opsional) Jalankan Tailwind via Vite
```bash
npm install
npm run dev
```

---

## Database & Technical Requirement

Project ini memenuhi requirement teknis berikut:

### 1) Laravel Migration
- Migration dibuat untuk tabel:
  - `sellers`
  - `products`

File:
- `database/migrations/2025_12_22_000003_create_sellers_table.php`
- `database/migrations/2025_12_22_000004_create_products_table.php`

Struktur tabel:
- **sellers**
  - `id`
  - `name` (Nama Toko)
  - `email` (unique)
  - `status` enum: `active|inactive`
  - timestamps
- **products**
  - `id`
  - `seller_id` (FK -> `sellers.id`, cascade on delete)
  - `name` (Nama Produk)
  - `price` (decimal)
  - `stock` (unsigned integer)
  - `status` enum: `active|inactive`
  - timestamps

### 2) Eloquent Model & Relationship
Model yang digunakan:
- `app/Models/Seller.php`
- `app/Models/Product.php`

Relasi:
- `Seller::products()` => `hasMany(Product::class)`
- `Product::seller()` => `belongsTo(Seller::class)`

### 3) Laravel Validation
Validasi dilakukan di layer route handler (closure) saat create data:

- Seller:
  - `name`: required, string, max 255
  - `email`: required, email
  - `status`: required, in active/inactive

- Product:
  - `name`: required, string, max 255
  - `seller_id`: required, integer
  - `price`: required, numeric, **gt:0**
  - `stock`: required, integer, **min:0**
  - `status`: required, in active/inactive

### 4) Business Rule
Business rule yang diterapkan:
- **Produk tidak dapat ditambahkan jika seller berstatus `inactive`**
- **Harga produk harus > 0**
- **Stok tidak boleh negatif**

Catatan:
- Rule seller inactive dicek saat create product di route `superadmin.products.store`.

---

## Data Entry Task (Seeder)

Seeder tersedia dan dipanggil lewat `DatabaseSeeder`.

File:
- `database/seeders/SellerSeeder.php`
- `database/seeders/ProductSeeder.php`

Hasil seeding:
- **2 Seller aktif**
- **1 Seller nonaktif**
- **5 Produk**

### Contoh data gagal validasi / rule
Di `ProductSeeder`, ada contoh data yang **disengaja gagal** karena melanggar business rule:
- Produk mencoba dibuat untuk seller dengan status `inactive`.

Seeder akan menampilkan pesan `Skip seed invalid product: seller status inactive` dan tidak menyimpan data tersebut.

---

## Akun Login (Seed)

Setelah menjalankan `php artisan migrate --seed` atau `php artisan migrate:fresh --seed`, akun berikut otomatis dibuat (password sudah di-hash menggunakan `Hash::make(...)`).

### Superadmin
- Email: `superadmin@marketplace.test`
- Password: `password123`

### Seller
- Email: `seller.alpha@marketplace.test`
- Password: `password123`

atau

- Email: `seller.beta@marketplace.test`
- Password: `password123`

Login URL:
- Seller: `/login/seller`
- Superadmin: `/login/superadmin`

Setelah login:
- Superadmin diarahkan ke `/superadmin/sellers`
- Seller diarahkan ke `/seller/products`

## Testing & Analisis

### Bagaimana memastikan fitur berjalan dengan benar?
- Jalankan `php artisan migrate:fresh --seed` lalu cek:
  - Halaman daftar seller menampilkan 3 data seed
  - Halaman daftar product menampilkan 5 data seed
- Uji form create:
  - Create seller valid
  - Create product valid (seller aktif)
  - Create product invalid (seller inactive)
  - Input price = 0 (harus ditolak)
  - Input stock = -1 (harus ditolak)

### Contoh skenario error & penanganannya
- **Seller inactive tapi dipilih saat create product**
  - Ditolak dengan error di field `seller_id`.
- **Price <= 0**
  - Ditolak oleh rule validation `gt:0`.
- **Stock negatif**
  - Ditolak oleh rule validation `min:0`.
- **Seller/Product tidak ditemukan**
  - Ditangani dengan pesan error (mis. saat add-to-cart: “Produk tidak ditemukan”).

---

## Screenshot (tempat menaruh di README)
Tambahkan screenshot hasil aplikasi berjalan:

- Halaman daftar seller
  - `docs/screenshots/sellers-index.png`
- Halaman daftar produk
  - `docs/screenshots/products-index.png`
- Contoh proses tambah data
  - `docs/screenshots/create-product.png`

---

## Struktur Folder Terkait
- `app/Models` (Seller, Product)
- `database/migrations` (sellers, products)
- `database/seeders` (SellerSeeder, ProductSeeder)
- `resources/views` (Blade UI untuk guest, seller, superadmin)
