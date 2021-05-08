<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Collectionloanitems $model
 */

$this->title = Yii::t('app', 'Entri Peminjaman');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sirkulasi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<iframe id='Iframe1Slip' src='' class='clsifrm' style="width: 0px; height: 0px; border: none;" width="100" height="100"></iframe>

<!-- Form cari Nomor Anggota -->
<div style="background-image: linear-gradient(to bottom, #59bedc, #0978c5);color:#fff;padding:0px 0px 10px 20px;border-radius:3px;">
	<?php echo $this->render('_formAnggota', array('model'=>$model)); ?>
</div>
<!-- /Form cari Nomor Anggota -->

<!-- HINT - ALERT -->
<div id="parent-warning-barcode" hidden="hidden" class="callout callout-warning callout-dismissible"  style="margin-bottom: 0!important;">
	<span id="warning-scanbarcode">Warning nanti disini</span>
</div>
<!-- /.HINT - ALERT -->

<div id="divAnggota" style="display:block;margin:20px 0;"></div>

<div id="divPrint" style="display:inline;"></div>
<?php
$print = isset($_SESSION['print']) ? $_SESSION['print'] : null;
$url = Url::to(['print/print-kuitansi']);
$image_printer = Yii::$app->request->baseUrl . '/assets_b/images/icon_check.png';
//var_dump($image_printer);
if($print['id'] == "1"){

	// AMBIL DATA TRANSAKSI

	// $this->registerJs("
	// $(document).ready(function() {
	// 		swal({   
	// 			title:' ',   
	// 			text: 'Apakah ingin mencetak struk peminjaman ?',   
	// 			//type: 'info',
	// 			imageUrl: 'http://localhost/inlislite/backend/assets_b/images/printer.gif', 
	// 			showCancelButton: true,
	// 			confirmButtonText: 'Cetak',
	// 			cancelButtonText: 'Tutup',
	// 			closeOnConfirm: true,   
	// 			showLoaderOnConfirm: true, }, 
	// 		function(){   
	// 			$.get('". $url ."', {transactionID: ".$print['transactionID']."},function(data, status){
	// 	            //alert('Data: ');
	// 	            //$('#divPrint').html(data);
	// 	            //$('#divPrint').show();
	// 	            //$.print('#divPrint');  
	// 	            //$('#divPrint').hide();
	// 	            //alert(status);
	// 	            if(status == 'success'){
	// 	            	try {
	// 		            	var oIframe = document.getElementById('Iframe1Slip');
	// 		                var oDoc = (oIframe.contentWindow || oIframe.contentDocument);
	// 		                if (oDoc.document) oDoc = oDoc.document;
	// 		                oDoc.write('<html><head>');
	// 		                oDoc.write('</head><body onload=\"this.focus(); this.print(true);\" style=\"text-align: left; font-size: 8pt; width: 95%; height:90%\">');
	// 		                oDoc.write(data + '</body></html>');
	// 		                oDoc.close();
	// 	                } catch (e) {
	// 		                alert(e.message);
	// 		                self.print();
	// 		            }
	// 	            }
		            
	// 	        });
	// 			/*setTimeout(function(){ 
	// 				//$.print('#divPrint');  
	// 			}, 200);*/
	// 		});
	// 	});
		
		
	// ");

	   // HAPUS SESSION PRINT
     unset($_SESSION['print']);
	

}

?>