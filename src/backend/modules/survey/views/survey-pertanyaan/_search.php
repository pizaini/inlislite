<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\SurveyPertanyaanSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="survey-pertanyaan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'Survey_id') ?>

    <?= $form->field($model, 'Pertanyaan') ?>

    <?= $form->field($model, 'JenisPertanyaan') ?>

    <?= $form->field($model, 'Orientation') ?>

    <?php // echo $form->field($model, 'IsMandatory')->checkbox() ?>

    <?php // echo $form->field($model, 'IsCanMultipleAnswer')->checkbox() ?>

    <?php // echo $form->field($model, 'NoUrut') ?>

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
