

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
<div id="collectionerror"></div>
<?php Pjax::begin(['id' => 'myGridview']); echo GridView::widget([
        'id'=>'myGrid',
        'pjax'=>true,
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
                        'sizes'=>Yii::$app->params['pageSize'],
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
                  'choose' => function ($url, $model) use ($idjilid,$mode) {
                                      return Html::a('<span class="glyphicon glyphicon-check"></span> '.Yii::t('app','Choose'), '#', [
                                                      'title' => Yii::t('app', 'Choose'), 
                                                      //'data-toggle' => 'tooltip',
                                                      'class' => 'btn btn-primary btn-sm',
                                                      'data-dismiss'=>'modal',
                                                      'onClick' => '
                                                        $.ajax({
                                                            type     :"POST",
                                                            cache    : false,
                                                            url  : "'.Yii::$app->urlManager->createUrl(["akuisisi/koleksi-jilid/fill-serial-collection","id"=>$model->ID,"idjilid"=>$idjilid,"mode"=>$mode]).'",
                                                            success  : function(response) {
                                                                $("#collectionerror").html(response);
                                                                if(response == "")
                                                                {
                                                                   $.pjax.reload({container:"#myGridview"});  //Reload GridView
                                                                   //alertSwal("Data berhasil ditambahkan","success","2000");
                                                                }
                                                            }
                                                        });
                                                      '
                                                    ]);},

                ],
            ],
            ['class' => 'yii\grid\SerialColumn'],
            'NomorBarcode', 
            'RFID',
            'NoInduk',
            [
                'attribute'=>'DataBib',
                //'value'=>'source.Name',
                'format' => 'raw',
                'width' => '30%'
            ],
            'CallNumber',
            'EDISISERIAL',
            'TANGGAL_TERBIT_EDISI_SERIAL',
            'IDJILID',
            'NOMORPANGGILJILID',

            
        ],
        'containerOptions'=>['style'=>'font-size:12px'],
        //'summary'=>'',
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> Daftar Koleksi Serial</h3>',
            'type'=>'default',
            //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> ', ['create'], ['class' => 'btn btn-success','title' => Yii::t('app','Add'),'data-toggle' => 'tooltip',]),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false,
            //'heading'=>false,
        ],
    ]); Pjax::end(); ?>



