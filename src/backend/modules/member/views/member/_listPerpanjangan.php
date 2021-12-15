<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\CollectionloanSearch $searchModel
 */


?>
<div class="collectionloans-index">


    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProviderPerpanjangan,
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
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute'=>'Tanggal','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
            [
                'attribute'=>'Biaya',
            ],
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'IsLunas',
                //'vAlign'=>'top',
                'label'=>'Lunas'
            ],
            ['attribute'=>'CreateDate','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
           /* ['attribute'=>'DueDate','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
            ['attribute'=>'ActualReturn','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
            'LateDays',*/

        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,

        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> Data Perpanjangan No.Anggota : ' . $model->MemberNo .' </h3>',
            'type'=>'info',
            'after'=>'<button type="button" class="btn btn-info" onclick="refresh()"><i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List').'</button>',
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>