## Cara instalasi
1. Clone repository ini
2. Buka terminal dan arahkan ke folder repository ini
3. Jalankan perintah `composer install`
4. Jalankan perintah `php artisan key:generate`
5. Buat database baru di MySQL
6. Copy file `.env.example` dan rename menjadi `.env`
7. Sesuaikan konfigurasi database di file `.env`
8. Jalankan perintah `php artisan jwt:secret`
9. Jalankan perintah `php artisan migrate`
10. Jalankan perintah `php artisan db:seed`
11. Jalankan perintah `php artisan storage:link` 
12. Jalankan perintah `php artisan serve`
13. Buka postman dan pointing ke `localhost:8000/api/`
14. Selesai

## user access
### user investor
email: investor@example.com
passwords: password

### user umkm
email: umkm@example.com
passwords: password

### user reviewer
email: reviewer@example.com
passwords: password

Made with ❤ by Kacong
