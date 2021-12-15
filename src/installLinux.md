#dibuat untuk project inlislite_v3 perpustakaan nasional
#jika ada pertanyaan silahkan hubungi rico.ulul@gmail.com

requirement untuk instalasi di linux adalah 
 - Apache  >= 2.4
 - Php >= 5.6
 - Mysql >= 5.6

Copy/move Project Folder ke directory apache
copy file project inlislite ke /var/www/html/
jalankan perintah 
	CHMOD -R 755 /var/www/html/inlislite3

 Setting Database
 	#ubah setting database di folder /inlislite3/common/config/main-local.php
 	    'username' => 'password username anda',  
        'password' => 'password anda',
 	#untuk port dan database bisa di setting di
 		'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=inlislite_v3',
 	#port di ganti dengan port database anda
 	#dbname di ganti dengan namadatabase anda


 install rar extension di ubuntu/linux mint
 	#jalankan perintah di bawah ini atau bisa juga menjalankan bash script install_rar.sh
	sudo su
	apt-get install phpize5
	apt-get install -y php5-dev
	apt-get install g++
	apt-get install build-essential
	cd /tmp
	wget http://pecl.php.net/get/rar-3.0.2.tgz
	gunzip rar-3.0.2.tgz
	tar -xvf rar-3.0.2.tar
	cd rar-3.0.2
	phpize
	./configure && make && make install
	sed -i 's/;   extension=msql.so/extension=\/usr\/lib\/php5\/20121212\/rar.so/g' /etc/php5/apache2/php.ini

	#untuk menjalankan script install_rar.sh jalankan perintah berikut
	sudo sh install_rar.sh

Konfigurasi php ini
	-ubah upload maxfilesize
	-ubah post maxfilesize
	#mengubah post size dan maximum upload file menjadi 200 MB.
	sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 200M/g' /etc/php5/apache2/php.ini
	sed -i 's/post_max_size = 200M/post_max_size = 200M/g' /etc/php5/apache2/php.ini

	#anda bisa mengubah konfigurasi manual php.ini di file /etc/php5/apache2/php.ini