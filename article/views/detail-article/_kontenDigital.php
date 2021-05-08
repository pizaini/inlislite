<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\CollectionSearchKardeks;

	echo GridView::widget([
		'id'=>'GridKontenDigitalArticle',
		'pjax'=>false,
		'dataProvider' => $dataProviderKontenDigital,
		'toolbar'=> [
			['content'=>
				\common\components\PageSize::widget(
					[
						'template'=> '{label} <div class="col-sm-8" style="width:175px">{list}</div>',
						'label'=>Yii::t('app', 'Showing :'),
						'labelOptions'=>[
							'class'=>'col-sm-4 control-label',
							'style'=>[
								'width'=> '75px',
								'margin'=> '0px',
								'padding'=> '0px',
							]

						],
						// gridview dengan if
						'sizes'=>(Yii::$app->config->get('language') != 'en' ? Yii::$app->params['pageSize'] : Yii::$app->params['pageSize_ing']),
						'options'=>[
							'id'=>'aa',
							'class'=>'form-control'
						]
					]
				)

			],

			//'{toggleData}',
			'{export}',
		],

		'columns' => [
			[
				'class'       => '\kartik\grid\CheckboxColumn',
				'pageSummary' => true,
				'rowSelectedClass' => GridView::TYPE_INFO,
				'name' => 'cek',
				'checkboxOptions' => function ($searchModelArticles, $key, $index, $column) {
					return [
						'value' => $searchModelArticles->ID
					];
				},
				'vAlign' => GridView::ALIGN_TOP
			],
			['class' => 'yii\grid\SerialColumn'],
			[
				//'label'=>'Nama',
				'format'=>'raw',
				'attribute'=>'FileURL',
			],
			[
				//'label'=>'Nama',
				'format'=>'raw',
				'attribute'=>'FileFlash',
			],
			//'FileFlash',
			[
				'attribute'=>'CreateDate',
				'format' => 'date',
			],
			[
				'attribute'=>'IsPublish',
				'format' => 'raw',
				'value'=>function($data){
					if($data['IsPublish'] == 1){
						return '<span class="label label-success">Publik</span>';
					}else if($data['IsPublish'] == 2){
						return '<span class="label label-primary">Hanya untuk anggota</span>';
					}elseif($data['IsPublish'] == 0){
						return '<span class="label label-warning">Tidak dipublikasikan</span>';
					}else{
						return '<span class="label label-default">Tidak diketahui</span>';
					}


				}
			],

		],

		'responsive'=>true,
		'containerOptions'=>['style'=>'font-size:12px'],
		'hover'=>true,
		'condensed'=>true,
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> Daftar Konten Digital </h3>',
			'type'=>'info',
			'before'=>'',
			'showFooter'=>false,
			'showHeader'=>false
		],
	]);
	?>