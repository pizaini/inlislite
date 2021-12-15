<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\MemberguessesSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="memberguesses-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'NoAnggota') ?>

    <?= $form->field($model, 'Nama') ?>

    <?= $form->field($model, 'Status_id') ?>

    <?= $form->field($model, 'MasaBerlaku_id') ?>

    <?php // echo $form->field($model, 'Profesi_id') ?>

    <?php // echo $form->field($model, 'PendidikanTerakhir_id') ?>

    <?php // echo $form->field($model, 'JenisKelamin_id') ?>

    <?php // echo $form->field($model, 'Alamat') ?>

    <?php // echo $form->field($model, 'CreateBy') ?>

    <?php // echo $form->field($model, 'CreateDate') ?>

    <?php // echo $form->field($model, 'CreateTerminal') ?>

    <?php // echo $form->field($model, 'UpdateBy') ?>

    <?php // echo $form->field($model, 'UpdateDate') ?>

    <?php // echo $form->field($model, 'UpdateTerminal') ?>

    <?php // echo $form->field($model, 'Deskripsi') ?>

    <?php // echo $form->field($model, 'LOCATIONLOANS_ID') ?>

    <?php // echo $form->field($model, 'Location_Id') ?>

    <?php // echo $form->field($model, 'TujuanKunjungan_Id') ?>

    <?php // echo $form->field($model, 'Information') ?>

    <?php // echo $form->field($model, 'NoPengunjung') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
