<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

use kartik\widgets\Select2;
/**
 * @var yii\web\View $this
 * @var common\models\MasterRangeUmur $model
 * @var yii\widgets\ActiveForm $form
 */
$option = array();
for ($i=1; $i <= 100 ; $i++) { 
    $option[$i] = $i;
}

?>

<div class="master-range-umur-form">

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

            'Keterangan'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Keterangan').'...','rows'=> 2],'label'=>Yii::t('app','Keterangan Umur')],

            'umur1'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Umur Batas Bawah').'...'],'label'=>Yii::t('app','Umur Batas Bawah')],

            'umur2'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Umur Batas Atas').'...'],'label'=>Yii::t('app','Umur Batas Atas')],

            //'NoUrut'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nomor Urut').'...']],

            'NoUrut'=>['type'=> Form::INPUT_WIDGET,'label'=> Yii::t('app', 'Nomor Urut'),'widgetClass' => Select2::classname(), 'options'=>['data' => $option]], 



        ]


        ]);




    ActiveForm::end(); ?>

</div>
