<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Backoffice INLISLite';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-user form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>
<div id='content' align="center">

<div class="message" data-message-value="<?= Yii::$app->session->getFlash('message') ?>">
</div>

    <center>
<div class="login-box">
    <div class="login-logo">
       
        <div id="divCloud" runat="server" class="cloud"></div>
        
    </div>
    <div style="opacity: 0.90; text-shadow: 1px 1px 2px #027fa5, 0 0 25px #e3e3e459, 0 0 5px darkblue; font-family: 'Candara';">
        <span style="font-weight: bold;font-size: 26px;color: white"> BACKOFFICE </span>
                                
    </div>
    <div style="opacity: 0.90; text-shadow: 2px 2px #333333; font-family: 'Candara';">
        <span style="font-weight: bold;font-size: 20px;color: white"> <?=Yii::$app->config->get('NamaPerpustakaan'); ?> </span>
                                
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body" style="padding-left: 121px;">

<!--        <p class="login-box-msg">Sign in to start your session</p>-->
<!--        <img src="css/images/sign_in.png" alt=""/>-->
        
        <?php $form = ActiveForm::begin([
                'id' => 'login-form', 'enableClientValidation' => false,
                'layout'=>'inline']); ?>

        <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

        <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>
        
        <div class="form-group">
            
            <!-- /.col -->
            
                <?= Html::submitButton('', ['class' => 'btn btn-primary btn-block btn-flat button_login', 'name' => 'login-button']) ?>
            
            <!-- /.col -->
        </div>


        <?php ActiveForm::end(); ?>

       <!--  <a href="#">I forgot my password</a><br>
       <a href="register.html" class="text-center">Register a new membership</a> -->

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
        <div id="footer"> <span class="inlislite">Inlislite V3.2 © <?= yii::t('app',\Yii::$app->params['year']); ?> </span> <span class="perpus">  <?= yii::t('app','Perpustakaan Nasional Republik Indonesia') ?></span></div>
        </center>
</div>
<?php
$script = <<< JS

if ($('.message').data("messageValue")) {
    alert($('.message').data("messageValue"));
}
JS;

//$this->registerJs($script);

foreach (Yii::$app->session->getAllFlashes() as $message){
    $msg = $message['message'];
        if(!empty($msg)){
           echo \common\components\Alert::widget([
                'type' => '',
                'options' => [
                    'title' => 'Oops...',
                    'confirmButtonText'  => "OK",

                ]
        ]);
       }
}


?>