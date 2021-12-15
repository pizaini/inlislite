<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \keanggotaan\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$urlResetPassword           = Url::to('kirim-password');
$ajaxOptionResetPassword    = [
    'type' => 'POST',
    'url'  => $urlResetPassword,
    'data' => array(
        'NoAnggota' => new yii\web\JsExpression('function(){ return $("#passwordresetrequestform-memberno").val(); }'),
        'Email' => new yii\web\JsExpression('function(){ return $("#passwordresetrequestform-emailaddress").val(); }'),
    ),
    'beforeSend'=>new yii\web\JsExpression('function(){
    var $msg = "";
			if($.trim($("#passwordresetrequestform-emailaddress").val()) == ""){
			    $msg = "Alamat email tidak boleh kosong.";
			    $("#passwordresetrequestform-emailaddress").focus();
                alertSwal($msg,"warning",1500);

			    return false;
			}

			if($("#passwordresetrequestform-emailaddress").val().length > 0 && !validateEmail($("#passwordresetrequestform-emailaddress").val())){
			    $msg = "Alamat email bukan alamat email yang valid.";
			    $("#passwordresetrequestform-emailaddress").focus();
			    alertSwal($msg,"warning",1500);
			    return false;
			}

			if($.trim($("#passwordresetrequestform-memberno").val()) == ""){
			    $msg = "No.Anggota tidak boleh kosong.";
			      $("#passwordresetrequestform-memberno").focus();
			    alertSwal($msg,"warning",1500);

			    return false;
			}

			if($.trim($("#passwordresetrequestform-memberno").val()) == ""){
			    $msg = "No.Anggota tidak boleh kosong.";
			     $("#passwordresetrequestform-memberno").focus();
			    alertSwal($msg,"warning",1500);

			    return false;
			}



     }'),
    'success'=>new yii\web\JsExpression('function(data){
			alertSwal(data,"success",2000);
			$("#passwordresetrequestform-memberno").val("");
			$("#passwordresetrequestform-emailaddress").val("");

     }'),
    'error' => new yii\web\JsExpression('function(xhr, ajaxOptions, thrownError){
                    var msg = cleanResponseError(xhr.responseText,"Not Found (#404): ");
                    alertSwal(msg,"warning",1500);

     }'),

];

// Kirim No.Anggota

$urlSendMemberNo           = Url::to('kirim-nomor');
$ajaxOptionSendMemberNo   = [
    'type' => 'POST',
    'url'  => $urlSendMemberNo,
    'data' => array(
        'Nama' => new yii\web\JsExpression('function(){ return $("#passwordresetrequestform-name").val(); }'),
        'Tgl' => new yii\web\JsExpression('function(){ return $("#passwordresetrequestform-dateofbirth").val(); }'),
        'Email' => new yii\web\JsExpression('function(){ return $("#passwordresetrequestform-emailaddress2").val(); }'),
    ),
    'beforeSend'=>new yii\web\JsExpression('function(){
    var $msg = "";
			if($.trim($("#passwordresetrequestform-emailaddress2").val()) == ""){
			    $msg = "Alamat email tidak boleh kosong.";
			    $("#passwordresetrequestform-emailaddress2").focus();
                alertSwal($msg,"warning",1500);

			    return false;
			}

			if($("#passwordresetrequestform-emailaddress2").val().length > 0 && !validateEmail($("#passwordresetrequestform-emailaddress2").val())){
			    $msg = "Alamat email bukan alamat email yang valid.";
			    $("#passwordresetrequestform-emailaddress2").focus();
			    alertSwal($msg,"warning",1500);
			    return false;
			}

			if($.trim($("#passwordresetrequestform-name").val()) == ""){
			    $msg = "Nama tidak boleh kosong.";
			      $("#passwordresetrequestform-name").focus();
			    alertSwal($msg,"warning",1500);

			    return false;
			}

			if($.trim($("#passwordresetrequestform-dateofbirth").val()) == ""){
			    $msg = "Tanggal lahir tidak boleh kosong.";
			     $("#passwordresetrequestform-dateofbirth").focus();
			    alertSwal($msg,"warning",1500);

			    return false;
			}



     }'),
    'success'=>new yii\web\JsExpression('function(data){
			alertSwal(data,"success",2000);
			$("#passwordresetrequestform-name").val("");
			$("#passwordresetrequestform-emailaddress2").val("");
			$("#passwordresetrequestform-dateofbirth").val("");

     }'),
    'error' => new yii\web\JsExpression('function(xhr, ajaxOptions, thrownError){
                    var msg = cleanResponseError(xhr.responseText,"Not Found (#404): ");
                    alertSwal(msg,"warning",1500);

     }'),

];
?>

