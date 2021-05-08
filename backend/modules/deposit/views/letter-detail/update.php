<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\LetterDetail $model
 */

$this->title = 'Update Letter Detail' . ' ' . $model->TITLE;
$this->params['breadcrumbs'][] = ['label' => 'Letter Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->TITLE, 'url' => ['view', 'id' => $model->LETTER_DETAIL_ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="letter-detail-update">

    <div class="page-header">
        <h3><span class="glyphicon glyphicon-edit"></span> Koreksi</h3>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
