<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\MasterPelanggaranLockerSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="master-pelanggaran-locker-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'jenis_pelanggaran') ?>

    <?= $form->field($model, 'denda') ?>

    <?= $form->field($model, 'deskripsi') ?>

    <?= $form->field($model, 'CreateBy') ?>

    <?php // echo $form->field($model, 'CreateDate') ?>

    <?php // echo $form->field($model, 'CreateTerminal') ?>

    <?php // echo $form->field($model, 'UpdateBy') ?>

    <?php // echo $form->field($model, 'UpdateDate') ?>

    <?php // echo $form->field($model, 'UpdateTerminal') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
