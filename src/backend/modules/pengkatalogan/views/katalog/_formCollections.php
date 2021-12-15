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
            'OPAC1'=>yii::t('app','Tampil di OPAC'),
            'OPAC0'=>yii::t('app','Jangan tampil di OPAC'),
            'MEDIA'=>yii::t('app','Pilih Bentuk Fisik'),
            'SUMBER'=>yii::t('app','Pilih Sumber'),
            'KATEGORI'=>yii::t('app','Pilih Kategori'),
            'AKSES'=>yii::t('app','Pilih Akses'),
            'STATUS'=>yii::t('app','Pilih Ketersediaan'),
            'LOKASI'=>yii::t('app','Pilih Lokasi Perpustakaan dan Ruang'),
            //'LOKASI'=>'Pilih Lokasi Ruang',
            'CETAKLABEL'=>yii::t('app','Cetak Label'),
            'KERANJANG1'=>yii::t('app','Masukan ke Keranjang Koleksi'),
            'KARANTINA'=>yii::t('app','Karantina Data'));
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

#rekanan-modal-catcoll .modal-dialog {  
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
echo Html::a(Yii::t('app', 'Tambah Eksemplar'), 'javascript:void(0)', ['id'=>'btnAddCollection','class' => 'btn btn-success btn-sm']);
?><br><br>
        <?php  echo $this->render('_searchAdvancedColl', [
            'model' => $searchModelColl,
            'rules' => $rulesColl,
            'rda'=>$rda,
            'for'=>$for,
            'id'=>$id,
            'edit'=>$edit,
            'refer'=>\common\components\CatalogHelpers::encrypt_decrypt('encrypt',$referrerUrl)]); ?>
        <br>
        <?php 
        echo '<div class=row><div class=col-sm-1>'.yii::t('app','Aksi').'</div><div class=col-sm-3>'
                        .Select2::widget([
                        'id' => 'cbActioncheckbox',
                        'name' => 'cbActioncheckbox',
                        'data' => $datacheckbox,
                        'size'=>'sm',
                        'pluginEvents' => [
                            "select2:select" => 'function() { 
                                var id = $("#cbActioncheckbox").val();
                                isLoading=true;
                                 $.ajax({
                                    type     :"POST",
                                    cache    : false,
                                    url  : "'.Yii::$app->urlManager->createUrl(["akuisisi/koleksi/get-dropdown"]).'?id="+id,
                                    success  : function(response) {
                                        $("#actionDropdown").html(response);
                                    }
                                });
                            }',
                        ]
                    ])
                        .'</div><div id=actionDropdown></div><div class=col-sm-1>'
                        .Html::button('<i class="glyphicon glyphicon-check"></i> Proses', [
                            'id'=>'btnCheckprocess',
                            'class' => 'btn btn-primary btn-sm', 
                            'title' => 'Proses', 
                            //'data-toggle' => 'tooltip'
                        ])
                        .'</div></div>';
        ?>
        <br>
        <div id="checkError"></div>
        <?php Pjax::begin(['id' => 'myGridviewColl']); echo GridView::widget([
        'id' => 'GridviewColl',
        'dataProvider' => $dataProviderColl,
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
        'filterSelector' => 'select[name="per-page"]',
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class'       => '\kartik\grid\CheckboxColumn',
                'pageSummary' => true,
                'rowSelectedClass' => GridView::TYPE_INFO,
                'name' => 'cek',
                'checkboxOptions' => function ($searchModelColl, $key, $index, $column) {
                    return [
                        'value' => $searchModelColl->ID
                    ];
                },
                'vAlign' => GridView::ALIGN_TOP
            ],
            ['class' => 'yii\grid\SerialColumn'],
            [
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
                                    FormCollection(id,$("#hdnCatalogId").val());
                                '
                                ]); 
                         }
            ],
            //'RFID',
            [
                'attribute'=>'TanggalPengadaan',
                'format' => 'date',
            ],
            [
                'attribute'=>'NoInduk',
                'label' => yii::t('app','No Induk'),
            ],
            [
                'attribute'=>'DataBib',
                //'value'=>'source.Name',
                'format' => 'raw',
            ],
            [
                'attribute'=>'Media_id',
                'value'=>'media.Name',
            ],
            [
                'attribute'=>'Source_id',
                'value'=>'source.Name',
            ],
            [
                'attribute'=>'Category_id',
                'value'=>'category.Name',
            ],
            [
                'attribute'=>'Rule_id',
                'value'=>'rule.Name',
            ],
            [
                'attribute'=>'Status_id',
                'value'=>'status.Name',
            ],
            [
                'attribute'=>'Location_Library_id',
                'value'=>'locationLibrary.Name',
            ],
            [
                'attribute'=>'Location_id',
                'value'=>'location.Name',
            ],
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'ISOPAC', 
                'vAlign'=>'top'
            ],
        ],
        //'summary'=>'',
        'responsive'=>true,
        'containerOptions'=>['style'=>'font-size:12px'],
        'hover'=>true,
        'condensed'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '. yii::t('app','Data Koleksi').'</h3>',
            'type'=>'info',
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>
        
    </div>
<?php
Modal::begin(['id' => 'collection-modal']);
echo "<div id='modalCollection'></div>";
Modal::end();

Modal::begin(['id' => 'rekanan-modal-catcoll']);
echo "<div id='modalPartnersCatColl'></div>";
Modal::end();
?>
<input type="hidden" id="hdnAjaxUrlFormCollectionSave" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/save-catalogs-collection"])?>">
<input type="hidden" id="hdnAjaxUrlFormCollection" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/bind-catalogs-collection"])?>">
<input type="hidden" id="hdnUrlProsesCetakLabel" value="<?=Yii::$app->urlManager->createUrl(["akuisisi/koleksi/cetak-label-proses"])?>">
<?php 
    $this->registerJsFile( 
        Yii::$app->request->baseUrl.'/assets_b/js/catalogs_coll.js'
    );

    $this->registerJs(' 
    $(document).ready(function(){
        $(\'#btnCheckprocess\').click(function(){
            var CekAction = $(\'#cbActioncheckbox\').val();
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
                      title: "'.yii::t('app','Apakah anda yakin?').'",   
                      text: "'.yii::t('app','akan memindahkan data ini ke karantina').'",   
                      showCancelButton: true,   
                      closeOnConfirm: false,   
                      showLoaderOnConfirm: true,
                      confirmButtonColor: "#DD6B55",   
                      confirmButtonText: "'.yii::t('app','OK, Karantinakan!').'",
                      cancelButtonText: "'.yii::t('app','Tidak').'",
                    }, 
                    function(){ 
                        $.ajax({
                            type: \'POST\',
                            url : "'.Yii::$app->urlManager->createUrl(["akuisisi/koleksi/checkbox-process"]).'",
                            data : {row_id: CekId, action: CekAction, actionid : CekActionDetail},
                            success : function(response) {
                              $(\'#checkError\').html(response);
                              $.pjax.reload({container:"#myGridviewColl",async:false});  //Reload GridView
                              alertSwal("'.yii::t('app','Data berhasil dihapus!').'", "success","2000");
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


