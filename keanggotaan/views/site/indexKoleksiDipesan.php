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
                'attribute' => 'member.MemberNo',
                'label'=>Yii::t('app', 'Member No')
            ],
            [
                'attribute' => 'member.Fullname',
                'label'=>Yii::t('app', 'Full Name')
            ],
            [
                'attribute' => 'collections.NomorBarcode',
                'label'=>Yii::t('app', 'Nomor Barcode'),
            ],
            //'CallNumber',
            [
                'attribute' => 'collections.catalog.Title',
                'label'=>Yii::t('app', 'Judul'),
            ],
            [
                'attribute' => 'collections.catalog.Author',
                'label'=>Yii::t('app', 'Pengarang'),
            ],
            [
                'attribute' => 'collections.catalog.Publisher',
                'label'=>Yii::t('app', 'Penerbit'),
            ],
        
            [
                'attribute' => 'collections.location.Name',
                'label'    => Yii::t('app', 'Lokasi Ruang'),
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
