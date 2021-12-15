<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\LocationsSearch $searchModel
 */

$this->title = Yii::t('app', 'Locations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="locations-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Locations',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

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
        'pager' => [
            'firstPageLabel' => Yii::t('app','Awal'),
            'lastPageLabel'  => Yii::t('app','Akhir')
        ],
        'filterSelector' => 'select[name="per-page"]',
        'filterModel' => $searchModel,
 'columns' => [
		
            ['class' => 'yii\grid\SerialColumn'],

            'Code',
            'Name',
            'Description',
            // 'ISPUSTELING',
            //  [
            //     'class'=>'kartik\grid\BooleanColumn',
            //     'attribute'=>'ISPUSTELING', 
            //     'vAlign'=>'top'
            // ],
//            'UrlLogo:url', 
//            'IsPrintBarcode', 
//            'IsGenerateVisitorNumber', 
//            'IsInformationSought', 
//            'IsVisitsDestination', 

            [
                'class' => 'yii\grid\ActionColumn',
				'contentOptions'=>['style'=>'width: 200px;'],
                'template' => '<span style="display:inline">{update} {delete}</span>',
                'buttons' => [
				'update' => function ($url, $model) {
                                    //return Html::a('edit', Yii::$app->urlManager->createUrl(['setting/checkpoint/locations/update','id' => $model->ID,'edit'=>'t']),
        return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app', 'Update'), Yii::$app->urlManager->createUrl(['setting/checkpoint/locations/update','id' => $model->ID,'edit'=>'t']),                                        
        [
                                                    'title' => Yii::t('app', 'Update'),
                                                    'class' => 'btn btn-primary btn-sm'
                                                  ]);},
												  
                'delete' => function ($url, $model) {
                                                      
       //                             return Html::a('hapus', Yii::$app->urlManager->createUrl(['setting/checkpoint/locations/delete','id' => $model->ID,'edit'=>'t']),
        return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete'), Yii::$app->urlManager->createUrl(['setting/checkpoint/locations/delete','id' => $model->ID,'edit'=>'t']),                                        
        [
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
