<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\base\PeraturanPeminjamanHariSearch $searchModel
 */

$this->title = Yii::t('app', 'Peraturan Peminjaman Hari');
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="peraturan-peminjaman-hari-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Peraturan Peminjaman Hari',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
		'toolbar'=> [
            ['content'=>
                 \common\components\PageSize::widget(
                    [
                        'template'=> '{label} <div class="col-sm-8" style="width:175px">{list}</div>',
                        'label'=>Yii::t('app', 'Tampilkan :'),
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

            //'DayIndex',
            [
                'attribute' => 'DayIndex',
                'label' => Yii::t('app','Hari'),
                'value' => function($model)
                {
                    $day = [1=>yii::t('app','Senin'),2=>yii::t('app','Selasa'),3=>yii::t('app','Rabu'),4=>yii::t('app','Kamis'),5=>yii::t('app','Jum\'at'),6=>yii::t('app','Sabtu'),7=>yii::t('app','Mingggu')];
                    return $day[$model->DayIndex];
                },
            ],

            [
                'attribute' => 'MaxLoanDays',
                'label' => Yii::t('app','Maks. Lama Pinjam')
            ],
            // 'DendaPerTenor',
            [
                'attribute' => 'DendaPerTenor',
                'label' => Yii::t('app','Jumlah Denda')
            ],
            // 'DaySuspend',
            [
                'attribute' => 'DaySuspend',
                'label' => Yii::t('app','Lama Suspend')
            ],
            //'DayPerpanjang', 
            [
                'attribute' => 'DayPerpanjang',
                'label' => Yii::t('app','Maks. Lama Perpanjangan')
            ],

            // 'CountPerpanjang', 
            [
                'attribute'=> 'CountPerpanjang',
                'label' => Yii::t('app','Maks. Banyaknya Perpanjang')
            ], 

            
            // 'MaxPinjamKoleksi',
            // 'MaxLoanDays',
            // 'DendaTenorJumlah',
            // 'DendaTenorSatuan',


            [
                'class' => 'yii\grid\ActionColumn',
				'contentOptions'=>['style'=>'max-width: 100px;'],
                 'template' => '<div class="btn-group-vertical"> {update} {delete}</div>',
                'buttons' => [
				'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app', 'Update'), Yii::$app->urlManager->createUrl(['setting/sirkulasi/peraturan-peminjaman-hari/update','id' => $model->ID,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Update'),
                                                    'class' => 'btn btn-primary btn-sm'
                                                  ]);},
												  
                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete'), Yii::$app->urlManager->createUrl(['setting/sirkulasi/peraturan-peminjaman-hari/delete','id' => $model->ID,'edit'=>'t']), [
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success']),'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
