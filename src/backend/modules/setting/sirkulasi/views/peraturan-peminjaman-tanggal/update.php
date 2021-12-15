<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\base\PeraturanPeminjamanTanggal $model
 */

$this->title = Yii::t('app', 'Update') . ' ' .Yii::t('app', 'Peraturan Peminjaman Tanggal') . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Peraturan Peminjaman Tanggals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="peraturan-peminjaman-tanggal-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
