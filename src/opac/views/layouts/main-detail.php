<?php

/* @var $this \yii\web\View */
/* @var $content string */


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



?>


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

    var d = new Date(Date(<?php echo $today['year'].",".$today['mon'].",".$today['mday'].",".$today['hours'].",".$today['minutes'].",".$today['seconds']; ?>));
    var weekday=new Array(7);
    var weekday=["Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu"];
    var weekday_en=new Array(7);
    var weekday_en=["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
    var monthname=new Array(12);
    var monthname=["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
    var monthname_en=new Array(12);
    var monthname_en=["","January","February","March","April","May","June","July","August","September","October","November","December"];
    var dayname=weekday[<?= date('w')?>];
    var day=<?= date('d')?>;
    var month=monthname[<?= date('m')?>];
    var year=<?= date('Y')?>;
    if ('<?= $lang ?>'=='en') {
            var dayname=weekday_en[<?= date('w')?>];
            var month=monthname_en[<?= date('m')?>];
        }
    setInterval(function() {
        d.setSeconds(d.getSeconds() + 1);
        $('#clocktime').text((dayname+", "+day+" "+month+" "+year+", "+d.getHours() +':' + d.getMinutes() + ':' + d.getSeconds() ));
    }, 1000);

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
                <h4 class="modal-title"><?= Yii::t('app', 'Usulan Koleksi')?></h4>
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

 