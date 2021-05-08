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
        'pjax'=>true,
        'pjaxSettings' => [
            'options' => [
                'enablePushState' => false,
            ],
        ],
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
                 'group'=>true,
                
             ],
             [
                //'attribute'=>'user.username',
                'header'=>'Catatan Perubahan',
                'format' => 'raw',
                'value'=>  function($model){
                    $pieces = preg_split('/(?=[A-Z])/',$model->field_name);
                    $fieldname = implode(" ", $pieces);
                    return '<span style=color:blue><b>'.Yii::t('app', trim($fieldname)) .'</b></span> : '. $model->old_value . ' -> <b>' . $model->new_value.'</b>';
                }
             ],
            
        ],
        
    ]);  ?>

</div>

