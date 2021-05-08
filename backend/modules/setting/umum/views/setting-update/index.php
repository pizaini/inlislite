<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use common\widgets\AjaxButton;

/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 */

$this->title = Yii::t('app', 'Pengaturan Setting Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sirkulasi'), 'url' => Url::to(['/setting/umum'])];
$this->params['breadcrumbs'][] = $this->title;
$homeUrl=Yii::$app->homeUrl;

$ajaxOptions = [
        'type' => 'POST',
        'url'  => 'set-import-authority',
        'data' => [
                'value' =>  new yii\web\JsExpression('$(this).val()'),
                'IsActivatingImportingAuthorityData' => $model->IsActivatingImportingAuthorityData == 0 ? 1 : 0,
                'IsActivatingKII' => $model->IsActivatingKII == 0 ? 1 : 0,
            ],
       'success'=>new yii\web\JsExpression('
            function(data){ 
                console.log(data);
                location.reload();
            }'),
       'error' => new yii\web\JsExpression('function(xhr, ajaxOptions, thrownError){ 
            alert("memang belum");
        }'),
    ];

?>

<style type="text/css">
    .col-sm-4 label{
        font-weight: normal;
    }

    .table{
        margin-bottom: 0px;
    }

    .form-group > .col-sm-offset-3  {
        margin-left: 0px;
    }
</style>


<div class="settingparameters-create">
    <div class="page-header">
        <!-- <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?> -->
        <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    </div>
<div class="settingparameters-form">
  <div class="form-group">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]]); ?>
        <div class="row">
            <div class="col-sm-3" style="text-align: right">
                <label><?= yii::t('app','Status update aplikasi')?></label>
            </div>
            <div class="col-sm-9">
                <p>
                    Server : http://dev.pnri.go.id/UpdateVersion/CurrentUpdateVersion.txt <?= yii::t('app','(Server tidak aktif)')?><br/>
                    <?= yii::t('app','Versi sekarang : v.3.0')?>
                </p>
            </div>
        </div>
        <hr class="" /> 
        <?php // echo $form->field($model, 'IsActivatingImportingAuthorityData')->checkbox(array('label'=>'Ya/ Tidak'))->label('Status Auto Update Data Authority'); ?>

        <div class="form-group field-dynamicmodel-isactivatingimportingauthoritydata required">
            <label class="control-label col-sm-3" for="dynamicmodel-isactivatingimportingauthoritydata"><?= Yii::t('app','Status Auto Update Data Authority') ?></label>
            <div class="col-sm-offset-3 col-sm-9">
                <div class="checkbox">
                   <?php 
                    echo AjaxButton::widget([
                        'label' => $model->IsActivatingImportingAuthorityData == 0 ? '<i class="glyphicon glyphicon-check"></i> ' .Yii::t('app','Aktifkan') : '<i class="glyphicon glyphicon-check"></i> ' .Yii::t('app','NonAktifkan'),
                        'ajaxOptions' => $ajaxOptions,
                        'htmlOptions' => [
                            'class' => $model->IsActivatingImportingAuthorityData == 0 ? 'btn btn-info' : 'btn btn-danger',
                            'id' => 'btnIsActivatingImportingAuthorityData',
                            'type' => 'submit',
                            'value' => 'IsActivatingImportingAuthorityData'
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <div class="col-sm-offset-3 col-sm-9"></div>
            <div class="col-sm-offset-3 col-sm-9"><div class="help-block"></div></div>
        </div>

        <div class="form-group field-dynamicmodel-isactivatingkii required">
            <label class="control-label col-sm-3" for="dynamicmodel-isactivatingkii"><?= Yii::t('app','Status Aktivasi Katalog Induk Inlislite') ?> </label>
            <div class="col-sm-offset-3 col-sm-9">
                <div class="checkbox">
                    <?php 
                    echo AjaxButton::widget([
                        'label' => $model->IsActivatingKII == 0 ? '<i class="glyphicon glyphicon-check"></i> ' .Yii::t('app','Aktifkan') : '<i class="glyphicon glyphicon-check"></i> ' .Yii::t('app','NonAktifkan'),
                        'ajaxOptions' => $ajaxOptions,
                        'htmlOptions' => [
                            'class' => $model->IsActivatingKII == 0 ? 'btn btn-info' : 'btn btn-danger',
                            'id' => 'btnIsActivatingKII',
                            'type' => 'submit',
                            'value' => 'IsActivatingKII'
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <div class="col-sm-offset-3 col-sm-9"></div>
            <div class="col-sm-offset-3 col-sm-9"><div class="help-block"></div></div>
        </div>

        <?php // echo $form->field($model, 'IsActivatingKII')->checkbox(array('label'=>'Ya/ Tidak'))->label('Status Aktivasi Katalog Induk Inlislite '); ?>
        <hr class="" /> 
        <div class="form-horizontal"> <!-- hidden="hidden" -->
            <table class="table" style="table-layout: fixed;">
                <thead>
                    <tr>
                        <td class="col-sm-3 text-right"><label class="control-label"><?= Yii::t('app','Tanggal Update Terakhir Data Authority') ?></label></td>
                        <td>
                            <p> 
                                <?php echo $model->AuthorityDataLastDate; ?>
                                <div class="padding0 col-sm-9"><b class="hint-uang"></b></div>
                            </p>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="form-horizontal"> <!-- hidden="hidden" -->
            <table class="table" style="table-layout: fixed;">
                <thead>
                    <tr>
                        <td class="col-sm-3 text-right"><label class="control-label"><?= Yii::t('app','Kode Katalog Induk Inlislite(KII)') ?></label></td>
                        <td>
                        <p>
                            <?php echo $model->KIICode; ?>
                            <div class="padding0 col-sm-9"><b class="hint-uang"></b></div>
                        </p>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="form-horizontal"> <!-- hidden="hidden" -->
            <table class="table" style="table-layout: fixed;">
                <thead>
                    <tr>
                        <td class="col-sm-3 text-right"><label class="control-label"><?= Yii::t('app','Update Struktur Database').Yii::t('app','Versi 3.2').' (10 Feb 2021)' ?></label></td>
                        <td>
                       <!-- <button type="button" class="btn btn-md btn-info" id="updateQuery" onclick="#" disabled><i class="fa fa-edit"></i> Update</button>-->
                         <button type="button" class="btn btn-md btn-info" id="updateQuery" onclick="updateCek();"><i class="fa fa-edit"></i> Update</button>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
        <hr class="" /> 
        <legend><h4><strong>Sinkronisasi Data</strong></h4></legend>
        <div class="form-horizontal"> <!-- hidden="hidden" -->
            <table class="table" style="table-layout: fixed;">
                <thead>
                    <tr>
                        <td class="col-sm-3 text-right"><label class="control-label" style="color : red"><?= Yii::t('app','Petunjuk Sebelum Melakukan Sinkronisasi') ?></label></td>
                        <td>
                            - Pastikan sudah terhubung dengan koneksi Server <br>
                            - Pada saat pertama kali sinkronisasi, pastikan data di Local sudah sama dengan di Server <br>
                        </td>
                    </tr>
                </thead>
            </table>
            <table class="table" style="table-layout: fixed;">
                <thead>
                    <tr>
                        <td class="col-sm-3 text-right"><label class="control-label"><?= Yii::t('app','Sinkronisasi Data Local ke Server').'<br> '.Yii::$app->config->get('SinkronisasiLocaltoServer') ?></label></td>
                        <td>
                       <!-- <button type="button" class="btn btn-md btn-info" id="updateQuery" onclick="#" disabled><i class="fa fa-edit"></i> Update</button>-->
                         <button type="button" class="btn btn-md btn-primary" id="sinkronisasiLocalServer" value="up"><i class="fa fa-arrow-circle-up"></i> Local ke Server</button>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="form-horizontal"> <!-- hidden="hidden" -->
            <table class="table" style="table-layout: fixed;">
                <thead>
                    <tr>
                        <td class="col-sm-3 text-right"><label class="control-label"><?= Yii::t('app','Sinkronisasi Data Server ke Local').'<br> '.Yii::$app->config->get('SinkronisasiServertoLocal') ?></label></td>
                        <td>
                       <!-- <button type="button" class="btn btn-md btn-info" id="updateQuery" onclick="#" disabled><i class="fa fa-edit"></i> Update</button>-->
                         <button type="button" class="btn btn-md btn-primary" id="sinkronisasiServerLocal" value="down"><i class="fa fa-arrow-circle-down"></i> Server ke Local</button>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    
    

</div>

</div>




<!-- Modal -->
<div id="SettingJaminanUang" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app','Setting').' '.Yii::t('app','Uang Jaminan') ?></h4>
            </div>
            <div class="modal-body row">
                
                


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<div id="errorLog">
    
    
</div>


<script>
    function updateCek(){
        a = 'update';
        // alert(url);
        // alert('a');
        $.ajax({
            type     :"POST",
            cache    : false,
            url      : a,
            /*success  : function(response) {
                //window.location = window.location.href; 
            }*/
            success  : function(response) {
            $("#errorLog").html(response);
            }
        });
        
        // sweetAlert('', 'Tampung katalog akan dibersihkan');
        
    }

    $("#sinkronisasiLocalServer").click(function(){
        $.ajax({
            type : "POST",
            url : "sinkronisasi-data",
            cache : false,
            data : {tipe : this.value},
            
        }).done(function(data) {
            console.log(data);
            location.reload();
        }).error(function(xhr, status, error) {
          var err = eval("(" + xhr.responseText + ")");
          alert(err.Message);
        });
    });

    $("#sinkronisasiServerLocal").click(function(){
        $.ajax({
            type : "POST",
            url : "sinkronisasi-data",
            cache : false,
            data : {tipe : this.value},
            
        }).done(function(data) {
            console.log(data);
            location.reload();
        }).error(function(xhr, status, error) {
          var err = eval("(" + xhr.responseText + ")");
          alert(err.Message);
        });
    });
</script>
