<?php

/* @var $this \yii\web\View */
/* @var $content string */

use guestbook\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

$asset = AppAsset::register($this);
$baseurl = $asset->baseUrl;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="<?= Yii::$app->request->baseUrl . '/web'?>/css/login.css" type="text/css" />
    <script type="text/javascript" src="<?= Yii::$app->request->baseUrl . '/web'?>/js/jquery/jquery.js"></script>
    <script src="<?= Yii::$app->request->baseUrl . '/web'?>/js/jquery/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
    <script src="<?= Yii::$app->request->baseUrl . '/web'?>/js/jquery/jquery.dataTables.min.js" type="text/javascript"></script> 
</head>
<body>

<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    /*
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = [
            'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    */
    ?>

    <div class="container">
        <?php /*= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) */?>
        <?php //= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<!--
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
