<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var common\models\JenisAnggota $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="jenis-anggota-form">

    <?php
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]]);
    
    echo '<div class="page-header">' . Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

    echo '&nbsp;' . Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-warning']) . '</div>';
    
    echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'jenisanggota' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Jenisanggota') . '...', 'maxlength' => 255],'label'=>Yii::t('app','Name')],
            
            'MasaBerlakuAnggota' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Masa Berlaku Anggota') . '...', 'maxlength' => 255],'label'=>Yii::t('app','Masa Berlaku Anggota'),'hint'=>Yii::t('app','Hari')],
            
            'BiayaPendaftaran' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'BiayaPendaftaran') . '...', 'maxlength' => 10]],
            
            'BiayaPerpanjangan' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'BiayaPerpanjangan') . '...', 'maxlength' => 10]],
            
            'UploadDokumenKeanggotaanOnline' => ['type' => Form::INPUT_CHECKBOX, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'UploadDokumenKeanggotaanOnline') . '...', 'maxlength' => 1]],
            
            'MaxPinjamKoleksi' => ['type' => Form::INPUT_TEXT, 'options' => [
                    'placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'MaxPinjamKoleksi') . '...', 'maxlength' => 11],'label'=>Yii::t('app', 'Maks. koleksi yang dapat dipinjam')],
            
            'MaxLoanDays' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Max Lama Peminjaman') . '...', 'maxlength' => 10],'label'=> Yii::t('app','Maks. Lama Pinjam'),'hint'=>Yii::t('app','Hari')],
            
            'WarningLoanDueDay' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Jeda Hari Peringatan Peminjaman utk Kembali') . '...', 'maxlength' => 10],'label'=>Yii::t('app', 'Jeda Hari Peringatan Peminjaman utk Kembali'),'hint'=>Yii::t('app','Hari')],
            
            'DayPerpanjang' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Max Lama Perpanjangan') . '...', 'maxlength' => 10],'label'=>Yii::t('app', 'Maks. Lama Perpanjangan'),'hint'=>'hari'],
            
            'CountPerpanjang' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Maks. Banyaknya Perpanjang') . '...', 'maxlength' => 10],'label'=>Yii::t('app', 'Maks. Banyaknya Perpanjang'),'hint'=>Yii::t('app','(jika diisi dengan 0 maka tidak boleh diperpanjang).')],

/*

            'DendaPerTenor' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Denda Per Tenor') . '...', 'maxlength' => 10]],
            'DendaTenorJumlah' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Jumlah Denda Tenor') . '...', 'maxlength' => 10]],
            //'DendaTenorSatuan' => ['type' => Form::INPUT_DROPDOWN_LIST, 'options' => ['value' => ["Hari" => "Hari", "Minggu" => "Minggu", "Bulan" => "Bulan", "Tahun" => "Tahun"]]],
            'DendaTenorSatuan' => ['type' => Form::INPUT_DROPDOWN_LIST, 'items' => ["Hari" => "Hari", "Minggu" => "Minggu", "Bulan" => "Bulan", "Tahun" => "Tahun"], 'hint' => 'Type and select state'],
            'DendaTenorMultiply' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Pengali Denda Tenor') . '...', 'maxlength' => 10]],
            'DaySuspend' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Lama Suspend') . '...', 'maxlength' => 10]],
            
            */
            
        ]
    ]);
    //echo $form->field($model, 'satuan')->dropDownList(["Hari" => "Hari", "Minggu" => "Minggu", "Bulan" => "Bulan", "Tahun" => "Tahun"]);
    



			//echo $form->field($model, 'DendaType')->radioList(array('Konstan'=>'Konstan','Berkelipatan'=>'Berkelipatan'), ['inline'=>true])->label(Yii::t('app','Denda'));


		echo $form->field($model, 'DendaType')->radioList(array('Konstan'=>yii::t('app','Konstan'),'Berkelipatan'=>yii::t('app','Berkelipatan')), 
			[
				'item' => function($index, $label, $name, $checked, $value) {

					$return = '<label class="radio-inline">';
					$return .= '<input type="radio" class="DendaTypeRadio DendaTypeRadio'.$value.'" name="' . $name . '" value="' . $value . '"  '.($checked == 1? 'checked':'').'>';
					$return .= ' '.ucwords($label);
					$return .= '</label>';

					return $return;
				}
			]
			)->label(Yii::t('app','Denda'));

		echo $form->field($model, 'DendaPerTenor')->input('number',['min'=>0,'style' => 'width: 10%;'])->label(Yii::t('app','Jumlah Denda'));


?>

	<div class="form-group kv-fieldset-inline field-jenisanggota-dendatenorjumlah-pack" >
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
					'data' => ["Hari" => yii::t('app','Harian'), "Minggu" => yii::t('app','Minggu'), "Bulan" => yii::t('app','Bulanan'), "Tahun" => yii::t('app','Tahunan')],
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

	echo $form->field($model, 'DendaTenorMultiply')->input('number',['min'=>1,'style' => 'width: 10%;'])->label(Yii::t('app','Pengali Tenor Denda'))->hint(Yii::t('app','Kali'));
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

	echo $form->field($model, 'DaySuspend')->input('number',['min'=>0,'style' => 'width: 10%;'])->label(Yii::t('app','Lama Skorsing'))->hint(Yii::t('app','Hari'));
