INLISlite v3 Performance Tuning.
==================================
By. rico.ulul@gmail.com

Windows

APACHE & PHP
	#NOTE
	file PHP ini biasanya berada dalam folder instalasi php ex. C:/Xampp/PHP/php.ini

		1. Upload.
		Secara Default upload_max_filesize di Php adalah 2MB. Dan post_max_size 8MB. Jika ada ingin merubah settingan itu, bisa di rubah di file php.ini.
		post_max_size harus lebih besar/sama dengan  upload_max_filesize.

		2. memory_limit
		Secara Default PHP memiliki limit memory 128 MB. untuk mengubahnya di file php.ini. Parameter memory_limit

		3. max_execution_time

		4. date.timezone
		Untuk mengubah timezone sesuai dengan lokasi anda bisa di ubah pada parameter date.timezone. contoh date.timezone = Asia/Jakarta
		Untuk list timezone bisa dilihat di http://php.net/manual/en/timezones.php
Mysql
	#untuk memaksimalkan kinerja mysql kita bisa menyeting Mysql agar bekerja lebih maksimal.
	#Anda bisa melakukan optimize bila anda memiliki RAM > 4GB (rekomendasi)
	#setting yang di rubah ada di folder my.cnf
	#lokasi defaultnya ada folder XAMPP, Xampp/mysql/bin/my.ini
	#tambahkan baris ini ke Xampp/mysql/bin/my.ini

	innodb_buffer_pool_size = 2G
	innodb-buffer-pool-instances=2


	#innodb_buffer_pool_size = 2G bisa anda rubah sesuai dengan ram yg tersedia (di rekomendasikan bila mempunyai ram lebih dari 4GB).
	#innodb_buffer_pool_size = 2G (memberikan dedicated ram 2GB kepada Mysql agar proses Query menjadi lebih cepat).
	#anda bisa mengganti Dedicatec ram yg di berikan kepada Mysql dnegan merubah angka 2G.
	#misal, untuk memberikan ram 3GB kepada Mysql, anda tinggal Merubah innodb_buffer_pool_size = 3G


Linux

	Ubuntu & Linux Mint

		APACHE & PHP
			#NOTE
			file PHP ini biasanya berada dalam folder instalasi php ex. /etc/php5/apache2/php.ini

			1. Upload.
			Secara Default upload_max_filesize di Php adalah 2MB. Dan post_max_size 8MB. Jika ada ingin merubah settingan itu, bisa di rubah di file php.ini.
			post_max_size harus lebih besar/sama dengan  upload_max_filesize.

			2. memory_limit
			Secara Default PHP memiliki limit memory 128 MB. untuk mengubahnya di file php.ini. Parameter memory_limit

			3. max_execution_time

			4. date.timezone
			Untuk mengubah timezone sesuai dengan lokasi anda bisa di ubah pada parameter date.timezone. contoh date.timezone = Asia/Jakarta
			Untuk list timezone bisa dilihat di http://php.net/manual/en/timezones.php
		Mysql
			#untuk memaksimalkan kinerja mysql kita bisa menyeting Mysql agar bekerja lebih maksimal.
			#Anda bisa melakukan optimize bila anda memiliki RAM > 4GB (rekomendasi)
			#setting yang di rubah ada di folder my.cnf
			#lokasi defaultnya ada folder instalasi mysql, /etc/mysql/my.cnf
			#tambahkan baris ini ke /etc/mysql/my.cnf
			
			innodb_buffer_pool_size = 2G
			innodb-buffer-pool-instances=2

			#innodb_buffer_pool_size = 2G bisa anda rubah sesuai dengan ram yg tersedia (di rekomendasikan bila mempunyai ram lebih dari 4GB).
			#innodb_buffer_pool_size = 2G (memberikan dedicated ram 2GB kepada Mysql agar proses Query menjadi lebih cepat).
			#anda bisa mengganti Dedicatec ram yg di berikan kepada Mysql dnegan merubah angka 2G.
			#misal, untuk memberikan ram 3GB kepada Mysql, anda tinggal Merubah innodb_buffer_pool_size = 3G	

	Centos
		APACHE & PHP
			#NOTE
			file PHP ini biasanya berada dalam folder instalasi php ex. /etc/httpd/conf/httpd.conf

			1. Upload.
			Secara Default upload_max_filesize di Php adalah 2MB. Dan post_max_size 8MB. Jika ada ingin merubah settingan itu, bisa di rubah di file php.ini.
			post_max_size harus lebih besar/sama dengan  upload_max_filesize.

			2. memory_limit
			Secara Default PHP memiliki limit memory 128 MB. untuk mengubahnya di file php.ini. Parameter memory_limit

			3. max_execution_time

			4. date.timezone
			Untuk mengubah timezone sesuai dengan lokasi anda bisa di ubah pada parameter date.timezone. contoh date.timezone = Asia/Jakarta
			Untuk list timezone bisa dilihat di http://php.net/manual/en/timezones.php
		Mysql
			#untuk memaksimalkan kinerja mysql kita bisa menyeting Mysql agar bekerja lebih maksimal.
			#Anda bisa melakukan optimize bila anda memiliki RAM > 4GB (rekomendasi)
			#setting yang di rubah ada di folder my.cnf
			#lokasi defaultnya ada di, /etc/my.cnf
			#tambahkan baris ini ke /etc/my.cnf
			
			innodb_buffer_pool_size = 2G
			innodb-buffer-pool-instances=2	

			#innodb_buffer_pool_size = 2G bisa anda rubah sesuai dengan ram yg tersedia (di rekomendasikan bila mempunyai ram lebih dari 4GB).
			#innodb_buffer_pool_size = 2G (memberikan dedicated ram 2GB kepada Mysql agar proses Query menjadi lebih cepat).
			#anda bisa mengganti Dedicatec ram yg di berikan kepada Mysql dnegan merubah angka 2G.
			#misal, untuk memberikan ram 3GB kepada Mysql, anda tinggal Merubah innodb_buffer_pool_size = 3G




#Hardware Optimation
	#anda bisa menggunakan SSD sebagai pengganti Hardisk anda. SSD memiliki kecepatan Hingga 100x dari Hardisk.