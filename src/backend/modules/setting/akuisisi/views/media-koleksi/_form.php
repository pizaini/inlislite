<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use common\models\Worksheets;

/**
 * @var yii\web\View $this
 * @var common\models\Collectionmedias $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="collectionmedias-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
        echo '<div class="page-header">'. Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

    echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) . '</div>';
    echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

        'Code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Kode').'...', 'maxlength'=>255]], 

        'Name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nama').'...', 'maxlength'=>255]], 

        'Worksheet_id'=>[
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\Select2', 
            'options'=>[
            	'data'=>ArrayHelper::map(Worksheets::find()->all(),'ID','Name'),
            	'options'=> ['placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'Worksheet')]
            ],
        ],

//'IsDelete'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Is Delete').'...']], 

    ]


    ]);

    ActiveForm::end();  ?>

</div>
