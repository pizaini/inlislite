<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use opac\assets_b\AppAsset;
use common\widgets\Alert;
use kartik\datecontrol\DateControl;
use kartik\datetime\DateTimePicker;
use kartik\widgets\DatePicker;
use common\models\Collections;
use common\models\Refferenceitems;
use common\models\Worksheets;
use common\components\OpacHelpers;
use common\models\LocationLibrary;
use common\components\DirectoryHelpers;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use common\models\CollectionSearchKardeks;


$ids=(int)$catalogID;
$pencarian_url=Yii::$app->urlManager->createAbsoluteUrl('pencarian-sederhana');
$base=Yii::$app->homeUrl;
$pengarang=array_values(array_filter(preg_split("/((--)|[|])/", $detailOpac[0]['PENGARANG'])));
$catatan=array_values(array_filter(preg_split("/((--)|[|])/", $detailOpac[0]['CATATAN'])));
$subyek=preg_split("/([|])/", $detailOpac[0]['SUBJEK']);
$penerbitan=preg_split("/([|])/", $detailOpac[0]['PENERBITAN']);
$ISBN=preg_split("/([|])/", $detailOpac[0]['ISBN']);
$ISMN=preg_split("/([|])/", $detailOpac[0]['ISMN']);
$ISSN=preg_split("/([|])/", $detailOpac[0]['ISSN']);
$KONTEN=preg_split("/([|])/", $detailOpac[0]['KONTEN']);
$MEDIA=preg_split("/([|])/", $detailOpac[0]['MEDIA']);
$PENYIMPANAN_MEDIA=preg_split("/([|])/", $detailOpac[0]['PENYIMPANAN_MEDIA']);
$LOKASI_AKSES_ONLINE=preg_split("/([|])/", $detailOpac[0]['LOKASI_AKSES_ONLINE']);
$ABSTRAK=preg_split("/([|])/", $detailOpac[0]['ABSTRAK']);
$DESKRIPSI_FISIK=preg_split("/([|])/", $detailOpac[0]['DESKRIPSI_FISIK']);
$PERNYATAAN_SERI=preg_split("/([|])/", $detailOpac[0]['PERNYATAAN_SERI']);
$EDISI=preg_split("/([|])/", $detailOpac[0]['EDISI']);
$LOKASI_AKSES_ONLINE=preg_split("/([|])/", $detailOpac[0]['LOKASI_AKSES_ONLINE']);
$INFORMASI_TEKNIS=preg_split("/([|])/", $detailOpac[0]['INFORMASI_TEKNIS']);


