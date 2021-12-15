<?php 
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\grid\GridView;
use yii\widgets\Pjax;


use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use common\widgets\AjaxButton;

?>
<?php 
$url2           = Url::to('hapus-item');
?>

<?php 
      if (count($daftarItem) > 0){
      ?>
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
  </div><!-- /.tab-content-->
</div><!-- /.nav-tabs-custom -->
<!-- /.ANGGOTA AREA -->

<?php
}
?>

<!-- KOLEKSI YANG AKAN DIKEMBALIKAN -->
<div style="text-align: center; font-size: 14px; background-color: #73b9d7; padding: 10px;
                      color: #fff; text-shadow: 0 1px 2px #222; margin-bottom: 3px; border-radius: 5px;">
                      <b><?= yii::t('app','KOLEKSI YANG AKAN DIPERPANJANG') ?></b></div>
<div>

      <?php 
      if (count($daftarItem) > 0){
      ?>
    <div class="table-responsive">  
    <table class="table table-striped table-bordered table-hover table-condensed">
      <thead>
        <tr>
          <th width="40px" style="text-align: right">No</th>
          <th width="100px"><?= yii::t('app','No.Peminjaman')?></th>
          <th width="100px"><?= yii::t('app','No.Barcode')?></th>
          <th width="250px"><?= yii::t('app','Judul')?></th>
          <th width="100px"><?= yii::t('app','Penerbit')?></th>
          <!--<th width="100px"><?= yii::t('app','Tgl.Pinjam')?></th>-->
          <!--<th width="100px">Jatuh Tempo</th>-->
          <th width="100px"><?= yii::t('app','Tgl. Perpanjangan')?></th>
          <!--<th width="100px">Hari Terlambat</th>-->
          <th width="100px"><?= yii::t('app','Jatuh Tempo Perpanjangan')?></th>
          <th width="50px"><?= yii::t('app','Perpanjangan')?></th>
          <th width="40px">&nbsp;</th>
        </tr>
      </thead>
      <tbody>

        <?php foreach ($daftarItem as $item): ?>
        <?php
          $totalItem = count($daftarItem);
           $data = [
                'CollectionLoanItem_id'=>trim($item["CollectionLoanItem_id"]),
                'Collection_id'=>trim($item["Collection_id"]),
                'NomorPinjam' => trim($item["NomorPinjam"]),
                'NomorBarcode' => trim($item["NomorBarcode"]),
                'MemberID' => trim($item["MemberID"]),
                'Title' =>  trim($item["Title"]),
                'Penerbit' =>  trim($item["Penerbit"]),
                'TglPinjam'=>trim($item["TglPinjam"]),
                'DueDate'=>trim($item["DueDate"]),
                'TglPerpanjang'=>trim($item["TglPerpanjang"]),
                'DueDatePerpanjang'=>trim($item["DueDatePerpanjang"]),
                'CountPerpanjang'=>trim($item["CountPerpanjang"])
              ];

               // $late = \common\components\SirkulasiHelpers::lateDays(date('Y-m-d') ,date("Y-m-d", strtotime($item["DueDate"])));
               $late = \common\components\SirkulasiHelpers::lateDays($item["TglPerpanjang"] ,date("Y-m-d", strtotime($item["DueDate"])));
               
           //if(date('Y-m-d') > $item['TglKembali'])
            if( $late > 0)
            {
               $class = "class='danger'";
               if(Yii::$app->sirkulasi->checkItemPelanggaran(trim($item["NomorBarcode"]))){
                  // Jika ada
                  //throw new \yii\web\HttpException(404, 'Item dengan No.barcode : '.trim($model[0]["NomorBarcode"]).' sudah ada di list pengembalian!');
                }else{
                  // add
                  --$totalItem;
                  continue;
                  // Yii::$app->sirkulasi->addItemPelanggaran($data); ----------------------------tidak bisa di perpanjang karena ada pelanggaran
                }
            }else{
              $class = "";
              if(Yii::$app->sirkulasi->checkItemPerpanjanganSafe(trim($item["NomorBarcode"]))){
                  // Jika ada
                  //throw new \yii\web\HttpException(404, 'Item dengan No.barcode : '.trim($model[0]["NomorBarcode"]).' sudah ada di list pengembalian!');
                }else{
                  // add to safe item
                  Yii::$app->sirkulasi->addItemPerpanjanganSafe($data);
                }

            } 
        ?>
        <tr <?=$class?> >
          <td style="text-align: right"><?php echo $n++; ?></td>
          <td><?php echo trim($item['NomorPinjam']); ?></td>
          <td><?php echo trim($item['NomorBarcode']); ?></td>
          <td><?php echo $item['Title']; ?></td>
          <td style="text-align: left">
           <?php echo $item['Penerbit']; ?>
          </td>
          <!--<td style="text-align: left"> -->
            <?php  
              // echo \common\components\Helpers::DateTimeToViewFormat($item['TglPinjam']);
             ?>
          <!--</td> -->
          <!-- <td style="text-align: left"> -->
            <?php  
              // echo $DueDate = \common\components\Helpers::DateTimeToViewFormat($item['DueDate']);
             ?>
          <!-- </td> -->
          <td style="text-align: left">
            <?php  
              echo \common\components\Helpers::DateTimeToViewFormat($item['TglPerpanjang']);
             ?>
          </td>
          <!-- <td style="text-align: left"> -->
            <?php  
               $late = \common\components\SirkulasiHelpers::lateDays(date('Y-m-d') ,date("Y-m-d", strtotime($DueDate)));
                   if($late > 0){
                            // $html = '<span class="label label-danger">'.$late.' Hari</span>';
                            $html = $late;
                        }else{
                            // $html = '<span class="label label-warning">'.$late.' Hari</span>';
                            $html = $late;
                        }
                    
                    // echo $html;
             ?>
          <!--</td>-->
          <td style="text-align: left">
            <?php  
              echo \common\components\Helpers::DateTimeToViewFormat($item['DueDatePerpanjang']);
             ?>
          </td>
          <!--</td>-->
          <td style="text-align: left">
            <?php  
              echo 'Ke - '.++$item['CountPerpanjang'];
             ?>
          </td>
          <td style="text-align: center">
            <?php
            /*$product_id = null;

            if (!empty($product)) {
              $product_id = $product->id;
            }*/
            ?>
            <?php
            echo AjaxButton::widget([
              'label' => '<i class="glyphicon glyphicon-remove"></i> ' .Yii::t('app','Batal'),
              'ajaxOptions' => [
                    'type' => 'POST',
                    'url'  => $url2,
                    'data' => array(
                            'NomorBarcode' => trim($item['NomorBarcode']),
                            'index'=> ($n - 2)
                        ),
                   'success'=>new yii\web\JsExpression('function(data){ 
                      $("#koleksi-item").html(data); 
                    }'),
                   'error' => new yii\web\JsExpression('function(xhr, ajaxOptions, thrownError){ 
                            alert(xhr.responseText); 
                            $("#peminjamanitemform-nomorbarcode").val("");
                            $("#peminjamanitemform-nomorbarcode").focus();
                          }'),
                ],
              'htmlOptions' => [
              'class' => 'btn btn-danger btn-sm',
              'data-toggle'=>"tooltip",
              'title'=>"Batal",
              'id' => 'hapus-'.$n,
              'type' => 'submit'
              ]
              ]);
            ?>   
           
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="5"><strong><?= yii::t('app','Total Koleksi Yang Diperpanjang : ')?></strong></td>
          <td style="text-align: right">&nbsp;</td>
          <td style="text-align: right">
            <strong><?=$totalItem?></strong>
          </td>
        </tr>
      </tfoot>
    </table>
    </div>
   <?php 
      }else{
        $this->registerJs("
  
  $('#btn-simpan').prop('disabled', true);

  
  
  
");
      }
   ?>

    <table style="width: 30%; margin-top: 50px;">
        <tbody>
            <tr>
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
    <!-- ./KOLEKSI YANG AKAN DIKEMBALIKAN -->