<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\LockersSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="lockers-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'No_pinjaman') ?>

    <?= $form->field($model, 'no_member') ?>

    <?= $form->field($model, 'no_identitas') ?>

    <?= $form->field($model, 'jenis_jaminan') ?>

    <?php // echo $form->field($model, 'id_jamin_idt') ?>

    <?php // echo $form->field($model, 'id_jamin_uang') ?>

    <?php // echo $form->field($model, 'loker_id') ?>

    <?php // echo $form->field($model, 'tanggal_pinjam') ?>

    <?php // echo $form->field($model, 'tanggal_kembali') ?>

    <?php // echo $form->field($model, 'CreateBy') ?>

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
