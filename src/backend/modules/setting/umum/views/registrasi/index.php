<?php


use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 */

$this->title = Yii::t('app', 'Pengaturan Registrasi Inlislite');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Umum'), 'url' => Url::to(['/setting/umum'])];
$this->params['breadcrumbs'][] = $this->title;
$homeUrl=Yii::$app->homeUrl;

$jenisPerpustakaan = Yii::$app->db->createCommand('SELECT ID, Name From jenis_perpustakaan WHERE ID = '.Yii::$app->config->get('JenisPerpustakaan').'')->queryOne();

?>



<div class="settingparameters-create">
    <div class="page-header">
        
    </div>
    <div class="settingparameters-form">
      <!-- <table id="GeoResults"></table> -->
        
        <div class="form-group">
            <div class="row">
              <?php if($data['Value'] == ''){ ?>
                <div class="col-sm-3" style="text-align: right">
                    <label><?= yii::t('app','Pastikan komputer anda terkoneksi dengan internet untuk registrasi aplikasi Inlislite ke Perpustakaan Nasional RI')?></label>
                </div>
                <div class="col-sm-9">
                    <div class="col-md-12">
                        <button class="btn btn-info" onclick="registrasi();"><?= yii::t('app','Registrasi Sekarang')?></button>
                    </div>
                </div>
              <?php } else { ?>
                <!-- <div class="col-sm-3" style="text-align: right">
                    <label><?//= yii::t('app','Jika anda ingin merubah data registrasi aplikasi inlislite ke Perpusnas, silahkan klik tombol Registrasi Ulang, dan pastikan komputer anda terkoneksi dengan internet')?></label>
                </div>
                <div class="col-sm-9">
                    <div class="col-md-12">
                        <button class="btn btn-info" onclick="registrasi();"><?//= yii::t('app','Registrasi Ulang')?></button>
                    </div>
                </div> -->
                <div class="col-md-12">
                  <h5>Aplikasi Inlislite ini telah teregistrasi di Perpustakaan Nasional Republik Indonesia</h5>
                  <h6>Jika ingin merubah data, silahkan hubungin Perpustakaan Nasional</h6>
                  <hr>
                  <div class="col-sm-3" style="text-align: right">
                    <label><?= yii::t('app','Nama Perpustakaan')?></label>
                  </div>
                  <div class="col-sm-9">
                    <div class="col-md-12">
                      <p id="perpusName"></p>
                    </div>
                  </div>

                  <div class="col-sm-3" style="text-align: right">
                    <label><?= yii::t('app','Jenis Perpustakaan')?></label>
                  </div>
                  <div class="col-sm-9">
                    <div class="col-md-12">
                      <p id="perpusJenis"></p>
                    </div>
                  </div>

                  <div class="col-sm-3" style="text-align: right">
                    <label><?= yii::t('app','Negara')?></label>
                  </div>
                  <div class="col-sm-9">
                    <div class="col-md-12">
                      <p id="perpusNegara"></p>
                    </div>
                  </div>

                  <div class="col-sm-3" style="text-align: right">
                    <label><?= yii::t('app','Provinsi Perpustakaan')?></label>
                  </div>
                  <div class="col-sm-9">
                    <div class="col-md-12">
                      <p id="perpusProv"></p>
                    </div>
                  </div>

                  <div class="col-sm-3" style="text-align: right">
                    <label><?= yii::t('app','Kode Registrasi')?></label>
                  </div>
                  <div class="col-sm-9">
                    <div class="col-md-12">
                      <p id="perpusKode"></p>
                    </div>
                  </div>
                </div>
              <?php } ?>
                
            </div>
            
            
        </div>

        
        

    </div>

</div>

