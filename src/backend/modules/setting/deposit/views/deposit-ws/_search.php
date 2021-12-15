<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\DepositWsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deposit-ws-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'jenis_penerbit') ?>

    <?= $form->field($model, 'id_deposit_group_penerbit_ws') ?>

    <?= $form->field($model, 'id_deposit_kelompok_penerbit_ws') ?>

    <?= $form->field($model, 'nama_penerbit') ?>

    <?php // echo $form->field($model, 'alamat1') ?>

    <?php // echo $form->field($model, 'alamat2') ?>

    <?php // echo $form->field($model, 'alamat3') ?>

    <?php // echo $form->field($model, 'kabupaten') ?>

    <?php // echo $form->field($model, 'id_wilayah_ws') ?>

    <?php // echo $form->field($model, 'kode_pos') ?>

    <?php // echo $form->field($model, 'no_telp1') ?>

    <?php // echo $form->field($model, 'no_telp2') ?>

    <?php // echo $form->field($model, 'no_telp3') ?>

    <?php // echo $form->field($model, 'no_fax') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'contact_person') ?>

    <?php // echo $form->field($model, 'no_contact') ?>

    <?php // echo $form->field($model, 'koleksi_per_tahun') ?>

    <?php // echo $form->field($model, 'keterangan') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
