<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

?>


<div class="table-responsive">
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterSelector' => 'select[name="per-page"]',
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'user.username',
                'header'=>'Oleh',
                'format' => 'raw',
                'value'=>  function($model){
                    return $model->user->username .' ('.$model->user->Fullname.')';
                },
                'group'=>true,  // enable grouping,
                //'groupedRow'=>true,                    // move grouped column to a single grouped row
             ],
             [
                'attribute'=>'date',
                'header'=>'Tanggal',
                'value'=>  function($model){
                    return \common\components\Helpers::DateTimeIndonesiaFormat($model->date);
                },
                'group'=>true,
                
             ],
             [
                //'attribute'=>'user.username',
                'header'=>'Catatan',
                'format' => 'raw',
                'value'=>  function($model){
                    return '<b>'. $model->field_name .'</b>' .' : '. $model->old_value . ' Menjadi ' . $model->new_value;
                }
             ],
            
        ],
        
    ]);  ?>

</div>

