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
  <div class="form-group col-sm-12">
  <div class="row">
  <div class="col-xs-6">
    
  <?php


  $form = ActiveForm::begin([

  'type'=>ActiveForm::TYPE_HORIZONTAL,
  'formConfig' => ['labelSpan'=>4,'deviceSize' => ActiveForm::SIZE_SMALL],

  ]); ?>

    <?= $form->field($model, 'Value1')->checkbox(['label'=>'Ya'],['inline'=>true])->label('Aktifkan Booking');; ?>   
    
   <!--   <?= $form->field($model,'Value2')->textInput(['style'=>'width:150px'], ['inline'=>true])->label(Yii::t('app', 'Jumlah Pemesanan Maksimal')) ?> -->
    <?= $form->field($model,'Value2')->textInput(['type' => 'number', 'min' => 1], ['inline'=>true])->label(Yii::t('app', 'Jumlah Pemesanan Maksimal')) ?>

    <?= $form->field($model, 'Value3')->widget(TimePicker::className(),  
     [  
        'readonly' => true,                     
        'pluginOptions' => [
                'showSeconds' => true,
                'minuteStep' => 1,
                'showMeridian' => false,
                'defaultTime' => date('H:i', strtotime('-2 hour')),

        ],
        'size' => xs,
        'options'=>[
            'class'=>'form-control',
            //'style'=>'width:150px',
            
        ],
    ])->label(Yii::t('app', 'Lama Pemesanan Sebelum kadaluarsa')); ?>

  </div>
    <div class="col-xs-7"></div>
<div class="col-xs-12">
 <div class="alert alert-info alert-dismissable" style="margin-top: 20px">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-info"></i> <?= Yii::t('app','Catatan')  ?></h4>
    <?= Yii::t('app','Apabila masa jatuh tempo tersisa setelah jam tutup perpustakaan, maka akan diakumulasikan setelah jam buka pada hari operasional berikutnya ')  ?>
</div>


    <?php //echo $form->field($model,'Value4')->radioList(['Simple'=>Yii::t('app', 'Simple'),'Advance'=>Yii::t('app', 'Advance')], ['inline'=>true])->label(Yii::t('app', 'Entry Form Collection'))?>
  
    &nbsp;<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?> 

</div>



    </div>


</div>
