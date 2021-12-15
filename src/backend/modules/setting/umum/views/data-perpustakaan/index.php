<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use common\models\JenisPerpustakaan;
use kartik\widgets\FileInput;


/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 */

$this->title = Yii::t('app', 'Pengaturan Nama Perpustakaan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), ];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Umum'), ];
$this->params['breadcrumbs'][] = $this->title;

?>

<style type="text/css">
    .col-sm-4 label {
        font-weight: normal;
    }

    .table {
        margin-bottom: 0px;
    }
</style>


<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<div class="settingparameters-create">
    <div class="page-header">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => ' btn btn-primary']) ?>
    </div>

    <div class="settingparameters-form">
        <div class="col-sm-9">


            <div class="form-horizontal"> <!-- hidden="hidden" -->
                <table class="table" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <td class="col-sm-3">
                                <label class="control-label"><?= Yii::t('app', 'Nama Perpustakaan') ?></label>
                            </td>
                            <td>
                                <div class="col-sm-9" style="margin-bottom: -22px">
                                    <?= $form->field($model, 'NamaPerpustakaan')->textInput([
                                        'placeholder' => $model->getAttributeLabel('MemberNo'),
                                        // 'style' => 'width:500px;',
                                        'maxlength' => 255,
                                    ])->label(false); ?>

                                </div>

                            </td>
                        </tr>
                    </thead>
                </table>
            </div>


            <div class="form-horizontal"> <!-- hidden="hidden" -->
                <table class="table" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <td class="col-sm-3">
                                <label class="control-label"><?= Yii::t('app', 'Jenis Perpustakaan') ?></label>
                            </td>
                            <td>
                                <div class="col-sm-9" style="margin-bottom: -25px;">
                                    <?= $form->field($model, 'JenisPerpustakaan')->widget('\kartik\widgets\Select2', [
                                        'data' => ArrayHelper::map(JenisPerpustakaan::find()->all(), 'ID', 'Name'),
                                        'size' => 'sm',
                                        'pluginOptions' => [
                                            //'allowClear' => true,
                                        ],
                                        'options' => ['placeholder' => Yii::t('app', 'Choose') . ' ' . Yii::t('app', 'Jenis Perpustakaan')]
                                    ])->label(false); ?>

                                </div>
                                <div class="padding0 col-sm-9"><b class="hint-idt"></b></div>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="form-horizontal"> <!-- hidden="hidden" -->
                <table class="table" style="table-layout: fixed;">
                    <thead>
                    <tr>
                        <td class="col-sm-3"><label class="control-label"><?= Yii::t('app', 'Gambar Logo') ?></label>
                        </td>
                        <td>
                            <div class="col-sm-9" style="padding: 0;">
                                <img
                                    src="<?=  Yii::$app->urlManager->createUrl('/../uploaded_files/aplikasi/logo_perpusnas_2015.png')."?timestamp=" . rand(); ?>"
                                    width="100" height="100" class="img-thumbnail"/>
                                <br>
                                
                                <?= FileInput::widget([
                                	'model' => $model,
                                	'attribute' => 'logo',
                                	'options' => ['accept' => 'image/png']
                                	]); ?>
                            </div>
                            <div class="padding0 col-sm-9"><b class="hint-uang"></b></div>
                        </td>
                    </tr>
                    </thead>
                </table>
            </div>

            <div class="form-horizontal"> <!-- hidden="hidden" -->
                <table class="table" style="table-layout: fixed;">
                    <thead>
                    <tr>
                        <td class="col-sm-3"><label class="control-label"><?= Yii::t('app', 'Gambar KOP') ?></label>
                        </td>
                        <td>
                            <div class="col-sm-9" style="padding: 0;">
                                <img src="<?=  Yii::$app->urlManager->createUrl('/../uploaded_files/aplikasi/kop.png')."?timestamp=" . rand(); ?>"
                                     width="100%" height="auto"/>
                                <br>
                                <?= FileInput::widget([
                                	'model' => $model,
                                	'attribute' => 'kop',
                                	'options' => ['accept' => 'image/png']
                                	]); ?>

                            </div>

                            <div class="padding0 col-sm-9"><b class="hint-uang"></b></div>
                        </td>
                    </tr>
                    </thead>
                </table>
            </div>


            <div class="form-horizontal"> <!-- hidden="hidden" -->
                <table class="table" style="table-layout: fixed;">
                    <thead>
                    <tr>
                        <td class="col-sm-3"><label
                                class="control-label"><?= Yii::t('app', 'Gunakan kop di laporan') ?></label></td>
                        <td>
                            <div class="col-sm-4" style="padding: 0;margin-left: 13px;">

                                <?php echo $form->field($model, 'IsUseKop')->checkbox(array('label' => yii::t('app','Ya / Tidak')))->label(false); ?>
                            </div>
                            <div class="padding0 col-sm-9"><b class="hint-uang"></b></div>
                        </td>
                    </tr>
                    </thead>
                </table>
            </div>


            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>




