<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
use kartik\widgets\Select2;


use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use common\widgets\AjaxButton;

/**
 * @var yii\web\View $this
 * @var common\models\Collectionloanitems $model
 */

$ajaxPeriodik    = [
                    'type' => 'POST',
                    'url'  => 'pengiriman-koleksi-cetak',
                    'data' => array(
                            'FromDate' => new yii\web\JsExpression('function(){ return document.getElementsByName("from_date")[0].value; }'),
                            'EndDate' => new yii\web\JsExpression('function(){ return document.getElementsByName("to_date")[0].value; }'),
                            'Waktu' => 'periodik'
                        ),
                   'success'=>new yii\web\JsExpression('function(data){ 
                   	console.log(data)
                   		$("#list-pengiriman").html(data); 	
                   		
               		}'),
         //           'error' => new yii\web\JsExpression('function(xhr, ajaxOptions, thrownError){ 
         //           					// alert(xhr.responseText); 
									// var str = xhr.responseText;
									// var message = str.replace("Not Found (#404):","");

         //           					$("#parent-warning-barcode").show();
         //           					$("#warning-scanbarcode").html(message);

         //           					$("#peminjamanitemform-nomorbarcode").val("");
         //           					$("#peminjamanitemform-nomorbarcode").focus();
         //           				}'),
                ];
?>


<div class="settingparameters-form">
  	<form id="form-SearchFilter" method="POST" action="pengiriman-koleksi-cetak">    
	    <div id="SearchFilter" class="col-sm-12">
	        <div class="form-horizontal">
	            <div class="box-body">
	            	<div class="form-group">
	                    <label for="" class="col-sm-2 control-label" style="margin-left:0px;"><?= Yii::t('app','Judul Cetak') ?></label>
	                    <div class="col-sm-8">
	                    	<textarea class="form-control" id="judulCetak" name="judulCetak">DAFTAR PENGIRIMAN BAHAN PUSTAKA(BUKU) KE BAGIAN PENGOLAHAN TAHUN <?=date('Y')?></textarea>
	                    </div>
	                </div>

	                <div class="form-group">
	                    <label for="" class="col-sm-2 control-label" style="margin-left:0px;"><?= Yii::t('app','Nama Penanggung Jawab') ?></label>
	                    <div class="col-sm-8">
	                    	<input type="text" name="penanggungjawab" id="penanggungjawab" class="form-control" placeholder="Nama Penanggung Jawab ...">
	                    </div>
	                </div>

	                <div class="form-group">
	                    <label for="" class="col-sm-2 control-label" style="margin-left:0px;"><?= Yii::t('app','NIP Penanggung Jawab') ?></label>
	                    <div class="col-sm-8">
	                    	<input type="text" name="nip" id="nip" class="form-control" placeholder="NIP Penanggung Jawab ...">
	                    </div>
	                </div>

	                <!-- Pilih Periode -->
	                <div class="form-group">
	                    <label for="pilihPeriode" class="col-sm-2 control-label"><?= Yii::t('app','Periode Tanggal') ?></label>

	                    <div class="col-sm-10 row">
	                        
	                        
	                        <!-- Harian -->
	                        <div class="col-sm-8" id="periodeHarian"  >
	                            <?=  DatePicker::widget([
	                                'name' => 'from_date', 
	                                'id' => 'periode', 
	                                'type' => DatePicker::TYPE_RANGE,
	                                'value' => date('d-m-Y'),
	                                'name2' => 'to_date', 
	                                'value2' => date('d-m-Y'),
	                                'separator' => 's/d',
	                                'options' => ['placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Date')],
	                                'pluginOptions' => [
	                                'format' => 'dd-mm-yyyy',
	                                'todayHighlight' => true,
	                                'autoclose'=>true,
	                                'id' => 'rangeHarian',
	                                ]
	                                ]);
	                                ?>
	                        </div><!-- /Harian -->
	                        

	                    </div>

	                </div>
	                <!-- /Pilih Periode -->

	                
	                
	                

	                
	                
	            </div>
	            <!-- /.box-body -->
	            <div class="form-group padding0">
	                <div class="col-sm-10 col-sm-offset-2 padding0">
	                	<?php
					            echo AjaxButton::widget([
					                'label' => Yii::t('app','Cetak Pengiriman Periodik'),
					                'ajaxOptions' => $ajaxPeriodik,
					                'htmlOptions' => [
					                    'class' => 'btn btn-sm btn-primary',
					                    'id' => 'cetak_periodik',
					                    'type' => 'submit'
					                ]
					            ]);
			            ?>
	                    
	                    
	                </div>
	               
	            </div>
	            <!-- /.box-footer -->
	        </div>
	    </div>
	</form> 

</div>

<div class="settingparameters-form">
	<div id="list-pengiriman"></div>
</div>


<input type="hidden" id="hdnUrlProsesCetak" value="<?=Yii::$app->urlManager->createUrl(["akuisisi/pengiriman-koleksi/print-pengiriman-koleksi"])?>">

