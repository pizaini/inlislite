<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;

use kartik\grid\GridView;
use kartik\select2\Select2;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

use common\widgets\AjaxButton;


use common\models\JenisPelanggaran;
use common\models\JenisDenda;

/**
 * @var yii\web\View $this
 * @var common\models\Collectionloanitems $model
 */
$this->title = Yii::t('app', 'Daftar Pengembalian / Pelanggaran');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sirkulasi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?php 
$totalItem = count($daftarItem);

$totalPelanggaran =  count($daftarItem[0]->pelanggarans);

?>

<div class="page-header">
	<h3>
		&nbsp;
		<div class="pull-left">
			<?php
      if ($totalItem > 0){
  		  	echo '<p>';
          echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-print"></span> '. Yii::t('app', 'Cetak Slip Pengembalian'), '#', ['class' => 'btn btn-success','id'=>'print-slip-pengembalian',]);
          if($totalPelanggaran > 0 ) {
            echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-print"></span> '. Yii::t('app', 'Cetak Slip Pelanggaran'), '#', ['class' => 'btn btn-default','id'=>'print-slip-pelanggaran',]);
    		  }
        	echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-remove"></span> '. Yii::t('app', 'Selesai'), ['index'], ['class' => 'btn btn-warning']);

    			echo '</p>';
       }
			?>
		</div>
	</h3>
</div>



<!-- ANGGOTA AREA -->
<div class="nav-tabs-custom" id="anggota-area">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#detail-anggota" data-toggle="tab"><?= Yii::t('app','Detail Anggota')?></a></li>
    
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="detail-anggota">
      <div class="row">
        <?=$tab_infoanggota?>
      </div>
    </div>
   
  </div><!-- /.tab-content-->
</div><!-- /.nav-tabs-custom -->
<!-- /.ANGGOTA AREA -->

<?php 
      if (count($daftarItem) > 0){

?>
<!-- KOLEKSI YANG TELAH DIKEMBALIKAN -->
<div style="text-align: center; font-size: 14px; background-color: #73b9d7; padding: 10px;
                      color: #fff; text-shadow: 0 1px 2px #222; margin-bottom: 3px; border-radius: 5px;">
                      <b>KOLEKSI YANG TELAH DIKEMBALIKAN</b></div>
<div>


<div class="table-responsive">  
    <table class="table table-striped table-bordered table-hover table-condensed">
      <thead>
        <tr>
          <th width="40px" style="text-align: right">No</th>
          <th width="100px">No.Peminjaman</th>
          <th width="100px">No.Barcode</th>
          <th>Judul</th>
          <th width="100px">Penerbit</th>
          <th width="100px">Tgl. Pinjam</th>
          <th width="141px">Jatuh Tempo</th>
        </tr>
      </thead>
      <tbody>

        <?php foreach ($daftarItem as $item): ?>
        <?php
          $totalItem = count($daftarItem);
          
   
          if(date('Y-m-d') > $item->DueDate)
            {
               $class = "class='danger'";
               
            }else{
              $class = "";
            } 
        ?>
        <tr <?=$class?> >
          <td style="text-align: right"><?php echo $n++; ?></td>
          <td><?php echo trim($item->CollectionLoan_id); ?></td>
          <td><?php echo trim($item->collection->NomorBarcode); ?></td>
          <td><?php echo $item->collection->catalog->Title; ?></td>
          <td style="text-align: right">
           <?php echo $item->collection->catalog->Publisher; ?>
          </td>
          <td style="text-align: right">
            <?php  
              echo \common\components\Helpers::DateTimeToViewFormat($item->LoanDate);
             ?>
          </td>
          <td style="text-align: right">
            <?php  
              echo \common\components\Helpers::DateTimeToViewFormat($item->DueDate);
             ?>
          </td>
         
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="5"><strong>Total Koleksi Yang Dikembalikan : </strong></td>
          <td style="text-align: right">&nbsp;</td>
          <td style="text-align: right">
            <strong><?=$totalItem?></strong>
          </td>
        </tr>
      </tfoot>
    </table>
    </div>

<iframe id='Iframe1Slip' src='#' class='clsifrm' style="width: 0pt; height: 0pt; border: none;" ></iframe>
      
<?php

    $pelanggaran = $item->pelanggarans;

  } //end count
