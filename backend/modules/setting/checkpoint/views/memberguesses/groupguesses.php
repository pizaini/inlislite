<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\GroupguessesSearch $searchModel
 */

$this->title = Yii::t('app', 'Buku Tamu Rombongan');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="groupguesses-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Groupguesses',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
		'toolbar'=> [
            ['content'=>
                 \common\components\PageSize::widget(
                    [
                        'template'=> '{label} <div class="col-sm-8" style="width:175px">{list}</div>',
                        'label'=>yii::t('app','Tampilkan :'),
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
        'filterModel' => $searchModel,
        'columns' => [
		
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'CreateDate', 
                'label'=>Yii::t('app', 'Waktu Kunjungan'),
                'value'=>function($model)
                {
                    $time = strtotime($model->CreateDate);
                    return date('d-m-Y H:i:s',$time);
                },
                'contentOptions'=>['style'=>'width: 200px;'],
                'filterType'=>GridView::FILTER_DATE,
                'filterWidgetOptions'=>[
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                    ]
                ],
            ],  
            ['attribute'=>'NoPengunjung', 
                'label'=>Yii::t('app', 'No.Rombongan')
            ],  
            // 'NamaKetua',
            ['attribute'=>'NamaKetua', 
                'label'=>Yii::t('app', 'Ketua Rombongan')
            ], 

            [
            'attribute'=>'AsalInstansi',
            'label'=>Yii::t('app', 'Asal Instansi')
            ],

            ['attribute'=>'CountPersonel', 
            'label'=>Yii::t('app', 'Jumlah Personil')
            ], 
            // 'NomerTelponKetua',
            // 'EmailInstansi:email', 
            // 'AlamatInstansi',
            // 'CountPersonel',

            //'location.Name',
            ['attribute'=>'locationName',
                'value'=> 'location.Name',
                'label'=> Yii::t('app','Lokasi Ruang'),
                'width'=> '15.52%',
            ],

            // 'location.locationLibrary.Name',
            ['attribute'=>'locationlocationLibraryName',
                'value'=> 'location.locationLibrary.Name',
                'label'=> Yii::t('app','Lokasi Perpustakaan'),
                'width'=> '15.52%',
            ],
            [
                'attribute' => 'TujuanKunjungan',
                'label'=>Yii::t('app', 'Tujuan Kunjungan'),
                'value' => 'tujuanKunjungan.TujuanKunjungan',
            ],
//            'CountPNS', 
//            'CountPSwasta', 
//            'CountPeneliti', 
//            'CountGuru', 
//            'CountDosen', 
//            'CountPensiunan', 
//            'CountTNI', 
//            'CountWiraswasta', 
//            'CountPelajar', 
//            'CountMahasiswa', 
//            'CountLainnya', 
//            'CountSD', 
//            'CountSMP', 
//            'CountSMA', 
//            'CountD1', 
//            'CountD2', 
//            'CountD3', 
//            'CountS1', 
//            'CountS2', 
//            'CountS3', 
//            'CountLaki', 
//            'CountPerempuan', 
//            'TujuanKunjungan_ID', 
//            'Location_ID', 
//            'TeleponInstansi', 
//            'Information', 

            [
                'class' => 'yii\grid\ActionColumn',
				'contentOptions'=>['style'=>'max-width: 90px;'],
                'template' => '<div class="">{delete}</div>',
                'buttons' => [
				// 'update' => function ($url, $model) {
    //                                 return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app','Update'), Yii::$app->urlManager->createUrl(['groupguesses/update','id' => $model->ID,'edit'=>'t']), [
    //                                                 'title' => Yii::t('app', 'Edit'),
    //                                                 'class' => 'btn btn-primary btn-sm'
    //                                               ]);},
												  
                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app','Delete'), Yii::$app->urlManager->createUrl(['setting/checkpoint/memberguesses/delete-group','id' => $model->ID,'edit'=>'t']), [
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
        'floatHeader'=>false,

        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            // 'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), 'javascript:history.go(0)', ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
