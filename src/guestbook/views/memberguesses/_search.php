<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model guestbook\models\MemberguessesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="memberguesses-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'NoAnggota') ?>

    <?= $form->field($model, 'Nama') ?>

    <?= $form->field($model, 'Status') ?>

    <?= $form->field($model, 'MasaBerlaku') ?>

    <?php // echo $form->field($model, 'Profesi') ?>

    <?php // echo $form->field($model, 'PendidikanTerakhir') ?>

    <?php // echo $form->field($model, 'JenisKelamin') ?>

    <?php // echo $form->field($model, 'Alamat') ?>

    <?php // echo $form->field($model, 'CreateBy') ?>

    <?php // echo $form->field($model, 'CreateDate') ?>

    <?php // echo $form->field($model, 'CreateTerminal') ?>

    <?php // echo $form->field($model, 'UpdateBy') ?>

    <?php // echo $form->field($model, 'UpdateDate') ?>

    <?php // echo $form->field($model, 'UpdateTerminal') ?>

    <?php // echo $form->field($model, 'Deskripsi') ?>

    <?php // echo $form->field($model, 'LOCATIONLOANS_ID') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
