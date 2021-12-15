<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<?php
$dates              = new DateTime();
$dateNow            = $dates->format('d-m-Y');
?>
<div class="wrapper">

<!-- Main content -->
      <section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h5 class="page-header">
              <i class="glyphicon glyphicon-globe"></i> <?=Yii::$app->config->get('NamaPerpustakaan'); ?>
              <small class="pull-right">Slip Perpanjangan Koleksi</small>
            </h5>
          </div><!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
         
          <div class="col-sm-12 ">
            <b>No.Transaksi #<?=$collectionLoan_id;?></b><br>
            <br>
            <b>No.Anggota   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b> <?=$model->member->MemberNo;?><br>
            <b>Nama Anggota &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b> <?=$model->member->Fullname?><br>
            <!-- <b>Tanggal Perpanjangan :</b> <?=$dateNow;?> -->
          </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- Table row -->
        <div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped" style="font-size: 12px;">
              <thead>
                <tr>
                  <th>No.Barcode</th>
                  <th>Judul</th>
                  <th>Tanggal Perpanjang</th>
                  <th>Tgl Harus Kembali</th>
                </tr>
              </thead>
              <tbody>
               <?php
                // echo'<pre>';print_r($model->collectionloanextends);die;
                // foreach ($model->collectionloanitems as $row) {
                
                  foreach ($model->collectionloanextends as $row) {
                    # code...
                ?>
                    <tr>
                            <td>
                                <?=$row->collection->NomorBarcode;?>
                            </td>
                            <td >
                                <?=$row->collection->catalog->Title;?>
                            </td>
                            <td >
                                <?=date('d/m/Y', strtotime($row->DateExtend));?>
                            </td>
                            <td >
                                <?=date('d/m/Y', strtotime($row->DueDateExtend));?>
                            </td>
                        </tr>
                <?php
                    
                  }
                
                
                ?>
                
                
              </tbody>
            </table>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="row">
          <center>
              <!-- accepted payments column -->
              <div class="col-sm-6">
                <h5>Petugas</h5>
                
                <br/><br/><br/><br/> <b>( . . . . . . . )</b>
              </div><!-- /.col -->
              <div class="col-sm-6">
               <h5>Peminjam</h5>
               <br/><br/><br/><br/> <b>( . . . . . . . )</b> 
               </div>
            </center>
          </div><!-- /.col -->
        
      </section><!-- /.content -->
    
</div><!-- /.row -->               
