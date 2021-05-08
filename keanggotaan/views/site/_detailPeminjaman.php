<?php

use yii\helpers\Html;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Books';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collectionloans-item-index">

    <? Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'Collection_id',
            //'description:ntext',
            //'isbn',

           /* [
            	'attribute'=>'author_id',
            	'value'=>'author.name'
            ],*/
            
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <? Pjax::end();?>
</div>
