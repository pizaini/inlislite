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

$this->title = Yii::t('app', 'Cetak Label');
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
            '1'=>'Nomor Panggil & Barcode',
            '2'=>'Nomor Panggil',
            '3'=>'Barcode',
            '4'=>'Label Berwarna'),
    'size'=>'sm',
]);

  ?>
  </div>
  <div class="col-md-3">
  <?php 
  echo Select2::widget([
    'id' => 'cbActioncheckbox2',
    'name' => 'cbActioncheckbox2',
    'data' => array(
            '1'=>'1 Kolom',
            '2'=>'2 Kolom',
            '3'=>'Merk Golden Cock No.121 (2 Kolom)',
            '4'=>'Tom & Jerry Cock No.121 (2 Kolom)',
            '5'=>'3 Kolom'),
    'size'=>'sm',
]);

  ?>
  </div>
   <div id="actionDropdown"></div>
   <div class="col-md-4">
    <?php 
    echo Html::button('<i class="glyphicon glyphicon-check"></i> Cetak Label', [
                        'id'=>'btnCheckprocess',
                        'class' => 'btn btn-primary btn-sm', 
                        'title' => 'Cetak Label', 
                        //'data-toggle' => 'tooltip'
                    ]);
    ?>
    </div>
</div>
<div class="catalogs-salin-index">
    	 <?php Pjax::begin(['id' => 'myGridview']); 
    	 echo GridView::widget([
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
            'NomorBarcode',
            'NoInduk', 
            'CallNumber', 
            'Title',
            'Author',
            'Publishment',
            'PhysicalDescription',
            [
                'attribute'=>'Source_id',
                'value'=>'source.Name',
            ],
            [
                'attribute'=>'Category_id',
                'value'=>'category.Name',
            ],
            [
                'attribute'=>'Media_id',
                'value'=>'media.Name',
            ],
            [
                'attribute'=>'Rule_id',
                'value'=>'rule.Name',
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