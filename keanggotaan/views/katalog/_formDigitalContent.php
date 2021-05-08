<?php
use yii\helpers\Html;
use dosamigos\fileupload\FileUploadUI;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\components\DirectoryHelpers;
use kartik\widgets\Select2;
/**
 * @var yii\web\View $this
 * @var common\models\Members $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="col-sm-12">
  <div class="box-group" id="accordion">
    <div class="panel panel-default">
      <div class="box-header with-border">
            <div class="col-xs-12 col-sm-12" >
                  <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseFile">
                        <?= yii::t('app','Unggah Konten Digital')?>
                      </a>
                  </h4>
            </div>
      </div>
      <div id="collapseFile" class="panel-collapse collapse in">
        <div class="box-body">
              <div class="form-group kv-fieldset-inline">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($model,'fileExecutable'); ?></label>
                        <div class="col-sm-3">
                          <?php echo Html::activeTextInput($model,'fileExecutable',['class'=>'form-control','style'=>'max-width:100%']); ?>
                        </div>
                      </div>
              </div>
              <div class="form-group kv-fieldset-inline">
                <div class="col-sm-12">
                          <?php echo Html::activeCheckbox($model,'isCompress',['id'=>'catalogfiles-iscompress']); ?>
                   </div>
              </div>
              <div class="form-group kv-fieldset-inline">
                <div class="col-sm-12">
                         <?php Pjax::begin(['id' => 'uploadFileUI']); ?>
                         <?= FileUploadUI::widget([
                            'model' => $model,
                            'attribute' => 'file',
                            'url' => ['upload-konten-digital', 'id' => $id],
                            'clientOptions' => [
                                'maxFileSize' => DirectoryHelpers::GetBytes(ini_get('upload_max_filesize')),
                                'acceptFileTypes'=>new yii\web\JsExpression('/(.*)(zip|[^(php|html)])$/i'),
                                // 'acceptFileTypes'=>new yii\web\JsExpression('/(\.|\/)(gif|jpe?g|png|rar|zip|mp3|mp4|txt|pdf|doc|docs|xls|xlsx)$/i'),
                                'messages' => [
                                  'maxFileSize' => Yii::t('app', 'File exceeds maximum allowed size of ').ini_get('upload_max_filesize').'B',
                                ]
                            ],
                            'clientEvents' => [
                                    'fileuploaddone' => 'function(e, data) {
                                                            console.log(e);
                                                            console.log(data);
                                                        }',
                                    'fileuploadfail' => 'function(e, data) {
                                                            console.log(e);
                                                            console.log(data);
                                                        }',
                                    'fileuploadstop' => 'function(e, data) {
                                                            $("#catalogfiles-fileexecutable").val("");
                                                            $.pjax.reload({container:"#myGridviewKontenDigital", async:false});
                                                            alertSwal("Data berhasil diunggah!", "success","2000");
                                                        }',
                            ],
                            'uploadTemplateView'=> "../../../../../keanggotaan/views/katalog/_templateUpload",
                            'downloadTemplateView'=> "../../../../../keanggotaan/views/katalog/_templateDownload",
                            'formView'=> "../../../../../keanggotaan/views/katalog/_templateForm"
                        ]);
                        ?>

                        <?php Pjax::end(); ?>
                   </div>
              </div>
              <div class="form-group kv-fieldset-inline">
                        <div class="col-sm-12">
                          <table class="InfoTable" cellpadding="0" cellspacing="0" border="0" style="background-color: #FFFFCC; width: 100%;">
                            <tbody style="font-size: 10px"><tr>
                                <td colspan="3"  style="padding: 5px">
                                    <b><?= yii::t('app','Petunjuk')?></b>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 100px;padding: 5px; padding-bottom: 0px"  >
                                    <?= yii::t('app','Jenis File')?>
                                </td>
                                <td style="width: 3px;padding: 5px; padding-bottom: 0px" >&nbsp;:&nbsp;</td>
                                <td  style="padding: 5px; padding-bottom: 0px">
                                    <?= yii::t('app','Semua File')?>
                                </td>
                            </tr>
                            <tr>
                                <td  style="padding: 5px; padding-bottom: 0px">
                                    <?= yii::t('app','Maks. Ukuran File')?>
                                </td  style="padding: 5px; padding-bottom: 0px">
                                <td style="width: 3px;padding: 5px; padding-bottom: 0px">&nbsp;:&nbsp;</td>
                                <td  style="padding: 5px; padding-bottom: 0px">
                                    <span id="ContentPlaceHolder1_lbMaksFileSize"><?=ini_get('upload_max_filesize')?>B</span>
                                </td>
                            </tr>
                            <tr>
                                <td  style="padding: 5px">
                                    <?= yii::t('app','Keterangan')?>
                                </td>
                                <td style="width: 3px;padding: 5px">&nbsp;:&nbsp;</td>
                                <td  style="padding: 5px">
                                    <?= yii::t('app','Untuk mengunggah Flipbook dalam bentuk flash. Lakukan kompresi dalam bentuk file zip/rar.')?>
                                </td>
                            </tr>
                        </tbody></table>
                        </div>
              </div>
        </div>
      </div>
    </div>
  </div>
<div id="msgKontenDigital"></div>

<?php 
echo '<div style=width:70% class=row><div class=col-sm-1>'.yii::t('app','Aksi').'</div><div class=col-sm-3>'
                        .Select2::widget([
                        'id' => 'cbActioncheckboxKontenDigital',
                        'name' => 'cbActioncheckboxKontenDigital',
                        'data' => ['DOWNLOAD'=>yii::t('app','Download File'),'OPAC'=>yii::t('app','Tampil di OPAC'),'REMOVE'=>yii::t('app','Hapus File')],
                        'size'=>'sm',
                        'pluginEvents' => [
                            "select2:select" => 'function() { 
                                var id = $("#cbActioncheckboxKontenDigital").val();
                                 isLoading=true;
                                 $.ajax({
                                    type     :"POST",
                                    cache    : false,
                                    url  : "'.Yii::$app->urlManager->createUrl(["katalog/get-dropdown-konten-digital"]).'?id="+id,
                                    success  : function(response) {
                                        $("#actionDropdownKontenDigital").html(response);
                                    }
                                });
                            }',
                        ]
                    ])
                        .'</div><div id=actionDropdownKontenDigital></div><div class=col-sm-1>'
                        .Html::button('<i class="glyphicon glyphicon-check"></i> Proses', [
                            'id'=>'btnCheckprocessKontenDigital',
                            'class' => 'btn btn-primary btn-sm', 
                            'title' => 'Proses', 
                            //'data-toggle' => 'tooltip'
                        ])
                        .'</div></div><br>';

Pjax::begin(['id' => 'myGridviewKontenDigital','linkSelector'=>false]); 
       echo GridView::widget([
        'id'=>'GridKontenDigital',
        'pjax'=>false,
        'dataProvider' => $dataProviderKontenDigital,
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
        // 'filterSelector' => 'select[name="per-page"]',
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class'       => '\kartik\grid\CheckboxColumn',
                'pageSummary' => true,
                'rowSelectedClass' => GridView::TYPE_INFO,
                'name' => 'cek',
                'checkboxOptions' => function ($searchModelKontenDigital, $key, $index, $column) {
                    return [
                        'value' => $searchModelKontenDigital->ID
                    ];
                },
                'vAlign' => GridView::ALIGN_TOP
            ],
            ['class' => 'yii\grid\SerialColumn'],
            [
               //'label'=>'Nama',
               'format'=>'raw',
               'attribute'=>'FileURL',
               'value' => function($data){
                  $modelcat = \common\models\Catalogs::findOne($data->Catalog_id);
                   $worksheetDir=DirectoryHelpers::GetDirWorksheet($modelcat->Worksheet_id);
                   $url = '../../uploaded_files/dokumen_isi/'.$worksheetDir.'/'.'/'.$data->FileURL;
                   return Html::a($data->FileURL, $url, ['title' => $data->FileURL,'target' => '_blank']); 
               }
            ],
            [
               //'label'=>'Nama',
               'format'=>'raw',
               'attribute'=>'FileFlash',
               'value' => function($data){
                   $fileflash= str_replace(".rar","",str_replace(".zip","",$data->FileURL)).'/'.$data->FileFlash;
                  $modelcat = \common\models\Catalogs::findOne($data->Catalog_id);
                   $worksheetDir=DirectoryHelpers::GetDirWorksheet($modelcat->Worksheet_id);
                   $url = '../../uploaded_files/dokumen_isi/'.$worksheetDir.'/'.$fileflash;
                   if($data->FileFlash)
                   {
                      return Html::a($fileflash, $url, ['title' =>  $fileflash,'target' => '_blank']); 
                   }else{
                      return null;
                   }
               }
            ],
            //'FileFlash',
            'createBy.Fullname',
            [
                'attribute'=>'CreateDate',
                'format' => 'date',
            ],
            [
                'attribute'=>'IsPublish',
                'format' => 'raw',
                'value'=>function($data){
                            if($data->IsPublish == 1){
                                 return '<span class="label label-success">Publik</span>';
                            }else if($data->IsPublish == 2){
                                 return '<span class="label label-primary">Hanya untuk anggota</span>';
                            }elseif($data->IsPublish == 0){
                                 return '<span class="label label-warning">Tidak dipublikasikan</span>';
                            }else{
                                 return '<span class="label label-default">Tidak diketahui</span>';
                            }


                         }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'width: 70px;'],
                'template' => '<div class="btn-group-vertical">{delete}</div>',
                'buttons' => [
                    'delete' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete'), 'javascript:void(0)', [
                                                        'title' => Yii::t('app', 'Delete'),
                                                        //'data-toggle' => 'tooltip',
                                                        'class' => 'btn btn-danger btn-sm',
                                                        'onclick'=> '
                                                          swal(
                                                            {   
                                                              title: "'.yii::t('app','Apakah anda yakin?').'",  
                                                              text: "'.yii::t('app','berkas akan terhapus secara permanen').'",     
                                                              showCancelButton: true,   
                                                              confirmButtonColor: "#DD6B55",   
                                                              confirmButtonText: "'.yii::t('app','OK, Hapus!').'",
                                                              showLoaderOnConfirm: true,   
                                                              closeOnConfirm: false 
                                                            }, 
                                                            function(){   
                                                              $.ajax({
                                                                  type: \'POST\',
                                                                  url : "'.Yii::$app->urlManager->createUrl(["katalog/delete-konten-digital?id=".$model->ID]).'",
                                                                  success : function(response) {
                                                                      $.pjax.reload({container:"#myGridviewKontenDigital", async:false});
                                                                      if(response==true)
                                                                      {
                                                                        alertSwal("'.yii::t('app','Data berhasil dihapus!').'", "success","2000");
                                                                      }else{
                                                                        alertSwal("'.yii::t('app','Data gagal dihapus!').'", "error","2000");
                                                                      }
                                                                  },
                                                              });
                                                            });
                                                        '
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
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.yii::t('app','Daftar Konten Digital').' </h3>',
            'type'=>'info',
            'before'=>
            '',
            //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> ', ['create'], ['class' => 'btn btn-success','title' => Yii::t('app','Add'),'data-toggle' => 'tooltip',]),
            //'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['reset-konten-digital?id='.$id.'&isreset=1'], ['class' => 'btn btn-info']),
            'showFooter'=>false,
            'showHeader'=>false
        ],
    ]); Pjax::end();
     ?>
</div>



<script type="text/javascript">
  $("#catalogfiles-fileexecutable").focus(function() {
    $("#msgKontenDigital").html("");
  });
  $('#catalogfiles-file-fileupload').bind('fileuploadsubmit', function (e, data) {
    // The example input, doesn't have to be part of the upload form:
    var fileexecutable = $('#catalogfiles-fileexecutable');
    var iscompress = $('#catalogfiles-iscompress:checkbox:checked');
    data.formData = {fileExecutable: fileexecutable.val(),isCompress: iscompress.length};
});

  
</script>

<?php 
    $this->registerJs(' 
    $(document).ready(function(){
        $(\'#btnCheckprocessKontenDigital\').click(function(){
            var CekAction = $(\'#cbActioncheckboxKontenDigital\').val();
            var CekActionDetail = $(\'#cbActionDetailKontenDigital\').val();
            var CekId = $(\'#GridKontenDigital\').yiiGridView(\'getSelectedRows\');
            if(CekId.length == 0){
                alertSwal("'.yii::t('app','Harap pilih konten digital.').'",\'error\',\'2000\');
                return;
            }
            if (CekAction === \'REMOVE\')
            {
                swal(
                {   
                  title: "Apakah anda yakin?",   
                  text: "'.yii::t('app','berkas akan terhapus secara permanen').'",   
                  showCancelButton: true,   
                  closeOnConfirm: false,   
                  showLoaderOnConfirm: true,
                  confirmButtonColor: "#DD6B55",   
                  confirmButtonText: "'.yii::t('app','OK, Hapus!').'", 
                }, 
                function(){   
                  $.ajax({
                      type: \'POST\',
                      url : "'.Yii::$app->urlManager->createUrl(["katalog/checkbox-process-konten-digital"]).'",
                      data : {row_id: CekId, action: CekAction, actionid : CekActionDetail},
                      success : function(response) {
                          $.pjax.reload({container:"#myGridviewKontenDigital", async:false});
                          alertSwal("'.yii::t('app','Data berhasil dihapus!').'", "success","2000");
                      },
                  });
                });
                
            }else{
              isLoading=true;
              $.ajax({
                      type: \'POST\',
                      url : "'.Yii::$app->urlManager->createUrl(["katalog/checkbox-process-konten-digital"]).'",
                      data : {row_id: CekId, action: CekAction, actionid : CekActionDetail},
                      success : function(response) {
                          if (CekAction === \'DOWNLOAD\')
                          {
                            window.location=response;
                          }
                          $.pjax.reload({container:"#myGridviewKontenDigital", async:false});
                          alertSwal("'.yii::t('app','Data berhasil diproses!').'", "success","2000");
                      }
                  });
            }
            
        });
    });', \yii\web\View::POS_READY);

?>