<?php

use yii\helpers\Html;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\Library $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="library-form">
<div class="col-xs-6 col-sm-6">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]]); 

    echo '<div class="page-header">'. Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) . '</div>';

    echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

        'Name'=>['type'=> Form::INPUT_TEXT, 'options'=>['style'=>'','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Name').'...', 'maxlength'=>100]], 
       // 'Tag'=>['type'=> Form::INPUT_TEXT, 'options'=>['style'=>'','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Tag').'...', 'maxlength'=>3]], 
        'JumlahKarakter'=>['type'=> Form::INPUT_TEXT, 'options'=>['style'=>'','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'JumlahKarakter').'...', 'maxlength'=>11]],     
        
        ]


        ]);


    ActiveForm::end(); ?>
</div>
</div>
