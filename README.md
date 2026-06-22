# 📋 Proyek CRUD - Pemrograman Web Lanjut

**NIM:** A122407260  
**Mata Kuliah:** Pemrograman Web Lanjut  

---

## 📌 Deskripsi

Proyek ini merupakan implementasi operasi **CRUD (Create, Read, Update, Delete)** menggunakan **PHP Native** sebagai bagian dari tugas mata kuliah Pemrograman Web Lanjut. Aplikasi ini dirancang dengan struktur yang terorganisir untuk memisahkan logika bisnis, tampilan, dan konfigurasi.

---

## 🛠️ Teknologi yang Digunakan

- **PHP** (Backend)
- **MySQL** (Database)
- **CSS** (Styling)
- **HTML** (Tampilan)

---

## 📁 Struktur Direktori

```
proweblanjut-crud-a122407260/
├── api/          # Endpoint API dan logika backend
├── app/          # Logika aplikasi (model, controller, view)
├── assets/       # File statis (gambar, CSS, JS)
├── config/       # Konfigurasi database dan aplikasi
├── publik/       # Entry point dan file yang dapat diakses publik
└── .gitignore
```

---

## ⚙️ Cara Instalasi & Menjalankan

### 1. Clone Repository

```bash
git clone https://github.com/rofiqslhdn/proweblanjut-crud-a122407260.git
cd proweblanjut-crud-a122407260
```

### 2. Konfigurasi Database

- Buat database baru di MySQL, misalnya `db_crud`
- Sesuaikan konfigurasi koneksi database di file `config/database.php`:

```php
$host = 'localhost';
$dbname = 'db_crud';
$username = 'root';
$password = '';
```

### 3. Jalankan Aplikasi

- Letakkan folder proyek di direktori `htdocs` (XAMPP) atau `www` (WAMP)
- Aktifkan **Apache** dan **MySQL** melalui XAMPP/WAMP
- Akses aplikasi melalui browser:

```
http://localhost/proweblanjut-crud-a122407260/publik/
```

---

## ✨ Fitur

- ✅ **Create** — Menambahkan data baru
- ✅ **Read** — Menampilkan daftar data
- ✅ **Update** — Mengubah data yang sudah ada
- ✅ **Delete** — Menghapus data

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan akademik.
