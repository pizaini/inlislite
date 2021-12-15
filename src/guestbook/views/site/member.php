<?php

// use Yii;
use yii\helpers\Html;
use common\models\TujuanKunjungan;
use common\models\Locations;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;



//use common\components\AjaxSubmitButton;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Buku Tamu Pengunjung');


$jmlPengunjung = str_pad($totalVisitors, 4, '0', STR_PAD_LEFT);

// get cookies
$cookies = Yii::$app->request->cookies;
$location_Bukutamu_id  = $cookies->getValue('location_Bukutamu_id');

// get location model
$location = Locations::find()
    ->where(['ID' => $location_Bukutamu_id ])
    ->one();
Yii::$app->view->params['subTitle'] = '<h3>'.Yii::t('app', 'Selamat Datang').'<br>'.Yii::t('app', 'Di ').$location->Name.'</h3><h5 style="display: block;">('.number_format($totalVisitors).' '.Yii::t('app', 'pengunjung hari ini').')</h5>';

?>


<style type="text/css" media="screen">
 .btn-default:active, .btn-default.active, .open > .dropdown-toggle.btn-default {
    color: #FBFBFB;
    background-color: #336699;
    border-color: #336699;
}

.btn-default:hover,
.btn-default:active,
.btn-default.hover {
    color: #FBFBFB;
  background-color: #79A1CA;
}
</style>


<div class="message" data-message-value="<?= Yii::$app->session->getFlash('message') ?>">
</div>

<div class="row">
    <div class="col-sm-12" style="padding:0 -15px">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab"><?= Yii::t('app', 'Anggota') ?></a></li>
                <li><a href="<?= Yii::$app->getUrlManager()->createUrl('site/nonmember') ?>"><?= Yii::t('app', 'Non Anggota') ?></a></li>
                <li><a href="<?= Yii::$app->getUrlManager()->createUrl('site/group')?>"><?= Yii::t('app', 'Rombongan') ?></a></li>
            </ul>

        </div>
    </div>
</div>


