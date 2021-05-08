<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use unclead\widgets\TabularInput;
use unclead\widgets\MultipleInput;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;

/**
 * @var yii\web\View $this
 * @var common\models\Library $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="library-form">
<div class="col-xs-6 col-sm-6">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]]); 
    echo '<div class="page-header">'. Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

    echo  '&nbsp;' . Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-warning']) . '</div>';
    echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

        'NAME'=>['type'=> Form::INPUT_TEXT, 'options'=>['style'=>'width:150px','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'lib_Name').'...', 'maxlength'=>200]], 

        'FULLNAME'=>['type'=> Form::INPUT_TEXT, 'options'=>['class'=>'col-sm-12','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'lib_Fullname').'...', 'maxlength'=>300]], 

        'URL'=>['type'=> Form::INPUT_TEXT, 'options'=>['class'=>'col-sm-12','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'lib_Url').'...', 'maxlength'=>300]], 

        'PORT'=>['type'=> Form::INPUT_TEXT, 'options'=>['style'=>'width:150px','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'lib_Port').'...', 'maxlength'=>10]], 

        'DATABASENAME'=>['type'=> Form::INPUT_TEXT, 'options'=>['class'=>'col-sm-12','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'lib_Databasename').'...', 'maxlength'=>100]], 

        // 'RECORDSYNTAX'=>['type'=> Form::INPUT_DROPDOWN_LIST,'items'=>['unknown'=>'unknown','GRS1'=>'GRS1','SUTRS'=>'SUTRS','USMARC'=>'USMARC','UKMARC'=>'UKMARC','XML'=>'XML'],'options'=>['style'=>'width:150px', 'maxlength'=>20]], 
        'RECORDSYNTAX'=>[
                'type'=>Form::INPUT_WIDGET, 
                'widgetClass'=>'kartik\select2\Select2',
                'options'=>[
                    'data' => ['unknown'=>'unknown','GRS1'=>'GRS1','SUTRS'=>'SUTRS','USMARC'=>'USMARC','UKMARC'=>'UKMARC','XML'=>'XML'],
                    'options'=>[
                        'options'=>['placeholder'=>'Sintaks']
                    ]
                ],
            ],  
            
        // 'PROTOCOL'=>['type'=> Form::INPUT_DROPDOWN_LIST,'items'=>['SRU'=>'SRU','z3950'=>'z3950'],'options'=>['style'=>'width:150px', 'maxlength'=>45]], 
        'PROTOCOL'=>[
                'type'=>Form::INPUT_WIDGET, 
                'widgetClass'=>'kartik\select2\Select2',
                'options'=>[
                    'data' => ['SRU'=>'SRU','z3950'=>'z3950'],
                    'options'=>[
                        'options'=>['placeholder'=>'Protocol']
                    ]
                ],
            ],        
    

        ]


        ]);
        ?>
           <div class="form-horizontal"> <!-- hidden="hidden" -->
                <table class="table" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <td class="control-label col-sm-3">
                                <br/>
                                <br/>
                                <label class="control-label"><?= Yii::t('app','Kriteria Pencarian') ?></label>
                            </td>
                            <td>
                                <div class="col-sm-12" style="padding: 0;">
                                    <?php    
                                    echo TabularInput::widget([
                                        'models' => $model2,
                                        'allowEmptyList'    => false,
                                        'limit' => 30,


                                        'columns' => [
                                        [
                                        'name'  => 'CRITERIANAME',
                                        'title' => Yii::t('app','Kriteria'),
                                        'type'  => 'textInput',
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
          //  die;


    /*
            
 
    
    echo $form->field($model2, 'CRITERIANAME')->widget(MultipleInput::className(), [
        'limit'             => 10,
        'allowEmptyList'    => false,
                //    'models' => $model2->CRITERIANAME,
        'enableGuessTitle'  => true,
        'min'               => 0, // should be at least 2 rows
        'addButtonPosition' => MultipleInput::POS_HEADER // show add button in the header
    ])
    ->label("Kriteria Pencarian");*/

    ActiveForm::end(); ?>
</div>
</div>
