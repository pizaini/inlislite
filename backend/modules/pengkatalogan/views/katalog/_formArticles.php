<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\Select2;
use yii\bootstrap\Modal;
use kartik\select2\Select2Asset;

//handle for pjax reload on select2
Select2Asset::register($this);
/**
 * @var yii\web\View $this
 * @var common\models\Members $model
 * @var yii\widgets\ActiveForm $form
 */

$datacheckbox = array(
            'OPAC1'=>'Tampil di OPAC',
            'OPAC0'=>'Jangan tampil di OPAC',
            /*'MEDIA'=>'Pilih Bentuk Fisik',
            'SUMBER'=>'Pilih Sumber',
            'KATEGORI'=>'Pilih Kategori',
            'AKSES'=>'Pilih Akses',
            'STATUS'=>'Pilih Ketersediaan',
            'LOKASI'=>'Pilih Lokasi Perpustakaan dan Ruang',
            'CETAKLABEL'=>'Cetak Label',
            'KERANJANG1'=>'Masukan ke Keranjang Koleksi',
            'KARANTINA'=>'Karantina Data'*/);
?>
<style type="text/css">
.modal .modal-dialog {
    min-width: 65%;
   }
.modal .modal-body {
    height:auto;
    max-height:150%;
    overflow:auto;
}

#rekanan-modal-article .modal-dialog {
    min-width: 45%;
}
.standard-error-summary
{
background-color: #faffe1;
padding: 5px;
border:dashed 1px #cccccc;
margin-bottom: 10px;
font-size: 12px;
margin: 10px;
}
</style>
<div class="col-sm-12">
<?php
echo Html::a(Yii::t('app', 'Tambah Artikel'), 'javascript:void(0)', ['id'=>'btnAddArticles','class' => 'btn btn-success btn-sm']);


