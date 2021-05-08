<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\JenisPerpustakaan $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="jenis-perpustakaan-form">
    <div class="col-xs-6 col-sm-6">
        <?php
        $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'formConfig' => ['labelSpan' => 4, 'deviceSize' => ActiveForm::SIZE_SMALL]]);
        echo '<div class="page-header">';
        echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
        echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) ;
        echo '</div>';
        echo Form::widget([

            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'Modul' => ['type' => Form::INPUT_TEXT, 'options' => ['style' => 'width:300px', 'placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Modul') . '...', 'maxlength' => 50]],
                'Host' => ['type' => Form::INPUT_TEXT, 'options' => ['style' => 'width:300px', 'placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Host') . '...', 'maxlength' => 50]],
                'Port' => ['type' => Form::INPUT_TEXT, 'options' => ['style' => 'width:300px', 'placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Port') . '...', 'maxlength' => 11]],
                'CredentialMail' => ['type' => Form::INPUT_TEXT, 'options' => ['style' => 'width:300px', 'placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Credential Mail') . '...', 'maxlength' => 50]],
                'CredentialPassword' => ['type' => Form::INPUT_TEXT, 'options' => ['style' => 'width:300px', 'placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Credential Password') . '...', 'maxlength' => 50]],
                'MailFrom'=>['type'=> Form::INPUT_TEXT, 'options'=>['style'=>'width:300px','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Mail From').'...', 'maxlength'=>50]], 
                'MailDisplayName'=>['type'=> Form::INPUT_TEXT, 'options'=>['style'=>'width:300px','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Mail Display Name').'...', 'maxlength'=>50]], 
                'IsActive'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['style'=>'width:300px','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Is Active').'...',]],
                'EnableSsl'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['style'=>'width:300px','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Enable Ssl').'...']], 
                ]
        ]);
        
        

        ActiveForm::end();
        ?>
    </div>
</div>
