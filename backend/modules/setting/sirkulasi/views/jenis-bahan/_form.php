<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var common\models\LocationLibrary $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="location-library-form">

    <?php
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]]);
    
    echo "<div class='page-header'>";
    echo '<p>'. Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) . '</p>';
    echo "</div>";


    echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'MaxPinjamKoleksi' => ['type' => Form::INPUT_TEXT, 'options' => ['style'=>'width: 10%;','placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Kode') . '...', 'maxlength' => 50],'label'=>Yii::t('app','Maks. koleksi yang dapat dipinjam')],

            'MaxLoanDays' => ['type' => Form::INPUT_TEXT, 'options' => ['style'=>'width: 10%;','placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Nama') . '...', 'maxlength' => 255],'label'=>Yii::t('app','Maks. Lama Pinjam'),'hint'=>yii::t('app','Hari')],
            
            'WarningLoanDueDay' => ['type' => Form::INPUT_TEXT, 'options' => ['style'=>'width: 10%;','placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Nama') . '...', 'maxlength' => 255],'label'=>Yii::t('app','Jeda Hari Peringatan Peminjaman utk Kembali'),'hint'=>yii::t('app','Hari')],
            
            'DayPerpanjang' => ['type' => Form::INPUT_TEXT, 'options' => ['style'=>'width: 10%;','placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Nama') . '...', 'maxlength' => 255],'label'=>Yii::t('app','Maks. Lama Perpanjang'),'hint'=>yii::t('app','Hari')],

            'CountPerpanjang' => ['type' => Form::INPUT_TEXT, 'options' => ['style'=>'width: 10%;','placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Nama') . '...', 'maxlength' => 255],'label'=>Yii::t('app','Maks. Banyaknya Perpanjang'),'hint'=>yii::t('app','(jika diisi dengan 0 maka tidak boleh diperpanjang)')],
        ]
    ]);

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

	<div class="form-group kv-fieldset-inline field-worksheets-dendatenorjumlah-pack" >
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
<div class="form-group kv-fieldset-inline field-worksheets-suspendtenorjumlah-pack">
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


<?= $form->field($model, 'SuspendTenorMultiply')->input('number',['min'=>1,'style' => 'width: 10%;'])->label(Yii::t('app','Pengali Tenor Skorsing'))->hint(yii::t('app','Hari')); ?>


    
    <?php ActiveForm::end();
    ?>

</div>


<!-- HISTORY -->
<?php
    echo \common\widgets\Histori::widget([
            'model'=>$model,
            'id'=>'member',
            'urlHistori'=>'detail-histori?id='.$model->ID
        
    ]);
?>

<?php
$this->registerJs("

        if($('.DendaTypeRadioKonstan').is(':checked'))
        {
            $('.field-worksheets-dendatenorjumlah-pack').hide();
            $('.field-worksheets-dendatenormultiply').hide();
            $('#worksheets-dendatenorjumlah').attr('min', 0);
            $('#worksheets-dendatenorjumlah').val(0);

            $('#worksheets-dendatenormultiply').attr('min', 0);
            $('#worksheets-dendatenormultiply').val(0);
            
        }
        else
        {
        	$('.field-worksheets-dendatenorjumlah-pack').show();
            $('.field-worksheets-dendatenormultiply').show();
            $('#worksheets-dendatenorjumlah').attr('min', 1);
            $('#worksheets-dendatenorjumlah').val(1);

            $('#worksheets-dendatenormultiply').attr('min', 1);
            $('#worksheets-dendatenormultiply').val(1);
        }

        if($('.SuspendTypeRadioKonstan').is(':checked'))
        {
            $('.field-worksheets-suspendtenorjumlah-pack').hide();
            $('.field-worksheets-suspendtenormultiply').hide();
            $('#worksheets-suspendtenorjumlah').attr('min', 0);
            $('#worksheets-suspendtenorjumlah').val(0);

            $('#worksheets-suspendtenormultiply').attr('min', 0);
            $('#worksheets-suspendtenormultiply').val(0);
            
        }
        else
        {
            $('.field-worksheets-suspendtenorjumlah-pack').show();
            $('.field-worksheets-suspendtenormultiply').show();
            $('#worksheets-suspendtenorjumlah').attr('min', 1);
            $('#worksheets-suspendtenorjumlah').val(1);

            $('#worksheets-suspendtenormultiply').attr('min', 1);
            $('#worksheets-suspendtenormultiply').val(1);

        }


    //ketika radiobuton DendaType di klik 
    $('.DendaTypeRadio').change(function(){
    	//alert($(this).val());

        if($(this).val() == 'Konstan')
        {
            $('.field-worksheets-dendatenorjumlah-pack').hide();
            $('.field-worksheets-dendatenormultiply').hide();
            $('#worksheets-dendatenorjumlah').attr('min', 0);
            $('#worksheets-dendatenorjumlah').val(0);

            $('#worksheets-dendatenormultiply').attr('min', 0);
            $('#worksheets-dendatenormultiply').val(0);
            
        }
        else
        {
        	$('.field-worksheets-dendatenorjumlah-pack').show();
            $('.field-worksheets-dendatenormultiply').show();
            $('#worksheets-dendatenorjumlah').attr('min', 1);
            $('#worksheets-dendatenorjumlah').val(1);

            $('#worksheets-dendatenormultiply').attr('min', 1);
            $('#worksheets-dendatenormultiply').val(1);
        }
    });

    //ketika radiobuton DendaType di klik 
    $('.SuspendTypeRadio').change(function(){
    	//alert($(this).val());

        if($(this).val() == 'Konstan')
        {
            $('.field-worksheets-suspendtenorjumlah-pack').hide();
            $('.field-worksheets-suspendtenormultiply').hide();
            $('#worksheets-suspendtenorjumlah').attr('min', 0);
            $('#worksheets-suspendtenorjumlah').val(0);

            $('#worksheets-suspendtenormultiply').attr('min', 0);
            $('#worksheets-suspendtenormultiply').val(0);
            
        }
        else
        {
            $('.field-worksheets-suspendtenorjumlah-pack').show();
            $('.field-worksheets-suspendtenormultiply').show();
            $('#worksheets-suspendtenorjumlah').attr('min', 1);
            $('#worksheets-suspendtenorjumlah').val(1);

            $('#worksheets-suspendtenormultiply').attr('min', 1);
            $('#worksheets-suspendtenormultiply').val(1);

        }
    });


");
?>