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
        //jika ada biaya tidak bisa perpanjang otomatis
        if ($model->Biaya <= 0) {
            echo Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-primary']);
        } else
        {

            echo Html::Button(Yii::t('app', 'Create'), ['class' => 'btn btn-primary disabled']);
        }       
        
        echo '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['user/index'], ['class' => 'btn btn-warning']);
        ?>
        <!-- ./Button -->
    </div>

    <?php if($model->Biaya > 0) {?>
 <div class="alert alert-danger alert-dismissable" style="margin-top: 20px">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-info"></i> <?= Yii::t('app','Catatan')  ?></h4>
    <?= Yii::t('app','Anda tidak bisa melakukan perpanjangan otomatis karena biaya perpanjangan berbayar')  ?>
</div>
    <br>
    <br>
    <?php
    }
    ?>
    <div class="form-group">
        <label class="control-label col-md-2" for="jenis-anggota">Nomor Anggota </label>
        <div class="col-md-10">
            <input type="text" readonly="readonly" value="<?= $modelMember['MemberNo'] ?>" class="form-control" style="font-weight:bold;width:250px;">
        </div>
    </div>
    
<?php
    // echo'<pre>';print_r($modelMember['MemberNo']);
//     echo $form->field($modelMember, 'MemberNo')->textInput([
//     'readonly'=>true,
//     'value'=>$modelMember['MemberNo'],
//     'style' => 'font-weight:bold;width:250px;',
//     'type' => 'number',
//     'maxlength' => 10
// ])->label(Yii::t('app', 'Nomor Anggota'));

    echo $form->field($modelMember, 'Fullname')->textInput([
    'readonly'=>true,
    //'value'=>$memberNo,
    'style' => 'font-weight:bold;width:250px;',
    'maxlength' => 10
])->label(Yii::t('app', 'Nama Anggota'));

?>
 <div class="form-group field-jenis-anggota" id="JenisAnggota" >
    <label class="control-label col-md-2" for="jenis-anggota">Jenis Anggota </label>
    <div class="col-md-10">
    <?= Select2::widget([
        'model' => $modelMember,
        'attribute' => 'jenisAnggota',
        'data' => ArrayHelper::map(JenisAnggota::find()->all(), 'id', 'jenisanggota'),
            'size' => 'sm',
            'disabled' => true,            
            'options' => [

                'placeholder' => Yii::t('app', 'Choose') . ' ' . Yii::t('app', 'Type Identity'),
            ],
            'pluginOptions' => [

                'allowClear' => true,
                'width'=> '250px',
            ],
                ]
        );
    ?>

        </div>
</div>
 
<?php
echo $form->field($modelMember, 'EndDate')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => 'Enter birth date ...'],
    'pluginOptions' => [
        'autoclose'=>true,
        'format' => 'dd-mm-yyyy',
    ],
    'disabled' => true,
    'options' => [
        'style' => 'width:170px',
    ],

])->label(Yii::t('app', 'Masa Berlaku'));



echo $form->field($model, 'Tanggal')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => 'Enter birth date ...'],
    'pluginOptions' => [
        'autoclose'=>true,
        'format' => 'dd-mm-yyyy',
    ],
    'disabled' => true,
    'options' => [
        'style' => 'width:170px',
    ],

])->label(Yii::t('app', 'Tanggal Berakhir'));

echo Html::hiddenInput('Tanggal', $model->Tanggal);
echo Html::hiddenInput('Tanggal', $model->Tanggal);
echo Html::hiddenInput('EndDate', $modelMember->EndDate);

echo $form->field($model, 'Biaya')->textInput([
    'placeholder' => $model->getAttributeLabel('Biaya'),
    'readonly'=>true,
    //'value'=>$memberNo,
    'style' => 'font-weight:bold;width:250px;',
    'type' => 'number',
    'maxlength' => 10
]);


?>
    
<?=$form->field($model, 'Keterangan', [
])->textArea([
    'placeholder' => Yii::t('app', 'Keterangan'),
    'style' => 'width:350px;',
    'maxlength' => 255,
    'readonly' => true
]);




ActiveForm::end();
?>

</div>


