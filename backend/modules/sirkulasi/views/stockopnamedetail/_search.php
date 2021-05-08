<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\StockopnamedetailSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="stockopnamedetail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'StockOpnameID') ?>

    <?= $form->field($model, 'CollectionID') ?>

    <?= $form->field($model, 'PrevLocationID') ?>

    <?= $form->field($model, 'CurrentLocationID') ?>

    <?php // echo $form->field($model, 'PrevStatusID') ?>

    <?php // echo $form->field($model, 'CurrentStatusID') ?>

    <?php // echo $form->field($model, 'PrevCollectionRuleID') ?>

    <?php // echo $form->field($model, 'CurrentCollectionRuleID') ?>

    <?php // echo $form->field($model, 'CreateBy') ?>

    <?php // echo $form->field($model, 'CreateDate') ?>

    <?php // echo $form->field($model, 'CreateTerminal') ?>

    <?php // echo $form->field($model, 'UpdateBy') ?>

    <?php // echo $form->field($model, 'UpdateDate') ?>

    <?php // echo $form->field($model, 'UpdateTerminal') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
