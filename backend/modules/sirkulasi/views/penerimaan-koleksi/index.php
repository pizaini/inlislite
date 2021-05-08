<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\PengirimanSearch $searchModel
 */

$this->title = Yii::t('app', 'Verifikasi Koleksi Siap Layan');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengiriman-index">
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Pengiriman',
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

            'JudulKiriman:ntext',
            'PenanggungJawab',
            'NipPenanggungJawab',

            

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'width: 170px;'],
                'template' => '{detail} {cetak}',
                'buttons' => [
                // 'update' => function ($url, $model) {
                //                     return Html::a('<span class="glyphicon glyphicon-pencil"></span> Koreksi', Yii::$app->urlManager->createUrl(['sirkulasi/penerimaan-koleksi/update','id' => $model->ID,'edit'=>'t']), [
                //                                     'title' => Yii::t('app', 'Edit'),
                //                                     'class' => 'btn btn-primary btn-sm'
                //                                   ]);},
                                                  
                // 'delete' => function ($url, $model) {
                //                     return Html::a('<span class="glyphicon glyphicon-trash"></span> Hapus', Yii::$app->urlManager->createUrl(['sirkulasi/penerimaan-koleksi/delete','id' => $model->ID,'edit'=>'t']), [
                //                                     'title' => Yii::t('app', 'Delete'),
                //                                     'class' => 'btn btn-danger btn-sm',
                //                                     'data' => [
                //                                         'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                //                                         'method' => 'post',
                //                                     ],
                //                                   ]);},
                'detail' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span> '.Yii::t('app', 'Detail'), Yii::$app->urlManager->createUrl(['sirkulasi/penerimaan-koleksi/detail','id' => $model->ID,]), [
                                                    'title' => Yii::t('app', 'Detail'),
                                                    'class' => 'btn btn-primary btn-sm'
                                                  ]);},
                'cetak' => function ($url, $model) {
                                    return '<button class="btn btn-warning btn-sm" onclick="cetak('.$model->ID.')"><span class="glyphicon glyphicon-print"></span> Cetak</button>';
                                }

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
            // 'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success']),'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>

<input type="hidden" id="hdnUrlProsesCetak" value="<?=Yii::$app->urlManager->createUrl(["sirkulasi/penerimaan-koleksi/cetak"])?>">

<script type="text/javascript">
    function cetak(id){
        var url =  $('#hdnUrlProsesCetak').val();
        window.open(url+'?id='+id,'_blank');
    }
</script>
