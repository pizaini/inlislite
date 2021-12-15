

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

 
 <?php 

 /* Pjax::begin(['id' => 'search']);
  echo $this->render('_searchAdvanced', ['model' => $searchModel,'rules' => $rules]);
  Pjax::end();*/

 Pjax::begin(['id' => 'myGridviewPilihJudul']); 
 echo GridView::widget([
        'id'=>'myGridPilihJudul',
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
                        // gridview dengan if
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
        //'filterModel' => $searchModel,
        'columns' => [
             [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'width: 50px;'],
                'template' => '<span style="display:inline">{choose}</span>',
                'buttons' => [
                    'choose' => function ($url, $model)  {
                    $id= $model->ID;
                    //$workshetid= $model->Worksheet_id;

                    return Html::a('<span class="glyphicon glyphicon-check"></span> '.Yii::t('app','Choose'), '#', [
                                  'title' => Yii::t('app', 'Choose'), 
                                  //'data-toggle' => 'tooltip',
                                  'data-dismiss'=>'modal',
                                  'class' => 'btn btn-primary btn-sm',
                                  'onClick' => 'js:sendCatalog('.$id.');'
                                ]);},

                ],
            ],
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'NomorBarcode',
                //'value'=>'source.Name',
                //'format' => 'raw',
            ],
            [
                'attribute'=>'TanggalPengadaan',
                'format' => 'date',
            ],
            'NoInduk',
            [
                'attribute'=>'DataBib',
                //'value'=>'source.Name',
                'format' => 'raw',
            ],
            [
                'attribute'=>'Media_id',
                'value'=>'media.Name',
            ],
            [
                'attribute'=>'Source_id',
                'value'=>'source.Name',
            ],
            [
                'attribute'=>'Category_id',
                'value'=>'category.Name',
            ],
            [
                'attribute'=>'Rule_id',
                'value'=>'rule.Name',
            ],
            [
                'attribute'=>'Status_id',
                'value'=>'status.Name',
            ],
            [
                'attribute'=>'Location_Library_id',
                'value'=>'locationLibrary.Name',
            ],
            [
                'attribute'=>'Location_id',
                'value'=>'location.Name',
            ],
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'ISOPAC', 
                'vAlign'=>'top'
            ],
            
        ],
        //'summary'=>'',
        'responsive'=>true,
        'containerOptions'=>['style'=>'font-size:12px'],
        'hover'=>true,
        'condensed'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',

            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>



<?php
 
$this->registerJs(
   '$("document").ready(function(){ 
        $("#search").on("pjax:end", function() {
            $.pjax.reload({container:"#myGridviewPilihJudul"});  //Reload GridView
        });
    });'
);
?>
 