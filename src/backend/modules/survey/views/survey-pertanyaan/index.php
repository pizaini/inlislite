<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use common\models\SurveyPilihan;
use common\models\SurveyPilihanSesi;

use yii\bootstrap\Modal;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\SurveyPertanyaanSearch $searchModel
 */

$this->title = 'Survey Pertanyaan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-pertanyaan-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Survey Pertanyaan', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
		'toolbar'=> [
            ['content'=>
                 \common\components\PageSize::widget(
                    [
                        'template'=> '{label} <div class="col-sm-8" style="width:175px">{list}</div>',
                        'label'=>'Tampilkan :',
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
            'filterModel' => $searchModel,
            'columns' => [

            ['class' => 'yii\grid\SerialColumn'],

            'NoUrut',
    //        'Survey_id',
            'Pertanyaan:ntext',
            'JenisPertanyaan',
            'Orientation',
            //'IsMandatory:boolean',
            ['attribute'=>'IsMandatory','format'=>['boolean'],'label'=>'Wajib Diisi'],
//            'IsCanMultipleAnswer:boolean',
            ['attribute'=>'IsCanMultipleAnswer','format'=>['boolean'],'label'=>'Jawaban bisa lebih dari satu'],


            ['attribute' => 'Jumlah Pilihan',
           // 'format' => 'raw',
            'value' => function ($model) {
                $jml = SurveyPilihan::find()
                    ->where(['Survey_Pertanyaan_id' => $model->ID ])
                    ->count();
                return $jml;
            },
            ],

            ['attribute' => 'Jumlah Responden',
               // 'format' => 'raw',
                'value' => function ($model) {
                    // Jika Jenis pertanyaan berupa pilihan maka mencari jumlah responden di table Survey_pilihan_sesi
                    if ($model->JenisPertanyaan == 'Pilihan') {
                        $jml = SurveyPilihanSesi::find()
                            ->groupBy(['Sesi'])
                            ->join('INNER JOIN','survey_pilihan','survey_pilihan_sesi.Survey_Pilihan_id = survey_pilihan.ID')
                            ->join('INNER JOIN','survey_pertanyaan','survey_pilihan.Survey_Pertanyaan_id = survey_pertanyaan.ID')
                            ->where(['Survey_Pertanyaan_id' => $model->ID ])
                            ->count();
                    } else {
                    // Jika Jenis pertanyaan berupa Isian maka mencari jumlah data isian yang diisi oleh responden di tabe SurveyIsian
                        $jml = common\models\SurveyIsian::find()
                            ->groupBy(['Sesi'])
                            ->where(['Survey_Pertanyaan_id' => $model->ID ])
                            ->count();
                        ;
                    }
                    return $jml;
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
				'contentOptions'=>['style'=>'max-width: 200px;'],
                'template' => '<div class="btn-group-vertical"> {update} {delete} {pilihan} </div>',
                'buttons' => [
				'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app','Update'), Yii::$app->urlManager->createUrl(['survey/survey-pertanyaan/update','id' => $model->ID,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Edit'),
                                                    'class' => 'btn btn-primary btn-sm'
                                                  ]);},

                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app','Delete'), Yii::$app->urlManager->createUrl(['survey/survey-pertanyaan/delete','id' => $model->ID,'edit'=>'t','sid'=>$model->Survey_id]), [
                                                    'title' => Yii::t('app', 'Delete'),
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'data' => [
                                                        'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                                                        'method' => 'post',
                                                    ],
                                                  ]);},

                'pilihan' => function ($url, $model) {
                        if ($model->JenisPertanyaan == 'Pilihan' ) {
                            // return Html::a('<span class="glyphicon glyphicon-th-list"></span>', 'javascript:void(0)' , [
                            return Html::a('<span class="glyphicon glyphicon-th-list"></span> '.Yii::t('app','Pilihan'), Yii::$app->urlManager->createUrl(['survey/survey-pilihan/index','id' => $model->ID,'sid'=>$model->Survey_id ,'edit'=>'t']) , [
                                'title' => Yii::t('app', 'Daftar Pilihan'),
                                'class' => 'btn btn-success btn-sm',
                                // 'onclick' => 'modalPilihan('.$model->ID.')',
                                'data-pjax'=>'0',
                                'data' => [
                                    //'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                                    //'method' => 'post',
                                ],
                              ]);
                        } else {
                            // return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', 'javascript:void(0)', [
                            return Html::a('<span class="glyphicon glyphicon-list-alt"></span> '.Yii::t('app','Isian'), Yii::$app->urlManager->createUrl(['survey/survey-isian/index','id' => $model->ID,'sid'=>$model->Survey_id ,'edit'=>'t']), [
                                'title' => Yii::t('app', 'Daftar Isian'),
                                'class' => 'btn btn-warning btn-sm',
                                // 'onclick' => 'modalIsian('.$model->ID.')',
                                'data-pjax'=>'0',
                                'data' => [
                                    //'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                                    //'method' => 'post',
                                ],
                              ]);
                        }

                    },

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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create','id'=>$id], ['class' => 'btn btn-success']),'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index','id'=> $id], ['class' => 'btn btn-info']). Html::a('Kembali', Yii::$app->urlManager->createUrl(["survey/data"]) ,['class' => 'btn btn-warning pull-right','data-pjax'=>'0', ]),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>


<?php Modal::begin(['id' => 'result-modal']);
    echo "<div id='modalResult'></div>";
Modal::end(); ?>




<script type="text/javascript">
    // Load Modal for Survey Optional Answer
    function modalPilihan(id)
    {
    $('#modalResult').html('Loading...');

        if($.ajax({
            type    :'POST',
            cache   : false,
            url     : '../survey-pilihan/index?id='+id,
            success : function(response) {
                $('#modalResult').html(response);
            }
        }))
        {
          $('#result-modal').modal('show');
        }
    }

    // Load Modal for Survey Essay Answer
    function modalIsian(id)
    {
    $('#modalResult').html('Loading...');

        if($.ajax({
            type    :'POST',
            cache   : false,
            url     : '../survey-isian/index?id='+id,
            success : function(response) {
                $('#modalResult').html(response);
            }
        }))
        {
          $('#result-modal').modal('show');
        }
    }
</script>



<?php

$this->registerJs("

    // $('#btnAddPartners').click(function(e) {
    //     if($.ajax({
    //         type     :'POST',
    //         cache    : false,
    //         url  : 'bind-partners?id=&edit=0',
    //         success  : function(response) {
    //             $('#modalPartners').html(response);
    //         }
    //     }))
    //     {
    //       $('#rekanan-modal').modal('show');
    //     }
    // });

    ");
?>
