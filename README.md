# 🎀 LadyStyle Shop - Website Toko Online Fashion

Selamat datang di **LadyStyle Shop**! Ini adalah website toko online fashion wanita yang modern dan estetik. Panduan ini dibuat khusus agar kamu bisa menginstal dan menjalankan website ini di laptopmu dengan mudah, meskipun belum pernah coding sebelumnya.

---

## 🛠️ Persiapan (Wajib Download)

Kita akan menggunakan aplikasi **Laragon** untuk menjalankan website ini. Laragon itu ibarat "mesin" server di laptopmu.

1.  **Download Laragon**
    * Link: [Download Laragon Full](https://github.com/leokhoa/laragon/releases/download/6.0.0/laragon-wamp.exe)
    * Install seperti biasa (Next > Next > Install).

2.  **Download Project Ini**
    * Klik tombol hijau **<> Code** di halaman GitHub ini -> Pilih **Download ZIP**.
    * Simpan di folder *Downloads* atau tempat yang mudah dicari.

---

## 🚀 Cara Install (Langkah demi Langkah)

Ikuti urutan ini dengan teliti ya!

### Langkah 1: Menyiapkan Folder Project
1.  Buka folder instalasi Laragon di laptopmu. Biasanya ada di `C:\laragon`.
2.  Masuk ke folder `www`. Jadi posisinya di `C:\laragon\www`.
3.  Buka file ZIP *LadyStyle Shop* yang sudah kamu download.
4.  **Ekstrak (Copy)** folder yang ada di dalam ZIP ke dalam folder `www` tadi.
5.  **PENTING:** Ganti nama foldernya menjadi `ladystyle-shop` (huruf kecil semua, tanpa spasi).
    * Pastikan alamatnya jadi: `C:\laragon\www\ladystyle-shop`.

### Langkah 2: Menyalakan Laragon
1.  Buka aplikasi **Laragon**.
2.  Klik tombol **Start All**.
3.  Pastikan tulisan Apache dan MySQL sudah muncul angka port-nya (artinya sudah jalan).

### Langkah 3: Membuat Database (Lewat Browser)
Kita akan membuat "gudang data" untuk website ini menggunakan phpMyAdmin.

1.  Buka browser (Chrome, Firefox, atau Edge).
2.  Ketik alamat ini di kolom atas:
    * **http://localhost/phpmyadmin**
    * *(Catatan: Jika link di atas tidak bisa, coba link khusus ini: http://localhost/phpmyadmin6/public/)*
3.  Akan muncul halaman login.
    * **Username:** `root`
    * **Password:** (kosongkan saja / jangan diisi)
    * Klik **Go** atau **Masuk**.
4.  Setelah masuk, lihat menu di bagian atas. Klik tab **Databases** (atau "Basis Data").
5.  Di kolom *Create database*, ketik nama ini persis (jangan typo):
    👉 `ladystyle_shop_db`
6.  Klik tombol **Create**. Database kosong sudah jadi!

### Langkah 4: Memasukkan Data (Import SQL)
Sekarang kita isi database kosong tadi dengan data produk dan akun yang sudah disiapkan.

1.  Pastikan kamu masih di dalam database `ladystyle_shop_db` (cek tulisan di kiri atas layar).
2.  Klik tab **Import** di deretan menu atas.
3.  Klik tombol **Choose File** (atau "Pilih File").
4.  Cari file SQL di dalam folder project kamu tadi.
    * Buka folder: `C:\laragon\www\ladystyle-shop\sql`
    * Pilih file bernama: **`ladystyle_shop_db.sql`**
    * Klik **Open**.
5.  Geser halaman ke paling bawah, lalu klik tombol **Import** (atau **Go** / **Kirim**).
6.  Tunggu sebentar. Jika berhasil, akan muncul kotak berwarna hijau bertuliskan *"Import has been successfully finished..."*.

---

## 🌐 Cara Menjalankan Website

1.  Pastikan Laragon masih jalan (tombolnya "Stop", bukan "Start").
2.  Buka browser baru.
3.  Ketik alamat ini:
    👉 **http://localhost/ladystyle-shop**

Selamat! Website **LadyStyle Shop** seharusnya sudah tampil cantik di layarmu. 🎉

---

## 🔑 Akun Login (Untuk Masuk)

### 1. Login Admin (Pemilik Toko)
Untuk mengelola produk, melihat pesanan, dan ganti banner.
* **Link:** [http://localhost/ladystyle-shop/login.php](http://localhost/ladystyle-shop/login.php)
* **Email:** `admin@ladystyle.local`
* **Password:** `admin123`

### 2. Login Customer (Pembeli)
Untuk mencoba belanja layaknya pembeli biasa.
* **Link:** [http://localhost/ladystyle-shop/login_customer.php](http://localhost/ladystyle-shop/login_customer.php)
* Atau kamu bisa daftar akun baru sendiri di menu **Daftar**.

---

## ❓ Jika Ada Masalah (Troubleshooting)

**Q: Database tidak bisa connect / Error "Connection Failed"**
* Cek Langkah 3. Apakah nama database yang kamu buat tulisannya benar-benar `ladystyle_shop_db`? Huruf kecil semua dan pakai garis bawah.

**Q: Gambar produk tidak muncul**
* Cek Langkah 1. Pastikan nama folder di `C:\laragon\www` adalah `ladystyle-shop`. Kalau namanya beda, sistem bingung cari gambarnya.

**Q: Tidak bisa masuk phpMyAdmin**
* Pastikan Laragon sudah di-klik "Start All". Jika link `http://localhost/phpmyadmin` tidak bisa, coba klik tombol **Database** di aplikasi Laragon untuk opsi alternatif (HeidiSQL).

---
*Project Tugas Akhir Pemrograman Web - LadyStyle Shop*