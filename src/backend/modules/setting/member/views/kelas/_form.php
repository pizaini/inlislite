<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\KelasSiswa $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="kelas-siswa-form">

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

        'namakelassiswa'=>['type'=> Form::INPUT_TEXT, 'label'=>yii::t('app','Nama Klass'),'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Namakelassiswa').'...', 'maxlength'=>50]], 

        ]


        ]);
    ActiveForm::end(); ?>

</div>
