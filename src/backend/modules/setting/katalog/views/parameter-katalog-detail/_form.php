<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="settingparameters-form">


<?php $form = ActiveForm::begin([
'type'=>ActiveForm::TYPE_HORIZONTAL,
'formConfig' => ['deviceSize' => ActiveForm::SIZE_SMALL],
]); ?>


<div class="form-group">

        <div class="col-sm-3 col-sm-offset-1">
            <?=$form->field($model,'TagInp')->textInput(['inline'=>true])->label(Yii::t('app', 'Tag'))?>
        </div>
        <div class="col-sm-2">
            <?=Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success' ])?>
        </div>
    </div>

<?php Pjax::begin(['id' => 'myGridview']); ?>

<?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'Tag',
                'value'=>'field.Tag',
                'contentOptions' => ['style' => 'width: 100px;'],
                'filter'=>false,
            ],
            [
                'attribute'=>'Field_id',
                'value'=>'field.Name',
                'filter'=>false,
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'width: 50px;'],
                'template' => '{delete}',
                'buttons' => [
                                                  
                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['setting/katalog/parameter-katalog-detail/delete','id' => $model->ID,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Delete'),
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'data' => [
                                                        'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                                                        'method' => 'post',
                                                    ],
                                                  ]);},

                ],
            ],
            
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'summary'=>'',
        'options' => ['style' => 'width: 550px;'],
    ]); ?>

<?php Pjax::end(); ?>



</div>


