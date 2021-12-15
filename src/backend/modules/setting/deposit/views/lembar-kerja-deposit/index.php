<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 */

$this->title = Yii::t('app', 'Ruas Data Bibliografis');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'SSKCKR'), 'url' => Url::to(['/setting/deposit/lembar-kerja-deposit/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settingparameters-create">

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
    ]) ?>

</div>
