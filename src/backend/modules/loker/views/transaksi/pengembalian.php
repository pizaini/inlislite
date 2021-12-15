<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\web\JsExpression;

use common\models\MasterJenisIdentitas;
use common\models\MasterUangJaminan;
use common\models\MasterLoker;
use common\models\Lockers;

use common\models\MasterPelanggaranLocker;

/**
 * @var yii\web\View $this
 * @var common\models\Lockers $model
 * @var yii\widgets\ActiveForm $form
 */

$this->title = Yii::t('app', 'Transaksi');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Locker'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// $data = MasterPelanggaranLocker::find()->all();
// $dataSelect2 = ArrayHelper::map($data,'ID','jenis_pelanggaran');
// print_r ($dataSelect2);
?>

<div class="lockers-form">
    <ul id="w3" class="nav nav-tabs">
        <li >
        	<a href="peminjaman" aria-expanded="false"><?= yii::t('app','Peminjaman')?></a>
        	<!-- <a href="#tab-peminjaman" data-toggle="tab" aria-expanded="true">Peminjaman</a> -->
        </li>
        <li class="active">
            <a href="#tab-pengembalian" data-toggle="tab" aria-expanded="true"><?= yii::t('app','Pengembalian')?></a>
            <!-- <a href="pengembalian" aria-expanded="false">Pengembalian</a> -->
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content col-lg-9">
        <!-- Tab Peminjaman-->
        <div id="tab-peminjaman" class="tab-pane">
        </div>

        <!-- Tab Pengembalian-->
        <div id="tab-pengembalian" class="tab-pane active">
            <h3><?= yii::t('app','Pengembalian')?></h3>
            <?php $form = ActiveForm::begin(); ?>

          	
            <?= $form->field($model, 'No_pinjaman')->hiddenInput(['placeholder'=>Yii::t('app', 'Nomor Pinjaman')])->label(false); ?> 
			 <div class="table-responsive">
                <table class="table table-condensed table-bordered table-striped table-hover request-table" style="table-layout: fixed;">
                    <tbody>
                        <tr>
                            <td class="col-sm-4"><label class="control-label"><?= yii::t('app','Nomor Kunci atau Nomor Pinjaman')?></label></td>
                            <td >
                                <div class="input-group">
      								<?php //$form->field($model, 'No_pinjaman')->textInput(['placeholder'=>Yii::t('app', 'Nomor Pinjaman')])->label(false); ?> 
                                	<input type="textInput" class="form-control" name="cariLocker" id="cariLocker" value="" placeholder="<?= yii::t('app','Nomor Loker atau Nomor Peminjaman')?>" required>
                                    <div class="input-group-btn" >
                                        <button class="btn btn-success" type="button" id="searchLocker"><i class="glyphicon glyphicon-search"></i>&nbsp;<?= yii::t('app',' Cari')?></button>
                                    </div><!-- /btn-group -->
                                    <!-- <input type="text" class="form-control" name="nomorID" id="nomorID" placeholder="Masukkan Nomor ID"> -->
                                    <!-- Button Search nomor ID, Memberguess atau Member-->
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

			<div id="detail-peminjaman">
				<!-- ISi konten akan diisi oleh javascript cari data via controller -->
			</div>
			
			<div id="Pelanggaran" hidden="hidden">
                <table class="table table-condensed table-bordered table-striped table-hover request-table" style="table-layout: fixed;">
                    <tbody>
                        <tr>
                            <td class="col-sm-4"><label class="control-label"><?= yii::t('app','Pelanggaran')?></label></td>
                            <td >
                                <?= $form->field($model, 'id_pelanggaran_locker')->widget('\kartik\widgets\Select2',[
                                    'data'=>ArrayHelper::map(MasterPelanggaranLocker::find()->all(),'ID','jenis_pelanggaran'),
                                    'pluginOptions' => [
                                    'allowClear' => true,
                                    //'templateResult' => new JsExpression('format'),
                                    // 'templateSelection' => new JsExpression('format'),
                                    ],
                                    'options'=> ['placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'Pelanggaran'),'id'=>'pilihPelanggaran']
                                    ])->label(false)->hint(yii::t('app','Kosongkan jika tidak ada pelanggaran')); ?>
                                 <div style="color: red; font-weight: bold;" id="denda">
                                 	
                                 </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-primary pull-right']); ?>
            </div>
    
            <?php $form = ActiveForm::end(); ?>
        </div>
    </div>
</div>


<?php
$this->registerJs("

    //Cursor focus on field when page loaded
    $('#cariLocker').focus();

	$(document).ready(function() {
		$(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});
	});

	function validasi(){
	    $('#Pelanggaran').hide();
		$('#detail-peminjaman').html('Loading...');
	    var id = $('#cariLocker').val();
	    //alert(id);
	    $.ajax({
	        type    : 'POST',
	        url     : 'ceknomorpengembalian?id='+id,

	        success : function(response)
	        {
	            if (!response) {
	            	swal('Data Peminjaman tidak ditemukan');
                    $('#cariLocker').val('');
	            	$('#Pelanggaran').hide();
					$('#detail-peminjaman').html(response);
	            } else {
	            	document.getElementById('detail-peminjaman').innerHTML = response;
	            	$('#Pelanggaran').show();
	       
	            	$('#lockers-no_pinjaman').attr('value', $('#noPeminjaman').html());

	            }
	            
	        }
	    });
	}


	$('#cariLocker').keydown(function(event){
		if(event.keyCode == 13) {
			validasi();
		}
	});

	$('#searchLocker').on('click', validasi);

	$('#pilihPelanggaran').change(function(){
		var idPel = $(this).val();
		//alert(idPel);
		$.get('cekpelanggaran',{ idPel : idPel},function(data){
			if (data=='null') {
				document.getElementById('denda').innerHTML = ' ';	
			} else {
				var data = $.parseJSON(data);
				var fined = (data.denda).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
				// $('#denda').attr('value',);
				document.getElementById('denda').innerHTML = 'Denda yang harus anda bayarkan adalah RP. ' + fined;
			}
			
		});
	});


");
?>



<?php
$print = isset($_SESSION['printPelanggaranLoker']) ? $_SESSION['printPelanggaranLoker'] : null;
$url = Url::to(['transaksi/cetak-bukti-pelanggaran']);
$image_printer = Yii::$app->request->baseUrl . '/assets_b/images/icon_check.png';
//var_dump($image_printer);
if($print['id'] == "1"){

    echo "
    <iframe id='Iframe1Slip' src='#' class='clsifrm' style=\"width: 0pt; height: 0pt; border: none;\" ></iframe>
    <div id=\"divPrint\" style=\"display:inline;\"></div>
    ";
  
    // AMBIL DATA TRANSAKSI

    $this->registerJs("
    $.get('". $url ."', {id: ".$print['idPelanggaran']."},function(data, status){
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
                swal(e.message);
                self.print();
            }
        }
    });
  
    ");

    // // AMBIL DATA TRANSAKSI

    // $this->registerJs("
    // $(document).ready(function() {
    //         swal({   
    //             title:' ',   
    //             text: 'Apakah ingin mencetak struk Pelanggaran ?',   
    //             //type: 'info',
    //             imageUrl: 'http://localhost/inlislite3/backend/assets_b/images/printer.gif', 
    //             showCancelButton: true,
    //             confirmButtonText: 'Cetak',
    //             cancelButtonText: 'Tutup',
    //             closeOnConfirm: true,   
    //             showLoaderOnConfirm: true, }, 
    //         function(){   
    //             $.get('". $url ."', {id: ".$print['idPelanggaran']."},function(data, status){
    //                 if(status == 'success'){
    //                     try {
    //                         var oIframe = document.getElementById('Iframe1Slip');
    //                         var oDoc = (oIframe.contentWindow || oIframe.contentDocument);
    //                         if (oDoc.document) oDoc = oDoc.document;
    //                         oDoc.write('<html><head>');
    //                         oDoc.write('</head><body onload=\"this.focus(); this.print(true);\" style=\"text-align: left; font-size: 8pt; width: 95%; height:90%\">');
    //                         oDoc.write(data + '</body></html>');
    //                         oDoc.close();
    //                     } catch (e) {
    //                         alert(e.message);
    //                         self.print();
    //                     }
    //                 }
                    
    //             });
    //             /*setTimeout(function(){ 
    //                 //$.print('#divPrint');  
    //             }, 200);*/
    //         });
    //     });
    // ");

       // HAPUS SESSION PRINT
     unset($_SESSION['printPelanggaranLoker']);
    

}

?>