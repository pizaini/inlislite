<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\MasterKependudukanSearch $searchModel
 */


Modal::begin([
    'id' => 'modal-penduduk-2',
    'header' => '<h4 class="modal-title"> Data Penduduk</h4>',
]);
 
?>

<div class="table-responsive">
 <?php Pjax::begin([
  'id'=>'kependudukan-index-pjax',
]); ?>
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'responsive'=>true,
        //'hover'=>true,
        'filterSelector' => 'select[name="per-page"]',
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nomorkk',
            'nik',
            'namalengkap',
            'al1',
             'alamat', 
             'lhrtempat', 
             'lhrtanggal', 
             'ttl', 
             'umur', 
             'jk', 
             'jenis', 
             'status', 
             'sts', 
             'agama', 
             'pendidikan', 
             'pekerjaan', 
        ],
        
    ]); Pjax::end(); ?>

</div>

<?php

Modal::end(); ?>

