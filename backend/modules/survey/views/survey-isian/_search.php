<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\SurveyIsianSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="survey-isian-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'Survey_Pertanyaan_id') ?>

    <?= $form->field($model, 'Sesi') ?>

    <?= $form->field($model, 'MemberNo') ?>

    <?= $form->field($model, 'Isian') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
