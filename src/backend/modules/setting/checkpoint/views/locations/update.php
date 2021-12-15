<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Locations $model
 */

$this->title = Yii::t('app', 'Update Locations') . ' ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Locations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="locations-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
