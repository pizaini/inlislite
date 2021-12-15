<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\MemberSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>


<div class="members-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'MemberNo') ?>

    <?= $form->field($model, 'Fullname') ?>

    <?= $form->field($model, 'PlaceOfBirth') ?>

    <?= $form->field($model, 'DateOfBirth') ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
