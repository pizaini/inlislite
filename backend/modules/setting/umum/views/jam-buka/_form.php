<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\MasterJamBuka $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="master-jam-buka-form">

	    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);

	    echo "<div class='page-header'>";
	    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    	echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']);
	    echo "</div>";

	    echo Form::widget([

	    'model' => $model,
	    'form' => $form,
	    'columns' => 1,
	    'attributes' => [

	    'hari'=>['type'=> Form::INPUT_TEXT, 'options'=>['readonly' => true,'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Hari').'...', 'maxlength'=>50]],

	    'jam_buka'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_TIME]],

	    'jam_tutup'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_TIME]],


	    ]


	    ]);
	    ActiveForm::end(); ?>

</div>


