<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\JenisPerpustakaanSearch $searchModel
 */

$this->title = Yii::t('app', 'Jenis Perpustakaan');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-perpustakaan-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Jenis Perpustakaan',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
		'toolbar'=> [
            ['content'=>
                 \common\components\PageSize::widget(
                    [
                        'template'=> '{label} <div class="col-sm-8" style="width:175px">{list}</div>',
                        'label'=>Yii::t('app', 'Tampilkan :'),
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
        'filterModel' => $searchModel,
        'columns' => [

            ['class' => 'yii\grid\SerialColumn'],

            'Name',
                     [

                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span> '.Yii::t('app', 'Form Entri').' <br> '.yii::t('app','Anggota'), Yii::$app->urlManager->createUrl(['setting/umum/jenis-perpustakaan/custom', 'id' => $data->ID]), [
                                'title' => Yii::t('app', 'Form Entri Anggota'),
                                'class' => 'btn btn-primary btn-sm'
                    ]);
                },
                        'contentOptions' => ['style' => 'width: 25px;'],
                    ],
                [

                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span> '.Yii::t('app', 'Kolom Daftar').' <br> '.yii::t('app','Anggota'), Yii::$app->urlManager->createUrl(['setting/umum/jenis-perpustakaan/formdaftaranggota', 'id' => $data->ID]), [
                                'title' => Yii::t('app', 'Kolom Daftar Anggota'),
                                'class' => 'btn btn-primary btn-sm'
                    ]);
                },
                        'contentOptions' => ['style' => 'width: 25px;'],
                    ],   
                        [

                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span> '.Yii::t('app', 'Form Entri').' <br> '.yii::t('app','Anggota Online'), Yii::$app->urlManager->createUrl(['setting/umum/jenis-perpustakaan/formentrianggotaonline', 'id' => $data->ID]), [
                                'title' => Yii::t('app', 'Form Entri Anggota Online'),
                                'class' => 'btn btn-primary btn-sm'
                    ]);
                },
                        'contentOptions' => ['style' => 'width: 25px;'],
                    ],
                //    [

                // 'format' => 'raw',
                // 'value' => function ($data) {
                //     return Html::a('<span class="glyphicon glyphicon-list-alt"></span> '.Yii::t('app', 'Form Edit').' <br> '.yii::t('app','Anggota Online'), Yii::$app->urlManager->createUrl(['setting/umum/jenis-perpustakaan/formeditanggotaonline', 'id' => $data->ID]), [
                //                 'title' => Yii::t('app', 'Form Edit Anggota Online'),
                //                 'class' => 'btn btn-primary btn-sm'
                //     ]);
                // },
                //         'contentOptions' => ['style' => 'width: 25px;'],
                //     ],       
                   [

                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span> '.Yii::t('app', 'Form Entri').' <br> '.yii::t('app','Peminjaman'), Yii::$app->urlManager->createUrl(['setting/umum/jenis-perpustakaan/formentripeminjaman', 'id' => $data->ID]), [
                                'title' => Yii::t('app', 'Form Entri Peminjaman'),
                                'class' => 'btn btn-primary btn-sm'
                    ]);
                },
                        'contentOptions' => ['style' => 'width: 25px;'],
                    ],
                        [

                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span> '.Yii::t('app', 'Form Entri').' <br> '.yii::t('app','Pengembalian'), Yii::$app->urlManager->createUrl(['setting/umum/jenis-perpustakaan/formentripengembalian', 'id' => $data->ID]), [
                                'title' => Yii::t('app', 'Form Entri Pengembalian'),
                                'class' => 'btn btn-primary btn-sm'
                    ]);
                },
                        'contentOptions' => ['style' => 'width: 25px;'],
                    ],
                         [

                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span> '.Yii::t('app', 'Form Info').' <br> '.yii::t('app','Anggota'), Yii::$app->urlManager->createUrl(['setting/umum/jenis-perpustakaan/forminfoanggota', 'id' => $data->ID]), [
                                'title' => Yii::t('app', 'Form Info Anggota'),
                                'class' => 'btn btn-primary btn-sm'
                    ]);
                },
                        'contentOptions' => ['style' => 'width: 25px;'],
                    ],   
            [
                'class' => 'yii\grid\ActionColumn',
				'contentOptions'=>['style'=>'max-width: 175px;'],
                'template' => '<div class="btn-group-vertical"> {update} {delete} </div>',
                'buttons' => [
				'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app', 'Update'), Yii::$app->urlManager->createUrl(['setting/umum/jenis-perpustakaan/update','id' => $model->ID,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Update'),
                                                    'class' => 'btn btn-primary btn-sm'
                                                  ]);},

                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete'), Yii::$app->urlManager->createUrl(['setting/umum/jenis-perpustakaan/delete','id' => $model->ID,'edit'=>'t']), [
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success']),'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
