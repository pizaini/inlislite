

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
                        'label'=>Yii::t('app', 'Showing :'),
                        'labelOptions'=>[
                            'class'=>'col-sm-4 control-label',
                            'style'=>[
                                'width'=> '75px',
                                'margin'=> '0px',
                                'padding'=> '0px',
                            ]

                        ],
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
                    $rda=($model->IsRDA == null) ? '0' : $model->IsRDA;
                    return Html::a('<span class="glyphicon glyphicon-check"></span> '.Yii::t('app','Choose'), '#', [
                                  'title' => Yii::t('app', 'Choose'), 
                                  //'data-toggle' => 'tooltip',
                                  'data-dismiss'=>'modal',
                                  'class' => 'btn btn-primary btn-sm',
                                  'onClick' => 'js:sendCatalog('.$id.','.$workshetid.','.$rda.');'
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
                'label'=>yii::t('app','Judul'),
                'format' => 'raw',
            ],
            [
              'header'=>yii::t('app','Pengarang'),
              'attribute'=>'Author'
            ],
            // 'Author',
            //'Edition',
            [
              'header'=>yii::t('app','Penerbit'),
              'attribute'=>'Publishment'
            ],
            // 'Publishment',
            //'PhysicalDescription',
            [
              'header'=>yii::t('app','Subyek'),
              'attribute'=>'Subject'
            ],
            // 'Subject',
            [
              'header'=>yii::t('app','Nomor Panggil'),
              'attribute'=>'CallNumber'
            ],
            // 'CallNumber',
            /*[
                'attribute'=>'JumlahKontenDigital',
                'value'=>function($model) {
                    return $model->getCatalogfiles()->count();
                },
                'contentOptions'=>['style'=>'width: 150px;text-align:right;'],
            ],*/
            [
                'attribute'=>'Eksemplar',
                'value'=>function($model) {
                    return $model->getCollections()->count();
                },
                'contentOptions'=>['style'=>'width: 150px;text-align:right;'],
            ]
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
<input type="hidden" id="hdnFor" value="<?=$for?>">
<input type="hidden" id="hdnAjaxUrlPilihJujdul" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/pilih-judul-proses"])?>">
<script type="text/javascript">
sendCatalog = function(id,workshetid,rda) {
  $.ajax({
      type     :"POST",
      cache    : false,
      url  : $("#hdnAjaxUrlPilihJujdul").val()+"?for="+$("#hdnFor").val()+"&rda="+$("#hdnRda").val(),
      data: {id:id,workshetid:workshetid,rda:rda},
      success  : function(response) {
          $("#catalogs-worksheet_id").val(workshetid).trigger("change");
          $("#entryBibliografi").html(response);
          if($("#hdnFor").val()=='coll')
          {
              $("#entryBibliografiPanel").removeClass().addClass("disabled");
              $("#hdnPilihJudul").val(id);
              $("#catalogs-worksheet_id").attr('disabled', 'disabled');
          }

          var oldtext = $(".content-wrapper .content-header h1").html();
          var newtext = oldtext;
          if(rda=='1' && oldtext.indexOf("(RDA)") == -1)
          {
            $("#hdnRda").val("1");
            newtext = oldtext+" (RDA)";
            $(".rdainput").show();
          }

          if(rda=='0' && oldtext.indexOf("(RDA)") != -1)
          {
            $("#hdnRda").val("0");
            newtext = oldtext.replace("(RDA)","");
            $(".rdainput").hide();
          }


          $(".content-wrapper .content-header h1").html(newtext);
          $(".content-wrapper .content-header ul.breadcrumb li.active").html(newtext);
          document.title = newtext;
      }
  });
}
</script>
