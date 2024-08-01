# CodeIgniter 4 Application Starter - PT KB Insurance Indonesia

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](https://codeigniter.com).

This repository holds a composer-installable app starter.
It has been built from the
[development repository](https://github.com/codeigniter4/CodeIgniter4).

More information about the plans for version 4 can be found in [CodeIgniter 4](https://forum.codeigniter.com/forumdisplay.php?fid=28) on the forums.

You can read the [user guide](https://codeigniter.com/user_guide/)
corresponding to the latest version of the framework.

## Installation & updates

Langkah - Langkah Instalasi dan Penggunaan

- Extract folder project, atau clone project, kemudian buka folder project menggunakan command prompt, lalu ketikkan "composer install"
- Copy file env, dan ubah menjadi .env, lalu sesuaikan dengan credential database Anda
- Jalankan migration dengan mengetikkan "php spark migrate" pada command prompt untuk mengisi database dengan tabel yang telah dibuat
- Jalankan seeder dengan mengetikkan "php spark db:seeder AllSeeder" pada command prompt untuk mengisi database dengan data yang telah dibuat
- Setelah selesai menjalankan migration dan seeder, jalankan aplikasi dengan mengetikkan "php spark serve"
- Buka aplikasi dengan mengetikkan "http://localhost:8080" (default) pada browser Anda
- Terdapat dua credential yang dapat Anda gunakan untuk login :
    1. Admin 1
        - Email : john@kb.com
        - Password : password123
    2. Admin 2
        - Email : jane@kb.com,
        - Password : password456
-  Ketika Anda sudah berhasil login, klik menu "Pertanggungan" untuk melihat, mengubah dan menambahkan data
-  Pada menu "Pertanggungan", data yang telah dibuat juga dapat dilakukan pencetakan dengan melakukan klik pada tombol print di sebelah data yang tersedia.