<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\MasterUangJaminan $model
 */

$this->title = Yii::t('app', 'Setting').' '.Yii::t('app', 'Jaminan Peminjaman');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Loker'), 'url' => Url::to(['/setting/loker'])];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-uang-jaminan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
