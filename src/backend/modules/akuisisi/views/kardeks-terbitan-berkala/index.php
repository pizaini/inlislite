<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\CollectionSearchKardeks;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\QuarantinedCollectionSearch $searchModel
 */

$this->title = Yii::t('app', 'Kardeks Terbitan Berkala');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Akuisisi'), 'url' => Url::to(['/akuisisi'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quarantined-collections-index">
<?php  echo $this->render('_searchAdvanced', ['model' => $searchModel,'rules' => $rules]); ?>
    <?php Pjax::begin(['id' => 'myGridview']); echo GridView::widget([
        'id'=>'myGrid',
        'pjax'=>true,
        'dataProvider' => $dataProvider,
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
                        'sizes'=>Yii::$app->params['pageSize'],
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
        'pager' => [
            'firstPageLabel' => Yii::t('app','Awal'),
            'lastPageLabel'  => Yii::t('app','Akhir')
        ],
        'filterSelector' => 'select[name="per-page"]',
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class'=>'kartik\grid\ExpandRowColumn',
                'value'=>function ($model, $key, $index,$column) { 
                            return GridView::ROW_COLLAPSED;
                },
                'detail'=>function ($model, $key, $index,$column){
                        $searchModel = new CollectionSearchKardeks;
                        $params['CatalogId']= $model->ID;
                        $dataProvider = $searchModel->search2($params);

                        return Yii::$app->controller->renderPartial('_subEdisiserial',[
                                    'searchModel'=>$searchModel,
                                    'dataProvider'=>$dataProvider,
                                ]);

                }
            ],
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'BIBID',
                //'value'=>'source.Name',
                'format' => 'raw',
            ],
            [
                'attribute'=>'Title',
                //'value'=>'source.Name',
                'format' => 'raw',
            ],
            'Author', 
            'PublishLocation', 
            'Publisher', 
            'PublishYear', 
            'JumlahEdisiSerial', 
            'Eksemplar'
        ],
        //'summary'=>'',
        'responsive'=>true,
        'containerOptions'=>['style'=>'font-size:12px'],
        'hover'=>true,
        'condensed'=>true,
        'options'=>['font-size'=>'11px'],
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            /*'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),*/
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
