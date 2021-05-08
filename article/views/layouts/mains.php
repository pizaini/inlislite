<?php

/* @var $this \yii\web\View */
/* @var $content string */


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use article\assets_b\AppAsset;
use common\widgets\Alert;
use kartik\datecontrol\DateControl;
use kartik\datetime\DateTimePicker;
use kartik\widgets\DatePicker;
use common\models\Collections;
use common\models\Refferenceitems;
use common\models\Worksheets;
use common\components\OpacHelpers;
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

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Yii::t('app', 'Pencarian Artikel')?> - Perpusnas RI</title>
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
                           <!--  <div class="image"> <a href='<?=$homeUrl?>'> <img src="../<?=Yii::getAlias('@upload')."/aplikasi/logo_perpusnas_2015.png"?>" class="img-logo" height="65" width="70"> </a></div> -->
                           <!--  <div class="image"> <a href='<?=$homeUrl?>'> <img src="<?= Yii::$app->urlManager->createUrl('../uploaded_files/aplikasi/logo_perpusnas_2015.png') ?>" class="img-logo" height="65" width="70"> </a></div> -->
                            <div class="image"><a href="<?php echo $homeUrl; ?>"><img src="<?= Yii::$app->urlManager->createUrl('../uploaded_files/aplikasi/logo_perpusnas_2015.png') ?>" class="img-logo" height="65" width="70"></a></div> 
                            <div class="text">
                                <h3 style="margin-top: 20px;"><?= Yii::t('app', 'Pencarian Artikel')?></h3>
                                <div class="clear"></div>
                                <div class="time"><?= Yii::$app->config->get('NamaPerpustakaan') ?></div><br/>
                                <div class="clear"></div>
                                <div class="timeddr" style="margin-bottom: 10px"><?= Yii::$app->request->cookies->getValue('location_detail')['Address'] ?></div>
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
                        <?= $content ?>
                    </div>
                </section><!-- /.content -->
            </div><!-- /.container -->
        </div><!-- /.content-wrapper -->


    <footer class="footer main-footer">
      <div class="container">
        <div class="pull-right hidden-sm" style="font-family: "Corbel", Arial, Helvetica, sans-serif;">
          <?=\Yii::$app->params['footerInfoRight'];?>
        </div>
        <?= yii::t('app',\Yii::$app->params['footerInfoLeft']); ?> &copy; <?= yii::t('app',\Yii::$app->params['year']); ?> <a href="http://inlislite.perpusnas.go.id" target="_blank"><?= yii::t('app','Perpustakaan Nasional Republik Indonesia') ?></a>
      </div> <!-- /.container -->
    </footer>
        <?php $this->endBody() ?>
    </div><!-- ./wrapper -->
