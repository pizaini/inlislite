

<?php 
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\Fields $model
 * @var yii\widgets\ActiveForm $form
 */
?>



<?php 

$form = ActiveForm::begin([
'id'=>"form-rekanan-modal",
'method'=>'post',
'action' => ['save-partners'],
'enableAjaxValidation' => false,
'enableClientValidation' => true,
'type'=>ActiveForm::TYPE_HORIZONTAL,
'formConfig' => ['labelSpan'=>3,'deviceSize' => ActiveForm::SIZE_SMALL]
]); ?>

<div class="modal-header" >
<h4 class="modal-title">Nama Sumber</h4>
</div>
<div class="modal-body" >
	<?=$form->field($model,'ID')->hiddenInput()->label(false)?>
	<?=$form->field($model,'Name')->textInput()->label(Yii::t('app', 'Name'))?>
	<?=$form->field($model,'Address')->textInput()->label(Yii::t('app', 'Address'))?>
	<?=$form->field($model,'Phone')->textInput()->label(Yii::t('app', 'Phone'))?>
	<?=$form->field($model,'Fax')->textInput()->label(Yii::t('app', 'Fax'))?>
	<input id="hdnEditCatColl" type="hidden" value="<?=$edit?>">
	<input id="hdnStatusCatColl" type="hidden" value="<?=$catcoll?>">
</div>
<div class="modal-footer" >

<?php 
if($catcoll == 1)
{
	$onclickCancel="$('#rekanan-modal-catcoll').modal('hide');";
}else{
	$onclickCancel="$('#rekanan-modal').modal('hide');";
}
?>
	<?=Html::a(Yii::t('app', 'Save'), 'javascript:void(0)', 
		[
			'id' => "add-rekanan-modal",
			'class' => 'btn btn-success',
			'onClick' => '$("#form-rekanan-modal").submit();',

		])?>
	<?=Html::a(Yii::t('app', 'Cancel'), 'javascript:void(0)', 
		[
			'id' => "cancel-rekanan-modal",
			'class' => 'btn btn-warning',
			'onClick' => $onclickCancel,
		])?>
</div>

<?php
ActiveForm::end(); 
?>


<script type="text/javascript">
var reA = /[^a-zA-Z]/g;
var reN = /[^0-9]/g;
function sortAlphaNum(a,b) {
    var aA = a.innerHTML.toLowerCase().replace(reA, "");
    var bA = b.innerHTML.toLowerCase().replace(reA, "");
    if(aA === bA) {
        var aN = parseInt(a.innerHTML.toLowerCase().replace(reN, ""), 10);
        var bN = parseInt(b.innerHTML.toLowerCase().replace(reN, ""), 10);
        return aN === bN ? 0 : aN > bN ? 1 : -1;
    } else {
        return aA > bA ? 1 : -1;
    }
}

$('#form-rekanan-modal').on('beforeSubmit', function(event, jqXHR, settings) {
        var form = $(this);

        var ID= $("#partners-id").val();
	    var Name= $("#partners-name").val();
	    var Address= $("#partners-address").val();
	    var Phone= $("#partners-phone").val();
	    var Fax= $("#partners-fax").val();
	    var Edit = $("#hdnEditCatColl").val();
	    var StatusCatColl = $("#hdnStatusCatColl").val();

        if(form.find('.has-error').length) {
            return false;
        }

        isLoading = false;
        $.ajax({
            type     :"POST",
            cache    : false,
            url  : form.attr('action'),
            data : {"edit":Edit, "ID":ID, "Name":Name, "Address":Address, "Phone":Phone,"Fax":Fax },
            success  : function(response) {
                if(Edit==1)
                {
                  $("#collections-partner_id").find("option[value='"+response+"']").text(Name);
                }else{
                  $('#collections-partner_id option:first').after($('<option />', { "value": response, text: Name}));
                  $('#collections-partner_id option:not(:first)').sort(sortAlphaNum).appendTo('#collections-partner_id');
                }
                $("#collections-partner_id").find("option[value='"+response+"']").prop("selected", true);
                $(".form-group .field-collections-partner_id .col-sm-8 #collections-partner_id").select2("destroy");
                $(".form-group .field-collections-partner_id .col-sm-8 #collections-partner_id").select2({
                    theme: "krajee", width:"100%"
                });
                //$.pjax.reload({container:"#pjax-collection-partners-catcoll",url :"bind-catalogs-collection?id="+collid+"&catalogid="+catid, replace : false});
                if(StatusCatColl==1)
                {
                	$("#rekanan-modal-catcoll").modal("hide");
                }else{
                	$("#rekanan-modal").modal("hide");
                }
            }
        });
        
        return false;
});
</script>
