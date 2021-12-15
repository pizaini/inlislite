<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\JenisPelanggaran $model
 */

$this->title = Yii::t('app', 'Update') . ' ' .Yii::t('app', 'Jenis Pelanggaran') . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jenis Pelanggarans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="jenis-pelanggaran-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