</body>
</html>
<?php $this->endPage() ?>
<?php $today = getdate(); ?>
<?php $lang = Yii::$app->config->get('language'); ?>
<script>
    //alert(Date());
  function startTime()
    {   var today=new Date();
        var weekday=new Array(7);
        var weekday=["Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu"];
        var weekday_en=new Array(7);
        var weekday_en=["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
        var monthname=new Array(12);
        var monthname=["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        var monthname_en=new Array(12);
        var monthname_en=["January","February","March","April","May","June","July","August","September","October","November","December"];
        var dayname=weekday[today.getDay()];
        var day=today.getDate();
        var month=monthname[today.getMonth()];
        var year=today.getFullYear();
        var h=today.getHours();
        var m=today.getMinutes();
        var s=today.getSeconds();
        h=checkTime(h);
        m=checkTime(m);
        s=checkTime(s);
        if ('<?= $lang ?>'=='en') {
            var dayname=weekday_en[today.getDay()];
            var month=monthname_en[today.getMonth()];
        }else{
            var dayname=weekday[today.getDay()];
            var month=monthname[today.getMonth()];
        }
        document.getElementById('clocktime').innerHTML=dayname+", "+day+" "+month+" "+year+", "+h+":"+m+":"+s;
        t=setTimeout(function(){startTime()},500);
    }

  function getData(dropdown) {
    var value = dropdown.options[dropdown.selectedIndex].value;
    if(document.getElementById("dariTGL").style.display == "inline"){

    }
    document.getElementById("dariTGL").style.display = "none"
    document.getElementById("sampaiTGL").style.display = "none"
    if (value == '4'){   
      document.getElementById("dariTGL").style.display = "inline"
      document.getElementById("sampaiTGL").style.display = "inline"
    }
  }
    function tampilBooking() {
        $.ajax({
            type: "POST",
            cache: false,
            url: "booking?action=showBookingDetail",
            success: function (response) {
                $("#modalBooking").modal('show');
                $("#BookingShow").html(response);
            }
        });
    }
    function tampilUsulan() {
        $.ajax({
            type: "POST",
            cache: false,
            url: "usulan-koleksi?action=showUsulan",
            success: function (response) {
                $("#modalUsulan").modal('show');
                $("#UsulanShow").html(response);
            }
        });
    }

      function tampilLogin() {
        $.ajax({
            type: "POST",
            cache: false,
            url: "site/login",
            success: function (response) {
                $("#modalLogin").modal('show');
                $("#LoginShow").html(response);
            }
        });

    }

    function cancelBooking(id) {
        $.ajax({
            type: "POST",
            cache: false,
            url: "booking?action=cancelBooking&colID="+id,
            success: function (response) {
                $("#modalBooking").modal('hide');
                $("#alert").html(response);
            }
        });
    }

    function loginAnggota() {
        $.ajax({
            type: "POST",
            cache: false,
            url: "site/loginanggota",
            data : $("#login-anggota").serialize(), 
            success: function (response) {
                console.log(response);
                if (response) {
                  $("#error-login-opac-123-321").html(response);
                }
            }

        });
    }


  var MaxInputs = 50;
  var Dynamic = $("#Dynamic");
  var i = $("#Dynamic div").size() + 1;

  $("#AddInpElem").click( function () {
    if (i <= MaxInputs) {
      $("<div> <div class=\"row\">  <div class=\"col-sm-1\"></div>  <div class=\"col-sm-1\"><div class=\"form-group\"><select name=\"danAtau[]\" class=\"form-control\"><option value=\"and\">dan</option>  <option value=\"or\">atau</option>   <option value=\"selain\">selain</option>      </select></div></div><div class=\"col-sm-3\"><div class=\"form-group\">  <input name=\"katakunci[]\"  type=\"text\" class=\"form-control login-field\"  /></div></div><div class=\"col-sm-2\"><div class=\"form-group\"> <select name=\"jenis[]\" class=\"form-control\"><option>di dalam</option><option >di awal</option><option >di akhir</option> </select></div></div><div class=\"col-sm-3\"><div class=\"form-group\"><select   name=\"tag[]\" class=\"form-control\"> <option value=\"Judul\">Judul</option> <option value=\"Pengarang\">Pengarang</option> <option value=\"Penerbitan\">Penerbitan</option> <option value=\"Edisi\">Edisi</option> <option value=\"Deskripsi Fisik\">Deskripsi Fisik</option> <option value=\"Jenis Konten\">Jenis Konten</option> <option value=\"Jenis Media\">Jenis Media</option> <option value=\"Media Carrier\">Media Carrier</option> <option value=\"Subyek\">Subyek</option> <option value=\"ISBN\">ISBN</option> <option value=\"ISSN\">ISSN</option> <option value=\"ISMN\">ISMN</option> <option value=\"Nomor Panggil\">ISBN</option> <option value=\"Sembarang Ruas\">Sembarang Ruas </option>       </select> </div></div><a href=\"javascript:void(0)\" class=\"RemInpElem\"><span class=\"glyphicon glyphicon-minus-sign\"></span></a> &nbsp; </div> </div>").appendTo(Dynamic);
      i++;
    }
    return false;
  });


$("body").on("click",".RemInpElem", function(){
  if (i > 2) {
    $(this).parent("div").remove();
    i--;
  }
  return false;
}) 


</script>

<div class="modal fade" id="modalBooking" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Booking Detail</h4>
            </div>
            <div class="modal-body">
                <p id="demo"></p>
                <div id="BookingShow">
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0)" id="PrintButton" class="btn btn-primary"> <span class="glyphicon glyphicon-print"></span>  Print  </a>&nbsp;&nbsp;
                <button type="button" class="btn btn-default" data-dismiss="modal">&nbsp;<?= Yii::t('app', 'Tutup')?>&nbsp;</button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUsulan" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Usulan Koleksi</h4>
            </div>
            <div class="modal-body">
                <p id="demo"></p>
                <div id="UsulanShow">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Tutup')?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalLogin" role="dialog">
    <div class="modal-dialog modal-sm" >       
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Login Anggota')?></h4>
            </div>
            <div class="modal-body ">
                <p id="demo"></p>
                <div id="LoginShow">




                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Tutup')?></button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="modalBantuan" role="dialog">
    <div class="modal-dialog">       
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
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Tutup')?></button>
            </div>
        </div>
    </div>
</div>

 