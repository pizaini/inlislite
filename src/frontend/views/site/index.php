<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = 'Portal Aplikasi Inlis Lite';
?>

<div class="container">
    <div class="row">

    <center>
        <?= yii\helpers\Html::img(Url::base().'/'.Url::to('uploaded_files/aplikasi/inlislite.png'), ['alt'=>'Portal Aplikasi Inlis Lite', 'class'=>'thing', 'style' => 'width:38%; margin-bottom:34px;']) ?>

        <p>
            <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('backend')?>"> Back Office</a>
            <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('bacaditempat')?>"> <?=  Yii::t('app', 'Baca ditempat') ?></a>
            <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('guestbook')?>"> <?=  Yii::t('app', 'Buku Tamu') ?></a>
            <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('keanggotaan')?>"> <?=  Yii::t('app', 'Keanggotaan Online') ?></a>
            <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('digitalcollection')?>"> <?=  Yii::t('app', 'Layanan Koleksi Digital') ?></a>
            <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('opac')?>"> <?=  Yii::t('app', 'OPAC') ?></a>
			<a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('article')?>"> <?=  Yii::t('app', 'Artikel') ?></a>
            <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('pendaftaran')?>"> <?=  Yii::t('app', 'Pendaftaran Anggota') ?></a>
            <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('statistik-perkembangan-perpustakaan')?>"> <?=  Yii::t('app', 'Statistik') ?></a>			
            <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('survey')?>"> <?=  Yii::t('app', 'Survey') ?></a>
            
            <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('pengembalianmandiri')?>"> <?=  Yii::t('app', 'Pengembalian Mandiri') ?></a>
            <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('peminjamanmandiri')?>"> <?=  Yii::t('app', 'Peminjaman Mandiri') ?></a>
        </p>

     </center>
    </div>
</div>

<div class="site-index">
    <center>
        <div class="mediumtron">
             <p>
               
             </p>

        </div>
    </center>
    <div class="body-content">
    </div>
</div>
