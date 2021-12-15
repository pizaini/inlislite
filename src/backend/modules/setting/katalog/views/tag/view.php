<?php


use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Fields $model
 */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Katalog'), 'url' => Url::to(['/setting/katalog'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fields'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fields-view">
    <p> <?= Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) ?>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Remove'), ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
            'model' => $model,
            
        'attributes' => [
            [
                'attribute'=>'Group_id',
                'value'=>$model->group->Name,
            ],
            [
                'attribute'=>'Format_id',
                'value'=>$model->format->Name,
            ],
            'Tag',
            'Name',
            'Fixed:boolean',
            'Enabled:boolean',
            'Repeatable:boolean',
            'Length',
            'Mandatory:boolean',
            'IsCustomable:boolean',
            //'IsDelete',
            //'DEFAULTSUBTAG',
            //'ISSUBSERIAL:boolean',
        ],
       
    ]) ?>

</div>
