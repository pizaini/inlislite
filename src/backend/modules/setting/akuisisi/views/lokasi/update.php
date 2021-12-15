<?php

use yii\helpers\Html;
use yii\helpers\Url;


/**
 * @var yii\web\View $this
 * @var common\models\Locations $model
 */

$this->title = Yii::t('app', 'Update Locations') . ' Ruang ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Akuisisi'), 'url' => Url::to(['/setting/akuisisi'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Locations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="locations-update">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
