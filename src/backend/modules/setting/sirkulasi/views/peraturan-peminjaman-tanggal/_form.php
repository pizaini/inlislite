<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\DatePicker;

use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var common\models\base\PeraturanPeminjamanTanggal $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="peraturan-peminjaman-tanggal-form">

    <?php 

    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]]); 
    
    echo "<div class='page-header'>";
    echo '<p>'. Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) . '</p>';
    echo "</div>";


    echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

        //'TanggalAwal'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]], 
        'TanggalAwal'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DatePicker::classname(),'options'=>['pluginOptions' => [
                                'format' => 'dd-mm-yyyy',
                                'autoclose' => true,
                                'todayHighlight' => true,
                            ]], ], 

        //'TanggalAkhir'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]], 
        'TanggalAkhir'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DatePicker::classname(),'options'=>['pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ]], ], 



        'MaxPinjamKoleksi'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Maks. koleksi yang dapat dipinjam').'...'],'label' => Yii::t('app','Maks. koleksi yang dapat dipinjam')], 

        'MaxLoanDays'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Maks. Lama Pinjam').'...'],'label' => Yii::t('app','Maks. Lama Pinjam')], 

        'WarningLoanDueDay'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Jeda Hari Peringatan Peminjaman utk Kembali').'...'],'label' => Yii::t('app','Jeda Hari Peringatan Peminjaman utk Kembali')], 

        'DayPerpanjang'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Maks. Lama Perpanjang').'...'],'label' => Yii::t('app','Maks. Lama Perpanjangan')], 

        'CountPerpanjang'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Maks. Banyaknya Perpanjang').'...'],'label' => Yii::t('app','Maks. Banyaknya Perpanjang'),'hint'=>Yii::t('app','(jika diisi dengan 0 maka tidak boleh diperpanjang).')], 




        // 'DendaTenorMultiply'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Denda Tenor Multiply').'...']], 

        // 'DaySuspend'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Day Suspend').'...']], 

        // 'DendaTenorJumlah'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Denda Tenor Jumlah').'...']], 

        // 'DendaPerTenor'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Denda Per Tenor').'...', 'maxlength'=>10]], 

        // 'SuspendMember'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Suspend Member').'...']], 

        // 'DendaTenorSatuan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Denda Tenor Satuan').'...', 'maxlength'=>45]], 

        ]
        ]);


        echo $form->field($model, 'DendaType')->radioList(array('Konstan'=>'Konstan','Berkelipatan'=>'Berkelipatan'), 
            [
                'item' => function($index, $label, $name, $checked, $value) {

                    $return = '<label class="radio-inline">';
                    $return .= '<input type="radio" class="DendaTypeRadio DendaTypeRadio'.$value.'" name="' . $name . '" value="' . $value . '"  '.(($checked == 1)? 'checked':'').'>';
                    $return .= ' '.ucwords($label);
                    $return .= '</label>';

                    return $return;
                }
            ]
            )->label(Yii::t('app','Denda'));

        echo $form->field($model, 'DendaPerTenor')->input('number',['min'=>0,'style' => 'width: 10%;'])->label(Yii::t('app','Jumlah Denda'));




?>

    <div class="form-group kv-fieldset-inline field-peraturanpeminjamantanggal-dendatenorjumlah-pack" >
        <?= Html::activeLabel($model, 'DendaTenorJumlah', [
            'label'=>Yii::t('app','Satuan Tenor Denda'), 
            'class'=>'col-sm-3 control-label'
        ]) ?>
        <div class="col-sm-9">
            <div class="col-sm-2" style="padding-left: 0px; margin-right: -7%;">
                <?= $form->field($model, 'DendaTenorJumlah')->input('number',['min'=>1])->label(Yii::t('app','Satuan Tenor Denda'))->label(false); ?>
            </div>
            <div class="col-sm-9">
                <?php echo "<div class='col-sm-3'>".Select2::widget([
                    'model' => $model,
                    'attribute' => 'DendaTenorSatuan',
                    'data' => ["Hari" => yii::t('app','Hari'), "Minggu" => yii::t('app','Minggu'), "Bulan" => yii::t('app','Bulan'), "Tahun" => yii::t('app','Tahun')],
                    'pluginOptions' => [
                        'allowClear' => false,
                        'class'=> 'col-sm-6'
                    ],
                    ])."</div>"; 
                    ?>
            </div>

        </div>
    </div>



