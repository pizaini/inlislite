<?php 
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

echo Select2::widget([
'id' => 'collections-location_id',
'name' => 'Collections[Location_id]',
'data'=>ArrayHelper::map($model,'ID','Name'),
'pluginOptions' => [
	'allowClear' => true,
	],
]);

?>


