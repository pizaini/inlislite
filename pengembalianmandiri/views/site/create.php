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
 * @var common\models\Collectionloanitems $model
 */
$this->title = Yii::t('app', 'Entri Pengembalian');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sirkulasi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$form 			= ActiveForm::begin(
				    [
				    	'action'=> Yii::$app->urlManager->createUrl("site/simpan"),
				        'type'=>ActiveForm::TYPE_HORIZONTAL,
				        'enableClientValidation' => true,
				        'formConfig' => [
				            'labelSpan' => '3',
				            //'deviceSize' => ActiveForm::SIZE_TINY,
				        ],
				    ]
			    );

$url2           = Yii::$app->urlManager->createUrl("site/view-koleksi");
$ajaxOptions    = [
                    'type' => 'POST',
                    'url'  => $url2,
                    'data' => array(
                            //'NoAnggota' => $noAnggota,
                            //'memberID'  => $memberID,
                            'NomorBarcode' => new yii\web\JsExpression('function(){ return $("#dynamicmodel-nomorbarcode").val(); }'),
                            'TglTransaksi' => ''
                        ),
                    //'beforeSend' => new yii\web\JsExpression('function(){ setModalLoader(); }'),
                    //'update' => '#divAnggota',
                    //'success'=>new yii\web\JsExpression('function(data){ $("#divAnggota").html(data);'.$dataSuccess.' }'),
                   'success'=>new yii\web\JsExpression('
                   		function(data){ 
                   			$("#koleksi-item").html(data); 
                   			$("#dynamicmodel-nomorbarcode").val("");
                   			$("#dynamicmodel-nomorbarcode").focus(); 
                   			$("#btn-simpan").prop("disabled", false);
                   			$("#parent-warning-barcode").hide();
                   		}'),
                   'error' => new yii\web\JsExpression('function(xhr, ajaxOptions, thrownError){ 
                   					// alert(xhr.responseText);
                   					var str = xhr.responseText;
									var message = str.replace("Not Found (#404):","");

                   					$("#parent-warning-barcode").show();
                   					$("#warning-scanbarcode").html(message);

                   					$("#dynamicmodel-nomorbarcode").val("");
                   					$("#dynamicmodel-nomorbarcode").focus();
                   					//$("#btn-simpan").prop("disabled", true);
                   				}'),
                ];

?>
<style>
	hr {
	margin-top: 10px;
    margin-bottom: 0px;
    border: 0;
    border-top: 1px solid #6f6f6f;
	}
</style>
<div class="page-header">
	<h3>
		&nbsp;
		<div class="pull-left">
			<?php
			echo '<p>';
			echo Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> '.Yii::t('app', 'Proses') , ['class' => 'btn btn-success','id'=>'btn-simpan']);
			echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-remove"></span> '. Yii::t('app', 'Batal Transaksi'), ['index'], ['class' => 'btn btn-danger']);
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
		        		<?= Html::activeTextInput($model,'nomorBarcode',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Masukkan No. Barcode')]); ?>
		        		<div class="input-group-btn" >
		        			<?php
						            echo AjaxButton::widget([
						                'label' => '<i class="glyphicon glyphicon-check"></i> ' .Yii::t('app','Ok'),
						                'ajaxOptions' => $ajaxOptions,
						                'htmlOptions' => [
						                    'class' => 'btn btn-warning',
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
<div id="parent-warning-barcode" class="callout callout-warning callout-dismissible" hidden="hidden" style="margin-bottom: 0!important;">
	<!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
	<!-- <h4><i class="fa fa-info"></i> Note:</h4> -->
	<span id="warning-scanbarcode">Warning nanti disini</span>
</div>
<!-- /.HINT - ALERT -->



<!-- KOLEKSI YANG AKAN DIKEMBALIKAN -->
</br>
<div id="koleksi-item">
	<div style="text-align: center; font-size: 14px; background-color: #73b9d7; padding: 10px;
	                    color: #fff; text-shadow: 0 1px 2px #222; margin-bottom: 3px; border-radius: 5px;">
	                    <b><?= yii::t('app','KOLEKSI YANG AKAN DIKEMBALIKAN')?></b></div>
</div>


</br>
<div class="page-panduan">
		&nbsp;

			<div id="panduan" style="font-size: 14px; background-color: rgba(255, 0, 0, 0.1); border: 1px solid #ccc; padding: 10px;
	                    color: #4a4a4a; margin-bottom: 3px; border-radius: 5px;">
	                    <!-- <span><a onClick = "divHide('panduan');">X</a></span> -->
	                    <span><a onclick="myFunction()" style="cursor:pointer">Close</a></span>
	                    <hr/ >
	                    <h4><?= yii::t('app','Petunjuk Pengembalian Mandiri :')?></h4>
						<h4>
						<ol>
						  <li><?= yii::t('app','Masukkan / Pindai Nomor Barcode koleksi yang akan dikembalikan satu per satu')?></li>
						  <li><?= yii::t('app','Pastikan data koleksi yang akan dikembalikan muncul di dalam daftar koleksi yang akan dikembalikan')?></li>
						  <li><?= yii::t('app','Klik tombol Proses')?></li>
						</ol> </h4>
	        </div>

</div>
<!-- ,/KOLEKSI YANG AKAN DIKEMBALIKAN -->
<script>


    function myFunction() {
		document.getElementById("panduan").style.display = "none";

    };

</script>

<?php
$HapusOk        = '$("#ErrorMsg").hide();$("#DataList").html("");$("#DataList").html(data);$("#DivKoleksi").html("");$("#TxtNoItem2").focus();$("#TxtNoItem2").val("");'; 
$HapusItemUrl      = Yii::$app->urlManager->createUrl("site/hapus-item");
$token          = Yii::$app->request->csrfToken;

$this->registerJs("
	
	$('#btn-simpan').prop('disabled', true);

	$('#dynamicmodel-nomorbarcode').focus();
	
	$('#dynamicmodel-nomorbarcode').keydown(function(event){
        if(event.keyCode == 13) {
			if($('#dynamicmodel-nomorbarcode').val() == ''){
				// alert('Nomor Barcode tidak boleh kosong.');
				$('#parent-warning-barcode').show();
				$('#warning-scanbarcode').html('Nomor Barcode tidak boleh kosong.');
				
				$('#dynamicmodel-nomorbarcode').focus();
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