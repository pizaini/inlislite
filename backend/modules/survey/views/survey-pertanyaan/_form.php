<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;


/**
 * @var yii\web\View $this
 * @var common\models\SurveyPertanyaan $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="survey-pertanyaan-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); 

    echo '<div class="page-header">';
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo '&nbsp;'.Html::a('Kembali', Yii::$app->request->referrer,['class' => 'btn btn-warning' ]);
    echo '</div>';
    
    echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

//'Survey_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Survey ID').'...']], 

    'Pertanyaan'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Pertanyaan').'...','rows'=> 6]], 


    'IsMandatory'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Is Mandatory').'...'],'label'=>'Wajib diisi'], 

    'IsCanMultipleAnswer'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Is Can Multiple Answer').'...'],'label'=>'Jawaban bisa lebih dari satu'], 

//'JenisPertanyaan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Jenis Pertanyaan').'...', 'maxlength'=>20]], 
    'JenisPertanyaan'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>Select2::classname(),'options'=>['data' => ['Pilihan' => 'Pilihan Ganda','Isian' => 'Isian Bebas'], ]],

//'Orientation'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Orientation').'...', 'maxlength'=>20],'label'=>'Orientasi'], 
    'Orientation'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>Select2::classname(),'options'=>['data' => ['Vertikal' => 'Vertikal','Horisontal' => 'Horisontal'], ]],

    'NoUrut'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'No Urut').'...']], 
    ]


    ]);
    // echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    // echo Html::a('Kembali', Yii::$app->request->referrer,['class' => 'btn btn-warning pull-right','data-pjax'=>'0', ]);
    ActiveForm::end(); ?>

</div>