<div class="modal fade" id="modal-registrasi">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?= yii::t('app','Form Registrasi Aplikasi')?></h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="formRegis">
          <div class="box-body">
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label"><?= yii::t('app','Nama Perpustakaan')?></label>

              <div class="col-sm-10">
                <input type="text" class="form-control" id="namaPerpus" name="namaPerpus" placeholder="Nama Perpustakaan" readonly="readonly">
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label"><?= yii::t('app','Jenis Perpustakaan')?></label>

              <div class="col-sm-10">
                <input type="text" class="form-control" id="jenisPerpus" name="jenisPerpus" placeholder="Jenis Perpustakaan" readonly="readonly">
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label"><?= yii::t('app','Negara')?></label>

              <div class="col-sm-10">
                <input type="text" class="form-control" id="negara" name="negara" placeholder="Kode Registrasi" readonly="readonly">
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label"><?= yii::t('app','Propinsi')?></label>

              <div class="col-sm-10">
                <select name="provinsi" class="form-control select2" id="setProp">
                <?php 
                  $propinsi = Yii::$app->db->createCommand('SELECT ID, NamaPropinsi FROM propinsi')->queryAll();
                  echo'<option value="">-- Pilih Provinsi --</option>';
                  foreach ($propinsi as $prov) {
                    echo'<option value="'.$prov['ID'].'">'.$prov['NamaPropinsi'].'</option>';
                  }
                ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label"><?= yii::t('app','Kode Registrasi')?></label>

              <div class="col-sm-10">
                <input type="text" class="form-control" id="kodeRegis" name="kodeRegis" placeholder="Kode Registrasi" readonly="readonly">

                
              </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?= yii::t('app','Batal')?></button>
            <button type="button" class="btn btn-info pull-right" onclick="proses();"><?= yii::t('app','Proses')?></button>
          </div>
          <!-- /.box-footer -->
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script type="text/javascript">

    $(document).ready(function(){
        getIP();
		    getData();
    });

    function getIP(){
      $.getJSON("http://ip-api.com/json", function(data) {
          // console.log(data.as)
          document.getElementById('negara').value = data.country;
          // document.getElementById('provinsi').value = data. city;
          var table_body = "";
          $.each(data, function(k, v) {
              
              table_body += "<tr><td>" + k + "</td><td><b>" + v + "</b></td></tr>";
          });
          $("#GeoResults").html(table_body);
      });
      // $.ajax({
      //   url : 'getip',
      //   type : 'json',
      //   success : function(data){
      //     console.log(data)
      //   }
      // })
    }

    function registrasi(){
        var d = new Date();
        var n = d.getFullYear();
        var m = d.getMonth() + 1;
        var date = d.getDate();
        var time = d.getHours()+d.getMinutes()+d.getMilliseconds();
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        for (var i = 0; i < 15; i++)
           text += possible.charAt(Math.floor(Math.random() * possible.length));

        var nmPerpus = '<?php echo Yii::$app->config->get('NamaPerpustakaan'); ?>';
        var jenisPerpus = '<?php echo $jenisPerpustakaan["Name"] ?>';
        // alert(jenisPerpus)
        var KodeRegistrasi = '<?= $data['Value'] ?>';
        var kodeRegis = text+'_'+n+m+date+time;
        if(KodeRegistrasi !== ''){
          $.ajax({
            // url : 'http://suryaciptaagung.co.id/OSSS/Perpus%20Daerah/api/web/v1/registrasi/view',
            url : '<?= Yii::$app->urlManager->createUrl(["/setting/umum/registrasi/detail"]) ?>',
            type : 'GET',
            dataType : 'JSON',
            data : {
              noReg : KodeRegistrasi
            },
            success : function(data){
              // console.log(data.NamaPropinsi)
              $('#namaPerpus').val(nmPerpus);
              $('#jenisPerpus').val(jenisPerpus);
              $("#setProp").val(data.provID);

              $('#modal-registrasi').modal('show');
            }
          });
          $('#kodeRegis').val(KodeRegistrasi);
        }else{
          $('#kodeRegis').val(kodeRegis);
        }
        
        $('#namaPerpus').val(nmPerpus);
        $('#jenisPerpus').val(jenisPerpus);
        $('#modal-registrasi').modal('show');
    }

    function proses(){
      if($('#setProp').val() == ''){
        swal({html:true, title: "error", text: "Silahkan Pilih Provinsi", type: "error", timer: 3000}); 
      }else{
        var success = '<?php echo Yii::t("app","Registrasi Aplikasi Inlislite");?>';
        var br = '<?php echo Yii::t("app","Berhasil"); ?>';
        var error = '<?php echo Yii::t("app","Oops..."); ?>';
        var br2 = '<?php echo Yii::t("app","Gagal Registrasi!"); ?>';
          $.ajax({
              url : '<?= Yii::$app->urlManager->createUrl(["/setting/umum/registrasi/proses"]) ?>',
              data : $('#formRegis').serialize(),
              type : 'POST',
              dataType : 'JSON',
              success : function(data){
                  // console.log(data.status)
                  if(data.status == 'TRUE'){

                    swal({html:true, title: "success!", text: success+" <br> "+br, type: "success", timer: 6000}, function(){ location.reload(); });    

                  }else{
                    swal({html:true, title: "error", text: error+" "+br2, type: "error", timer: 3000}); 
                  }
                  
              }
          });
      }
      
    }
	
	  function getData(){
      var KodeRegistrasi = '<?= $data['Value'] ?>';
      var html = '';
      $.ajax({
        url : '<?= Yii::$app->urlManager->createUrl(["/setting/umum/registrasi/detail"]) ?>',
        // url : 'http://suryaciptaagung.co.id/OSSS/Perpus%20Daerah/api/web/v1/registrasi/view?noReg='+KodeRegistrasi,
        type : 'GET',
        data : {noReg : KodeRegistrasi},
        dataType : 'JSON',
        success : function(data){
          var prov = '';
          if(data == "Tidak ada Nomor Registrasi"){
            swal({
              title: "Maaf!",
              text: "Kode Registrasi Tidak Ditemukan!",
              icon: "success",
              button: "Daftar Ulang",
            }, function(){
              registrasi();
            });
          }else{
            $('#perpusName').html(data.NamaPerpustakaan);
            $('#perpusJenis').html(data.JenisPerpustakaan);
            $('#perpusNegara').html(data.Negara);
            $('#perpusProv').html(data.NamaPropinsi);
            $('#perpusKode').html(data.ActivationCode);
          }
          
          // console.log(data)
        }  
      })
      
    }
</script>