<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\base\PeraturanPeminjamanHari $model
 */

$this->title = Yii::t('app', 'Add').' '.Yii::t('app', 'Peraturan Peminjaman Hari');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Peraturan Peminjaman Haris'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="peraturan-peminjaman-hari-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
