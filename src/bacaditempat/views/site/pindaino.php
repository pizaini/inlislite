<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use kartik\widgets\ActiveForm;

use common\models\Locations;
use yii\helpers\Url;

use common\components\MemberHelpers;


/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Baca Ditempat');

// Yii::$app->view->params['subTitle'] = '<h3>'.Yii::t('app', 'Selamat Datang').'<br>'Yii::t('app', 'Di').'Di '.$locationName.'</h3><h5> ('.$readedCollection['eksemplar'].' eksemplar dari '.$readedCollection['judul'] .' judul dibaca hari ini)</h5>';
Yii::$app->view->params['subTitle'] = '<h3>'.Yii::t('app', 'Selamat Datang').'<br>'.Yii::t('app', 'Di ').$locationName.'</h3>
                                        <h5> ('.$readedCollection['eksemplar'].' '.Yii::t('app', 'eksemplar dari ').$readedCollection['judul'] .' '.Yii::t('app', 'judul dibaca hari ini ').')</h5>';
Yii::$app->params['pathFotoAnggota'] = '' ;

?>


<style type="text/css" media="screen">
    .book-items div:nth-child(n+6)
    {
        display: none;
    }
</style>


<div class="message" data-message-value="<?= Yii::$app->session->getFlash('message') ?>">
</div>



<div class="box-body" style="padding:50px 0">

    <div id="input-nomember">
        <?php // echo Html::beginForm(Yii::$app->request->baseUrl.'/site/pindai-no', 'post', ['class'=>'uk-width-medium-1-1 uk-form uk-form-horizontal']); ?>
        <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]); ?>
        <div class="col-sm-12" style="padding-bottom: 15px">
            <center>
            	<?= Yii::t('app','Silahkan pindai kartu anggota / pengunjung'); ?>
            </center>
        </div>
        <div class="row">
            <div class="col-sm-offset-4 col-sm-4">
                <div class="input-group">
                    <?php // $form->field($model, 'NomorAnggota')->textInput(['id'=>'ContentPlaceHolder1_txtNoAnggota','class'=>'form-control','style'=>'width:100%'])->label(false); ?>
                    <?= Html::input('text','NoAnggota',$NoAnggota,['id'=>'noAnggota','class'=>'form-control','placeholder'=>Yii::t('app', 'No. anggota / pengunjung'),'autofocus'=>'autofocus'])  ?>
                    <!-- <input name="no" value="<?php // $no ?>" type="text" name="txtNoindentitas" class="form-control"/> -->
                    <div class="input-group-btn">
                        <button type="button" id="ContentPlaceHolder1_btCheck" class="btn btn-default">
                            <i class="fa fa-check"></i>
                        </button>
                    </div>
                </div>
                <hr>
                <button type="button" id="btnNotMember" class="btn btn-default btn-block">
                    Saya Bukan Anggota
                </button>
            </div>
            <div class="col-sm-4"></div>
        </div>
        <div class="row">
            <div class="col-sm-12 ">
                <center><h4 id="message-nomember" class="text-danger"><?= Yii::$app->session->getFlash('message') ?></h4></center>
            </div>
        </div>
    </div>



<!--         Foto dan pindai koleksi/Collection
        Wait 10s for scan collection
        if 10s user iddle refresh page or clear all data input -->
    <div class="row" id='pindaiKoleksi' hidden="hidden">
        <div class="col-sm-1"></div>
        <div class="col-sm-3">
            <center><img src="../../uploaded_files/foto_anggota/nophoto.jpg" style="width: 146px; height: 146px" id="MemberPhoto" class="img-circle"><h4><b id="namaMember">EDWIN JUNAEDI</b></h4></center>
        </div>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-11">
                    <p><?= Yii::t('app','Silahkan pindai barcode koleksi yang akan anda baca'); ?></p>
                </div>
                <div class="col-sm-1">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-md-11">
                    <div class="form-group">
                        <input type="text" id="pindaiBuku" class="form-control" value="" maxlength="50">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <button id="PindaiKoleksi_btCheck" type="button" class="btn btn-default">
                            <i class="fa fa-check"></i>
                        </button>
                    </div>
                </div>
                <p class="alertprogres"><?= Yii::t('app','Ditunggu '); ?> <span id="secondRemaining"></span> <?= Yii::t('app','detik'); ?></p>
            </div>
            <div class="row"><strong class="text-danger" id="warnBookScan">&nbsp;</strong></div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="box-body no-padding">
                        <div class="table table-condensed">
                            <div class="book-items">
                                <div id="labelKoleksi" class="col-sm-12 book-item">
                                    <div class="col-sm-12">
                                        <h4><b><?= Yii::t('app','Keranjang Baca Anda'); ?></b></h4>
                                    </div>
                                </div>
                               <!--  <tr>
                                    <td width="20%"><img src="img/thumb_10.jpg"></td>
                                    <td width="80%">
                                        <h5><b>Pengantar Komputer /</b> Siswono Yudohusodo</h5>
                                        <p>Jakarta: Gramedia, 2010</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="img/thumb_13.jpg"></td>
                                    <td>
                                        <h5><b>Babat Majapahit /</b> Limbad</h5>
                                        <p>Jakarta: Gramedia, 2010</p>
                                    </td>
                                </tr> -->
                            </div>
                            <div>
                                <td colspan="2">
                                    <a class="showhide" id="showAll" hidden="hidden" href="javascript:void(null)">+ 5 lainnya</a>
                                    <a class="showhide" id="hideAll" hidden="hidden" href="javascript:void(null)">+ 5 lainnya</a>
                                </td>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-2"></div>
        <br>
        <!-- /.box-body -->
    </div>

    <!-- Hidden information for post data -->
    <!-- /Hidden information for post data -->


    <?php ActiveForm::end();?>

