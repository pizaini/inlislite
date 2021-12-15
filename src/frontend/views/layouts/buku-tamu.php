<?php
/**
 * @link https://www.inlislite.perpusnas.go.id/
 * @copyright Copyright (c) 2015 Perpustakaan Nasional Republik Indonesia
 * @license https://www.inlislite.perpusnas.go.id/licences
 */


/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\PendaftaranAsset;
use common\widgets\Alert;
use yii\helpers\Url;

$asset = PendaftaranAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
  <head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> - Perpusnas RI</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="shortcut icon" type='image/x-icon' href="/../favicon.png">

    <?php $this->head() ?>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
  </head>


  <body class="skin-blue layout-top-nav">
    <div class="wrapper">
      <!-- Header web -->
      <header class="main-header">
        <!-- navbar -->
        <nav class="navbar navbar-static-top">
          <div class="container">
            <div class="navbar-header">
              <div class="title">
                <div class="image"><img src="<?= Yii::$app->urlManager->createUrl('../uploaded_files/aplikasi/logo_perpusnas_2015.png') ?>" class="img-logo" height="65" width="70"></div>
                <div class="text">
                  <h3><?= Html::encode($this->title) ?></h3>
                  <div class="clear"></div>
                  <div class="time"><?= Yii::$app->config->get('NamaPerpustakaan') ?></div>
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
              <span class="pull-right" style="color:#fff; margin-right:5px; margin-top:5px">Jumat, 30 September 2015 - 23:59</span><br>
              <!-- <ul class="nav navbar-nav">
                <li><a href="#">Keranjang (0)</a></li>
                <li><a href="#">Login</a></li>
                <li>
                  <a href="#">Registrasi </a>

                </li> -->
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
            <div class="col-md-12">
              <center>
                <strong>
                  <h3>
                    <?= Html::encode($this->subtitle) ?>
                  </h3>
                </strong>
              </center>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12" style="padding:0 -15px">
              <!-- Custom Tabs -->
              <!-- nav-tabs-custom -->
            </div><!-- /.col -->
          </div>

        </div>
      </section>
      <!-- Full Width Column -->

      <?php $this->beginBody() ?>
      <div class="content-wrapper">
        <div class="container">
          <!-- Content Header (Page header) -->

          <!-- Main content -->
          <section class="content">
            <?= Alert::widget() ?>
            <div class="box box-default">
              <?= $content ?>
            </div>
          </section><!-- /.content -->
        </div><!-- /.container -->
      </div><!-- /.content-wrapper -->


      <footer class="main-footer">
        <div class="container">
          <div class="pull-right hidden-xs">
            <b>Version</b> 3.0
          </div>
          Copyright &copy; <?= date('Y') ?> <a href="http://pnri.go.id">Perpustakaan Nasional Republik Indonesia</a>. All rights reserved.
        </div><!-- /.container -->
      </footer>
    <?php $this->endBody() ?>
    </div><!-- ./wrapper -->
  </body>
</html>
<?php $this->endPage() ?>