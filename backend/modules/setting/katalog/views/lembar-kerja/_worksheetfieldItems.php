

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
use yii\widgets\Pjax;
/**
 * @var yii\web\View $this
 * @var common\models\Fields $model
 * @var yii\widgets\ActiveForm $form
 */
?>



<?php 

$form = ActiveForm::begin([
'id' => 'tabular-form',
'method'=>'post',
'action' => ['save-worksheetfield-items'],
'type'=>ActiveForm::TYPE_HORIZONTAL,
'formConfig' => ['labelSpan'=>3,'deviceSize' => ActiveForm::SIZE_SMALL]
]); ?>

<div class="modal-header" >
<h4 class="modal-title">Setting Ruas Jenis Bahan : <?=$worksheetsName?> - <?=$fieldsName?></h4>
</div>
<div class="modal-body" >
  
       <div class="form-horizontal"> <!-- hidden="hidden" -->
                <table class="table" style="table-layout: fixed;">
                    <thead>
                    <tr>
                    </tr>
                        <tr>                            
                            <td>
                                <div class="col-sm-12" style="padding: 0;">
      <?php      
    echo Html::hiddenInput('WorksheetField_id', $wfid);
    echo Html::hiddenInput('Worksheet_id', $wid);
    $data = ArrayHelper::map(\common\models\Refferences::find()->all(),'ID',function($model) {return $model['Name'];});
    // array_unshift($data,"--Tidak ada--");
    $data[0] = "--Tidak ada--";
    ksort($data);

    echo TabularInput::widget([
    'addButtonPosition' => unclead\widgets\MultipleInput::POS_HEADER,
    'addButtonOptions' => [
        'class' => 'btn btn-success', 
        'label' => '<i class="glyphicon glyphicon-plus"></i> Tambah'
    ],
    'removeButtonOptions' => [
        'class' => 'btn btn-danger', 
        'label' => '<i class="glyphicon glyphicon-remove"></i> Hapus'
    ],

    'options' => [
       'id' => 'my_id',              
    ],
    //'id' => 'my_id',    
    'attributeOptions' => [
            'enableAjaxValidation'      => true,
            'enableClientValidation'    => true,
            'validateOnChange'          => false,
            'validateOnSubmit'          => true,
            'validateOnBlur'            => false,
        ],
    'models' => $model,
    //'form' => $form,
    'allowEmptyList'    => false,
    'limit' => 80,
        
    'columns' => [
        [
            'name'  => 'Name',
            'type'  => 'textInput',
            'title' => 'Nama',


            
        ],
        [
            'name'  => 'Refference_id',
            'type'  => \kartik\widgets\Select2::className(),
            'title' => 'referensi',
            'options' => [
                //'data' =>ArrayHelper::map(\common\models\Refferences::find()->all(),'ID','Name'),
                'data' => $data, 
                'pluginOptions' => [
                ]
            ], 
        ],
        [
            'name'  => 'StartPosition',
            'type'  => 'textInput',
            'title' => 'Posisi mulai',
            
        ],
        [
            'name'  => 'Length',
            'type'  => 'textInput',
            'title' => 'Panjang',
            
        ],
        [
            'name'  => 'DefaultValue',
            'type'  => 'textInput',
            'title' => 'Nilai Awal',
        ],
        [
            'name'  => 'IdemTag',
            'type'  => 'textInput',
            'title' => 'Sama Dengan Tag',
        ],
        [
            'name'  => 'IdemStartPosition',
            'type'  => 'textInput',
            'title' => 'posisi',
            
        ],    
        
    ],
]); 

?>
           
                                </div>
                                <div class="padding0 col-sm-9"><b class="hint-uang"></b></div>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>

</div>
<div class="modal-footer" >


    <?php
    echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;'. Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

    echo  '&nbsp; <button class="btn btn-warning" data-dismiss="modal">'. Yii::t('app', 'Cancel').'</button> </p>';

    ActiveForm::end();

   
