<?php
use yii\helpers\Html;
use common\models\TujuanKunjungan;
use common\models\MasterPekerjaan;
use common\models\MasterPendidikan;
use common\models\JenisKelamin;
use common\models\Locations;

use yii\helpers\ArrayHelper;

use kartik\widgets\ActiveForm;

//use common\components\AjaxSubmitButton;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Buku Tamu Pengunjung');


$jmlPengunjung = str_pad($totalVisitors, 4, '0', STR_PAD_LEFT);

// get cookies
$cookies = Yii::$app->request->cookies;
$location_Bukutamu_id  = $cookies->getValue('location_Bukutamu_id');

// get location model
$location = Locations::find()
    ->where(['ID' => $location_Bukutamu_id ])
    ->one();
Yii::$app->view->params['subTitle'] = '<h3>'.Yii::t('app', 'Selamat Datang').'<br>'.Yii::t('app', 'Di ').$location->Name.'</h3><h5 style="display: block;">('.number_format($totalVisitors).' '.Yii::t('app', 'pengunjung hari ini').')</h5>';

$profesi = ArrayHelper::map(MasterPekerjaan::find()->all(),'id','Pekerjaan');
$pendidikan = ArrayHelper::map(MasterPendidikan::find()->all(),'id','Nama');
$jenisKelamin = ArrayHelper::map(JenisKelamin::find()->all(),'ID','Name');

?>



<style type="text/css">
    .ui-dialog { z-index: 10000 !important ;}
</style>

<div class="message" data-message-value="<?= Html::encode(Yii::$app->session->getFlash('message')) ?>">
</div>

<div class="row">
    <div class="col-sm-12" style="padding:0 -15px">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Yii::$app->getUrlManager()->createUrl('site/member') ?>" ><?= Yii::t('app', 'Anggota') ?></a></li>
                <li class="active"><a href="#tab_2" data-toggle="tab"><?= Yii::t('app', 'Non Anggota') ?></a></li>
                <li><a href="<?= Yii::$app->getUrlManager()->createUrl('site/group') ?>"><?= Yii::t('app', 'Rombongan') ?></a></li> 
            </ul>

        </div>
    </div>
</div>


<div class="box-body" style="padding:50px 0">
    <div class="tab-content">
        <!-- Tab Buku-tamu anggota -->
        <div class="tab-pane " id="tab_1">
        </div>
        <!-- Tab Buku-tamu Non-Anggota -->
        <div class="tab-pane active" id="tab_2">

            <?php $form = ActiveForm::begin(); ?>


            <div class="col-sm-12">
                <div class="box box-info">
                <!-- /.box-header -->
                <!-- form start -->
                    <form class="form-horizontal">
                        <div class="box-body">
                            <div class="row form-group">
                                <label class="col-sm-3 col-sm-offset-1 control-label">
                                    <?= Yii::t('app', 'Nama Pengunjung') ?>
                                </label>
                                <div class="col-sm-7">
                                    <?= $form->field($model, 'Nama')->textInput()->label(false) ?>
                                    <!-- <input type="text" class="form-control" id="inputEmail3"> -->
                                </div>
                                <div class="col-sm-1">
                                </div>
                            </div>

                            <div class="row form-group">
                                <label class="col-sm-3 col-sm-offset-1 control-label">
                                    <?= Yii::t('app', 'Pekerjaan') ?>
                                </label>
                                <div class="col-sm-7">
                                    <?=Html::activeRadioList($model, 'Profesi_id', $profesi, [
                                        'item' => function ($index, $label, $name, $checked, $value) {
                                            return '<label class="col-sm-4">' . Html::radio($name, $checked, ['value'  => $value]) . ' &nbsp; ' . $label . '</label>';
                                        }
                                    ])?>

                                </div>
                            </div>

                            <div class="row form-group">
                                <label class="col-sm-3 col-sm-offset-1 control-label">
                                    <?= Yii::t('app', 'Pendidikan Terakhir') ?>
                                </label>
                                <div class="col-sm-7">
                                   <?=Html::activeRadioList($model, 'PendidikanTerakhir_id', $pendidikan, [
                                    'item' => function ($index, $label, $name, $checked, $value) {
                                        return '<label class="col-sm-4">' . Html::radio($name, $checked, ['value'  => $value]) . ' &nbsp; ' . $label . '</label>';
                                    }
                                    ])?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <label class="col-sm-3 col-sm-offset-1 control-label">
                                    <?= Yii::t('app', 'Jenis Kelamin') ?>
                                </label>
                                <div class="col-sm-7">
                                    <?=Html::activeRadioList($model, 'JenisKelamin_id', $jenisKelamin, [
                                    'item' => function ($index, $label, $name, $checked, $value) {
                                        return '<label class="col-sm-4">' . Html::radio($name, $checked, ['value'  => $value]) . ' &nbsp; ' . $label . '</label>';
                                    }
                                    ])?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 col-sm-offset-1 control-label">
                                    <?= Yii::t('app', 'Alamat') ?>
                                </label>
                                <div class="col-sm-7">
                                    <?= $form->field($model, 'Alamat')->textarea(['rows'=>'3','class'=>'form-control'])->label(false); ?>
                                    <!-- <textarea class="form-control" rows="3"></textarea> -->
                                </div>
                            </div>

                            <!-- Jika menu Tujuan Kunjungan di set True -->
                            <?php if ($location->IsVisitsDestination == 1) { ?>
                            <div class="row form-group">
                                <label class="col-sm-3 col-sm-offset-1 control-label"><?= Yii::t('app', 'Tujuan') ?></label>
                                <div class="col-sm-7">
                                    <?= $form->field($model, 'TujuanKunjungan_Id')->widget('\kartik\widgets\Select2',[
                                        'data'=>ArrayHelper::map(TujuanKunjungan::find()->where(['NonMember' => 1])->all(),'ID','TujuanKunjungan'),
                                        'pluginOptions' => [
                                        'allowClear' => true,
                                        ],
                                        'options'=> ['placeholder'=>Yii::t('app', 'Pilih Tujuan Kunjungan')]
                                    ])->label(false); ?>
                                </div>
                            </div>
                            <?php } ?>

                            <!-- Jika menu Inforamsi yang anda cari di set True -->
                            <?php if ($location->IsInformationSought == 1) { ?>
                            <div class="row form-group">
                                <label class="col-sm-3 col-sm-offset-1 control-label">
                                    Informasi Yang anda cari ?
                                </label>
                                <div class="col-sm-7">
                                    <?= $form->field($model, 'Information')->textarea(['rows'=>'3','class'=>'form-control'])->label(false); ?>
                                    <!-- <textarea class="form-control" rows="3"></textarea> -->
                                </div>
                            </div>
                            <?php } ?>



                            <?php if ($location->IsGenerateVisitorNumber == 1) { ?>
                            <input type="hidden" name="IsGenerateVisitorNumber" id="IsGenerateVisitorNumber" value="true" />
                            
                            <div id="barcodeNumber" data-barcode-number="<?= Yii::$app->session->getFlash('barcodeNumber') ?>">
                            </div>
                            
                            <?php } ?>
                            <?php if ($location->IsPrintBarcode == 1) { ?>
                            <input type="hidden" name="IsPrintBarcode" id="IsPrintBarcode" value="true" />
                            <?php } ?>




                        </div><!-- /.box-body -->

                        <div class="form-group">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-8">
                                <button type="submit" class="btn btn-default"><?= Yii::t('app', 'Simpan') ?></button>&nbsp;
                                <button type="button" onClick="window.location.href=window.location.href" class="btn btn-default"><?= Yii::t('app', 'Ulangi') ?></button>
                            </div>
                        </div>
                        <div class="col-sm-4"></div>
                       <!--  <div class="col-sm-8"><h4>Terima kasih. Anda pengunjung ke <?= number_format($jmlPengunjung+1) ?> hari ini.</h4></div> -->
                    </form> <!-- end form -->
                </div>
            </div>
            <?php ActiveForm::end();?>

        </div>
        <!-- Tab Buku-tamu Rombongan -->
        <div class="tab-pane" id="tab_3">
        </div><!-- /.tab-pane -->
    </div><!-- /.tab-content -->
