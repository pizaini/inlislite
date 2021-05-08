<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;


/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 */

$this->title = Yii::t('app', 'Pengaturan Jaminan Peminjaman');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Loker'), 'url' => Url::to(['/setting/loker'])];
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
                            <td class="col-sm-3"><label class="control-label"><?= Yii::t('app','Uang') ?></label></td>
                            <td>
                                <div class="col-sm-4" style="padding: 0;">
                                	<?= Html::activeCheckbox($model, 'JaminanUangLoker', ['class' => 'text-default','label' => yii::t('app','  Aktifkan')]); ?>
                                </div>
                                <div class="col-sm-3" style="padding-right: 0" >
                                    <!-- <button data-toggle="modal" type="button" data-target="#SettingJaminanUang" class="btn btn-primary btn-sm ol-sm-12"><i class="fa fa-gear"></i> <?= Yii::t('app','Setting') ?></button> -->
                                    <a href="<?= Url::to(['/setting/loker/master-uang-jaminan']) ?>" class="btn btn-primary btn-sm ol-sm-12"><i class="fa fa-gear"></i> <?= Yii::t('app','Setting') ?></a>
                                    <!--<?= Html::a(Yii::t('app','Setting'), ['/setting/loker/master-uang-jaminan',], ['class' => 'btn btn-sm btn-primary']) ?>-->
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
                            <td class="col-sm-3"><label class="control-label"><?= Yii::t('app','Identitas') ?></label></td>
                            <td>
                                <div class="col-sm-4" style="padding: 0;">
                                	<?= Html::activeCheckbox($model, 'JaminanIdentitasLoker', ['class' => 'text-default','label' => yii::t('app','  Aktifkan')]); ?>
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
                            <td class="col-sm-3"><label class="control-label"><?= Yii::t('app','Cetak Bukti Transaksi') ?></label></td>
                            <td>
                                <div class="col-sm-4" style="padding: 0;">
                                	<?= Html::activeCheckbox($model, 'CetakBuktiTransaksi', ['class' => 'text-default','label' => yii::t('app','  Aktifkan')]); ?>
                                </div>
                                
                                <div class="padding0 col-sm-9"><b class="hint-idt"></b></div>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>

            <!-- Table Set CetakBuktiPelanggaran -->
            <div class="form-horizontal"> <!-- hidden="hidden" -->
                <table class="table" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <td class="col-sm-3"><label class="control-label"><?= Yii::t('app','Cetak Bukti Pelanggaran') ?></label></td>
                            <td>
                                <div class="col-sm-4" style="padding: 0;">
                                	<?= Html::activeCheckbox($model, 'CetakBuktiPelanggaran', ['class' => 'text-default','label' => yii::t('app','  Aktifkan')]); ?>
                                </div>
                                
                                <div class="padding0 col-sm-9"><b class="hint-idt"></b></div>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>

            <!-- Table Set Apakah Member boleh meminjam lebih dari 1 -->
            <div class="form-horizontal"> <!-- hidden="hidden" -->
                <table class="table" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <td class="col-sm-3"><label class="control-label"><?= Yii::t('app','Pinjam Lebih Dari Satu') ?></label></td>
                            <td>
                                <div class="col-sm-4" style="padding: 0;">
                                	<?= Html::activeCheckbox($model, 'IsMemberAllowedToBorrowMultipleLocker', ['class' => 'text-default','label' => yii::t('app','  Aktifkan')]); ?>
                                </div>
                                
                                <div class="padding0 col-sm-9"><b class="hint-idt"></b></div>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>

   
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
