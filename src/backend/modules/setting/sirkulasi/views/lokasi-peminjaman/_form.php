<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\LocationLibrary $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]); ?>
<div class="page-header">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']).' '.
     Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']);
    ?>
</div>
<div class="location-library-form">

    <?php
    echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'Code' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Kode') . '...', 'maxlength' => 50]],

            'Name' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Nama') . '...', 'maxlength' => 255]],

            'Address' => ['type' => Form::INPUT_TEXTAREA, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Address') . '...', 'maxlength' => 255]],

            //'KIILastUploadDate' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => DateControl::classname(), 'options' => ['type' => DateControl::FORMAT_DATETIME]],

        ]


    ]);
    ActiveForm::end(); ?>

</div>
