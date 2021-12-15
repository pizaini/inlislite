<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\JenisPerpustakaan $model
 */

$this->title = Yii::t('app', 'Update') . ' ' .Yii::t('app', 'Kependudukan') . ' ' . $model->namalengkap;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kependudukan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->namalengkap, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="jenis-perpustakaan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
