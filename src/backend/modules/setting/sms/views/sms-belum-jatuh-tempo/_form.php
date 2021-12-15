<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\TimePicker;
/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="settingparameters-form">
  <div class="form-group col-sm-4">


    <?php
    $form = ActiveForm::begin([
    'id'=>"form-collection-modal",
    'method'=>'post',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'type'=>ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => ['labelSpan'=>4,'deviceSize' => ActiveForm::SIZE_SMALL],

    ]); ?>



    <?= $form->field($model,'Value1')->radioList(['TRUE'=>Yii::t('app', 'Ya'),'FALSE'=>Yii::t('app', 'Tidak')], ['inline'=>true])->label(Yii::t('app', 'Aktifkan Pengiriman SMS'))?>

    <?= $form->field($model,'Value2')->textInput(['type' => 'number', 'min' => 1], ['inline'=>true])->label(Yii::t('app', 'Jeda Hari')) ?>
    <?= $form->field($model, 'Value3')->widget(TimePicker::className(),  
     [  
        'readonly' => true,                     
        'pluginOptions' => [
                'minuteStep' => 1,
                'showMeridian' => false,
                'defaultTime' => date('H:i', strtotime('-2 hour')),

        ],
        'size' => xs,
        'options'=>[
            'class'=>'form-control',
            //'style'=>'width:150px',
            
        ],
    ])->label(Yii::t('app', 'Waktu Pengiriman SMS')); ?>

<div class="form-group field-dynamicmodel-value4 required">
<label class="control-label col-sm-4" for="dynamicmodel-value4"><?=yii::t('app','Contoh Isi Pesan')?></label>
<div class="col-sm-8"><textarea id="dynamicmodel-value4" class="form-control" name="DynamicModel[Value4]" readonly="" rows="8" inline="">Yth. Anggota 00001L2016, pinjaman koleksi " Buku praktis kardiologi /  oleh Sta..." akan jatuh tempo pada 2016-05-18. Harap segera mengembalikan </textarea></div>
<div class="col-sm-offset-4 col-sm-8"></div>
<div class="col-sm-offset-4 col-sm-8"><div class="help-block"></div></div>
</div>
  
    &nbsp;<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>
<!--     <div class="form-group col-sm-8">
        <div class="error-summary" style="">
            <p>Anda Bisa Menggunakan Template kata untuk membuat sms peringatan lebih informatif. Anda bisa menulis nama penerima sms dengan perintah <b>[[nama]]</b>, dan tanggal pinjam dengan <b>[[tglPinjam]]</b>. Berikut ini adalah list perintah yang tersedia :</p>
            <ul>
            <li>[[nama]]</li>
            <li>[[tglPinjam]]</li>
            <li>[[tglHarusKembali]]</li>
            <li>[[namaKoleksiDipinjam]]</li>
            <li>[[JumlahTotalKoleksiDipinjam]]</li>
            <li>[[JumlahTotalKoleksiDipinjamTelahJatuhTempo]]</li>
            <li>[[JumlahTotalKoleksiDipinjamAkanJatuhTempo]]</li>
            <li>[[tglPinjam]]</li>
            <li>[[tglPinjam]]</li>
            </ul>
        </div>
    </div> -->

</div>

