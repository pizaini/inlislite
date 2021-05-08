<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\CollectionSearch $searchModel
 */

$this->title = Yii::t('app', 'Export Data Tag Katalog');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengkatalogan'), 'url' => Url::to(['/pengkatalogan'])];
$this->params['breadcrumbs'][] = $this->title;

$datacheckbox = array(
            'EXPORT'=>yii::t('app','Export Data Terpilih'),
            'EXPORTALL'=>yii::t('app','Export Semua Data'));
?>
<div class="catalogs-salin-index">
<?php $urlAdvanceSearch = '_searchAdvanced'; echo $this->render($urlAdvanceSearch, ['model' => $searchModel,'rules' => $rules]); ?>
        <!-- <div class="col-md-1">
            <h6 class="panel-title"> Aksi </h6>
        </div> -->
        <div class="form-group" style="padding-bottom:30px">
  <label for="inputType" class="col-md-1 control-label control-label-sm"><?= yii::t('app','Aksi')?></label>
  <div class="col-md-3">
      <?php 
  echo Select2::widget([
    'id' => 'cbActioncheckbox',
    'name' => 'cbActioncheckbox',
    'data' => $datacheckbox,
    'size'=>'sm',
    /*'pluginOptions' => [
        'allowClear' => true
    ],*/
    //'theme' => Select2::THEME_BOOTSTRAP,
    'pluginEvents' => [
        "select2:select" => 'function() { 
            var id = $("#cbActioncheckbox").val();
            if(id == "EXPORT"){
                $("#btnExportAll").hide();
                $("#btnExport").show();
            }else{
                $("#btnExport").hide();
                $("#btnExportAll").show();
            }
        }',
    ]
]);

  ?>
  </div>
   <div id="actionDropdown"></div>
   <div class="col-md-1">
    <?php 
    
    echo Html::button('<i class="glyphicon glyphicon-check"></i> Download', [
                        'type'=>'submit',
                        'id'=>'btnExport',
                        'class' => 'btn btn-primary btn-sm', 
                        'title' => 'Proses', 
                    ]);
    echo Html::button('<i class="glyphicon glyphicon-check"></i> Download', [
                        'type'=>'submit',
                        'id'=>'btnExportAll',
                        'class' => 'btn btn-primary btn-sm', 
                        'title' => 'Proses', 
                        'style' => 'display : none'
                    ]);
    ?>
    </div>

    <div class="col-md-1" style="padding-left: 5px;">
    
    </div>
</div>
        
    	 <?php Pjax::begin(['id' => 'myGridview']); 
    	 $columns=array();
         $columns[] = [
                'class'       => '\kartik\grid\CheckboxColumn',
                'pageSummary' => true,
                'rowSelectedClass' => GridView::TYPE_INFO,
                'name' => 'cek',
                'checkboxOptions' => function ($searchModel, $key, $index, $column) {
                    return [
                        'value' => $searchModel->ID
                    ];
                },
                'vAlign' => GridView::ALIGN_TOP,
                // 'hidden'=> ($for=='karantina') ? true : false
                'hidden'=> false
            ];
    	 $columns[] = ['class' => 'yii\grid\SerialColumn'];
         $columns[] = ['format' => 'raw', 'attribute' => 'Title'];
    	 $columns[] = 'Author';
    	 $columns[] = 'Publisher';
    	 $columns[] = 'PublishYear';
    	 // for ($i=1; $i < 1000 ; $i++) { 
      //       $tag =  str_pad($i, 3, '0', STR_PAD_LEFT);
      //       $columns[] = "t".$tag;
      //    }
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
        'pager' => [
            'firstPageLabel' => Yii::t('app','Awal'),
            'lastPageLabel'  => Yii::t('app','Akhir')
        ],
        'filterSelector' => 'select[name="per-page"]',
        //'filterModel' => $searchModel,
        'columns' => $columns,
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

<input type="hidden" id="hdnUrlProsesExport" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog-export-data-tag/download-export"])?>">
<input type="hidden" id="hdnUrlProsesExportAll" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog-export-data-tag/download-export-all"])?>">

 <?php 

    $this->registerJs(' 
        $(document).ready(function(){
            $(\'#btnExportAll\').click(function(){
                var CekActionDetail = $(\'#cbActioncheckbox\').val();
                var ids = $(\'#myGrid\').yiiGridView(\'getSelectedRows\');
                
                // var arrayId = {ids} 
                // var ids = jQuery.param(arrayId);
                var url =  $(\'#hdnUrlProsesExportAll\').val();
                window.location.href = url+\'?actionid=\'+CekActionDetail;
                
            });

            $(\'#btnExport\').click(function(){
                var CekAction = $(\'#cbActioncheckbox\').val();
                var CekActionDetail = $(\'#cbActioncheckbox\').val();
                var ids = $(\'#myGrid\').yiiGridView(\'getSelectedRows\');
                if(ids.length == 0){
                    alertSwal(\'Harap pilih data katalog.\',\'error\',\'2000\');
                    return;
                }

                if(CekAction === \'EXPORT\'){
                    var arrayId = {ids} 
                    var ids = jQuery.param(arrayId);
                    var url =  $(\'#hdnUrlProsesExport\').val();
                    window.location.href = url+\'?actionid=\'+CekActionDetail+\'&\'+ids;
                
                }
            });
        });
    ', \yii\web\View::POS_READY);

?>