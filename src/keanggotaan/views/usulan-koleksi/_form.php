<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;


use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;

use common\models\Worksheets;
/**
 * @var yii\web\View $this
 * @var common\models\Requestcatalog $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="requestcatalog-form">
<?php 
// echo '<pre>';print_r($model);die;

?>
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

Yii::t('app', 'noAnggota')=>['type'=> Form::INPUT_TEXT, 'options'=>[
	'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Member ID').'...', 
	'maxlength'=>50,
	'readonly'=>true,
    'value'=>Yii::$app->user->identity->NoAnggota,
	]
], 

Yii::t('app', 'WorksheetID')=>[
	'type'=> Form::INPUT_WIDGET, 
	'widgetClass'=>'\kartik\widgets\Select2',
	'options'=>[
		'data'=>ArrayHelper::map(Worksheets::find()->all(),'ID','Name'),
	], 
	
     /*'options'=>[
		'placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'Worksheet ID'),
     ],*/
     /*'pluginOptions' => [

          'allowClear' => true,
                                       //'width'=> '150px',
      ],*/
	//'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Worksheet ID').'...']
], 

'Title'=>[
    'type'=> Form::INPUT_TEXT, 
    'options'=>[
    'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Judul').'...', 
    'maxlength'=>255],
], 



//'DateRequest'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

//'Type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Type').'...', 'maxlength'=>50]], 


'Author'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Author').'...', 'maxlength'=>255]], 

'Publisher'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Publisher').'...', 'maxlength'=>50]], 

'PublishLocation'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Publish Location').'...', 'maxlength'=>255]], 

'PublishYear'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Publish Year').'...', 'maxlength'=>50]], 

'Comments'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Comments').'...','rows'=> 6]], 

//'CallNumber'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Call Number').'...', 'maxlength'=>50]], 

//'ControlNumber'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Control Number').'...', 'maxlength'=>50]], 

//'Subject'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Subject').'...', 'maxlength'=>255]], 

//'Status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Status').'...', 'maxlength'=>20]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
