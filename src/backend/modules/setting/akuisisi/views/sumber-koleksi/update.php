<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Collectionsources $model
 */

$this->title = Yii::t('app', 'Update Collectionsources') . ' ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Akuisisi'), 'url' => Url::to(['/setting/akuisisi'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collectionsources'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="collectionsources-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