<div class="box-body" style="padding:50px 0">
    <div class="tab-content">
        <!-- Tab Buku-tamu anggota -->
        <div class="tab-pane active" id="tab_1">
        <?php $form = ActiveForm::begin(); ?>
	        <div id="input-nomember">

	        	<center  style="padding-bottom: 15px">
	        		<?= Yii::t('app','Silahkan pindai kartu anggota Anda'); ?>
	        	</center>

	        	<div class="row">
	        		<div class="col-sm-offset-4 col-sm-4">
	        			<div class="input-group">
	        				<?php //$form->field($model, 'NomorAnggota')->textInput(['id'=>'ContentPlaceHolder1_txtNoAnggota','class'=>'form-control','style'=>'width:100%'])->label(false); ?>
	        				<?= Html::input('text','Memberguesses[NoAnggota]',$NoAnggota,['id'=>'ContentPlaceHolder1_txtNoAnggota','class'=>'form-control','placeholder'=>Yii::t('app', 'No. anggota / pengunjung'),'autofocus'=>'autofocus'])  ?>
	        				<!-- <input name="no" value="<?php // $no ?>" type="text" name="txtNoindentitas" class="form-control"/> -->
	        				<div class="input-group-btn">
	        					<button type="button" id="ContentPlaceHolder1_btCheck" class="btn btn-default">
	        						<i class="fa fa-check"></i>
	        					</button>
	        				</div>
	        			</div>
	        		</div>
	        		<div class="col-sm-4"></div>
	        	</div>
	        	<div class="row">
	        		<div class="col-sm-offset-4 col-sm-4">
	        			<h4 id="message-nomember" class="text-danger text-center">
	        			</h4>
	        		</div>
	        	</div>
	        </div>

            


            <center id="detail-member" hidden="hidden">
                <img id="MemberPhoto" src="" class="img-circle" style="height: 146px; width: 146px;">
                <div class="row">
                    <div class="col-sm-12">
                        <h4><b class="" id="namaMember">namaMemberLoading...</b></h4>
                        <?= $form->field($model, 'Nama')->hiddenInput(['id'=>'ContentPlaceHolder1_txtNama','class'=>'form-control'])->label(false); ?>
                    </div>
                </div>

                <!-- Jika IsVisitsDestination / Menu Tujuan Kunjungin diaktifan di  table locations -->
                <?php if ($location->IsVisitsDestination == 1) { ?>
                <br><?= Yii::t('app','Apa yang Ingin Anda Lakukan Hari Ini?'); ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="" data-toggle="buttons" >
                            <?php $tujuan = TujuanKunjungan::find()->where(['Member' => 1])->all(); ?>
                            <?php foreach ($tujuan as $tujuan) {  ?>
                            <label class="btn btn-default btn_TujuanKunjungan">
                                <input type="radio" class="ContentPlaceHolder1_txtTujuanKunjungan_Id" id="TujuanKunjungan_Id[]" name="Memberguesses[TujuanKunjungan_Id]" value="<?= $tujuan->ID ?>" /> <?= $tujuan->TujuanKunjungan  ?>
                            </label> &nbsp;
                            <?php } ?>
                        </div>
                    </div>
                    <!-- <input type="hidden" id="TujuanValue" name="Memberguesses[TujuanKunjungan_Id]" />                   -->
                </div>
                <?php } ?>

                <!-- Jika IsInformationSought / pertanyaan informasi yang dicari diaktifkan -->
                <?php if ($location->IsInformationSought == 1) { ?>
                <br><?= Yii::t('app','Informasi Apa yang Anda Cari?'); ?>
                <div class="row">
                    <div role="form">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4"><div class="form-group"><textarea name="Memberguesses[Information]" id="ContentPlaceHolder1_txtInformation" class="form-control" rows="3"></textarea></div></div>
                        <div class="col-sm-4"></div>
                    </div>
                </div>
                <?php } ?>

                <div class="row">
                    <div role="form">
                        <div class="col-sm-4"></div>

                        <div class="col-sm-4">
                            <div class="form-group"><br>
                                <?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Simpan') : Yii::t('app', 'Create'), ['id'=>'btnSave','class' => $model->isNewRecord ? 'btn btn-default' : 'btn btn-primary']);?>
                            </div>
                        </div>
                        <div class="col-sm-4"></div>
                    </div>
                </div>
                <br>
                Terima kasih. Anda <b>pengunjung ke <?= number_format($jmlPengunjung+1) ?></b> hari Ini.
            </center>

            <!-- Hidden information for post data -->
            <input type="hidden" name="MemberID" id="MemberID" value="<?= $MemberID ?>" />
            <input type="hidden" name="PhotoUrl" id="PhotoUrl" value="<?= $PhotoUrl ?>" />
            <textarea hidden="hidden" name="Memberguesses[Alamat]" rows="2" cols="20" readonly="readonly" id="ContentPlaceHolder1_txtAlamatPengunjung" class="inp_elm_cekpoin" style="height:80px;width:300px;background: rgba(255,255,255,0.2);color:white;"><?= $model->Alamat ?></textarea>
            <?php ActiveForm::end();?>


        </div>
    </div><!-- /.tab-content -->
</div>

<audio id="welcomeAudio" width="320" height="176" hidden="hidden">
  <source src="<?= Yii::$app->urlManager->createUrl("../uploaded_files/settings/audio").'/'.$audio['Value'] ?>" type="audio/mpeg">
  Browser anda tidak support HTML5 audio.
</audio >

<?php
$urlnya = Yii::$app->urlManager->createUrl("members/get-member");
$urlpoto = Yii::$app->urlManager->createUrl("../uploaded_files/foto_anggota/");
$massage =Yii::t('app','Anggota tidak ditemukan');
$script = <<< JS

var IsVisitsDestination = '$location->IsVisitsDestination' ;
var IsInformationSought = '$location->IsInformationSought';

  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      validation();
      return false;
    }
  });


function checkImage (src, good, bad) {
    var img = new Image();
    img.onload = good; 
    img.onerror = bad;
    img. src = src;
}




