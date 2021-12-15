<?php

use yii\helpers\Html;
use kartik\widgets\ColorInput;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\Library $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="library-form">
<div class="col-xs-6 col-sm-6">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]]); 

    echo '<div class="page-header">'. Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) . '</div>';

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

        'kdKelas'=>['type'=> Form::INPUT_TEXT, 'options'=>['label'=>'asdasdas','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Kode Kelas').'...', 'maxlength'=>3]], 

        'namakelas'=>['type'=> Form::INPUT_TEXT, 'options'=>['style'=>'','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nama Kelas').'...', 'maxlength'=>255]],     
        
        ]


    ]);
    echo $form->field($model, 'warna')->widget(ColorInput::classname(), [
        'options' => ['placeholder' => 'Select color ...'],
        ]);

    ActiveForm::end(); ?>
</div>
</div>
