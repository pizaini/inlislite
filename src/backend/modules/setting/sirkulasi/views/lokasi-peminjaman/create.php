<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\LocationLibrary $model
 */

$this->title = Yii::t('app', 'Tambah').' '.Yii::t('app', 'Lokasi Perpustakaan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lokasi Perputakaan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-library-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
