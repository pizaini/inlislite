<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\SurveyIsianSearch $searchModel
 */

$this->title = 'Survey Isian';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-isian-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Survey Isian', ['create'], ['class' => 'btn btn-success'])*/  ?>
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

            //'Survey_Pertanyaan_id',
            //'Sesi',
            //'MemberNo',
            'Isian:ntext',

    //         [
    //             'class' => 'yii\grid\ActionColumn',
				// 'contentOptions'=>['style'=>'max-width: 75px;'],
    //             'template' => '<div class="btn-group-vertical"> {update} {delete} </div>',
    //             'buttons' => [
				// 'update' => function ($url, $model) {
    //                                 return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app','Update'), Yii::$app->urlManager->createUrl(['survey/survey-isian/update','id' => $model->ID,'edit'=>'t']), [
    //                                                 'title' => Yii::t('app', 'Edit'),
    //                                                 'class' => 'btn btn-primary btn-sm'
    //                                               ]);},
												  
    //             'delete' => function ($url, $model) {
    //                                 return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app','Delete'), Yii::$app->urlManager->createUrl(['survey/survey-isian/delete','id' => $model->ID,'edit'=>'t']), [
    //                                                 'title' => Yii::t('app', 'Delete'),
    //                                                 'class' => 'btn btn-danger btn-sm',
    //                                                 'data' => [
    //                                                     'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
    //                                                     'method' => 'post',
    //                                                 ],
    //                   ]);},
    //             ],
    //         ],
        


        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,

        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            // 'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']).  Html::a('Kembali', Yii::$app->urlManager->createUrl(["survey/survey-pertanyaan/index",'id'=>$sid]) ,['class' => 'btn btn-warning pull-right','data-pjax'=>'0', ]),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