$isBooking= Yii::$app->config->get('IsBookingActivated');
$this->registerJS('
$(document).ready(function() {
    $(\'#detail\').DataTable(
        {
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": true
        });
} );


    ');
$urlcover;
if($detailOpac[0]['CoverURL'])
{
     if(file_exists(Yii::getAlias('@uploaded_files/sampul_koleksi/original/'.DirectoryHelpers::GetDirWorksheet($detailOpac[0]['Worksheet_id']).'/'.$detailOpac[0]['CoverURL'])))
    {
        $urlcover= '../uploaded_files/sampul_koleksi/original/'.DirectoryHelpers::GetDirWorksheet($detailOpac[0]['Worksheet_id']).'/'.$detailOpac[0]['CoverURL'];
    }
    else {
        $urlcover= '../uploaded_files/sampul_koleksi/original/Monograf/tdkada.gif';
    }

}else{
    $urlcover= '../uploaded_files/sampul_koleksi/original/Monograf/tdkada.gif';
}

Url::remember();
AppAsset::register($this);
$homeUrl=Yii::$app->homeUrl;
$base=Yii::$app->controller->route;
$dateNow = new \DateTime("now");
$bahasa = Refferenceitems::find()
    ->where(['Refference_id' => 5])
    ->all();
$bentukKarya = Refferenceitems::find()
    ->where(['Refference_id' => 17])
    ->all();
$targetPembaca = Refferenceitems::find()
    ->where(['Refference_id' => 2])
    ->all();
$Worksheets= OpacHelpers::sortWorksheets(Worksheets::find()->asArray()->all());

$namaruang = Yii::$app->request->cookies->getValue('location_opac_name')['Name'];
$namalokperpus = Yii::$app->request->cookies->getValue('location_detail_opac')['Name'];
$namaperpus = ($namalokperpus && $namaruang) ? $namalokperpus." - ".$namaruang :Yii::$app->config->get('NamaPerpustakaan');
$alamat = Yii::$app->request->cookies->getValue('location_detail_opac')['Address'];

if(!Yii::$app->user->isGuest){
    $noAnggota = \Yii::$app->user->identity->NoAnggota;
    $this->title = 'Online Public Access Catalog - Perpusnas RI';
    $booking = Collections::find()
                    ->select([
                        'collections.BookingExpiredDate',
                        'catalogs.Title',
                    ])
                    ->leftJoin('catalogs', '`catalogs`.`ID` = `collections`.`Catalog_id`')
                    ->andWhere('BookingMemberID ="' . $noAnggota.'"')
                    ->andWhere('BookingExpiredDate >  "' . $dateNow->format("Y-m-d H:i:s") . '"')
                    ->all();

}

else {
   
    $booking=null;  
}
    if(sizeof($booking)==0){
        $this->registerJS('
        $(document).ready(
            function() {
                $(\'a.bookmarkShow\').hide();
            }
        );
        ');



    }else{
        $this->registerJS('

        $(document).ready(
            function() {
                $(\'a.bookmarkShow\').show();
                $(\'a.bookmarkShow\').text(\'Keranjang('.sizeof($booking).')\');
            }
        );
        ');

    }

    $pengarangs = '';
    for($x=0;$x<sizeof($pengarang);$x++){
        $pengarangs .= $pengarang[$x].'; ';
    }
    $pengarangs = rtrim($pengarangs, '; ');

    $penerbitans = '';
    for($x=0;$x<sizeof($penerbitan);$x++){
        $penerbitans .= $penerbitan[$x].'; ';
    }
    $penerbitans = rtrim($penerbitans, '; ');

    $isbns = '';
    for($x=0;$x<sizeof($ISBN);$x++){
        $isbns .= $ISBN[$x].'; ';
    }
    $isbns = rtrim($isbns, '; ');
?>   
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="<?=$pengarangs?>">
    <meta name="description" content="<?=$penerbitans?>, <?php echo Yii::t('app', 'ISBN') ?>: <?=$isbns?>">
    <?= Html::csrfMetaTags() ?>
    <title><?=$detailOpac[0]['JUDUL']?> | <?= $namaperpus ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" type='image/x-icon' href="<?=Yii::$app->urlManager->createUrl('../uploaded_files/aplikasi/favicon.png');?>">


    <!--  <link rel="shortcut icon" type='image/x-icon' href="../..<?=Yii::getAlias('@upload')."/aplikasi/favicon.png"?>"> -->

    <?php $this->head() ?>

</head>


<body class="skin-blue layout-top-nav">
    <div class="wrapper" style=" background-color: #FFF;">
        <!-- Header web -->
        <header class="main-header">
            <!-- navbar -->
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <div class="title">
                           
                            <div class="image"><img src="<?= Yii::$app->urlManager->createUrl('../uploaded_files/aplikasi/logo_perpusnas_2015.png') ?>" class="img-logo" height="65" width="70"></div>
                            <div class="text">
                                <h3 style="margin-top: 20px;">Online Public Access Catalog</h3>
                                <div class="clear"></div>
                                <div class="time"><?= $namaperpus ?></div><br/>
                                <div class="clear"></div>
                                <div class="timeddr" style="margin-bottom: 10px"><?= $alamat ?></div>
                                <div class="clear"></div>
                            </div>
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                                <i class="fa fa-bars"></i>
                            </button>
                            <div class="clear">
                            </div>
                        </div>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-right" id="navbar-collapse">
                        <span class="pull-right" style="color:#fff; margin-right:5px; margin-top:5px" id="clocktime" ></span><br>
                        <ul class="nav navbar-nav pull-right">        
            
                    <li> <a class="bookmarkShow" href="javascript:void(0)"  onclick='tampilBooking()' ></a> </li>     
                    <li> <a href="<?php echo $homeUrl."bookmark"; ?>"><?= Yii::t('app', 'Tampung')?></a> </li>
                      <?php 
                      if (Yii::$app->user->isGuest) {
                        echo"

                         <li> <a href=\"javascript:void(0)\"  onclick='tampilLogin()'>Login</a> </li>
                        <li> <a href=\"../".Url::to('pendaftaran')."\">" .Yii::t('app', 'Registrasi')."</a> </li>
                        ";
                      } else {
                        echo"
                        <li> <a href=\"".$homeUrl."site/logout  \">Logout (" . $noAnggota.")</a> </li>

                        ";

                        $_SESSION['__NoAnggota']= $noAnggota;
                      }
                      ?>
                          
                  </ul>

                    </div><!-- /.navbar-collapse -->
                    <!-- Navbar Right Menu -->
                    <!-- /.navbar-custom-menu -->
                </div><!-- /.container-fluid -->
            </nav>
            <!-- /navbar -->
        </header>

          <section class="content-search">
                  <div class="container">
                    <div class="row">
                      <div class="col-sm-12">
                      <span class="nav-tabs-custom">
                          <ul class="nav nav-tabs">
                            <li class="active"> <a href="#tab_1" data-toggle="tab"><?= Yii::t('app', 'Cari')?></a> </li>
                    <li> <a href="<?= $homeUrl; ?>browse">Browse</a> </li>

                          </ul>
                        </span>
                        </div>
                        </div>
                    <form action="<?php echo $homeUrl; ?><?=$indexer?>" method="GET"/>
                    <input type="hidden" name="action" value="pencarianSederhana"/>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                  <div class="col-sm-4"><div class="form-group">
                          <input type="text" class="form-control" name="katakunci" id="KataKunci" placeholder='<?= Yii::t('app', 'Kata Kunci')?>' size="25" ></div></div>
                      <div class="col-sm-4"><div class="form-group">

                    <select  class="form-control" name="ruas">
                      <option value="Judul"><?= Yii::t('app', 'Judul')?></option>
                      <option value="Pengarang"><?= Yii::t('app', 'Pengarang')?></option>
                      <option value="Penerbit"><?= Yii::t('app', 'Penerbitan')?></option>
                      <option value="Subyek"><?= Yii::t('app', 'Subyek')?></option>
                      <option value="Nomor Panggil"><?= Yii::t('app', 'Nomor Panggil')?></option>
                      <option value="ISBN">ISBN</option>
                      <option value="ISSN">ISSN</option>                    
                      <option value="ISMN">ISMN</option>
                      <option value="Semua Ruas"><?= Yii::t('app', 'Sembarang')?></option> 

                    </select>
                              </form>
                      </div></div>
                      <div class="col-sm-3"><div class="form-group">

                              <select class="form-control" name="bahan" onChange="getData(this);" >
                      <?php
                      for ($i=0; $i <sizeof($Worksheets) ; $i++) { 
                      echo"<option value ='".$Worksheets[$i]['ID']."'>".$Worksheets[$i]['Name']."</option> ";
                      }
                      ?>

                      <option value="Semua Jenis Bahan" selected><?= Yii::t('app', 'Semua Bahan')?></option>

                    </select>
                              
                      </div></div>
                      <div class="col-sm-1"><input class="btn btn-success" type="submit" value=<?= Yii::t('app', 'Cari')?>  align="right"></div>
                   </div>

                   </div>
                  <div class="row">&nbsp;</div>
                  <div class="row">
                  <div class="col-sm-12">&nbsp; &nbsp; <a href="<?php echo $homeUrl."pencarian-lanjut"; ?>"><?= Yii::t('app', 'Pencarian lanjut')?> </a> - <a href="<?php echo $homeUrl."riwayat-pencarian"; ?>"><?= Yii::t('app', 'Riwayat Pencarian')?> </a> - <a href="" data-toggle="modal" data-target="#modalBantuan"><?= Yii::t('app', 'Bantuan')?></a> </div>
                  </div>
                  

                 <!-- Modal -->
                  <div class="modal fade" id="modalBantuan" role="dialog">
                    <div class="modal-dialog">

                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title"><?= Yii::t('app', 'Bantuan')?></h4>
                        </div>
                        <div class="modal-body">

                            <ul>
                            <li> <?= Yii::t('app', 'Pencarian sederhana adalah pencarian koleksi dengan menggunakan hanya satu kriteria pencarian saja.')?> </li>
                            <li> <?= Yii::t('app', 'Ketikkan kata kunci pencarian, misalnya : " Sosial kemasyarakatan "')?> </li>
                            <li> <?= Yii::t('app', 'Pilih ruas yang dicari, misalnya : " Judul " .')?> </li>
                            <li> <?= Yii::t('app', 'Pilih jenis koleksi misalnya  " Monograf(buku) ", atau biarkan pada  pilihan " Semua Jenis Bahan "')?> </li>
                            <li> <?= Yii::t('app', 'Klik tombol "Cari" atau tekan tombol Enter pada keyboard')?> </li>
                        </ul>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        </div>
                      </div>

                    </div>
                  </div>
                    
                    </div>
                </section>

                    <!-- Full Width Column -->
          <div id="alert">
          </div>
          <div id="usulan">
          </div>
        <?php $this->beginBody() ?>
        <div class="content-wrapper" style="min-height: 507px;">
            <div class="container">
                <!-- Content Header (Page header) -->

                <!-- Main content -->
                <section class="content">
                 <!--   <?= Alert::widget() ?>  -->
                    <div class="box box-default">
<script type="text/javascript">

  function keranjang(id) {        
        //var id = $("#catalogID").val();
        $.ajax({
          type     :"POST",
          cache    : false,
          url  : "?action=keranjang&catID="+id,
          success  : function(response) {
            $("#keranjang").html(response);
      }
        });


      }

  function booking(CiD,id) {        
        //var id = $("#catalogID").val();
        $.ajax({
          type     :"POST",
          cache    : false,
          url  : "?action=boooking&colID="+id+"&id="+CiD,
          success  : function(response) {
            //location.reload();
            $("#alert").html(response);

            //$("#detail"+CiD).html(response);
            //collection(CiD);
            //search(CiD);
            //$("#collapsecollection"+CiD).collapse('show');
          }
        });


      }
  function logDownload(id) {
    $.ajax({
        type: "POST",
        cache: false,
        url: "?action=logDownload&ID=" + id,
    });


  }

    </script>
    <div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="gridSystemModalLabel">Cite This</h4>
        </div>
        <div class="modal-body">
         <b> APA Citation </b>  <br>
         <?= $cite['APA']?> <br>
       <!--  <b> Chicago Style Citation </b>  <br>
         <?/*= $cite['APA']*/?> <br>
         <b> MLA Citation </b>  <br>
         <?/*= $cite['APA']*/?> <br> -->
      <div id="keranjang">

      </div>

       </div>
       <div class="modal-footer">
        <p align="center" style="color:grey">Peringatan: citasi ini tidak selalu 100% akurat!</p>

      </div>
    </div>
  </div>
</div>
<section class="content">
  <div class="box box-default">
    <div class="box-body" style="padding:20px 0">
      <div class="breadcrumb">
        <ol class="breadcrumb">
          <?php if(Yii::$app->config->get('OpacIndexer') == 1) {?>
            <li><a href="<?=$homeUrl; ?>search">Home</a></li>
          <?php }else{ ?>
            <li><a href="<?=$homeUrl; ?>">Home</a></li>
          <?php } ?>
          
          <li><a >Detail Result</a></li>
         
          <?php
         /* $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collections'), 'url' => ['index']];
          $this->params['breadcrumbs'][] = $this->$detailOpac[0]['Title'];
      */    ?>

        </ol>
      </div>

      <div class="row">
        <div class="col-md-9">
          <div class="row">
            <div class="col-md-2"><br><img src="<?= $urlcover ?>" style="width:97px ; height:144px"></div>
            <div class="col-md-10"><center>
              <a href="" data-toggle="modal" data-target=".bs-example-modal-md"  ><i class="glyphicon glyphicon-cog"></i> <?= yii::t('app','Cite This')?></a>&nbsp; &nbsp; &nbsp; &nbsp; 
              <a href="javascript:void(0)" onclick="keranjang(<?= $ids; ?>)" ><i class="glyphicon glyphicon-shopping-cart"></i> <?= yii::t('app','Tampung')?></a>&nbsp; &nbsp; &nbsp; &nbsp; 
              <a href="" ><i class="glyphicon glyphicon-new-window"></i> <?= yii::t('app','Export Record')?></a>
            </center>
            <table class="table table-striped">
            <?php 

              if($detailOpac[0]['JUDUL']!='' || $detailOpac[0]['JUDUL']!= NULL)
              echo"
              <tr>
               <td>".yii::t('app','Judul')."</td>
                <td style=\"color:red\">".$detailOpac[0]['JUDUL']."</td>              
              </tr>
              ";
              if($detailOpac[0]['JUDUL_SERAGAM']!='' || $detailOpac[0]['JUDUL_SERAGAM']!= NULL)
              echo"
              <tr>
                <td>".yii::t('app','Judul Seragam')."</td>
                <td>".$detailOpac[0]['JUDUL_SERAGAM']."</td>
              </tr>
                ";

              if($detailOpac[0]['PENGARANG']!='' || $detailOpac[0]['PENGARANG'] != NULL){
                      echo"
                        <tr>
                        <td>".yii::t('app','Pengarang')."</td>
                        <td>
                      ";
                      for($x=0;$x<sizeof($pengarang);$x++){
                      
                       echo "<a href=\"pencarian-sederhana?action=pencarianSederhana&ruas=Pengarang&bahan=Semua Jenis Bahan&katakunci=".$pengarang[$x]."\"> ".$pengarang[$x]." </a> </br>";
                      

                      }
                      echo"
                      </td>
                      </tr>
                      ";
                }
              if($detailOpac[0]['EDISI'] != '' || $detailOpac[0]['EDISI'] != NULL)  
                  {
                     echo"
                        <tr>
                        <td>".yii::t('app','EDISI')."</td>
                        <td>
                      ";
                      for($x=0;$x<sizeof($EDISI);$x++){
                      
                       echo $EDISI[$x]." </br>";
                      
                      }
                      echo"
                      </td>
                      </tr>
                      ";


                    }
              if($detailOpac[0]['PERNYATAAN_SERI'] != '' || $detailOpac[0]['PERNYATAAN_SERI'] != NULL)  
                  {
                     echo"
                        <tr>
                        <td>".yii::t('app','Pernyataan Seri')."</td>
                        <td>
                      ";
                      for($x=0;$x<sizeof($PERNYATAAN_SERI);$x++){
                      
                       echo $PERNYATAAN_SERI[$x]." </br>";
                      
                      }
                      echo"
                      </td>
                      </tr>
                      ";


                    }
              if($detailOpac[0]['PENERBITAN'] != '' || $detailOpac[0]['PENERBITAN'] != NULL)  
                    echo"
                          <tr>
                          <td>".yii::t('app','Penerbitan')."</td>
                          <td>
                    ";
                    for($x=0;$x<sizeof($penerbitan);$x++){

                        echo $penerbitan[$x]." </br> ";
                    }
                     echo "
                        </td>
                      </tr>";
              if($detailOpac[0]['DESKRIPSI_FISIK'] != '' || $detailOpac[0]['DESKRIPSI_FISIK'] != NULL)  
                  {
                     echo"
                        <tr>
                        <td>".yii::t('app','Deskripsi Fisik')."</td>
                        <td>
                      ";
                      for($x=0;$x<sizeof($DESKRIPSI_FISIK);$x++){
                      
                       echo $DESKRIPSI_FISIK[$x]." </br>";
                      
                      }
                      echo"
                      </td>
                      </tr>
                      ";


                    }
              if($detailOpac[0]['KONTEN'] != '' || $detailOpac[0]['KONTEN'] != NULL)  
                  {
                     echo"
                        <tr>
                        <td>".yii::t('app','Konten')."</td>
                        <td>
                      ";
                      for($x=0;$x<sizeof($KONTEN);$x++){
                      
                       echo $KONTEN[$x]." </br>";
                      
                      }
                      echo"
                      </td>
                      </tr>
                      ";


                    }
              if($detailOpac[0]['MEDIA'] != '' || $detailOpac[0]['MEDIA'] != NULL)  
                  {
                     echo"
                        <tr>
                        <td>".yii::t('app','Media')."</td>
                        <td>
                      ";
                      for($x=0;$x<sizeof($MEDIA);$x++){
                      
                       echo $MEDIA[$x]." </br>";
                      
                      }
                      echo"
                      </td>
                      </tr>
                      ";


                    }
              if($detailOpac[0]['PENYIMPANAN_MEDIA'] != '' || $detailOpac[0]['PENYIMPANAN_MEDIA'] != NULL)  
                  {
                     echo"
                        <tr>
                        <td>".yii::t('app','Penyimpan Media')."</td>
                        <td>
                      ";
                      for($x=0;$x<sizeof($PENYIMPANAN_MEDIA);$x++){
                      
                       echo $PENYIMPANAN_MEDIA[$x]." </br>";
                      
                      }
                      echo"
                      </td>
                      </tr>
                      ";


                    }              
              if($detailOpac[0]['INFORMASI_TEKNIS'] != '' || $detailOpac[0]['INFORMASI_TEKNIS'] != NULL)  
                  {
                     echo"
                        <tr>
                        <td>".yii::t('app','Informasi Teknis')."</td>
                        <td>
                      ";
                      for($x=0;$x<sizeof($INFORMASI_TEKNIS);$x++){
                      
                       echo $INFORMASI_TEKNIS[$x]." </br>";
                      
                      }
                      echo"
                      </td>
                      </tr>
                      ";


                    } 
              if($detailOpac[0]['ISBN'] != '' || $detailOpac[0]['ISBN'] != NULL)  
                  {
                     echo"
                        <tr>
                        <td>".yii::t('app','ISBN')."</td>
                        <td>
                      ";
                      for($x=0;$x<sizeof($ISBN);$x++){
                      
                       echo $ISBN[$x]." </br>";
                      
                      }
                      echo"
                      </td>
                      </tr>
                      ";


                    } 
              if($detailOpac[0]['ISSN'] != '' || $detailOpac[0]['ISSN'] != NULL)  
                  {
                     echo"
                        <tr>
                        <td>".yii::t('app','ISSN')."</td>
                        <td>
                      ";
                      for($x=0;$x<sizeof($ISSN);$x++){
                      
                       echo $ISSN[$x]." </br>";
                      
                      }
                      echo"
                      </td>
                      </tr>
                      ";


                    } 
              if($detailOpac[0]['ISMN'] != '' || $detailOpac[0]['ISMN'] != NULL)  
                  {
                     echo"
                        <tr>
                        <td>".yii::t('app','ISMN')."</td>
                        <td>
                      ";
                      for($x=0;$x<sizeof($ISMN);$x++){
                      
                       echo $ISMN[$x]." </br>";
                      
                      }
                      echo"
                      </td>
                      </tr>
                      ";


                    } 
              if($detailOpac[0]['SUBJEK']!='' || $detailOpac[0]['SUBJEK'] != NULL){

                      echo"
                        <tr>
                        <td>".yii::t('app','Subjek')."</td>
                        <td>
                      ";
                      for($x=0;$x<sizeof($subyek);$x++){
                      
                        echo"
                        <a href=\"pencarian-sederhana?action=pencarianSederhana&ruas=Subyek&bahan=Semua Jenis Bahan&katakunci=".$subyek[$x]."\"> ".$subyek[$x]." </a> </br>
                        ";
                      }
                        echo "
                        </td>
                      </tr>";
                }
              if($detailOpac[0]['ABSTRAK'] != '' || $detailOpac[0]['ABSTRAK'] != NULL)  
                  {
                     echo"
                        <tr>
                        <td>".yii::t('app','Abstrak')."</td>
                        <td>
                      ";
                      for($x=0;$x<sizeof($ABSTRAK);$x++){
                      
                       echo $ABSTRAK[$x]." </br>";
                      
                      }
                      echo"
                      </td>
                      </tr>
                      ";


                    } 
              if($detailOpac[0]['CATATAN'] != '' || $detailOpac[0]['CATATAN'] != NULL)  {
                    echo"
                          <tr>
                          <td>".yii::t('app','Catatan')."</td>
                          <td>
                    ";
                    for($x=0;$x<sizeof($catatan);$x++){

                        echo $catatan[$x]." </br> ";
                    }
                     echo "
                        </td>
                      </tr>";
              }
              if($detailOpac[0]['BAHASA'] != '' || $detailOpac[0]['BAHASA'] != NULL)  
              echo"
              <tr>
                <td>".yii::t('app','Bahasa')."</td>
                <td>".$detailOpac[0]['BAHASA']."</td>
              </tr>              
              ";
              if($detailOpac[0]['BENTUK_KARYA'] != '' || $detailOpac[0]['BENTUK_KARYA'] != NULL)  
              echo"
              <tr>
                <td>".yii::t('app','Bentuk Karya')."</td>
                <td>".$detailOpac[0]['BENTUK_KARYA']."</td>
              </tr>
              ";
              if($detailOpac[0]['TARGET_PEMBACA'] != '' || $detailOpac[0]['TARGET_PEMBACA'] != NULL)  
              echo"
              <tr>
                <td>".yii::t('app','Target Pembaca')."</td>
                <td>".$detailOpac[0]['TARGET_PEMBACA']."</td>
              </tr>";
              if($detailOpac[0]['LOKASI_AKSES_ONLINE'] != '' || $detailOpac[0]['LOKASI_AKSES_ONLINE'] != NULL)  
                  {
                     echo"
                        <tr>
                        <td>".yii::t('app','Lokasi Akses Online')."</td>
                        <td>
                      ";
                      for($x=0;$x<sizeof($LOKASI_AKSES_ONLINE);$x++){
                      
                       echo $LOKASI_AKSES_ONLINE[$x]." </br>";
                      
                      }
                      echo"
                      </td>
                      </tr>
                      ";


                    }  ?>
            </table>
            
            <br>
          </div>
        </div>
        <div class="row">&nbsp;</div>
        <div class="row">                       
          <div class="col-md-12"><span class="nav-tabs-content">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_11" data-toggle="tab"><?= yii::t('app','Eksemplar')?></a></li>
               <li><a href="#tab_12" data-toggle="tab" title="Tampilkan Konten Digital"><?= yii::t('app','Konten Digital')?></a></li>
              <li><a href="#tab_22" data-toggle="tab" title="Tampilkan Metadata MARC"><?= yii::t('app','MARC')?></a></li>
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                  <?= yii::t('app','Unduh Katalog')?> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="detail-opac/download?id=<?=$ids?>&amp;type=MARC21">Format MARC Unicode/UTF-8</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="detail-opac/download?id=<?=$ids?>&amp;type=MARCXML">Format MARC XML</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="detail-opac/download?id=<?=$ids?>&amp;type=MODS">Format MODS</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="detail-opac/download?id=<?=$ids?>&amp;type=DC_RDF">Format Dublin Core (RDF)</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="detail-opac/download?id=<?=$ids?>&amp;type=DC_OAI">Format Dublin Core (OAI)</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="detail-opac/download?id=<?=$ids?>&amp;type=DC_SRW">Format Dublin Core (SRW)</a></li>
                </ul>
              </li>



            </ul>
          </span>
          <div class="tab-content">
            <div class="tab-pane-content active" id="tab_11">
            <?php if ($detailOpac[0]['Worksheet_id']==4) {
             ?>
              <div class="quarantined-collections-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin(['id' => 'myGridview']);
    echo GridView::widget([
        'id' => 'myGrid',
        'pjax' => true,
        'dataProvider' => $dataProviderSerial,
        'toolbar' => [
            ['content' =>
                \common\components\PageSize::widget(
                    [
                        'template' => '{label} <div class="col-sm-6" style="width:175px">{list}</div>',
                        'label' => Yii::t('app', 'Showing :'),
                        'labelOptions' => [
                            'class' => 'col-sm-4 control-label',
                            'style' => [
                                'width' => '75px',
                                'margin' => '0px',
                                'padding' => '0px',
                            ]

                        ],
                        'sizes' => Yii::$app->params['pageSize'],
                        'options' => [
                            'id' => 'aa',
                            'class' => 'form-control'
                        ]
                    ]
                )

            ],
            //'{pager}',
            //'{toggleData}',
            //'{export}',
        ],
        //'filterSelector' => 'select[name="per-page"]',
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'kartik\grid\ExpandRowColumn',
                'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                'detail' => function ($model, $key, $index) {
                    $searchModel = new CollectionSearchKardeks;
                    $params['CatalogId'] = $model->Catalog_id;
                    $params['EdisiSerial'] = $model->EDISISERIAL;
                    //echo"<pre>"; print_r($params); echo"</pre>"; die;

                    $dataProvider = $searchModel->search4($params);

                    return Yii::$app->controller->renderPartial('_subEksemplar', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                    ]);

                }
            ],
            ['class' => 'yii\grid\SerialColumn'],
            //'Edisi_id', 
            'EDISISERIAL',
            'TANGGAL_TERBIT_EDISI_SERIAL',
            'Eksemplar',

        ],
        //'summary'=>'',
        'responsive' => true,
        'containerOptions' => ['style' => 'font-size:13px'],
        'hover' => true,
        'condensed' => true,
        'options' => ['font-size' => '12px'],
        /*        'panel' => [
                    'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
                    'type'=>'info',
                    'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),

                   'showFooter'=>true,
                   'pager' => true,
                ],*/
    ]);
    Pjax::end(); ?>

</div>

             <?php 
            } else {
             ?>
             <div id="detail<?= $ids;?>">
              <div class="table-responsive">
              <table  id="detail" class="table table-striped table-bordered" cellspacing="0">
              <thead>
                  <tr>                        
                        <th ><?= yii::t('app','No Barcode')?></th>
                        <th ><?= yii::t('app','No. Panggil')?></th>
                        <th ><?= yii::t('app','Akses')?></th>
                        <th ><?= yii::t('app','Lokasi')?></th>
                        <th ><?= yii::t('app','Ketersediaan')?></th>
                  
                  </tr>
                  </tr>
              </thead>
             
              <tbody>
                  <?php 
                  for($i=0;$i<sizeof($collectionDetail);$i++){
                  $dateNow = new \DateTime("now");
                  if($collectionDetail[$i]['BookingMemberID'] == $noAnggota && $collectionDetail[$i]['BookingExpiredDate'] > $dateNow->format("Y-m-d H:i:sO") ){
                    $collectionDetail[$i]['ketersediaan'] = "Sudah Dipesan";

                    } else
                    if($collectionDetail[$i]['BookingExpiredDate'] > $dateNow->format("Y-m-d H:i:s") ){
                    $collectionDetail[$i]['ketersediaan'] = "Sudah Dipesan  Sampai ".$collectionDetail[$i]['BookingExpiredDate'];

                    }
                    


                    echo"    <tr>
                      <td>"
                          .$collectionDetail[$i]['NomorBarcode'].
                      "</td>
                      <td>"
                          .$collectionDetail[$i]['CallNumber'].
                      "</td>
                      <td> "
                          .$collectionDetail[$i]['akses']."
                          
                      </td>
                      <td>"
                          .$collectionDetail[$i]['namaperpus']." - ".$collectionDetail[$i]['lokasi'].
                      "</td>
                      <td>"
                          .OpacHelpers::maskedStatus($collectionDetail[$i]['ketersediaan']);
           
                        if($isBooking=='1' && $collectionDetail[$i]['ketersediaan'] == "Tersedia" && ($collectionDetail[$i]['akses'] == "Dapat dipinjam" || $collectionDetail[$i]['akses'] == "Tersedia" )){
                        if (!isset($noAnggota)) {
                            $booking = "
                                  <br>
                                   <a href=\"javascript:void(0)\" class=\"btn btn-success btn-xs navbar-btn\" onclick='tampilLogin()'>pesan</a>
                                ";                    
                        } else
                            $booking = "
                                    <form>
                                    <input type=\"button\" onclick=\"booking(" . $collectionDetail[$i]['Catalog_id'] . "," . $collectionDetail[$i]['id'] . ")\" class=\"btn btn-success btn-xs navbar-btn\" value=\"pesan\">

                                    </form>         
                      ";
                       }else {
                                  $booking = "";
                              }
                      echo $booking."
      
                       </td>
                        ";
                  echo"
                      
                  </tr>";

                  }

                  ?> 



              </tbody>
              </table>

              </div>
              </div>
             <?php 
            }
             ?>
            

          </div>
           <div class="tab-pane-content" id="tab_22">
           <?php
            if($marcOpac!=""){
            echo"<table class=\"table table-bordered\">
            <tr>
            <td>
            Tag
            </td>
            <td>
            Ind1
            </td>
            <td>
            Ind2
            </td>
            <td>
            Isi
            </td>
            </tr>
            ";
              for($i=0; $i < sizeof($marcOpac); $i++){
            echo"<tr>
            <td>
            ".$marcOpac[$i]['Tag']."
            </td>
            
             <td>
            ".$marcOpac[$i]['Indicator1']."
            </td>

             <td>
            ".$marcOpac[$i]['Indicator2']."
            </td>

             <td>
            ".$marcOpac[$i]['Value']."
            </td>


            </tr>
            ";
              }
            }
            echo"</table>";
           ?>

           </div>
            <div class="tab-pane-content " id="tab_12">
                       <?php
            if($KontenDigital[0]['Catalog_id']!=""){
            echo"<table id=\"detail\" class=\"table table-bordered\">
            <tr>
            <td>
            No
            </td>
            <td>
            Nama File
            </td>
            <td>
            Nama File Format Flash
            </td>
            <td>
            Format File
            </td>
            <td>
            Action
            </td>
            </tr>
            ";
              for($i=0; $i < sizeof($KontenDigital); $i++){
            echo"<tr>
            <td>
            ".($i+1)."
            </td>
            ";
            ?>
               <?php 
               // echo '<pre>'; print_r($KontenDigital); echo '</pre>';
               // echo '<pre>'; print_r($i); echo '</pre>';
               if($KontenDigital[$i]['FormatFile']!='' && $KontenDigital[$i]['FormatFile']!= NULL)
            {  
                $fakePath = DirectoryHelpers::GetTemporaryFolder($KontenDigital[$i]['ID'],2);
                if(!isset($noAnggota) && $KontenDigital[$i]['IsPublish']==2){
                    $kata="<a href=\"javascript:void(0)\" onclick=\"tampilLogin()\" >Baca Online</a>";
                } else {
                $kata="<a href=\"../uploaded_files".$fakePath."\" onclick=\"logDownload(".$KontenDigital[$i]['ID'].")\" target=\"_blank\" >Baca Online</a>";
                }
            }
            else{
                
                $fakePath = DirectoryHelpers::GetTemporaryFolder($KontenDigital[$i]['ID'],1);
                if((!isset($noAnggota)) && $KontenDigital[$i]['IsPublish']==2){
                $kata="<a href=\"javascript:void(0)\" onclick=\"tampilLogin()\" >Download</a>";
                } else{
                $kata="<a  href=\"../uploaded_files".$fakePath."\" onclick=\"logDownload(".$KontenDigital[$i]['ID'].")\"target=\"_blank\" >Download</a>";           
                }
            }
           
           
            
            
             echo"
              <td>
             ".$KontenDigital[$i]['FileURL']."
             </td>
             <td>
             ".$KontenDigital[$i]['FileFlash']."
             </td>
             <td>
             ".$KontenDigital[$i]['FormatFile']."
             </td>
             <td>
            ".$kata."
            </td>



            </tr>
            ";
              }
            }
            echo"</table>";
           ?>


          </div>
          <div class="tab-pane-content" id="tab_33">Content Unduh katalog</div>           
        </div>
      </div>
    </div>
    <div class="row">&nbsp;</div>
  </div>
  <?php if (sizeof($similiarTitle) !=0) {
    # code...
   ?>
  <div class="col-md-3">

        <span style="margin-bottom:13px"><strong><?= yii::t('app','Karya Terkait')?> :</strong></span>
        <div class="list-group facet" id="side-panel-authorStr">

           <div id="side-collapse-authorStr" class="collapse in">

          <?php
          $divHiddenBuka='<div class="facedHidden" >';
          $divHiddenTutup=(sizeof($similiarTitle)>5 ? '</div>' : '');

              for($i=0;$i<sizeof($similiarTitle);$i++){
              if($i==5){echo$divHiddenBuka;}
              echo"
          
              <a style=\"padding: 8px 40px 8px 8px;\" class=\"list-group-item \" href=\"?id=".$similiarTitle[$i]['ID']."\" > ".$similiarTitle[$i]['Title']."</a>
          
              ";
              }

              echo$divHiddenTutup;
              if(sizeof($similiarTitle)>5){
              echo"<a  href=\"#\" style=\"padding: 8px 40px 8px 8px;\" class=\"list-group-item faced\"  >Show More</a>";
              }
              ?>

        </div>

          </div>


  </div>
  <?php }?>
</div>

</div>
</div>
<div class="row">&nbsp;</div>                    
          </section><!-- /.content -->



          <?php 
          $this->registerJS('

            $(\'.facedHidden\').hide();

            // Make sure all the elements with a class of "clickme" are visible and bound
            // with a click event to toggle the "box" state
            $(\'.faced\').each(function() {
                $(this).show(0).on(\'click\', function(e) {
                    // This is only needed if your using an anchor to target the "box" elements
                    e.preventDefault();
                    
                    // Find the next "box" element in the DOM
                    $(this).prev(\'.facedHidden\').slideToggle(\'fast\');
                    if ( $(this).text() == "Show More") {
                $(this).text("Show Less")

                } else
                {
                $(this).text("Show More");
                }   


                });
            });
            ');

          ?>