<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\LocationLibrary $model
 */

$this->title = Yii::t('app', 'Koreksi Lokasi Perpustakaan') . ' ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lokasi Perpustakaan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="location-library-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
