<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\grid\GridView;
use yii\widgets\Pjax;


use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use common\widgets\AjaxButton;

/**
 * @var yii\web\View $this
 * @var common\models\PengirimanKoleksi $model
 * @var yii\widgets\ActiveForm $form
 */

$url2           = Url::to('view-koleksi');
$ajaxOptions    = [
                    'type' => 'POST',
                    'url'  => $url2,
                    'data' => array(
                            'NOBARCODE' =>  new yii\web\JsExpression('$("#dynamicmodel-nobarcode").val()'),
                        ),
                    'success'=>new yii\web\JsExpression('
                    	function(data){
                    	// alert($("#dynamicmodel-nobarcode").val()) 
                    	$("#koleksi-item").html(data); 
                    	$("#dynamicmodel-nobarcode").val("");
                    	$("#dynamicmodel-nobarcode").focus(); 
                    	$("#btn-simpan").prop("disabled", false);
                    	$("#btn-batal").show();
                    }'
                    ),
                   
                   'error' => new yii\web\JsExpression('function(xhr, ajaxOptions, thrownError){ 
                   					// alert(xhr.responseText); 
									var str = xhr.responseText;
									var message = str.replace("Not Found (#404):","");

                   					$("#parent-warning-barcode").show();
                   					$("#warning-scanbarcode").html(message);
                   					$("#dynamicmodel-nobarcode").val("");
                   					$("#dynamicmodel-nobarcode").focus();
                   					//$("#btn-simpan").prop("disabled", true);
                   				}'),
                ];
?>

<div class="col-md-12">
	<div class="page-header">
		<h3>
			&nbsp;
			<div class="pull-left">
				<?php
				echo '<p>';
				echo Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> '.Yii::t('app', 'Create') , ['class' => 'btn btn-success','id'=>'btn-simpan']);
				echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-remove"></span> '. Yii::t('app', 'Batal'), [], ['class' => 'btn btn-danger','id'=>'btn-batal','style'=>'display: none;']);
				echo '</p>';
				?>
			</div>
		</h3>
	</div>

	<!-- KOLEKSI-AREA -->
	<div style="background-image: linear-gradient(to bottom, #59bedc, #0978c5);
	     color:#fff;padding:0px 0px 10px 20px;border-radius:3px;">
		<div class="content_edit" id="koleksi-area">
			<table border="0">
			    <tr>
			        <td valign="top" class='icon-users'>&nbsp;&nbsp;
			        	<div class="input-group">
			        		<?= Html::activeTextInput($model,'NOBARCODE',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').'  No.'.Yii::t('app', ' Barcode').'']); ?>
			        		<div class="input-group-btn" >
			        			<?php
							            echo AjaxButton::widget([
							                'label' => '<i class="glyphicon glyphicon-search"></i> ' .Yii::t('app','Cari'),
							                'ajaxOptions' => $ajaxOptions,
							                'htmlOptions' => [
							                    'class' => 'btn btn-default',
							                    'id' => 'cari',
							                    'type' => 'submit'
							                ]
							            ]);
					            ?>    
			        			
			        		</div><!-- /btn-group -->
			        	</div>
			        	<div class="hint-block col-sm-9"></div>
			        </td>
			        <td valign="top" style="padding-top: 18px; padding-left: 10px">
			           
			        
			        <?php 
			            //echo Html::submitButton(Yii::t('app', 'Cari Item') , ['class' => 'btn btn-warning']);?>
			        </td>
			        <td width="5%">&nbsp;</td>
			        <td valign="top" class='icon-book'>&nbsp;&nbsp;<?php //CHtml::textField("TxtNoItem","",array('id'=>'TxtNoItem','placeholder'=>'Barcode Koleksi')); ?></td>
			        <td valign="top"><?//CHtml::ajaxButton("Cari Koleksi", $url2, $ajaxOptions2, $htmlButton2); ?></td>
			        <td style="display: none;">&nbsp;&nbsp;&nbsp;<a href="#" id="ShowPopUp">List Koleksi Yang Dipinjam</a></td>
			    </tr>
			</table>
		</div>
	</div>
	<!-- /.KOLEKSI-AREA -->
	<!-- HINT - ALERT -->
	<div id="parent-warning-barcode" hidden="hidden" class="callout callout-warning callout-dismissible"  style="margin-bottom: 0!important;">
		<span id="warning-scanbarcode">Warning nanti disini</span>
	</div>
	<!-- /.HINT - ALERT -->
	<br>
	<div id="koleksi-item"></div>
</div>



<?php
$HapusOk        = '$("#ErrorMsg").hide();$("#DataList").html("");$("#DataList").html(data);$("#DivKoleksi").html("");$("#TxtNoItem2").focus();$("#TxtNoItem2").val("");'; 
$HapusItemUrl      = Url::to('hapus-item');
$token          = Yii::$app->request->csrfToken;
$this->registerJs("
	
	$('#btn-simpan').prop('disabled', true);

	$('#dynamicmodel-nobarcode').focus();
	
	
	$('#dynamicmodel-nobarcode').keydown(function(event){
        if(event.keyCode == 13) {
			

			if($('#dynamicmodel-nobarcode').val() == ''){
				// alert('Nomor Barcode tidak boleh kosong.');
				$('#parent-warning-barcode').show();
				$('#warning-scanbarcode').html('Nomor Barcode tidak boleh kosong.');

				$('#peminjamanitemform-nobarcode').focus();
			}else{
				$('#cari').click();
			}
		}
	});


	// Disable Enter Submit Form
	$(document).on('keyup keypress', 'form input[type=\"text\"]', function(e) {
	  if(e.which == 13) {
	    e.preventDefault();
	    return false;
	  }
	});
	
	
	
	
");

?>

<script>
  $('#btn-simpan').click(function(){

      var arrayPengiriman = [];
      var inputs = document.querySelectorAll("input[name='PengirimanKoleksi[]']");
      // alert(inputs.length)
      for (i = 0; i <= inputs.length + 1; i++) {

        arrayPengiriman.push({
          CollectionID : $('#colid-'+i).val(),
          NomorBarcode : $('#nobarcode-'+i).val(),
          NoInduk : $('#noinduk-'+i).val(),
          CallNumber : $('#callnumber-'+i).val(),
          Judul : $('#title-'+i).val(),
          TahunTerbit : $('#tahunterbit-'+i).val(),
          Quantity : $('#quantity-'+i).val(),
          TanggalKirim : $('#tanggalkirim-'+i).val(),
        });
      }

      $.ajax({
        url : 'create',
        type : 'POST',
        dataType : 'JSON',
        data : {PengirimanKoleksi : arrayPengiriman},
        success: function(hasil){
        	console.log(hasil)
          	swal({
          		icon: "success",
			  	title: "Berhasil Disimpan",
			});
			location.reload();
        }
      })
      return false;
  });
</script>
