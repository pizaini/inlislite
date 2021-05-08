<?php
/**
 * @link https://www.inlislite.perpusnas.go.id/
 * @copyright Copyright (c) 2015 Perpustakaan Nasional Republik Indonesia
 * @license https://www.inlislite.perpusnas.go.id/licences
 */

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;

use kartik\widgets\ActiveForm;
$this->title = Yii::t('app', 'Pendaftaran Anggota');
?>

<div style="clear:both;"></div>


<div class="col-md-12">
<?= Yii::t('app', 'Klik') ?> <?= Html::a(Yii::t('app', 'disini'), ['/pendaftaran/anggota-aktif/']); ?>, <?= Yii::t('app', 'jika anda telah terdaftar sebagai anggota, namun belum memiliki user dan password akses layanan Keanggotaan Online.') ?>
<br/><br/>
<!-- Retrieve data membersrule -->
<?php foreach ($model as $data) : ?>
    <p>
        <b><?=$data->NameCategory ?></b><br/>
        <?=$data->Contents ?>
    </p>
    <hr/>
<?php endforeach; ?>

<!-- End Retreive -->

        

</div>     
     <div style="margin-left:45px; margin-bottom:45px; width:100%;">   
      <?= Html::a(Yii::t('app', 'Lanjutkan Registrasi'), ['/pendaftaran/anggota/'], ['class' => 'btn btn-md btn-warning',]); ?>
    </div>
       


