<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\LetterDetailSearch $searchModel
 */

?>
<div class="letter-detail-index">

    <?php Pjax::begin(['linkSelector' => 'a:not(.target-blank)']); echo GridView::widget([
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

            // 'ID',
            [
                'attribute'=>'collectionmedias.Name', 
                'label'=>Yii::t('app', 'Bahan Perpustakaan'),
            ],
            [
                'attribute'=>'TITLE', 
                'label'=>Yii::t('app', 'Judul'),
            ],
            [
                'attribute'=>'QUANTITY', 
                'label'=>Yii::t('app', 'Jumlah Judul'),
            ],
            [
                'attribute'=>'COPY', 
                'label'=>Yii::t('app', 'Jumlah Copy'),
            ],
            [
                'attribute'=>'PUBLISHER', 
                'label'=>Yii::t('app', 'Nama Penerbit'),
            ],
            [
                'attribute'=>'PUBLISHER_ADDRESS', 
                'label'=>Yii::t('app', 'Nama Penerbit'),
            ],
            [
                'attribute'=>'PUBLISH_YEAR', 
                'label'=>Yii::t('app', 'Tahun Terbit'),
            ],
            [
                'attribute'=>'ISBN', 
                'label'=>Yii::t('app', 'ISSN/ISBN'),
            ],
//            'PRICE', 
//            'LETTER_ID', 
//            'COLLECTION_TYPE_ID', 
//            'REMARK', 
//            'AUTHOR', 
//            'PUBLISHER', 
//            'PUBLISHER_ADDRESS', 
//            'ISBN', 
//            'PUBLISH_YEAR', 
//            'PUBLISHER_CITY', 
//            'ISBN_STATUS', 
//            'KD_PENERBIT_DTL', 

            [
                'class' => 'yii\grid\ActionColumn',
				'contentOptions'=>['style'=>'max-width: 20px;'],
                'template' => '{delete}',
                'buttons' => [
												  
                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['deposit/terima-kasih/delete','id' => $model->ID,'form'=>'LettDet']), [
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
            'heading'=>'<h3 class="panel-title"><b>Detail</b></h3>',
            // 'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']).' '.Html::a(Yii::t('app', 'Cetak Ucapan Terima Kasih'), ['cetak','id' => $model->ID], ['target'=>'_blank','class' => 'btn bg-green btn-md']),
            'showFooter'=>true
        ],
    ]); Pjax::end(); ?>

</div>
