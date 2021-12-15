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

    <?= $form->field($model,'Value1')->radioList(['TRUE'=>Yii::t('app', 'Ya'),'FALSE'=>Yii::t('app', 'Tidak')], ['inline'=>true])->label(Yii::t('app', 'Tampilkan Usulan Koleksi'))?>

    &nbsp;<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>


</div>
