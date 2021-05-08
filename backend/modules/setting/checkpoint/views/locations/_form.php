<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;

use common\models\LocationLibrary;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var common\models\Locations $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="locations-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); 





    echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

	'LocationLibrary_id'=>['type'=> Form::INPUT_WIDGET,'widgetClass' => Select2::classname(), 'options'=>['data' => ArrayHelper::map(LocationLibrary::find()->all(),'ID','Name')]], 

	'Code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Kode').'...', 'maxlength'=>255]], 

	'Name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nama').'...', 'maxlength'=>255]], 

	'Description'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Deskripsi').'...', 'maxlength'=>255]],

	'ISPUSTELING'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Apakah lokasi pusteling').'...']], 

	'IsVisitsDestination'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Tampilkan ruas maksud kunjungan').'...']], 

	'IsInformationSought'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Tampilkan ruas informasi yang dicari').'...']], 

	'IsGenerateVisitorNumber'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Tampilkan nomor pengunjung').'...']], 

	'IsPrintBarcode'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Aktifkan cetak no. pengunjung').'...']],  


    ]


    ]);

	// 'UrlLogo'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'URL Logo Lokasi').'...', 'maxlength'=>100]], 
	// Usage with ActiveForm and model
	if ($model->isNewRecord) {
		echo $form->field($model, 'UrlLogo')->widget(FileInput::classname(), [
		    'options' => ['accept' => 'image/*'],
		]);
	} else {
		/*echo $form->field($model, 'UrlLogo')->widget(FileInput::classname(), [
		    'options' => ['accept' => 'image/*'],
		    'pluginOptions' => [
		        'overwriteInitial'=>false,
		        'uploadUrl' => Url::to(['/setting/checkpoint/locations/file-upload'])
		    ]	
		]);*/
		echo FileInput::widget([
		    'name' => 'UrlLogo',
		    'pluginOptions' => [
		        'uploadUrl' => Url::to(['/setting/checkpoint/locations/file-upload','id'=>$model['ID']]),
		    ]
		]);
	}

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo ' '.Html::a(Yii::t('app', 'Kembali') , $url= '../locations' , ['class' =>'btn btn-warning']);
   
    ActiveForm::end(); ?>

</div>
