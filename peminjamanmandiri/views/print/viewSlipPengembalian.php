<?php
$dates              = new DateTime();
$dateNow            = $dates->format('d-m-Y');
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
        <!-- info row -->
        <div class="row invoice-info">
         <?php //var_dump($model[0]['UpdateDate'])?>
          <div class="col-sm-12 ">
            <b>No.Transaksi #<?=$collectionLoan_id;?></b><br>
            <br>
            <b>No.Anggota   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b> <?=$model[0]->member->MemberNo;?><br>
            <b>Nama Anggota &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b> <?=$model[0]->member->Fullname?><br>
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
                  <th>Jatuh Tempo</th>
                  <th>Tgl Kembali</th>
                </tr>
              </thead>
              <tbody>
               <?php
                //var_dump($model->collectionloanitems);  
                foreach ($model as $row) {
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
                            <td >
                                <?=date('d/m/Y', strtotime($row->ActualReturn));?>
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
              <div class="col-xs-4">
               <h5>Anggota</h5>
               <br/><br/><br/><br/> <b>( <?=$model[0]->member->Fullname?> )</b> 
               </div>
            </center>
          </div><!-- /.col -->
        
      </section><!-- /.content -->
    
</div><!-- /.row -->               
