<?php

use yii\helpers\Html;
use inliscore\adminlte\MyAsset;
use backend\assets_b\AppAsset;
use backend\assets_b\ActiveResponseAsset;
use yii\helpers\Url;

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
        <link rel="shortcut icon" type='image/x-icon' href="/..<?=Yii::$app->urlManager->createUrl('/uploaded_files/aplikasi/favicon.png');?>">
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
	
	<style>
		.sidebar-menu .treeview-menu > li > a {
		  font-size: 12px;
		}

        body {  
            font-size: 11px;
        
    
        }


        /**
         * Adjust QueryBuilder Style
         * 
         */
        .query-builder .rules-group-container .rules-list .form-control{
          font-size: 11px;
        }

	</style>
	
    <?php $this->beginBody() ?>
    <body class="skin-blue">
        <div class="wrapper">

            <?php

            if(Yii::$app->id == 'app-keanggotaan-inlislite'){
                echo $this->render('heading-member', ['baseurl' => $baseurl]);
                echo $this->render('sidebar-member', ['baseurl' => $baseurl]);
            }else{
                echo $this->render('heading', ['baseurl' => $baseurl]);
                echo $this->render('sidebar', ['baseurl' => $baseurl]);
            }
            ?>



            <?php echo $this->render('content', ['content' => $content,'baseurl' => $baseurl]); ?>


        </div>

        <!--        <footer class="footer">
                    <div class="container">
                        <p class="pull-left">&copy; My Company <?= ''//date('Y')      ?></p>
                        <p class="pull-right"><?= ''//Yii::powered()      ?></p>
                    </div>
                </footer>-->
    </div>

    <?php 

echo $this->registerJS("
    var appBaseUrl = ".json_encode(Yii::$app->getUrlManager()->getBaseUrl())."

    var isLoading = true;
    ",yii\web\View::POS_HEAD);



?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<?php } ?>
<script type="text/javascript">
       $('body').scrollTop(0);
        jQuery(document).ajaxStart(function () {
            //show ajax indicator
            if(isLoading){
                startLoading();
            }
        }).ajaxComplete(function () {
            //hide ajax indicator
            if(isLoading){
                endLoading();
            }
        }).ajaxStop(function () {
            //hide ajax indicator
            if(isLoading){
                endLoading();
            }
        }).ajaxError(function () {
            //hide ajax indicator
            if(isLoading){
                endLoading();
            }
        });

        

</script>
