<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\MasterPelanggaranLocker $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="master-pelanggaran-locker-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); 
    
    echo '<div class="page-header">'.
    Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']). ' '.
    Html::a(Yii::t('app', 'Back') , 'index',['class' =>  'btn btn-warning' ]).
    '</div>';

    echo Form::widget([
    	'model' => $model,
    	'form' => $form,
    	'columns' => 1,
    	'attributes' => [

    	'jenis_pelanggaran'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Jenis Pelanggaran').'...', 'maxlength'=>50]], 

    	'denda'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Denda').'...', 'maxlength'=>10], 'label'=>yii::t('app','Denda')], 

    	'deskripsi'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Deskripsi').'...','rows'=> 6]], 

    	]


    ]);
    // echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
