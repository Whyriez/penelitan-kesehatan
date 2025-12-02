# Arsip Penelitian Kesehatan

Deskripsi singkat mengenai proyek ini. Jelaskan apa kegunaan aplikasi ini dan fitur utamanya.

## Prasyarat (Prerequisites)

Sebelum memulai, pastikan komputer Anda telah terinstal:
* PHP
* Composer
* Database (MySQL/PostgreSQL)

## Cara Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di komputer lokal Anda:

1.  **Buka folder proyek**
    Buka terminal dan arahkan ke direktori proyek atau buka via VS Code.

2.  **Install Dependensi**
    Unduh semua library bawaan Laravel.
    ```bash
    composer install
    ```

3.  **Setup Library Excel (Maatwebsite)**
    Pastikan library Excel terinstal dan konfigurasi telah di-publish.
    ```bash
    composer require maatwebsite/excel
    php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
    ```

4.  **Konfigurasi Environment**
    Salin file contoh konfigurasi.
    ```bash
    cp .env.example .env
    ```

    > **Penting:** Buka file `.env` yang baru saja dibuat, lalu sesuaikan konfigurasi database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) agar sesuai dengan database lokal Anda.

5.  **Generate Application Key**
    Buat kunci enkripsi aplikasi.
    ```bash
    php artisan key:generate
    ```

6.  **Migrasi Database & Seeding**
    Jalankan tabel database dan isi data dummy (seed).
    ```bash
    php artisan migrate --seed
    ```

7.  **Link Storage**
    Hubungkan folder storage ke public agar file/gambar bisa diakses.
    ```bash
    php artisan storage:link
    ```

## Menjalankan Aplikasi

Setelah instalasi selesai, jalankan perintah berikut untuk memulai server lokal:

```bash
php artisan serve
