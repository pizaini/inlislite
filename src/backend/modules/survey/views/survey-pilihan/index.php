<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

// use dosamigos\chartjs\ChartJs;
use dosamigos\highcharts\HighCharts;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\SurveyPilihanSearch $searchModel
 */

$this->title = 'Survey Pilihan';
$this->params['breadcrumbs'][] = $this->title;

$forChart = ($dataProvider->models);
// $forChart['value'] = $forChart['ChoosenCount'];
// unset($forChart[$ChoosenCount]);
?>
<div class="survey-pilihan-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Survey Pilihan', ['create'], ['class' => 'btn btn-success'])*/  ?>
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
            'Pilihan:ntext',
            //'ChoosenCount',
            ['attribute'=>'ChoosenCount','label'=>'Jumlah Responden'],


            [
                'class' => 'yii\grid\ActionColumn',
				'contentOptions'=>['style'=>'max-width: 90px;'],
                'template' => '<div class="btn-group-vertical"> {update} {delete} </div>',
                'buttons' => [
				'update' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app','Update'), Yii::$app->urlManager->createUrl(['survey/survey-pilihan/update','id' => $model->ID,'edit'=>'t']), [
                                    'title' => Yii::t('app', 'Edit'),
                                    'class' => 'btn btn-primary btn-sm'
                                  ]);},

                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app','Delete'), Yii::$app->urlManager->createUrl(['survey/survey-pilihan/delete','id' => $model->ID,'edit'=>'t','pid'=>$model->Survey_Pertanyaan_id]), [
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create','id'=>$id], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index','id'=>$id,'sid'=>$sid], ['class' => 'btn btn-info']).  Html::a('Kembali', Yii::$app->urlManager->createUrl(["survey/survey-pertanyaan/index",'id'=>$sid]) ,['class' => 'btn btn-warning pull-right','data-pjax'=>'0', ]),
            'showFooter'=>false
        ],
    ]); ?>

</div>

<div class="col-sm-12">
    <?php
    // Variable label untuk Diagram PIE
    $labelChart = array();

    // Variable untuk diagram batang
    $valueChart = array();
    $categoriesChart = array();

    foreach ($forChart as $forChart) {

        array_push($labelChart, ['y' => intval($forChart['ChoosenCount']),
            'name' => $forChart['Pilihan']]);

        array_push($valueChart,intval($forChart['ChoosenCount']));
        array_push($categoriesChart,$forChart['Pilihan']);
    }

    ?>

    <?php
    echo HighCharts::widget([
        'clientOptions' => [
            'chart' => [
                    'type' => 'pie'
            ],
            'title' => [
                 'text' => 'Survey Pilihan'
                 ],

            'tooltip' => [
                 'pointFormat' => '{series.name}: <b>{point.percentage:.1f}%</b>'
                 ],

            'plotOptions' => [
                 'pie' => [
                     'allowPointSelect' => true,
                     'cursor' => 'pointer',
                     'dataLabels' => ['enabled' => false],
                     'showInLegend' => true,
                     ],
                 ],
            'series' => [
                [
                    'name' => 'Pilihan',
                    'colorByPoint' => 'true',
                    'data' =>
                        $labelChart
                    ,
                ]
            ],
        ]
    ]);
     ?>

    <hr>

    <?php
    echo HighCharts::widget([
        'clientOptions' => [
            'chart' => [
                    'type' => 'column'
            ],
            'title' => [
                 'text' => 'Survey Pilihan'
                 ],
            'xAxis' => [
                'categories' => $categoriesChart
            ],
            'yAxis' => [
                'title' => [
                    'text' => 'Diagram Batang Survey Pilihan'
                ]
            ],
            'series' => [
                [
                'name'=>'Pilihan',
                'data' => $valueChart
                ]
            ]

        ]
    ]);
    ?>
</div>

<?php Pjax::end();  ?>
