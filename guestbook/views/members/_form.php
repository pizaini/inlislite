<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model guestbook\models\Members */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="members-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'MemberNo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Fullname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PlaceOfBirth')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'DateOfBirth')->textInput() ?>

    <?= $form->field($model, 'Address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AddressNow')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'InstitutionName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'InstitutionAddress')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'InstitutionPhone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'IdentityType')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'IdentityNo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'EducationLevel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Religion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Sex')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'MaritalStatus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'JobName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'RegisterDate')->textInput() ?>

    <?= $form->field($model, 'EndDate')->textInput() ?>

    <?= $form->field($model, 'BarCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PicPath')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'MotherMaidenName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'JenisPermohonan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'JenisPermohonanName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'JenisAnggota')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'JenisAnggotaName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'StatusAnggota')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'StatusAnggotaName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Handphone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ParentName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ParentAddress')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ParentPhone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ParentHandphone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nationality')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'LoanReturnLateCount')->textInput() ?>

    <?= $form->field($model, 'Branch_id')->textInput() ?>

    <?= $form->field($model, 'User_id')->textInput() ?>

    <?= $form->field($model, 'CreateBy')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CreateDate')->textInput() ?>

    <?= $form->field($model, 'CreateTerminal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'UpdateBy')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'UpdateDate')->textInput() ?>

    <?= $form->field($model, 'UpdateTerminal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AlamatDomisili')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'RT')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'RW')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Kelurahan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Kecamatan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Kota')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'KodePos')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'NoHp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'NamaDarurat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TelpDarurat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AlamatDarurat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'StatusHubunganDarurat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'City')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Province')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CityNow')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ProvinceNow')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'JobNameDetail')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'namakelassiswa')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tahunAjaran')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
