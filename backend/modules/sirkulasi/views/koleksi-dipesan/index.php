<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\CollectionloanitemSearch $searchModel
 */

$this->title = Yii::t('app', 'Daftar Koleksi Yang Dipesan');
$this->params['breadcrumbs'][] = 'Sirkulasi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collectionloanitems-index">
    <!-- <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(['id' => 'pjax-koleksi-dipesan']); 
    echo GridView::widget([
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
        'pager' => [
            'firstPageLabel' => Yii::t('app','Awal'),
            'lastPageLabel'  => Yii::t('app','Akhir')
        ],
        'filterSelector' => 'select[name="per-page"]',
        // 'filterModel' => $searchModel,
        'columns' => [

            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'bookingDate',
                'label' => Yii::t('app','Tgl. Pesan'),
                'value' => function($model){
                    $time = strtotime($model->bookingDate);
                    return date('d-m-Y H:i:s',$time);
                },
                'contentOptions'=>['style'=>'width: 200px;'],
            ],
            [
                'attribute' => 'member.MemberNo',
                'contentOptions'=>['style'=>'width: 150px;'],
            ],
            [
                'attribute' => 'member.Fullname',
                'contentOptions'=>['style'=>'width: 180px;'],
            ],[
                'attribute' => 'collections.NomorBarcode',
                'contentOptions'=>['style'=>'width: 150px;'],
            ],
            [
                'attribute' => 'collections.catalog.Title',
                'contentOptions'=>['style'=>'width: 400px;'],
            ],
            [
                'attribute' => 'collections.catalog.Publisher',
                'contentOptions'=>['style'=>'width: 400px;'],
            ],
            // [
            //     'attribute' => 'collections.catalog.Author',
            //     'contentOptions'=>['style'=>'width: 400px;'],
            // ],
            // 'bookingExpired',
            // 'catalog.Author',
            // 'catalog.Publisher',
            // 'catalog.Author',
            [
                'attribute' => 'collections.location.Name',
                'label'    => yii::t('app','Lokasi Ruang'),
                'contentOptions'=>['style'=>'width: 400px;'],
            ],
            [
                'attribute' => 'status',
                'contentOptions'=>['style'=>'width: 150px;'],
            ],
            // [
            //     'class' => 'yii\grid\ActionColumn',
            //     'contentOptions'=>['style'=>'max-width: 100px;'],
            //     'template' => '<div class="btn-group-vertical">{delete} </div>',
            //     'buttons' => [
            //         'delete' => function ($url, $model) {
            //             return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete'), Yii::$app->urlManager->createUrl(['sirkulasi/koleksi-dipesan/delete','id' => $model->ID,'member' => $model->BookingMemberID]), [
            //                 'title' => Yii::t('app', 'Delete'),
            //                 'class' => 'btn btn-danger btn-sm',
            //                 'data' => [
            //                     'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
            //                     'method' => 'post',
            //                 ],
            //             ]);},

            //     ],
            // ],


        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,
        'containerOptions'=>['style'=>'font-size:10px'],
        'options'=>['font-size'=>'10px'],
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
<?php
$script = <<< JS
$(document).ready(function() {
    setInterval(function() {      
        $.pjax.reload({container:'#pjax-koleksi-dipesan'});
    }, 60000); 
});
JS;
$this->registerJs($script);
?>