if (IsVisitsDestination == 1) {
    $('.ContentPlaceHolder1_txtTujuanKunjungan_Id').attr('required','required');

    // ketika klik tujuan kunjungan langsung post / submit data
    $('.btn_TujuanKunjungan').click(function () {
        $('#w0').submit();
    });
    // Jika Tujuan Kunjungan diaktifkan maka tidak ada tombol submit,
    $('#btnSave').hide();
}


if (IsInformationSought == 1) {
    $('#ContentPlaceHolder1_txtInformation').attr('required','required');
}


// $('#ContentPlaceHolder1_txtNoAnggota').change(function() {
//    validation();

// });

$('#ContentPlaceHolder1_btCheck').click(function() {
   validation();
});


function validation(){
     var memberNo = $('#ContentPlaceHolder1_txtNoAnggota').val();
     var urlMemberCheck = '$urlnya';
     var mass = '$massage';
    //alert(memberNo);
    $.ajax({
        url:  urlMemberCheck + '?memberNo=' + memberNo,
        type: 'GET',
        success: function(data){
            var data = $.parseJSON(data);
            if (data.StatusAnggota == null){
                $('#message-nomember').html(mass);
                // clear-data;
                $('#namaMember').html('');
                $('#ContentPlaceHolder1_txtNama').val('');
                $('#ContentPlaceHolder1_txtStatus').val('');
                $('#ContentPlaceHolder1_txtProfesi').val('');
                $('#ContentPlaceHolder1_txtPendidikanTerakhir').val('');
                $('#ContentPlaceHolder1_txtJenisKelamin').val('');
                $('#ContentPlaceHolder1_txtAlamatPengunjung').val('');
                $('#ContentPlaceHolder1_txtMasaBerlaku').val('');
                $('#ContentPlaceHolder1_txtNoAnggota').val('');

                // swal('Maaf, anda belum terdaftar.');
            }else{
                $('#detail-member').show();
                $('#input-nomember').hide();
                $('#namaMember').html(data.Fullname);
                $('#ContentPlaceHolder1_txtNama').attr('value', data.Fullname);
                $('#ContentPlaceHolder1_txtStatus').attr('value', data.StatusAnggota);
                $('#ContentPlaceHolder1_txtProfesi').attr('value', data.JobName);
                $('#ContentPlaceHolder1_txtPendidikanTerakhir').attr('value', data.EducationLevel);
                $('#ContentPlaceHolder1_txtJenisKelamin').attr('value', data.Sex);
                $('#ContentPlaceHolder1_txtAlamatPengunjung').val(data.Address);
                $('#ContentPlaceHolder1_txtMasaBerlaku').attr('value', data.RegisterDate + ' - ' + data.EndDate);
                $('#MemberID').attr('value', data.ID);
                $('#PhotoUrl').attr('value', data.PhotoUrl);
                $('#message-nomember').html('');
                
                // for member photo
                checkImage( "$urlpoto" + "/" + $('#PhotoUrl').val(), 
                    function(){
                        $("#MemberPhoto").attr('src',  "$urlpoto" + "/" + $('#PhotoUrl').val());
                    },  
                    function(){ 
                        $("#MemberPhoto").attr('src',  "$urlpoto" + "/" + "nophoto" +'.jpg');
                        console.log('tidak ada foto');
                    } 
                );
                
                welcomeAudio.play();

                if (IsInformationSought == 0 && IsVisitsDestination == 0 ) 
                {
                    setTimeout(function(){ 
                        $('#w0').submit();
                        $('#btnSave').hide();
                    }, 3000);
                }

            }

        },
        error: function(data) {
           // alert('Error, Hubungi administrator.');
            $('#message-nomember').html('Mohon maaf data tidak ditemukan.<br> Silahkan ulangi lagi');
            $('#ContentPlaceHolder1_txtNoAnggota').val('');
        }
    });
};

$('.btn-group button').click(function () {
    $('#buttonvalue').val($(this).text());
});

if ($('.message').data("messageValue")) {
    // swal($('.message').data("messageValue"));
}




JS;

$this->registerJs($script);
?>
