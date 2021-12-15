<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use leandrogehlen\querybuilder\QueryBuilderForm;
use yii\helpers\ArrayHelper;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\QuarantinedCollectionSearch $searchModel
 */

$this->title = Yii::t('app', 'Jilid Collections');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Akuisisi'), 'url' => Url::to(['/akuisisi'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quarantined-collections-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>

<div class="row">
    <div class="col-xs-9 col-xs-9 col-xs-offset-3">
<?php 

QueryBuilderForm::begin([
    'rules' => $rules,
    'builder' => [
        'id' => 'query-builder',
        'allowGroups' => false,
        'selectPlaceholder'=>'-Pilih Kriteria-',
        'filters' => [
            //['id' => 'ID', 'label' => 'Id', 'type' => 'integer'],
            ['id' => 'IDJILID', 'label' => 'IDJILID', 'type' => 'string'],
            ['id' => 'TahunJilid', 'label' => 'Tahun', 'type' => 'string'],
            ['id' => 'NOMORPANGGILJILID', 'label' => 'No.Panggil', 'type' => 'string'],
            ['id' => '(SELECT CONCAT(\'<b>\',catalogs.Title,\'</b>\',\'<br/>\',\'<br/>\',\'Penerbitan : \',catalogs.PublishLocation,\' \',catalogs.Publisher,\' \',catalogs.PublishYear) FROM catalogs WHERE a.Catalog_ID = catalogs.ID)', 'label' => 'Data Bibliografid', 'type' => 'string'],
            
             
        ]
    ]
 ])?>
 
  <div class="form-group pull-right">
      <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Cari', ['class' => 'btn btn-primary']); ?>
      <?php //echo Html::resetButton('Ulangi',['class' => 'btn btn-default']); 
        echo Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Ulangi'), ['index'], ['class' => 'btn btn-info']);
      ?>
  </div>
 <?php QueryBuilderForm::end() ?>
 </div>

</div>
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
       // 'filterModel' => $searchModel,
        'columns' => [
            /*[
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
            ['class' => 'yii\grid\SerialColumn'],
            'IDJILID', 
            'TahunJilid',
            'NOMORPANGGILJILID',
            [
                'attribute'=>'DataBib',
                //'value'=>'source.Name',
                'format' => 'raw',
            ],
            [
                'attribute'=>'JumlahKoleksi',
                //'value'=>'source.Name',
                'format' => 'integer',
                'hAlign' => 'right'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                /*'contentOptions'=>['style'=>'width: 40px;'],*/
                'template' => '<span style="display:inline">{view}</span>',
                'buttons' => [
                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-book"></span> '.Yii::t('app', 'View'), Yii::$app->urlManager->createUrl(['akuisisi/koleksi-jilid/view','idjilid' => $model->IDJILID,'idcat' => $model->Catalog_id]), [
                                                    'title' => Yii::t('app', 'View'), 
                                                    //'data-toggle' => 'tooltip',
                                                    'class' => 'btn btn-primary btn-sm'
                                                  ]);},

                ],
            ],
        ],
        //'summary'=>'',
        'responsive'=>true,
        'containerOptions'=>['style'=>'font-size:12px'],
        'hover'=>true,
        'condensed'=>true,
        'options'=>['font-size'=>'11px'],
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add New Jilid'), ['create'], ['class' => 'btn btn-success','title' => Yii::t('app','Add New Jilid'),/*'data-toggle' => 'tooltip',*/]),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
