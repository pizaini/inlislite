<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\CollectionSearchKardeks;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\QuarantinedCollectionSearch $searchModel
 */


?>
    <?php /*Pjax::begin(['id' => 'myGridview2']);*/ echo GridView::widget([
        'id'=>'myGrid2',
        /*'pjax'=>true,*/
        'dataProvider' => $dataProvider,
        'filterSelector' => 'select[name="per-page"]',
        //'filterModel' => $searchModel,
        'columns' => [
            [   
                'format' => 'raw',
                'options' => ['style'=>'width:1%'],
                'value' => function($model) {
                    return '
                    <a role="button" data-toggle="collapse" href="#'.$model->Edisi_id.'" aria-expanded="false" aria-controls="'.$model->Edisi_id.'"
                    <span class="glyphicon glyphicon-expand" style="font-size:16px; padding-left: 10px;  padding-right: 10px"></span> 
                    </a>';
                }

            ],
            ['class' => 'yii\grid\SerialColumn'],
            'EDISISERIAL', 
            [
                'attribute'=>'TANGGAL_TERBIT_EDISI_SERIAL',
                //'value'=>'source.Name',
                'format' => 'date',
            ],
            'Eksemplar',
        ],
        'afterRow' => function($model, $key, $index) {
            $searchModel = new CollectionSearchKardeks;
            $params['CatalogId']= $model->Catalog_id;
            $params['EdisiSerial']= $model->EDISISERIAL;
            $dataProvider = $searchModel->search3($params);
            $data = Html::tag('tr', 
                        Html::tag('td',
                            Yii::$app->controller->renderPartial('_subEksemplar',[
                                'searchModel'=>$searchModel,
                                'dataProvider'=>$dataProvider,
                            ])
                            ,['colspan'=>5]),['class'=>'collapse','id'=>$model->Edisi_id]);
            return $data;
            
        },
        'summary'=>false,
        'responsive'=>true,
        'containerOptions'=>['style'=>'font-size:12px'],
        'hover'=>true,
        'condensed'=>true,
        'headerRowOptions' => ['class' => GridView::TYPE_WARNING],
        'options'=>['font-size'=>'11px'],
    ]); /*Pjax::end();*/ ?>
