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

<!-- Iframe untuk print  -->
<iframe id='Iframe1Slip' src='' class='clsifrm' style="width: 0px; height: 0px; border: none;" width="100" height="100"></iframe>

<div class="page-header">
  <h3>
    &nbsp;
    <div class="pull-left">
      <?php
      if ($totalItem > 0){
          echo '<p>';
          // echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-print"></span> '. Yii::t('app', 'Cetak Slip Pengembalian'), '#', ['class' => 'btn btn-success','id'=>'print-slip-pengembalian',]);
          ////////////////
          // echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-print"></span> '. Yii::t('app', 'Cetak Struk Pengembalian'), '#', ['class' => 'btn btn-primary','id'=>'print-struk-pengembalian',]);
          ////////////////
          echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-print"></span> '. Yii::t('app', 'Cetak Slip Pengembalian'),'#', ['class' => 'btn btn-success','id' => 'CetakSlip']);
            echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-print"></span> '. Yii::t('app', 'Cetak Struk Pengembalian'),'#', ['class' => 'btn btn-primary','id' => 'CetakStruk']);
          if($totalPelanggaran > 0 ) {
            echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-print"></span> '. Yii::t('app', 'Cetak Slip Pelanggaran'), '#', ['class' => 'btn btn-default','id'=>'print-slip-pelanggaran',]);
          ////////////////////////
            echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-print"></span> '. Yii::t('app', 'Cetak Struk Pelanggaran'), '#', ['class' => 'btn btn-warning','id'=>'print-struk-pelanggaran',]);
          ////////////////////////           
          }
          echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-remove"></span> '. Yii::t('app', 'Selesai'), ['index'], ['class' => 'btn btn-warning']);

          echo '</p>';
       }
      ?>
    </div>
  </h3>
</div>


<?php 
if (count($daftarItem) > 0 && $for != 'epm') {
?>
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
}
?>

<?php 
      if (count($daftarItem) > 0){

?>
<!-- KOLEKSI YANG TELAH DIKEMBALIKAN -->
<div style="text-align: center; font-size: 14px; background-color: #73b9d7; padding: 10px;
                      color: #fff; text-shadow: 0 1px 2px #222; margin-bottom: 3px; border-radius: 5px;">
                      <b><?= yii::t('app','KOLEKSI YANG TELAH DIKEMBALIKAN')?></b></div>
<div>


<div class="table-responsive">  
    <table class="table table-striped table-bordered table-hover table-condensed">
      <thead>
        <tr>
          <th width="40px" style="text-align: right">No</th>
          <th width="100px"><?= yii::t('app','No.Peminjaman')?></th>
          <th width="100px"><?= yii::t('app','No.Barcode')?></th>
          <th><?= yii::t('app','Judul')?></th>
          <th width="100px"><?= yii::t('app','Tgl.Pinjam')?></th>
          <th width="141px"><?= yii::t('app','Jatuh Tempo')?></th>
        <?php if ($for != 'epm') { ?>
          <th width="141px"><?= yii::t('app','Aksi')?></th>
        <?php }?>
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
    <?php if ($for != 'ep') { ?>
    <tr>
          <td colspan="11"><?='<pre>'; echo 'Name        : '.trim($key).'<br />No. Anggota : '.trim($items[0]['MemberNo']);'</pre>' ?></td>
    </tr>
    <?php } ?>
        <?php foreach ($items as $item):?>

        <?php
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
   
          if($for != 'epm' && date('Y-m-d') > $item['DueDate'])
            {
               $class = "class='danger'";
               
            }else{
              $class = "";
            } 
        ?>
        <tr <?=$class?> >
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

              echo (!$cekPelanggaranExist) ? Html::a('<span class="glyphicon glyphicon-info-sign"></span> '.yii::t('app','Pelanggaran'), Yii::$app->urlManager->createUrl(['sirkulasi/pengembalian/create-pelanggaran','loanID' => $item['NomorPinjam'],'member'=>$item['MemberID'],'loanItemID'=>$item['CollectionLoanItem_id'],'for'=>$for]), ['class' => 'btn btn-sm btn-danger','id'=>'create-pelanggaran',]) : '';
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
          <td colspan="5"><strong><?= yii::t('app','Total Koleksi Yang Dikembalikan : ')?></strong></td>
          <td style="text-align: right">&nbsp;</td>
          <td style="text-align: right">
            <strong><?=$totalItem?></strong>
          </td>
        </tr>
      </tfoot>
    </table>
    </div>

      
<?php
    $pelanggaran = $daftarItem['0']->pelanggarans;
  } //end count
