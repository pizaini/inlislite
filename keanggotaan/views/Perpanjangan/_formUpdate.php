<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\jui\AutoComplete;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use common\widgets\MaskedDatePicker;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use common\models\JenisAnggota;
/**
 * @var yii\web\View $this
 * @var common\models\MemberPerpanjangan $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="member-perpanjangan-form">
    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,]); ?>

    <div class="page-header">
        <!-- Button -->
        <?php
        echo Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-primary']);
        echo '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']);
        ?>
        <!-- ./Button -->
    </div>

<?= $form->field($modelMember, 'Fullname')->textInput([
    'placeholder' => $model->getAttributeLabel('Fullname'),
    'readonly'=>true,
    'style' => 'font-weight:bold;width:250px;',
]);
?>

   <?=
    $form->field($modelMember, 'jenisAnggota')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(JenisAnggota::find()->all(), 'id', 'jenisanggota'),
        'size' => 'sm',
        'options' => [

            'placeholder' => Yii::t('app', 'Choose') . ' ' . Yii::t('app', 'Type Identity'),
            'onchange'=>
                '$.getJSON("change-anggota",{ id : $("#members-jenisanggota").val() },function(data){

                    $("#memberperpanjangan-biaya").val(data.Biaya);
                    $("#memberperpanjangan-tanggal").val(data.Expired);                    


                })'
        ],
        'pluginOptions' => [

            'allowClear' => true,
        'width'=> '300px',
        ],
            ]
    )->label(Yii::t('app', 'Jenis Anggota'))
    ?>

<?php
echo $form->field($model, 'Biaya')->textInput([
    'placeholder' => $model->getAttributeLabel('Biaya'),
    //'readonly'=>true,
    //'value'=>$memberNo,
    'style' => 'font-weight:bold;width:250px;',
    'type' => 'number',
    'maxlength' => 10
]);


echo $form->field($model, 'Tanggal')->widget(MaskedDatePicker::classname(), [
    'enableMaskedInput' => true,
    'maskedInputOptions' => [
        'mask' => '99-99-9999',
        'pluginEvents' => [
            'complete' => "function(){console.log('complete');}"
        ]
    ],
    'removeButton' => false,
    'options' => [
        'style' => 'width:170px',
    ],
    'pluginOptions' => [
        'autoclose' => true,
        'todayHighlight' => true,
        'format' => 'dd-mm-yyyy',
    ]
])->label(Yii::t('app', 'Tanggal Berakhir'));
?>



        
        <div class="form-group field-members-rw">
            <label class="control-label col-md-2" for="members-rw">Status Pelunasan</label>
            <div class="col-md-9">
                <label><?=Html::activeCheckbox($model,'IsLunas')?></label>
            </div>
            <div class="col-md-offset-3 col-md-9"></div>

        </div>
            

        

<?php
echo $form->field($model, 'Keterangan', [
])->textArea([
    'placeholder' => Yii::t('app', 'Keterangan'),
    'style' => 'width:350px;',
    'maxlength' => 255,
]);




ActiveForm::end();
?>

</div>


    <?php
    $this->registerJs("
	 $(document).ready(function(){

     $('#members-jenisanggota').focusout(function(){
             var NoAnggota = $('#memberperpanjangan-member_id').val();
             var res = NoAnggota.split('-');
             $.getJSON('check-membership',{ memberNo : res[0] },function(data){

                    $('#MasaBerlaku').show();
                    $('#IsiMasaBerlaku').html(data.EndDate);
                    $('#memberperpanjangan-biaya').val(data.Biaya);
                    $('#memberperpanjangan-tanggal').val(data.Expired);

    

                    //alert(data.jenisAnggota);
                
            }).error(function(jqXHR) {
                if (jqXHR.status == 404) {
                    $('#MasaBerlaku').hide();
                    $('#memberperpanjangan-member_id').val('');
                    $('#memberperpanjangan-member_id').focus();
                    //alert(\"No.Anggota tidak ditemukan.\");
                } else {
                    alert(\"Other non-handled error type\");
                }
            });
        });


	});

");
    ?>

    <?php
echo \common\widgets\Histori::widget([
    'model' => $model,
    'id' => 'memebr_perpanjangan',
    'urlHistori' => 'detail-histori?id=' . $model->ID
]);
?>
