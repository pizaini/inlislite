<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\LetterDetail $model
 */

$this->title = 'Create Letter Detail';
$this->params['breadcrumbs'][] = ['label' => 'Letter Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="letter-detail-create">
    <div class="page-header">
        <h3><span class="glyphicon glyphicon-plus-sign"></span> Tambah</h3>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
