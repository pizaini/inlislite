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

$data = Yii::$app->db->createCommand("
SELECT sa.id, sa.`Title` AS JudulArtikel,
sa.`Title` AS judul
-- CONCAT(CONCAT(sa.`Title`,' - '),cat.`Title`) AS judul
FROM serial_articles sa 

ORDER BY judul ASC;")->queryAll();
$datas;
if ($data){
    foreach ($data as $key => $value){
        $datas[$value['id']]= $value['judul'];
    }
}

$this->title = 'Unggah Konten Digital Artikel';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengkatalogan'), 'url' => Url::to(['/pengkatalogan'])];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="col-sm-12">
        <div class="box-group" id="accordion">
            <!-- <div class="panel panel-default"> -->
                <div class="box-header with-border">
                    <div class="col-xs-12 col-sm-12" >
                        <h4 class="box-title">
                            <?php
                            echo '<p>';
                            echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-arrow-left"></span> '. Yii::t('app', 'Kembali'), ['index'], ['class' => 'btn btn-warning']);
                            echo '</p>';
                            ?>
                        </h4>
                    </div>
                </div>
                <div id="collapseFile" class="panel-collapse collapse in">
                    <div class="box-body">
                        <div class="form-group kv-fieldset-inline">
                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="email">Pilih Judul Artikel</label>

                                </div>
                                <div class="col-sm-8">
                                    <?=  Select2::widget([
                                        'model' => $model,
                                        'attribute' => 'Articles_id',
                                        'data' => $datas,
                                        'options' => [
                                            'id' => 'Articles_id',
                                            'placeholder' => 'Pilih Artikel ...',
                                            'required => true'],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]); ?>
                                </div>
                                <div class="col-sm-2"></div>
                            </div>
                        </div>
                        <div class="row"></div>
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
                                <?php echo Html::activeCheckbox($model,'isCompress',['id'=>'serialarticlefiles-iscompress']); ?>
                            </div>
                        </div>
                        <div class="form-group kv-fieldset-inline">
                            <div class="col-sm-12">
                                <?php Pjax::begin(['id' => 'uploadFileUI']); ?>
                                <?= FileUploadUI::widget([
                                    'model' => $model,
                                    'attribute' => 'file',
                                    'url' => ['upload-konten-digital-artikel'],
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
                                                            alertSwal("Data berhasil diunggah!", "success","2000");
                                                        }',
                                        'fileuploadfail' => 'function(e, data) {
                                                            console.log(e);
                                                            console.log(data);
                                                        }',
                                        'fileuploadstop' => 'function(e, data) {
                                                            $("#serialarticlefiles-fileexecutable").val("");
                                                            // $.pjax.reload({container:"#myGridviewKontenDigitalArticle", async:false});
                                                            alertSwal("Data berhasil diunggah!", "success","2000");
                                                        }',
                                    ],
                                    'uploadTemplateView'=> "../../../../../backend/modules/pengkatalogan/views/katalog/_templateUpload",
                                    'downloadTemplateView'=> "../../../../../backend/modules/pengkatalogan/views/katalog/_templateDownload",
                                    'formView'=> "../../../../../backend/modules/pengkatalogan/views/katalog/_templateForm"
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
                                            Maks. Ukuran File
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
            <!-- </div> -->
        </div>
        <div id="msgKontenDigital"></div>

        <br>
        <div id="checkError"></div>
       


    </div>



    <script type="text/javascript">
        $("#serialarticlefiles-fileexecutable").focus(function() {
            $("#msgKontenDigital").html("");
        });
        $('#serialarticlefiles-file-fileupload').bind('fileuploadsubmit', function (e, data) {
            // The example input, doesn't have to be part of the upload form:
            var fileexecutable = $('#serialarticlefiles-fileexecutable');
            var iscompress = $('#serialarticlefiles-iscompress:checkbox:checked');
            var artikelID = $('#Articles_id').val();
            data.formData = {fileExecutable: fileexecutable.val(),isCompress: iscompress.length, artikelID : artikelID};
        });


    </script>

<?php
$this->registerJs(' 
    $(document).ready(function(){
        $(\'#btnCheckprocessKontenDigitalArticle\').click(function(){
            var CekAction = $(\'#cbActioncheckboxKontenDigitalArticle\').val();
            var CekActionDetail = $(\'#cbActionDetailKontenDigital\').val();


            var val = [];
            $(\'#test:checked\').each(function(i){
              val[i] = $(this).val();
                // alert(val[i]);
            });
            var CekId = val;
            if(CekId.length == 0){
                alertSwal("'.yii::t('app','Harap pilih konten digital.').'",\'error\',\'2000\');
                return;
            }
            if (CekAction === \'REMOVE\')
            {
                swal(
                {   
                  title: "'.yii::t('app','Apakah anda yakin?').'",  
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
                      url : "'.Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/checkbox-process-konten-digital-article"]).'",
                      data : {row_id: CekId, action: CekAction, actionid : CekActionDetail},
                      success : function(response) {
                          $.pjax.reload({container:"#GridArtikelKontenDigitals", async:false});
                          alertSwal("'.yii::t('app','Data berhasil dihapus!').'", "success","2000");
                      },
                  });
                });
                
            }else{
              isLoading=true;
              $.ajax({
                      type: \'POST\',
                      url : "'.Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/checkbox-process-konten-digital-article"]).'",
                      data : {row_id: CekId, action: CekAction, actionid : CekActionDetail},
                      success : function(response) {
                          if (CekAction === \'DOWNLOAD\')
                          {
                            window.location=response;
                          }
                          $.pjax.reload({container:"#GridArtikelKontenDigitals", async:false});
                          alertSwal("'.yii::t('app','Data berhasil diproses!').'", "success","2000");
                      }
                  });
            }
        });
    });', \yii\web\View::POS_READY);

?>