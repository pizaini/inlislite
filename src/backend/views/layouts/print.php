<?php

use yii\helpers\Html;
use inliscore\adminlte\MyAsset;
use backend\assets_b\AppAsset;
use backend\assets_b\ActiveResponseAsset;


/**
 * @var \yii\web\View $this
 * @var string $content
 */

if (Yii::$app->controller->action->id === 'login') {
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

$lte_asset = MyAsset::register($this);
$baseurl = $lte_asset->baseUrl;
AppAsset::register($this);
ActiveResponseAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" manifest="<?= isset($this->context->manifestFile) ? $this->context->manifestFile : '' ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
         <!-- Favicons -->
        <link rel="shortcut icon" type='image/x-icon' href="<?= Yii::$app->request->baseUrl . '/assets_b/images/icon.png' ?>">

        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
	
	<style>
		.sidebar-menu .treeview-menu > li > a {
		  font-size: 11px;
		}
	</style>
	
    <?php $this->beginBody() ?>
    <body class="skin-blue">
        <div class="wrapper">

            <?= $content ?>
        
        </div>
    </div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<?php } ?>