?>


      <?php 
      if ($totalPelanggaran > 0){
        $no=1;
      ?>
<!-- KOLEKSI YANG TERKENA PELANGGARAN -->
<div style="text-align: center; font-size: 14px; background-color: #73b9d7; padding: 10px;
                      color: #fff; text-shadow: 0 1px 2px #222; margin-bottom: 3px; border-radius: 5px;">
                      <b>KOLEKSI YANG TERKENA PELANGGARAN</b></div>
<div>


    <div class="table-responsive">  
    <table class="table table-striped table-bordered table-hover table-condensed">
      <thead>
        <tr>
          <th width="40px" style="text-align: right">No</th>
          <th width="100px">No.Peminjaman</th>
          <th width="100px">No.Barcode</th>
          <th width="300px">Judul</th>
          <th width="100px">Jatuh Tempo</th>
          <th width="100px">Terlambat</th>
          <th width="141px">Pelanggaran</th>
          <th width="141px">Jenis Denda</th>
          <th width="141px">Jumlah Denda</th>
          <th width="141px">Jumlah Skorsing</th>
        </tr>
      </thead>
      <tbody>

        <?php foreach ($pelanggaran as $item): ?>
        <?php
          $totalItem = count($pelanggaran);
         
        ?>
        <tr>
          <td style="text-align: right"><?php echo $no; ?></td>
          <td><?php echo trim($item->CollectionLoan_id); ?></td>
          <td><?php echo trim($item->collection->NomorBarcode); ?></td>
          <td><?php echo $item->collection->catalog->Title; ?></td>
          <td style="text-align: right">
           <?php  
              echo \common\components\Helpers::DateTimeToViewFormat($item->collectionLoanItem->DueDate);
             ?>
          </td>
          <td style="text-align: right">
            <?php 
            	$late = \common\components\SirkulasiHelpers::lateDays(date('Y-m-d') ,date("Y-m-d", strtotime($item->collectionLoanItem->DueDate)));
            	if($late > 0){
            		$html = $late.' Hari';
            	}else{
            		$html = '0 Hari';
            	}
            	echo $html;
            ?>
          </td>
          <td>
             <?php 
                echo $item->jenisPelanggaran->JenisPelanggaran;
             ?>
          </td>
          <td >
             <?php 
                echo $item->jenisDenda->Name;
             ?>
          </td>
          <td style="text-align: right">
            <?php 
                echo $item->JumlahDenda;
             ?>
          </td>
          <td style="text-align: right">
           <?php 
                echo $item->JumlahSuspend;
             ?>
           
          </td>
        </tr>
        <?php $no = $no+1; ?>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="8"><strong>Total Koleksi Yang Terkena Pelanggaran : </strong></td>
          <td style="text-align: right">&nbsp;</td>
          <td style="text-align: right">
            <strong><?=$totalItem?></strong>
          </td>
        </tr>
      </tfoot>
    </table>
    </div>
   <?php 
      }
   ?>

<table style="width: 30%; margin-top: 20px;">
    <tbody><tr>
      <td class="style4" colspan="3">
        &nbsp;<?= yii::t('app','Keterangan : ')?>
      </td>
    </tr>
    <tr>
      <td class="style4">
        &nbsp;
      </td>
      <td style="background-color: #ebcccc">
        &nbsp;
      </td>
      <td>
        &nbsp;<?= yii::t('app','Sudah jatuh tempo.')?>
      </td>
    </tr>
</tbody>
</table>
<!-- ./KOLEKSI YANG TERKENA PELANGGARAN -->
<?php 
$this->registerJs("
    $('#print-slip-pelanggaran').click(function() {
        var url = '".Url::to(['print/cetak-slip-pelanggaran','NoPinjam'=>$daftarItem[0]['CollectionLoan_id']])."';
          $('#Iframe1Slip').attr('src',url);
        });

    $('#print-slip-pengembalian').click(function() {
     
        var url = '".Url::to(['print/cetak-slip-pengembalian','NoPinjam'=>$daftarItem[0]['CollectionLoan_id']])."';
          $('#Iframe1Slip').attr('src',url);
        });
  ");


?>

