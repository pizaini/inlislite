<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\Cardformats $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="cardformats-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); 

    echo '<div class="page-header">'. Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) . '</div>';

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

        'Name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nama').'...', 'maxlength'=>255,'style'=>'width:300px']], 

        'Width'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Width').'...', 'maxlength'=>3,'style'=>'width:150px']], 

        'Height'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Height').'...', 'maxlength'=>3,'style'=>'width:150px']], 

        'FormatTeks'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Format Teks').'...','rows'=> 6]], 

        'FormatTeksNoAuthor'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Format Teks No Author').'...','rows'=> 6]], 

        'FontName'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Font Name').'...', 'maxlength'=>45,'style'=>'width:300px']],  

        'FontSize'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Font Size').'...', 'maxlength'=>2,'style'=>'width:150px']], 

//'IsDelete'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Is Delete').'...']], 





        ]


        ]);
        ActiveForm::end(); ?>

</div>
