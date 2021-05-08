<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\RequestcatalogSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="requestcatalog-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'Type') ?>

    <?= $form->field($model, 'Title') ?>

    <?= $form->field($model, 'Subject') ?>

    <?= $form->field($model, 'Author') ?>

    <?php // echo $form->field($model, 'PublishLocation') ?>

    <?php // echo $form->field($model, 'PublishYear') ?>

    <?php // echo $form->field($model, 'Publisher') ?>

    <?php // echo $form->field($model, 'Comments') ?>

    <?php // echo $form->field($model, 'MemberID') ?>

    <?php // echo $form->field($model, 'CallNumber') ?>

    <?php // echo $form->field($model, 'ControlNumber') ?>

    <?php // echo $form->field($model, 'DateRequest') ?>

    <?php // echo $form->field($model, 'Status') ?>

    <?php // echo $form->field($model, 'CreateBy') ?>

    <?php // echo $form->field($model, 'CreateDate') ?>

    <?php // echo $form->field($model, 'CreateTerminal') ?>

    <?php // echo $form->field($model, 'UpdateBy') ?>

    <?php // echo $form->field($model, 'UpdateDate') ?>

    <?php // echo $form->field($model, 'UpdateTerminal') ?>

    <?php // echo $form->field($model, 'WorksheetID') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
