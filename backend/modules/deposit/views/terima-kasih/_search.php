<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\LetterSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="letter-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'TYPE_OF_DELIVERY') ?>

    <?= $form->field($model, 'LETTER_DATE') ?>

    <?= $form->field($model, 'LETTER_NUMBER') ?>

    <?= $form->field($model, 'ACCEPT_DATE') ?>

    <?php // echo $form->field($model, 'SENDER') ?>

    <?php // echo $form->field($model, 'PHONE') ?>

    <?php // echo $form->field($model, 'INTENDED_TO') ?>

    <?php // echo $form->field($model, 'IS_PRINTED') ?>

    <?php // echo $form->field($model, 'CreateDate') ?>

    <?php // echo $form->field($model, 'CreateBy') ?>

    <?php // echo $form->field($model, 'CreateTerminal') ?>

    <?php // echo $form->field($model, 'UpdateDate') ?>

    <?php // echo $form->field($model, 'UpdateBy') ?>

    <?php // echo $form->field($model, 'UpdateTerminal') ?>

    <?php // echo $form->field($model, 'PUBLISHER_ID') ?>

    <?php // echo $form->field($model, 'LETTER_NUMBER_UT') ?>

    <?php // echo $form->field($model, 'IS_SENDEDEMAIL') ?>

    <?php // echo $form->field($model, 'IS_NOTE') ?>

    <?php // echo $form->field($model, 'LANG') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
