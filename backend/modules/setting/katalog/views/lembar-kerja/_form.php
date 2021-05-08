<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;
use unclead\widgets\TabularInput;
use unclead\widgets\MultipleInput;


use common\models\Formats;
use common\models\Fields;
use common\models\Worksheets;


/**
 * @var yii\web\View $this
 * @var common\models\Worksheets $model
 * @var yii\widgets\ActiveForm $form
 */

// $test = ArrayHelper::map(Fields::find()->all(),'ID','Tag');
$test = ArrayHelper::map(Fields::find()->all(),'ID',function($model) {return $model['Tag'].' - '.$model['Name'];});
?>

<div class="worksheets-form">
    <div class="col-sm-12">
        <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]]); 

        echo '<div class="page-header">'. Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
        echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) . '</div>';

        ?>

  

    <div class="col-sm-7">
        <?php

        if ($model->isNewRecord) 
        {

            echo $form->field($model3, 'copyWorksheet')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Worksheets::find()->all(),'ID','Name'),
                'options' => ['placeholder' => 'Copy dari Jenis Bahan'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'addon' => [

                    'append' => [
                        'content' => Html::button('Proses', [
                            'class' => 'btn btn-primary prosesCopyWorksheet', 
                            'title' => 'Proses', 
                            //'data-toggle' => 'tooltip'
                        ]),
                        'asButton' => true
                    ]
                ],
                ])->label('Copy dari Jenis Bahan');
        } 
        

        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [

            'Format_id'=>[
                'type'=>Form::INPUT_WIDGET, 
                'widgetClass'=>'\kartik\widgets\Select2', 
                'options'=>[
                    'data'=>ArrayHelper::map(Formats::find()->all(),'ID','Name'),
                        //'options'=> ['placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'Format')], 
                        'pluginOptions' => [
                            'allowClear' => true,
                            'width'=> '200px',
                            'disabled' => true,
                        ],

                ],
            ],

//'Format_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Format').'...']], 

            'CODE'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Kode').'...', 'maxlength'=>10]], 

            'Name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nama').'...', 'maxlength'=>100]], 
            'Keterangan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Keterangan').'...', 'maxlength'=>255]], 

            'NoUrut'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'No Urut').'...']], 

            'ISSERIAL'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['id'=> 'iserial', 'onclick' => 'getcheckbox()', 'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Isserial').'...']],
            'ISKARTOGRAFI'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Iskartografi').'...'], 'label'=>'Kartografi'],  
            'ISMUSIK'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Ismusik').'...'],'label'=>'Musik'], 
            'IsBerisiArtikel'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['id'=> 'artikelbebas', 'onclick' => 'getcheckbox()', 'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Berisi Artikel').'...'],'label'=>'Berisi Artikel'], 

//'CardFormat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Card Format').'...', 'maxlength'=>100]], 

//'DEPOSITFORMAT_CODE'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Depositformat  Code').'...', 'maxlength'=>5]], 

            ]


            ]);

            ?> 
        </div>
            <div class="form-horizontal col-sm-12"> <!-- hidden="hidden" -->
                <table class="table" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <td class="col-sm-1">
                                <label class="control-label">
                                &nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?= Yii::t('app','Tag') ?>
                                </label>
                            </td>
                            <td  class="col-sm-5 pass">
                                 <?php   echo TabularInput::widget([
                                    'id' => 'tabular1',
                                    'models' => $model2,


                                    'allowEmptyList'    => false,
                                    'limit' => 80,


                                    'columns' => [
                                    [
                                        'name'  => 'Field_id',
                                        // 'type'  => 'dropDownList',
                                        'type'  => \kartik\widgets\Select2::className(),
                                        'title' => 'Tag',
                                        'options' => [
                                            'data' =>$test,
                                            'pluginOptions' => [
                                                // 'todayHighlight' => true
                                            ]
                                        ],
                                        // 'items' => ArrayHelper::map(Fields::find()->all(),'ID','Name')  
                                    ],
                                    [
                                        'name'  => 'comment',
                                        'type'  => 'static',
                                        'value' => function($model2) {
                                            $field=Fields::findOne($model2->Field_id);
                                            if ($field->Tag === '006' || $field->Tag === '007' || $field->Tag === '008')
                                            //return Html::tag('span', 'ini setting tag '.$field->Tag, ['class' => 'label label-info']);
                                            return Html::a(Yii::t('app', 'Setting'), 'javascript:void(0)', ['id'=>'AddWorksheetfieldItems','onclick'=>'js:AddWorksheetfieldItems('.$model2->ID.');','class' => 'btn bg-maroon btn-sm']);
                                        },
                                        'headerOptions' => [
                                            'style' => 'width: 70px;',
                                        ]
                                    ]

                                    ],
                                    ]) ;
                                    ?>      
                            </td>
                            <td class="col-sm-1" id="tampilkan_form"></td>
                        </tr>
                    </thead>
                </table>
            </div>

                <?php





            ActiveForm::end(); ?>

    </div>
</div>
<?php
Modal::begin(['id' => 'WorksheetfieldItems-modal','size' => 'modal-lg','options'=>[
  //'style'=>['z-index'=>9999],
  'data-backdrop'=>'static',
  
]]);
echo "<div id='modalWorksheetfieldItems'></div>";
Modal::end();
?>
<input type="hidden" id="hdnID" value="<?=Yii::$app->urlManager->createUrl(["setting/katalog/lembar-kerja/worksheetfield-items"])?>">
<?php
    $this->registerJs("
    $('.prosesCopyWorksheet').click(function(){
        //alert('halo');s
        var CopyWorkID = $('#dynamicmodel-copyworksheet').val();
        window.location = 'create?copy=' + CopyWorkID;
    });
");

?>
<script type="text/javascript">
   function AddWorksheetfieldItems(id){
        isLoading = false;
        if($.ajax({
            type     :"POST",
            cache    : false,
            url  : $("#hdnID").val()+"?id="+id,
            success  : function(response) {
                $("#modalWorksheetfieldItems").html(response);
            }
        }))
        {
          $("#WorksheetfieldItems-modal").modal("show");
          $('#WorksheetfieldItems-modal').removeAttr('tabindex');
        }
    }

    getcheckbox();
    function getcheckbox(){
        var iserial = document.getElementById("iserial");
        var artikel = document.getElementById("artikelbebas");
        // alert(iserial.checked)
        if(iserial.checked == true){
            artikel.disabled = true;
            iserial.disabled = false;
        }else if(artikel.checked == true){
            iserial.disabled = true;
            artikel.disabled = false;
        }else{
            iserial.disabled = false;
            artikel.disabled = false;
        }
    }

</script>