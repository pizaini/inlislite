<?php

/* @var $this \yii\web\View */
/* @var $content string */


use yii\helpers\Html;
use yii\helpers\Url;

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use keanggotaan\assets_b\LoginAsset;
use common\widgets\Alert;


LoginAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <!-- Favicons -->
     <link rel="shortcut icon" type='image/x-icon' href="../..<?=Yii::getAlias('@upload')."/aplikasi/favicon.png"?>">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>



<body class="skin-blue layout-top-nav">
<div class="wrapper">
   <header class="main-header">
        <nav class="navbar navbar-static-top">
          <div class="container">
            <div class="navbar-header">

              <div class="title">
                    <div class="image">
                        <a href='<?=$homeUrl?>'> <img src="<?= Yii::$app->urlManager->createUrl('../uploaded_files/aplikasi/logo_perpusnas_2015.png') ?>" class="img-logo" height="65" width="70"> </a>
                         <?php //yii\helpers\Html::img(Url::base().'/'.Url::to('assets_b/img/logo.png'), ['alt'=>'Logo Keanggotaan Online Inlislite', 'class'=>'img-logo', ]) ?>
                        <!-- <img src="img/logo.png" class="img-logo"> -->
                    </div>
                    <div class="text">
                        <h3>Keanggotaan Online</h3>
                        <div class="clear"></div>
                        <div class="time"><?=Yii::$app->config->get('NamaPerpustakaan')?></div>
                        <div class="clear"></div>
                    </div><button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                <i class="fa fa-bars"></i>
              </button>
              <div class="clear"></div>
                </div>
              
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse pull-right" id="navbar-collapse">
              <span class="pull-right" style="color:#fff; margin-right:5px; margin-top:5px">

						<div id="clock-large" font size="6" > </div>


            
            </div><!-- /.navbar-collapse -->
            <!-- Navbar Right Menu -->
              <!-- /.navbar-custom-menu -->
          </div><!-- /.container-fluid -->
        </nav>
      </header>
    <section class="content-search" style="min-height:520px">
        <div class="container-login" >
            <div class="container">
                 <!-- Main content -->
                <section class="content">
                    <?= Alert::widget() ?>
                    <?= $content ?>
                </section>
            </div><!-- /.container -->
        </div><!-- /.content-wrapper -->
    </section>
	<footer class="footer main-footer">
		<div class="container">
			<div class="pull-right hidden-sm" style="font-family: "Corbel", Arial, Helvetica, sans-serif;">
				<?=\Yii::$app->params['footerInfoRight'];?>
			</div>
			<?= yii::t('app',\Yii::$app->params['footerInfoLeft']); ?> &copy; <?= yii::t('app',\Yii::$app->params['year']); ?> <a href="http://inlislite.perpusnas.go.id" target="_blank"><?= yii::t('app','Perpustakaan Nasional Republik Indonesia') ?></a>
		</div> <!-- /.container -->
	</footer>
</div><!-- ./wrapper -->

<!-- <footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Perpustakaan Nasional Republik Indonesia <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer> -->



<?php $this->endBody() ?>
<script type="text/javascript">
    GoToPagePendaftaran = function () {
        var parser = document.createElement('a');
        parser.href = document.URL;
        var path = String(parser.pathname).split("/");
        var url = "http://" + parser.host + "/pendaftaran";
        url = String(url);
        window.location = url;
    };
    GoToPageAktivasi = function () {
        var parser = document.createElement('a');
        parser.href = document.URL;
        var path = String(parser.pathname).split("/");
        var url = "http://" + parser.host + "/pendaftaran/anggota-aktif";
        url = String(url);
        window.location = url;
    };

    validateEmail = function ($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if (!emailReg.test($email)) { return false; }
        else { return true; }
    };

    alertSwal = function($msg,$type,$timer) {
        swal({
            title: " ",
            text: $msg,
            type: $type,
            timer: $timer,
            cancelButtonText: "Tutup",
            closeOnConfirm: true,
        });
    };

    cleanResponseError = function($responseText,$varFind){
        var msg = $responseText.replace($varFind, " ")
        return msg;
    };

</script>
</body>
</html>
<?php $this->endPage() ?>




