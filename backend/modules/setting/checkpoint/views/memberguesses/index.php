<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use common\models\MasterPekerjaan;
use common\models\Members;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\MemberguessesSearch $searchModel
 */

$this->title = Yii::t('app','Buku Tamu').' '.$title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="memberguesses-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Memberguesses', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin();

    if ($listFor == 'anggota' )
    {
        // Menampilkan table untuk Anggota
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


                // Informasi untuk anggota
                ['class' => 'yii\grid\SerialColumn'],
                ['attribute'=>'CreateDate',
                    'label'=>Yii::t('app', 'Waktu Kunjungan'),
                    'value'=>function($model)
                    {
                        $time = strtotime($model->CreateDate);
                        return date('d-m-Y H:i:s',$time);
                    },                  
                    'contentOptions'=>['style'=>'width: 200px;'],
                    'filterType'=>GridView::FILTER_DATE,
                    'filterWidgetOptions'=>[
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
                        ]
                    ],      
                ],
                'NoAnggota',
                // 'memberinfo.Fullname',
                ['attribute'=>'Nama',
                    'label'=> Yii::t('app','Nama Lengkap'),
                    'width'=> '15.52%',
                ],

                // 'memberinfo.JenisAnggota_id',
                ['attribute'=>'memberinfoJenisAnggota_id',
                    'value'=> 'memberinfo.jenisAnggota.jenisanggota',
                    'label'=> Yii::t('app','Jenis Anggota'),
                    'width'=> '100px',
                ],
                
                //'location.Name',
                ['attribute'=>'locationName',
                    'value'=> 'location.Name',
                    'label'=> Yii::t('app','Lokasi Ruang'),
                    'width'=> '15.52%',
                ],

                // 'location.locationLibrary.Name',
                ['attribute'=>'locationlocationLibraryName',
                    'value'=> 'location.locationLibrary.Name',
                    'label'=> Yii::t('app','Lokasi Perpustakaan'),
                    'width'=> '15.52%',
                ],

                [
                    'attribute' => 'TujuanKunjungan',
                    'label'=> Yii::t('app','Tujuan Kunjungan'),
                    'value' => 'tujuanKunjungan.TujuanKunjungan',
                ],
                // 'tujuanKunjungan.TujuanKunjungan',
                // ['attribute'=>'memberinfoAddressNow',
                //     'value'=> 'memberinfo.AddressNow',
                //     'label' => Yii::t('app','Alamat')
                // ],
                // ['attribute'=>'memberinfoPhone',
                //     'width'=> '100px',
                //     'value'=> 'memberinfo.Phone',
                //     'label' => Yii::t('app','Phone')
                // ],
                // // 'memberinfo.Email',
                // ['attribute'=>'memberinfoEmail',
                //     'width'=> '100px',
                //     'value'=> 'memberinfo.Email',
                //     'label' => Yii::t('app','Email')
                // ],

                [
                    'class' => 'yii\grid\ActionColumn',
    				'contentOptions'=>['style'=>'max-width: 90px;'],
                    'template' => '{delete} ',
                    'buttons' => [
                    'delete' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app','Delete'), Yii::$app->urlManager->createUrl(['setting/checkpoint/memberguesses/delete','id' => $model->ID,'edit'=>'t']), [
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
    else
    {
        // Menampilkan list untuk Non Anggota
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


                // Informasi untuk anggota
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute'=>'CreateDate',
                    'label'=>Yii::t('app', 'Waktu Kunjungan'),
                    'value'=>function($model)
                    {
                        $time = strtotime($model->CreateDate);
                        return date('d-m-Y H:i:s',$time);
                    },                    
                    'contentOptions'=>['style'=>'width: 200px;'],
                    'filterType'=>GridView::FILTER_DATE,
                    'filterWidgetOptions'=>[
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
                        ]
                    ],  
                ],
                [
                'attribute'=>'NoPengunjung',
                'label'=>Yii::t('app', 'Nomor Pengunjung')
                ],
                'Nama',
                [
                'attribute'=>'JenisKelamin_id', 
                'value'=> 'jenisKelamin.Name', 
                'label'=>Yii::t('app', 'Jenis Kelamin')
                ],
                // ['attribute'=>'Profesi_id', 'value'=> 'profesi.Pekerjaan', 'label'=>Yii::t('app', 'Profesi')],
                // ['attribute'=>'PendidikanTerakhir_id', 'value'=> 'pendidikanTerakhir.Nama', 'label'=>Yii::t('app', 'Pendidikan Terakhir')],

                // 'Profesi_id',

                [
                'attribute'=>'Alamat',
                'label'=>Yii::t('app', 'Alamat')
                
                ],

                //'location.Name',
                ['attribute'=>'locationName',
                    'value'=> 'location.Name',
                    'label'=> Yii::t('app','Lokasi Ruang'),
                    'width'=> '15.52%',
                ],

                // 'location.locationLibrary.Name',
                ['attribute'=>'locationlocationLibraryName',
                    'value'=> 'location.locationLibrary.Name',
                    'label'=> Yii::t('app','Lokasi Perpustakaan'),
                    'width'=> '15.52%',
                ],

                [
                    'attribute' => 'TujuanKunjungan',
                    'label'=>Yii::t('app', 'Tujuan Kunjungan'),
                    'value' => 'tujuanKunjungan.TujuanKunjungan',
                ],
                // ['attribute'=>'Profesi_id','value'=> function($model){
                //     if ($model->NoAnggota == null) {
                //         $jenisID = MasterPekerjaan::findOne($model->Profesi_id);
                //         return $jenisID['Pekerjaan'];
                //     } else {
                //         $members = Members::findOne(['MemberNo'=>$model->NoAnggota]);
                //         $jenisID = MasterPekerjaan::findOne($members->Job_id);
                //         return $jenisID['Pekerjaan'];
                //     }
                // },'label'=>Yii::t('app','Profesi')],


                // // 'PendidikanTerakhir_id',
                // ['attribute'=>'PendidikanTerakhir_id','value'=> function($model){
                //     if ($model->NoAnggota == null) {
                //         $jenisID = MasterPekerjaan::findOne($model->Profesi_id);
                //         return $jenisID['Pekerjaan'];
                //     } else {
                //         $members = Members::findOne(['MemberNo'=>$model->NoAnggota]);
                //         $jenisID = MasterPekerjaan::findOne($members->Job_id);
                //         return $jenisID['Pekerjaan'];
                //     }
                // },'label'=>Yii::t('app','Pendidikan Terakhir')],


                // 'JenisKelamin_id',
                // [
                //     'attribute'=>'Worksheet_id',
                //     'value'=>'worksheet.Name',
                // ],

                // 'CreateDate',
                // 'Status_id',
                // 'MasaBerlaku_id',
                //            'Deskripsi',
                //            'LOCATIONLOANS_ID',
                //            'Location_Id',
                //            'TujuanKunjungan_Id',
                //            'Information',
                //            'NoPengunjung',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions'=>['style'=>'max-width: 90px;'],
                    'template' => '{delete} ',
                    'buttons' => [
                    // 'update' => function ($url, $model) {
                    //                                 return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app','Update'), Yii::$app->urlManager->createUrl(['memberguesses/update','id' => $model->ID,'edit'=>'t']), [
                    //                                                 'title' => Yii::t('app', 'Edit'),
                    //                                                 'class' => 'btn btn-primary btn-sm'
                    //                                               ]);},

                    'delete' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app','Delete'), Yii::$app->urlManager->createUrl(['setting/checkpoint/memberguesses/delete','id' => $model->ID,'edit'=>'t']), [
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
