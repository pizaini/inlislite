
<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\MemberSearch $searchModel
 */

$this->title = Yii::t('app', 'Koleksi Diperpanjang');
//$this->params['breadcrumbs'][] = $this->title;
?>

<?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'toolbar'=> [
            ['content'=>
                 \common\components\PageSize::widget(
                    [
                        'template'=> '{label} <div class="col-sm-8" style="width:175px">{list}</div>',
                        'label'=>'Tampilkan :',
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

            ['class' => 'yii\grid\SerialColumn'],
           
            [
                'attribute'=>'CollectionLoan_id', 
                'width'=>'110px',
                'label'=>Yii::t('app', 'No.Peminjaman'),
                'group'=>true,  // enable grouping
                'vAlign'=>'middle',
                'hAlign'=>'center',
            ],
            
            [
                'attribute'=>'collection.NomorBarcode', 
                'label'=>Yii::t('app', 'No.Barcode'),
            ],
            [
                'attribute'=>'collection.catalog.ControlNumber', 
                'label'=>'Control Number',
            ],
            [
                'attribute'=>'collection.catalog.Title', 
                'label'=>Yii::t('app', 'Judul'),
            ],
            [
                'attribute'=>'collection.catalog.Author', 
                'label'=>Yii::t('app', 'Pengarang'),
            ],
            [
                'attribute'=>'collection.catalog.Publisher', 
                'label'=>Yii::t('app', 'Penerbit'),
            ],
            [
                'attribute'=>'DateExtend', 
                'label'=>Yii::t('app', 'Tanggal Perpanjang'),
            ],
            [
                'attribute'=>'DueDateExtend', 
                'label'=>Yii::t('app', 'Tanggal Jatuh Tempo'),
            ],
            
            
],

        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,

        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            /*'before'=>Html::a('<i class="glyphicon glyphicon-plus">
                </i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success']),*/
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>
