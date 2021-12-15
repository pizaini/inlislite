<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\MasterJenisIdentitas $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="master-jenis-identitas-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);

    echo '<div class="page-header">';
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) ;
    echo '</div>';

    echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

        'Nama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nama').'...', 'maxlength'=>255]], 
        'IsNIK'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>[array('label'=>'asd')],'label'=>yii::t('app','Terhubung dengan data kependudukan')], 

        ]


        ]);

    ActiveForm::end(); ?>

    </div>
