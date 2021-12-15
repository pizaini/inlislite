

<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use kartik\grid\GridView;



/**
 * @var yii\web\View $this
 * @var common\models\Collections $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div id="msgerror"></div>
<?php Pjax::begin(['id' => 'myGridviewTag']); echo GridView::widget([
        'id'=>'myGrid',
        'pjax'=>true,
        'pjaxSettings' => [
            'options' => [
                'enablePushState' => false,
            ],
        ],
        'dataProvider' => $dataProvider,
        'toolbar'=> [
            ['content'=>
                 \common\components\PageSize::widget(
                    [
                        'template'=> '{label} <div class="col-sm-8" style="width:175px">{list}</div>',
                        'label'=>Yii::t('app', 'Showing :'),
                        'labelOptions'=>[
                            'class'=>'col-sm-4 control-label',
                            'style'=>[
                                'width'=> '75px',
                                'margin'=> '0px',
                                'padding'=> '0px',
                            ]

                        ],
                        'sizes'=>(Yii::$app->config->get('language') != 'en' ? Yii::$app->params['pageSize'] : Yii::$app->params['pageSize_ing']),
                        'options'=>[
                            'id'=>'aa',
                            'class'=>'form-control'
                        ]
                    ]
                 )

            ],

            //'{toggleData}',
            '{export}',
        ],
        'filterSelector' => 'select[name="per-page"]',
        'filterModel' => $searchModel,
        'columns' => [
           /* [
                'class'       => '\kartik\grid\CheckboxColumn',
                'pageSummary' => true,
                'rowSelectedClass' => GridView::TYPE_INFO,
                'name' => 'cek',
                'checkboxOptions' => function ($searchModel, $key, $index, $column) {
                    return [
                        'value' => $searchModel->ID
                    ];
                },
                'vAlign' => GridView::ALIGN_TOP
            ],*/
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'width: 50px;'],
                'template' => '<span style="display:inline">{choose}</span>',
                'buttons' => [
                    'choose' => function ($url, $model)  {
                    $id= $model->ID;
                    $code= $model->Tag;
                    $desc= $model->Name;
                    $fixed= (int)$model->Fixed;
                    $enabled= (int)$model->Enabled;
                    $panjang= $model->Length;
                    $mandatory= (int)$model->Mandatory;
                    $iscustomable= (int)$model->IsCustomable;
                    $repeatable= (int)$model->Repeatable;

                    return Html::a('<span class="glyphicon glyphicon-check"></span> '.Yii::t('app','Choose'), '#', [
                                  'title' => Yii::t('app', 'Choose'), 
                                  //'data-toggle' => 'tooltip',
                                  'class' => 'btn btn-primary btn-sm',
                                  'onClick' => 'js:SendTag("'.$id.'","'.$code.'","'.$desc.'",'.$fixed.','.$enabled.','.$panjang.','.$mandatory.','.$iscustomable.','.$repeatable.');'
                                ]);},

                ],
            ],
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'Format_id',
                'value'=>'format.Name',
            ],
            [
                'attribute'=>'Group_id',
                'value'=>'group.Name',
            ],
            'Tag',
            'Name',
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'Fixed', 
                'vAlign'=>'middle'
            ],
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'Enabled', 
                'vAlign'=>'middle'
            ],
            [
                'attribute'=>'Length',
                'contentOptions'=>['style'=>'text-align:right;'],
            ],
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'Repeatable', 
                'vAlign'=>'middle'
            ],
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'Mandatory', 
                'vAlign'=>'middle'
            ],
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'IsCustomable', 
                'vAlign'=>'middle'
            ],

            
        ],
        'containerOptions'=>['style'=>'font-size:12px'],
        //'summary'=>'',
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> Daftar Tag</h3>',
            'type'=>'default',
            //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> ', ['create'], ['class' => 'btn btn-success','title' => Yii::t('app','Add'),'data-toggle' => 'tooltip',]),
            //'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false,
            //'heading'=>false,
        ],
    ]); Pjax::end(); ?>



