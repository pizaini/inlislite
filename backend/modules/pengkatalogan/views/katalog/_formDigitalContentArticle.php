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
SELECT sa.id, sa.`Title` AS JudulArtikel, cat.`Title` AS JudulKatalog,
sa.`Title` AS judul
-- CONCAT(CONCAT(sa.`Title`,' - '),cat.`Title`) AS judul
FROM serial_articles sa 
LEFT JOIN catalogs cat ON sa.`Catalog_id` = cat.`ID`
WHERE cat.`ID` =".$id." 
ORDER BY judul ASC;")->queryAll();
$datas;
if ($data){
    foreach ($data as $key => $value){
        $datas[$value['id']]= $value['judul'];
    }
}

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
                                <div class="col-sm-2">
                                    <label for="email"><?php echo Html::activeLabel($model,'Articles_id'); ?></label>

                                </div>
                                <div class="col-sm-8">
                                    <?=  Select2::widget([
                                        'model' => $model,
                                        'attribute' => 'Articles_id',
                                        'data' => $datas,
                                        'options' => [
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
                                    <?php echo Html::activeTextInput($model,'fileExecutable',['class'=>'form-control','style'=>'max-width:100%','placeholder'=>'namefile.formatfile']); ?>
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
                                    'url' => ['upload-konten-digital-artikel', 'id' => $id],
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
                                                            $.pjax.reload({container:"#myGridviewKontenDigital", async:false});
                                                            alertSwal("'.yii::t('app','Data berhasil diunggah!').'", "success","2000");
                                                        }',
                                    'fileuploadfail' => 'function(e, data) {
                                                            console.log(e);
                                                            console.log(data);
                                                            $.pjax.reload({container:"#myGridviewKontenDigital", async:false});
                                                            alertSwal("'.yii::t('app','Nama File Format Flash tidak boleh kosong!').'", "error","2000");
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
            </div>
        </div>
        <div id="msgKontenDigital"></div>

        <br>
        <div id="checkError"></div>
        <?php 
        echo '<div style=width:70% class=row><div class=col-sm-1>'.yii::t('app','Aksi').'</div><div class=col-sm-3>'
                        .Select2::widget([
                        'id' => 'cbActioncheckboxKontenDigitalArticle',
                        'name' => 'cbActioncheckboxKontenDigitalArticle',
                        'data' => ['DOWNLOAD'=>yii::t('app','Download File'),'OPAC'=>yii::t('app','Tampil di OPAC'),'REMOVE'=>yii::t('app','Hapus File')],
                        'size'=>'sm',
                        'pluginEvents' => [
                            "select2:select" => 'function() { 
                                var id = $("#cbActioncheckboxKontenDigitalArticle").val();
                                 isLoading=true;
                                 $.ajax({
                                    type     :"POST",
                                    cache    : false,
                                    url  : "'.Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/get-dropdown-konten-digital"]).'?id="+id,
                                    success  : function(response) {
                                        $("#actionDropdownKontenDigitalArticle").html(response);
                                    }
                                });
                            }',
                        ]
                    ])
                        .'</div><div id=actionDropdownKontenDigitalArticle></div><div class=col-sm-1>'
                        .Html::button('<i class="glyphicon glyphicon-check"></i> Proses', [
                            'id'=>'btnCheckprocessKontenDigitalArticle',
                            'class' => 'btn btn-primary btn-sm', 
                            'title' => 'Proses', 
                            //'data-toggle' => 'tooltip'
                        ])
                        .'</div></div><br>';

        Pjax::begin(['id' => 'GridArtikelKontenDigitals']); 
        echo GridView::widget([
            // 'id' => 'GridArtikelKontenDigitals',
            'dataProvider' => $dataProviderArticlesWithKontenDigital,
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
                // [
                //     'class'       => '\kartik\grid\CheckboxColumn',
                //     'pageSummary' => true,
                //     'rowSelectedClass' => GridView::TYPE_INFO,
                //     'name' => 'cek',
                //     'checkboxOptions' => function ($searchModelArticles, $key, $index, $column) {
                //         return [
                //             'value' => $searchModelArticles->id
                //         ];
                //     },
                //     'vAlign' => GridView::ALIGN_TOP
                // ],
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

                        return $data->Title;
                        // return Html::a($data->Title, 'javascript:void(0)', [
                        //     'title' => $data->Title,
                        //     'onclick' => '
                        //             var id = $(this).closest("tr").data("key");
                        //             FormArticle(id,$("#hdnCatalogId").val());
                        //         '
                        // ]);
                    }
                ],
                'EDISISERIAL',
                [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'expandTitle' => 'lihat konten digital',
                    'collapseIcon' => '<h6>File Digital</h6>',
                    'expandIcon' => '<h6 class="label label-success">File Digital <span class="glyphicon glyphicon-collapse-down"></span></h6>',
                    'collapseTitle' => 'tutup konten digital',
                    'expandAllTitle' => 'lihat semua konten digital',
                    'collapseAllTitle' => 'tutup semua konten digital',
                    'detail' => function ($model, $key, $index) {
                        $searchModel = new \common\models\SerialArticleFilesSearch();
                        $params['ArticleID'] = $model->id;
                        $dataProvider = $searchModel->search2($params);

                        return Yii::$app->controller->renderPartial('_subEksemplarArticle', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                        ]);

                    }
                ],
            ],
            //'summary'=>'',
            'responsive'=>true,
            'containerOptions'=>['style'=>'font-size:12px'],
            'hover'=>true,
            'condensed'=>true,
            'panel' => [
                'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> Data Artikel </h3>',
                'type'=>'info',
                'showFooter'=>false
            ],
        ]); Pjax::end(); ?>


    </div>



    <script type="text/javascript">
        $("#serialarticlefiles-fileexecutable").focus(function() {
            $("#msgKontenDigital").html("");
        });
        $('#serialarticlefiles-file-fileupload').bind('fileuploadsubmit', function (e, data) {
            // The example input, doesn't have to be part of the upload form:
            var fileexecutable = $('#serialarticlefiles-fileexecutable');
            var iscompress = $('#serialarticlefiles-iscompress:checkbox:checked');
            var serialID = $('#serialarticlefiles-articles_id');
            data.formData = {fileExecutable: fileexecutable.val(),isCompress: iscompress.length,serialID : serialID.val()};
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