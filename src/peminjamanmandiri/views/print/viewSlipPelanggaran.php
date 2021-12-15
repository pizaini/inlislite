<?php
$dates              = new DateTime();
$dateNow            = $dates->format('d-m-Y');
?>

<div class=""  >

<div class="wrapper">

<!-- Main content -->
      <section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h5 class="page-header">
              <i class="fa fa-globe"></i> <?=Yii::$app->config->get('NamaPerpustakaan'); ?>
              <small class="pull-right">Slip Pelanggaran Koleksi</small>
            </h5>
          </div><!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
         <?php //var_dump($model[0]['UpdateDate'])?>
          <div class="col-sm-12">
            <b>No.Transaksi #<?=$collectionLoan_id;?></b><br>
            <br>
            <b>No.Anggota   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b> <?=$model[0]->member->MemberNo;?><br>
            <b>Nama Anggota &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b> <?=$model[0]->member->Fullname?><br>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- Table row -->
        <div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped" >
              <thead>
                <tr>
                  <th>No.Barcode</th>
                  <th>Judul</th>
                  <th>Jatuh Tempo</th>
                  <th>Tgl Kembali</th>
                  <th>Keterlambatan(Hari)</th>
                  <th>Jumlah Denda(Rp.)</th>
                  <th>Jumlah Skorsing(Hari)</th> 
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
                                <?=date('d/m/Y', strtotime($row->collectionLoanItem->DueDate));?>
                            </td>
                            <td >
                                <?=date('d/m/Y', strtotime($row->collectionLoanItem->ActualReturn));?>
                            </td>
                            <td >
                                <?php 

                                  $late = \common\components\SirkulasiHelpers::lateDays($row->collectionLoanItem->ActualReturn ,date("Y-m-d", strtotime($row->collectionLoanItem->DueDate)));
                                  if($late > 0){
                                    $html = $late.' Hari';
                                  }else{
                                    $html = '0 Hari';
                                  }
                                  echo $html;
                                  
                                ?>
                            </td>
                            <td >
                                <?=$row->JumlahDenda;?>
                            </td>
                            <td >
                                <?=$row->JumlahSuspend;?>
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
              <div class="col-xs-5">
                <h5>Petugas</h5>
                
                <br/><br/><br/><br/> <b>( . . . . . . . )</b>
              </div><!-- /.col -->
              <div class="col-xs-5">
               <h5>Anggota</h5>
               <br/><br/><br/><br/> <b>( <?=$model[0]->member->Fullname?> )</b> 
               </div>
            </center>
          </div><!-- /.col -->
        
      </section><!-- /.content -->
    
</div><!-- /.row -->               
</div>

