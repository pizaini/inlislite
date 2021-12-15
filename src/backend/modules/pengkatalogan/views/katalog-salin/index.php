<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\CollectionSearch $searchModel
 */

$this->title = Yii::t('app', 'Salin Katalog');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengkatalogan'), 'url' => Url::to(['/pengkatalogan'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalogs-salin-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>

 </div>