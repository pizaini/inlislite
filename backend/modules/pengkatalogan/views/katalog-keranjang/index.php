<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\CollectionSearch $searchModel
 */

$this->title = Yii::t('app', 'Keranjang Katalog');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengkatalogan'), 'url' => Url::to(['/pengkatalogan'])];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="form-group" style="padding-bottom:30px">
  <div class="col-md-3">
   <?php 
  echo Select2::widget([
    'id' => 'cbActioncheckbox',
    'name' => 'cbActioncheckbox',
    'data' => array(
            'OPAC1'=>'Tampil di OPAC',
            'OPAC0'=>'Jangan tampil di OPAC',
            'KERANJANG0'=>'Hapus dari Keranjang Katalog',
            'KARANTINA'=>'Karantina Data'),
    'size'=>'sm',
]);

  ?>
  </div>
   <div id="actionDropdown"></div>
   <div class="col-md-1">
    <?php 
    echo Html::button('<i class="glyphicon glyphicon-check"></i> Proses', [
                        'id'=>'btnCheckprocess',
                        'class' => 'btn btn-primary btn-sm', 
                        'title' => 'Proses', 
                        //'data-toggle' => 'tooltip'
                    ]);
    ?>
    </div>
    <div class="col-md-1">
    <?php 
    echo Html::button('<i class="glyphicon glyphicon-trash"></i> Kosongkan Keranjang', [
                        'id'=>'btnCheckprocess',
                        'class' => 'btn btn-danger btn-sm', 
                        'title' => 'Kosongkan Keranjang', 
                        //'data-toggle' => 'tooltip'
                    ]);
    ?>
    </div>
</div>
<div class="catalogs-salin-index">
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
            [
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
            ],
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'BIBID',
                //'value'=>'source.Name',
                'format' => 'raw',
            ],
            [
                'attribute'=>'Title',
                //'value'=>'source.Name',
                'format' => 'raw',
            ],
            'Edition',
            'Publishment',
            'PhysicalDescription',
            'Subject',
            'CallNumber',
            [
                'attribute'=>'KontenDigital',
                'value'=>function($model) {
                    return $model->getCatalogfiles()->count();
                },
                'contentOptions'=>['style'=>'width: 150px;text-align:right;'],
            ],
            [
                'attribute'=>'Eksemplar',
                'value'=>function($model) {
                    return $model->getCollections()->count();
                },
                'contentOptions'=>['style'=>'width: 150px;text-align:right;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                /*'contentOptions'=>['style'=>'width: 250px;'],*/
                'template' => '<div class="btn-group-vertical">{update}{delete}</div>',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app', 'Edit'), Yii::$app->urlManager->createUrl(['akuisisi/koleksi/update','id' => $model->ID,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Edit'), 
                                                    //'data-toggle' => 'tooltip',
                                                    'class' => 'btn btn-primary btn-sm'
                                                  ]);},
                                                  
                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete'), Yii::$app->urlManager->createUrl(['akuisisi/koleksi/delete','id' => $model->ID,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Delete'),
                                                    //'data-toggle' => 'tooltip',
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'data' => [
                                                        'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                                                        'method' => 'post',
                                                    ],
                                                  ]);},

                ],
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
            //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> ', ['create'], ['class' => 'btn btn-success','title' => Yii::t('app','Add'),'data-toggle' => 'tooltip',]),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>
 </div>