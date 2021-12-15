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
<div class="Pengaturanparameters-form">
    <div class="form-group">
        <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>

        <h2> <?= Yii::t('app', 'Pengaturan faset pengarang')?></h2>
        <?= $form->field($model,'Value1')->textInput(['type' => 'number', 'min' => 1,'style'=>'width:150px'], ['inline'=>true])->label(Yii::t('app', 'Maksimal Jumlah Baris')) ?>
        <?= $form->field($model,'Value2')->textInput(['type' => 'number', 'min' => 1,'style'=>'width:150px'], ['inline'=>true])->label(Yii::t('app', 'Minimal Jumlah Baris')) ?>

        &nbsp;<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
        <h2> <?= Yii::t('app', 'Pengaturan faset penerbit')?></h2>
        <?= $form->field($model,'Value3')->textInput(['type' => 'number', 'min' => 1,'style'=>'width:150px'], ['inline'=>true])->label(Yii::t('app', 'Maksimal Jumlah Baris')) ?>
        <?= $form->field($model,'Value4')->textInput(['type' => 'number', 'min' => 1,'style'=>'width:150px'], ['inline'=>true])->label(Yii::t('app', 'Minimal Jumlah Baris')) ?>

        &nbsp;<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
        <h2> <?= Yii::t('app', 'Pengaturan faset lokasi penerbitan')?></h2>
        <?= $form->field($model,'Value5')->textInput(['type' => 'number', 'min' => 1,'style'=>'width:150px'], ['inline'=>true])->label(Yii::t('app', 'Maksimal Jumlah Baris')) ?>
        <?= $form->field($model,'Value6')->textInput(['type' => 'number', 'min' => 1,'style'=>'width:150px'], ['inline'=>true])->label(Yii::t('app', 'Minimal Jumlah Baris')) ?>
        &nbsp;<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
        <h2> <?= Yii::t('app', 'Pengaturan faset tahun terbit')?></h2>
        <?= $form->field($model,'Value7')->textInput(['type' => 'number', 'min' => 1,'style'=>'width:150px'], ['inline'=>true])->label(Yii::t('app', 'Maksimal Jumlah Baris')) ?>
        <?= $form->field($model,'Value8')->textInput(['type' => 'number', 'min' => 1,'style'=>'width:150px'], ['inline'=>true])->label(Yii::t('app', 'Minimal Jumlah Baris')) ?>
        &nbsp;<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
        <h2> <?= Yii::t('app', 'Pengaturan faset subyek')?></h2>
        <?= $form->field($model,'Value9')->textInput(['type' => 'number', 'min' => 1,'style'=>'width:150px'], ['inline'=>true])->label(Yii::t('app', 'Maksimal Jumlah Baris')) ?>
        <?= $form->field($model,'Value10')->textInput(['type' => 'number', 'min' => 1,'style'=>'width:150px'], ['inline'=>true])->label(Yii::t('app', 'Minimal Jumlah Baris')) ?>
        &nbsp;<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
        <h2> <?= Yii::t('app', 'Pengaturan faset Bahasa')?></h2>
        <?= $form->field($model,'Value11')->textInput(['type' => 'number', 'min' => 1,'style'=>'width:150px'], ['inline'=>true])->label(Yii::t('app', 'Maksimal Jumlah Baris')) ?>
        <?= $form->field($model,'Value12')->textInput(['type' => 'number', 'min' => 1,'style'=>'width:150px'], ['inline'=>true])->label(Yii::t('app', 'Minimal Jumlah Baris')) ?>
        &nbsp;<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
        <?php //echo $form->field($model,'Value4')->radioList(['Simple'=>Yii::t('app', 'Simple'),'Advance'=>Yii::t('app', 'Advance')], ['inline'=>true])->label(Yii::t('app', 'Entry Form Collection'))?>

    </div>


</div>
