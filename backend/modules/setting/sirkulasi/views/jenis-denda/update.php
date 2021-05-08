<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\JenisDenda $model
 */

$this->title = Yii::t('app', 'Update') . ' ' .Yii::t('app', 'Jenis Denda') . ' ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jenis Dendas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="jenis-denda-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
