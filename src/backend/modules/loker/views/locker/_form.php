<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\Lockers $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="lockers-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'No_pinjaman'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'No Pinjaman').'...', 'maxlength'=>255]], 

'no_identitas'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'No Identitas').'...']], 

'no_member'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'No Member').'...']], 

'loker_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Loker ID').'...']], 

'tanggal_pinjam'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

'tanggal_kembali'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

'jenis_jaminan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Jenis Jaminan').'...', 'maxlength'=>20]], 

'id_jamin_uang'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Jaminan Uang ID').'...']], 

'id_jamin_idt'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Jaminan Jenis Identitas ID').'...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