<?php 

    echo $form->field($model, 'DendaTenorMultiply')->input('number',['min'=>1,'style' => 'width: 10%;'])->label(Yii::t('app','Pengali Tenor Denda'))->hint(yii::t('app','Kali'));
       //'DendaTenorMultiply' => ['type' => Form::INPUT_TEXT, 'options' => ['style'=>'width: 10%;','placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Nama') . '...', 'maxlength' => 255]],




    // Suspen atau SKors Yuhu
    echo $form->field($model, 'SuspendType')->radioList(array('Konstan'=>yii::t('app','Konstan'),'Berkelipatan'=>yii::t('app','Berkelipatan')), 
        //['inline'=>true]
        [
            'item' => function($index, $label, $name, $checked, $value) {

                $return = '<label class="radio-inline">';
                $return .= '<input type="radio" class="SuspendTypeRadio SuspendTypeRadio'.$value.'" name="' . $name . '" value="' . $value . '" '.($checked == 1? 'checked':'').'>';
                //$return .= '<i></i>';
                //$return .= '<span>' . ucwords($label) . '</span>';
                $return .= ' '.ucwords($label);
                $return .= '</label>';

                return $return;
            }
        ]
        )->label(Yii::t('app','Skorsing'));

    echo $form->field($model, 'DaySuspend')->input('number',['min'=>0,'style' => 'width: 10%;'])->label(Yii::t('app','Lama Skorsing'))->hint(yii::t('app','Hari'));
    ?>
    <div class="form-group kv-fieldset-inline field-peraturanpeminjamantanggal-suspendtenorjumlah-pack">
        <?= Html::activeLabel($model, 'SuspendTenorJumlah', [
            'label'=>Yii::t('app','Satuan Tenor Skorsing'), 
            'class'=>'col-sm-3 control-label'
        ]) ?>
        <div class="col-sm-9">
            <div class="col-sm-2" style="padding-left: 0px; margin-right: -7%;">
                <?= $form->field($model, 'SuspendTenorJumlah')->input('number',['min'=>1])->label(Yii::t('app','Satuan Tenor Denda'))->label(false); ?>
            </div>
            <div class="col-sm-9">
                <?php echo "<div class='col-sm-3'>".Select2::widget([
                    'model' => $model,
                    'attribute' => 'SuspendTenorSatuan',
                    'data' => ["Hari" => yii::t('app','Hari'), "Minggu" => yii::t('app','Minggu'), "Bulan" => yii::t('app','Bulan'), "Tahun" => yii::t('app','Tahun')],
                    'pluginOptions' => [
                        'allowClear' => false,
                        'class'=> 'col-sm-6'
                    ],
                    ])."</div>"; 
                    ?>
            </div>

        </div>
    </div>


    <?= $form->field($model, 'SuspendTenorMultiply')->input('number',['min'=>1,'style' => 'width: 10%;'])->label(Yii::t('app','Pengali Tenor Skorsing'))->hint(yii::t('app','Kali')); ?>



    <?php 


    ActiveForm::end(); ?>

</div>




