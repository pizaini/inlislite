<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\CollectionSearchKardeks;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\QuarantinedCollectionSearch $searchModel
 */

$this->title = Yii::t('app', 'Proposed Collections');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Akuisisi'), 'url' => Url::to(['/akuisisi'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quarantined-collections-index">
<?php  echo $this->render('_searchAdvanced', ['model' => $searchModel,'rules' => $rules]); ?>
    <?php Pjax::begin(['id' => 'myGridview']); echo GridView::widget([
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
        'pager' => [
            'firstPageLabel' => Yii::t('app','Awal'),
            'lastPageLabel'  => Yii::t('app','Akhir')
        ],
        'filterSelector' => 'select[name="per-page"]',
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            /*'ID',
            'Type',*/
            [
                'attribute'=>'Title',
                'label'=>yii::t('app','Judul'),
            ],
            /*'Subject',*/
            [
                'attribute'=>'Author',
                'label'=>yii::t('app','Pengarang'),
            ],
            [
                'attribute'=>'Publishment',
                'label'=>yii::t('app','Penerbit'),
            ],
            [
                'attribute'=>'DateRequest',
                'label'=>yii::t('app','Tanggal Request'),
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
            /*'PublishLocation',
            'PublishYear',
            'Publisher',*/
            // 'DateRequest:date',
            /*'Comments',*/
            [
                'attribute'=>'MemberID',
                'label'=>yii::t('app','Nomor Anggota'),
                'value'=>'member.MemberNo',
            ],
            /*'CallNumber',
            'ControlNumber',*/
            //'Status',
            [
                'class'=>'kartik\grid\EditableColumn',
                'attribute' => 'Status',
                'label'=>yii::t('app','Status'),
                //'refreshGrid' => true,
                'editableOptions' => function($model) {
                    return [
                        'inputType' => \kartik\editable\Editable::INPUT_SELECT2,
                        'placement'=>'left',
                        'size'=>'sm',
                        'name' => 'cbStatus',
                        'options' => [ // your widget settings here
                            'data' => ['Usulan'=>'Usulan','Diterima'=>'Diterima','Ditolak'=>'Ditolak'],
                            'pluginOptions' => [
                                //'tags' => ['say', 'what'],
                                //'data' => [0=>'0',1=>'1'],
                            ],
                        ]
                    ];
                }

            ],
            [
                'class' => 'yii\grid\ActionColumn',
                /*'contentOptions'=>['style'=>'width: 40px;'],*/
                'template' => '<span style="display:inline">{view}</span>',
                'buttons' => [
                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-book"></span> '.Yii::t('app', 'View'), Yii::$app->urlManager->createUrl(['akuisisi/koleksi-usulan/view','id' => $model->ID]), [
                                                    'title' => Yii::t('app', 'View'), 
                                                    //'data-toggle' => 'tooltip',
                                                    'class' => 'btn btn-primary btn-sm'
                                                  ]);},

                ],
            ],
            /*'CreateBy',
            'CreateDate',
            'CreateTerminal',*/
            /*[
                'attribute'=>'WorksheetID',
                'value'=>'worksheet.Name',
            ]*/
        ],
        //'summary'=>'',
        'responsive'=>true,
        'containerOptions'=>['style'=>'font-size:12px'],
        'hover'=>true,
        'condensed'=>true,
        'options'=>['font-size'=>'11px'],
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            /*'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),*/
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
