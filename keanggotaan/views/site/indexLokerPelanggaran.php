<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;


use common\models\MasterJenisIdentitas;
use common\models\MasterUangJaminan;
use common\models\MasterLoker;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\LockersSearch $searchModel
 */

$this->title = Yii::t('app', $title);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lockers-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // echo Html::a('Create Lockers', ['create'], ['class' => 'btn btn-success']) ;  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
		'toolbar'=> [
            ['content'=>
                 \common\components\PageSize::widget(
                    [
                        'template'=> '{label} <div class="col-sm-8" style="width:175px">{list}</div>',
                        'label'=>'Tampilkan',
                        'labelOptions'=>[
                            'class'=>'col-sm-4 control-label',
                            'style'=>[
                                'width'=> '75px',
                                'margin'=> '0px',
                                'padding'=> '0px',
                            ]

                        ],
                        'sizes'=>Yii::$app->params['pageSize'],
                        'options'=>[
                            'id'=>'aa',
                            'class'=>'form-control'
                        ]
                    ]
                 )

            ],

            '{toggleData}',
            '{export}',
        ],
        'filterSelector' => 'select[name="per-page"]',
        //'filterModel' => $searchModel,
        'columns' => [
		
            ['class' => 'yii\grid\SerialColumn'],
             ['attribute'=>'tanggal_pinjam',
              'label'=>Yii::t('app', 'Tanggal Pinjam'),
             'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']
             ], 
             ['attribute'=>'tanggal_kembali',
              'label'=>Yii::t('app', 'Tanggal Kembali'),
             'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
            'No_pinjaman',
            //'no_member',
            [
                'attribute'=>'jenis_jaminan',
                'label'=>Yii::t('app', 'Jenis Jaminan'),
                'value'=> function($model){
                    // $jenisID = MasterLoker::findOne(['ID'=>$model->loker_id]);
                    return $model->jenis_jaminan.' - '.$model->uangJaminan['Name'].' '.$model->jenisIdentitas['Nama'];
                },
            ],			

			
			[
                'attribute'=>'loker_id',
                'label'=>Yii::t('app', 'Loker'),
                'value'=>'loker.Name',
            ],
            [
                'header'=>Yii::t('app', 'Lokasi Perpustakaan'),
                'value'=>'loker.locations.locationLibrary.Name',
            ],
			
           //'loker_id',
           // ['attribute'=>'loker_id','value'=> function($model){
                // $jenisID = MasterLoker::findOne(['ID'=>$model->loker_id]);
                // return $jenisID['Name'];
            // },'label'=>'Loker'], 
          
          /* ['attribute'=>'tanggal_kembali','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']], */
           
		   //'id_pelanggaran_locker', 
		   
			[
                'attribute'=>'id_pelanggaran_locker',
                'value'=>'pelanggaran.jenis_pelanggaran',
            ],
			

            
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,

        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            // 'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success']),'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
