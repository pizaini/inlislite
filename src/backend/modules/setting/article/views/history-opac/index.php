<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\CollectioncategorySearch $searchModel
 */

$this->title = Yii::t('app', 'Histori Pencarian');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Opac'), 'url' => Url::to(['/setting/opac'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="historyopac-index">

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        // set your toolbar
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
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
            'waktu',
        	'User_id',
            'ip',
            'jenis_pencarian',
            
             [
                'attribute'=> 'keyword',
                //'value'=>'source.Name',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a(Html::encode($model->keyword),$model->url);
                }
            ],  
            //'ruas',
            'jenis_bahan',

            
            
    
            /*['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'prod_name',
                'format' => 'raw',
                'value' => function ($model, $key, $index) { 
                    return Html::a($model->prod_name, ['update', 'id' => $model->id]);
                },
            ],*/


            
            //'IsDelete',
           /* [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'max-width: 25px;'],
                'template' => '{update} {delete}',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['setting/akuisisi/kategori-koleksi/update','User_id' => $model->User_id,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Edit'),
                                                    'class' => 'btn btn-primary btn-sm'
                                                  ]);},
                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['setting/akuisisi/kategori-koleksi/delete','User_id' => $model->User_id,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Delete'),
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'data' => [
                                                        'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                                                        'method' => 'post',
                                                    ],
                                                  ]);},


                ],

            ],*/
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
