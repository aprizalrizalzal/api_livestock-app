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
10. Setelah instalasi selesai, jalankan perintah berikut untuk melakukan migrasi ke database:
    ```shell
    php artisan migrate
    ```
    Tunggu hingga proses migrasi ke database selesai.
11. Setelah database berhasil dibuat, jalankan perintah berikut untuk mengisi database dengan data awal (seeder):
    ```shell
    php artisan db:seed
    ```
    Tunggu hingga proses seeding selesai.
12. Terakhir, jalankan perintah berikut untuk menjalankan server:
    ```shell
    php artisan serve
    ```
    Tunggu hingga Anda melihat pemberitahuan INFO di terminal yang menyatakan bahwa server berjalan pada [http://127.0.0.1:8000].

Dengan langkah-langkah ini, Anda sekarang seharusnya dapat menjalankan API Livestock App dengan sukses. Pastikan untuk mengikuti langkah-langkah ini dengan hati-hati untuk memastikan semua alat dan aplikasi berfungsi dengan benar.

### Default akun testing dari proses seeding
1. Role admin (Administrator)
   email: admin@example.com
   password: password
2. Role seller (Penjual)
   email: seller_satu@example.com
   password: password
   
   email: seller_dua@example.com
   password: password
3. Role buyer (Pembeli)
   email: buyer_satu@example.com
   password: password
   
   email: buyer_dua@example.com
   password: password
