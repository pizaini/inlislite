<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use unclead\widgets\TabularInput;
use unclead\widgets\MultipleInput;

use common\models\Refferences;

use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var common\models\Refferences $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="refferences-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]]); 

    echo '<div class="page-header">'. Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) . '</div>';

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

        'Name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nama').'...', 'maxlength'=>255]], 

        //'Format_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Format').'...']], 

        //'IsDelete'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Is Delete').'...']], 

        ]


        ]);

        ?> 
        <div class="form-horizontal"> <!-- hidden="hidden" -->
            <table class="table" style="table-layout: fixed;">
                <thead>
                    <tr>
                        <td class="col-sm-3" style="text-align: right;">
                            <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?= Yii::t('app','Item Referensi') ?>
                            </label>
                        </td>
                        <td>
                            <div class="col-sm-12" style="padding: 0;">
                             <?php echo TabularInput::widget([
                                'models' => $model2,


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

            if ($model->isNewRecord) 
            {

                echo $form->field($model3, 'copyReff')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Refferences::find()->all(),'ID','Name'),
                    'options' => ['placeholder' => yii::t('app','Salin dari Jenis Bahan')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'addon' => [

                        'append' => [
                            'content' => Html::button(yii::t('app','Proses'), [
                                'class' => 'btn btn-primary prosesCopyRefference', 
                                'title' => yii::t('app','Proses'),
                                //'data-toggle' => 'tooltip'
                            ]),
                            'asButton' => true
                        ]
                    ],
                    ])->label('Copy dari Referensi');
            } 
        

            ActiveForm::end(); ?>

        </div>


<?php
    $this->registerJs("
    $('.prosesCopyRefference').click(function(){
        //alert('halo');
        var CopyWorkID = $('#dynamicmodel-copyreff').val();
        window.location = 'create?copy=' + CopyWorkID;
    });
");


?>
