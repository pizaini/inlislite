<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\CollectionSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="collections-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'NoInduk') ?>

    <?= $form->field($model, 'Currency') ?>

    <?= $form->field($model, 'RFID') ?>

    <?= $form->field($model, 'Price') ?>

    <?php // echo $form->field($model, 'TanggalPengadaan') ?>

    <?php // echo $form->field($model, 'CallNumber') ?>

    <?php // echo $form->field($model, 'IsDelete') ?>

    <?php // echo $form->field($model, 'Branch_id') ?>

    <?php // echo $form->field($model, 'Catalog_id') ?>

    <?php // echo $form->field($model, 'Partner_id') ?>

    <?php // echo $form->field($model, 'Location_id') ?>

    <?php // echo $form->field($model, 'Rule_id') ?>

    <?php // echo $form->field($model, 'Category_id') ?>

    <?php // echo $form->field($model, 'Media_id') ?>

    <?php // echo $form->field($model, 'Source_id') ?>

    <?php // echo $form->field($model, 'GroupingNumber') ?>

    <?php // echo $form->field($model, 'NomorBarcode') ?>

    <?php // echo $form->field($model, 'Status') ?>

    <?php // echo $form->field($model, 'Keterangan_Sumber') ?>

    <?php // echo $form->field($model, 'CreateBy') ?>

    <?php // echo $form->field($model, 'CreateDate') ?>

    <?php // echo $form->field($model, 'CreateTerminal') ?>

    <?php // echo $form->field($model, 'UpdateBy') ?>

    <?php // echo $form->field($model, 'UpdateDate') ?>

    <?php // echo $form->field($model, 'UpdateTerminal') ?>

    <?php // echo $form->field($model, 'IsVerified') ?>

    <?php // echo $form->field($model, 'QUARANTINEDBY') ?>

    <?php // echo $form->field($model, 'QUARANTINEDDATE') ?>

    <?php // echo $form->field($model, 'QUARANTINEDTERMINAL') ?>

    <?php // echo $form->field($model, 'STATUSAKUISISI') ?>

    <?php // echo $form->field($model, 'ISREFERENSI')->checkbox() ?>

    <?php // echo $form->field($model, 'EDISISERIAL') ?>

    <?php // echo $form->field($model, 'NOJILID') ?>

    <?php // echo $form->field($model, 'TANGGAL_TERBIT_EDISI_SERIAL') ?>

    <?php // echo $form->field($model, 'BAHAN_SERTAAN') ?>

    <?php // echo $form->field($model, 'KETERANGAN_LAIN') ?>

    <?php // echo $form->field($model, 'TGLENTRYJILID') ?>

    <?php // echo $form->field($model, 'IDJILID') ?>

    <?php // echo $form->field($model, 'NOMORPANGGILJILID') ?>

    <?php // echo $form->field($model, 'ISOPAC')->checkbox() ?>

    <?php // echo $form->field($model, 'JILIDCREATEBY') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', ' cari'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
