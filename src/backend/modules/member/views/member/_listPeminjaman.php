<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use common\widgets\AjaxButton;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\CollectionloanSearch $searchModel
 */

$this->title = Yii::t('app', 'Koreksi Anggota #') . $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="members-update">
    <div class="page-header">
        <h3>
        &nbsp;
        <!--<span class="glyphicon glyphicon-edit"></span> Koreksi -->

        <!-- Button -->

        <div class="pull-left">
          
            <?php
            echo '<p>';
            echo  Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary btn-sm']);
           /* echo  '&nbsp;' . Html::a(Yii::t('app', 'Salin dari Data Kependudukan'), ['detail-kependudukan'], ['class' => 'btn btn-primary btn-sm','data-toggle'=>"modal",
                                                    'data-target'=>"#myModal",
                                                    'data-title'=>"Detail Data",]);*/
            if($modelUser->IsCanResetMemberPassword){
             echo   '&nbsp;' . AjaxButton::widget([
                            'label' => Yii::t('app','Reset Password Keanggotaan Online'),
                            'ajaxOptions' => $ajaxOptions,
                            'htmlOptions' => [
                                'class' => 'btn btn-success btn-sm',
                                'id' => 'cari',
                                'type' => 'submit'
                            ]
                        ]);
           }
            //echo  '&nbsp;' .Html::a(Yii::t('app', 'Atur Foto'), ['crop-profile-image'], ['class' => 'btn btn-info btn-sm','data-toggle' => 'modal','data-target' => '#crop-modal']);
            

            echo  '&nbsp;' .Html::a(Yii::t('app', 'Kartu Anggota'), ['/member/pdf/kartu-anggota-satuan/','tipe'=>'2','id'=>$model->ID], ['class' => 'btn bg-maroon btn-sm','target'=>'_blank']);

            echo  '&nbsp;' .Html::a(Yii::t('app', 'Selesai'), url::previous(), ['class' => 'btn btn-warning btn-sm']);

           ?>
          </div>
           <?php
           echo yii\bootstrap\ButtonDropdown::widget([
              'label' => Yii::t('app','Cetak Bebas Pustaka'),
              'options' => [
                'class'=>'btn bg-purple btn-sm'
              ],
              'dropdown' => [
                  'items' => [
                      [ 
                        'label' => 'Model 1 (A4)', 
                        'url' =>  ['/member/pdf/cetak-bebas-pustaka/',
                          'id'=>$model->ID,'tipe'=>'1'
                        ]
                      ],
                      [
                        'label' => 'Model 2 (8,5" x 5,5")', 
                        'url' =>  ['/member/pdf/cetak-bebas-pustaka/',
                          'id'=>$model->ID,'tipe'=>'2'
                        ]
                      ],

                  ],
              ],
          ]);
            echo '</p>';
            ?>
      </h3>
    </div>

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li><a href="<?=$base?>update?id=<?=$model->ID?>"><?= Yii::t('app','Detail Anggota')?></a></li>
            <li><a href="<?=$base?>foto-anggota?id=<?=$model->ID?>"><?= Yii::t('app','Foto Anggota')?></a></li>
            <li><a href="<?=$base?>histori-pelanggaran?id=<?=$model->ID?>"><?= Yii::t('app','Data Pelanggaran')?></a></li>
            <li class="active"><a href="#peminjaman" data-toggle="tab"><?= Yii::t('app','Data Peminjaman')?></a></li>
            <li>
                <a href="<?=$base?>histori-perpanjangan?id=<?=$model->ID?>"><?= Yii::t('app','Data Perpanjangan')?></a>
            </li>
            <li>
                <a href="<?=$base?>data-sumbangan?id=<?=$model->ID?>"><?= Yii::t('app','Data Sumbangan')?></a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="collectionloans-index">
        

                <?php Pjax::begin(); echo GridView::widget([
                    'dataProvider' => $dataProviderPeminjaman,
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
                    //'filterModel' => $searchModelPeminjaman,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'CollectionLoan_id',
                        [
                            'attribute'=>'Collection_id',
                            'value'=>'collection.NomorBarcode'
                        ],
                        [
                            'attribute'=>'collection.catalog.Title',
                            'value'=>'collection.catalog.Title'
                        ],
                        'LoanStatus',
                        ['attribute'=>'LoanDate','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
                        ['attribute'=>'DueDate','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
                        ['attribute'=>'ActualReturn','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
                        'LateDays',

                    ],
                    'responsive'=>true,
                    'hover'=>true,
                    'condensed'=>true,
                    'floatHeader'=>false,

                    'panel' => [
                        'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> Data Peminjaman No.Anggota : ' . $model->MemberNo .' </h3>',
                        'type'=>'info',
                        'after'=>'<button type="button" class="btn btn-info" onclick="refresh()"><i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List').'</button>',
                        'showFooter'=>false
                    ],
                ]); Pjax::end(); ?>

            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    function refresh() {
        $( ".collectionloans-index" ).load(window.location.href + " .collectionloans-index" );
    }
</script>