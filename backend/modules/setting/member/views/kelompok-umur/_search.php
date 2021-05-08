<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\MasterRangeUmurSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="master-range-umur-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'umur1') ?>

    <?= $form->field($model, 'umur2') ?>

    <?= $form->field($model, 'Keterangan') ?>

    <?= $form->field($model, 'CREATEBY') ?>

    <?php // echo $form->field($model, 'CreateDate') ?>

    <?php // echo $form->field($model, 'CreateTerminal') ?>

    <?php // echo $form->field($model, 'UpdateBy') ?>

    <?php // echo $form->field($model, 'UpdateDate') ?>

    <?php // echo $form->field($model, 'UpdateTerminal') ?>

    <?php // echo $form->field($model, 'TrashBy') ?>

    <?php // echo $form->field($model, 'TrashDate') ?>

    <?php // echo $form->field($model, 'TrashTerminal') ?>

    <?php // echo $form->field($model, 'IsDelete') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
