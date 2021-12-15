#!/bin/bash
​
# go to root
#dibuat oleh mazpaijo untuk project inlislite_v3 perpustakaan nasional
#jika ada pertanyaan silahkan hubungi rico.ulul@gmail.com
cd
apt-get install -y phpize5
apt-get install -y php5-dev
apt-get install -y g++
apt-get install -y build-essential
cd /tmp
wget http://pecl.php.net/get/rar-3.0.2.tgz
gunzip rar-3.0.2.tgz
tar -xvf rar-3.0.2.tar
cd rar-3.0.2
phpize
./configure && make && make install
sed -i 's/;   extension=msql.so/extension=\/usr\/lib\/php5\/20121212\/rar.so/g' /etc/php5/apache2/php.ini
service apache2 restart
