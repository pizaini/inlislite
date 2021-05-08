<?php 
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

echo Select2::widget([
'id' => 'cbActionDetail2',
'name' => 'cbActionDetail2',
'data'=>ArrayHelper::map($model,'ID','Name'),
'size' => 'sm',
]);

?>


