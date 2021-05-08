<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Partners $model
 */

$this->title = Yii::t('app', 'Tambah Nama Sumber Perolehan') . ' ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Akuisisi'), 'url' => Url::to(['/setting/akuisisi'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Partners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="partners-update">

<!--     <div class="page-header">
        <h3><span class="glyphicon glyphicon-edit"></span> <?=Yii::t('app','Update')?></h3>
    </div>
 -->
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
