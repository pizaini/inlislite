<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model guestbook\models\MembersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="members-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'MemberNo') ?>

    <?= $form->field($model, 'Fullname') ?>

    <?= $form->field($model, 'PlaceOfBirth') ?>

    <?= $form->field($model, 'DateOfBirth') ?>

    <?php // echo $form->field($model, 'Address') ?>

    <?php // echo $form->field($model, 'AddressNow') ?>

    <?php // echo $form->field($model, 'Phone') ?>

    <?php // echo $form->field($model, 'InstitutionName') ?>

    <?php // echo $form->field($model, 'InstitutionAddress') ?>

    <?php // echo $form->field($model, 'InstitutionPhone') ?>

    <?php // echo $form->field($model, 'IdentityType') ?>

    <?php // echo $form->field($model, 'IdentityNo') ?>

    <?php // echo $form->field($model, 'EducationLevel') ?>

    <?php // echo $form->field($model, 'Religion') ?>

    <?php // echo $form->field($model, 'Sex') ?>

    <?php // echo $form->field($model, 'MaritalStatus') ?>

    <?php // echo $form->field($model, 'JobName') ?>

    <?php // echo $form->field($model, 'RegisterDate') ?>

    <?php // echo $form->field($model, 'EndDate') ?>

    <?php // echo $form->field($model, 'BarCode') ?>

    <?php // echo $form->field($model, 'PicPath') ?>

    <?php // echo $form->field($model, 'MotherMaidenName') ?>

    <?php // echo $form->field($model, 'Email') ?>

    <?php // echo $form->field($model, 'JenisPermohonan') ?>

    <?php // echo $form->field($model, 'JenisPermohonanName') ?>

    <?php // echo $form->field($model, 'JenisAnggota') ?>

    <?php // echo $form->field($model, 'JenisAnggotaName') ?>

    <?php // echo $form->field($model, 'StatusAnggota') ?>

    <?php // echo $form->field($model, 'StatusAnggotaName') ?>

    <?php // echo $form->field($model, 'Handphone') ?>

    <?php // echo $form->field($model, 'ParentName') ?>

    <?php // echo $form->field($model, 'ParentAddress') ?>

    <?php // echo $form->field($model, 'ParentPhone') ?>

    <?php // echo $form->field($model, 'ParentHandphone') ?>

    <?php // echo $form->field($model, 'Nationality') ?>

    <?php // echo $form->field($model, 'LoanReturnLateCount') ?>

    <?php // echo $form->field($model, 'Branch_id') ?>

    <?php // echo $form->field($model, 'User_id') ?>

    <?php // echo $form->field($model, 'CreateBy') ?>

    <?php // echo $form->field($model, 'CreateDate') ?>

    <?php // echo $form->field($model, 'CreateTerminal') ?>

    <?php // echo $form->field($model, 'UpdateBy') ?>

    <?php // echo $form->field($model, 'UpdateDate') ?>

    <?php // echo $form->field($model, 'UpdateTerminal') ?>

    <?php // echo $form->field($model, 'AlamatDomisili') ?>

    <?php // echo $form->field($model, 'RT') ?>

    <?php // echo $form->field($model, 'RW') ?>

    <?php // echo $form->field($model, 'Kelurahan') ?>

    <?php // echo $form->field($model, 'Kecamatan') ?>

    <?php // echo $form->field($model, 'Kota') ?>

    <?php // echo $form->field($model, 'KodePos') ?>

    <?php // echo $form->field($model, 'NoHp') ?>

    <?php // echo $form->field($model, 'NamaDarurat') ?>

    <?php // echo $form->field($model, 'TelpDarurat') ?>

    <?php // echo $form->field($model, 'AlamatDarurat') ?>

    <?php // echo $form->field($model, 'StatusHubunganDarurat') ?>

    <?php // echo $form->field($model, 'City') ?>

    <?php // echo $form->field($model, 'Province') ?>

    <?php // echo $form->field($model, 'CityNow') ?>

    <?php // echo $form->field($model, 'ProvinceNow') ?>

    <?php // echo $form->field($model, 'JobNameDetail') ?>

    <?php // echo $form->field($model, 'namakelassiswa') ?>

    <?php // echo $form->field($model, 'tahunAjaran') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
