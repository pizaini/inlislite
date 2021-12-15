<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;


/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 */

$this->title = Yii::t('app', 'Pengaturan Bahasa');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bahasa'), 'url' => Url::to(['/setting/umum'])];
$this->params['breadcrumbs'][] = $this->title;

?>

<style type="text/css">
	label{
		font-weight: 100px;
	}

	.control-label{
		font-size: 15px;
	}

    .radio-inline, .checkbox-inline{
        font-size: 15px;
    }
    .borderless td, .borderless th {
        border: none;
    }
</style>

<div class="settingparameters-create">
        <!-- <div class="collapse navbar-collapse pull-right clockZ" id="navbar-collapse" style="margin-top:35px; cursor:pointer;">
            <?php 
             // foreach (yii::$app->params['languages'] as $key => $language) {
                 // echo '<span class="language" style="font-size:20px; color:black; margin-top:42px" id="'.$key.'">'.$language.' </span>';
             // }
            ?>
        </div> -->
<?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'formConfig' => ['labelSpan' => 4, 'deviceSize' => ActiveForm::SIZE_SMALL]]); ?>
    <div class="page-header">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>
<?= $form->field($model,'language')->radioList(['idn'=>Yii::t('app', 'Indonesia'),'en'=>Yii::t('app', 'Inggris')], ['inline'=>true])->label(Yii::t('app', 'Pilih Bahasa'),['class' => 'control-label col-sm-2'])?>

</div>

<script type="text/javascript">
        var a = $('input[name=\"DynamicModel[language]\"]:checked').val();
    
</script>
