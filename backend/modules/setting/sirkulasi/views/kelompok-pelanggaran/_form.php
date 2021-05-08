<?php

use yii\helpers\Html;
use kartik\widgets\ColorInput;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\KelompokPelanggaran $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="kelompok-pelanggaran-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
    
    echo "<div class='page-header'>";
    echo '<p>'. Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) . '</p>';
    echo "</div>";

    echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

        'Name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nama').'...', 'maxlength'=>50]], 

        'Jumlah'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Jumlah').'...']], 

//'SuspendMember'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Suspend Member').'...']], 

//'Warna'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Warna').'...', 'maxlength'=>50]], 

        ]


        ]);
    
    echo $form->field($model, 'Warna')->widget(ColorInput::classname(), [
        'options' => ['placeholder' => 'Select color ...'],
        ]);
    echo $form->field($model, 'SuspendMember')->checkbox();
    

    ActiveForm::end(); ?>

</div>
