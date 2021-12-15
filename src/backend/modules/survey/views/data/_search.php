<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\SurveySearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="survey-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'NamaSurvey') ?>

    <?= $form->field($model, 'TanggalMulai') ?>

    <?= $form->field($model, 'TanggalSelesai') ?>

    <?= $form->field($model, 'Ya/Tidak')->checkbox() ?>

    <?php // echo $form->field($model, 'NomorUrut') ?>

    <?php // echo $form->field($model, 'TargetSurvey') ?>

    <?php // echo $form->field($model, 'HasilSurveyShow') ?>

    <?php // echo $form->field($model, 'RedaksiAwal') ?>

    <?php // echo $form->field($model, 'RedaksiAkhir') ?>

    <?php // echo $form->field($model, 'Keterangan') ?>

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
