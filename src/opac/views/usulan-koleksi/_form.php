<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;


use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;

use common\models\Worksheets;
/**
 * @var yii\web\View $this
 * @var common\models\Requestcatalog $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="requestcatalog-form">
<?php 
//print_r(Worksheets::find()->all());\
$anggotatxt=['type'=> Form::INPUT_TEXT, 'options'=>[
    'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Member ID').'...', 
    'maxlength'=>50,
    'readonly'=>(!Yii::$app->user->identity->NoAnggota ? false : true),
    'value'=>Yii::$app->user->identity->NoAnggota,
    ]
];
?>
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'noAnggota'=>$anggotatxt, 

'WorksheetID'=>[
    'type'=> Form::INPUT_WIDGET, 
    'widgetClass'=>'\kartik\widgets\Select2',
    'options'=>[
        'data'=>ArrayHelper::map(Worksheets::find()->orderBy('NoUrut')->all(),'ID','Name'),
    ], 
    
], 

'Title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Title').'...', 'maxlength'=>255]], 



'Author'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Author').'...', 'maxlength'=>255]], 

'Publisher'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Publisher').'...', 'maxlength'=>50]], 

'PublishLocation'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Publish Location').'...', 'maxlength'=>255]], 

'PublishYear'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Publish Year').'...', 'maxlength'=>50]], 

'Comments'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Comments').'...','rows'=> 6]], 

    ]


    ]);
    echo "<center>".Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
