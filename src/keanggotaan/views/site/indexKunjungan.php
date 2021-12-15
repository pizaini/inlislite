<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\CollectionloanitemSearch $searchModel
 */

$this->title = Yii::t('app', $title);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collectionloanitems-index">
    <!-- <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
        //'filterModel' => $searchModel,
        'columns' => [

            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'CreateDate',
                'header' => Yii::t('app', 'Tanggal Kunjungan'),
                'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:m:s']
            ],
            [
                'attribute' => 'location.locationLibrary.Name',
                'header' => Yii::t('app', 'Lokasi Perpustakaan'),
            ],
            [
                'attribute' => 'location.Name',
                'header' => Yii::t('app', 'Lokasi Ruangan'),
            ],
            [
                'attribute' => 'tujuanKunjungan.TujuanKunjungan',
                'header' => Yii::t('app', 'Tujuan Kunjungan'),
            ],
            [
                'attribute' => 'Information',
                'header' => Yii::t('app', 'Informasi Dicari'),
            ],
            

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
