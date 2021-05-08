<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Stockopnamedetail $model
 */

$this->title = Yii::t('app', 'Create Stockopnamedetail');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Stockopnamedetails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stockopnamedetail-create">
    <div class="page-header">
        <h3><span class="glyphicon glyphicon-plus-sign"></span> Tambah</h3>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
