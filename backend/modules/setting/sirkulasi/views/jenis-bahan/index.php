<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\JenisPerpustakaanSearch $searchModel
 */

$this->title = Yii::t('app', 'Jenis Bahan');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="perpustakaan-daerah-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


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
            'Name',

            [
            	'attribute' => 'MaxPinjamKoleksi',
            	'label' => Yii::t('app','Maksimal Pinjam Koleksi')
            ],

            //'MaxLoanDays',
            [
            	'attribute' => 'MaxLoanDays',
            	'label' => Yii::t('app','Maks. Lama Pinjam')
            ],

            [
            	'attribute' => 'DendaTenorJumlah',
            	'label' => Yii::t('app','Jumlah Denda')
            ],


            [
            	'attribute' => 'DaySuspend',
            	'label' => Yii::t('app','Lama Skorsing')
            ],

            [
            	'attribute' => 'DayPerpanjang',
            	'label' => Yii::t('app','Maks. Lama Perpanjangan')
            ],

            [
            	'attribute' => 'CountPerpanjang',
            	'label' => Yii::t('app','Maks. Banyaknya Perpanjang')
            ],



            //'DendaTenorJumlah',
            //'DaySuspend',
            //'DayPerpanjang',
            //'CountPerpanjang',


            [
                'class' => 'yii\grid\ActionColumn',
				'contentOptions'=>['style'=>'max-width: 75px;'],
                'template' => '<div class="btn-group-vertical"> {update} </div>',
                'buttons' => [
				'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app', 'Update'), Yii::$app->urlManager->createUrl(['setting/sirkulasi/jenis-bahan/update','id' => $model->ID,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Update'),
                                                    'class' => 'btn btn-primary btn-sm'
                                                  ]);},
												  
                // 'delete' => function ($url, $model) {
                //                     return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete'), Yii::$app->urlManager->createUrl(['setting/sirkulasi/jenis-bahan/delete','id' => $model->ID,'edit'=>'t']), [
                //                                     'title' => Yii::t('app', 'Delete'),
                //                                     'class' => 'btn btn-danger btn-sm',
                //                                     'data' => [
                //                                         'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                //                                         'method' => 'post',
                //                                     ],
                //                                   ]);},



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
            //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success']),'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
