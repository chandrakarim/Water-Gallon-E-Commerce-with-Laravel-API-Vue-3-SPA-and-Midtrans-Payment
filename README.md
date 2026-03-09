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

Backend menggunakan **Laravel 12** dengan pendekatan best practice.

- Laravel 12 REST API
- PostgreSQL Database
- Midtrans Snap Payment Gateway
- Laravel Sanctum Authentication
- Laravel Policy Authorization
- Service Layer Architecture
- Form Request Validation
- API Resource Transformer

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
4. Customer melakukan **checkout**.
5. Customer memilih metode pembayaran:
   - COD
   - Midtrans Payment
6. Sistem membuat order dengan status:

```
WAITING_PAYMENT
```

atau

```
WAITING_ASSIGN
```

7. Jika pembayaran Midtrans berhasil maka order berubah menjadi:

```
WAITING_ASSIGN
```

---

## Admin Flow

1. Admin login ke dashboard.
2. Admin melihat daftar pesanan customer.
3. Admin memilih pesanan yang sudah dibayar.
4. Admin melakukan **assign courier**.
5. Status order berubah menjadi:

```
ASSIGNED
```

---

## Courier Flow

1. Courier login ke dashboard.
2. Courier melihat daftar pesanan yang sudah diassign.
3. Courier melakukan **pickup barang dari depot**.

Status berubah menjadi:

```
PICKED_UP
```

4. Courier mengantar pesanan ke customer.

Status berubah menjadi:

```
DELIVERED
```

Jika pembayaran **COD**, maka pembayaran dilakukan saat delivery.

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

# Running the Project

## 1 Jalankan Backend

Masuk ke folder backend:

```
cd backend
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

Masuk ke folder frontend:

```
cd frontend
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

## Example Dashboard Admin Data Kota