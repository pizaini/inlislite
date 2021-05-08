<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\color\ColorInput;

/**
 * @var yii\web\View $this
 * @var common\models\Warnaddc $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="warnaddc-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
    echo '<div class="page-header">'. Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo  '&nbsp;' . Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-warning']) . '</div>';

     echo '<p>'.$form->field($model, 'KodeDDC')->textInput(['style'=>'width:210px'], ['inline'=>true])->label(Yii::t('app', 'KodeDDC'));

     echo '<p>'.$form->field($model, 'Warna')->widget(ColorInput::classname(), ['options' => ['style'=>'width:150px','placeholder' => Yii::t('app', 'Select color ').'...'],
]);


    ActiveForm::end(); ?>

</div>
