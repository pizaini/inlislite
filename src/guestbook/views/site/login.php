<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Locations;
use yii\helpers\ArrayHelper;
//use common\components\AjaxSubmitButton;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Buku Tamu Pengunjung');
Yii::$app->view->params['subTitle'] = '<h3>Login</h3>';


?>
    <div class="message" data-message-value="<?= Yii::$app->session->getFlash('message') ?>">
    </div>



<div id="msgboxpanel"></div>
<div class="box-body" style="padding:50px 0">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <center>
            <h4>
                <?= Yii::t('app', 'Silahkan login') ?><br>
                <?= Yii::t('app', 'untuk menentukan lokasi buku tamu') ?>
            </h4>

            <?= Html::beginForm(Yii::$app->request->baseUrl.'/site/login', 'post', ['class'=>'uk-width-medium-1-1 uk-form uk-form-horizontal']); ?>
                <div class="login-form">
                    <div class="form-group">
                        <input name="Users[username]" type="text" id="txtUserName" class="form-control login-field"         placeholder="Username" />
                        <label class="login-field-user fa fa-user" for="login-name"></label>
                    </div>

                    <div class="form-group">
                        <input  name="Users[password]" type="password" id="txtPassword" class="form-control login-field" placeholder="Password" />
                        <label class="login-field-pass fa fa-lock" for="login-pass"></label>
                    </div>

                    <button class="btn btn-success btn-md btn-block" type="submit">Login</button>
                    <!--<a class="login-link" href="#">Lupa password?</a>-->
                </div>
            <?= Html::endForm(); ?>  
        </center>
    </div>
    <div class="col-sm-4"></div>          
</div>



<?php
$script = <<< JS

if ($('.message').data("messageValue")) {
    swal($('.message').data("messageValue"));
}
JS;

$this->registerJs($script);
?>
