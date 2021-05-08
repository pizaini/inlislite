<?php

use yii\bootstrap\Nav;
use inliscore\adminlte\widgets\SideMenu;
use mdm\admin\components\MenuHelper;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $baseurl ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>
        <?php
        if (Yii::$app->user->isGuest) {
            Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        } else {
            echo Yii::$app->user->identity->username;
        }
        ?></p>
        
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div> -->

        <?php
        $menuCallback = function($menu) {
            // echo'<pre>';print_r($menu);die;
            $item = [
                'label' => Yii::t('app', $menu['name']),
                // 'label' => $menu['name'],
                'url' => MenuHelper::parseRoute($menu['route']),
            ];

            if($menu['route'] == '/admin/assignment/index'){
                $item = '';
            }else{
                if($menu['name'] == NULL){
                     $item = [
                        'label' => 'Label Kosong',
                        'url' => MenuHelper::parseRoute($menu['route']),
                    ];
                }
                
                if (!empty($menu['data'])) {
                    $item['icon'] = 'fa ' . $menu['data'];
                } else {
                    $item['icon'] = 'fa fa-angle-double-right';
                }
                if ($menu['children'] != []) {
                    $item['items'] = $menu['children'];
                }
                // return $item;
            }

            return $item;
        };
        
       
         $items = MenuHelper::getAssignedMenu(Yii::$app->user->id, null, $menuCallback);
          echo SideMenu::widget([
          'items' => $items,
          ]); 


        /*echo SideMenu::widget(
                [
                    'items' => [
                        ['label' => 'Dashboard', 'url' => ['/site/index'], 'icon' => 'fa fa-dashboard'],
                        ['label' => 'Akuisisi', 'icon' => 'fa fa-book',
                            'items' => [
                                ['label' => 'Daftar Nama Sumber Perolehan', 'url' => ['/setting/akuisisi/rekanan'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Entri Koleksi', 'url' => ['/pengkatalogan/katalog/create?for=coll&rda=0'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Entri Koleksi (RDA)', 'url' => ['/pengkatalogan/katalog/create?for=coll&rda=1'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Daftar Koleksi', 'url' => ['/akuisisi/koleksi'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Jilid Koleksi', 'url' => ['/akuisisi/koleksi-jilid'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Kardeks Terbitan Berkala', 'url' => ['/akuisisi/kardeks-terbitan-berkala'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Daftar Usulan Koleksi', 'url' => ['/akuisisi/koleksi-usulan'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Import Data dari Excel', 'url' => ['/akuisisi/koleksi-import'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Keranjang Koleksi', 'url' => ['/akuisisi/koleksi/keranjang'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Karantina Koleksi ', 'url' => ['/akuisisi/koleksi/karantina'], 'icon' => 'fa fa-angle-double-right'],
                            ]],
                        ['label' => 'Katalog', 'icon' => 'fa fa-book',
                            'items' => [
                                ['label' => 'Entri Katalog', 'url' => ['/pengkatalogan/katalog/create?for=cat&rda=0'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Entri Katalog (RDA)', 'url' => ['/pengkatalogan/katalog/create?for=cat&rda=1'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Salin Katalog', 'url' => ['/pengkatalogan/katalog-salin/create'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Daftar Katalog', 'url' => ['/pengkatalogan/katalog'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Export Data Tag Katalog', 'url' => ['/pengkatalogan/katalog-export-data-tag'], 'icon' => 'fa fa-angle-double-right'],

                                ['label' => 'Daftar Konten Digital', 'url' => ['/pengkatalogan/katalog-konten-digital'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Keranjang Katalog', 'url' => ['/pengkatalogan/katalog/keranjang'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Karantina Katalog', 'url' => ['/pengkatalogan/katalog/karantina'], 'icon' => 'fa fa-angle-double-right'],
                            ]],
                        ['label' => 'Keanggotaan', 'icon' => 'fa  fa-user',
                            'items' => [
                                ['label' => 'Entri Anggota', 'url' => ['/member/member/create'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Daftar Anggota', 'url' => ['/member/member/index'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Import Data dari Excel', 'url' => ['/member/member/import-anggota'], 'icon' => 'fa fa-angle-double-right'],
                               // ['label' => 'Daftar Pengunjung', 'url' => ['/member/daftar-pengunjung'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Daftar Sumbangan', 'url' => ['/member/sumbangan'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Daftar Perpanjangan', 'url' => ['/member/perpanjang'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Keranjang Anggota', 'url' => ['/member/member/keranjang-anggota'], 'icon' => 'fa fa-angle-double-right'],
                            ]],
                        ['label' => 'Sirkulasi', 'icon' => 'fa  fa-refresh',
                            'items' => [
                                ['label' => 'Entri Peminjaman', 'url' => ['/sirkulasi/peminjaman/create'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Entri Peminjaman Susulan', 'url' => ['/sirkulasi/peminjaman/create-susulan'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Daftar Koleksi Dipesan', 'url' => ['/sirkulasi/koleksi-dipesan/index'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Daftar Peminjaman', 'url' => ['/sirkulasi/peminjaman/index'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Entri Pengembalian', 'url' => ['/sirkulasi/pengembalian/create'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Entri Pengembalian Susulan', 'url' => ['/sirkulasi/pengembalian/create-susulan'], 'icon' => 'fa fa-angle-double-right'],
                                //['label'=>'Entri Pengembalian RFID', 'url' => ['/member/member'], 'icon'=>'fa fa-angle-double-right'],
                                ['label' => 'Daftar Pengembalian', 'url' => ['/sirkulasi/pengembalian/index'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Stock Opname', 'url' => ['/sirkulasi/stockopname/index'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Data Pelanggaran', 'url' => ['/sirkulasi/pelanggaran/index'], 'icon' => 'fa fa-angle-double-right'],
                                //['label' => 'Daftar Baca di Tempat', 'url' => ['/sirkulasi/read-onlocation/index'], 'icon' => 'fa fa-angle-double-right'],
                            ]],
                        ['label' => 'Locker', 'icon' => 'fa  fa-key',
                            'items' => [
                                ['label' => Yii::t('app', 'Peminjaman'), 'url' => ['/loker/transaksi/peminjaman'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => Yii::t('app', 'Pengembalian'), 'url' => ['/loker/transaksi/pengembalian'], 'icon' => 'fa fa-angle-double-right'],
                                // ['label'=>Yii::t('app','List Transaksi'), 'url' => ['/loker/transaksi/'], 'icon'=>'fa fa-angle-double-right'],
                                ['label' => Yii::t('app', 'Daftar Transaksi'), 'icon' => 'fa fa-angle-double-right', 'items' => [
                                        ['label' => Yii::t('app', 'Daftar Peminjaman'), 'url' => ['/loker/transaksi/daftar-peminjaman'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Daftar Pengembalian'), 'url' => ['/loker/transaksi/daftar-pengembalian'], 'icon' => 'fa fa-angle-double-right'],
                                    ]],
                            ]],
                        ['label' => 'Survey', 'icon' => 'fa  fa-users',
                            'items' => [
                                ['label' => Yii::t('app', 'Data Survey'), 'url' => ['/survey/data'], 'icon' => 'fa fa-angle-double-right'],
                            ]],
                        ['label' => Yii::t('app', 'Buku Tamu'), 'url' => ['/setting/checkpoint/memberguesses'], 'icon' => 'fa fa-check-square',
                            'items' =>
                            [
                                ['label' => Yii::t('app', 'Anggota'), 'url' => ['/setting/checkpoint/memberguesses/anggota'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => Yii::t('app', 'Non Anggota'), 'url' => ['/setting/checkpoint/memberguesses/nonanggota'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => Yii::t('app', 'Rombongan'), 'url' => ['/setting/checkpoint/memberguesses/rombongan'], 'icon' => 'fa fa-angle-double-right'],
                            ]],

                        //opac
                       ['label' => Yii::t('app', 'Opac'), 'url' => ['/opac/'], 'icon' => 'fa fa-gears',
                            'items' => [
                                ['label' => 'Riwayat Pencarian Sederhana', 'url' => ['/opac/history/pencarian-sederhana'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Riwayat Pencarian Browse', 'url' => ['/opac/history/browse'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Riwayat Pencarian Lanjut', 'url' => ['/opac/history/pencarian-lanjut'], 'icon' => 'fa fa-angle-double-right'],
                                
                               
                            ]],
                    
                        //LKD
                       ['label' => Yii::t('app', 'Layanan Koleksi Digital'), 'url' => ['/lkd/'], 'icon' => 'fa fa-gears',
                            'items' => [
                                ['label' => 'Riwayat Pencarian Sederhana', 'url' => ['/lkd/history/pencarian-sederhana'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Riwayat Pencarian Browse', 'url' => ['/lkd/history/browse'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => 'Riwayat Pencarian Lanjut', 'url' => ['/lkd/history/pencarian-lanjut'], 'icon' => 'fa fa-angle-double-right'],


                            ]],
                    



                        ['label' => Yii::t('app', 'Baca Ditempat'), 'url' => ['/bacaditempat/koleksi-dibaca'], 'icon' => 'fa fa-rss-square',
                            'items' =>
                            [
                                ['label' => Yii::t('app', 'Anggota'), 'url' => ['/bacaditempat/koleksi-dibaca/anggota'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => Yii::t('app', 'Non Anggota'), 'url' => ['/bacaditempat/koleksi-dibaca/nonanggota'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => Yii::t('app', 'Pengembalian Koleksi Baca Ditempat'), 'url' => ['/bacaditempat/pengembalian-koleksi-baca-ditempat'], 'icon' => 'fa fa-angle-double-right'],
                            ]],
                        // <report>
                    ['label' => Yii::t('app','Laporan'), 'url' => ['/laporan'], 'icon'=>'fa fa-files-o',
                        'items'=>[
                            ['label'=>Yii::t('app','Katalog'), 'icon'=>'fa fa-angle-double-right','items'=>[
                                ['label'=>Yii::t('app','Laporan Katalog Per Kriteria'), 'url' => ['/laporan/katalog/katalog-perkriteria'],'icon'=>'fa fa-angle-double-right'],
                                ['label'=>Yii::t('app','Laporan Kinerja User'), 'url' => ['/laporan/katalog/kinerja-user'],'icon'=>'fa fa-angle-double-right'],
                            ]],
							// <=====>
                            ['label'=>Yii::t('app','Koleksi'), 'icon'=>'fa fa-angle-double-right','items'=>[
                                ['label'=>Yii::t('app','Laporan Koleksi Per Periodik'), 'url' => ['/laporan/koleksi/periodik'],'icon'=>'fa fa-angle-double-right'],
                                ['label'=>Yii::t('app','Laporan Buku Induk'),'url' => ['/laporan/koleksi/buku-induk'],'icon'=>'fa fa-angle-double-right'],
								['label'=>Yii::t('app','Laporan Accesion List'),'url' => ['/laporan/koleksi/accession-list'],'icon'=>'fa fa-angle-double-right'],
                                ['label'=>Yii::t('app','Laporan Ucapan Terima Kasih'),'url' => ['/laporan/koleksi/ucapan-terimakasih'],'icon'=>'fa fa-angle-double-right'],
								['label'=>Yii::t('app','Laporan Usulan Koleksi'),'url' => ['/laporan/koleksi/usulan-koleksi'],'icon'=>'fa fa-angle-double-right'],
								['label'=>Yii::t('app','Laporan Kinerja User'),'url' => ['/laporan/koleksi/kinerja-user'],'icon'=>'fa fa-angle-double-right'],
							]],
							// </=====>
							['label'=>Yii::t('app','Anggota'), 'icon'=>'fa fa-angle-double-right','items'=>[
                                ['label'=>Yii::t('app','Laporan Per Pendaftaran'), 'url' => ['/laporan/anggota/perpendaftaran'],'icon'=>'fa fa-angle-double-right'],
                                ['label'=>Yii::t('app','Laporan Per Perpanjang'), 'url' => ['/laporan/anggota/perpanjangan'],'icon'=>'fa fa-angle-double-right'],
								['label'=>Yii::t('app','Laporan Sumbangan Anggota'), 'url' => ['/laporan/anggota/sumbangan'],'icon'=>'fa fa-angle-double-right'],
								['label'=>Yii::t('app','Laporan Bebas Pustaka'), 'url' => ['/laporan/anggota/bebas-pustaka'],'icon'=>'fa fa-angle-double-right'],
								['label'=>Yii::t('app','Laporan Kinerja User'), 'url' => ['/laporan/anggota/kinerja-user'],'icon'=>'fa fa-angle-double-right'],
                            ]],
							['label'=>Yii::t('app','Sirkulasi'), 'icon'=>'fa fa-angle-double-right','items'=>[
								['label'=>Yii::t('app','Laporan Peminjaman'), 'url' => ['/laporan/sirkulasi/laporan-peminjaman'],'icon'=>'fa fa-angle-double-right'],
                                ['label'=>Yii::t('app','Laporan Perpanjangan Peminjaman'), 'url' => ['/laporan/sirkulasi/perpanjangan-peminjaman'],'icon'=>'fa fa-angle-double-right'],
								['label'=>Yii::t('app','Laporan Sangsi Pelanggaran Peminjaman'), 'url' => ['/laporan/sirkulasi/sangsi-pelanggaran-peminjaman'],'icon'=>'fa fa-angle-double-right'],
								['label'=>Yii::t('app','Laporan Koleksi Sering Dipinjam'), 'url' => ['/laporan/sirkulasi/koleksi-sering-dipinjam'],'icon'=>'fa fa-angle-double-right'],
								['label'=>Yii::t('app','Laporan Anggota Sering Meminjam'), 'url' => ['/laporan/sirkulasi/laporan-anggota-sering-meminjam'],'icon'=>'fa fa-angle-double-right'],
								['label'=>Yii::t('app','Laporan Kinerja User Peminjaman'), 'url' => ['/laporan/sirkulasi/kinerja-user-peminjaman'],'icon'=>'fa fa-angle-double-right'],
								['label'=>Yii::t('app','Laporan Kinerja User Pengembalian'), 'url' => ['/laporan/sirkulasi/kinerja-user-pengembalian'],'icon'=>'fa fa-angle-double-right'],
								['label'=>Yii::t('app','Laporan Pengembalian Terlambat'), 'url' => ['/laporan/sirkulasi/pengembalian-terlambat'],'icon'=>'fa fa-angle-double-right'],
                  
                            ]],
							['label'=>Yii::t('app','Buku Tamu'), 'icon'=>'fa fa-angle-double-right','items'=>[
                                ['label'=>Yii::t('app','Laporan Kunjungan Perperiodik'), 'url' => ['/laporan/buku-tamu/kunjungan-periodik'],'icon'=>'fa fa-angle-double-right'],
                                ['label'=>Yii::t('app','Laporan Kunjungan Khusus Anggota'), 'url' => ['/laporan/buku-tamu/kunjungan-khusus-anggota'],'icon'=>'fa fa-angle-double-right'],
                            ]],
							['label'=>Yii::t('app','Baca di Tempat'), 'icon'=>'fa fa-angle-double-right','items'=>[
                                ['label'=>Yii::t('app','Laporan Berdasarkan Koleksi'), 'url' => ['/laporan/baca-ditempat/berdasarkan-koleksi'],'icon'=>'fa fa-angle-double-right'],
                                ['label'=>Yii::t('app','Laporan Koleksi Sering Baca di Tempat'), 'url' => ['/laporan/baca-ditempat/koleksi-sering-baca-ditempat'],'icon'=>'fa fa-angle-double-right'],
                            ]],
							['label'=>Yii::t('app','Loker'), 'icon'=>'fa fa-angle-double-right','items'=>[
                                ['label'=>Yii::t('app','Laporan Periodik'), 'url' => ['/laporan/loker/laporan-periodik'],'icon'=>'fa fa-angle-double-right'],
                                ['label'=>Yii::t('app','Laporan Sangsi Pelanggaran'), 'url' => ['/laporan/loker/laporan-sangsi-pelanggaran-loker'],'icon'=>'fa fa-angle-double-right'],										
                            ]],
							['label'=>Yii::t('app','Opac'), 'icon'=>'fa fa-angle-double-right','items'=>[
                                ['label'=>Yii::t('app','Laporan Periodik'), 'url' => ['/laporan/opac/laporan-periodik'],'icon'=>'fa fa-angle-double-right'],
                            ]],
							['label'=>Yii::t('app','SMS'), 'icon'=>'fa fa-angle-double-right','items'=>[
                                ['label'=>Yii::t('app','Laporan Periodik'), 'url' => ['/laporan/sms/laporan-periodik'],'icon'=>'fa fa-angle-double-right'],
                            ]],
                           
                    ]],
					// </report>
                        ['label' => Yii::t('app', 'Administrasi'), 'url' => ['/setting/'], 'icon' => 'fa fa-gears',
                            'items' => [

                                ['label' => Yii::t('app', 'Setting') . ' ' . Yii::t('app', 'Akuisisi'), 'url' => ['/setting/akuisisi'], 'icon' => 'fa fa-angle-double-right',
                                    'items' =>
                                    [

                                        ['label' => 'Ruas Data Bibliografis', 'url' => ['/setting/akuisisi/lembar-kerja-akuisisi'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Kategori Koleksi', 'url' => ['/setting/akuisisi/kategori-koleksi'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Jenis Sumber', 'url' => ['/setting/akuisisi/sumber-koleksi'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Bentuk Fisik', 'url' => ['/setting/akuisisi/media-koleksi'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Mata Uang', 'url' => ['/setting/akuisisi/mata-uang'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Master DJKN', 'url' => ['/setting/akuisisi/master-djkn'], 'icon' => 'fa fa-angle-double-right'],
                                        // ['label' => 'Lembar Kerja Akuisisi', 'url' => ['/setting/akuisisi/lembar-kerja-akuisisi'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Penomoran Koleksi', 'url' => ['/setting/akuisisi/nomor-induk'], 'icon' => 'fa fa-angle-double-right'],
                                    ]],
                                ['label' => Yii::t('app', 'Setting') . ' ' . Yii::t('app', 'Katalog'), 'url' => ['/setting/katalog'], 'icon' => 'fa fa-angle-double-right',
                                    'items' =>
                                    [
                                        ['label' => 'Tag', 'url' => ['/setting/katalog/tag'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Referensi', 'url' => ['/setting/katalog/referensi'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Klas Besar', 'url' => ['/setting/katalog/kelas-besar'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Kata Sandang', 'url' => ['/setting/katalog/kata-sandang'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Jenis Bahan Pustaka', 'url' => ['/setting/katalog/lembar-kerja'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Format Kartu', 'url' => ['/setting/katalog/format-kartu'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Pengaturan Detail Katalog', 'url' => ['/setting/katalog/parameter-katalog-detail'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Penyedia Katalog', 'url' => ['/setting/katalog/penyedia-katalog'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Form Entri', 'url' => ['/setting/katalog/entri-form'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Pengaturan Lainnya', 'url' => ['/setting/katalog/parameter-katalog'], 'icon' => 'fa fa-angle-double-right'],
                                    ]],
                                ['label' => Yii::t('app', 'Setting') . ' ' . Yii::t('app', 'Member'), 'url' => ['/setting/member'], 'icon' => 'fa fa-angle-double-right', 'items' => [
                                        ['label' => Yii::t('app', 'Members Card'), 'url' => ['/setting/member/kartu-anggota'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Redaksi Keanggotaan'), 'url' => ['/setting/member/redaksi-keanggotaan'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Jenis Anggota'), 'url' => ['/setting/member/jenis-anggota'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Jenis Identitas'), 'url' => ['/setting/member/master-jenis-identitas'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Pekerjaan'), 'url' => ['/setting/member/master-pekerjaan'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Pendidikan'), 'url' => ['/setting/member/pendidikan'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Kelompok Umur'), 'url' => ['/setting/member/kelompok-umur'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Kelas'), 'url' => ['/setting/member/kelas'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Fakultas'), 'url' => ['/setting/member/fakultas'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Jurusan'), 'url' => ['/setting/member/jurusan'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Program Studi'), 'url' => ['/setting/member/program-studi'], 'icon' => 'fa fa-angle-double-right'],
                                        
                                        ['label' => Yii::t('app', 'Jenis Kelamin'), 'url' => ['/setting/member/jenis-kelamin'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Agama'), 'url' => ['/setting/member/agama'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Data Kependudukan'), 'url' => ['/setting/member/kependudukan'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Entri Keanggotaan'), 'url' => ['/setting/member/entri-anggota'], 'icon' => 'fa fa-angle-double-right'],
                                    ]],
                                ['label' => Yii::t('app', 'Setting') . ' ' . Yii::t('app', 'Sirkulasi'), 'url' => ['/setting/sirkulasi'], 'icon' => 'fa fa-angle-double-right',
                                    'items' => [
                                        ['label' => Yii::t('app', 'Hari Libur'), 'url' => ['/setting/sirkulasi/holiday'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Jenis Bahan'), 'url' => ['/setting/sirkulasi/jenis-bahan'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Kelompok Pelanggaran'), 'url' => ['/setting/sirkulasi/kelompok-pelanggaran'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Jenis Denda'), 'url' => ['/setting/sirkulasi/jenis-denda'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Jenis Pelanggaran'), 'url' => ['/setting/sirkulasi/jenis-pelanggaran'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Jenis Akses'), 'url' => ['/setting/sirkulasi/peraturan-peminjaman'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Peraturan Peminjaman (Tgl)'), 'url' => ['/setting/sirkulasi/peraturan-peminjaman-tanggal'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Peraturan Peminjaman (Hari)'), 'url' => ['/setting/sirkulasi/peraturan-peminjaman-hari'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Setting Transaksi'), 'url' => ['/setting/sirkulasi/setting-transaksi'], 'icon' => 'fa fa-angle-double-right'],
                                    ]],
                                // ['label' => Yii::t('app', 'Setting') . ' ' . Yii::t('app', 'Buku Tamu'), 'url' => ['/setting/checkpoint'], 'icon' => 'fa fa-angle-double-right',
                                //     'items' =>
                                //     [
                                //         ['label' => 'Pengaturan Lokasi', 'url' => ['/setting/checkpoint/locations'], 'icon' => 'fa fa-angle-double-right'],
                                //     ]],
                                ['label' => Yii::t('app', 'Setting') . ' ' . Yii::t('app', 'Locker'), 'url' => ['/loker/settings'], 'icon' => 'fa fa-angle-double-right', 'items' => [
                                        ['label' => Yii::t('app', 'Locker'), 'url' => ['/setting/loker/master-loker/index'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Jaminan Peminjaman'), 'url' => ['/setting/loker/jaminan'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Denda Pelanggaran'), 'url' => ['/setting/loker/masterpelanggaran'], 'icon' => 'fa fa-angle-double-right'],
                                    ]],
                                ['label' => Yii::t('app', 'Setting') . ' ' . Yii::t('app', 'Opac'), 'url' => ['/setting/opac'], 'icon' => 'fa fa-angle-double-right',
                                    'items' => [
                                        ['label' => Yii::t('app', 'Koleksi Unggulan'), 'url' => ['/setting/opac/koleksi-unggulan'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Koleksi Terbaru'), 'url' => ['/setting/opac/koleksi-terbaru'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Koleksi Sering Di Pinjam'), 'url' => ['/setting/opac/koleksi-sering-dipinjam'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Pemesanan Koleksi'), 'url' => ['/setting/opac/booking-setting'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Pengaturan Faset'), 'url' => ['/setting/opac/faced-setting'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Pengusulan Koleksi'), 'url' => ['/setting/opac/usulan-koleksi'], 'icon' => 'fa fa-angle-double-right'],
                                        //['label' => Yii::t('app', 'Riwayat'), 'url' => ['/setting/opac/history-opac'], 'icon' => 'fa fa-angle-double-right'],
                                    ]],
                                ['label' => Yii::t('app', 'Setting') . ' ' . Yii::t('app', 'LKD'), 'url' => ['/setting/digitalcollection'], 'icon' => 'fa fa-angle-double-right',
                                    'items' => [
                                        ['label' => Yii::t('app', 'Koleksi Unggulan'), 'url' => ['/setting/digitalcollection/koleksi-unggulan'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Koleksi Terbaru'), 'url' => ['/setting/digitalcollection/koleksi-terbaru'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Koleksi Sering Di Pinjam'), 'url' => ['/setting/digitalcollection/koleksi-sering-dipinjam'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Pengaturan Faset'), 'url' => ['/setting/digitalcollection/faced-setting'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Pengusulan Koleksi'), 'url' => ['/setting/digitalcollection/usulan-koleksi'], 'icon' => 'fa fa-angle-double-right'],
                                        //['label' => Yii::t('app', 'Riwayat'), 'url' => ['/setting/digitalcollection/history-digital-collection'], 'icon' => 'fa fa-angle-double-right'],
                                    ]],
                                ['label' => Yii::t('app', 'Setting') . ' ' . Yii::t('app', 'Umum'), 'url' => ['/setting/umum'], 'icon' => 'fa fa-angle-double-right',
                                    'items' =>
                                    [
                                        //['label' => 'Perpustakaan Daerah', 'url' => ['/setting/umum/perpustakaan-daerah'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Jenis Perpustakaan', 'url' => ['/setting/umum/jenis-perpustakaan'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Unit Kerja', 'url' => ['/setting/umum/unit-kerja'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Mail Server', 'url' => ['/setting/umum/mail-server'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Menu', 'url' => ['/setting/umum/menu'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Hak Akses', 'url' => ['/setting/umum/role'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Setting User', 'url' => ['/setting/umum/user'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Nama Perpustakaan', 'url' => ['/setting/umum/data-perpustakaan'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Lokasi Ruang'), 'url' => ['/setting/akuisisi/lokasi'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Lokasi Perpustakaan'), 'url' => ['/setting/sirkulasi/lokasi-peminjaman'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Setting Update', 'url' => ['/setting/umum/setting-update'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => 'Layanan Sabtu dan Minggu', 'url' => ['/setting/umum/layanan-sabtu-dan-minggu'], 'icon' => 'fa fa-angle-double-right'],
										['label' => Yii::t('app', 'Jam Operasional Layanan'), 'url' => ['/setting/umum/jam-buka'], 'icon' => 'fa fa-angle-double-right'],
                                    ]],
                                ['label' => Yii::t('app', 'Setting') . ' ' . Yii::t('app', 'Audio'), 'url' => ['/loker/settings'], 'icon' => 'fa fa-angle-double-right',
                                    'items' => [
                                        ['label' => Yii::t('app', 'Buku Tamu'), 'url' => ['/setting/audio/audio-bukutamu'], 'icon' => 'fa fa-angle-double-right'],
                                    ]],
                                ['label' => Yii::t('app', 'Setting') . ' ' . Yii::t('app', 'Sms Gateway'), 'url' => ['/loker/settings'], 'icon' => 'fa fa-angle-double-right',
                                    'items' => [
                                        ['label' => Yii::t('app', 'Peminjaman Akan Jatuh Tempo'), 'url' => ['/setting/sms/sms-belum-jatuh-tempo'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Peminjaman Setelah Jatuh Tempo'), 'url' => ['/setting/sms/sms-jatuh-tempo'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'Sms Manual'), 'url' => ['/setting/sms/sms-manual/index'], 'icon' => 'fa fa-angle-double-right'],
                                        ['label' => Yii::t('app', 'History  Sms'), 'url' => ['/setting/sms/history-sms'], 'icon' => 'fa fa-angle-double-right'],
                                    ]],
                            ]],
                    ],
        ]);*/
        ?>




    </section>

</aside>
