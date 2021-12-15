

<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use kartik\grid\GridView;



/**
 * @var yii\web\View $this
 * @var common\models\Collections $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div id="msgerror"></div>

 
 <?php 

echo $this->render('_searchModalAdvanced', ['model' => $searchModel,'rules' => $rules,'for'=>$for]);

 Pjax::begin(['id' => 'myGridviewPilihJudul']); 
 echo GridView::widget([
        'id'=>'myGridPilihJudul',
        'pjax'=>true,
        'pjaxSettings' => [
            'options' => [
                'enablePushState' => false,
            ],
        ],
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
        //'filterModel' => $searchModel,
        'columns' => [
             [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'width: 50px;'],
                'template' => '<span style="display:inline">{choose}</span>',
                'buttons' => [
                    'choose' => function ($url, $model)  {
                    $id= $model->ID;
                    $workshetid= $model->Worksheet_id;

                    return Html::a('<span class="glyphicon glyphicon-check"></span> '.Yii::t('app','Pilih'), '#', [
                                  'title' => Yii::t('app', 'Pilih'), 
                                  //'data-toggle' => 'tooltip',
                                  'data-dismiss'=>'modal',
                                  'class' => 'btn btn-primary btn-sm',
                                  'onClick' => 'js:sendCatalog('.$id.','.$workshetid.');'
                                ]);},

                ],
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
            //'Edition',
            'Publishment',
            //'PhysicalDescription',
            'Subject',
            'CallNumber',
            [
                'attribute'=>'KontenDigital',
                'label'=>yii::t('app','Konten Digital'),
                'value'=>function($model) {
                    return $model->getCatalogfiles()->count();
                },
                'contentOptions'=>['style'=>'width: 150px;text-align:right;'],
            ],
/*            [
                'attribute'=>'Eksemplar',
                'value'=>function($model) {
                    return $model->getCollections()->count();
                },
                'contentOptions'=>['style'=>'width: 150px;text-align:right;'],
            ]*/
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
            /*'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),*/
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>
<input type="hidden" id="hdnAjaxUrlPilihJujdul" value="<?=Yii::$app->urlManager->createUrl(["setting/opac/koleksi-unggulan/tambah"])?>">
<script type="text/javascript">
sendCatalog = function(id,workshetid) {
  $.ajax({
      type     :"POST",
      cache    : false,
      url  : $("#hdnAjaxUrlPilihJujdul").val()+"?id="+id,
      data: {id:id,workshetid:workshetid},

  });
}
</script>