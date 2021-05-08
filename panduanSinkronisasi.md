Panduan Sinkronisasi Data dari Local ke Server atau sebaliknya
==============================================================

Catatan :
	1. Setting koneksi Database Server di aplikasi Local Inlislite, terdapat di folder :
		- \common\config\main-local.php
		- tambahkan script berikut ini :
			// Isi untuk koneksi ke server, koneksi ini digunakan untuk sinkronisasi Data
			'db2' => [
				'class' => 'yii\db\Connection',
				'dsn' => 'mysql:host=server;dbname=inlislite_32',
				'username' => 'root',
				'password' => '',
			],
			
		- untuk dsn, username, password : disesuaikan dengan yang digunakan.
		
	2. Pastikan terlebih dahulu Database Local harus disamakan dengan Database Server
		- Proses menyamakan Database Server ke Local, bisa dilakukan dengan cara Backup Restore di Aplikasi Local Inlislite
		
	3. Proses sinkronisasi ini dilakukan setiap hari
		- terdapat di menu Administrasi -> Pengaturan Update
		- di halamam tersebut, ada konten yang menunjukan tombol untuk sinkronisasi
		
	4. Data sinkronisasi dari Local ke Server yang dikirim, meliputi :
		- Data Anggota
		- Buku Tamu
		- Baca Ditempat
		- Sirkulasi
		- Pelanggaran
		
	5. Data sinkronisasi dari Server ke Local, meliputi :
		- Semua tabel
	