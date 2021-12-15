<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use common\models\Applications;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\JenisPerpustakaan $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<style type="text/css">

    .form-group > .col-md-offset-2, .col-md-10{
        margin-left: 0px;
    }
</style>

<div class="jenis-perpustakaan-form">
    <div class="col-xs-6 col-sm-6">
        <?php
        $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]);
        echo "<div class='page-header'>";
        echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
        echo ' '.Html::a(Yii::t('app', 'Back') , 'index',['class' =>  'btn btn-warning' ]);
        echo "</div>";
        echo Form::widget([

            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'Code' => ['type' => Form::INPUT_TEXT, 'options' => ['style' => 'width:300px', 'placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Kode') . '...', 'maxlength' => 50]],
                'Name' => ['type' => Form::INPUT_TEXT, 'options' => ['style' => 'width:300px', 'placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Name') . '...', 'maxlength' => 255]],
            //'IsActive'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['style'=>'width:300px','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Is Active').'...',]],
               
                ]
        ]);
        echo $form->field($model, 'IsActive')->checkbox(array('label' => 'Ya/ Tidak'))->label('Aktif');
        echo $form->field($model, 'Application_id')->widget('\kartik\widgets\Select2', [
            'data' => ArrayHelper::map(Applications::find()->all(), 'ID', 'Name'),
            'pluginOptions' => [
                // 'allowClear' => true,
            ],
            'options' => ['placeholder' => Yii::t('app', 'Choose') . ' ' . Yii::t('app', 'Application')]
        ])->label("Aplikasi");
        
        ActiveForm::end();
        ?>
    </div>
</div>
