<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\Departments $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="departments-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); 
    echo '<div class="page-header">';
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) ;
    echo '</div>';
    echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

    'Code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Kode').'...', 'maxlength'=>100]],

    'Name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nama').'...', 'maxlength'=>255],'label'=>Yii::t('app','Nama Unit Kerja')],

    'Description'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Deskripsi').'...', 'maxlength'=>255, 'colrow'=>2]],

    ]


    ]);
    ActiveForm::end(); ?>

</div>
