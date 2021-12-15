<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\FieldSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="fields-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'Tag') ?>

    <?= $form->field($model, 'Name') ?>

    <?= $form->field($model, 'Fixed') ?>

    <?= $form->field($model, 'Enabled') ?>

    <?php // echo $form->field($model, 'Length') ?>

    <?php // echo $form->field($model, 'Repeatable') ?>

    <?php // echo $form->field($model, 'Mandatory') ?>

    <?php // echo $form->field($model, 'IsCustomable') ?>

    <?php // echo $form->field($model, 'IsDelete') ?>

    <?php // echo $form->field($model, 'Format_id') ?>

    <?php // echo $form->field($model, 'Group_id') ?>

    <?php // echo $form->field($model, 'CreateBy') ?>

    <?php // echo $form->field($model, 'CreateDate') ?>

    <?php // echo $form->field($model, 'CreateTerminal') ?>

    <?php // echo $form->field($model, 'UpdateBy') ?>

    <?php // echo $form->field($model, 'UpdateDate') ?>

    <?php // echo $form->field($model, 'UpdateTerminal') ?>

    <?php // echo $form->field($model, 'DEFAULTSUBTAG') ?>

    <?php // echo $form->field($model, 'ISSUBSERIAL')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
