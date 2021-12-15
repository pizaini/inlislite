<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;


use yii\helpers\Url;
use kartik\widgets\FileInput;
use kartik\widgets\ActiveForm;

use yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\JenisPerpustakaanSearch $searchModel
 */

$this->title = Yii::t('app', 'Kependudukan');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="perpustakaan-daerah-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Jenis Perpustakaan',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); 
    echo GridView::widget([
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
        'filterModel' => $searchModel,
 'columns' => [

            ['class' => 'yii\grid\SerialColumn'],
            [
            'attribute'=>'nomorkk',
            'label'=>'Nomor KK'
            ]
            ,'nik',
             'namalengkap',
             'alamat',
             'lhrtempat',
             'lhrtanggal',
             'umur',
             'jenis',
             'sts',

             // 'agama',
             [
                'attribute'=>'agamas',
                'value'=>'agamakep.Name',
                'label'=>Yii::t('app','Agama')
             ],
             'pendidikan',
             'pekerjaan',

            [
                'class' => 'yii\grid\ActionColumn',
				'contentOptions'=>['style'=>'max-width: 90px;'],
                'template' => '<div class="btn-group-vertical"> {update} {delete} </div>',
                'buttons' => [
				'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app', 'Update'), Yii::$app->urlManager->createUrl(['setting/member/kependudukan/update','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Update'),
                                                    'class' => 'btn btn-primary btn-sm'
                                                  ]);},

                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete'), Yii::$app->urlManager->createUrl(['setting/member/kependudukan/delete','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Delete'),
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'data' => [
                                                        'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                                                        'method' => 'post',
                                                    ],
                                                  ]);},


                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,

        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success']).' '.Html::a('<span class="glyphicon glyphicon-cloud-upload"></span> '.Yii::t('app', 'Import').'', ['result-modal'],[
                    'data-toggle'=>"modal",
                    'class' => 'btn btn-primary btn-md',
                    'data-target'=>"#result-modal",
                    // 'onclick' => 'modalChart('.$model2->ID.')'
                    ]),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>





<?php Modal::begin([
    'id' => 'result-modal',
    'size'=>'modal-md',
    'header' => '<h4 class="modal-title">'.yii::t('app','Import Data Kependudukan').'</h4>',
    'options' => [
    'width' => '900px',
    ],
    ]);
echo " <div id='modalResults' style='width:100%'></div>";

$url = Yii::$app->urlManager->createUrl(['../uploaded_files/templates/datasheet/kependudukan/001_Database Kependudukan.xlsx']);
?>
    Template : <?= Html::a('Unduh Template', $url, ['class'=>'btn btn-primary btn-xs btn-flat']) ?>
    <br>
    <br>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','target'=>"hidden_iframe"]]) ?>

    <!-- $form->field($model, 'file')->fileInput() -->
    <?= $form->field($model2, 'file')->widget(FileInput::classname(), [
        'options'=>['accept'=>'.xls, .xlsx, .csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'],
        'pluginOptions'=>[
            'allowedFileExtensions'=>['xls','xlsx'],
            'showPreview' => false,
            'autoReplace' => true,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => true,
            'uploadLabel' => Yii::t('app','Proses'),
            'uploadUrl' => Url::to(['/setting/member/kependudukan/index']),
        ]
    ]);?>
   <?php 
   ?>

<?php ActiveForm::end();


Modal::end(); ?>



