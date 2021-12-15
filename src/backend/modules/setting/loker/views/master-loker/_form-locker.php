<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

use yii\helpers\ArrayHelper;

//Models
use common\models\Locations;
use common\models\LocationLibrary;
/**
 * @var yii\web\View $this
 * @var common\models\MasterLoker $model
 * @var yii\widgets\ActiveForm $form
 */



if (!$model->isNewRecord) {
    $Locations = Locations::find()->where('ID ='.$model->locations_id)->asArray()->one();
    $idLoc = $Locations['LocationLibrary_id'];
}
?>

<div class="master-loker-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); 

    // echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    
    echo '<div class="page-header">';
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo  '&nbsp;' . Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-warning']) . '</div>';


    echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

    'No'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'No Loker').'...', 'maxlength'=>255],'label'=>Yii::t('app','Nomor Loker')], 

    'Name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nama').'...', 'maxlength'=>255],'label'=>Yii::t('app','Keterangan / Nama Loker')], 
    
	]
    ]);
?>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group field-masterloker-locations_id required">
                <label class="control-label col-md-2" for="masterloker-locations_id"><?= Yii::t('app','Lokasi Perpustakaan');  ?></label>
                <div class="col-md-10">
                    <?php if ($model->isNewRecord): ?>
                        <?= Select2::widget([
                            'name' => 'locations-Library',
                            'data' => ArrayHelper::map(LocationLibrary::find()->all(),'ID','Name'),
                            'options' => ['id' => 'locations-Library', 'required' => 'true', 'placeholder' => Yii::t('app','-- Silahkan pilih lokasi perpustakaan --')]
                        ]); ?>
                        
                    <?php else: ?>
                        <?= Select2::widget([
                            'name' => 'locations-Library',
                            'data' => ArrayHelper::map(LocationLibrary::find()->all(),'ID','Name'),
                            'value' => $idLoc,
                            'options' => ['id' => 'locations-Library', 'required' => 'true', 'placeholder' => Yii::t('app','-- Silahkan pilih lokasi perpustakaan --')]
                        ]); ?>
                    <?php endif ?>

                </div>
                <div class="col-md-offset-2 col-md-10"></div>
                <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
            </div>
        </div>
    </div>

    <div class="row" id="selecter-locations"> <!-- Akan diisi selecter dari response ajax -->
        <?php if (!$model->isNewRecord) //  <!-- Jika Dalam Menu Edit -->
        {
            echo '        
            <div class="col-sm-12">
                <div class="form-group field-masterloker-locations_id required">
                    <label class="control-label col-md-2" for="masterloker-locations_id">'. Yii::t('app','Lokasi Ruangan').'</label>
                    <div class="col-md-10">
                        '. Html::activeDropDownList($model, 'locations_id',
                            ArrayHelper::map(Locations::find()->where('LocationLibrary_id = '.$idLoc)->select(['Name', 'ID'])->orderBy('ID')->all(), 'ID', 'Name'),
                            ['prompt' => "-- Silahkan pilih lokasi --", 'class'=>'form-control']).'
                    </div>
                    <div class="col-md-offset-2 col-md-10"></div>
                    <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
                </div>
            </div>';
        } 
            
        ?>
    </div>


<?php



    echo $form->field($model, 'status')->widget(Select2::classname(), [
        'data' => ['ready' => 'Ready','Out of Order' => 'Out of Order','used' => 'Used'],
        'pluginOptions' => [
                // 'allowClear' => true
        ],
    ]);


    ActiveForm::end(); ?>

</div>


<?php
$script = <<< JS

    $.fn.select2.defaults.set('theme', 'krajee');


    $('#locations-Library').change(function(){
        var idLoc = $(this).val();
        // swal(idLoc);
        $.get('load-selecter-locations',{idLoc : idLoc},function(data){
        })
        .done(function(data) {
            $( '#selecter-locations' ).html(data); 
            $('#masterloker-locations_id').select2({}); 
            // alert( "second success" );
        })
        .fail(function(data) {
            $('#locations-id').hide();
            // alert( "error" );
        });
    });


    if ($('.message').data("messageValue")) {
        swal($('.message').data("messageValue"));
    }

JS;

$this->registerJs($script);
?>