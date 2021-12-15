<?php


use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use unclead\widgets\MultipleInput;
use unclead\widgets\TabularInput;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use common\models\Formats;
use kartik\grid\GridView;

/**
 * @var yii\web\View $this
 * @var common\models\Fields $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="fields-form">


    <div class="page-header">
        <h3><span class="glyphicon glyphicon-edit"></span> <?= Yii::t('app','Format penamaan dan aturan tag')  ?></h3>
    </div>

<!--     <legend style="background-color: #f2f0f0">
        <div class="row">
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 18px">Format penamaan dan aturan tag</span>
        </div>
    </legend> -->
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

    'Format_id'=>[
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\Select2', 
            'options'=>[
                'data'=>ArrayHelper::map(Formats::find()->all(),'ID','Name'),
                'options'=> ['placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'Format')], 
                'pluginOptions' => [
                    // 'allowClear' => true,
                    'width'=> '200px',
                ],

            ],
        ],

'Tag'=>['type'=> Form::INPUT_TEXT, 'options'=>['style'=>'width:50%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Tag').'...', 'maxlength'=>3]], 

'Name'=>['type'=> Form::INPUT_TEXT, 'options'=>['style'=>'width:50%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nama').'...', 'maxlength'=>100]], 

//'Format_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Format ID').'...']], 

//'Group_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Group ID').'...']], 

'Fixed'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Fixed').'...']], 

'Enabled'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Enabled').'...']], 

'Length'=>['type'=> Form::INPUT_TEXT, 'options'=>['style'=>'width:150px','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Length').'...']], 

'Repeatable'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Repeatable').'...']], 

'Mandatory'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Mandatory').'...']], 

'IsCustomable'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Is Customable').'...']], 

//'IsDelete'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Is Delete').'...']], 

//'ISSUBSERIAL'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Issubserial').'...']], 

//'DEFAULTSUBTAG'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Defaultsubtag').'...', 'maxlength'=>12]], 

    ]


    ]);
    ?>
    
       <div class="form-horizontal"> <!-- hidden="hidden" -->
                <table class="table" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <td class="col-sm-2"><label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?= Yii::t('app','Sub Ruas') ?></label></td>
                            <td>
                                <div class="col-sm-12" style="padding: 0;">
      <?php      echo TabularInput::widget([
    'models' => $newSubruas,
        'allowEmptyList'    => false,
        'limit' => 30,
        
    
    'columns' => [
        [
            'name'  => 'Code',
            'type'  => 'textInput',
            'title' => yii::t('app','Kode'),
            
            
        ],
        [
            'name'  => 'Name',
            'type'  => 'textInput',
            'title' => yii::t('app','Nama'),
            
        ],
        [
            'name'  => 'Delimiter',
            'type'  => 'textInput',
            'title' => yii::t('app','Tanda Baca'),
            
        ],
                [
            'name'  => 'SortNo',
            'type'  => 'textInput',
            'title' => yii::t('app','Nomor Urut'),
            
        ],
        [
            'name'  => 'IsShow',
            'type'  => 'checkBox',
            'title' => 'Show',
            
        ],
                [
            'name'  => 'Repeatable',
            'type'  => 'checkBox',
            'title' => 'Repeatable',
            
        ],

        
    ],
]); ?>
           
                                </div>
                                                                <div class="padding0 col-sm-9"><b class="hint-uang"></b></div>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
    
    
    
       <div class="form-horizontal"> <!-- hidden="hidden" -->
                <table class="table" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <td class="col-sm-2"><label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?= Yii::t('app','Indikator') ?> 1</label></td>
                            <td>
                                <div class="col-sm-12" style="padding: 0;">
<?php    echo TabularInput::widget([
    'models' => $newIndikator1,
        'allowEmptyList'    => false,
        'limit' => 30,
        
    
    'columns' => [
        [
            'name'  => 'Code',
            'type'  => 'textInput',
            'title' => yii::t('app','Kode'),
            
            
        ],
        [
            'name'  => 'Name',
            'type'  => 'textInput',
            'title' => yii::t('app','Nama'),
            
        ],
        

        
    ],
]) ; 
?>
           
                                </div>
                                                                <div class="padding0 col-sm-9"><b class="hint-uang"></b></div>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
    
    <div class="form-horizontal"> <!-- hidden="hidden" -->
                <table class="table" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <td class="col-sm-2"><label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?= Yii::t('app','Indikator') ?> 2</label></td>
                            <td>
                                <div class="col-sm-12" style="padding: 0;">
<?php            echo TabularInput::widget([
    'models' => $newIndikator2,
        'allowEmptyList'    => false,
        'limit' => 30,
        
    
    'columns' => [
        [
            'name'  => 'Code',
            'type'  => 'textInput',
            'title' => yii::t('app','Kode'),
            
            
        ],
        [
            'name'  => 'Name',
            'type'  => 'textInput',
            'title' => yii::t('app','Nama'),
            
        ],
        

        
    ],
]) ;

?>
           
                                </div>
                                                                <div class="padding0 col-sm-9"><b class="hint-uang"></b></div>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>


    
    <?php
      echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;'. Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

    echo  '&nbsp;' . Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-warning']) . '</p>';
    
    ActiveForm::end();
    ?>
    
   

</div>
