<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\PelanggaranSearch $searchModel
 */

?>
<div class="pelanggaran-index">


   <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProviderPelanggaran,
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
        //'filterModel' => $searchModelPelanggaran,
 'columns' => [
        
            ['class' => 'yii\grid\SerialColumn'],

            'CollectionLoan_id',
            [
                'attribute'=>'collection.NomorBarcode',
                'value'=>'collection.NomorBarcode'
            ],
            [
                'attribute'=>'collection.catalog.Title',
                'value'=>'collection.catalog.Title'
            ],
            [
                'attribute'=>'Jenis Pelanggaran',
                'label'=> yii::t('app','Jenis Pelanggaran'),
                'value'=>'jenisPelanggaran.JenisPelanggaran'
            ],
            [
                'attribute'=>'Sangsi',
                'label'=> yii::t('app','Sangsi'),
                'value'=>'jenisDenda.Name'
            ],
            [
                'attribute'=>'JumlahDenda',
                'label'=> yii::t('app','Jumlah Denda'),
            ],
            [
                'attribute'=>'JumlahSuspend',
                'label'=> yii::t('app','Jumlah Suspend'),
            ],
            [
                'label'=> yii::t('app','Tgl. Denda'),
                'attribute'=>'CreateDate',
                'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']
            ],
//            'JumlahSuspend', 
//            'Paid:boolean', 
//            'Member_id', 
//            'Collection_id', 

            
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,

        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.yii::t('app','Data Pelanggaran No.Anggota : '). $model->MemberNo .' </h3>',
            'type'=>'info',
            //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success']),
            'after'=>'<button type="button" class="btn btn-info" onclick="refresh()"><i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List').'</button>',
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>