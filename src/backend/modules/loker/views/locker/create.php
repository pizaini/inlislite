<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Lockers $model
 */

$this->title = 'Create Lockers';
$this->params['breadcrumbs'][] = ['label' => 'Lockers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lockers-create">
    <div class="page-header">
        <h3><span class="glyphicon glyphicon-plus-sign"></span> Tambah</h3>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
