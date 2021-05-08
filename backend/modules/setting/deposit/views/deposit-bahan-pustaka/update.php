<?php

use yii\helpers\Html;
use yii\helpers\Url;


/**
 * @var yii\web\View $this
 * @var common\models\Collectionmedias $model
 */

$this->title = Yii::t('app', 'Update Master Bahan Pustaka') . ' ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'SSKCKR'), 'url' => Url::to(['/setting/deposit'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Bahan Pustaka'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="collectionmedias-update">



    <?= $this->render('_form', [
    	'mode' => 'update',
        'model' => $model,
    ]) ?>

</div>