?><br><br>
        <?php  /*echo $this->render('_searchAdvancedArticle', [
            'model' => $searchModelArticles,
            'rules' => $rulesColl,
            'rda'=>$rda,
            'for'=>$for,
            'id'=>$id,
            'edit'=>$edit,
            'refer'=>\common\components\CatalogHelpers::encrypt_decrypt('encrypt',$referrerUrl)]);*/ ?>
        <br>
        <?php
        /*echo '<div class=row><div class=col-sm-1>Aksi</div><div class=col-sm-3>'
                        .Select2::widget([
                        'id' => 'cbActioncheckboxArticle',
                        'name' => 'cbActioncheckboxArticle',
                        'data' => $datacheckbox,
                        'size'=>'sm',
                        'pluginEvents' => [
                            "select2:select" => 'function() {
                                var id = $("#cbActioncheckboxArticle").val();
                                isLoading=true;
                                 $.ajax({
                                    type     :"POST",
                                    cache    : false,
                                    url  : "'.Yii::$app->urlManager->createUrl(["akuisisi/koleksi/get-dropdown"]).'?id="+id,
                                    success  : function(response) {
                                        $("#actionDropdownArticle").html(response);
                                    }
                                });
                            }',
                        ]
                    ])
                        .'</div><div id=actionDropdownArticle></div><div class=col-sm-1>'
                        .Html::button('<i class="glyphicon glyphicon-check"></i> Proses', [
                            'id'=>'btnCheckprocessArticle',
                            'class' => 'btn btn-primary btn-sm',
                            'title' => 'Proses',
                            //'data-toggle' => 'tooltip'
                        ])
                        .'</div></div>';*/
        ?>
        <br>
        <div id="checkError"></div>
        <?php Pjax::begin(['id' => 'myGridviewArticle']); echo GridView::widget([
        'id' => 'GridviewArticles',
        'dataProvider' => $dataProviderArticles,
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
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class'       => '\kartik\grid\CheckboxColumn',
                'pageSummary' => true,
                'rowSelectedClass' => GridView::TYPE_INFO,
                'name' => 'cek',
                'checkboxOptions' => function ($searchModelArticles, $key, $index, $column) {
                    return [
                        'value' => $searchModelArticles->id
                    ];
                },
                'vAlign' => GridView::ALIGN_TOP
            ],
            ['class' => 'yii\grid\SerialColumn'],
/*            [
                         //'label'=>'Nama',
                         'format'=>'raw',
                         'attribute'=>'NomorBarcode',
                         'value' => function($data) use ($for){
                             if($for=='karantina')
                             {
                                $url = Url::to(['viewkarantina','id'=>$data->ID,'edit'=>'t']);
                             }else{
                                $url = Url::to(['..\..\pengkatalogan\katalog\update','for' => 'coll','id'=>$data->ID,'edit'=>'t']);
                             }

                             return Html::a($data->NomorBarcode, 'javascript:void(0)', [
                                'title' => $data->NomorBarcode,
                                'onclick' => '
                                    var id = $(this).closest("tr").data("key");
                                    FormArticle(id,$("#hdnCatalogId").val());
                                '
                                ]);
                         }
            ],*/
            //'RFID',
            /*[
                'attribute'=>'Title',
                //'value'=>'source.Name',
                'format' => 'raw',
            ],*/
            [
                //'label'=>'Nama',
                'format'=>'raw',
                'attribute'=>'title',
                'value' => function($data){


                    return Html::a($data->Title, 'javascript:void(0)', [
                        'title' => $data->Title,
                        'onclick' => '
                                    var id = $(this).closest("tr").data("key");
                                    FormArticle(id,$("#hdnCatalogId").val());
                                '
                    ]);
                }
            ],
            // 'Article_type',
            //'Title',
            [
              'attribute' => 'Creator',
              'filter' => false,
              'format' => 'raw',
               'value' => function ($model) {
                $modelcat = Yii::$app->db->createCommand('select * from serial_articles_repeatable where serial_article_ID = '.$model->id.' and serial_articles_repeatable.article_field = "Kreator"')->queryAll();
            
                $test = array();
                foreach($modelcat as $value){
                    $test[] .= $value['value'];
                }

                   return  implode(',<br>',$test);
               },
            ],
            [
              'attribute' => 'Contributor',
              'filter' => false,
              'format' => 'raw',
               'value' => function ($model) {
                $modelcat = Yii::$app->db->createCommand('select * from serial_articles_repeatable where serial_article_ID = '.$model->id.' and serial_articles_repeatable.article_field = "Kontributor"')->queryAll();
                
                // $modelcat = \common\models\SerialArticlesRepeatable::find(['serial_articlce_ID'=>$model->id])->asArray()->All();
                $test = array();
                foreach($modelcat as $value){
                    $test[] .= $value['value'];
                }

                // echo '<pre>';print_r(implode(',<br>',$test));echo '</pre>';
                // echo $test;
                   return  implode(',<br>',$test);
               },
            ],
            // 'Contributor',
            'StartPage',
            'Pages',
            [
              'attribute' => 'Subject',
              'filter' => false,
              'format' => 'raw',
               'value' => function ($model) {
                $modelcat = Yii::$app->db->createCommand('select * from serial_articles_repeatable where serial_article_ID = '.$model->id.' and serial_articles_repeatable.article_field = "Subjek"')->queryAll();
            
                $test = array();
                foreach($modelcat as $value){
                    $test[] .= $value['value'];
                }
                
                   return  implode(',<br>',$test);
               },
            ],
            /*'DDC',
            'Call_Number',*/
            'EDISISERIAL',

            [
                //'label'=>'Nama',
                'format'=>'raw',
                'attribute'=>'TANGGAL_TERBIT_EDISI_SERIAL',
                'value' => function($data){
                    $date=date_create($data->TANGGAL_TERBIT_EDISI_SERIAL);
                    return date_format($date,"d F Y");
                }
            ],

            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'ISOPAC',
                'vAlign'=>'top',
                'label'=>yii::t('app','Tampilkan di OPAC')
            ],
            // 'Abstract',
            // [
            //     'class' => 'kartik\grid\ExpandRowColumn',
            //     'value' => function ($model, $key, $index, $column) {
            //         return GridView::ROW_COLLAPSED;
            //     },
            //     'expandTitle' => 'lihat konten digital',
            //     'collapseTitle' => 'tutup konten digital',
            //     'expandAllTitle' => 'lihat semua konten digital',
            //     'collapseAllTitle' => 'tutup semua konten digital',
            //     'detail' => function ($model, $key, $index) {
            //         $searchModel = new \common\models\SerialArticleFilesSearch();
            //         $params['ArticleID'] = $model->id;
            //         $dataProvider = $searchModel->search2($params);

            //         return Yii::$app->controller->renderPartial('_subEksemplarArticle', [
            //             'searchModel' => $searchModel,
            //             'dataProvider' => $dataProvider,
            //         ]);

            //     }
            // ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'width: 105px;'],
                'template' => '<div class="btn-group-vertical"> {delete} </div>',
                'buttons' => [
                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete'), Yii::$app->urlManager->createUrl(['pengkatalogan/katalog/delete-edisi-serial','id' => $model->id,'edit'=>'t','tab'=>'artikel']), [
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
        //'summary'=>'',
        'responsive'=>true,
        'containerOptions'=>['style'=>'font-size:12px'],
        'hover'=>true,
        'condensed'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '. yii::t('app','Data Artikel').'</h3>',
            'type'=>'info',
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

    </div>
<?php
Modal::begin(['id' => 'article-modal']);
echo "<div id='modalArticle'></div>";
Modal::end();

Modal::begin(['id' => 'rekanan-modal-catcoll']);
echo "<div id='modalPartnersCatColl'></div>";
Modal::end();

Modal::begin(['id' => 'KontenDigitalArticle-modal']);
echo "<div id='modalKontenDigitalArticle'></div>";
Modal::end();


?>
<input type="hidden" id="hdnAjaxUrlFormCollectionArticle" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/bind-catalogs-article"])?>">
<input type="hidden" id="hdnAjaxUrlFormDigitalArticle" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/bind-catalogs-digital-article"])?>">

<?php
    $this->registerJsFile(
        Yii::$app->request->baseUrl.'/assets_b/js/catalogs_article.js'
    );

    $this->registerJs(' 
    $(document).ready(function(){
        $(\'#btnCheckprocessArticle\').click(function(){
            var CekAction = $(\'#cbActioncheckboxArticle\').val();
            var CekActionDetail = $(\'#cbActionDetail\').val();
            var CekId = $(\'#GridviewColl\').yiiGridView(\'getSelectedRows\');
            if(CekId.length == 0){
                alertSwal(\'Harap pilih data koleksi.\',\'error\',\'2000\');
                return;
            }

            if(CekAction === \'CETAKLABEL\')
            {
                var arrayId = {CekId} 
                var ids = jQuery.param(arrayId);
                var url =  $(\'#hdnUrlProsesCetakLabel\').val();
                var sumber = $(\'input:radio[name ="cbActionLabel1"]:checked\').val();
                var model = $(\'#cbActionLabel3\').val();
                var format = $(\'#cbActionLabel4\').val();
                CekActionDetail = sumber+"|"+model+"|"+format;

                window.location.href = url+\'?actids=\'+CekActionDetail+\'&\'+ids;
            }else{
                isLoading=true;
                if (CekAction === \'KARANTINA\')
                {
                    swal(
                    {   
                      title: "Apakah anda yakin?",   
                      text: "akan memindahkan data ini ke karantina",   
                      showCancelButton: true,   
                      closeOnConfirm: false,   
                      showLoaderOnConfirm: true,
                      confirmButtonColor: "#DD6B55",   
                      confirmButtonText: "OK, Karantinakan!",
                      cancelButtonText: "Tidak",  
                    }, 
                    function(){ 
                        $.ajax({
                            type: \'POST\',
                            url : "'.Yii::$app->urlManager->createUrl(["akuisisi/koleksi/checkbox-process"]).'",
                            data : {row_id: CekId, action: CekAction, actionid : CekActionDetail},
                            success : function(response) {
                              $(\'#checkError\').html(response);
                              $.pjax.reload({container:"#myGridviewColl",async:false});  //Reload GridView
                              alertSwal("'.yii::t('app','Data terpilih berhasil diproses').'", "success","2000");
                            }
                        });
                    });  
                }else{
                    $.ajax({
                        type: \'POST\',
                        url : "'.Yii::$app->urlManager->createUrl(["akuisisi/koleksi/checkbox-process"]).'",
                        data : {row_id: CekId, action: CekAction, actionid : CekActionDetail},
                        success : function(response) {
                          $.pjax.reload({container:"#myGridviewColl",async:false});  //Reload GridView
                          alertSwal("'.yii::t('app','Data terpilih berhasil diproses').'", "success","2000");
                        }
                    });
                }
                
            }
        });
    });', \yii\web\View::POS_READY);

?>


