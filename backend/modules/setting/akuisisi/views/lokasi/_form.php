<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

use kartik\widgets\FileInput;

use common\models\LocationLibrary;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var common\models\Locations $model
 * @var yii\widgets\ActiveForm $form
 */
?>


<div class="locations-form">
<?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'options' => ['enctype' => 'multipart/form-data']]); 
echo "<div class='page-header'>";
echo '<p>'. Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
echo  '&nbsp;' . Html::a(Yii::t('app', 'Kembali'), ['index'], ['class' => 'btn btn-warning']) . '</p>';
echo "</div>";


// echo $form->field($model, 'LocationLibrary_id')->widget(Select2::classname(), [
//     'data' => ArrayHelper::map(LocationLibrary::find()->all(),'ID','Name'),
//     'options' => ['placeholder' => 'Select a state ...'],
//     'pluginOptions' => [
//         'allowClear' => true,
//         'label'=> Yii::t('app', 'Lokasi Perpustakaan'),
//     ],
// ]);


echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

    'LocationLibrary_id'=>['type'=> Form::INPUT_WIDGET,'label'=> Yii::t('app', 'Lokasi Perpustakaan'),'widgetClass' => Select2::classname(), 'options'=>['data' => ArrayHelper::map(LocationLibrary::find()->all(),'ID','Name')]], 

    'Code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Kode').'...', 'maxlength'=>255]], 

    'Name'=>['type'=> Form::INPUT_TEXT,'label'=> Yii::t('app', 'Nama'), 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nama').'...', 'maxlength'=>255]], 

//'IsDelete'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Is Delete').'...']], 


    'Description'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Description').'...', 'maxlength'=>255]], 
    // 'ISPUSTELING'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Ispusteling').'...']], 
    // 'IsVisitsDestination'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Ispusteling').'...']], 
    // 'IsInformationSought'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Ispusteling').'...']], 
    // 'IsGenerateVisitorNumber'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Ispusteling').'...']], 
    // 'IsPrintBarcode'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Ispusteling').'...']], 
    ]


    ]);
    ?>




<div class="row">
    <label class="control-label col-md-2" style=""><u><?= yii::t('app','Buku Tamu')?></u></label>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group field-locations-description">
            <label class="control-label col-md-2" for="locations-description"><?= yii::t('app','Pilihan Pertanyaan Kunjungan')?></label>
            <div class="col-md-10">
            <?php

            if ($model->IsVisitsDestination == 1) 
            {
                $pertanyaan = 'IsVisitsDestination';
            } 
            else if($model->IsInformationSought == 1) 
            {
                $pertanyaan = 'IsInformationSought';
            }
            else
            {
                $pertanyaan = 0;
            }
            

// echo '<label class="control-label col-md-2">Pilihan Pertanyaan Kunjungan</label>';
            echo Select2::widget([
                'name' => 'pertanyaan',
                'value' => $pertanyaan,
                'data' => [0 => Yii::t('app','-- Tanpa Pilihan --'), 'IsVisitsDestination' => Yii::t('app', 'Tampilkan ruas maksud kunjungan'), 'IsInformationSought' => Yii::t('app', 'Tampilkan ruas informasi yang dicari')],
                // 'options' => ['width'=>'250px'],
                'pluginOptions' => ['class'=>'form-control'],
                ]);;
                ?>
                
            </div>
            <div class="col-md-offset-2 col-md-10"></div>
            <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
        </div>
    </div>
</div>
    <?php  
    echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [ 

       'IsGenerateVisitorNumber'=>['type'=> Form::INPUT_CHECKBOX, 'label'=> Yii::t('app', 'Tampilkan nomor pengunjung'),'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Ispusteling').'...']], 
       'IsPrintBarcode'=>['type'=> Form::INPUT_CHECKBOX, 'label'=> Yii::t('app', 'Aktifkan cetak no. pengunjung'),'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Ispusteling').'...']], 

        ]
        ]);
    ?>




            <div class="form-horizontal"> <!-- hidden="hidden" -->
                <table class="table" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <td class="col-sm-2 text-right"><label class="control-label text-right"><?= Yii::t('app','Gambar Logo Lokasi') ?></label></td>
                            <td>
                                <div class="col-sm-5" style="padding: 0;">
                                    <?php if ($model->UrlLogo !== ""): ?>
                                        <img src="<?= $model->UrlLogo; ?>" style="max-width: 250px; padding-bottom: 10px;"/>   
                                    <?php endif ?>
        
                                     <?= $form->field($model, 'Logo')->widget(FileInput::classname(), [
                                        'options' => ['accept' => 'image/*'],
                                        ])->label(false); ?>

                                </div>
                                <div class="padding0 col-sm-9"><b class="hint-uang"></b></div>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
    <?php

    
    ActiveForm::end();  ?>

</div>

<script type="text/javascript">
    
    $('#locations-isvisitsdestination').change(function(){
        if ($('#locations-isvisitsdestination').prop('checked')) 
        {
            $('#locations-isinformationsought').prop('checked', false);
        };
    });
    $('#locations-isinformationsought').change(function(){        
        if ($('#locations-isinformationsought').prop('checked')) 
        {
            $('#locations-isvisitsdestination').prop('checked', false);
        };
    });

</script>