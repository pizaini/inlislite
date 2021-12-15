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
echo Html::a(Yii::t('app', 'Tambah Edisi Serial'), 'javascript:void(0)', ['id'=>'btnEdisiSerial','class' => 'btn btn-success btn-sm']);

// echo '<pre>';print_r($searchModelEdisiSerial);echo'</pre>';
// echo '<pre>asdd';echo'</pre>';


?><br><br>

        <br>
        <br>
        <div id="checkError"></div>
        <?php Pjax::begin(['id' => 'myGridviewArticle']); echo GridView::widget([
        'id' => 'GridviewArticles',
        'dataProvider' => $dataProviderEdisiSerial,
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
            
            ['class' => 'yii\grid\SerialColumn'],
            
            // [
            //     'format'=>'raw',
            //     'attribute'=>'no_edisi_serial',
            //     'value' => function($data){


            //         return Html::a($data->no_edisi_serial, 'javascript:void(0)', [
            //             'title' => $data->no_edisi_serial,
            //             'onclick' => '
            //                         var id = $(this).closest("tr").data("key");
            //                         FormEdisiSerial(id,$("#hdnCatalogId").val());
            //                     '
            //         ]);
            //     }
            // ],
            'no_edisi_serial',

            [
                //'label'=>'Nama',
                'format'=>'raw',
                'attribute'=>'tgl_edisi_serial',
                'value' => function($data){
                    $date=date_create($data->tgl_edisi_serial);
                    return date_format($date,"d F Y");
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'width: 105px;'],
                'template' => '<div class="btn-group-vertical"> {delete} </div>',
                'buttons' => [
                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete'), Yii::$app->urlManager->createUrl(['pengkatalogan/katalog/delete-edisi-serial','id' => $model->id,'edit'=>'t','tab'=>'eSerial']), [
                                                    'title' => Yii::t('app', 'Delete'),
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'data' => [
                                                        'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                                                        'method' => 'post',
                                                    ],
                                                  ]);},


                ],

            ],

            // 'tgl_edisi_serial',
            //'Title',
            
            
        ],
        //'summary'=>'',
        'responsive'=>true,
        'containerOptions'=>['style'=>'font-size:12px'],
        'hover'=>true,
        'condensed'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '. yii::t('app','Edisi Serial').'</h3>',
            'type'=>'info',
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

    </div>
<?php
Modal::begin(['id' => 'edisi_serial-modal']);
echo "<div id='modalEdisiSerial'></div>";
Modal::end();

?>
<input type="hidden" id="hdnAjaxUrlEdisiSerial" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/bind-edisi-serial"])?>">

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


