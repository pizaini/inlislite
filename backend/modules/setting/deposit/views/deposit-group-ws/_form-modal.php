

<?php 
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\Fields $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<style>
.modal-open {

  overflow-y: auto;

}
</style>


<?php 

$form = ActiveForm::begin([
'id'=>"form-groupws-modal",
'method'=>'post',
'action' => ['save-ws'],
'enableAjaxValidation' => false,
'enableClientValidation' => true,
'type'=>ActiveForm::TYPE_HORIZONTAL,
'formConfig' => ['labelSpan'=>3,'deviceSize' => ActiveForm::SIZE_SMALL]
]); ?>

<div class="modal-header" >
<h4 class="modal-title">Nama Sumber</h4>
</div>
<div class="modal-body" >

	<?=$form->field($model,'id_group')->textInput([ 'placeholder' => 'Otomotatis by Sistem', 'disabled' => true])->label(Yii::t('app', 'Name Group'))?>
    <?=$form->field($model,'group_name')->textInput()->label(Yii::t('app', 'Name'))?>

</div>
<div class="modal-footer" >

	<?=Html::a(Yii::t('app', 'Save'), 'javascript:void(0)', 
		[
			'id' => "add-rekanan-modal",
			'class' => 'btn btn-success',
			'onClick' => '$("#form-groupws-modal").submit();',

		])?>

    <?=Html::a(Yii::t('app', 'Cancel'), 'javascript:void(0)', 
        [
            'id' => "cancel-rekanan-modal",
            'class' => 'btn btn-warning',
            'onClick' => '$("#rekanan-modal").modal("hide");',
        ])?>
</div>

<?php
ActiveForm::end(); 
?>


<script type="text/javascript">

$('#form-groupws-modal').on('beforeSubmit', function(event, jqXHR, settings) {
        var form = $(this);

        var id_group= $("#depositgroupws-id_name").val();
	    var group_name= $("#depositgroupws-group_name").val();

        if(form.find('.has-error').length) {
            return false;
        }

        isLoading = false;
        $.ajax({
            type     :"POST",
            cache    : false,
            url  : form.attr('action'),
            data : {"id_group":id_group, "group_name":group_name},
            success  : function(response) {

            $('#depositws-id_group_deposit_group_ws option:first').after($('<option />', { "value": response, text: group_name}));

                //$.pjax.reload({container:"#pjax-collection-partners-catcoll",url :"bind-catalogs-collection?id="+collid+"&catalogid="+catid, replace : false});

                    var element = document.getElementById("deposit-form");
                    element.classList.add("modal-open");
                    $("#rekanan-modal").modal("hide");
                    // document.getElementById("deposit-form").style.color = "blue";



            }
        });
        
        return false;
});
</script>

