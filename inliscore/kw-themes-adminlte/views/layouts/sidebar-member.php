<?php
use yii\bootstrap\Nav;
use inliscore\adminlte\widgets\SideMenu;
use mdm\admin\components\MenuHelper;
?>
<aside class="main-sidebar">

    <section class="sidebar">

    <?php
    if (Yii::$app->user->isGuest) {
        Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
    } else {
        echo Yii::$app->user->identity->NoAnggota;
    }
    
    $menuCallback = function($menu) {
            $item = [
                'label' => $menu['name'],
                'url' => MenuHelper::parseRoute($menu['route']),
            ];
            if (!empty($menu['data'])) {
                $item['icon'] = 'fa ' . $menu['data'];
            } else {
                $item['icon'] = 'fa fa-angle-double-right';
            }
            if ($menu['children'] != []) {
                $item['items'] = $menu['children'];
            }
            return $item;
        };

        echo SideMenu::widget(
            [
                'items' => [
                    ['label' => Yii::t('app', 'Dashboard'), 'url' => ['/site/index'], 'icon'=>'fa fa-dashboard'],
                    ['label' => Yii::t('app', 'Profile'), 'url' => ['/user/index'], 'icon'=>'fa fa-user'],
                    (Yii::$app->config->get('PerpanjanganKoleksiMandiri') == '1') ? ['label' => 'Perpanjangan Peminjaman', 'url' => ['/perpanjangan-koleksi/create'], 'icon'=>'fa fa-book'] : '',
                    (Yii::$app->config->get('PerpanjanganKenggotaanMandiri') == '1') ? ['label' => 'Perpanjangan Anggota', 'url' => ['/perpanjangan/create'], 'icon'=>'fa fa-book'] : '',
                    ['label' => Yii::t('app', 'Histori Perpanjangan Keanggotaan'), 'url' => ['/site/daftar-perpanjangan'], 'icon'=>'fa fa-retweet'],
                    ['label' => Yii::t('app', 'Histori Peminjaman Koleksi'), 'icon' => 'fa fa-book',
                            'items' => [
                                ['label' => Yii::t('app', 'Koleksi Sedang Dipinjam'), 'url' => ['/site/daftar-peminjaman'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => Yii::t('app', 'Koleksi Telah Dikembalikan'), 'url' => ['/site/daftar-pengembalian'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => Yii::t('app', 'Histori Pelanggaran'), 'url' => ['/site/daftar-pelanggaran'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => Yii::t('app', 'Koleksi Diperpanjang'), 'url' => ['/site/daftar-perpanjangan-sirkulasi'], 'icon' => 'fa fa-angle-double-right'],
                            ]
                    ],
                    ['label' => Yii::t('app', 'Histori Pemesanan Koleksi'), 'icon' => 'fa fa-book',
                            'items' => [
                                ['label' => Yii::t('app', 'Koleksi Sedang Dipesan'), 'url' => ['/site/koleksi-sedang-dipesan'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => Yii::t('app', 'Koleksi Pernah Dipesan'), 'url' => ['/site/koleksi-pernah-dipesan'], 'icon' => 'fa fa-angle-double-right'],
                            ]
                    ],
                    ['label' => Yii::t('app', 'Histori Baca ditempat'), 'url' => ['/site/baca-ditempat'], 'icon'=>'fa fa-dashboard'],
                    ['label' => Yii::t('app', 'Histori Kunjungan'), 'url' => ['/site/kunjungan'], 'icon'=>'fa fa-dashboard'],
                    ['label' => Yii::t('app', 'Histori Peminjaman Loker'), 'icon' => 'fa fa-key',
                            'items' => [
                                ['label' => Yii::t('app', 'Loker Sedang Dipinjam'), 'url' => ['/site/loker-pinjam'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => Yii::t('app', 'Loker Pernah Dipinjam'), 'url' => ['/site/loker-kembali'], 'icon' => 'fa fa-angle-double-right'],
                                ['label' => Yii::t('app', 'Histori Pelanggaran'), 'url' => ['/site/loker-pelanggaran'], 'icon' => 'fa fa-angle-double-right'],
                            ]
                    ],
                    //['label' => 'Histori Pengisian Survey', 'url' => ['/site/histori-survey'], 'icon'=>'fa fa-dashboard'],
                    ['label' => Yii::t('app', 'Histori Sumbangan Anggota'), 'url' => ['/site/sumbangan-anggota'], 'icon'=>'fa fa-dashboard'],
                    ['label' => Yii::t('app', 'Koleksi Favorit'), 'url' => ['/site/koleksi-favorit'], 'icon'=>'fa fa-dashboard'],
                    ['label' => Yii::t('app', 'Usulan Koleksi'), 'url' => ['/usulan-koleksi/index'], 'icon'=>'fa fa-dashboard'],
                    ['label' => Yii::t('app', 'Upload Koleksi'), 'url' => ['/katalog/index'], 'icon'=>'fa fa-dashboard'],
                ],
        ]);
    
    
    ?>




    </section>

</aside>
