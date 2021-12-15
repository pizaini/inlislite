

<?php 
use yii\bootstrap\Modal;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\Fields $model
 * @var yii\widgets\ActiveForm $form
 */
?>


<?php 
Modal::begin([
	'id' => $id,
    'header' =>'<h4>'.$header.'</h4>',
]);
?>


<?php 

$form = ActiveForm::begin([
'id'=>"form-$id",
'method'=>'post',
'enableClientValidation' => true,
'validateOnChange' => true,
'validateOnSubmit' => true,
'type'=>ActiveForm::TYPE_HORIZONTAL,
'formConfig' => ['deviceSize' => ActiveForm::SIZE_SMALL],
]); ?>

<div class="modal-body" >
	<?=$form->field($model,'[--n--]Code')->textInput(['style'=>'width:25%'])->label(Yii::t('app', 'Code'))?>
	<?=$form->field($model,'[--n--]Name')->textInput(['style'=>'width:85%'])->label(Yii::t('app', 'Name'))?>
</div>
<div class="modal-footer" >
	<?=Html::a(Yii::t('app', 'Save'), '#', 
		[
			'id' => "add-$id",
			'class' => 'btn btn-success',
			'onClick' => "js:$('#form-$id').trigger('submit');return false;",

		])?>
	<?=Html::a(Yii::t('app', 'Cancel'), 'javascript:void()', 
		[
			'id' => "cancel-$id",
			'class' => 'btn btn-warning',
			'data-dismiss' => 'modal'

		])?>
</div>

<?php
ActiveForm::end(); 
?>

<?php
$this->registerJs(
   '$("#form-'.$id.'").data("target","'.$targetGridId.'")'
);
$this->registerJs(
   '$("#form-'.$id.'").on("beforeSubmit", function (event, messages) {
	    js:validateIndikator("#form-'.$id.'");
	    $("#'.$id.'").modal("hide");
   		return false;
	});'
);

?>

<?php
Modal::end();
?>