<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\builder\TabularForm;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;


/**
 * @var yii\web\View $this
 * @var common\models\Survey $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="survey-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); 
    echo '<div class="page-header">';
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Simpan') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo '&nbsp;'.Html::a(Yii::t('app', 'Kembali'), 'index',['class' => 'btn btn-warning' ]);
    echo '</div>';

    echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

		'NamaSurvey'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nama Survey').'...', 'maxlength'=>200]], 

		//'TanggalMulai'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 
		'TglMulai'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DatePicker::classname(),'options'=>['pluginOptions' => [
                                'format' => 'dd-mm-yyyy',
                                'autoclose' => true,
                                'todayHighlight' => true,
                            ]], ], 

		// 'TanggalMulai'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateTimePicker::classname(),'options'=>['pluginOptions' => [
  //                               'format' => 'yyyy-mm-dd hh:ii:ss',
  //                               'autoclose' => true,
  //                               'todayHighlight' => true,
  //                           ]], ], 

		//'TanggalSelesai'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],
		'TglSelesai'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DatePicker::classname(),'options'=>['pluginOptions' => [
                                'autoclose' => true,
                                'todayHighlight' => true,
                                'format' => 'dd-mm-yyyy',
                            ]],], 


		//'IsActive'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Ya/Tidak').'...']], 

		'IsActive'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>[array('label'=>'asd')],'label'=>yii::t('app','Aktif')], 


		// ]]); 
	
		// <div class="row">
		// 	<div class="col-sm-12">
		// 		php $form->field($model, 'IsActive')->checkbox(array('label'=>'Ya/Tidak'),['class'=>'col-md-12'])->label('Aktif'); ?
		// 	</div>
		// </div>


	
		// echo Form::widget([

		// 	'model' => $model,
		// 	'form' => $form,
		// 	'columns' => 1,
		// 	'attributes' => [

		//'TargetSurvey'=>['type' => Form::INPUT_WIDGET', widgetClass'=>Select2::classname(), 'options'=>['data' => [1 => 'Anggota',2 => 'Semua'],'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Responden').'...'], 'label'=>'Responden'], 

		'NomorUrut'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nomor Urut').'...']], 

		//'TargetSurvey'=>['type'=> Form::INPUT_DROPDOWN_LIST,'items'=>['1'=>'Anggota','2'=>'Semua'], 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Responden').'...'], 'label'=>'Responden'], 
		//'TargetSurvey'=>['type' => Form::INPUT_WIDGET', widgetClass'=>Select2::classname(), 'options'=>['data' => [1 => 'Anggota',2 => 'Semua']], 'label'=>'Responden'], 
		'TargetSurvey'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>Select2::classname(),'options'=>['data' => [1 => 'Anggota',0 => 'Semua'], ],'label'=>'Responden'],
		

		'HasilSurveyShow'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>Select2::classname(),'options'=>['data' => [1 => 'Back Office dan Module Survey',0 => 'Back Office'], ],'label'=>yii::t('app','Perlihatkan Hasil Survey')],
		 
		//'TargetSurvey'=>['type'=> TabularForm::INPUT_DROPDOWN_LIST, 'widgetClass'=>Select2::classname(), 'items'=>['1'=>'Anggota','2'=>'Semua'], 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Responden').'...'], 'label'=>'Responden'], 

		//'HasilSurveyShow'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Hasil Survey Show').'...']], 

		'RedaksiAwal'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Redaksi Awal').'...','rows'=> 6]], 

		'RedaksiAkhir'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Redaksi Akhir').'...','rows'=> 6]], 

		'Keterangan'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Keterangan').'...', 'maxlength'=>255]], 

    ]


    ]);
    // echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    // echo Html::a('Kembali', Yii::$app->request->referrer,['class' => 'btn btn-warning pull-right','data-pjax'=>'0', ]);
    
    ActiveForm::end(); ?>

</div>

<!-- HISTORY -->
<?php
    echo \common\widgets\Histori::widget([
            'model'=>$model,
            'id'=>'member',
            'urlHistori'=>'detail-histori?id='.$model->ID
        
    ]);
?>


<?php
$this->registerJs("
    $('#survey-tanggalmulai-disp').val($('#survey-tanggalmulai').val());

    $('#survey-tanggalselesai-disp').val($('#survey-tanggalselesai').val());
");



?>

