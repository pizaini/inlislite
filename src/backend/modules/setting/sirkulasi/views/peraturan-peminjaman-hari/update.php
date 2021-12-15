<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\base\PeraturanPeminjamanHari $model
 */

$this->title = Yii::t('app', 'Update') . ' ' . Yii::t('app', 'Peraturan Peminjaman Hari') . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Peraturan Peminjaman Haris'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="peraturan-peminjaman-hari-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
