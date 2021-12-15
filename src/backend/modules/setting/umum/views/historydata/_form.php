<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Historydata */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="historydata-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Action')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TableName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'IDRef')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Note')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'CreateBy')->textInput() ?>

    <?= $form->field($model, 'CreateDate')->textInput() ?>

    <?= $form->field($model, 'CreateTerminal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'UpdateBy')->textInput() ?>

    <?= $form->field($model, 'UpdateDate')->textInput() ?>

    <?= $form->field($model, 'UpdateTerminal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Member_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
