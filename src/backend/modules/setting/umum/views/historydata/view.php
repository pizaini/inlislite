<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Historydata */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Historydatas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="historydata-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            'Action',
            'TableName',
            'IDRef',
            'Note:ntext',
            'CreateBy',
            'CreateDate',
            'CreateTerminal',
            'UpdateBy',
            'UpdateDate',
            'UpdateTerminal',
            'Member_id',
        ],
    ]) ?>

</div>
