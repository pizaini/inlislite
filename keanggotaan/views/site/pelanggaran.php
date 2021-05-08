
<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\MemberSearch $searchModel
 */

$this->title = Yii::t('app', 'Histori Pelanggaran');
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
            
            //'ID',
            [
                'attribute'=>'collection.NomorBarcode', 
                'label'=>Yii::t('app', 'No.Barcode'),
            ],
            [
                'attribute'=>'collection.catalog.Title', 
                'label'=>Yii::t('app', 'Judul'),
            ],
            [
                'attribute'=>'jenisPelanggaran.JenisPelanggaran', 
                'label'=>Yii::t('app', 'Jenis Pelanggaran'),
            ],
            [
                'attribute'=>'jenisDenda.Name', 
                'label'=>Yii::t('app', 'Sangsi'),
            ],
            [
                'attribute'=>'JumlahDenda', 
                'label'=>Yii::t('app', 'Jumlah Denda'),
            ],
            [
                'attribute'=>'JumlahSuspend', 
                'label'=>Yii::t('app', 'Jumlah Suspend'),
            ],
            // 'JumlahDenda',
            // 'JumlahSuspend',
            [
                'attribute'=>'CreateDate',
                'label'=>Yii::t('app', 'Tgl.Denda'),
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']
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