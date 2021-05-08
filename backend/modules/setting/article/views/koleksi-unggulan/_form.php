<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="settingparameters-form">
  <div class="form-group">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>

    <?= $form->field($model,'Value1')->radioList(['TRUE'=>Yii::t('app', 'Ya'),'FALSE'=>Yii::t('app', 'Tidak')], ['inline'=>true])->label(Yii::t('app', 'Tampilkan Koleksi Unggulan'))?>

    <?= $form->field($model,'Value2')->textInput(['type' => 'number', 'min' => 1,'type' => 'number', 'min' => 1,'style'=>'width:150px'], ['inline'=>true])->label(Yii::t('app', 'Maksimal Jumlah Koleksi')) ?>

    

    <?php //echo $form->field($model,'Value4')->radioList(['Simple'=>Yii::t('app', 'Simple'),'Advance'=>Yii::t('app', 'Advance')], ['inline'=>true])->label(Yii::t('app', 'Entry Form Collection'))?>
  
    &nbsp;<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>


</div>
