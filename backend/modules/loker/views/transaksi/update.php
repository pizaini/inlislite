<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Lockers $model
 */

$this->title = 'Update Lockers' . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Lockers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lockers-update">

    <div class="page-header">
        <h3><span class="glyphicon glyphicon-edit"></span> Koreksi</h3>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
