<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

use common\models\MasterJurusan;

/**
 * @var yii\web\View $this
 * @var common\models\MasterProgramStudi $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="master-program-studi-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); 

    echo '<div class="page-header">';
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

	if (!$model->isNewRecord) {
	 echo ' '.Html::a(Yii::t('app', 'Delete'), Yii::$app->urlManager->createUrl(['setting/member/program-studi/delete','id' => $model->id,'edit'=>'t']), [
    	'title' => Yii::t('app', 'Delete'),
    	'class' => 'btn btn-danger ',
    	'data' => [
    	'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
    	'method' => 'post',
    	],
    	]);
	} 
	
   

    echo '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) . '</div>';


    echo Form::widget([

    	'model' => $model,
    	'form' => $form,
    	'columns' => 1,
    	'attributes' => [

    	//'id_jurusan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Id Jurusan').'...']], 


    	'id_jurusan'=>['type'=> Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2', 'options'=>['data'=>ArrayHelper::map(MasterJurusan::find()->all(), 'id', 'Nama'),],'label'=>Yii::t('app','Jurusan')], 


    	'Nama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nama').'...', 'maxlength'=>255]], 

    	//'KIILastUploadDate'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

    	]


    	]);

    	?>


    	
	    
	    <?php
	    ActiveForm::end(); ?>

</div>
