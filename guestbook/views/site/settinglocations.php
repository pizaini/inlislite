<?php
use yii\helpers\Html;
use common\models\Locations;
use yii\helpers\ArrayHelper;
//use common\components\AjaxSubmitButton;
use common\components\OpacHelpers;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Buku Tamu Pengunjung');

Yii::$app->view->params['subTitle'] = '<h3>'.Yii::t('app', 'Penentuan Lokasi').'<br>'.Yii::t('app', 'Buku Tamu').'</h3>';

?>
    <div class="message" data-message-value="<?= Yii::$app->session->getFlash('message') ?>">
    </div>


<div class="box-body" style="padding:50px 0">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <center>
            <?= Html::beginForm(Yii::$app->request->baseUrl.'/site/setting-locations', 'post', ['class'=>'uk-width-medium-1-1 uk-form uk-form-horizontal']); ?>
            <h4><b><?= Yii::t('app', 'IP Komputer') ?></b></h4>
            <!-- <h4><b><?= Yii::$app->request->userIP ?></b></h4> -->
            <h4><b><?= OpacHelpers::getIP() ?></b></h4>
            <br>

            <div class="form-group">
                <?= Html::dropDownList('LocationLibrary', null,
                    ArrayHelper::map($loclibs, 'ID', 'Name'),
                    ['prompt' => Yii::t('app', '-- Silahkan pilih lokasi perpustakaan --'), 'class'=>'form-control','id'=>'locations-Library']) ?>
            </div>

            <div class="form-group" id="selecter-locations">
            </div>

            <button type="submit" class="btn btn-success btn-md btn-block"><?= Yii::t('app', 'Simpan') ?></button>
            <!--<a class="login-link" href="#">Lupa password?</a>-->
            <?= Html::endForm(); ?>   
        </center>
    </div>
    <div class="col-sm-4"></div>          
</div>



<?php
$AppbaseUrl = json_encode(Yii::$app->getUrlManager()->getBaseUrl());

$script = <<< JS
	var appBaseUrl = $AppbaseUrl;
    $('#locations-Library').change(function(){
        var idLoc = $(this).val();
        // swal(idLoc);
        $.get('load-selecter-locations',{idLoc : idLoc},function(data){
			startLoading();
        })
        .done(function(data) {
            $( '#selecter-locations' ).html(data); 
			endLoading();
            // alert( "second success" );
        })
        .fail(function(data) {
            $('#locations-id').hide();
			endLoading();
            // alert( "error" );
        });
    });


    if ($('.message').data("messageValue")) {
        alert($('.message').data("messageValue"));
    }


	//Loading select Location
	function startLoading()
	{
		var url = appBaseUrl;
		$("<div class=\"modal-backdrop\" style=\"opacity:0.5\"><center><img src=\""+url+"/../backend/assets_b/images/loading.gif\" class=\"centered\" width=\"150px\" /></center></div>").appendTo(document.body);
	}

	function endLoading()
	{
		$(".modal-backdrop").remove();
	}



JS;

$this->registerJs($script);
?>
