<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\SumbanganKoleksi $model
 */

$this->title = Yii::t('app', 'Create Sumbangan Koleksi');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sumbangan Koleksis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sumbangan-koleksi-create">
    <div class="page-header">
        <h3><span class="glyphicon glyphicon-plus-sign"></span> Tambah</h3>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
