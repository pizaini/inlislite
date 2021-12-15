<?php
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\JenisAnggota;

?>

<div class="form-group field-jenis-anggota" id="JenisAnggota" >
    <label class="control-label col-md-2" for="jenis-anggota">Jenis Anggota </label>
    <div class="col-md-10">
    <?= Select2::widget([
        'model' => $modelss,
        'attribute' => 'jenisAnggota',
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
        );
    ?>

        </div>
</div>

