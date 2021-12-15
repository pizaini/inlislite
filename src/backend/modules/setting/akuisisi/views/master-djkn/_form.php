<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;


use kartik\widgets\Select2;
/**
 * @var yii\web\View $this
 * @var common\models\MasterDjkn $model
 * @var yii\widgets\ActiveForm $form
 */


$option = array();
for ($i=1; $i <= 100 ; $i++) { 
	$option[$i] = $i;
}

?>

<div class="master-djkn-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); 

    echo '<div class="page-header">'. Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) . '</div>';


    echo Form::widget([

    	'model' => $model,
    	'form' => $form,
    	'columns' => 1,
    	'attributes' => [

    	// 'Option_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Option ID').'...', 'maxlength'=>20],'label'=>Yii::t('app','Option')], 

    	'Option_id'=>['type'=> Form::INPUT_WIDGET,'label'=> Yii::t('app', 'Option'),'widgetClass' => Select2::classname(), 'options'=>['data' => $option]], 

    	'Option_Name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Option  Name').'...', 'maxlength'=>100],'label'=>Yii::t('app','Name')], 

    	'Value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Value').'...', 'maxlength'=>100],'label'=>Yii::t('app','Nilai')], 

    	]


    ]);
    //echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
