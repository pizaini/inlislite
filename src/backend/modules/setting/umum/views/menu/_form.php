<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use common\models\Applications;
use common\models\Modules;

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

        echo '<div class="page-header">'.
        Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']). ' '.
        Html::a(Yii::t('app', 'Back') , 'index',['class' =>  'btn btn-warning' ]).
        '</div>';

        echo $form->field($model, 'Application_id')->widget('\kartik\widgets\Select2', [
            'data' => ArrayHelper::map(Applications::find()->all(), 'ID', 'Name'),
            'pluginOptions' => [
                // 'allowClear' => true,
            ],
            'options' => ['placeholder' => Yii::t('app', 'Choose') . ' ' . Yii::t('app', 'Application')]
        ])->label("Aplikasi");
        echo Form::widget([

            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'Name' => ['type' => Form::INPUT_TEXT, 'options' => ['style' => 'width:300px', 'placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Name') . '...', 'maxlength' => 128]],
                'URL' => ['type' => Form::INPUT_TEXT, 'options' => ['style' => 'width:300px', 'placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'URL') . '...', 'maxlength' => 11],'label'=>'URL'],
                'ClassName' => ['type' => Form::INPUT_TEXT, 'options' => ['style' => 'width:300px', 'placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Class') . '...', 'maxlength' => 256]],
                'IsPublish' => ['type' => Form::INPUT_CHECKBOX, 'options' => ['style' => '', 'placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Is Active') . '...',],'label'=>Yii::t('app','Tampilkan di menu')],
                'IsHeader' => ['type' => Form::INPUT_CHECKBOX, 'options' => ['style' => '', 'placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Is Active') . '...',],'label'=>Yii::t('app','Header')],
               'SortNo' => ['type' => Form::INPUT_TEXT, 'options' => ['style' => 'width:300px', 'placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'No Urut') . '...', 'maxlength' => 11]],
                ]
        ]);

        echo $form->field($model, 'ParentID')->widget('\kartik\widgets\Select2', [
            'data' => ArrayHelper::map(Modules::find()->all(), 'ID', 'Name'),
            'pluginOptions' => [
                'allowClear' => true,
            ],
            'options' => ['placeholder' => Yii::t('app', 'Choose') . ' ' . Yii::t('app', 'ParentID')]
        ])->label("Menu Induk");        

      
        ActiveForm::end();
        ?>
    </div>
</div>
