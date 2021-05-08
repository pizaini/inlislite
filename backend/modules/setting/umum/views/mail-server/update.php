<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\JenisPerpustakaan $model
 */

$this->title = Yii::t('app', 'Koreksi Mail Server') . ' ' . $model->Modul;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mail Server'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Modul, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="jenis-perpustakaan-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
