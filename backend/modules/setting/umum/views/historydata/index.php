<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\builder\Form;
/* @var $this yii\web\View */
/* @var $searchModel common\models\HistorydataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Historydatas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="historydata-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'ID',
            'Action',
            //'TableName',
	    [
	     'attribute'=>'TableName',
	     //'format'=>'date',
	     'label'=>Yii::t('app','Nama Tabel')
	    ],
            'IDRef',
            [
	     'attribute'=>'CreateDate',
	     'format'=>'date',
	     'label'=>Yii::t('app','Tanggal')
	    ],
            //'Note:ntext',
            [
	           'attribute'=>'Note',
		   'format'=>'raw',
		   'contentOptions'=>['style'=>'max-width: 175px;word-wrap: break-word;'],
		   'content'=> function($model){
				return '<p>'.$model->Note.'</p>';
			},
	            'label'=>Yii::t('app','Catatan')
            ],
           // 'CreateBy',

            //'CreateDate:date',
            // 'CreateTerminal',
            // 'UpdateBy',
            // 'UpdateDate',
            // 'UpdateTerminal',
            // 'Member_id',

        ],
    ]); ?>
<?php Pjax::end(); ?></div>
