<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use common\models\JenisPerpustakaan;


/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 */

$this->title = Yii::t('app', 'Pengaturan Data Perpustakaan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Umum'), 'url' => Url::to(['/setting/umum'])];
$this->params['breadcrumbs'][] = $this->title;

?>

<style type="text/css">
	.col-sm-4 label{
		font-weight: normal;
	}

	.table{
		margin-bottom: 0px;
	}
</style>


<div class="settingparameters-create">
	<div class="page-header">
		<h1><?= Html::encode($this->title) ?></h1>
	</div>

	<div class="settingparameters-form">
		<div class="col-sm-9">
			<?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>

            <!-- Table Set Jaminan Uang -->
            <div class="form-horizontal"> <!-- hidden="hidden" -->
                <table class="table" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <td class="col-sm-3"><label class="control-label"><?= Yii::t('app','Nama Perpustakaan') ?></label></td>
                            <td>
                                <div class="col-sm-4" style="padding: 0;">
                                	<?= Html::activeTextInput($model, 'NamaPerpustakaan', ['style' => 'width:500px', 'style' => 'height:40px']); ?>
                                </div>
                                                                <div class="padding0 col-sm-9"><b class="hint-uang"></b></div>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>

            <!-- Table Set Jaminan Identitas -->
            <div class="form-horizontal"> <!-- hidden="hidden" -->
                <table class="table" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <td class="col-sm-3"><label class="control-label"><?= Yii::t('app','Lokasi Perpustakaan') ?></label></td>
                            <td>
                                <div class="col-sm-4" style="padding: 0;">
                                	<?= Html::activeTextInput($model, 'NamaLokasiPerpustakaan', ['style' => 'width:500px', 'style' => 'height:40px']); ?>
                                </div>
                                
                                <div class="padding0 col-sm-9"><b class="hint-idt"></b></div>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>

   
            <!-- Table Set CetakBuktiTransaksi -->
            <div class="form-horizontal"> <!-- hidden="hidden" -->
                <table class="table" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <td class="col-sm-3"><label class="control-label"><?= Yii::t('app','Jenis Perpustakaan') ?></label></td>
                            <td>
                                <div class="col-sm-4" style="padding: 0;">

                                	
                                <?= $form->field($model, 'JenisPerpustakaan')->widget('\kartik\widgets\Select2',[
                                        'data'=>ArrayHelper::map(JenisPerpustakaan::find()->all(),'ID','Name'),
                                        'pluginOptions' => [
                                        'allowClear' => true,
                                        ],
                                        'options'=> ['placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'Jenis Perpustakaan')]
                                        ])->label(false); ?>
                                
                                </div>
                                
                                <div class="padding0 col-sm-9"><b class="hint-idt"></b></div>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>

            <!-- Table Set CetakBuktiPelanggaran -->
            
            <!-- Table Set Apakah Member boleh meminjam lebih dari 1 -->
            
   
            <hr>

			<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => ' btn btn-primary']) ?>


			<?php ActiveForm::end() ?>
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
