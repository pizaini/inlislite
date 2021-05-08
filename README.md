#Modified source code
Source code berikut merupakan modifikasi yang dibuat untuk menyesuaikan pengaturan yang kami perlukan tanpa menghilangkan lisensi Inlislite V3 dari Perpusnas Indonesia. Kami bangga menggunakan produk ini dan kami selalu mendukung Perpusnas dalam memberikan layanan teknologin informasi bagi masyarakat Indonesia

##Modifikasi yang kami lakukan
* Pembuatan docker file agar aplikasi ini dapat dijalankan melalui Docker container
* Modifikasi logo utama
* Modifikasi favicon
* Modifikasi halaman utama (landing page)

##Docker run
```shell
docker run -d -p 8083:80 -v "C:\server\www\inlislite":/app/web -e DB_HOST="host.docker.internal" -e DB_PORT="3306" -e DB_NAME="inlislite" -e DB_USERNAME="root" -e DB_PASSWORD="xxxxxx"  yiisoftware/yii2-php:5.6-apache
```

#TENTANG INLISLITE VERSI 3 (Original readme)
==================================

INLISLite versi 3 merupakan pengembangan lanjutan dari perangkat lunak (software) aplikasi otomasi perpustakaan INLISLite versi 2.1.2 yang dibangun dan dikembangkan oleh Perpustakaan Nasional RI (Perpustakaan Nasional RI) sejak tahun 2011.

INLISLite versi 3 dikembangkan sebagai perangkat lunak satu pintu bagi pengelola perpustakaan untuk menerapkan otomasi perpustakaan sekaligus mengembangkan perpustakaan digital / mengelola dan melayankan koleksi digital.

INLIS Lite dibangun dan dikembangkan secara resmi oleh Perpustakaan Nasional RI dalam rangka menghimpun koleksi nasional dalam jejaring Perpustakaan Digital Nasional Indonesia, disamping membantu upaya pengembangan pengelolaan dan pelayanan perpustakaan berbasis teknologi informasi dan komunikasi di seluruh Indonesia yang didasarkan pada :

Undang-Undang Republik Indonesia Nomor 43 Tahun 2007 tentang Perpustakaan;
Peraturan Pemerintah Nomor 24 Tahun 2014 Tentang Pelaksanaan Undang-Undang Republik Indonesia Nomor 43 Tahun 2007 tentang Perpustakaan;
Undang-Undang Nomor 4 Tahun 1990 Tentang Serah Simpan Karya Cetak dan Rekam.

## Karakteristik INLISLite Versi 3.0
Mengikuti standar metadata MARC (MAchine Readable Cataloguing) dalam pembentukan katalog digitalnya.
Berbasis web (webbased application software), di mana dalam pengoperasiannya menggunakan aplikasi browser internet yang umum digunakan untuk menjelajahi informasi di internet.
Instalasi perangkat lunak INLIS Lite cukup dilakukan pada satu komputer yang difungsikan sebagai pangkalan data (server). Pengoperasian aplikasi cukup dilakukan melalui komputer kerja (workstation) dengan cara mengkoneksikannya melalui perangkat jaringan komputer, baik secara lokal (local area network), antar wilayah (wide area network), maupun Internet.
Dapat dioperasikan secara bersamaan dalam satu waktu secara simultan (multi user ready)
Bebas pakai / gratis (freeware dan opensource).

## Pilihan Platform InlisLite Versi 3
INLISLite versi 3 dibangun dalam dua pilihan platform bahasa pemrograman yaitu:
DotNet Framework, yang dapat diinstalasi pada komputer bersistem operasi Windows.
PHP (opensource), yang dapat diinstalasi pada komputer bersistem operasi Windows dan Linux.

