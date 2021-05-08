<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\KelompokPelanggaran $model
 */

$this->title = Yii::t('app', 'Update') . ' ' .Yii::t('app', 'Kelompok Pelanggaran') . ' ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kelompok Pelanggarans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="kelompok-pelanggaran-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