<?php
$this->registerJs("

        if($('.DendaTypeRadioKonstan').is(':checked'))
        {
            $('.field-peraturanpeminjamantanggal-dendatenorjumlah-pack').hide();
            $('.field-peraturanpeminjamantanggal-dendatenormultiply').hide();
            $('#peraturanpeminjamantanggal-dendatenorjumlah').attr('min', 0);
            $('#peraturanpeminjamantanggal-dendatenorjumlah').val(0);

            $('#peraturanpeminjamantanggal-dendatenormultiply').attr('min', 0);
            $('#peraturanpeminjamantanggal-dendatenormultiply').val(0);
            
        }
        else
        {
            $('.field-peraturanpeminjamantanggal-dendatenorjumlah-pack').show();
            $('.field-peraturanpeminjamantanggal-dendatenormultiply').show();
            $('#peraturanpeminjamantanggal-dendatenorjumlah').attr('min', 1);
            $('#peraturanpeminjamantanggal-dendatenorjumlah').val(1);

            $('#peraturanpeminjamantanggal-dendatenormultiply').attr('min', 1);
            $('#peraturanpeminjamantanggal-dendatenormultiply').val(1);
        }

        if($('.SuspendTypeRadioKonstan').is(':checked'))
        {
            $('.field-peraturanpeminjamantanggal-suspendtenorjumlah-pack').hide();
            $('.field-peraturanpeminjamantanggal-suspendtenormultiply').hide();
            $('#peraturanpeminjamantanggal-suspendtenorjumlah').attr('min', 0);
            $('#peraturanpeminjamantanggal-suspendtenorjumlah').val(0);

            $('#peraturanpeminjamantanggal-suspendtenormultiply').attr('min', 0);
            $('#peraturanpeminjamantanggal-suspendtenormultiply').val(0);
            
        }
        else
        {
            $('.field-peraturanpeminjamantanggal-suspendtenorjumlah-pack').show();
            $('.field-peraturanpeminjamantanggal-suspendtenormultiply').show();
            $('#peraturanpeminjamantanggal-suspendtenorjumlah').attr('min', 1);
            $('#peraturanpeminjamantanggal-suspendtenorjumlah').val(1);

            $('#peraturanpeminjamantanggal-suspendtenormultiply').attr('min', 1);
            $('#peraturanpeminjamantanggal-suspendtenormultiply').val(1);

        }


    //ketika radiobuton DendaType di klik 
    $('.DendaTypeRadio').change(function(){
        //alert($(this).val());

        if($(this).val() == 'Konstan')
        {
            $('.field-peraturanpeminjamantanggal-dendatenorjumlah-pack').hide();
            $('.field-peraturanpeminjamantanggal-dendatenormultiply').hide();
            $('#peraturanpeminjamantanggal-dendatenorjumlah').attr('min', 0);
            $('#peraturanpeminjamantanggal-dendatenorjumlah').val(0);

            $('#peraturanpeminjamantanggal-dendatenormultiply').attr('min', 0);
            $('#peraturanpeminjamantanggal-dendatenormultiply').val(0);
            
        }
        else
        {
            $('.field-peraturanpeminjamantanggal-dendatenorjumlah-pack').show();
            $('.field-peraturanpeminjamantanggal-dendatenormultiply').show();
            $('#peraturanpeminjamantanggal-dendatenorjumlah').attr('min', 1);
            $('#peraturanpeminjamantanggal-dendatenorjumlah').val(1);

            $('#peraturanpeminjamantanggal-dendatenormultiply').attr('min', 1);
            $('#peraturanpeminjamantanggal-dendatenormultiply').val(1);
        }
    });

    //ketika radiobuton DendaType di klik 
    $('.SuspendTypeRadio').change(function(){
        //alert($(this).val());

        if($(this).val() == 'Konstan')
        {
            $('.field-peraturanpeminjamantanggal-suspendtenorjumlah-pack').hide();
            $('.field-peraturanpeminjamantanggal-suspendtenormultiply').hide();
            $('#peraturanpeminjamantanggal-suspendtenorjumlah').attr('min', 0);
            $('#peraturanpeminjamantanggal-suspendtenorjumlah').val(0);

            $('#peraturanpeminjamantanggal-suspendtenormultiply').attr('min', 0);
            $('#peraturanpeminjamantanggal-suspendtenormultiply').val(0);
            
        }
        else
        {
            $('.field-peraturanpeminjamantanggal-suspendtenorjumlah-pack').show();
            $('.field-peraturanpeminjamantanggal-suspendtenormultiply').show();
            $('#peraturanpeminjamantanggal-suspendtenorjumlah').attr('min', 1);
            $('#peraturanpeminjamantanggal-suspendtenorjumlah').val(1);

            $('#peraturanpeminjamantanggal-suspendtenormultiply').attr('min', 1);
            $('#peraturanpeminjamantanggal-suspendtenormultiply').val(1);

        }
    });


");
?>