# Project IELTS Reading API

REST API untuk latihan soal IELTS Reading berbasis Laravel 12 dengan autentikasi Sanctum dan role-based access control (admin & user).

---

## Setup Awal

### 1. Clone Repository

```bash
git clone https://github.com/[username]/project-ielts.git
cd project-ielts
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Lalu edit bagian berikut di file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_kamu
DB_USERNAME=nama_username_kamu
DB_PASSWORD=password_kamu

SESSION_DRIVER=file
```

### 4. Generate App Key

```bash
php artisan key:generate
```

### 5. Migrasi Database

```bash
php artisan migrate
```

> **Catatan:** Jalankan `composer install` terlebih dahulu sebelum migrasi, karena Laravel Breeze dan Sanctum perlu terinstall agar file migration tersedia.

### 6. Jalankan Server

```bash
php artisan serve
```

Server akan berjalan di `http://127.0.0.1:8000`

---

## Skema Database

Lihat skema lengkap di: [https://dbdiagram.io/d/IELTS-reading-69a10e7fa3f0aa31e1418de4](https://dbdiagram.io/d/IELTS-reading-69a10e7fa3f0aa31e1418de4)

---

## Dokumentasi API (Swagger)

Lihat dokumentasi lengkap di: [https://app.swaggerhub.com/apis/notyet-f8b/project-ielts-api/1.0.0](https://app.swaggerhub.com/apis/notyet-f8b/project-ielts-api/1.0.0)

---

## Autentikasi

| Endpoint | Bearer Token |
|---|---|
| `POST /api/register` | ❌ Tidak perlu |
| `POST /api/login` | ❌ Tidak perlu |
| Semua endpoint lainnya | ✅ Wajib |

Setelah login, gunakan token yang dikembalikan sebagai **Bearer Token** di header `Authorization`.

---

## Endpoint API

Base URL: `http://127.0.0.1:8000`

---

### Auth

#### Register
`POST /api/register`

```json
{
  "name": "michie",
  "email": "michie@gmail.com",
  "password": "bahagia3",
  "password_confirmation": "bahagia3",
  "role": "user"
}
```
> Field `role` diisi `user` atau `admin`.

---

#### Login
`POST /api/login`

```json
{
  "email": "michie@gmail.com",
  "password": "bahagia3"
}
```

---

#### Logout
`POST /api/logout` — 🔐 Bearer Token

---

### Admin — Passages

> Semua endpoint admin memerlukan Bearer Token dengan role **admin**.

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/admin/passages` | Ambil semua passage |
| POST | `/api/admin/passages` | Buat passage baru |
| GET | `/api/admin/passages/{id}` | Ambil satu passage beserta questions & options |
| PUT | `/api/admin/passages/{id}` | Update passage |
| DELETE | `/api/admin/passages/{id}` | Hapus passage |

**Body POST/PUT:**
```json
{
  "title": "Bacaan IELTS no 1",
  "content": "Biodiversity refers to the variety of life on Earth..."
}
```
> Untuk PUT, kirim hanya field yang ingin diubah.

---

### Admin — Questions

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/admin/questions` | Ambil semua question |
| POST | `/api/admin/questions` | Buat question baru |
| GET | `/api/admin/questions/{id}` | Ambil satu question |
| PUT | `/api/admin/questions/{id}` | Update question |
| DELETE | `/api/admin/questions/{id}` | Hapus question |

**Body POST/PUT:**
```json
{
  "passage_id": 1,
  "question_text": "What is the main topic of the passage?",
  "question_type": "multiple choice"
}
```

---

### Admin — Options

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/admin/options` | Ambil semua option |
| POST | `/api/admin/options` | Buat option baru |
| GET | `/api/admin/options/{id}` | Ambil satu option |
| PUT | `/api/admin/options/{id}` | Update option |
| DELETE | `/api/admin/options/{id}` | Hapus option |

**Body POST/PUT:**
```json
{
  "question_id": 4,
  "option_label": "D",
  "option_text": "Salah banget",
  "is_correct": false
}
```

---

### User — Exercises

> Semua endpoint user memerlukan Bearer Token dengan role **user**.

#### Ambil Semua Passage
`GET /api/exercises`

---

#### Submit Jawaban
`POST /api/exercises/submit-answer`

Kirim semua jawaban untuk satu passage sekaligus. Setiap `selected_option_id` adalah ID dari tabel `options`. Karena soal berbentuk pilihan ganda, setiap question memiliki beberapa options — pilih **satu** `option_id` per question.

```json
{
  "passage_id": 1,
  "answers": [
    {"selected_option_id": 1},
    {"selected_option_id": 6},
    {"selected_option_id": 9}
  ]
}
```

> **Catatan:** Jumlah objek dalam array `answers` harus sesuai dengan jumlah questions di passage tersebut. Sistem otomatis mendeteksi `question_id` dari option yang dipilih karena setiap option sudah terhubung ke satu question di database.

**Response:**
```json
{
  "message": "Jawaban berhasil dikirim",
  "total_questions": 3,
  "correct_answers": 2,
  "score": 2
}
```

### Prompting AI yang saya gunakan
1. buatkan saya skema tabel relasional (db diagram io) dari sistem latihan soal IELTS reading, tabel tersebut berupa daftar soal dan opsi soal, menyimpan jawaban user, dan memproleh skor yang diperoleh user(dari jawaban yang benar atas opsi soalnya). dan di tabel user memiliki relasi atau connect dengan jawaban user atas pilihan dari opsi soalnya
2. buatkan fitur untuk register dan login user, dimana ketika user mau mengakses route untuk mengerjakan soal, memberikan jawaban dan mendapatkan skor harus login/ register terlebih dahulu (jadi akses nya di batasi menggunakan middleware). dan buat kan fitur role based access control untuk akses admin (membuat soal,pertanyaan, dan jawaban yang benar) dan akses user untuk mengerjakan soal ielts reading tersebut. buat memakai library tambahan laravel breeze(atau yang terbaik, dan tidak usah buatkan view.blade nya hanya buat backend). buat model dan controller nya juga.
3. buat dokumentasi project ielts saya di readme.md yaitu: setup awal mulai dari git clone dr github saya trus edit .env yang database untuk atur nama db connection, nama db, nama username db dan password db nya, dan atur session_driver jdi session_driver = file. trus composer install untuk instalasi laravel breeze, kemudian php artisan migrate untuk migrate semua file migrasi yang telah saya buat. dokumentasi skema tabel yang saya buat di dbdiagram di link ini: https://dbdiagram.io/d/IELTS-reading-69a10e7fa3f0aa31e1418de4 dokumentasi penggunaan api di link ini: https://app.swaggerhub.com/apis/notyet-f8b/project-ielts-api/1.0.0 