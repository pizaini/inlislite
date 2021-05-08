<?php
$dates              = new DateTime();
$dateNow            = $dates->format('d-m-Y');
?>


<style type="text/css">
    .invoice-header
    {
        text-align: center;
        font-family:Arial;
        font-size:13px; 
        padding-bottom:5px; 
        color:#000;
        margin-bottom:3px; 
        border-radius:5px;
        width: 100%
    }
    .invoice-detail
    {
        background: white; 
        width: 100%;
        font-family:Arial; 
        font-size:11px
    }

    .invoice-detail > tbody > tr > td
    {
        vertical-align:top;
        padding: 2px; 
        color: #000;
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

<div onload="window.print();">
    <div>

        <div class="invoice-header">
            <b>Struk Pengembalian Koleksi<br><?=Yii::$app->config->get('NamaPerpustakaan'); ?><br></b>
        </div>

        <table cellpadding="px" cellspacing="0" class="invoice-detail">
            <tbody>
                <tr>
                    <td align="left" class="" width="25%">No.Transaksi </td> 
                    <td width="5%">:</td> 
                    <td width="35%">#<?=$collectionLoan_id;?> </td> 
                </tr>
                <tr>
                    <td align="left" class="" width="25%">Nomor Anggota </td> 
                    <td width="5%">:</td> 
                    <td width="35%"><?=$model[0]->member->MemberNo;?></td> 
                </tr>
                <tr>
                    <td align="left"  width="25%">Nama </td> 
                    <td  width="5%">:</td> 
                    <td  width="35%"><?=$model[0]->member->Fullname?></td> 
                </tr>
                          
            </tbody>
        </table>
        <br>


        <table align="center" cellpadding="5" cellspacing="0" class="invoice-item">
            <tbody>
                <tr>
                    <!-- <td align="center" width="10%"> Nomor</td> -->
                    <!-- <td align="center" style="" width="25%"> Item ID</td> -->
                    <td align="center" width="20%">No. Barcode</td>
                    <td align="center" width="40%">Judul</td>
                    <td align="center" width="20%">Jatuh Tempo</td>
                    <td align="center" width="20%">Tgl Kembali</td>
                </tr>

                <?php foreach ($model as $row): ?>
                <tr class="item-content-invoice">
                    <td align="left" width="15%">
                        <span><?=$row->collection->NomorBarcode;?></span>
                    </td>
                    <td align="left" width="40%">
                        <span><?=$row->collection->catalog->Title;?></span>
                    </td>
                    <td align="left" width="20%">
                        <span><?=date('d/m/Y', strtotime($row->DueDate));?></span>
                    </td>
                    <td align="left" width="20%">
                        <span><?=date('d/m/Y', strtotime($row->ActualReturn));?></span>
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
                    <td align="Center" width="40%">(<span style="margin-left:90%; "></span>)</td>
                    <td width="8%">&nbsp;</td>
                    <td></td>
                    <td align="Center" width="42%">( <?=$model[0]->member->Fullname?> )</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">window.print();</script>