<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\LocationLibrarySearch $searchModel
 */

?>

<div class="loan-histori">
    <center><span style="font-weight: bold"><?= yii::t('app','Daftar Koleksi Pernah di Pinjam (10 Terakhir)')?></span></center>
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $model,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
            
            [
                'attribute'=>'collection.NomorBarcode',
                'label'=>yii::t('app','Nomor Barcode')
            ],
            [
                'attribute'=>'collection.catalog.Title',
                'label'=>yii::t('app','Judul')
            ],
            [
                'attribute'=>'collection.catalog.DeweyNo',
                'label'=>yii::t('app','Kelas')
            ],
            [
                'value'=>function ($data) {
                    return \common\components\Helpers::DateTimeToViewFormat($data->LoanDate);
                },
                'label'=>yii::t('app','Tgl.Pinjam'),
                'hAlign' => GridView::ALIGN_CENTER
            ],
            [
                'value'=>function ($data) {
                    return \common\components\Helpers::DateTimeToViewFormat($data->ActualReturn);
                },
                'label'=>yii::t('app','Tgl.Dikembalikan'),
                'hAlign' => GridView::ALIGN_CENTER
            ],
            //'LoanDate:date',
            //'ActualReturn',
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,
        'containerOptions'=>['style'=>'font-size:small'],
        
    ]); Pjax::end(); ?>
    
    <?php
    //var_dump($modelCountCategory);
    //$posts = $modelCountCategory->getModels();
    //var_dump($posts);
     ?>
    <center><span style="font-weight: bold"><?= yii::t('app','Histori Kategori Koleksi Yang Sering di Pinjam')?></span></center>
    <?php echo GridView::widget([
        'dataProvider' => $modelCountCategory,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute'=>'Code','header'=>yii::t('app','Kode')],
            ['attribute'=>'Name','header'=>yii::t('app','Nama')],
            ['attribute'=>'Jumlah','header'=>yii::t('app','Jumlah'),'hAlign' => GridView::ALIGN_CENTER,],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        //'floatHeader'=>false,

        
    ]); ?>
</div>


