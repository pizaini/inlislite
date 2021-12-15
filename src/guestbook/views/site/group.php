<?php
use yii\helpers\Html;
use common\models\TujuanKunjungan;
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
               <li><a href="<?= Yii::$app->getUrlManager()->createUrl('site/member') ?>"><?= Yii::t('app', 'Anggota') ?></a></li>
                <li><a href="<?= Yii::$app->getUrlManager()->createUrl('site/nonmember') ?>"><?= Yii::t('app', 'Non Anggota') ?></a></li>
                <li class="active"><a href="#tab_3" data-toggle="tab"><?= Yii::t('app', 'Rombongan') ?></a></li>
            </ul>

        </div>
    </div>
</div>


<div class="box-body" style="padding:50px 0">
    <div class="tab-content">
        <!-- Tab Buku-tamu anggota -->
        <div class="tab-pane " id="tab_1">
        </div>
        <!-- Tab Buku-tamu Nonanggota -->
        <div class="tab-pane " id="tab_2">
        </div>
        <!-- Tab Buku-tamu Rombongan -->
        <div class="tab-pane active " id="tab_3">

            <div class="col-sm-12">
                <div class="box box-info">
                    <!-- /.box-header -->
                    <!-- form start -->
                    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'formConfig' => ['labelSpan' => 4, 'deviceSize' => ActiveForm::SIZE_SMALL],]); ?>
                        <div class="box-body">
                            <div class="col-sm-10 col-sm-offset-1">

                                <?= $form->field($model, 'NamaKetua')->textInput(['class'=>'col-sm-7'])->label(Yii::t('app','Nama Ketua Rombongan')) ?>
                                <?= $form->field($model, 'NomerTelponKetua')->textInput(['class'=>'col-sm-7'])->label(Yii::t('app','Nomor Telepon Ketua Rombongan')) ?>
                                <?= $form->field($model, 'AsalInstansi')->textInput()->label(Yii::t('app','Nama Instansi Lembaga')) ?>
                                <?= $form->field($model, 'AlamatInstansi')->textarea(['rows'=>'3','class'=>'form-control'])->label(Yii::t('app','Alamat Instansi Lembaga')) ?>
                                <?= $form->field($model, 'TeleponInstansi')->textInput()->label(Yii::t('app','Nomor Telepon Instansi Lembaga')) ?>
                                <?= $form->field($model, 'EmailInstansi')->input('email')->label(Yii::t('app','Alamat Email Instansi Lembaga')) ?>
                                <?= $form->field($model, 'CountPersonel')->textInput(['class'=>'onlyNumber','onchange'=>'checkNumber(this)', 'onfocus'=>'DoWaterMarkOnFocus(this,&#39;1&#39;)', 'onblur'=>'DoWaterMarkOnBlur(this,&#39;1&#39;)'])->label(Yii::t('app','Jumlah Personil')) ?>


                                <!-- Form Group pekerjaan -->
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">
                                        <?= Yii::t('app','Jenis Kelamin')?>
                                    </label>
                                    <div class="col-sm-8 ">

                                        <div class="col-sm-12" style="padding-bottom: 15px">
                                            <!-- kolom jumlah CountLaki -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountLaki',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountLaki', ['style'=>'text-align:left','label'=>Yii::t('app','Laki-Laki'), 'class'=>'col-sm-3 control-label']) ?>

                                            <!-- kolom jumlah Pegawai Swasta -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountPerempuan',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountPerempuan', ['style'=>'text-align:left','label'=>Yii::t('app','Perempuan'), 'class'=>'col-sm-3 control-label']) ?>

                                        </div>

                                    </div>
                                </div><!-- End form pekerjaan group -->

                                <!-- Form Group pekerjaan -->
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">
                                        <?= Yii::t('app','Pekerjaan')?>
                                    </label>
                                    <div class="col-sm-8 ">

                                        <div class="col-sm-12" style="padding-bottom: 15px">
                                            <!-- kolom jumlah pns -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountPNS',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountPNS', ['style'=>'text-align:left','label'=>Yii::t('app','PNS'), 'class'=>'col-sm-3 control-label']) ?>

                                            <!-- kolom jumlah Pegawai Swasta -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountPSwasta',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountPSwasta', ['style'=>'text-align:left','label'=>Yii::t('app','Pegawai Swasta'), 'class'=>'col-sm-3 control-label']) ?>

                                            <!-- kolom jumlah Peneliti -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountPeneliti',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountPeneliti', ['style'=>'text-align:left','label'=>Yii::t('app','Peneliti'), 'class'=>'col-sm-3 control-label']) ?>
                                        </div>

                                        <div class="col-sm-12" style="padding-bottom: 15px">
                                            <!-- kolom jumlah CountGuru -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountGuru',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountGuru', ['style'=>'text-align:left','label'=>Yii::t('app','Guru'), 'class'=>'col-sm-3 control-label']) ?>

                                            <!-- kolom jumlah CountDosen -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountDosen',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountDosen', ['style'=>'text-align:left','label'=>Yii::t('app','Dosen'), 'class'=>'col-sm-3 control-label']) ?>

                                            <!-- kolom jumlah CountPensiunan -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountPensiunan',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountPensiunan', ['style'=>'text-align:left','label'=>Yii::t('app','Pensiunan'), 'class'=>'col-sm-3 control-label']) ?>
                                        </div>

                                        <div class="col-sm-12" style="padding-bottom: 15px">
                                            <!-- kolom jumlah CountTNI -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountTNI',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountTNI', ['style'=>'text-align:left','label'=>Yii::t('app','TNI'), 'class'=>'col-sm-3 control-label']) ?>

                                            <!-- kolom jumlah CountWiraswasta -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountWiraswasta',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountWiraswasta', ['style'=>'text-align:left','label'=>Yii::t('app','Wiraswasta'), 'class'=>'col-sm-3 control-label']) ?>

                                            <!-- kolom jumlah CountPelajar -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountPelajar',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountPelajar', ['style'=>'text-align:left','label'=>Yii::t('app','Pelajar'), 'class'=>'col-sm-3 control-label']) ?>
                                        </div>

                                        <div class="col-sm-12" style="padding-bottom: 15px">
                                            <!-- kolom jumlah CountMahasiswa -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountMahasiswa',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountMahasiswa', ['style'=>'text-align:left','label'=>Yii::t('app','Mahasiswa'), 'class'=>'col-sm-3 control-label']) ?>

                                            <!-- kolom jumlah CountLainnya -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountLainnya',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountLainnya', ['style'=>'text-align:left','label'=>Yii::t('app','Lainnya'), 'class'=>'col-sm-3 control-label']) ?>
                                        </div>

                                    </div>
                                </div><!-- End form pekerjaan group -->

                                <!-- form pendidikan group -->
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">
                                        <?= Yii::t('app','Pendidikan Terakhir')?>
                                    </label>
                                    <div class="col-sm-8 ">

                                        <div class="col-sm-12" style="padding-bottom: 15px">
                                            <!-- kolom jumlah CountSD -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountSD',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountSD', ['style'=>'text-align:left','label'=>Yii::t('app','SD'), 'class'=>'col-sm-3 control-label']) ?>

                                            <!-- kolom jumlah CountSMP-->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountSMP',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountSMP', ['style'=>'text-align:left','label'=>Yii::t('app','SMP (sederajat)'), 'class'=>'col-sm-3 control-label']) ?>

                                            <!-- kolom jumlah CountSMA -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountSMA',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountSMA', ['style'=>'text-align:left','label'=>Yii::t('app','SMA (sederajat)'), 'class'=>'col-sm-3 control-label']) ?>
                                        </div>

                                        <div class="col-sm-12" style="padding-bottom: 15px">
                                            <!-- kolom jumlah Diploma (D1) -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountD1',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountD1', ['style'=>'text-align:left','label'=>Yii::t('app','Diploma (D1)'), 'class'=>'col-sm-3 control-label']) ?>

                                            <!-- kolom jumlah CountD2-->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountD2',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountD2', ['style'=>'text-align:left','label'=>Yii::t('app','Diploma (D2)'), 'class'=>'col-sm-3 control-label']) ?>

                                            <!-- kolom jumlah CountD3-->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountD3',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountD3', ['style'=>'text-align:left','label'=>Yii::t('app','Diploma (D3)'), 'class'=>'col-sm-3 control-label']) ?>

                                        </div>

                                        <div class="col-sm-12" style="padding-bottom: 15px">
                                            <!-- kolom jumlah Sarjana (S1) -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountS1',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountS1', ['style'=>'text-align:left','label'=>Yii::t('app','Sarjana (S1)'), 'class'=>'col-sm-3 control-label']) ?>

                                            <!-- kolom jumlah Sarjana (S2) -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountS2',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountS2', ['style'=>'text-align:left','label'=>Yii::t('app','Magister (S2)'), 'class'=>'col-sm-3 control-label']) ?>

                                            <!-- kolom jumlah Sarjana (S3) -->
                                            <div class="col-sm-1">
                                                <?= Html::activeTextInput($model,'CountS3',['class'=>'form-control onlyNumber','style'=>'padding: 2px;']); ?>
                                            </div>
                                            <?= Html::activeLabel($model, 'CountS3', ['style'=>'text-align:left','label'=>Yii::t('app','Doktor (S3)'), 'class'=>'col-sm-3 control-label']) ?>
                                        </div>

                                    </div>
                                </div>


                                <?php if ($location->IsVisitsDestination == 1) { ?>
                                    <?= $form->field($model, 'TujuanKunjungan_ID')->widget('\kartik\widgets\Select2',[
                                        'data'=>ArrayHelper::map(TujuanKunjungan::find()->where(['Rombongan' => 1])->all(),'ID','TujuanKunjungan'),
                                        'pluginOptions' => [
                                        'allowClear' => true,
                                        ],
                                        'options'=> ['placeholder'=>Yii::t('app', 'Pilih').' '.Yii::t('app', 'Tujuan Kunjungan')]
                                    ])->label(Yii::t('app','Tujuan Kunjungan')); ?>
                                <?php } ?>

                                <?php if ($location->IsInformationSought == 1) { ?>
                                    <?= $form->field($model, 'Information')->textarea(['rows'=>'3','class'=>'form-control'])->label(Yii::t('app','Informasi yang anda cari')); ?>
                                <?php } ?>

                                <?php if ($location->IsGenerateVisitorNumber == 1) { ?>
                                <input type="hidden" name="IsGenerateVisitorNumber" id="IsGenerateVisitorNumber" value="true" />
                                <div id="barcodeNumber" data-barcode-number="<?= Yii::$app->session->getFlash('barcodeNumber') ?>">
                                </div>
                                <?php } ?>
                                <?php if ($location->IsPrintBarcode == 1) { ?>
                                <input type="hidden" name="IsPrintBarcode" id="IsPrintBarcode" value="true" />
                                <?php } ?>
                                <input type="hidden" name="Location_ID" id="" value="<?= $location_Bukutamu_id  ?>" />

                            </div> <!-- end col-sm-10 offset-1 -->

                        </div><!-- /.box-body -->
                        <div class="col-sm-10 col-sm-offset-1">
                            <div class="col-sm-8 col-sm-offset-4">
                                <button type="submit" class="btn btn-default">
                                    <?= Yii::t('app', 'Simpan') ?>
                                </button>&nbsp;
                                <button type="button" onClick="window.location.href=window.location.href" class="btn btn-default">
                                    <?= Yii::t('app', 'Ulangi') ?>
                                </button>
                            </div>
                            <div class="col-sm-8 col-sm-offset-4" style="padding-top: 15px">
                                <!-- <h4>Terima kasih. Anda rombongan ke <?= number_format($totalGroup+1)  ?> hari ini.</h4> -->
                            </div>
                        </div><!-- /.box-footer -->


                    <?php ActiveForm::end();?>
                </div>
            </div><!-- /.tab-pane -->

        </div>
    </div>
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
if ($('#barcodeNumber').data("barcodeNumber")) {
    $(document.body).append('<div class="sweet-overlay" tabindex="-1" style="opacity: 1.06; display: block;"></div>');

    $( "#barcodeNumber" ).dialog({
        autoOpen: false,
        width: 400,
        title: "Barcode",
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

    // not print barcode if non supported
    if ($('#IsPrintBarcode').val() != "true") {
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

$('.onlyNumber').attr("placeholder", "0");

$('.onlyNumber').keypress(function(e){
    //disable when is not number
    if(e.which > 31 && (e.which < 48 || e.which > 57)){
      return false;
  } else {
  }
});



JS;

$this->registerJs($script);
?>
