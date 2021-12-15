<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;


$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-user form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>


    
        
            <?php $form = ActiveForm::begin(
                [
                    'id' => 'login-anggota',
                ]
            ); ?>
    <div class="login-form">
                <?= $form->field($model, 'noanggota', $fieldOptions1)->textInput(array('placeholder' => Yii::t('app', 'No.Anggota')))->label(false) ?>

                <?= $form->field($model, 'password',$fieldOptions2)->passwordInput(array('placeholder' => 'Password'))->label(false) ?>

                <?php // $form->field($model, 'rememberMe')->checkbox() ?>
                <div style="margin: 5px;" id="error-login-opac-123-321" class="text-danger"></div>
                <div class="form-group">

                <?= Html::a(Yii::t('app', 'Masuk'),'javascript:void(0)', ['class' => 'btn btn-success btn-md btn-block', 'name' => 'login-button','onClick'=>'loginAnggota();']) ?>
                    <div><?= Html::a(Yii::t('app', 'Lupa password / No. Anggota ?'), ['site/request-password-reset']) ?>.</div>
                </div>

            <?php ActiveForm::end(); ?>
        
    
       
    </div>

