<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\LocationLibrary $model
 */

$this->title = Yii::t('app', 'Update') . ' ' .Yii::t('app', 'Jenis Bahan') . ' ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jenis Bahan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="location-library-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
