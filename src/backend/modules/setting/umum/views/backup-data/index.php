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

$this->title = Yii::t('app', 'Pengaturan Backup Data');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Umum'), 'url' => Url::to(['/setting/umum'])];
$this->params['breadcrumbs'][] = $this->title;
$homeUrl=Yii::$app->homeUrl;





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
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'formConfig' => ['labelSpan' => 1, 'deviceSize' => ActiveForm::SIZE_SMALL]]); ?>

        <div class="form-horizontal"> <!-- hidden="hidden" -->
            <table class="table" style="table-layout: fixed;">
                <thead>
                    <tr>
                        <td class="col-sm-2 text-right"><label class="control-label"><?= Yii::t('app','Backup Database') ?></label></td>
                        <td>
                        <button type="button" class="btn btn-md btn-info" onclick="backupDb();"><i class="fa fa-edit"></i> Proses</button>
                        </td>
                    </tr>
                    <tr>
                        <td></td><td><p>* <?= Yii::t('app','File .sql akan terunduh pada browser ini') ?></p></td>
                    </tr>
                </thead>
            </table>
        </div>
        
        
        <div class="form-horizontal"> <!-- hidden="hidden" -->
            <table class="table" style="table-layout: fixed;">
                <thead>
                    <tr>
                        <td class="col-sm-2 text-right"><label class="control-label"><?= Yii::t('app','Backup Uploaded Files') ?></label></td>
                        <td>
                        <!-- <a href="<?php //echo $homeUrl.'setting/umum/backup-data/zip'; ?>"><button type="button" class="btn btn-md btn-info" onclick="backupUploaded();"><i class="fa fa-edit"></i> Proses</button></a> -->
                        <!-- <button type="button" class="btn btn-md btn-info" onclick="setTimeout(backupUploaded, 10000);"><i class="fa fa-edit"></i> Proses</button> -->
                        <button type="button" class="btn btn-md btn-info" onclick="backupUploaded();"><i class="fa fa-edit"></i> Proses</button>
                        </td>
                    </tr>
                    <tr>
                        <td></td><td><p>* <?= Yii::t('app','File .zip akan terunduh pada browser ini') ?></p></td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    
    

</div>

</div>






<script>
    function backupDb(){
        home = "<?php echo $homeUrl ?>" + "setting/umum/backup-data/cek";
        // alert(home);
        b = 'setting/umum/backup-data/create';
        a = "<?php echo $homeUrl ?>" + b;
        // alert(url);
        // alert(a);
        $.ajax({
            type     :"POST",
            cache    : false,
            url      : a,
            success  : function(response) {

                window.location.href = home;

                // sweetAlert('', 'Tampung katalog akan dibersihkan');
                // window.location = window.location.href; 
            },
            error: function(response, jqXHR, textStatus, errorThrown) {
               setTimeout(function(){ 

                    // window.location.href = home;
                    sweetAlert('', 'Maaf, data terlalu besar untuk di unduh, silahkan backup secara manual');
                }, 1000);
            }
        });
        
        
        
    }

    function backupUploaded() {
        
        home = "<?php echo $homeUrl ?>" + "setting/umum/backup-data/download-zip";
        // alert(home);
        // b = 'setting/umum/backup-data/zip';
        // a = "<?php echo $homeUrl ?>" + b;
        c = 0;
        t = '';
        
        $.ajax({
            type     :"POST",
            cache    : false,
            // url      : home,
            url      : "<?php echo $homeUrl ?>" + "setting/umum/backup-data/zip",
            success  : function(response) {
                // c = c + 1;
                // $.ajax({
                //     type    : "POST",
                //     cache   : false,
                //     url     : "<?php echo $homeUrl ?>" + "setting/umum/backup-data/download-zip",
                //     success  : function(response) {
                //         // window.location.href = "<?php echo $homeUrl ?>" + "setting/umum/backup-data";
                //     },
                //     error: function(response, jqXHR, textStatus, errorThrown) {
                //        // setTimeout(function(){ 
                //         sweetAlert('', 'Maaf, data terlalu besar untuk di unduh, silahkan backup secara manual');
                //         // }, 1000);
                //     }
                // });
                if (c == 1) {
                    clearTimeout(t);
                    sweetAlert('', 'Tampung katalog akan dibersihkan');
                    window.location.href = "<?php echo $homeUrl ?>" + "setting/umum/backup-data";
                }

                t = setTimeout(function () {
                    
                    window.location.href = home;
                }, 1000);
                // window.location.href = "<?php echo $homeUrl ?>" + "setting/umum/backup-data/zip";
            },
            error: function(response, jqXHR, textStatus, errorThrown) {
               setTimeout(function(){ 

                    // window.location.href = home;
                    sweetAlert('', 'Maaf, data terlalu besar untuk di unduh, silahkan backup secara manual');
                }, 1000);
            }
        });    
        // }
        
        
    }

    // function backupUploaded(){
    //     var time = new Date().getMinutes();
    //     var cekTime = time + 1;
    //             // alert(time);
    //     home = "<?php echo $homeUrl ?>" + "setting/umum/backup-data/";
    //     // alert(home);
    //     b = 'setting/umum/backup-data/zip';
    //     a = "<?php echo $homeUrl ?>" + b;
    //     // alert(url);
    //     // alert(a);
        
    //         $.ajax({
    //             type     :"POST",
    //             cache    : false,
    //             url      : home,
    //             success  : function(response) {
    //                 // if(time){
    //                     // alert(cekTime);
    //                 // }else{
    //                 window.location.href = a;

    //                 // window.location = window.location.href;
    //                 // } 
    //             }
    //         });    
    //     // }
         
    // }
</script>
