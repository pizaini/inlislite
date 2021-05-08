<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\TujuanKunjungan $model
 */

$this->title = Yii::t('app', 'Update') . ' ' .Yii::t('app', 'Tujuan Kunjungan') . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tujuan Kunjungans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tujuan-kunjungan-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