</div>




<?php

$urlpoto = Yii::$app->urlManager->createUrl("../uploaded_files/foto_anggota/");
$this->registerJs("

    var noAnggota = $('#noAnggota').val();
    var mType = '';

    function checkImage (src, good, bad) {
    	var img = new Image();
    	img.onload = good; 
    	img.onerror = bad;
    	img. src = src;
    }

    $('#noAnggota').keydown(function(event){
        if(event.keyCode == 13)
        {
            event.preventDefault();
            validation();
            return false;
        }
    });


    $('#pindaiBuku').keydown(function(event){
        if(event.keyCode == 13)
        {
            event.preventDefault();
            pindaiKoleksiBuku();
            return false;
        }
    });

    $('#ContentPlaceHolder1_btCheck').click(function(){
        validation();
    });

    $('#PindaiKoleksi_btCheck').click(function(){
        pindaiKoleksiBuku();
    });
    // countDown 10 s per 1 s
    var count = 10;
    function countDown() {
        $('#secondRemaining').html(count);
        if (count == 0)
        {
            // time is up
            //setTimeout(goBack, 500);
            location.reload();
        }
        else
        {
            count--;
            // setTimeout(countDown, 10000);
            setTimeout(countDown, 1000);
        }
    }


    function validation(){
        //alert('Jalan');
        var noAnggota = $('#noAnggota').val();

        if (!noAnggota)
        {
            $('#message-nomember').html('".Yii::t('app','Masukkan Nomor Anggota terlebih dahulu')."');
        }
        else
        {
            $.get('validasi-anggota',{ noAnggota : noAnggota },function(data)
            {
                var data = $.parseJSON(data);
                if (!data) {
                    $('#message-nomember').html('".Yii::t('app','Maaf, No Anggota / Pengunjung salah')."');
                    $('#noAnggota').val('');
                } else {
                    //alert(data.id);
                    $('#namaMember').html(data.name);
                    $('#pindaiKoleksi').show();
                    $('#input-nomember').hide();
                    countDown();

                    if (data.type == 'member')
                    {
                        //For foto member
                        //$('#MemberPhoto').attr('src',  '".MemberHelpers::getPathFotoAnggotaOriginal()."/foto_anggota/' + data.id +'.jpg');
                        //$('#MemberPhoto').attr('src',  '../../uploaded_files/foto_anggota/' + data.id +'.jpg');
                        //alert(data.PhotoUrl);
                        // for member photo
                        checkImage( '".$urlpoto ."/' + data.PhotoUrl , 
                        function(){
                        	$('#MemberPhoto').attr('src',  '".$urlpoto ."/' + data.PhotoUrl);
	                    },  
	                    function(){ 
	                    	$('#MemberPhoto').attr('src',  '".$urlpoto ."/' + 'nophoto' +'.jpg');
	                    	console.log('tidak ada foto');
	                    } 
                    );


                    }
                    //set focus ke input Field pindai Buku
                    $( '#pindaiBuku' ).focus();

                    //cek apakah hari ini anggota pernah scan buku atau belum, dan set cookie buku yg sudah terscan oleh anggota
                    $.get('readed-collection-from-member',{ noAnggota : noAnggota ,  type : data.type, id : data.id },function(data)
                    {
                        //Set html add
                        $( '#labelKoleksi' ).after(data);
                        bookItem();
                    });
                }
            });
        }


    }

    $('#btnNotMember').click(function(){
        // alert('ok')
        $.get('validasi-anggota',{ noAnggota : noAnggota },function(data)
        {
            var data = $.parseJSON(data);
            console.log(data)
            if (!data) {
                $('#message-nomember').html('".Yii::t('app','Maaf, No Anggota / Pengunjung salah')."');
                $('#noAnggota').val('');
            } else {
                //alert(data.id);
                $('#namaMember').html(data.name);
                $('#pindaiKoleksi').show();
                $('#input-nomember').hide();
                countDown();

                if (data.type == 'member')
                {
                    //For foto member
                    //$('#MemberPhoto').attr('src',  '".MemberHelpers::getPathFotoAnggotaOriginal()."/foto_anggota/' + data.id +'.jpg');
                    //$('#MemberPhoto').attr('src',  '../../uploaded_files/foto_anggota/' + data.id +'.jpg');
                    //alert(data.PhotoUrl);
                    // for member photo
                    checkImage( '".$urlpoto ."/' + data.PhotoUrl , 
                    function(){
                        $('#MemberPhoto').attr('src',  '".$urlpoto ."/' + data.PhotoUrl);
                    },  
                    function(){ 
                        $('#MemberPhoto').attr('src',  '".$urlpoto ."/' + 'nophoto' +'.jpg');
                        console.log('tidak ada foto');
                    } 
                );


                }
                //set focus ke input Field pindai Buku
                $( '#pindaiBuku' ).focus();

                //cek apakah hari ini anggota pernah scan buku atau belum, dan set cookie buku yg sudah terscan oleh anggota
                $.get('readed-collection-from-member',{ noAnggota : noAnggota ,  type : data.type, id : data.id },function(data)
                {
                    //Set html add
                    $( '#labelKoleksi' ).after(data);
                    bookItem();
                });
            }
        });
    });


    function pindaiKoleksiBuku(){
        var noBuku = $('#pindaiBuku').val();
        var noAnggota = $('#noAnggota').val();

        if (!noBuku)
        {
            $('#warnBookScan').html('".Yii::t('app','Scan Barcode buku terlebih dahulu')."');
            count = 10;
        }
        else
        {
            $.ajax({
                url: 'pindai-buku?noBuku=' + noBuku +'&noAnggota=' + noAnggota,
                type: 'GET',
                success: function(data){
                    var data = $.parseJSON(data);
                    // console.log(data)
                    if (!data)
                    {
                        //alert('tidak ada');
                        $('#warnBookScan').html('".Yii::t('app','Data Buku Tidak Ditemukan')."');
                        count = 10;
                        $('#pindaiBuku').val('');
                    }
                    else
                    {
                        if (data.exist == true) {
                            $('#warnBookScan').html('".Yii::t('app','Buku dengan judul yang sama sudah terpindai.')."');
                        } else {
                            // alert(data.CoverURL);
                            $( '#labelKoleksi' ).after( \"<div class='col-sm-12 book-item' style='padding: 10px 0;'><div class='col-sm-2'><img src='\"+ data.imgBookUrl +\"' style='width: 54px; height:84px;'></div><div class='col-sm-10'><h5><b>\"+ data.Title +\" /</b> \"+ data.Author +\" </h5><p>\"+ data.PublishLocation +\" \"+ data.Publisher +\" \"+ data.PublishYear +\"</p></div></div>\" );
                            $('#warnBookScan').html('".Yii::t('app','Berhasil Tersimpan')."');
                        }
                        // alert(data.Title);
                        count = 10;
                        $('#pindaiBuku').val('');
                        bookItem();

                    }
                },
                error: function(data)
                {
                    swal('".Yii::t('app','Error, Hubungi administrator.')."');
                    // $('#warnBookScan').html('Mohon maaf data tidak ditemukan.<br> Silahkan ulangi lagi');
                    count = 10;
                }
            });
        }
    }


    function bookItem()
    {
        if($('.book-item').length > 5)
        {
            var jumlah = $('.book-item').length ;
            $('#showAll').show();
            // $('.showhide').html('+ '+(jumlah-5)+' lainnya');
            $('#showAll').html('+ '+(jumlah-5)+' lainnya');
            $('#hideAll').html('- '+(jumlah-5)+' lainnya');
        }
    }

    $('#showAll').click(function()
    {
        $('.book-items div:nth-child(n+6)').slideToggle();
        $('#showAll').hide();
        $('#hideAll').show();
    }
    );

    $('#hideAll').click(function()
    {
        $('.book-items div:nth-child(n+6)').slideToggle();
        $('#showAll').show();
        $('#hideAll').hide();
    }
    );


");

?>
