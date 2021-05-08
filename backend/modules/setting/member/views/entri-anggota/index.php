<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\JenisPerpustakaan;


/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 */

$this->title = Yii::t('app', 'Pengaturan Entri Anggota');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Member'), 'url' => Url::to(['/setting/member'])];
$this->params['breadcrumbs'][] = $this->title;

?>

<style type="text/css">
	.col-sm-4 label{
		font-weight: normal;
	}

	.table{
		margin-bottom: 0px;
	}

    .settingparameters-form > .form-group > .form-horizontal > .form-group >.col-sm-offset-4{
        margin-left: 0px;
    }
    .borderless td, .borderless th {
        border: none;
    }
</style>

<div class="settingparameters-create">

<div class="settingparameters-form col-sm-6">
  <div class="form-group">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'formConfig' => ['labelSpan' => 4, 'deviceSize' => ActiveForm::SIZE_SMALL]]); ?>
    <div class="page-header">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?= $form->field($model,'TipeNomorAnggota')->radioList(['Otomatis'=>Yii::t('app', 'Otomatis'),'Manual'=>Yii::t('app', 'Manual')], ['inline'=>true])->label(Yii::t('app', 'Penentuan Nomor Anggota'))?>
   
    <?php // echo $form->field($model, 'TipePenomoranAnggota')->dropDownList(['1' => 'Pilihan 1', '2' => 'Pilihan 2', '3' => 'Pilihan 3', '4' => 'Pilihan 4']); ?>
    <?= $form->field($model, 'TipePenomoranAnggota')->widget(Select2::classname(), [
        'data' => ['1' => yii::t('app','Pilihan 1'), '2' => yii::t('app','Pilihan 2'), '3' => yii::t('app','Pilihan 3'), '4' => yii::t('app','Pilihan 4')],
        // 'options' => ['placeholder' => 'Select a state ...'],
        'pluginOptions' => [
        // 'allowClear' => true
        ],
    ])->label(yii::t('app','Tipe Penomoran Anggota')); ?>

    <?php //echo $form->field($model, 'MasaBerlakuAnggota')->dropDownList(['1' => '1 Hari', '2' => '1 Minggu', '3' => '1 Bulan', '4' => '1 Tahun']); ?>
    <?php // echo $form->field($model, 'MasaBerlakuAnggota')->widget(Select2::classname(), [
        // 'data' => ['1' => '1 Hari', '2' => '1 Minggu', '3' => '1 Bulan', '4' => '1 Tahun'],
        // 'pluginOptions' => [
        // ],
    // ]); ?>
   
    <?= $form->field($model, 'IsCetakSlipPerpanjangan')->checkbox(array('label'=>yii::t('app','Ya / Tidak')))->label(yii::t('app','Cetak Slip Perpanjangan'));; ?>	
   
    <?= $form->field($model, 'IsCetakSlipPelanggaran')->checkbox(array('label'=>yii::t('app','Ya / Tidak')))->label(yii::t('app','Cetak Slip Pelanggaran'));; ?>
   
    <?= $form->field($model, 'IsCetakSlipPendaftaran')->checkbox(array('label'=>yii::t('app','Ya / Tidak')))->label(yii::t('app','Cetak Slip Pendaftaran'));; ?>
    <?php //echo $form->field($model,'Value4')->radioList(['Simple'=>Yii::t('app', 'Simple'),'Advance'=>Yii::t('app', 'Advance')], ['inline'=>true])->label(Yii::t('app', 'Entry Form Collection'))?>

</div>


</div>

</div>




<!-- Modal -->
<div id="SettingJaminanUang" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app','Setting').' '.Yii::t('app','Uang Jaminan') ?></h4>
            </div>
            <div class="modal-body row">
                
                


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div class="col-sm-12">
<hr>
    <div class="col-sm-4">
        
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('app','Sistem Penomoran Anggota')  ?></h3>
                <div class="box-tools pull-right">
                    <!-- <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
                    <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
                </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body">
            <table class="table table-responsive">
                    <tr>
                        <td class="col-sm-6">
                            <?= yii::t('app','Pilihan 1')?>
                        </td>
                        <td class="col-sm-6">
                            YYMMDD99999
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?= yii::t('app','Pilihan 2')?>
                        </td>
                        <td>
                            YYYYMM999
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?= yii::t('app','Pilihan 3')?>
                        </td>
                        <td>
                            99999L2015
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?= yii::t('app','Pilihan 4')?>
                        </td>
                        <td>
                            NIK
                        </td>
                    </tr>
                </table>
            </div><!-- /.box-body -->
        </div>

    </div>

</div>


<script type="text/javascript">
        var a = $('input[name=\"DynamicModel[TipeNomorAnggota]\"]:checked').val();


    if (a == 'Otomatis') {
        $('.field-dynamicmodel-tipepenomorananggota').show();
    } else {
        $('.field-dynamicmodel-tipepenomorananggota').hide();
    }
    
</script>


<?php
$this->registerJs("

	// var a = $('input[name=\"DynamicModel[TipeNomorAnggota]\"]:checked').val();


	// if (a == 'Otomatis') {
	// 	$('.field-dynamicmodel-tipepenomorananggota').show();
	// } else {
	// 	$('.field-dynamicmodel-tipepenomorananggota').hide();
	// }
	


    //ketika radiobuton TipeNomorAnggota di klik 
    $('input[name=\"DynamicModel[TipeNomorAnggota]\"]').change(function(){
    	//alert($(this).val());

        if($(this).val() == 'Otomatis')
        {
        	$('.field-dynamicmodel-tipepenomorananggota').show();
        }
        else
        {
        	$('.field-dynamicmodel-tipepenomorananggota').hide();
        }
      
    });




");
?>