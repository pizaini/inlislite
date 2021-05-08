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
$this->title = Yii::t('app', 'Detail Pengembalian / Pelanggaran');
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
          echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-print"></span> '.Yii::t('app', 'Cetak Slip Pengembalian'), '#', ['class' => 'btn btn-success','id'=>'print-slip-pengembalian',]);
          ////////////////
          echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-print"></span> '.Yii::t('app', 'Cetak Struk Pengembalian'), '#', ['class' => 'btn btn-primary','id'=>'print-struk-pengembalian',]);
          ////////////////
          if($totalPelanggaran > 0 ) {
            echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-print"></span> '.Yii::t('app', 'Cetak Slip Pelanggaran'), '#', ['class' => 'btn btn-default','id'=>'print-slip-pelanggaran',]);
          ////////////////////////
            echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-print"></span> '.Yii::t('app', 'Cetak Struk Pelanggaran'), '#', ['class' => 'btn btn-warning','id'=>'print-struk-pelanggaran',]);
          ////////////////////////

    		  }
        	echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-remove"></span>'.yii::t('app','Selesai'), Yii::getAlias('@web'), ['class' => 'btn btn-warning']);

    			echo '</p>';
       }
			?>
		</div>
	</h3>
</div>

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
          <th width="100px"><?= yii::t('app','No.Peminjaman')?></th>
          <th width="150px"><?= yii::t('app','No.Barcode')?></th>
          <th><?= yii::t('app','Judul')?></th>
          <th width="100px"><?= yii::t('app','Penerbit')?></th>
          <th width="100px"><?= yii::t('app','Tgl. Pinjam')?></th>
          <th width="100px"><?= yii::t('app','Jatuh Tempo')?></th>

        </tr>
      </thead>
      <tbody>
<?php 
// echo '<pre>'; print_r($data);'</pre>';

$truecats = array();
foreach ($data as $key => $pieces) {
    if (!isset($truecats[$pieces['Fullname']])) {
        $truecats[$pieces['Fullname']] = array();
    }
    $truecats[$pieces['Fullname']][] = $pieces;
}
// echo '<pre>';print_r($truecats);echo '</pre>';
?>

  <?php foreach ($truecats as $key=>$items){ ?>
    <tr>
          <td colspan="11"><?='<pre>'; echo yii::t('app','Nama ').'       : '.trim($key).'<br />'; echo yii::t('app','No. Anggota : ').trim($items[0]['MemberNo']);'</pre>' ?></td>
    </tr>
        <?php foreach ($items as $item): 

          $totalItem = count($daftarItem);
          
          /* nyontoh _listkoleksi tapi kaya nya ngga pengaruh
          $data = [
                'CollectionLoanItem_id'=>trim($item["CollectionLoanItem_id"]),
                'CollectionLoan_id'=>trim($item["CollectionLoan_id"]),
                'Collection_id'=>trim($item["Collection_id"]),
                'NomorPinjam' => trim($item["NomorPinjam"]),
                'Fullname' => trim($model[0]["Fullname"]),
                'NomorBarcode' => trim($item["NomorBarcode"]),
                'MemberID' => trim($item["MemberID"]),
                'MemberNo' => trim($model[0]["MemberNo"]),
                'Title' =>  trim($item["Title"]),
                'TglPinjam'=>trim($item["TglPinjam"]),
                'DueDate'=>trim($item["DueDate"]),
                'TglKembali'=>trim($item["TglKembali"])
              ];*/
   
        ?>
        <tr>
          <td style="text-align: right"><?php echo $n++; ?></td>
          <td><?php echo trim($item['NomorPinjam']); ?></td>
          <td><?php echo trim($item['NomorBarcode']); ?></td>
          <td><?php echo $item['Title']; ?></td>
          
          <td style="text-align: right">
            <?php  
              echo \common\components\Helpers::DateTimeToViewFormat($item['TglPinjam']);
             ?>
          </td>
          <td style="text-align: right">
            <?php  
              echo \common\components\Helpers::DateTimeToViewFormat($item['DueDate']);
             ?>
          </td>
          <td style="text-align: center">
            <?php if ($for != 'epm') 
            {
              $cekPelanggaranExist = common\models\Pelanggaran::find()->where(['CollectionLoan_id'=>$item['NomorPinjam'], 'CollectionLoanItem_id'=>$item['CollectionLoanItem_id']])->One();

              echo (!$cekPelanggaranExist) ? Html::a('<span class="glyphicon glyphicon-info-sign"></span> '.yii::t('app','Pelanggaran'), Yii::$app->urlManager->createUrl(['sirkulasi/pengembalian/create-pelanggaran','loanID' => $item['NomorPinjam'],'member'=>$item['MemberID'],'loanItemID'=>$item['CollectionLoanItem_id']]), ['class' => 'btn btn-sm btn-danger','id'=>'create-pelanggaran',]) : '';
            }
            ?>
          </td>
         
        </tr>
        <?php 
        endforeach; 
  }
        ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="5"><strong><?= yii::t('app','Total Koleksi Yang Dikembalikan :')?> </strong></td>
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

  } //end count
?>

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


    $('#print-struk-pengembalian').click(function() {
     
        var url = '".Url::to(['print/cetak-struk-pengembalian','NoPinjam'=>$daftarItem[0]['CollectionLoan_id']])."';
          $('#Iframe1Slip').attr('src',url);
        });


    $('#print-struk-pelanggaran').click(function() {
        var url = '".Url::to(['print/cetak-struk-pelanggaran','NoPinjam'=>$daftarItem[0]['CollectionLoan_id']])."';
          $('#Iframe1Slip').attr('src',url);
        });





  ");


?>

