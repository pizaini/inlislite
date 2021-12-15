<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\JenisPerpustakaanSearch $searchModel
 */

$this->title = Yii::t('app', 'Histori User');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="history-data-user">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Jenis Perpustakaan',
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
        'filterSelector' => 'select[name="per-page"]',
        'filterModel' => $searchModel,
        'columns' => [

        ['class' => 'yii\grid\SerialColumn'],

            //'ID',
            'Action',
            //'TableName',
            [
                'attribute'=>'TableName',
                'label'=>Yii::t('app','Nama Tabel')
            ],
            'IDRef',
            [
                'attribute'=>'CreateDate',
                'format'=>'date',
                'label'=>Yii::t('app','Tanggal')
            ],
            //'Note:ntext',
            [
                'attribute'=>'Note',
                'format'=>'raw',
                'contentOptions'=>['style'=>'max-width: 175px;word-wrap: break-word;'],
                'content'=> function($model){
                    return '<p>'.$model->Note.'</p>';
                },
                'label'=>Yii::t('app','Catatan')
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'max-width: 75px;'],
                'template' => '<div class="btn-group-vertical"> {delete} </div>',
                'buttons' => [

                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete'), Yii::$app->urlManager->createUrl(['setting/umum/user/delete-history','id' => $model->ID,'edit'=>'t']), [
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
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']).' '.Html::a(Yii::t('app', 'Back') , 'index',['class' =>  'btn btn-warning' ]),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
