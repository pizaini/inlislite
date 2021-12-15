<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\SurveyPilihan $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="survey-pilihan-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);

    echo '<div class="page-header">';
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo '&nbsp;'.Html::a('Kembali', Yii::$app->request->referrer,['class' => 'btn btn-warning' ]);
    echo '</div>';

    echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

//'Survey_Pertanyaan_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Survey  Pertanyaan ID').'...']],

// 'ChoosenCount'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Choosen Count').'...']],

'Pilihan'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Pilihan').'...','rows'=> 6]],

    ]


    ]);
    // echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    // echo Html::a('Kembali', Yii::$app->request->referrer,['class' => 'btn btn-warning pull-right','data-pjax'=>'0', ]);
    ActiveForm::end(); ?>

</div>