?>


      <?php 
if ($for != 'epm') {
      if ($totalPelanggaran > 0){
        $no=1;
      ?>
<!-- KOLEKSI YANG TERKENA PELANGGARAN -->
<div style="text-align: center; font-size: 14px; background-color: #73b9d7; padding: 10px;
                      color: #fff; text-shadow: 0 1px 2px #222; margin-bottom: 3px; border-radius: 5px;">
                      <b><?= yii::t('app','KOLEKSI YANG TERKENA PELANGGARAN')?></b></div>
<!-- <div> -->


    <div class="table-responsive">  
    <table class="table table-striped table-bordered table-hover table-condensed">
      <thead>
        <tr>
          <th width="40px" style="text-align: right">No</th>
          <th width="100px"><?= yii::t('app','No.Peminjaman')?></th>
          <th width="100px"><?= yii::t('app','No.Barcode')?></th>
          <th width="300px"><?= yii::t('app','Judul')?>/th>
          <th width="100px"><?= yii::t('app','Jatuh Tempo')?></th>
          <th width="100px"><?= yii::t('app','Terlambat')?></th>
          <th width="141px"><?= yii::t('app','Pelanggaran')?></th>
          <th width="141px"><?= yii::t('app','Jenis Denda')?></th>
          <th width="141px"><?= yii::t('app','Jumlah Denda')?></th>
          <th width="141px"><?= yii::t('app','Jumlah Skorsing')?></th>
        </tr>
      </thead>
      <tbody>

        <?php foreach ($pelanggaran as $item):?>
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
              $late = \common\components\SirkulasiHelpers::lateDays($item->collectionLoanItem->ActualReturn ,date("Y-m-d", strtotime($item->collectionLoanItem->DueDate)));
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
          <td colspan="8"><strong><?= yii::t('app','Total Koleksi Yang Terkena Pelanggaran : ')?></strong></td>
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
} 
?>
<?php 
$HapusItemUrl      = Url::to('hapus-item');
$token          = Yii::$app->request->csrfToken;
$url = Url::to(['print/cetak-slip-pengembalian']);
//////
$urlStruk = Url::to(['print/cetak-struk-pengembalian']);
$transactionID = $daftarItem[0]['CollectionLoan_id'];
$this->registerJs("
    $('#print-slip-pelanggaran').click(function() {
        var url = '".Url::to(['print/cetak-slip-pelanggaran','NoPinjam'=>$daftarItem[0]['CollectionLoan_id']])."';
          $('#Iframe1Slip').attr('src',url);
        });

    $('#CetakSlip').click(function() {
     
        $.get('".$url."', {NoPinjam: ".$transactionID."},function(data, status){
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


    $('#CetakStruk').click(function() {
     
        var url = '".Url::to(['print/cetak-struk-pengembalian','NoPinjam'=>$daftarItem[0]['CollectionLoan_id']])."';
          $('#Iframe1Slip').attr('src',url);
    });


    $('#print-struk-pelanggaran').click(function() {
        var url = '".Url::to(['print/cetak-struk-pelanggaran','NoPinjam'=>$daftarItem[0]['CollectionLoan_id']])."';
          $('#Iframe1Slip').attr('src',url);
        });





  ");


?>

