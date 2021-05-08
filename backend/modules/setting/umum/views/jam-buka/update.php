<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\MasterJamBuka $model
 */

$this->title = Yii::t('app', 'Update') . ' ' .Yii::t('app', 'Jam Operasional Layanan') . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jam Operasional Layanan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="master-jam-buka-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
