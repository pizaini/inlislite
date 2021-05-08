<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;

/**
 * @var yii\web\View $this
 * @var common\models\MasaBerlakuAnggota $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="masa-berlaku-anggota-form">

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

        'jumlah'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Jumlah').'...']], 

        //'satuan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Satuan').'...', 'maxlength'=>50]], 

        ]


        ]);
    // echo $form->field($model, 'satuan')->dropDownList(["Hari" => "Hari", "Minggu" => "Minggu", "Bulan" => "Bulan", "Tahun" => "Tahun"]);
    echo $form->field($model, 'satuan')->widget(Select2::classname(), [
        'data' => ["Hari" => "Hari", "Minggu" => "Minggu", "Bulan" => "Bulan", "Tahun" => "Tahun"],
        // 'options' => ['placeholder' => 'Select a state ...'],
        'pluginOptions' => [
        // 'allowClear' => true
        ],
    ]);

    ActiveForm::end(); ?>

</div>
