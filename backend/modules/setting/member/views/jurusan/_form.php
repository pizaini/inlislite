<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

// MODEL
use common\models\MasterFakultas;


/**
 * @var yii\web\View $this
 * @var common\models\MasterJurusan $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="master-jurusan-form">

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

        'id_fakultas'=>[
        'type'=>Form::INPUT_WIDGET,
        'widgetClass'=>'\kartik\widgets\Select2',
        'options'=>['data'=>ArrayHelper::map(MasterFakultas::find()->all(),'id','Nama'),
                        //'class'=>'col-md-6'
        ],
        'hint'=>'Pilih Fakultas',
        'label' => yii::t('app','Fakultas'),
        ],
        'Nama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nama').'...', 'maxlength'=>255]],

        ]


        ]);

        ActiveForm::end(); ?>

</div>
