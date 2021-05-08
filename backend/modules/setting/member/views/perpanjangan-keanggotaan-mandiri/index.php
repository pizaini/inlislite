<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 */

$this->title = Yii::t('app', 'Pengaturan Setting Perpanjangan Keanggotaan Mandiri');
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

	.form-group > .col-md-offset-2, .col-md-10{
		margin-left: 0px;
	}
</style>

<?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);  ?>

<div class="settingparameters-create">
	<div class="page-header">
		<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
	</div>
    <div class="settingparameters-form">
      <div class="form-group">
        <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>

        <?= $form->field($model, 'PerpanjanganKenggotaanMandiri')->checkbox(array('label'=>yii::t('app','Aktif')))->label('Perpanjangan Keanggotaan Mandiri'); ?>
        <?php //echo $form->field($model,'Value4')->radioList(['Simple'=>Yii::t('app', 'Simple'),'Advance'=>Yii::t('app', 'Advance')], ['inline'=>true])->label(Yii::t('app', 'Entry Form Collection'))?>

        </div>
    </div>

</div>


<?php ActiveForm::end();  ?>