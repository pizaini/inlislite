<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Pengiriman $model
 */

$this->title = Yii::t('app', 'Create Pengiriman');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengirimen'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengiriman-create">
    <div class="page-header">
        <h3><span class="glyphicon glyphicon-plus-sign"></span> Tambah</h3>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
