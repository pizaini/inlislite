<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Baca di Tempat - Pindai Koleksi yang Dibaca');
?>
    <div class="message" data-message-value="<?= Yii::$app->session->getFlash('message') ?>">
    </div>
    <div id='content' align="center">
        <div id="div-loading"></div>
         <br />
            <br />
            <br />    
        <div style="position:relative;top:0px;     ;
            display:block;height:auto;width:100%;vertical-align:middle;   ">
          
                <div style="height:auto;position:relative;display:block;width:880px;border:0px solid red;top:0px; vertical-align:middle">
                
                    <table style="width:100%; " cellpadding="0px" cellspacing="0px" border="0px">
                        <tr>
                            
                            <td style=" vertical-align:middle" >
                                <?php echo Html::beginForm(Yii::$app->request->baseUrl.'/site/pindai-no', 'post', ['class'=>'uk-width-medium-1-1 uk-form uk-form-horizontal']); ?>
                                <div id="cekpoincontent"  
                                    style="font-size:11pt;color:white;position:relative;display:block;vertical-align:middle;
                                    padding:10px 20px;width:880px; height:auto;top:-30px;overflow:auto; background:rgba(255, 255, 255, 0.1);float:none;right:0px;border-radius:13px;">
&nbsp;&nbsp;
<div id="ContentPlaceHolder1_UpdatePanel1">
        <div id="ContentPlaceHolder1_UpdateProgress1" style="display:none;">
                <img id="ContentPlaceHolder1_Image1" src="images/loader.gif" alt="Proses..." />    
    </div>
    <div style=" text-align:center; width:800px; vertical-align: bottom;   ">
    <span style='font-size:13pt;color:#ffcc00; text-align:center; width:800px '><b>
    <span id="ContentPlaceHolder1_MapLokasi" style="color:White;font-size:13pt;color:#ffcc00; text-align:center; width:800px"><?= $libraryName ?></span>    
   <br />
                                <span id="ContentPlaceHolder1_lbNamaPerpus" style="color:White;font-size:10pt;font-weight:bold;position:relative;top:5px;text-shadow: 1 2px 1px #222;color:#ffcc00;">
                                <?= $locationName ?><br />
                                (<?= $readedCollection['eksemplar'] ?> eksemplar dari <?= $readedCollection['judul'] ?> judul dibaca hari ini)
                                </span>
                                </b>
                                </span></div><br/><br/>
                                <div style=" margin-left:100px;  text-align:center; width:800px; vertical-align: bottom ">
    <table  cellpadding="3" cellspacing="1" id="tabledaftar"   >
        <tr>
            <td rowspan="11" style=" vertical-align:top">
            <img src="<?= $urlLogo ?>" id="ContentPlaceHolder1_imgAnggota" style="height: 242px" /><br /> </td>
            <td style=" vertical-align:top"><span style='font-size:11pt; text-align:center;  '>
                <h1><?= $verifiedName ?></h1>
                <h2><?= $verifiedNo ?></h2>
                <br />
                Silahkan pindai barcode koleksi yang akan dibaca</span></td>
        </tr>
        <tr>
            <td colspan="3" style=" vertical-align:top">
            <input name="noKoleksi" value="<?= $no ?>" type="text" size="30" name="txtNoindentitas" class="inp_elm_cekpoin" style=" height: 20px;width:350px; " />
            </td>
        </tr>
        <tr>
            <td style=" vertical-align:top">
            <span style='font-size:11pt; text-align:center;  '>
                Ditunggu <div id="secondRemaining">10</div> detik 
            </span>
                &nbsp;</td>
        </tr>
        <tr>
            <td align="left" colspan="3" style=" vertical-align:top">
                &nbsp;</td>
        </tr>
        <tr>
            <td align="left" colspan="3" style=" vertical-align:top; height: 21px;">
                                                    
</td>
        </tr>
        <tr>
            <td colspan="3" style=" vertical-align:top">
                &nbsp;</td>
        </tr>
        <tr>
            <td style=" vertical-align:top; height: 25px;" colspan="3" align="left">
                <?php if (!$verifiedNo) { ?>
                Maaf data tidak ditemukan
                <br />Silahkan ulangi lagi
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding:1em;text-align:center;"><b><span id="ContentPlaceHolder1_lblError" style="color:Red;"></span></b></td>
        </tr>

         <tr>

            <td style="font-size:11pt; vertical-align:top">&nbsp; </td>
            <td style=" vertical-align:top">&nbsp;</td>
            <td style=" vertical-align:top">&nbsp;
            </td>
        </tr>
         <tr>

            <td style="font-size:11pt; vertical-align:top">&nbsp; </td>
            <td style=" vertical-align:top">&nbsp;</td>
            <td style=" vertical-align:top">&nbsp;
            </td>
        </tr>
         <tr>

            <td style="font-size:11pt; vertical-align:top">&nbsp; </td>
            <td style=" vertical-align:top">&nbsp;</td>
            <td style=" vertical-align:top">&nbsp;
            </td>
        </tr>
    </table>
    <table>
        <tr><td><h3>Keranjang Belanja Anda</h3></td></tr>
        <tr><td><h3>Keranjang Belanja Anda</h3></td></tr>
    </table>
    <br />
    </div>
     <input name="ctl00$ContentPlaceHolder1$OriginalID" type="hidden" id="ContentPlaceHolder1_OriginalID" />
     <div id="hiy"></div>
</div>
<script type="text/javascript" language="javascript">
    function handleEnter(obj, event) {
        var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
        if (keyCode == 13) {
            document.getElementById(obj).click();
            return false;
        }
        else {
            return true;
        }
    } 
</script>                                      
</div>
<?php echo Html::endForm(); ?>                                          
                            </td>
                        </tr>
                        </table>
                </div>
            </div>
            
        <div id="footer"><br /><span class="perpus">Copyright &copy; 2013. Perpustakaan Nasional Republik Indonesia</span></div>
    </div>

<?php
$script = <<< JS

if ($('.message').data("messageValue")) {
    alert($('.message').data("messageValue"));
}

// countDown 10 s per 1 s
var count = 10;
function countDown() {
    $('#secondRemaining').html(count);
    if (count == 0) {
        // time is up
        setTimeout(goBack, 500);
    } else {
        count--;
        setTimeout(countDown, 1000);
    }
}

function goBack() {
    window.location.href = '/inlislite3/bacaditempat';
}

function scanKoleksi() {
    window.location.href = '/inlislite3/bacaditempat';

    // reset counter
    count = 10;
    $('#secondRemaining').html(count);
}

countDown();
JS;

$this->registerJs($script);
?>