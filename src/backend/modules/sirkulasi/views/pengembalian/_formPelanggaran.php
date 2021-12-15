<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var common\models\Pelanggaran $model
 * @var yii\widgets\ActiveForm $form
 */
?>


<div class="pelanggaran-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);  ?>
	
	<div class="page-header">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
		<?= '&nbsp;' .Html::a('<span class="glyphicon glyphicon-remove"></span> '. Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-warning']); ?>
    </div>
	
	<input type="hidden" name="for" value="<?=$for?>">

    <div class="row">


    	<div class="col-sm-4">
    		<div class="table-responsive">
    			<table class="table">
    				<tbody>


    					<tr>
    						<th style="width:25%"><?= yii::t('app','No.Anggota')?></th>
    						<th style="width:5%">:</th>
    						<th><?= $modelItem->member->MemberNo ?></th>
    					</tr>

    					<tr>
    						<th><?= yii::t('app','Nama Anggota')?></th>
    						<th>:</th>
    						<th><?= $modelItem->member->Fullname ?></th>
    					</tr>


    					<tr>
    						<th style="width:25%"><?= yii::t('app','No.Peminjaman')?></th>
    						<th style="width:5%">:</th>
    						<td><?= $modelItem->CollectionLoan_id ?></td>
    					</tr>

    					<tr>
    						<th><?= yii::t('app','No.Barcode')?></th>
    						<th>:</th>
    						<td><?= $modelItem->collection->NomorBarcode ?></td>
    					</tr>

    				</tbody>
    			</table>
    		</div>
    	</div>

    	<div class="col-sm-4">
    		<div class="table-responsive">
    			<table class="table">
    				<tbody>


    				
    					<tr>
    						<th><?= yii::t('app','Judul')?></th>
    						<th>:</th>
    						<td><?= $modelItem->collection->catalog->Title ?></td>
    					</tr>

    					<tr>
    						<th style="width:25%"><?= yii::t('app','Penerbit')?></th>
    						<th style="width:5%">:</th>
    						<td><?= $modelItem->collection->catalog->Publisher ?></td>
    					</tr>

    					<tr>
    						<th><?= yii::t('app','Tgl.Pinjam')?></th>
    						<th>:</th>
    						<td><?= \common\components\Helpers::DateTimeToViewFormat($modelItem->LoanDate) ?></td>
    					</tr>

    					<tr>
    						<th><?= yii::t('app','Jatuh Tempo')?></th>
    						<th>:</th>
    						<td><?= \common\components\Helpers::DateTimeToViewFormat($modelItem->DueDate) ?></td>
    					</tr>


    				</tbody>
    			</table>
    		</div>
    	</div>


    </div>
    <hr>




	<?php
	//default value 0
	$model->JumlahDenda = $model->JumlahSuspend = 0;

	echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

		// 'JenisPelanggaran_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Jenis Pelanggaran ID').'...']], 
		'JenisPelanggaran_id'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>Select2::classname(),'options'=>['data' => ArrayHelper::map(common\models\JenisPelanggaran::find()->all(),'ID','JenisPelanggaran'),'options' => ['placeholder' => Yii::t('app', 'Enter').' '.Yii::t('app','Jenis Pelanggaran')],],'label'=>Yii::t('app','Jenis Pelanggaran')],

		'JenisDenda_id'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>Select2::classname(),'options'=>['data' => ArrayHelper::map(common\models\JenisDenda::find()->all(),'ID','Name'),'options' => ['placeholder' => Yii::t('app', 'Enter').' '.Yii::t('app','Jenis Denda')]],'label'=>Yii::t('app','Jenis Denda')],
		
		'JumlahDenda'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Jumlah Denda').'...', 'maxlength'=>100]], 
		
		'JumlahSuspend'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Jumlah Skorsing').'...'],'label'=>Yii::t('app','Jumlah Skorsing')], 
		
		// 'CollectionLoanItem_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Collection Loan Item ID').'...']], 


		// 'Member_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Member ID').'...']], 

		// 'Collection_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'No.Item').'...']], 




		// 'Paid'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Bayar').'...']], 

		// 'CollectionLoan_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'No.Peminjaman').'...', 'maxlength'=>255]], 

    ]


    ]);
    
    ActiveForm::end(); ?>

</div>
