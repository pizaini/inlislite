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

$this->title = Yii::t('app', 'Kategori Koleksi');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'digitalcollection'), 'url' => Url::to(['/setting/digitalcollection'])];
$this->params['breadcrumbs'][] = 'Koleksi Unggulan';
?>

<div class="settingparameters-form">
  <div class="form-group">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>

    <?= $form->field($model,'Value1')->radioList(['TRUE'=>Yii::t('app', 'Ya'),'FALSE'=>Yii::t('app', 'Tidak')], ['inline'=>true])->label(Yii::t('app', 'Tampilkan Koleksi Terbaru'))?>

    <?= $form->field($model,'Value2')->textInput(['style'=>'width:150px'], ['inline'=>true])->label(Yii::t('app', 'Maksimal Jumlah Koleksi')) ?>

    

    <?php //echo $form->field($model,'Value4')->radioList(['Simple'=>Yii::t('app', 'Simple'),'Advance'=>Yii::t('app', 'Advance')], ['inline'=>true])->label(Yii::t('app', 'Entry Form Collection'))?>
  
    &nbsp;<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>


</div>

<script type="text/javascript">
    function catalogs() {        
        //var id = $("#catalogID").val();
        $.ajax({
          type     :"POST",
          cache    : false,
          url  : "<?=Yii::$app->urlManager->createUrl('setting/digitalcollection/koleksi-unggulan/pilih-judul')?>",
        success  : function(response) {
            $("#catalogs").html(response);
        }

        });


      }


</script>

<div class="collectioncategorys-index">
    <div class="page-header">
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Collectioncategorys',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

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

            //'{toggleData}',
            //'{export}',
        ],
        'filterSelector' => 'select[name="per-page"]',
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
  
        	'title',
            'author',
            'PublishYear',
            'worksheet_name',
            //'IsDelete',
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'max-width: 25px;'],
                'template' => '<span style="display:inline"> {delete}</span>',
                'buttons' => [
                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Hapus'), Yii::$app->urlManager->createUrl(['setting/digitalcollection/koleksi-unggulan/delete','id' => $model->ID,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Delete'),
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'data' => [
                                                        'confirm' => Yii::t('yii','Apakah anda yakin ingin menghapus item ini?'),
                                                        'method' => 'post',
                                                    ],
                                                  ]);},


                ],

            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>false,
        'floatHeader'=>false,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.yii::t('app','Koleksi Unggulan').' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Pilih Judul'), ['pilih-judul'], ['class' => 'btn btn-primary','onClick' => 'catalogs()' ,'data-toggle'=>"modal",
                                                    'data-target'=>"#pilihsalin-modal",
                                                    'data-title'=>"Pilih Judul",]),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>
    <?php
Modal::begin([
    'id' => 'pilihsalin-modal',
    'size'=>'modal-lg',
    'header' => '<h4 class="modal-title">'.yii::t('app','Daftar Katalog').'</h4>',
    'options' => [
      'width' => '900px',
  ],
]);
 
echo" <div id=\"catalogs\">

  </div> ";
Modal::end();



$this->registerJs(
    '$("document").ready(function(){
        $("#search").on("pjax:end", function() {
            $.pjax.reload({container:"#myGridview"});  //Reload GridView
        });
    });'
);
?>
</div>
