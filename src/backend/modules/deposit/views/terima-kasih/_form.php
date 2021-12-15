<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use common\models\DepositWs;

/**
 * @var yii\web\View $this
 * @var common\models\Letter $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="letter-form">

    <?php $form = ActiveForm::begin([ 'enableClientValidation' => true,
                'options'                => [
                    'id'      => 'dynamic-form'
                 ]]); 

    echo $form->field($model, 'PUBLISHER_ID')->widget('\kartik\widgets\select2',
    	['data'=>ArrayHelper::map(DepositWs::find()->all(),'ID',function($model) 
    		{return $model['nama_penerbit'];
		    }),
          'pluginOptions' => [
              'allowClear' => true,
          ],])->label('Penerbit');
   	echo $form->field($model, 'TYPE_OF_DELIVERY')->widget('\kartik\widgets\select2',[
                  'data'=>array('DL'=>'Datang Langsung','P'=>'Pos'),'pluginOptions'=>['allowClear'=>true,],])->label('Jenis Pengiriman');
    echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [



	// 'PUBLISHER_ID'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Publisher  ID').'...']], 

	// 'TYPE_OF_DELIVERY'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Type  Of  Delivery').'...', 'maxlength'=>21]], 

	'LETTER_DATE'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE],'label'=>'Tanggal Surat'], 

	'LETTER_NUMBER'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Letter  Number').'...', 'maxlength'=>35],'label'=>'Nomor Surat'], 

	'ACCEPT_DATE'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE],'label'=>'Tanggal Terima'], 
	
	'SENDER'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Sender').'...', 'maxlength'=>155],'label'=>'Pengirim	'], 

	'PHONE'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Phone').'...'],'label'=>'No.Telp'], 

	'INTENDED_TO'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Intended  To').'...', 'maxlength'=>155],'label'=>'Ditujukan Kepada'], 

	// 'IS_PRINTED'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Is  Printed').'...']], 
	// Html::activeCheckbox($model,'IS_PRINTED',['label'=> yii::t('app','Status')]),

	// 'IS_SENDEDEMAIL'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Is  Sendedemail').'...']], 

	// 'IS_NOTE'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Is  Note').'...']], 

	// 'LETTER_NUMBER_UT'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Letter  Number  Ut').'...', 'maxlength'=>45]], 

	// 'LANG'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Lang').'...', 'maxlength'=>20]], 

    ]


    ]);
    // echo Html::activeCheckbox($model,'IS_PRINTED',['label'=> yii::t('app','Cetak UT')]);
    // echo Html::activeCheckbox($model,'IS_NOTE',['label'=> yii::t('app','Catatan')]);
    echo $form->field($model, 'IS_PRINTED')->checkbox();

    // echo $form->field($model, 'IS_NOTE')->checkbox();
    

    echo $form->field($model, 'LANG')->widget('\kartik\widgets\select2',[
                  'data'=>array('IND'=>'Indonesia','EN'=>'English'),'pluginOptions'=>['allowClear'=>true,],])->label('Bahasa');
    // echo Html::a(Yii::t('app', 'Tambah Detail'), 'javascript:void(0)', ['id'=>'btnAddPartners','onclick'=>'js:AddPartners();','class' => 'btn bg-yellow btn-md']);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);echo '&nbsp;';

    echo Html::a(Yii::t('app', 'Kembali'), ['./terima-kasih'], ['class' => 'btn bg-yellow btn-md']); echo '&nbsp;';

    if(!$model->isNewRecord){
    	echo Html::a(Yii::t('app', 'Tambah Detail'), ['letter-detail/create','letter_id'=>$letter_id], ['class' => 'btn bg-green btn-md','data-toggle'=>"modal",'data-target'=>"#deposit-form"]); echo '&nbsp;';
	}
	?>
	
	<div class="modal remote fade" id="deposit-form" style="overflow-y: auto !important;">
	        <div class="modal-dialog" style="width:700px;">
	            <div class="modal-content loader-lg"></div>
	        </div>
	</div>
</div>
  	<?php
	Modal::begin(['id' => 'rekanan-modal','options'=>[
	  'style'=>['z-index'=>9999],
	  'data-backdrop'=>'static'
	]]);
	echo "<div id='modalPartners'></div>";
	Modal::end();
	?>
	<?php ActiveForm::end(); ?>

