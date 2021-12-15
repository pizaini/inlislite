<?php
$dates              = new DateTime();
$dateNow            = $dates->format('d-m-Y');
?>
<style type="text/css">

    .wrapper
    {
      font-family:Arial;
      font-size:10px; 
      padding-bottom:5px; 
    }

    .row
    {
      margin-bottom: 15px;
    }

    .row pull-right
    {
      margin-left: 155px;
      font-size:8px; 
    }
    .invoice-item
    {
        background: white;
        border: thin solid #C0C0C0; 
        margin-left:auto; 
        margin-right:auto; 
        width: 100%;
        font-family:Arial; 
        font-size:11px
    }

    .invoice-item > tbody > tr > td
    {
        vertical-align:top;
        padding: 2px; 
        border: thin solid #C0C0C0; 
        font-weight: bold; 
        color: #000;
    }

    .item-content-invoice > td
    {
        font-weight: normal;
    }

    .invoice-signature
    {
        background: white; 
        width: 100%;
        font-family:Arial; 
        font-size:11px
    }

    .invoice-signature > tbody > tr > td
    {
        padding-top:10px; 
        vertical-align:top; 
        font-weight: bold; 
        color: #000;
    }
    .name-field-signature > td
    {
        vertical-align:top; 
        font-weight: bold; 
        color: #000;
    }
</style>
<div class="wrapper">

<!-- Main content -->
      <section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h3 class="page-header">
              <i class="fa fa-globe"></i> <?=Yii::$app->config->get('NamaPerpustakaan'); ?>
              <small class="pull-right">Slip Peminjaman Koleksi</small>
            </h3>
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
        </div>
        <br/>
</div><!-- /.row -->

        <!-- Table row -->
        <div class="row">
          <div class="col-xs-12 table-responsive">
            <table align="center" cellpadding="5" cellspacing="0" class="invoice-item">
            <tbody>
                <tr>
                    <!-- <td align="center" width="10%"> Nomor</td> -->
                    <!-- <td align="center" style="" width="25%"> Item ID</td> -->
                    <td align="center" style="" width="30%"> No. Barcode</td>
                    <td align="center" style="" width="40%">Judul</td>
                    <td align="center" style="" width="30%">Tgl Harus Kembali</td>
                </tr>

                <?php foreach ($model->collectionloanitems as $row): ?>
                <tr class="item-content-invoice">
                    <td align="left" width="30%">
                        <span><?=$row->collection->NomorBarcode;?></span>
                    </td>
                    <td align="left" width="40%">
                        <span><?=$row->collection->catalog->Title;?></span>
                    </td>
                    <td align="left" width="30%">
                        <span><?=date('d/m/Y', strtotime($row->DueDate));?></span>
                    </td>
                </tr>
                <?php endforeach ?>

            </tbody>
            </table>
        <br>
        <br>

        <table cellpadding="4px" cellspacing="0" class="invoice-signature">
            <tbody>
                <tr>
                    <td align="Center" width="40%">Petugas</td>
                    <td width="8%">&nbsp;</td>
                    <td></td>
                    <td align="Center" width="42%">Anggota</td>
                </tr>
                <tr>
                    <td align="Center" width="40%">&nbsp;</td>
                    <td width="8%">&nbsp;</td>
                    <td></td>
                    <td align="Center"  style="padding-top:50px" width="42%">&nbsp;</td>
                </tr>

                <tr class="name-field-signature">
                    <td align="Center" width="40%">( . . . . . . . )</td>
                    <td width="8%">&nbsp;</td>
                    <td></td>
                    <td align="Center" width="42%">( . . . . . . . )</td>
                </tr>
            </tbody>
        </table>
          </div><!-- /.col -->
        </div><!-- /.row -->
        
      </section><!-- /.content -->
        
