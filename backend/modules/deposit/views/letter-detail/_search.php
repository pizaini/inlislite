<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\LetterDetailSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="letter-detail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'LETTER_DETAIL_ID') ?>

    <?= $form->field($model, 'SUB_TYPE_COLLECTION') ?>

    <?= $form->field($model, 'TITLE') ?>

    <?= $form->field($model, 'QUANTITY') ?>

    <?= $form->field($model, 'COPY') ?>

    <?php // echo $form->field($model, 'PRICE') ?>

    <?php // echo $form->field($model, 'LETTER_ID') ?>

    <?php // echo $form->field($model, 'COLLECTION_TYPE_ID') ?>

    <?php // echo $form->field($model, 'REMARK') ?>

    <?php // echo $form->field($model, 'AUTHOR') ?>

    <?php // echo $form->field($model, 'PUBLISHER') ?>

    <?php // echo $form->field($model, 'PUBLISHER_ADDRESS') ?>

    <?php // echo $form->field($model, 'ISBN') ?>

    <?php // echo $form->field($model, 'PUBLISH_YEAR') ?>

    <?php // echo $form->field($model, 'PUBLISHER_CITY') ?>

    <?php // echo $form->field($model, 'ISBN_STATUS') ?>

    <?php // echo $form->field($model, 'KD_PENERBIT_DTL') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
