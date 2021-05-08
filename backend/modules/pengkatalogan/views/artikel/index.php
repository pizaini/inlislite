<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
// use kartik\widgets\Select2;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;

use common\models\Catalogs;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\CollectionSearch $searchModel
 */

$this->title = 'Daftar Artikel';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengkatalogan'), 'url' => Url::to(['/pengkatalogan'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
.modal .modal-dialog {
    min-width: 65%;
   }
.modal .modal-body {
    height:auto;
    max-height:150%;
    overflow:auto;
}

#rekanan-modal-article .modal-dialog {
    min-width: 45%;
}
.standard-error-summary
{
background-color: #faffe1;
padding: 5px;
border:dashed 1px #cccccc;
margin-bottom: 10px;
font-size: 12px;
margin: 10px;
}
</style>

<div class="collections-index">
 <?php  echo $this->render('_searchAdvanced', ['model' => $searchModel,'rules' => $rules]); ?>

 	<?php
		echo Html::a(Yii::t('app', 'Tambah Artikel Lepas'), 'javascript:void(0)', ['id'=>'btnAddArticles','class' => 'btn btn-success btn-sm']);
        echo '&nbsp;' .Html::a(Yii::t('app', 'Tambah Artikel Terbitan Berkala'), 'javascript:void(0)', ['id'=>'btnAddArticlesTerbitan','class' => 'btn btn-primary btn-sm']);
		echo '&nbsp;' . Html::a(Yii::t('app', 'Tambah Konten Digital Artikel'), 'javascript:void(0)', ['id'=>'btnAddDigitalArticles','class' => 'btn btn-warning btn-sm']);
	?>
	<br><br>
    <?php Pjax::begin(['id' => 'myGridviewArticle']); echo GridView::widget([
        'id' => 'GridviewArticles',
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
            // [
            //     'class'       => '\kartik\grid\CheckboxColumn',
            //     'pageSummary' => true,
            //     'rowSelectedClass' => GridView::TYPE_INFO,
            //     'name' => 'cek',
            //     'checkboxOptions' => function ($searchModelArticles, $key, $index, $column) {
            //         return [
            //             'value' => $searchModelArticles->id
            //         ];
            //     },
            //     'vAlign' => GridView::ALIGN_TOP
            // ],
            ['class' => 'yii\grid\SerialColumn'],
            [
                //'label'=>'Nama',
                'format'=>'raw',
                'attribute'=>'title',
                'value' => function($data){
                    $worksheet = \common\models\Worksheets::find()->addSelect(['ISSERIAL','IsBerisiArtikel'])->innerJoin('catalogs', 'catalogs.Worksheet_id = worksheets.ID')->where(['catalogs.ID' => $data->Catalog_id])->one();
                    // echo'<pre>';print_r($worksheet);echo'</pre>';
                    $type = ($worksheet['ISSERIAL'] !== '') ? $worksheet['ISSERIAL'] : '0';
                    $edit = 1;
                	echo'<input type="hidden" id="hdnCatalogId" value="'.$data->Catalog_id.'">';
                    return Html::a($data->Title, 'javascript:void(0)', [
                        'title' => $data->Title,
                        'onclick' => '
                                    var id = $(this).closest("tr").data("key");
                                    FormArticle(id,"'.$data->Catalog_id.'","'.$type.'","'.$edit.'");
                                    
                                '
                    ]);
                },
                'contentOptions'=>['style'=>'width: 400px;'],
            ],
            [
              'attribute' => 'Creator',
              'filter' => false,
              'format' => 'raw',
               'value' => function ($model) {
                $modelcat = Yii::$app->db->createCommand('select * from serial_articles_repeatable where serial_article_ID = '.$model->id.' and serial_articles_repeatable.article_field = "Kreator"')->queryAll();
            
                $test = array();
                foreach($modelcat as $value){
                    $test[] .= $value['value'];
                }

                   return  implode(',<br>',$test);
               },
            ],
            
            [
              'attribute' => 'Subject',
              'filter' => false,
              'format' => 'raw',
               'value' => function ($model) {
                $modelcat = Yii::$app->db->createCommand('select * from serial_articles_repeatable where serial_article_ID = '.$model->id.' and serial_articles_repeatable.article_field = "Subjek"')->queryAll();
            
                $test = array();
                foreach($modelcat as $value){
                    $test[] .= $value['value'];
                }
                
                   return  implode(',<br>',$test);
               },
            ],
            'EDISISERIAL',

            

            [
              'attribute' => 'Judul Katalog',
              'filter' => false,
              // 'format' => 'raw',
               'value' => function ($model) {
               	if($model->Catalog_id !== NULL){
               		$modelcat = Yii::$app->db->createCommand('select Title from catalogs where ID = '.$model->Catalog_id.' ')->queryOne();
            		return $modelcat['Title'];
               	}else{
               		return '-';
               	}
                
               },
               'contentOptions'=>['style'=>'width: 300px;'],
               'group'=>true,  // enable grouping
            ],

			[
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'ISOPAC',
                'vAlign'=>'top',
                'label'=>yii::t('app','Tampilkan di OPAC')
            ],

            [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'expandTitle' => 'lihat konten digital',
                    'collapseIcon' => '<h6>File Digital</h6>',
                    'expandIcon' => '<h6 class="label label-success">File Digital <span class="glyphicon glyphicon-collapse-down"></span></h6>',
                    'collapseTitle' => 'tutup konten digital',
                    'expandAllTitle' => 'lihat semua konten digital',
                    'collapseAllTitle' => 'tutup semua konten digital',
                    'detail' => function ($model, $key, $index) {
                        $searchModel = new \common\models\SerialArticleFilesSearch();
                        $params['ArticleID'] = $model->id;
                        $dataProvider = $searchModel->search2($params);

                        return Yii::$app->controller->renderPartial('_subEksemplarArticle', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                        ]);

                    }
                ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'width: 105px;'],
                'template' => '<div class="btn-group-vertical"> {delete} </div>',
                'buttons' => [
                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete'), Yii::$app->urlManager->createUrl(['pengkatalogan/katalog/delete-edisi-serial','id' => $model->id,'edit'=>'t','tab'=>'artikel']), [
                                                    'title' => Yii::t('app', 'Delete'),
                                                    'class' => 'btn btn-danger btn-xs',
                                                    'data' => [
                                                        'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                                                        'method' => 'post',
                                                    ],
                                                  ]);},


                ],

            ],

        ],
        //'summary'=>'',
        // 'responsive'=>true,
        'containerOptions'=>['style'=>'font-size:12px'],
        // 'hover'=>true,
        // 'condensed'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '. yii::t('app','Data Artikel').'</h3>',
            'type'=>'info',
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>


<?php
Modal::begin(['id' => 'article-modal']);
echo "<div id='modalArticle'></div>";
Modal::end();


// Modal::begin(['id' => 'KontenDigitalArticle-modal']);
// echo "<div id='modalKontenDigitalArticle'></div>";
// Modal::end();


?>

<!-- add new artikel non serial -->
<input type="hidden" id="hdnAjaxUrlFormCollectionArticle" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/artikel/bind-catalogs-article"])?>">
<!-- add new artikel non serial -->

<!-- add new digital artikel non serial -->
<input type="hidden" id="hdnAjaxUrlFormDigitalArticle" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/artikel/bind-catalogs-digital-article"])?>">
<!-- add new digital artikel non serial -->

<input type="hidden" id="hdnAjaxUrlEdisiSerial" value="<?=Yii::$app->urlManager->createUrl(['pengkatalogan/artikel/get-edisi-serial'])?>">

<?php 

    $this->registerJsFile(
        Yii::$app->request->baseUrl.'/assets_b/js/artikel.js'
    );

?>
