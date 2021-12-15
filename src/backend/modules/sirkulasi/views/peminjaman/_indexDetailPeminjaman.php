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
$this->title = Yii::t('app', 'Detail Peminjaman').' No. '.$transactionID;
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
				            'labelSpan' => '3',
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
                            'TglTransaksi' => ''
                        ),
                    //'beforeSend' => new yii\web\JsExpression('function(){ setModalLoader(); }'),
                    //'update' => '#divAnggota',
                    //'success'=>new yii\web\JsExpression('function(data){ $("#divAnggota").html(data);'.$dataSuccess.' }'),
                   'success'=>new yii\web\JsExpression('function(data){ $("#koleksi-item").html(data); $("#peminjamanitemform-nomorbarcode").val("");$("#peminjamanitemform-nomorbarcode").focus(); $("#btn-simpan").prop("disabled", false);}'),
                   'error' => new yii\web\JsExpression('function(xhr, ajaxOptions, thrownError){ 
                   					alert(xhr.responseText); 
                   					$("#peminjamanitemform-nomorbarcode").val("");
                   					$("#peminjamanitemform-nomorbarcode").focus();
                   				}'),
                ];

?>


<!-- Iframe untuk print  -->
<iframe id='Iframe1Slip' src='' class='clsifrm' style="width: 0px; height: 0px; border: none;" width="100" height="100"></iframe>

<!-- <iframe id='Iframe1Struk' src='' class='clsifrm' style="width: 0px; height: 0px; border: none;" width="100" height="100"></iframe> -->

<div class="page-header">
	<h3>
		&nbsp;
		<div class="pull-left">
			<?php
			echo '<p>';
			// echo Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> '.Yii::t('app', 'Create') , ['class' => 'btn btn-success','id'=>'btn-simpan']);
			echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-remove"></span> '. Yii::t('app', 'Selesai'), ['create'], ['class' => 'btn btn-warning']);

			// Cetak button
			echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-print"></span> '. Yii::t('app', 'Cetak Slip Peminjaman'),'#', ['class' => 'btn btn-success','id' => 'CetakSlip']);
			echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-print"></span> '. Yii::t('app', 'Cetak Struk Peminjaman'),'#', ['class' => 'btn btn-primary','id' => 'CetakStruk']);
			echo '</p>';
			?>
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



<!-- KOLEKSI YANG AKAN DIPINJAM -->
<!-- </br>
<div style="text-align: center; font-size: 14px; background-color: #73b9d7; padding: 10px;
                    color: #fff; text-shadow: 0 1px 2px #222; margin-bottom: 3px; border-radius: 5px;">
    <b>KERANJANG PEMINJAMAN</b>
</div>
<div>
    <?php 
    // if(!empty($koleksiLoanOutstanding)){

    ?>
	<?php
    // 	 echo GridView::widget([
	   //      'dataProvider' => $koleksiLoanCurrentTransaction,

	   //      'summary'=>'',
	   //      'columns' => [
	   //      ['class' => 'yii\grid\SerialColumn'],
	   //      	[
	   //      		'header'=>'No.Transaksi',
	   //      		'attribute'=>'CollectionLoan_id'
	   //      	],
	   //          'NomorBarcode',
	   //          [
	   //      		'header'=>'Judul',
	   //      		'attribute'=>'Title'
	   //      	],
	   //          [
	   //      		'header'=>'Pengarang',
	   //      		'attribute'=>'Author'
	   //      	],
	   //          [
	   //      		'header'=>'Penerbit',
	   //      		'attribute'=>'Publisher'
	   //      	],
	   //      	[
	   //      		'header'=>'Tgl Pinjam',
	   //      		'value'=>function($model){
	   //      			return \common\components\Helpers::DateTimeToViewFormat($model['LoanDate']);
	   //      		}
	        		
	   //      	],
	            
	   //          [
	   //      		'header'=>'Jatuh Tempo',
	   //      		'value'=>function($model){
	   //      			return \common\components\Helpers::DateTimeToViewFormat($model['DueDate']);
	   //      		}
	   //      	],
	   //      	[
	   //      		'header'=>'Terlambat',
	   //      		'format'=>'raw',
	   //      		'value'=>function($model){
	   //      			$late = \common\components\SirkulasiHelpers::lateDays(date('Y-m-d') ,date("Y-m-d", strtotime($model['DueDate'])));
	   //      			if($late > 0){
	   //      				$html = '<span class="label label-danger">'.$late.' Hari</span>';
	   //      			}else{
	   //      				$html = '<span class="label label-warning">'.$late.' Hari</span>';
	   //      			}
	   //      			return $html;
	   //      		}
	        		
	   //      	],
	            
	   //      ],
	   //      'responsive'=>true,
	   //      'hover'=>true,
	   //      'condensed'=>true,
	   //      'floatHeader'=>false,
	   //      'rowOptions'=>function ($model, $key, $index, $grid){
    //                 $style = array();
    //                 $warningLoanDueDay = \common\components\SirkulasiHelpers::getWarningLoanDueDay($model['Collection_id'],$_SESSION['NoAnggota']);

    //                 if (date('Y-m-d') > date("Y-m-d", strtotime($model['DueDate'])))// Warning Terlambat
    //                 {
    //                      $style = 'danger'; // Terlambat
    //                 }
    //                 elseif (\common\components\Helpers::addDayswithdate(date('Y-m-d'),$warningLoanDueDay) == date("Y-m-d", strtotime($model['DueDate'])))
    //                 {
    //                     $style = 'warning'; // Warning
    //                 }

    //                 return array('key'=>$key,'index'=>$index,'class'=>$style);
    //             },
	   //  ]);
    // }

   ?>
