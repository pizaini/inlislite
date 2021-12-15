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
              <i class="fa fa-globe"></i> <?=Yii::$app->config->get('NamaPerpustakaan'); ?>
              <small class="pull-right">Slip Peminjaman Koleksi</small>
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
            <b>Tanggal Transaksi :</b> <?=$dateNow;?>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- Table row -->
        <div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>No.Barcode</th>
                  <th>Judul</th>
                  <th>Tgl Harus Kembali</th>
                </tr>
              </thead>
              <tbody>
               <?php
                //var_dump($model->collectionloanitems);  
                foreach ($model->collectionloanitems as $row) {
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
                                <?=date('d/m/Y', strtotime($row->DueDate));?>
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
              <div class="col-xs-6">
                <h5>Petugas</h5>
                
                <br/><br/><br/><br/> <b>( . . . . . . . )</b>
              </div><!-- /.col -->
              <div class="col-xs-6">
               <h5>Peminjam</h5>
               <br/><br/><br/><br/> <b>( . . . . . . . )</b> 
               </div>
            </center>
          </div><!-- /.col -->
        
      </section><!-- /.content -->
    
</div><!-- /.row -->               
