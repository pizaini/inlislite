<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model guestbook\models\Memberguesses */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="memberguesses-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'NoAnggota')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'MasaBerlaku')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Profesi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PendidikanTerakhir')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'JenisKelamin')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Alamat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CreateBy')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CreateDate')->textInput() ?>

    <?= $form->field($model, 'CreateTerminal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'UpdateBy')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'UpdateDate')->textInput() ?>

    <?= $form->field($model, 'UpdateTerminal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Deskripsi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'LOCATIONLOANS_ID')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
