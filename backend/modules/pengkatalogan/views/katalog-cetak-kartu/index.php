<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\CollectionSearch $searchModel
 */

$this->title = Yii::t('app', 'Cetak Kartu Katalog');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengkatalogan'), 'url' => Url::to(['/pengkatalogan'])];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="form-group" style="padding-bottom:30px">
  <div class="col-md-3">
      <?php 
  echo Select2::widget([
    'id' => 'cbActioncheckbox',
    'name' => 'cbActioncheckbox',
    'data' => ArrayHelper::map(\common\models\Cardformats::find()->all(),'ID','Name'),
    'size'=>'sm',
]);

  ?>
  </div>
   <div id="actionDropdown"></div>
   <div class="col-md-4">
    <?php 
    echo Html::button('<i class="glyphicon glyphicon-check"></i> Cetak Kartu Katalog', [
                        'id'=>'btnCheckprocess',
                        'class' => 'btn btn-primary btn-sm', 
                        'title' => 'Cetak Kartu Katalog', 
                        //'data-toggle' => 'tooltip'
                    ]);
    ?>
    </div>
</div>
<div class="catalogs-salin-index">
    	 <?php 
         $dataProvider->key ='BIBID';
         Pjax::begin(['id' => 'myGridview']); 
    	 echo GridView::widget([
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
        'filterSelector' => 'select[name="per-page"]',
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class'       => '\kartik\grid\CheckboxColumn',
                'pageSummary' => true,
                'rowSelectedClass' => GridView::TYPE_INFO,
                'name' => 'bibid',
                'vAlign' => GridView::ALIGN_TOP
            ],
            ['class' => 'yii\grid\SerialColumn'],
            'BIBID', 
            'Title',
            'Author',
            'Publishment',
            'CallNumber',
        ],
        //'summary'=>'',
        'responsive'=>true,
        'containerOptions'=>['style'=>'font-size:12px'],
        'hover'=>true,
        'condensed'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> ', ['create'], ['class' => 'btn btn-success','title' => Yii::t('app','Add'),'data-toggle' => 'tooltip',]),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>
 </div>
<input type="hidden" id="hdnUrlProses" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog-cetak-kartu/proses"])?>">
 <?php 

    $this->registerJs(' 

    $(document).ready(function(){
    $(\'#btnCheckprocess\').click(function(){
        var CardFormatId = $(\'#cbActioncheckbox\').val();
        var bibids = $(\'#myGrid\').yiiGridView(\'getSelectedRows\');
        var arrayId = {bibids} 
        var bibids = jQuery.param(arrayId);
        var url =  $(\'#hdnUrlProses\').val();
        window.location.href = url+\'?idcardformat=\'+CardFormatId+\'&\'+bibids;

    });
    });', \yii\web\View::POS_READY);

?>