</div>

<div id="koleksi-item"></div> -->
<!-- ,/KOLEKSI YANG AKAN DIPINJAM -->

<!-- KOLEKSI LOAN OUTSTANDING -->

    <?php 
    //$koleksiLoanOutstanding = $koleksiLoanOutstanding->getModels();
    if(!empty($koleksiLoanOutstanding)){

    	//echo (\common\components\SirkulasiHelpers::lateDays('2016-01-08','2016-01-07'));
    ?>
    	</br>
		<div style="text-align: center; font-size: 14px; background-color: #73b9d7; padding: 10px;
                    color: #fff; text-shadow: 0 1px 2px #222; margin-bottom: 3px; border-radius: 5px;">
                    <b><?= yii::t('app','KOLEKSI YANG DIPINJAM')?></b>
      	</div>
        <div>
    	<?php
    	 echo GridView::widget([
	        'dataProvider' => $koleksiLoanOutstanding,

	        'summary'=>'',
	        //'showPageSummary' =>false,
	        //'showHeader '=>false,
	        //'toggleData' => '',
	        'columns' => [
	        ['class' => 'yii\grid\SerialColumn'],
	        	[
	        		'label'=> yii::t('app','No.Transaksi'),
	        		'attribute'=>'CollectionLoan_id'
	        	],
	        	[
	        		'label'=> yii::t('app','Nomor Barcode'),
	        		'attribute'=>'NomorBarcode'
	        	],
	            [
	        		'label'=>yii::t('app','Judul'),
	        		'attribute'=>'Title'
	        	],
	            [
	        		'label'=>yii::t('app','Pengarang'),
	        		'attribute'=>'Author'
	        	],
	            [
	        		'label'=>yii::t('app','Penerbit'),
	        		'attribute'=>'Publisher'
	        	],
	        	[
	        		'label'=>yii::t('app','Tgl.Pinjam'),
	        		'value'=>function($model){
	        			return \common\components\Helpers::DateTimeToViewFormat($model['LoanDate']);
	        		}
	        		
	        	],
	            
	            [
	        		'label'=>yii::t('app','Jatuh Tempo'),
	        		'value'=>function($model){
	        			return \common\components\Helpers::DateTimeToViewFormat($model['DueDate']);
	        			//var_dump($model['DueDate']);
	        		}
	        	],
	        	[
	        		'label'=>'Terlambat',
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
$url = Url::to(['print/print-kuitansi']);
//////
$urlStruk = Url::to(['print/print-struk-kuitansi']);
//////
$this->registerJs("
 
$('#CetakSlip').click(function(){
	$.get('".$url."', {transactionID: ".$transactionID."},function(data, status){
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
	/*setTimeout(function(){ 
		//$.print('#divPrint');  
	}, 200);*/
}); 

$('#CetakStruk').click(function(){
	$.get('".$urlStruk."', {transactionID: ".$transactionID."},function(data, status){
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
	/*setTimeout(function(){ 
		//$.print('#divPrint');  
	}, 200);*/
});
	
	
");
?>