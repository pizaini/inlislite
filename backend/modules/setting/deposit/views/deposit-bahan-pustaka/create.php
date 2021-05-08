<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Collectionmedias $model
 */

$this->title = Yii::t('app', 'Create Collectionmedias');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Akuisisi'), 'url' => Url::to(['/setting/akuisisi'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collectionmedias-create">

    <?= $this->render('_form', [
    	'mode' => 'create',
        'model' => $model,
    ]) ?>

</div>
