<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\base\PeraturanPeminjamanHariSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="peraturan-peminjaman-hari-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'DayIndex') ?>

    <?= $form->field($model, 'CreateBy') ?>

    <?= $form->field($model, 'CreateDate') ?>

    <?= $form->field($model, 'CreateTerminal') ?>

    <?php // echo $form->field($model, 'UpdateBy') ?>

    <?php // echo $form->field($model, 'UpdateDate') ?>

    <?php // echo $form->field($model, 'UpdateTerminal') ?>

    <?php // echo $form->field($model, 'MaxPinjamKoleksi') ?>

    <?php // echo $form->field($model, 'MaxLoanDays') ?>

    <?php // echo $form->field($model, 'DendaTenorJumlah') ?>

    <?php // echo $form->field($model, 'DendaTenorSatuan') ?>

    <?php // echo $form->field($model, 'DendaPerTenor') ?>

    <?php // echo $form->field($model, 'DendaTenorMultiply') ?>

    <?php // echo $form->field($model, 'SuspendMember')->checkbox() ?>

    <?php // echo $form->field($model, 'WarningLoanDueDay') ?>

    <?php // echo $form->field($model, 'DaySuspend') ?>

    <?php // echo $form->field($model, 'DayPerpanjang') ?>

    <?php // echo $form->field($model, 'CountPerpanjang') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
