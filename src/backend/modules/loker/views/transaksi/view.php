<?php


use yii\helpers\Html;
use yii\helpers\Url;
//use yii\widgets\DetailView;

use common\components\Helpers;

?>

<?= $this->render('_detailPeminjaman', [
    'model' => $model, 'data' => $data,
]) ?>


<div class="pull-right">
	<?php // Html::a('Cetak', ['cetak','id' => $model->ID], ['class'=>'btn btn-lg btn-primary','target' => '_blank']) ?>
	<a href="#;" id="PrintButton" class="btn btn-lg btn-primary"><?= yii::t('app','Cetak')?></a>
	<a href="peminjaman" id="backButton" class="btn btn-lg btn-warning"><?= yii::t('app','Selesai')?></a>
</div>


<?php
$this->registerJs("
	//WHEN DOCUMENT READY
	$(document).ready(function(){ 
	    lattestStatus();//LOAD LATTEST UPDATES
	    setInterval(function(){showUpdatedStatus();},5000);//LOAD LATTEST UPDATES EVERY 20 seconds    
	});

	function dataMember() {
		document.getElementById('fullnameMember').innerHTML = fullnameMember;
	}
");
?>


<iframe id='Iframe1Slip' src='#' class='clsifrm' style="width: 0pt; height: 0pt; border: none;" ></iframe>
<div id="divPrint" style="display:inline;"></div>

<?php
$url = Url::to(['transaksi/cetak']);
$this->registerJs("
	$('#PrintButton').click(function(){
		$.get('". $url ."', {id: ".$model->ID."},function(data, status){
			if(status == 'success'){
				try {
					var oIframe = document.getElementById('Iframe1Slip');
					var oDoc = (oIframe.contentWindow || oIframe.contentDocument);
					if (oDoc.document) oDoc = oDoc.document;
					oDoc.write('<html><head>');
					oDoc.write('</head><body onload=\"this.focus(); this.print(true);\" style=\"text-align: left; font-size: 8pt; width: 95%; height:90%\">');
					oDoc.write(data + '</body></html>');
					oDoc.close();
				} catch (e) {
					alert(e.message);
					self.print();
				}
			}
		});
	});
");
 ?>

 	