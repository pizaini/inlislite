<?php

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;

use common\models\SurveyPilihanSesi;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\SurveySearch $searchModel
 */

$this->title = 'Survey';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="survey-indeex">
    <div class="page-header">
            <!-- <h1><?= Html::encode($this->title) ?></h1>-->
        <?php  echo $this->render('_searchAdvanced', ['model' => $searchModel,'rules' => $rules]); ?>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Survey', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin();
    echo GridView::widget([
        'id'=>'myGrid',
        'dataProvider' => $dataProvider,
		'toolbar'=> [
            ['content'=>
                 \common\components\PageSize::widget(
                    [
                        'template'=> '{label} <div class="col-sm-8" style="width:175px">{list}</div>',
                        'label'=>yii::t('app','Tampilkan :'),
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
        // 'filterModel' => $searchModel,
		'columns' => [
		    //Checkbox
            ['class'       => '\kartik\grid\CheckboxColumn',
                'pageSummary' => true,
                'rowSelectedClass' => GridView::TYPE_INFO,
                'name' => 'cek',
                'checkboxOptions' => function ($searchModel, $key, $index, $column) {
                    return [
                        'value' => $searchModel->ID
                    ];
                },
                'vAlign' => GridView::ALIGN_TOP
            ],
            //Nomor
            ['class' => 'yii\grid\SerialColumn'],

            [
            'attribute' =>'NamaSurvey',
            'label' =>yii::t('app','Nama Survey')
            ],
            // 'TanggalMulai',
            [
                'attribute' => 'TanggalMulai',
                'label' =>yii::t('app','Tgl.Mulai'),
                'value' => function($model){
                    return date('d-m-Y', strtotime($model->TanggalMulai));
                }
            ],

            // ['attribute'=>'TanggalMulai','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
            // 'TanggalSelesai',
            [
                'attribute' => 'TanggalSelesai',
                'label' =>yii::t('app','Tanggal Selesai'),
                'value' => function($model){
                    return date('d-m-Y', strtotime($model->TanggalSelesai));
                }
            ],

            // ['attribute'=>'TanggalSelesai','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
            //'IsActive:boolean',
			[
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'IsActive',
                //'vAlign'=>'top',
                'label'=>'Status'
            ],
            //['attribute'=>'IsActive','format'=>['boolean'],'label'=>'Aktif'],

            [
            'attribute'=>'NomorUrut',
            'label'=>yii::t('app','Nomor Urut')
            ],
            //'TargetSurvey',
            //['attribute'=>'TargetSurvey', 'value' => 'TargetSurvey'==1 ? "Semua": 'TargetSurvey'==0 ? "Anggota": "Rejected",'label'=>'Responden'],
            [
            'attribute'=>'TargetSurvey',
            'label'=>yii::t('app','Target Survey'),
                'value' => function ($data){
                   return $data->TargetSurvey==1 ? "Anggota": ($data->TargetSurvey==0 ? "Semua": "Rejected");
               }
            ],


            ['attribute' => 'Jumlah Responden',
			'label'=>yii::t('app','Jumlah Responden'),
            'value' => function ($model) {
                $jml = SurveyPilihanSesi::find()
                    ->groupBy(['Sesi'])
                    ->join('INNER JOIN','survey_pilihan','survey_pilihan_sesi.Survey_Pilihan_id = survey_pilihan.ID')
                    ->join('INNER JOIN','survey_pertanyaan','survey_pilihan.Survey_Pertanyaan_id = survey_pertanyaan.ID')
                    ->where(['Survey_id' => $model->ID ])
                    ->count();
                return $jml;
            },
            ],
//            'HasilSurveyShow',
//            'RedaksiAwal:ntext',
//            'RedaksiAkhir:ntext',
//            'Keterangan',


            // Pilihan Column
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'max-width: 200px;'],
                'template' => '<div class="btn-group-vertical"> {pertanyaan} {hasilSurvey} </div>',
                'buttons' => [
                    'pertanyaan' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-th-list"></span> '.Yii::t('app', 'Daftar Pertanyaan').'', Yii::$app->urlManager->createUrl(['survey/survey-pertanyaan/index','id' => $model->ID,'edit'=>'t']), [
                                                        'title' => Yii::t('app', 'Daftar Pertanyaan'),
                                                        'class' => 'btn btn-success btn-sm',
                                                        'data-pjax'=>'0',
                                                        'data' => [
                                                            //'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                                                            //'method' => 'post',
                                                        ],
                                                      ]);},

                    'hasilSurvey' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-stats"></span> '.Yii::t('app', 'Hasil').'', ['result-modal'],[
                                                        'data-toggle'=>"modal",
                                                        'class' => 'btn btn-warning btn-sm',
                                                        'data-target'=>"#result-modal",
                                                        'onclick' => 'modalChart('.$model->ID.')'

                                                      ]);},

                ],
            ],


            // Action Column
            [
                'class' => 'yii\grid\ActionColumn',
				'contentOptions'=>['style'=>'max-width: 200px;min-width: 90px;'],
                'template' => '<div class="btn-group-vertical"> {update} {delete}</div>',
                'buttons' => [
    				'update' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app', 'Edit').'', Yii::$app->urlManager->createUrl(['survey/data/update','id' => $model->ID,'edit'=>'t']), [
                                                        'title' => Yii::t('app', 'Edit'),
                                                        'class' => 'btn btn-primary btn-sm'
                                                      ]);},

                    'delete' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete').'', Yii::$app->urlManager->createUrl(['survey/data/delete','id' => $model->ID,'edit'=>'t']), [
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success']),
            'after'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), [''], ['type'=>'button','class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]);
   Pjax::end(); ?>

    <?php Modal::begin([
        'id' => 'result-modal',
        'size'=>'modal-lg',
        'header' => '<h4 class="modal-title">Hasil Survey</h4>',
        'options' => [
            'width' => '900px',
        ],

        ]);
        echo " <div id='modalResults' style='width:100%'></div>";
    Modal::end(); ?>

</div>



<script type="text/javascript">
    function modalChart(id)
    {
        isLoading = false;
        $("#modalResults").html('<center>Loading...</center>');
        $.ajax({
            type     :"POST",
            cache    : false,
            url      : "<?= Yii::$app->urlManager->createUrl(['survey/data/hasil-survey']) ?>?id="+id,
            success  : function(response) {
                $("#modalResults").html(response);
            }
        });
    }

</script>


<?php
    $this->registerJs("
    isLoading = false;
");


?>