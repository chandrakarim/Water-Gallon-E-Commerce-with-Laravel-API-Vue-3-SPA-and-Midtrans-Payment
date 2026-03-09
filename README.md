# Water Gallon Delivery System
### Laravel 12 REST API • Vue 3 SPA • Midtrans Payment Gateway

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![Vue](https://img.shields.io/badge/Vue-3-green)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-Database-blue)
![Midtrans](https://img.shields.io/badge/Payment-Midtrans-orange)

Water Gallon Delivery System adalah aplikasi **Web Based E-Commerce** untuk pemesanan galon air secara online yang memungkinkan pelanggan melakukan pemesanan galon dengan metode pembayaran **COD (Cash On Delivery)** atau **Online Payment menggunakan Midtrans Snap Payment Gateway**.

Sistem ini dibangun menggunakan arsitektur **REST API Backend Laravel 12** dan **Frontend Single Page Application (SPA) menggunakan Vue.js 3**.

Aplikasi ini memiliki tiga role utama:

- Customer
- Admin
- Courier (Kurir)

Tujuan sistem ini adalah mempermudah proses **pemesanan, pengelolaan pesanan, dan distribusi galon kepada pelanggan** secara digital.

---
# Project Structure

Struktur project repository:

```
water-gallon
│
├── backend-api      # Laravel 12 REST API
│
├── frontend-app     # Vue 3 Single Page Application
│
└── README.md
```

---

# System Architecture

```
Customer Browser
        │
        ▼
Frontend SPA (Vue.js 3 + Vite)
        │
        ▼
REST API Backend (Laravel 12)
        │
        ▼
PostgreSQL Database
        │
        ▼
Midtrans Snap Payment Gateway
```

---

# Technology Stack

## Backend

Backend menggunakan **Laravel 12** dengan pendekatan **Laravel Best Practice Architecture** untuk menjaga kode tetap terstruktur, scalable, dan mudah di maintenance.

### Core Technology

- Laravel 12 REST API
- PostgreSQL Database
- Midtrans Snap Payment Gateway
- Laravel Sanctum Authentication
- Laravel Policy Authorization

### Laravel Best Practice Architecture

Struktur backend dipisahkan berdasarkan tanggung jawab masing-masing layer:

- **Controllers**  
  Mengatur alur request dan response API.

- **Form Requests**  
  Validasi data request menggunakan Laravel Form Request.

- **Services**  
  Berisi business logic aplikasi agar controller tetap bersih.

- **API Resources**  
  Mengatur format response API agar konsisten.

- **Policies**  
  Mengatur authorization atau hak akses user terhadap resource.

- **Observers**  
  Meng-handle event model seperti created, updated, deleted.

- **Helpers**  
  Berisi fungsi helper global yang digunakan di berbagai bagian aplikasi.

### Backend Architecture Layer

```
Controller
   ↓
Request Validation
   ↓
Service Layer
   ↓
Model / Database
   ↓
API Resource Response
```

Pendekatan ini membuat kode lebih:

- Modular
- Maintainable
- Scalable
- Clean Architecture

---

## Frontend

Frontend menggunakan **Vue 3 Single Page Application (SPA)**.

Dependencies yang digunakan:

```
vue
vue-router
pinia
axios
tailwindcss
heroicons
chart.js
vite
```

Penjelasan:

| Library | Fungsi |
|------|------|
| Vue 3 | Framework frontend |
| Vue Router | Routing halaman SPA |
| Pinia | State management |
| Axios | HTTP request ke API |
| TailwindCSS | Styling UI |
| Heroicons | Icon UI |
| Chart.js | Grafik dashboard admin |
| Vite | Development server |

---

# Development Tools

Tools yang digunakan selama pengembangan proyek:

| Tools | Fungsi |
|------|------|
| Laragon | Web server lokal |
| DBeaver | Database manager PostgreSQL |
| Postman | Testing API |
| Ngrok | Webhook Midtrans |

---

# Order Lifecycle

Sistem memiliki alur status pesanan sebagai berikut:

```
WAITING_PAYMENT → WAITING_ASSIGN → ASSIGNED → PICKED_UP → DELIVERED
```

Penjelasan:

| Status | Deskripsi |
|------|------|
| WAITING_PAYMENT | Order dibuat dan menunggu pembayaran |
| WAITING_ASSIGN | Pembayaran berhasil dan menunggu admin assign kurir |
| ASSIGNED | Admin sudah menentukan kurir |
| PICKED_UP | Kurir mengambil pesanan dari depot |
| DELIVERED | Pesanan sudah sampai ke customer |

Jika metode pembayaran **COD**, maka pembayaran dilakukan saat barang diterima oleh customer.

---

# Payment Method

Sistem mendukung dua metode pembayaran.

## 1. COD (Cash On Delivery)

Customer membayar langsung kepada kurir saat pesanan sampai.

## 2. Online Payment (Midtrans)

Pembayaran dilakukan melalui **Midtrans Snap Payment Gateway**.

Metode yang tersedia:

- Bank Transfer
- Virtual Account
- E-Wallet
- QRIS

---

# User Roles

| Role | Deskripsi |
|-----|-----|
| Customer | Melakukan pemesanan galon |
| Admin | Mengelola produk, pesanan, dan assign kurir |
| Courier | Mengantar pesanan ke customer |

---

# System Flow

## Customer Flow

1. Customer melakukan **register atau login**.
2. Customer melihat **daftar produk galon**.
3. Customer menambahkan produk ke **cart**.

![Screenshot_1](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Screenshot_1.png)

4. Customer melakukan **checkout**.

![Screenshot_2](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Screenshot_2.png)

5. Customer memilih metode pembayaran:
   - COD
   - Midtrans Payment
- Note untuk pengujian metode pembayaran : Midtrans Payment

![Screenshot_3](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Screenshot_3.png)

- untuk ujicoba metode pembayaran bisa menggunakan link di bawah ini
```
https://simulator.sandbox.midtrans.com/bca/va/index
```
![Screenshot_4](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Screenshot_4.png)

![Screenshot_5](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Screenshot_5.png)

6. Sistem membuat order dengan status:

```
WAITING_PAYMENT
```

atau

```
WAITING_ASSIGN
```
![Screenshot_6](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Screenshot_6.png)

7. Jika pembayaran Midtrans berhasil maka order berubah menjadi:

```
WAITING_ASSIGN
```

![Screenshot_7](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Screenshot_7.png)

---

## Admin Flow

1. Admin login ke dashboard.
2. Admin melihat daftar pesanan customer.

![Screenshot_8](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Screenshot_8.png)

3. Admin memilih pesanan yang sudah dibayar.
4. Admin melakukan **assign courier**.

![Screenshot_9](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Screenshot_9.png)
5. Status order berubah menjadi:

```
ASSIGNED
```
- jika kurir sudah berhasil mengirim barang sampai tujuan,maka di halaman admin menu Deliveries status akan berubah menjadi Delivered
![Screenshot_14](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Screenshot_14.png) 
```
DELIVERED
```
![Screenshot_15](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Screenshot_15.png) 

![Screenshot_16](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Screenshot_16.png) 

![Screenshot_17](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Screenshot_17.png) 

---

## Courier Flow

1. Courier login ke dashboard.
2. Courier melihat daftar pesanan yang sudah diassign.

![Screenshot_10](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Screenshot_10.png)

3. Courier melakukan **pickup barang dari depot**.

![Screenshot_11](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Screenshot_11.png)

Status berubah menjadi:

```
PICKED_UP
```

4. Courier mengantar pesanan ke customer.

![Screenshot_12](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Screenshot_12.png)
Status berubah menjadi:

```
DELIVERED
```
![Screenshot_13](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Screenshot_13.png)

Jika pembayaran **COD**, maka pembayaran dilakukan saat delivery (Pesanan sampai).

---

# Backend Architecture

Backend menggunakan beberapa best practice Laravel.

## Service Layer

Memisahkan business logic dari controller.

```
Controller
     │
     ▼
Service Layer
     │
     ▼
Model
```

---

## Form Request Validation

Digunakan untuk validasi request dari client.

Contoh:

```
StoreOrderRequest
UpdateProductRequest
RegisterRequest
```

---

## Policy Authorization

Digunakan untuk membatasi hak akses user.

Contoh:

- Admin hanya boleh mengelola produk
- Courier hanya boleh update delivery status

---

## API Resource

Digunakan untuk mentransformasi response API.

Contoh:

```
ProductResource
OrderResource
UserResource
```

---

# API Routes Overview

Berikut struktur endpoint API utama.

## Authentication

```
POST /api/auth/register
POST /api/auth/login
POST /api/logout
```

---

## Products

```
GET /api/public/products
GET /api/public/products/{id}
```

Customer login:

```
GET /api/products
GET /api/products/{id}
```

Admin:

```
POST /api/admin/products
PUT /api/admin/products/{id}
DELETE /api/admin/products/{id}
```

---

## Cart

```
GET /api/cart
POST /api/cart/add
PATCH /api/cart/{id}
DELETE /api/cart/{id}
DELETE /api/cart
```

---

## Orders

```
POST /api/orders/checkout
GET /api/orders
GET /api/orders/{id}
GET /api/orders/{id}/tracking
```

---

## Admin Orders

```
GET /api/admin/orders
GET /api/admin/orders/{id}
PATCH /api/admin/orders/{id}/assign
```

---

## Courier

```
GET /api/courier/orders
PATCH /api/courier/orders/{id}/pickup
PATCH /api/courier/orders/{id}/deliver
GET /api/courier/history
```

---

# Database

Database menggunakan **PostgreSQL**.

Database dikelola menggunakan **DBeaver** untuk:

- membuat database
- mengelola tabel
- menjalankan query
- monitoring data

---

# Testing API

Testing API dilakukan menggunakan **Postman**.

Endpoint yang diuji antara lain:

```
POST /api/auth/login
POST /api/auth/register
GET /api/products
POST /api/orders/checkout
POST /api/midtrans/callback
```

---
# Midtrans Configuration

Tambahkan konfigurasi berikut pada file `.env` di backend Laravel.

```env
# Midtrans Config
MIDTRANS_SERVER_KEY=Mid-server-xxxxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=Mid-client-xxxxxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false

FRONTEND_URL=http://localhost:5173
```

Jika belum memiliki API Midtrans silakan daftar akun di:

https://midtrans.com/id

Kemudian ambil **Server Key** dan **Client Key** di **Dashboard Sandbox Midtrans**.

---
# Running the Project

## 1 Jalankan Backend

Masuk ke folder backend-api:

```
cd backend-api
```

Install dependency:

```
composer install
```

Copy env:

```
cp .env.example .env
```

Generate key:

```
php artisan key:generate
```

Migration database:

```
php artisan migrate
php artisan db:seed

```

Run server:

```
php artisan serve
```

Backend berjalan di:

```
http://127.0.0.1:8000
```

---

# Jalankan Ngrok (Webhook Midtrans)

```
ngrok http 8000
```

Contoh URL:

```
https://xxxx.ngrok.io
```

Callback Midtrans:

```
https://xxxx.ngrok.io/api/midtrans/callback
```

---

# Jalankan Frontend

Masuk ke folder frontend-app:

```
cd frontend-app
```

Install dependency:

```
npm install
```

Run development server:

```
npm run dev
```

Frontend berjalan di:

```
http://localhost:5173
```

---

# Development URLs

Backend API

```
http://127.0.0.1:8000
```

Frontend

```
http://localhost:5173
```
Jalankan Ngrok (Webhook Midtrans)

```
ngrok http 8000
```
---

Notes: untuk menjalankan projek ini, jika menggunakan Laragon silahkan menjalankan di terimnal sesuai alur di Running the Project atau Development URLs.

---

# Future Improvements

Pengembangan yang dapat ditambahkan:

- Tracking lokasi kurir
- Notifikasi realtime
- Mobile application
- Multi cabang depot
- Manajemen stok galon

---

# Author
Chandra Karim
Water Gallon Delivery System  
Fullstack Web Development Project

## Example Home
![Home](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Home.png)

## Example Home Product
![Home_product](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Home_product.png)

## Example Home Tentang
![Home_tentang](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Home_tentang.png)

## Customer - Product
![Customer_product](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Customer_product.png)

## Customer - Pesanan Saya
![Customer_pesanan_saya](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Customer_pesanan_saya.png)

## Customer - Detail Pesanan
![Customer_pesanan_detail](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Customer_pesanan_detail.png)

## Customer - Tracking Pesanan
![Customer_tracking](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Customer_tracking.png)

## Customer - Keranjang
![Customer_keranjang](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Customer_keranjang.png)

## Customer - Address
![Customer_address](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Customer_address.png)

## Courier - Dashboard
![Courier_dashboard](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Courier_dashboard.png)

## Courier - Assign Orders
![Courier_Assign_orders](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Courier_Assign_orders.png)

## Courier - Assign Orders Detail
![Courier_Assign_orders_detail](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Courier_Assign_orders_detail.png)

## Admin - Dashboard
![Admin_dashboard](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Admin_dashboard.png)

## Admin - Products
![Admin_products](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Admin_products.png)

## Admin - Orders
![Admin_Orders](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Admin_Orders.png)

## Admin - Orders Detail
![Admin_orders_detail](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Admin_orders_detail.png)

## Admin - Deliveries Management
![Admin_deliveries_management](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Admin_deliveries_management.png)

## Admin - Deliveries Management Detail
![Admin_deliveries_management_detail](https://raw.githubusercontent.com/chandrakarim/Water-Gallon-E-Commerce-with-Laravel-API-Vue-3-SPA-and-Midtrans-Payment/main/Admin_deliveries_management_detail.png)