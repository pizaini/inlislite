<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\StockopnameSearch $searchModel
 */

$this->title = Yii::t('app', 'Stock Opname');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stockopname-index">
    <div class="page-header">
            <h1><?= Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success']) ?></h1>
    </div>
   

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
        'pager' => [
            'firstPageLabel' => Yii::t('app','Awal'),
            'lastPageLabel'  => Yii::t('app','Akhir')
        ],
        'filterSelector' => 'select[name="per-page"]',
        'filterModel' => $searchModel,
 'columns' => [
		
            ['class' => 'yii\grid\SerialColumn'],

            'ProjectName',
            [
            'attribute'=>'TglMulai',
            'label'    => yii::t('app','Tgl.Mulai'),
            'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']
            ],
            [
            'attribute'=>'Tahun',
            'label'    => yii::t('app','Tahun'),
            ],
            [
            'attribute'=>'Koordinator',
            'label'    => yii::t('app','Koordinator'),
            ],
            [
            'attribute'=>'Keterangan',
            'label'    => yii::t('app','Keterangan'),
            ],

            [
                'class' => 'yii\grid\ActionColumn',
				'contentOptions'=>['style'=>'width: 250px;'],
                'template' => '{update} {delete} {detail}',
                'buttons' => [
				'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> Koreksi', Yii::$app->urlManager->createUrl(['sirkulasi/stockopname/update','id' => $model->ID,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Edit'),
                                                    'class' => 'btn btn-primary btn-sm'
                                                  ]);},
												  
                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span> Hapus', Yii::$app->urlManager->createUrl(['sirkulasi/stockopname/delete','id' => $model->ID,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Delete'),
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'data' => [
                                                        'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                                                        'method' => 'post',
                                                    ],
                                                  ]);},
                'detail' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span> '.Yii::t('app', 'Detail'), Yii::$app->urlManager->createUrl(['sirkulasi/stockopname/detail','id' => $model->ID,]), [
                                                    'title' => Yii::t('app', 'Detail'),
                                                    'class' => 'btn btn-primary btn-sm'
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
            //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
