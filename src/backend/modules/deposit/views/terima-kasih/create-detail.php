<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\LetterSearch $searchModel
 */
?>
<div class="letter-index">
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

            // 'LETTER_ID',
            // 'TYPE_OF_DELIVERY',
            // ['attribute'=>'LETTER_DATE','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
            'TITLE',
            // ['attribute'=>'ACCEPT_DATE','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            'SENDER', 
//            'PHONE', 
//            'INTENDED_TO', 
//            'IS_PRINTED', 
//            'PUBLISHER_ID', 
//            'LETTER_NUMBER_UT', 
//            'IS_SENDEDEMAIL:email', 
//            'IS_NOTE', 
//            'LANG', 

            [
                'class' => 'yii\grid\ActionColumn',
				'contentOptions'=>['style'=>'max-width: 20px;'],
                'template' => '{update} {delete}',
                'buttons' => [
				'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['deposit/deposit-ut/update','id' => $model->LETTER_ID,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Edit'),
                                                    'class' => 'btn btn-primary btn-sm'
                                                  ]);},
												  
                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['deposit/deposit-ut/delete','id' => $model->LETTER_ID,'edit'=>'t']), [
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
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