?>
<div class="form-group kv-fieldset-inline field-jenisanggota-suspendtenorjumlah-pack">
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
				'data' => ["Hari" => yii::t('app','Harian'), "Minggu" => yii::t('app','Minggu'), "Bulan" => yii::t('app','Bulanan'), "Tahun" => yii::t('app','Tahunan')],
				'pluginOptions' => [
					'allowClear' => false,
					'class'=> 'col-sm-6'
				],
				])."</div>"; 
				?>
    	</div>

    </div>
</div>


<?= $form->field($model, 'SuspendTenorMultiply')->input('number',['min'=>1,'style' => 'width: 10%;'])->label(Yii::t('app','Pengali Tenor Skorsing'))->hint(Yii::t('app','Kali')); ?>



    <?php ActiveForm::end();
    ?>

</div>




<!-- HISTORY -->
<?php
    echo \common\widgets\Histori::widget([
            'model'=>$model,
            'id'=>'member',
            'urlHistori'=>'detail-histori?id='.$model->id
        
    ]);
?>









<?php
$this->registerJs("

        if($('.DendaTypeRadioKonstan').is(':checked'))
        {
            $('.field-jenisanggota-dendatenorjumlah-pack').hide();
            $('.field-jenisanggota-dendatenormultiply').hide();
            $('#jenisanggota-dendatenorjumlah').attr('min', 0);
            $('#jenisanggota-dendatenorjumlah').val(0);

            $('#jenisanggota-dendatenormultiply').attr('min', 0);
            $('#jenisanggota-dendatenormultiply').val(0);
            
        }
        else
        {
        	$('.field-jenisanggota-dendatenorjumlah-pack').show();
            $('.field-jenisanggota-dendatenormultiply').show();
            $('#jenisanggota-dendatenorjumlah').attr('min', 1);
            $('#jenisanggota-dendatenorjumlah').val(1);

            $('#jenisanggota-dendatenormultiply').attr('min', 1);
            $('#jenisanggota-dendatenormultiply').val(1);
        }

        if($('.SuspendTypeRadioKonstan').is(':checked'))
        {
            $('.field-jenisanggota-suspendtenorjumlah-pack').hide();
            $('.field-jenisanggota-suspendtenormultiply').hide();
            $('#jenisanggota-suspendtenorjumlah').attr('min', 0);
            $('#jenisanggota-suspendtenorjumlah').val(0);

            $('#jenisanggota-suspendtenormultiply').attr('min', 0);
            $('#jenisanggota-suspendtenormultiply').val(0);
            
        }
        else
        {
            $('.field-jenisanggota-suspendtenorjumlah-pack').show();
            $('.field-jenisanggota-suspendtenormultiply').show();
            $('#jenisanggota-suspendtenorjumlah').attr('min', 1);
            $('#jenisanggota-suspendtenorjumlah').val(1);

            $('#jenisanggota-suspendtenormultiply').attr('min', 1);
            $('#jenisanggota-suspendtenormultiply').val(1);

        }


    //ketika radiobuton DendaType di klik 
    $('.DendaTypeRadio').change(function(){
    	//alert($(this).val());

        if($(this).val() == 'Konstan')
        {
            $('.field-jenisanggota-dendatenorjumlah-pack').hide();
            $('.field-jenisanggota-dendatenormultiply').hide();
            $('#jenisanggota-dendatenorjumlah').attr('min', 0);
            $('#jenisanggota-dendatenorjumlah').val(0);

            $('#jenisanggota-dendatenormultiply').attr('min', 0);
            $('#jenisanggota-dendatenormultiply').val(0);
            
        }
        else
        {
        	$('.field-jenisanggota-dendatenorjumlah-pack').show();
            $('.field-jenisanggota-dendatenormultiply').show();
            $('#jenisanggota-dendatenorjumlah').attr('min', 1);
            $('#jenisanggota-dendatenorjumlah').val(1);

            $('#jenisanggota-dendatenormultiply').attr('min', 1);
            $('#jenisanggota-dendatenormultiply').val(1);
        }
    });

    //ketika radiobuton DendaType di klik 
    $('.SuspendTypeRadio').change(function(){
    	//alert($(this).val());

        if($(this).val() == 'Konstan')
        {
            $('.field-jenisanggota-suspendtenorjumlah-pack').hide();
            $('.field-jenisanggota-suspendtenormultiply').hide();
            $('#jenisanggota-suspendtenorjumlah').attr('min', 0);
            $('#jenisanggota-suspendtenorjumlah').val(0);

            $('#jenisanggota-suspendtenormultiply').attr('min', 0);
            $('#jenisanggota-suspendtenormultiply').val(0);
            
        }
        else
        {
            $('.field-jenisanggota-suspendtenorjumlah-pack').show();
            $('.field-jenisanggota-suspendtenormultiply').show();
            $('#jenisanggota-suspendtenorjumlah').attr('min', 1);
            $('#jenisanggota-suspendtenorjumlah').val(1);

            $('#jenisanggota-suspendtenormultiply').attr('min', 1);
            $('#jenisanggota-suspendtenormultiply').val(1);

        }
    });


");
?>