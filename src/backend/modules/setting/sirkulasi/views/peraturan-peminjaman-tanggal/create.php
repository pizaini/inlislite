<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\base\PeraturanPeminjamanTanggal $model
 */

$this->title = Yii::t('app', 'Add').' '.Yii::t('app', 'Peraturan Peminjaman Tanggal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Peraturan Peminjaman Tanggals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="peraturan-peminjaman-tanggal-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
