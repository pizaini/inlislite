<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\Collectionsources $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="collectionsources-form">

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

    //'IsDelete'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Is Delete').'...']], 

    ]


    ]);
    
    ActiveForm::end(); ?>

</div>
