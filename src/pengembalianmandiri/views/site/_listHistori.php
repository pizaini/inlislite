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
    <center><span style="font-weight: bold">Daftar Koleksi Pernah DiPinjam (10 Terakhir)</span></center>
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $model,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
            'collection.NomorBarcode',
            [
                'attribute'=>'collection.catalog.Title',
                'header'=>'Judul'
            ],
            [
                'attribute'=>'collection.catalog.DeweyNo',
                'header'=>'No.Klas'
            ],
            [
                'value'=>function ($data) {
                    return \common\components\Helpers::DateTimeToViewFormat($data->LoanDate);
                },
                'header'=>'Tgl. Pinjam',
                'hAlign' => GridView::ALIGN_CENTER
            ],
            [
                'value'=>function ($data) {
                    return \common\components\Helpers::DateTimeToViewFormat($data->ActualReturn);
                },
                'header'=>'Tgl. Dikembalikan',
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
    <center><span style="font-weight: bold">Histori Kategori Koleksi Yang Sering DiPinjam</span></center>
    <?php echo GridView::widget([
        'dataProvider' => $modelCountCategory,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute'=>'Code','header'=>'Kode'],
            ['attribute'=>'Name','header'=>'Nama',],
            ['attribute'=>'Jumlah','header'=>'Jumlah','hAlign' => GridView::ALIGN_CENTER,],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        //'floatHeader'=>false,

        
    ]); ?>
</div>


