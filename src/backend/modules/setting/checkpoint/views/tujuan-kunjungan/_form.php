<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\TujuanKunjungan $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="tujuan-kunjungan-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); 
    echo '<div class="page-header">';
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo '&nbsp;'.Html::a('Kembali', 'index',['class' => 'btn btn-warning' ]);
    echo '</div>';

    echo Form::widget([
	    'model' => $model,
	    'form' => $form,
	    'columns' => 1,
	    'attributes' => [

			'Code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Kode').'...', 'maxlength'=>100]], 

			'TujuanKunjungan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Tujuan Kunjungan').'...', 'maxlength'=>255]], 

			// 'Member'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Keanggotaan').'...']], 

			'Member'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>[array('label'=>'asd')],'label'=>Yii::t('app','Aktifkan Untuk Member')], 

			// 'NonMember'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Non Member').'...']], 
			'NonMember'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>[array('label'=>'asd')],'label'=>Yii::t('app','Aktifkan Untuk Non Member')], 

			// 'Rombongan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Rombongan').'...']], 
			'Rombongan'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>[array('label'=>'asd')],'label'=>Yii::t('app','Aktifkan Untuk Rombongan')], 

		]


	    ]);
    ActiveForm::end(); ?>

</div>
