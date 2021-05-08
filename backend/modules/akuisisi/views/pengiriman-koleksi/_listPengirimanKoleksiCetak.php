<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;

$datacheckbox = array(
            'CETAK'=>yii::t('app','Cetak Pengiriman Data Terpilih'),
            // 'CETAKALL'=>yii::t('app','Cetak Semua Pengiriman')
        );

?>
<!-- <div class="collections-index"> -->
    <?php
      
     Pjax::begin(['id' => 'myGridviewListColl']); 
     echo GridView::widget([
        'id'=>'myGridListColl',
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
        //'filterModel' => $searchModel,
        'columns' => [
            [
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
            ],
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'NOBARCODE', 
                'label'=>Yii::t('app', 'Nomor Barcode'),
            ],
            [
                'attribute'=>'JUDUL', 
                'label'=>Yii::t('app', 'Judul'),
            ],
            [
                'attribute'=>'TAHUNTERBIT', 
                'label'=>Yii::t('app', 'Tahun Terbit'),
            ],
            [
                'attribute'=>'NOINDUK', 
                'label'=>Yii::t('app', 'No Induk'),
            ],
            // [
            //     'attribute'=>'QUANTITY', 
            //     'label'=>Yii::t('app', 'Eks'),
            // ],
            // [
            //     'attribute'=>'TANGGALKIRIM',
            //     'label'=>Yii::t('app', 'Tanggal Kirim'),
            //     'format' => 'date',
            // ],
            
            

            [
                'class' => 'yii\grid\ActionColumn',
                /*'contentOptions'=>['style'=>'width: 250px;'],*/
                'template' => '<div class="btn-group-vertical">{delete}</div>',
                'buttons' => [                       
                'delete' => function ($url, $model) {

                                    return 
                                    Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete'), Yii::$app->urlManager->createUrl(['akuisisi/pengiriman-koleksi/delete-pengiriman','id' => $model->ID,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Delete'),
                                                    //'data-toggle' => 'tooltip',
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'data' => [
                                                        'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                                                        'method' => 'post',
                                                    ],
                                                  ]);
                            },

                ],
            ],
        ],
        //'summary'=>'',
        'responsive'=>true,
        'containerOptions'=>['style'=>'font-size:12px'],
        'hover'=>true,
        'condensed'=>true,
        'panel' => [
            'heading'=>'
                <div class="col-md-1">
                    <h6 class="panel-title"> Aksi </h6>
                </div>
                <div class="col-md-3">
                    '.Select2::widget([
                        'id' => 'cbActioncheckbox',
                        'name' => 'cbActioncheckbox',
                        'data' => $datacheckbox,
                        'size'=>'sm',
                        
                    ]).'

                    
                </div>
                <div id="actionDropdown"></div>
                <div class="col-md-1">
                    '.Html::button('<i class="glyphicon glyphicon-check"></i> '.yii::t('app','Proses'), [
                        'id'=>'btnCheckprocess',
                        'class' => 'btn btn-primary btn-sm', 
                        'title' => 'Proses', 
                        //'data-toggle' => 'tooltip'
                    ]).'
                </div>

                ',
            'type'=>'info',
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>
<!-- </div> -->

<?php 

    $this->registerJs(' 

    $(document).ready(function(){
    

    $(\'#btnCheckprocess\').click(function(){
        
        var CekAction = $(\'#cbActioncheckbox\').val();
        var judulCetak = $(\'#judulCetak\').val();
        var penanggungjawab = $(\'#penanggungjawab\').val();
        var nip = $(\'#nip\').val();
        var from_date = $(\'#periode\').val();
        var to_date = $(\'#periode-2\').val();
        // alert(CekAction)
        var ids = $(\'#myGridListColl\').yiiGridView(\'getSelectedRows\');
        if(ids.length == 0){
            alertSwal(\'Harap pilih data katalog.\',\'error\',\'2000\');
            return;
        }

        if(CekAction === \'CETAK\')
        {
            var arrayId = {ids} 
            var isian = {judulCetak, penanggungjawab, nip, from_date, to_date}
            var isians = jQuery.param(isian);
            var ids = jQuery.param(arrayId);
            
            var url =  $(\'#hdnUrlProsesCetak\').val();
            

            window.open(
              url+\'?\'+isians+\'&\'+ids,
              \'_blank\'
            );

            alertSwal("Data berhasil diunggah!", "success","2000");
            $.pjax.reload({container:"#myGridListColl", async:false});
            // window.location.href = url+\'?\'+isians+\'&\'+ids;
        }

        
        

    });
    });', \yii\web\View::POS_READY);

?>