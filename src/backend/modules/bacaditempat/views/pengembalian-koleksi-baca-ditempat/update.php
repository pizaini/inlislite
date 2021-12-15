<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\BacaditempatKembali $model
 */

$this->title = Yii::t('app', 'Update Bacaditempat Kembali') . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bacaditempat Kembalis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="bacaditempat-kembali-update">

    <div class="page-header">
        <h3><span class="glyphicon glyphicon-edit"></span> Koreksi</h3>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
