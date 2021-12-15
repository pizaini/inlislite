<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Bacaditempat $model
 */

$this->title = 'Create Bacaditempat';
$this->params['breadcrumbs'][] = ['label' => 'Bacaditempats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bacaditempat-create">
    <div class="page-header">
        <h3><span class="glyphicon glyphicon-plus-sign"></span> Tambah</h3>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
