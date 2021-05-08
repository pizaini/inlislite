<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\DatePicker;


/**
 * @var yii\web\View $this
 * @var common\models\Holidays $model
 * @var yii\widgets\ActiveForm $form
 */
$this->title = Yii::t('app', 'Tambah').' '.Yii::t('app', 'Hari Libur Panjang');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Holidays'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="holidays-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
    
    echo "<div class='page-header'>";
    echo '<p>'. Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) . '</p>';
    echo "</div>";


    echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

        // 'Dates'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE],'label'=> Yii::t('app','Tanggal')], 

        'Dates'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DatePicker::classname(),'options'=>['pluginOptions' => [
                                        'format' => 'dd-mm-yyyy',
                                        'autoclose' => true,
                                        'todayHighlight' => true,
                                        // 'value' => date("dd-mm-yyy", strtotime($model->Dates))
                                    ]],'label'=>Yii::t('app','Tanggal Awal') ], 

        'CreateDate'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DatePicker::classname(),'options'=>['pluginOptions' => [
                                        'format' => 'dd-mm-yyyy',
                                        'autoclose' => true,
                                        'todayHighlight' => true,
                                        // 'value' => date("dd-mm-yyy", strtotime($model->Dates))
                                    ]],'label'=>Yii::t('app','Tanggal Akhir') ], 

        // 'Dates'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_TIME]],

        'Names'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Names').'...', 'maxlength'=>255],'label'=> Yii::t('app','Name')], 

        ]


        ]);

        ActiveForm::end(); ?>

</div>
