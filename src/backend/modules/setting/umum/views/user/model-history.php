<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\ModelHistorySearch $searchModel
 */

$this->title = Yii::t('app', 'Histori Aktifitas');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modelhistory-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Modelhistory',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
		'toolbar'=> [
            ['content'=>
                 \common\components\PageSize::widget(
                    [
                        'template'=> '{label} <div class="col-sm-8" style="width:175px">{list}</div>',
                        'label'=>Yii::t('app', 'Tampilkan :'),
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
        'filterSelector' => 'select[name="per-page"]',
        'filterModel' => $searchModel,
 'columns' => [
		
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute'=>'date',
                'label' => Yii::t('app','Tanggal'),
                // 'group'=>true,  // enable grouping, (aktifkan jika ingin grid dalam tampilan group)
                'value' => function($model){
                    $time = strtotime($model->date);
                    return date('d-m-Y H:i:s', $time);
                },

                'contentOptions'=>['style'=>'width: 200px;'],
                'filterType'=>GridView::FILTER_DATE,
                'filterWidgetOptions'=>[
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                    ]
                ],  
            ],
            [
                'attribute' => 'type', 
                'label' => Yii::t('app','Aktifitas'),
                // 'group'=>true,  // enable grouping,
                // 'subGroupOf'=>2 ,
                'value'=>function($model)
                {
                    return ($model->type == 0 ? 'Entri' : ($model->type == 1 ? 'Koreksi' : 'Hapus') );
                },
                'contentOptions'=>['style'=>'width: 200px;'],

                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>[0 => "Entri",1 => "Koreksi",2 => "Hapus",], 
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>'Tindakan'],


            ], 
            [
                'attribute' => 'table',
                'label' => Yii::t('app','Tabel Data'),
                // 'group'=>true,  // enable grouping,
                // 'subGroupOf'=>1 
            ],
            [
                'attribute' => 'field_id',
                'label' => Yii::t('app','ID. Record')
            ],
            [
                'attribute' => 'deskripsi',
                'format' => 'raw',
                'value' => function($model)
                {
                    if ($model->type == 0 ) // Entri Baru
                    {
                        $pieces = preg_split('/(?=[A-Z])/',$model->field_name);
                        $fieldname = implode(" ", $pieces);
                        return '<span style=color:blue><b>'.Yii::t('app', trim($fieldname)) .'</b></span> : <b>' . ($model->new_value ? $model->new_value : '(Kosong)' ) .'</b>';
                    }
                    elseif ($model->type == 1) // Edit 
                    {
                        $pieces = preg_split('/(?=[A-Z])/',$model->field_name);
                        $fieldname = implode(" ", $pieces);
                        return '<span style=color:blue><b>'.Yii::t('app', trim($fieldname)) .'</b></span> : '. ($model->old_value ? $model->old_value : '(Kosong)' ) . ' -> <b>' . ($model->new_value ? $model->new_value : '(Kosong)' ) .'</b>';                       
                    } 
                    else 
                    {
                    }
                    

                    
                },
                'label' => Yii::t('app','Deskripsi')
            ],
//            'old_value:ntext', 
//            'new_value:ntext', 
           // 'user_id', 


        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,

        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            // 'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), Yii::$app->request->url, ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
