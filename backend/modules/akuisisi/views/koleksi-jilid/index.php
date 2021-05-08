<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\QuarantinedCollectionSearch $searchModel
 */

$this->title = Yii::t('app', 'Jilid Collections');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Akuisisi'), 'url' => Url::to(['/akuisisi'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quarantined-collections-index">
<?php  echo $this->render('_searchAdvanced', ['model' => $searchModel,'rules' => $rules]); ?>

    <?php Pjax::begin(['id' => 'myGridview']); echo GridView::widget([
        'id'=>'myGrid',
        'pjax'=>true,
        'dataProvider' => $dataProvider,
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
        'pager' => [
            'firstPageLabel' => Yii::t('app','Awal'),
            'lastPageLabel'  => Yii::t('app','Akhir')
        ],
        'filterSelector' => 'select[name="per-page"]',
        //'filterModel' => $searchModel,
        'columns' => [
            /*[
                'class'       => '\kartik\grid\CheckboxColumn',
                'pageSummary' => true,
                'rowSelectedClass' => GridView::TYPE_INFO,
                'name' => 'cek',
                'checkboxOptions' => function ($searchModel, $key, $index, $column) {
                    return [
                        'value' => $searchModel->ID
                    ];
                },
                'vAlign' => GridView::ALIGN_TOP
            ],*/
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'IDJILID',
                'label'=> yii::t('app','ID Jilid'),
            ],
            [
                'attribute'=>'TahunJilid',
                'label'=> yii::t('app','Tahun Jilid'),
            ],
            [
                'attribute'=>'NOMORPANGGILJILID',
                'label'=> yii::t('app','Nomor Panggil Jilid'),
            ],
            // 'IDJILID', 
            [
                'attribute'=>'DataBib',
                //'value'=>'source.Name',
                'format' => 'raw',
            ],
            [
                'attribute'=>'Eksemplar',
                //'value'=>'source.Name',
                'format' => 'integer',
                'hAlign' => 'right'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                /*'contentOptions'=>['style'=>'width: 40px;'],*/
                'template' => '<span style="display:inline">{view}</span>',
                'buttons' => [
                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-book"></span> '.Yii::t('app', 'View'), Yii::$app->urlManager->createUrl(['akuisisi/koleksi-jilid/view','idjilid' => $model->IDJILID,'idcat' => $model->Catalog_id]), [
                                                    'title' => Yii::t('app', 'View'), 
                                                    //'data-toggle' => 'tooltip',
                                                    'class' => 'btn btn-primary btn-sm'
                                                  ]);},

                ],
            ],
        ],
        //'summary'=>'',
        'responsive'=>true,
        'containerOptions'=>['style'=>'font-size:12px'],
        'hover'=>true,
        'condensed'=>true,
        'options'=>['font-size'=>'11px'],
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add New Jilid'), ['create'], ['class' => 'btn btn-success btn-sm','title' => Yii::t('app','Add New Jilid'),/*'data-toggle' => 'tooltip',*/]),
            /*'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),*/
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
