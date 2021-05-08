<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\grid\GridView;
use yii\widgets\Pjax;


use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use common\widgets\AjaxButton;
use common\widgets\MaskedDatePicker;

/**
 * @var yii\web\View $this
 * @var common\models\Collectionloanitems $model
 */
$this->title = Yii::t('app', 'Entri Peminjaman Susulan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sirkulasi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php 

$form 			= ActiveForm::begin(
				    [
				    	'action'=> Url::to('simpan'),
				        'type'=>ActiveForm::TYPE_HORIZONTAL,
				        'enableClientValidation' => true,
				        'formConfig' => [
				            //'labelSpan' => '3',
				            //'deviceSize' => ActiveForm::SIZE_TINY,
				        ],
				    ]
			    );

$url2           = Url::to('view-koleksi');
$ajaxOptions    = [
                    'type' => 'POST',
                    'url'  => $url2,
                    'data' => array(
                            'NoAnggota' => $noAnggota,
                            'memberID'  => $memberID,
                            'NomorBarcode' => new yii\web\JsExpression('function(){ return $("#peminjamanitemform-nomorbarcode").val(); }'),
                            'TglTransaksi' => new yii\web\JsExpression('function(){ return $("#peminjamanitemform-tgltransaksi").val(); }')
                        ),
                    'beforeSend' => new yii\web\JsExpression('function(){ 
						if($("#peminjamanitemform-tgltransaksi").val() == ""){
							// alert("Tanggal Transaksi tidak boleh kosong.");
							$("#parent-warning-barcode").show();
							$("#warning-scanbarcode").html("Tanggal Transaksi tidak boleh kosong.");

							$("#peminjamanitemform-tgltransaksi").focus();
							return false;
						}
                    }'),
                    //'update' => '#divAnggota',
                    //'success'=>new yii\web\JsExpression('function(data){ $("#divAnggota").html(data);'.$dataSuccess.' }'),
                   'success'=>new yii\web\JsExpression('function(data){ 
                   		$("#koleksi-item").html(data); 
                   		$("#peminjamanitemform-nomorbarcode").val("");
                   		$("#peminjamanitemform-nomorbarcode").focus(); 
                   		$("#btn-simpan").prop("disabled", false);
                   		$("#parent-warning-barcode").hide();

                   	}'),
                   'error' => new yii\web\JsExpression('function(xhr, ajaxOptions, thrownError){ 
                   					// alert(xhr.responseText); 
                   					var str = xhr.responseText;
									var message = str.replace("Not Found (#404):","");

                   					$("#parent-warning-barcode").show();
                   					$("#warning-scanbarcode").html(message);

                   					$("#peminjamanitemform-nomorbarcode").val("");
                   					$("#peminjamanitemform-nomorbarcode").focus();
                   				}'),
                ];

?>

<div class="page-header">
	<h3>
		&nbsp;
		<div class="pull-left">
			<?php
			echo '<p>';
			echo Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> '.Yii::t('app', 'Create') , ['class' => 'btn btn-success','id'=>'btn-simpan']);
			echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-remove"></span> '. Yii::t('app', 'Batal Transaksi'), ['index'], ['class' => 'btn btn-danger']);
			echo '</p>';
			?>
		</div>


		<div class="pull-right col-md-6 row" style="">
			<div class="col-md-12">	
			<?php
			echo $form->field($model, 'tglTransaksi', [
						'template' => "{label}<div class='col-md-8'>{input}</div>\n{hint}\n{error}",
						//'labelOptions' => [ 'class' => 'col-md-6' ]
				])->widget(MaskedDatePicker::classname(), 
    						[
    							'enableMaskedInput' => true,
							    'maskedInputOptions' => [
							        'mask' => '99-99-9999',
							        'pluginEvents' => [
							            'complete' => "function(){console.log('complete');}"
							        ]
							    ],
							   'removeButton' => false,
							   'options'=>[
	                                // 'style'=>'width:170px',
	                            ],
							    'pluginOptions' => [
	                            	'autoclose' => true,
	                            	'todayHighlight' => true,
	                            	'format'=>'dd-mm-yyyy',
	                            ]
    						])->label('<small>'. yii::t('app','Tgl Transaksi : ').'</small>',['style'=>'padding-top:0; font-size:17pt;','class'=>'col-md-4']);

			?>
			</div>
		</div>



	</h3>
</div>

<!-- ANGGOTA AREA -->
<div class="nav-tabs-custom" id="anggota-area">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#detail-anggota" data-toggle="tab"><?= Yii::t('app','Detail Anggota')?></a></li>
		<li><a href="#loan-locations" data-toggle="tab"><?= Yii::t('app','Lokasi Anggota')?></a></li>
		<li><a href="#loan-category" data-toggle="tab"><?= Yii::t('app','Kategori Koleksi')?></a></li>
		<li><a href="#history-last-loan" data-toggle="tab"><?= Yii::t('app','Histori Peminjaman')?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="detail-anggota">
			<div class="row">
				<?=$tab_infoanggota?>
			</div>
		</div>
		<div class="tab-pane" id="loan-locations">
				<?=$tab_loanLocation?>
		</div>
		<div class="tab-pane" id="loan-category">
				<?=$tab_loanCategory?>
		</div>
		<div class="tab-pane" id="history-last-loan">
				<?=$tab_historyLoan?>
		</div>
	</div><!-- /.tab-content -->
</div><!-- /.nav-tabs-custom -->
<!-- /.ANGGOTA AREA -->

<!-- KOLEKSI-AREA -->
<div style="background-image: linear-gradient(to bottom, #59bedc, #0978c5);
     color:#fff;padding:0px 0px 10px 20px;border-radius:3px;">
	<div class="content_edit" id="koleksi-area">
		<table border="0">
		    <tr>
		        <td valign="top" class='icon-users'>&nbsp;&nbsp;
		        	<div class="input-group">
		        		<?= Html::activeTextInput($model,'nomorBarcode',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').'  No.'.Yii::t('app', 'Barcode').'']); ?>
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
<div id="parent-warning-barcode" hidden="hidden" class="callout callout-warning callout-dismissible"  style="margin-bottom: 0!important;">
	<span id="warning-scanbarcode">Warning nanti disini</span>
</div>
<!-- /.HINT - ALERT -->

<!-- KOLEKSI YANG AKAN DIPINJAM -->
</br>
<div style="text-align: center; font-size: 14px; background-color: #73b9d7; padding: 10px;
                    color: #fff; text-shadow: 0 1px 2px #222; margin-bottom: 3px; border-radius: 5px;">
                    <b><?= yii::t('app','KERANJANG PEMINJAMAN')?></b></div>
<div>

<div id="koleksi-item"></div>
<!-- ,/KOLEKSI YANG AKAN DIPINJAM -->

<!-- KOLEKSI LOAN OUTSTANDING -->

    <?php 
	//$kolLoanOutStanding = $koleksiLoanOutstanding->getModels();
    if(!empty($kolLoanOutStanding)){
    	
    	//echo (\common\components\SirkulasiHelpers::lateDays('2016-01-08','2016-01-07'));
    ?>
    	</br>
		<div style="text-align: center; font-size: 14px; background-color: #73b9d7; padding: 10px;
                    color: #fff; text-shadow: 0 1px 2px #222; margin-bottom: 3px; border-radius: 5px;">
                    <b><?= yii::t('app','KOLEKSI YANG MASIH DIPINJAM')?></b></div>
                <div>
    	<?php
    	 echo GridView::widget([
	        'dataProvider' => $koleksiLoanOutstanding,
	        'summary'=>'',
	        'columns' => [
	        ['class' => 'yii\grid\SerialColumn'],
	        	[
	        		'header'=>'No.Transaksi',
	        		'attribute'=>'CollectionLoan_id'
	        	],
	            'NomorBarcode',
	            [
	        		'header'=>'Judul',
	        		'attribute'=>'Title'
	        	],
	            [
	        		'header'=>'Pengarang',
	        		'attribute'=>'Author'
	        	],
	            [
	        		'header'=>'Penerbit',
	        		'attribute'=>'Publisher'
	        	],
	        	[
	        		'header'=>'Tgl Pinjam',
	        		'value'=>function($model){
	        			return \common\components\Helpers::DateTimeToViewFormat($model['LoanDate']);
	        		}
	        		
	        	],
	            
	            [
	        		'header'=>'Tgl Harus Kembali',
	        		'value'=>function($model){
	        			return \common\components\Helpers::DateTimeToViewFormat($model['DueDate']);
	        			//var_dump($model['DueDate']);
	        		}
	        	],
	        	[
	        		'header'=>'Terlambat',
	        		'format'=>'raw',
	        		'value'=>function($model){
	        			$late = \common\components\SirkulasiHelpers::lateDays(date('Y-m-d') ,date("Y-m-d", strtotime($model['DueDate'])));
	        			if($late > 0){
	        				// $html = '<span class="label label-danger">'.$late.' Hari</span>';
	        				$html = $late;
	        			}else{
	        				// $html = '<span class="label label-warning">'.$late.' Hari</span>';
	        				$html = $late;
	        			}
	        			return $html;
	        		}
	        		
	        	],
	            
	        ],
	        'responsive'=>true,
	        'hover'=>true,
	        'condensed'=>true,
	        'floatHeader'=>false,
	        'rowOptions'=>function ($model, $key, $index, $grid){
                    $style = array();
                    $warningLoanDueDay = \common\components\SirkulasiHelpers::getWarningLoanDueDay($model['Collection_id'],$_SESSION['NoAnggota']);

                    if (date('Y-m-d') > date("Y-m-d", strtotime($model['DueDate'])))// Warning Terlambat
                    {
                         $style = 'danger'; // Terlambat
                    }
                    elseif (\common\components\Helpers::addDayswithdate(date('Y-m-d'),$warningLoanDueDay) == date("Y-m-d", strtotime($model['DueDate'])))
                    {
                        $style = 'warning'; // Warning
                    }

                    return array('key'=>$key,'index'=>$index,'class'=>$style);
                },
        
		

	        
	    ]);
    }

   ?>
	
<!-- /.KOLEKSI LOAN OUTSTANDING -->
<?php
$HapusOk        = '$("#ErrorMsg").hide();$("#DataList").html("");$("#DataList").html(data);$("#DivKoleksi").html("");$("#TxtNoItem2").focus();$("#TxtNoItem2").val("");'; 
$HapusItemUrl      = Url::to('hapus-item');
$token          = Yii::$app->request->csrfToken;
$this->registerJs("
	
	$('#btn-simpan').prop('disabled', true);

	$('#peminjamanitemform-nomorbarcode').focus();
	
	$('#peminjamanitemform-nomorbarcode').keydown(function(event){
        if(event.keyCode == 13) {
        	if($('#peminjamanitemform-tgltransaksi').val() == ''){
				// alert('Tanggal Transaksi tidak boleh kosong.');
				$('#parent-warning-barcode').show();
				$('#warning-scanbarcode').html('Tanggal Transaksi tidak boleh kosong.');

				$('#peminjamanitemform-tgltransaksi').focus();
				//retun flase
			}
			

			if($('#peminjamanitemform-nomorbarcode').val() == ''){
				// alert('Nomor Barcode tidak boleh kosong.');
				$('#parent-warning-barcode').show();
				$('#warning-scanbarcode').html('Nomor Barcode tidak boleh kosong.');

				$('#peminjamanitemform-nomorbarcode').focus();
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