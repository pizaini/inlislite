<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\PengirimanKoleksiSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="pengiriman-koleksi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'BIBID') ?>

    <?= $form->field($model, 'JUDUL') ?>

    <?= $form->field($model, 'TAHUNTERBIT') ?>

    <?= $form->field($model, 'CALLNUMBER') ?>

    <?php // echo $form->field($model, 'NOBARCODE') ?>

    <?php // echo $form->field($model, 'NOINDUK') ?>

    <?php // echo $form->field($model, 'QUANTITY') ?>

    <?php // echo $form->field($model, 'TANGGALKIRIM') ?>

    <?php // echo $form->field($model, 'CreateBy') ?>

    <?php // echo $form->field($model, 'CreateDate') ?>

    <?php // echo $form->field($model, 'CreateTerminal') ?>

    <?php // echo $form->field($model, 'UpdateBy') ?>

    <?php // echo $form->field($model, 'UpdateDate') ?>

    <?php // echo $form->field($model, 'UpdateTerminal') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
