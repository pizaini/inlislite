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
       
        <div id="divCloud" runat="server" class="cloud" style="height:190px !important"></div>
        
    </div>
</div><!-- /.login-box -->
<div class="outbox" style="margin-top:115px;">    <div style="opacity: 0.90; text-shadow: 2px 2px #333333; font-family: 'Candara'; margin-top: 35px;">
        <span style="font-weight: bold;font-size: 20px; color: white"> <?= yii::t('app','Pilih Lokasi Perpustakaan') ?>  </span>                          
    </div>

    <div class="login-box-body" style="padding-left: 40px;">       
        <?php $form = ActiveForm::begin([
                'id' => 'login-form', 'enableClientValidation' => false,
                'layout'=>'inline']); ?>

        <?=$form->field($model, 'location')->widget(\kartik\select2\Select2::classname(),[
                'data'=>\yii\helpers\ArrayHelper::map($modelLocation, 'ID', 'Name'),
                //'size'=>'sm',
                'pluginOptions' => [
                    'allowClear' => false,
                    'width'=> '300px',
                ],
            ]

        )->label(false)?>
        
        <div class="form-group">
            
                <?= Html::submitButton('', ['class' => 'btn btn-primary btn-block btn-flat button_login', 'name' => 'login-button']) ?>
            
        </div>
        <?php ActiveForm::end(); ?>
    </div>    
</div>
        <div id="footer"> <span class="inlislite">Inlislite V3.1 © <?= yii::t('app',\Yii::$app->params['year']); ?> </span> <span class="perpus">  <?= yii::t('app','Perpustakaan Nasional Republik Indonesia') ?></span></div>
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
                'type' => \common\components\Alert::TYPE_ERROR,
                'options' => [
                    'title' => 'Oops...',
                    'confirmButtonText'  => "OK",

                ]
        ]);
       }
}


?>