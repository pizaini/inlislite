<?php

use yii\helpers\Html;
use yii\helpers\Url;


/**
 * @var yii\web\View $this
 * @var common\models\Collectionmedias $model
 */

$this->title = Yii::t('app', 'Update Collectionmedias') . ' ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Akuisisi'), 'url' => Url::to(['/setting/akuisisi'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collectionmedias'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="collectionmedias-update">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