</div>

<audio id="welcomeAudio" width="320" height="176" hidden="hidden">
  <source src="<?= Yii::$app->urlManager->createUrl("../uploaded_files/settings/audio/").'/'.$audio['Value'] ?>" type="audio/mpeg">
  Browser anda tidak support HTML5 audio.
</audio >

<?= Yii::$app->session->getFlash('audio') ?>


<?php
$url = Yii::$app->urlManager->createUrl(['site']);
$script = <<< JS
    var url = "$url";
    if ($('#barcodeNumber').data("barcodeNumber"))
    {
        //Tambah blur page
        $(document.body).append('<div class="sweet-overlay" tabindex="-1" style="opacity: 1.06; display: block;"></div>');

        $( "#barcodeNumber" ).dialog({
            autoOpen: false,
            width: 400,
            title: "Barcode",
            style: "z-index: 10000",
            closeOnEscape: false,
            open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog | ui).hide(); },
            buttons: {
                "Tutup": function() {
                    $( this ).dialog( "close" );
                    $(".sweet-overlay").remove();
                },
                "Print": function() {
                    PrintElem("#barcodeNumber");
                }
            }
        });
        
        $('#CetakSlip').click(function(){
        $.get('".$url."', {transactionID: ".$transactionID."},function(data, status){
            if(status == 'success'){
                try {
                    var oIframe = document.getElementById('Iframe1Slip');
                    var oDoc = (oIframe.contentWindow || oIframe.contentDocument);
                    if (oDoc.document) oDoc = oDoc.document;
                    oDoc.write('<html><head>');
                    oDoc.write('</head><body onload=\"this.focus(); this.print(true);\" style=\"text-align: left; font-size: 8pt; width: 95%; height:90%\">');
                    oDoc.write(data + '</body></html>');
                    oDoc.close();
                } catch (e) {
                    alert(e.message);
                    self.print();
                }
            }
            
        });
        /*setTimeout(function(){ 
            //$.print('#divPrint');  
        }, 200);*/
    });

        // not print barcode if non supported
        if ($('#IsPrintBarcode').val() != "true") {
            $(document.body).append('<div class="sweet-overlay" tabindex="-1" style="opacity: 1.06; display: block;"></div>');
            $( "#barcodeNumber" ).dialog( "option", "buttons",
              [
                {
                  text: "Tutup",
                  click: function() {
                    $( this ).dialog( "close" );
                    $(".sweet-overlay").remove();
                  }
                }
              ]
            );
        }

        $( "#barcodeNumber" ).html(
            "<div>"
            + $('.message').data("messageValue") + "<br />"
            + "No. Pengunjung : " + $('#barcodeNumber').data("barcodeNumber") + "<br />"
            + "</div>"
            + "<img src= "+ url + "/image-barcode?fontText=" + $('#barcodeNumber').data("barcodeNumber") + " \" />"
        );

        $( "#barcodeNumber" ).dialog("open");

    } else if ($('.message').data("messageValue")) {
        swal($('.message').data("messageValue"));
    }

JS;

$this->registerJs($script);
?>
