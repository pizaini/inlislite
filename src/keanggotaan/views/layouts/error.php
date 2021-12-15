<?php

/* @var $this \yii\web\View */
/* @var $content string */


use yii\helpers\Html;
use yii\helpers\Url;

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use keanggotaan\assets_b\ErrorAsset;
use common\widgets\Alert;



ErrorAsset::register($this);
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
    <link rel="shortcut icon" type='image/x-icon' href="favicon.png">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<body>
<div class="wrapper">
   
    
    <div class="content-wrapper" >
        <div class="container">
             <!-- Main content -->
            <section class="content">
                <?= Alert::widget() ?>
                <?= $content ?>
            </section>
        </div><!-- /.container -->
    </div><!-- /.content-wrapper -->
    
</div><!-- ./wrapper -->

<!-- <footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Perpustakaan Nasional Republik Indonesia <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer> -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
