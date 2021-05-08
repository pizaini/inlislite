<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="settingparameters-form">
  <div class="form-group">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>
    <div class="page-header">
    	<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>


    <?= $form->field($model,'Value1')->textInput(['style'=>'width:250px'], ['inline'=>true])->label(Yii::t('app', 'Nama KABID Pengolahan'))?>

    <?= $form->field($model,'Value2')->textInput(['style'=>'width:250px'], ['inline'=>true])->label(Yii::t('app', 'NIP KABID Pengolahan')) ?>
  
    </div>


</div>
