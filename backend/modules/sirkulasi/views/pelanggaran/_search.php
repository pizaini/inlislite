<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\PelanggaranSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="pelanggaran-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'CollectionLoan_id') ?>

    <?= $form->field($model, 'CollectionLoanItem_id') ?>

    <?= $form->field($model, 'JenisPelanggaran_id') ?>

    <?= $form->field($model, 'JenisDenda_id') ?>

    <?php // echo $form->field($model, 'JumlahDenda') ?>

    <?php // echo $form->field($model, 'CreateBy') ?>

    <?php // echo $form->field($model, 'CreateDate') ?>

    <?php // echo $form->field($model, 'CreateTerminal') ?>

    <?php // echo $form->field($model, 'UpdateBy') ?>

    <?php // echo $form->field($model, 'UpdateDate') ?>

    <?php // echo $form->field($model, 'UpdateTerminal') ?>

    <?php // echo $form->field($model, 'JumlahSuspend') ?>

    <?php // echo $form->field($model, 'Paid')->checkbox() ?>

    <?php // echo $form->field($model, 'Member_id') ?>

    <?php // echo $form->field($model, 'Collection_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
