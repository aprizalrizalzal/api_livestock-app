# Panduan Menjalankan API Livestock App

## Persiapan Alat dan Cara Menjalankan

1. **Instalasi XMPP** - Unduh [XMPP](https://www.apachefriends.org/download.html) dari tautan ini.

2. **Instalasi Composer** - Unduh [Composer](https://getcomposer.org/download) dari tautan ini.

3. **Catatan**: Sebelum menginstal Composer, pastikan Anda telah menginstal XMPP, karena path PHP yang diperlukan oleh Composer akan tersedia setelah instalasi XMPP.

4. **Instalasi Visual Studio Code (VSCode)** - Unduh [VSCode](https://code.visualstudio.com/download) dari tautan ini.

5. Setelah berhasil menginstal semua alat di atas, lanjutkan ke langkah berikutnya.

6. **Jalankan XMPP, Apache, dan MySQL** - Pastikan Anda telah memulai XMPP, Apache, dan MySQL.

7. **Buka VSCode** dan buka folder yang berisi kode sumber Sistem Informasi Penjualan Hewan Ternak di NTB yang telah Anda unduh atau klon.

8. Setelah berhasil membuka kode sumber di VSCode, pilih menu "View" kemudian "Terminal" atau gunakan pintasan "Ctrl+`" untuk membuka terminal.

9. Di terminal yang terbuka, ketik perintah berikut untuk menginstal dependensi menggunakan Composer:
   ```shell
   composer install
   ```
   Tunggu hingga proses instalasi selesai.
10. Setelah instalasi selesai, silakan copy .env.example, kemudian rename hasil copy menjadi .env dan ubah FILESYSTEM_DISK=local menjadi FILESYSTEM_DISK=public.
11. Lanjut, jalankan perintah berikut untuk menghubungkan penyimpanan dengan sistem file publik. 
    ```shell
    php artisan storage:link
    ```
    Tunggu hingga berhasil terhubung
13. Lanjut, jalankan perintah berikut untuk melakukan migrasi ke database:
    ```shell
    php artisan migrate
    ```
    Tunggu hingga proses migrasi ke database selesai.
14. Setelah database berhasil dibuat, jalankan perintah berikut untuk mengisi database dengan data awal (seeder):
    ```shell
    php artisan db:seed
    ```
    Tunggu hingga proses seeding selesai.
15. Terakhir, jalankan perintah berikut untuk menjalankan server:
    ```shell
    php artisan serve
    ```
    Tunggu hingga Anda melihat pemberitahuan INFO di terminal yang menyatakan bahwa server berjalan pada [http://127.0.0.1:8000].

Dengan langkah-langkah ini, sekarang seharusnya dapat menjalankan API Livestock App dengan sukses. Pastikan untuk mengikuti langkah-langkah ini dengan hati-hati untuk memastikan semua alat dan aplikasi berfungsi dengan benar.

### Default akun testing dari proses seeding
1. Role admin (Administrator)
   - email: admin@example.com
   - password: password
2. Role seller (Penjual)
   - email: seller_satu@example.com 
   - email: seller_dua@example.com
   - password: password
3. Role buyer (Pembeli)
   - email: buyer_satu@example.com
   - email: buyer_dua@example.com
   - password: password
  
## Tambahan: Mengatasi Masalah Instalasi Package Composer

Jika Anda mengalami kegagalan saat menginstal package Composer, Anda dapat mencoba langkah-langkah berikut:

1. **Konfigurasi XMPP untuk Apache:**

   - Buka konfigurasi XMPP pada Apache dan cari opsi Config (Konfigurasi) PHP(php.ini).
   - Cari baris yang berisi `;extension=zip` dan hilangkan tanda `;` (titik koma) di awal baris.
   - Simpan perubahan dengan menekan `Ctrl+S`.
   - Tutup file konfigurasi dan coba lagi untuk menjalankan perintah instalasi Composer.

2. **Jalankan Perintah Instalasi Composer:**

   Setelah melakukan konfigurasi di atas, coba kembali menjalankan perintah instalasi Composer:

   ```shell
   composer install
   ```
