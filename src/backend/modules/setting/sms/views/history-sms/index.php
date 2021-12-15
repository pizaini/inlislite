<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\CollectioncategorySearch $searchModel
 */

$this->title = Yii::t('app', 'Riwayat Pengiriman SMS');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'SMS'), 'url' => Url::to(['/setting/sms'])];
$this->params['breadcrumbs'][] = Yii::t('app', 'Riwayat Pengiriman SMS');
?>


<div class="collectioncategorys-index">
    <div class="page-header">
    </div>

    <?php Pjax::begin(['id' => 'myGridview']);; echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        // set your toolbar
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

            '{toggleData}',
            '{export}',
        ],
        'filterSelector' => 'select[name="per-page"]',
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
  
        	'recieverID',
            'phoneNumber',
            'Text',
            'CreateBy',
            'CreateDate',
            'CreateTerminal',
            'UpdateBy',
            'UpdateDate',
            'UpdateTerminal',
            //'IsDelete',
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>false,
        'floatHeader'=>false,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app', 'Riwayat Pengiriman SMS').' </h3>',
            'type'=>'info',
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); 

$this->registerJs(
    '$("document").ready(function(){
        $("#search").on("pjax:end", function() {
            $.pjax.reload({container:"#myGridview"});  //Reload GridView
        });
    });'
);
?>
</div>
