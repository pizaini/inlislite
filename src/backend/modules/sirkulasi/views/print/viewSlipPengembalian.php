<?php
$dates              = new DateTime();
$dateNow            = $dates->format('d-m-Y');
// echo '<pre>'; print_r($daftarItem); echo '</pre>';
?>

<div class="wrapper" onload="window.print();">
<!-- Main content -->
      <section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h5 class="page-header">
              <i class="fa fa-globe"></i> <?=Yii::$app->config->get('NamaPerpustakaan'); ?>
              <small class="pull-right">Slip Pengembalian Koleksi</small>
            </h5>
          </div><!-- /.col -->
        </div>
<?php 
// echo '<pre>'; print_r($data);'</pre>';
// echo '<pre>'; print_r($daftarItem);'</pre>';
$truecats = array();
foreach ($daftarItem as $key => $pieces) {
    if (!isset($truecats[$pieces['Fullname']])) {
        $truecats[$pieces['Fullname']] = array();
    }
    $truecats[$pieces['Fullname']][] = $pieces;
}
// echo '<pre>';print_r($for);echo '</pre>';
?>

  <?php foreach ($truecats as $key=>$items)
  { 

echo '<div class="row invoice-info">';
  echo '<div class="col-sm-12 ">'; 
  echo '<b> No.Transaksi #'.implode(' , #',array_column($items,'NomorPinjam')); echo '<br >';
  echo 'Nama Anggota #'.trim($key); 
  echo '</b> <div>';
echo '<div>';

  ?>
<div class="row">
  <div class="col-xs-12 table-responsive">
    <table class="table table-striped">
    <thead>
        <tr>
          <th width="100px">No.Barcode</th>
          <th>Judul</th>
          <th width="100px">Jatuh Tempo</th>
          <th width="100px">Tgl Kembali</th>
        </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $item): 
        echo '<tr>
            <td>'.trim($item['NomorBarcode']).'</td>
            <td>'.trim($item['Title']).'</td>
            <td>'.\common\components\Helpers::DateTimeToViewFormat($item['DueDate']).'</td>
            <td>'.\common\components\Helpers::DateTimeToViewFormat($item['TglKembali']).'</td>
        </tr>';
      endforeach; ?>
    </tbody>
  </table>
  <div>
<div>
    <?php 
    
  }
    ?>
      

        <div class="row">
          <center>
              <!-- accepted payments column -->
              <div class="col-xs-6">
                <h5>Petugas</h5>
                
                <br/><br/><br/><br/> <b>( . . . . . . . )</b>
              </div><!-- /.col -->
            <?php if ($for != 'epm') { ?>
              <div class="col-xs-4">
               <h5>Anggota</h5>
               <br/><br/><br/><br/> <b>( <?=$model[0]->member->Fullname?> )</b> 
               </div>
            <?php } ?>
            </center>
          </div><!-- /.col -->
        
      </section><!-- /.content -->
    
</div><!-- /.row -->               
