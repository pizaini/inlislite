<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\BacaditempatSearch $searchModel
 */

$this->title = Yii::t('app','Koleksi Yang Dibaca').' '.$title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bacaditempat-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Bacaditempat', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>




    <?php Pjax::begin(); 

    if ($status == 'anggota') 
    {
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'toolbar'=> [
                ['content'=>
                     \common\components\PageSize::widget(
                        [
                            'template'=> '{label} <div class="col-sm-8" style="width:175px">{list}</div>',
                            'label'=>yii::t('app','Tampilkan :'),
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
            'filterModel' => $searchModel,
            'columns' => [
            
                ['class' => 'yii\grid\SerialColumn'],

                // Waktu.
                ['attribute'=>'CreateDate', 
                    // 'format'=> ['date', 'php:d-m-Y H:i:s'],
                    'label'=>Yii::t('app', 'Waktu Kunjungan'),
                    'value'=>function($model){
                        $time = strtotime($model->CreateDate);
                        return date('d-m-Y H:i:s',$time);
                    }
                ], 

                // No Anggota, 
                ['attribute'=>'NomorBarcode', 
                    'value'=>'collection.NomorBarcode',
                    'label'=>Yii::t('app','No Barcode')
                ],   

                // No Anggota, 
                ['attribute'=>'MemberNo', 
                    'value'=>'member.MemberNo',
                    'label'=>Yii::t('app','No Anggota')
                ],  

                // Nama, 
                // 'member.Fullname',
                ['attribute'=>'MemberFullname', 
                    'value'=>'member.Fullname',
                    'label'=>Yii::t('app','Nama')
                ],  
                
                // Judul, 
                [   
                    'attribute'=>'CatJudul', 
                    'contentOptions'=>['style'=>'max-width: 550px;'],
                    'value'=>'collection.catalog.Title', 
                    'label'=>Yii::t('app', 'Judul')
                ],  

                // 'collection.media.Name',
                ['attribute'=>'collectionmediaName', 
                    'value'=>'collection.media.Name', 
                    'label'=>Yii::t('app', 'Bentuk Fisik')
                ],

                // 'location.locationLibrary.Name',
                ['attribute'=>'locationlocationLibraryName', 
                    'value'=>'location.locationLibrary.Name', 
                    'label'=>Yii::t('app', 'Lokasi Perpustakaan')
                ],
                
                // Lokasi, 
                // 'location.Name',
                ['attribute'=>'LocationName', 
                    'value'=>'location.Name', 
                    'label'=>Yii::t('app', 'Lokasi Ruang')
                ],


               


                // // Penerbitan, 
                // [   
                //     'attribute'=>'CatPublisher', 
                //     'value'=>'collection.catalog.Publisher', 
                //     'label'=>Yii::t('app', 'Publisher')
                // ], 
                // // Edisi, 
                // [   
                //     'attribute'=>'CatEdition', 
                //     'value'=>'collection.catalog.Edition', 
                //     'label'=>Yii::t('app', 'Edisi')
                // ],  
                // // No Barcode, 
                // // 'collection.NomorBarcode',
                // ['attribute'=>'ColBarcode', 
                //     'value'=>'collection.NomorBarcode', 
                //     'label'=>Yii::t('app', 'No Barcode')
                // ],  


                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions'=>['style'=>'max-width: 75spx;'],
                    // 'template' => '<div class="btn-group-vertical"> {update} {delete} </div>',
                    'template' => '<div class="btn-group-vertical"> {delete} </div>',
                    'buttons' => [
                    // 'update' => function ($url, $model) {
                    //                                 return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app','Update'), Yii::$app->urlManager->createUrl(['bacaditempat/update','id' => $model->ID,'edit'=>'t']), [
                    //                                                 'title' => Yii::t('app', 'Edit'),
                    //                                                 'class' => 'btn btn-primary btn-sm'
                    //                                               ]);},
                                                      
                    'delete' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app','Delete'), Yii::$app->urlManager->createUrl(['bacaditempat/koleksi-dibaca/delete','id' => $model->ID,'edit'=>'t']), [
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
                // 'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success']),
                'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), 'javascript:history.go(0)', ['class' => 'btn btn-info']),
                'showFooter'=>false
            ],
        ]); 
    } 
    else if ($status == 'nonanggota') 
    {
        echo GridView::widget([
            'dataProvider' => $dataProvider,
    		'toolbar'=> [
                ['content'=>
                     \common\components\PageSize::widget(
                        [
                            'template'=> '{label} <div class="col-sm-8" style="width:175px">{list}</div>',
                            'label'=>yii::t('app','Tampilkan :'),
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

                // Waktu.
                // 'formattedcreatedate',
                // ['attribute'=>'WaktuKunjungan', 
                //     'label'=>Yii::t('app', 'Waktu Kunjung'),
                //     'value' => function($model){
                //         $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $model->CreateDate);
                //         $formatteddate = $myDateTime->format('d-m-Y H:i:s');
                //         return $formatteddate;
                //     }
                // ],
                ['attribute'=>'CreateDate', 
                    //'format'=> ['date', 'php:d-m-Y H:i:s'],
                    'label'=>Yii::t('app', 'Waktu Kunjungan'),
                    'value'=>function($model){
                        $time = strtotime($model->CreateDate);
                        return date('d-m-Y H:i:s',$time);
                    }
                ],  

                // No Anggota, 
                ['attribute'=>'NomorBarcode', 
                    'value'=>'collection.NomorBarcode',
                    'label'=>Yii::t('app','No Barcode')
                ],  

                // No Pengunjung,
                'NoPengunjung',

                //  Nama, 
                [   
                    'attribute'=>'GuestNama', 
                    'contentOptions'=>['style'=>'max-width: 550px;'],
                    'value'=>'memberguess.Nama', 
                    'label'=>Yii::t('app', 'Nama')
                ],  

                // Judul, 
                [   
                    'attribute'=>'CatJudul', 
                    'contentOptions'=>['style'=>'max-width: 550px;'],
                    'value'=>'collection.catalog.Title', 
                    'label'=>Yii::t('app', 'Judul')
                ],
                
                //  Lokasi, 
                // 'location.Name',
                ['attribute'=>'LocationName', 
                    'value'=>'location.Name', 
                    'label'=>Yii::t('app', 'Lokasi')
                ],

                // 'collection.media.Name',
                ['attribute'=>'collectionmediaName', 
                    'value'=>'collection.media.Name', 
                    'label'=>Yii::t('app', 'Bentuk Fisik')
                ],

                // 'location.locationLibrary.Name',
                ['attribute'=>'locationlocationLibraryName', 
                    'value'=>'location.locationLibrary.Name', 
                    'label'=>Yii::t('app', 'Lokasi Perpustakaan')
                ],
                
                // Lokasi, 
                // 'location.Name',
                ['attribute'=>'LocationName', 
                    'value'=>'location.Name', 
                    'label'=>Yii::t('app', 'Lokasi Ruang')
                ],



               
                // // Penerbitan, 
                // [   
                //     'attribute'=>'CatPublisher', 
                //     'value'=>'collection.catalog.Publisher', 
                //     'label'=>Yii::t('app', 'Publisher')
                // ], 
                // // Edisi, 
                // [   
                //     'attribute'=>'CatEdition', 
                //     'value'=>'collection.catalog.Edition', 
                //     'label'=>Yii::t('app', 'Edisi')
                // ],  
                // //  No Barcode, 
                // // 'collection.NomorBarcode',
                // ['attribute'=>'ColBarcode', 
                //     'value'=>'collection.NomorBarcode', 
                //     'label'=>Yii::t('app', 'No Barcode')
                // ],  


                // 'NoPengunjung',
                // 'collection_id',
                // 'Member_id',
                // 'Location_Id',

                [
                    'class' => 'yii\grid\ActionColumn',
    				'contentOptions'=>['style'=>'max-width: 75spx;'],
                    // 'template' => '<div class="btn-group-vertical"> {update} {delete} </div>',
                    'template' => '<div class="btn-group-vertical"> {delete} </div>',
                    'buttons' => [
    				// 'update' => function ($url, $model) {
                    //                                 return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app','Update'), Yii::$app->urlManager->createUrl(['bacaditempat/update','id' => $model->ID,'edit'=>'t']), [
                    //                                                 'title' => Yii::t('app', 'Edit'),
                    //                                                 'class' => 'btn btn-primary btn-sm'
                    //                                               ]);},
    												  
                    'delete' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app','Delete'), Yii::$app->urlManager->createUrl(['bacaditempat/koleksi-dibaca/delete','id' => $model->ID,'edit'=>'t']), [
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
                // 'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success']),
                'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), 'javascript:history.go(0)', ['class' => 'btn btn-info']),
                'showFooter'=>false
            ],
        ]); 
    }
    




    Pjax::end(); ?>

</div>