<div class="row">
    <div class="col-sm-5">
        <div class="box-body" >
            <div class="col-sm-1"></div>
            <div class="col-sm-11">
                <h4>Menghadapi masalah saat Login?</h4>


                <?php $form = ActiveForm::begin(
                    [
                        'id' => 'login-form',
                        'enableClientValidation'=>false
                    ]
                ); ?>
                <div class="login-form">
                    <span style="font-weight: bold">Saya lupa password</span>
                    <?= $form->field($model, 'EmailAddress')->textInput(array('placeholder' => 'Alamat Email'))->label(false) ?>

                    <?= $form->field($model, 'MemberNo')->textInput(array('placeholder' => 'No.Anggota'))->label(false) ?>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <?php
                            echo \common\widgets\AjaxButton::widget([
                                'label' => Yii::t('app','Kirim Password Baru'),
                                'ajaxOptions' => $ajaxOptionResetPassword,
                                'htmlOptions' => [
                                    'class' => 'btn btn-success btn-md',
                                    'id' => 'cari',
                                    'type' => 'submit'
                                ]
                            ]);
                            echo "&nbsp&nbsp";
                            echo \yii\helpers\Html::a('Kembali', Yii::$app->request->referrer, ['class'=>'btn btn-success'])
                            ?>
                        </div>

                    </div>
                    <br/>


                </div>
                <br/>
                <div class="login-form" >
                    <span style="font-weight: bold">Saya lupa nomor anggota</span>
                    <?= $form->field($model, 'EmailAddress2')->textInput(array('placeholder' => 'Alamat Email'))->label(false) ?>

                    <?= $form->field($model, 'Name')->textInput(array('placeholder' => 'Nama Anda'))->label(false) ?>

                    <?= $form->field($model, 'DateOfBirth')->widget(\common\widgets\MaskedDatePicker::classname(),
                        [

                            'enableMaskedInput' => true,
                            'maskedInputOptions' => [
                                'mask' => '99-99-9999',
                                'pluginEvents' => [
                                    'complete' => "function(){console.log('complete');}"
                                ]
                            ],
                            'removeButton' => false,
                            'options'=>[
                                'placeholder' => 'Tanggal Lahir',
                                //'style'=>'width:170px',
                            ],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'todayHighlight' => true,
                                'format'=>'dd-mm-yyyy',
                            ]
                        ])->label(false);

                    ?>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <?php
                            echo \common\widgets\AjaxButton::widget([
                                'label' => Yii::t('app','Kirim Nomor Anggota'),
                                'ajaxOptions' => $ajaxOptionSendMemberNo,
                                'htmlOptions' => [
                                    'class' => 'btn btn-success btn-md',
                                    'id' => 'send-memberNo',
                                    'type' => 'submit'
                                ]
                            ]);
                            echo "&nbsp&nbsp";
                            echo \yii\helpers\Html::a('Kembali', Yii::$app->request->referrer, ['class'=>'btn btn-success'])
                            ?>

                        </div>

                    </div>
                    <br/>
                </div>
                <?php ActiveForm::end(); ?>
            </div>

