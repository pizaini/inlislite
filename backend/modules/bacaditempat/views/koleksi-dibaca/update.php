<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Bacaditempat $model
 */

$this->title = 'Update Bacaditempat' . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Bacaditempats', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bacaditempat-update">

    <div class="page-header">
        <h3><span class="glyphicon glyphicon-edit"></span> Koreksi</h3>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
