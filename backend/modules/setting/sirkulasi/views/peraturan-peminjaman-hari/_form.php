<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

use kartik\widgets\DatePicker;

use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var common\models\base\PeraturanPeminjamanHari $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="peraturan-peminjaman-hari-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]]);
       
    echo "<div class='page-header'>";
    echo '<p>'. Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) . '</p>';
    echo "</div>";




    echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [



        'DayIndex'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>Select2::classname(),'options'=>['data'=> $day = [1=>yii::t('app','Senin'),2=>yii::t('app','Selasa'),3=>yii::t('app','Rabu'),4=>yii::t('app','Kamis'),5=>yii::t('app','Jum\'at'),6=>yii::t('app','Sabtu'),7=>yii::t('app','Mingggu')],'pluginOptions' => []], ], 



        'MaxPinjamKoleksi'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Maks. koleksi yang dapat dipinjam').'...'],'label' => Yii::t('app','Maks. koleksi yang dapat dipinjam')], 

        'MaxLoanDays'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Maks. Lama Pinjam').'...'],'label' => Yii::t('app','Maks. Lama Pinjam')], 

        'WarningLoanDueDay'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Jeda Hari Peringatan Peminjaman utk Kembali').'...'],'label' => Yii::t('app','Jeda Hari Peringatan Peminjaman utk Kembali')], 

        'DayPerpanjang'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Maks. Lama Perpanjang').'...'],'label' => Yii::t('app','Maks. Lama Perpanjang')], 

        'CountPerpanjang'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Maks. Banyaknya Perpanjang').'...'],'label' => Yii::t('app','Maks. Banyaknya Perpanjang'),'hint'=>Yii::t('app','(jika diisi dengan 0 maka tidak boleh diperpanjang).')], 




        ]
        ]);

        echo $form->field($model,'collectionCategory',[
                    //'options'=>[ 'style'=>'width: 821px;']
                ])->checkboxList(
                                yii\helpers\ArrayHelper::map(common\models\Collectioncategorys::find()->all(),'ID','Name'))
                     ->label('Koleksi yang dapat dipinjam');


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

    <div class="form-group kv-fieldset-inline field-peraturanpeminjamanhari-dendatenorjumlah-pack" >
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
    <div class="form-group kv-fieldset-inline field-peraturanpeminjamanhari-suspendtenorjumlah-pack">
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
            $('.field-peraturanpeminjamanhari-dendatenorjumlah-pack').hide();
            $('.field-peraturanpeminjamanhari-dendatenormultiply').hide();
            $('#peraturanpeminjamanhari-dendatenorjumlah').attr('min', 0);
            $('#peraturanpeminjamanhari-dendatenorjumlah').val(0);

            $('#peraturanpeminjamanhari-dendatenormultiply').attr('min', 0);
            $('#peraturanpeminjamanhari-dendatenormultiply').val(0);
            
        }
        else
        {
            $('.field-peraturanpeminjamanhari-dendatenorjumlah-pack').show();
            $('.field-peraturanpeminjamanhari-dendatenormultiply').show();
            $('#peraturanpeminjamanhari-dendatenorjumlah').attr('min', 1);
            $('#peraturanpeminjamanhari-dendatenorjumlah').val(1);

            $('#peraturanpeminjamanhari-dendatenormultiply').attr('min', 1);
            $('#peraturanpeminjamanhari-dendatenormultiply').val(1);
        }

        if($('.SuspendTypeRadioKonstan').is(':checked'))
        {
            $('.field-peraturanpeminjamanhari-suspendtenorjumlah-pack').hide();
            $('.field-peraturanpeminjamanhari-suspendtenormultiply').hide();
            $('#peraturanpeminjamanhari-suspendtenorjumlah').attr('min', 0);
            $('#peraturanpeminjamanhari-suspendtenorjumlah').val(0);

            $('#peraturanpeminjamanhari-suspendtenormultiply').attr('min', 0);
            $('#peraturanpeminjamanhari-suspendtenormultiply').val(0);
            
        }
        else
        {
            $('.field-peraturanpeminjamanhari-suspendtenorjumlah-pack').show();
            $('.field-peraturanpeminjamanhari-suspendtenormultiply').show();
            $('#peraturanpeminjamanhari-suspendtenorjumlah').attr('min', 1);
            $('#peraturanpeminjamanhari-suspendtenorjumlah').val(1);

            $('#peraturanpeminjamanhari-suspendtenormultiply').attr('min', 1);
            $('#peraturanpeminjamanhari-suspendtenormultiply').val(1);

        }


    //ketika radiobuton DendaType di klik 
    $('.DendaTypeRadio').change(function(){
        //alert($(this).val());

        if($(this).val() == 'Konstan')
        {
            $('.field-peraturanpeminjamanhari-dendatenorjumlah-pack').hide();
            $('.field-peraturanpeminjamanhari-dendatenormultiply').hide();
            $('#peraturanpeminjamanhari-dendatenorjumlah').attr('min', 0);
            $('#peraturanpeminjamanhari-dendatenorjumlah').val(0);

            $('#peraturanpeminjamanhari-dendatenormultiply').attr('min', 0);
            $('#peraturanpeminjamanhari-dendatenormultiply').val(0);
            
        }
        else
        {
            $('.field-peraturanpeminjamanhari-dendatenorjumlah-pack').show();
            $('.field-peraturanpeminjamanhari-dendatenormultiply').show();
            $('#peraturanpeminjamanhari-dendatenorjumlah').attr('min', 1);
            $('#peraturanpeminjamanhari-dendatenorjumlah').val(1);

            $('#peraturanpeminjamanhari-dendatenormultiply').attr('min', 1);
            $('#peraturanpeminjamanhari-dendatenormultiply').val(1);
        }
    });

    //ketika radiobuton DendaType di klik 
    $('.SuspendTypeRadio').change(function(){
        //alert($(this).val());

        if($(this).val() == 'Konstan')
        {
            $('.field-peraturanpeminjamanhari-suspendtenorjumlah-pack').hide();
            $('.field-peraturanpeminjamanhari-suspendtenormultiply').hide();
            $('#peraturanpeminjamanhari-suspendtenorjumlah').attr('min', 0);
            $('#peraturanpeminjamanhari-suspendtenorjumlah').val(0);

            $('#peraturanpeminjamanhari-suspendtenormultiply').attr('min', 0);
            $('#peraturanpeminjamanhari-suspendtenormultiply').val(0);
            
        }
        else
        {
            $('.field-peraturanpeminjamanhari-suspendtenorjumlah-pack').show();
            $('.field-peraturanpeminjamanhari-suspendtenormultiply').show();
            $('#peraturanpeminjamanhari-suspendtenorjumlah').attr('min', 1);
            $('#peraturanpeminjamanhari-suspendtenorjumlah').val(1);

            $('#peraturanpeminjamanhari-suspendtenormultiply').attr('min', 1);
            $('#peraturanpeminjamanhari-suspendtenormultiply').val(1);

        }
    });


");
?>