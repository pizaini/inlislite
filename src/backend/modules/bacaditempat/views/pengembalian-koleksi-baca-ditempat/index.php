<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use common\widgets\AjaxButton;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\BacaditempatKembaliSearch $searchModel
 */

$this->title = Yii::t('app', 'Pengembalian Koleksi Baca Ditempat');
$this->params['breadcrumbs'][] = Yii::t('app', 'Bacaditempat');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bacaditempat-kembali-index">


<div class="message" data-message-value="<?= $message ?>">
</div>

<?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);  ?>

<!-- KOLEKSI-AREA -->
<div style="background-image: linear-gradient(to bottom, #59bedc, #0978c5);
     color:#fff;padding:0px 0px 10px 20px;border-radius:3px;">
    <div class="content_edit" id="koleksi-area">
        <table border="0">
            <tr>
                <td valign="top" class='icon-users'>&nbsp;&nbsp;
                    <div class="input-group">
                        <?= Html::activeTextInput($model,'NomorBarcode',['class'=>'form-control fieldNomorBarcode','style'=>'width:100%','autofocus'=>'autofocus','placeholder'=>Yii::t('app', 'Enter').'  No.'.Yii::t('app', 'Barcode').'']); ?>
                        <div class="input-group-btn" >
                            <button type="submit" id="cari" class="btn btn-warning">
                                <i class="glyphicon glyphicon-check"></i> Ok
                            </button>    
                            <?php
                                    // echo AjaxButton::widget([
                                    //     'label' => '<i class="glyphicon glyphicon-check"></i> ' .Yii::t('app','Ok'),
                                    //     'ajaxOptions' => $ajaxOptions,
                                    //     'htmlOptions' => [
                                    //         'class' => 'btn btn-warning',
                                    //         'id' => 'cari',
                                    //         'type' => 'submit'
                                    //     ]
                                    // ]);
                            ?>  
                        </div><!-- /btn-group -->
                    </div>
                    <div class="hint-block col-sm-9"></div>
                </td>

            </tr>
        </table>
    </div>
</div>
<!-- /.KOLEKSI-AREA -->


<?php ActiveForm::end(); ?>


<!-- KOLEKSI YANG AKAN DIKEMBALIKAN -->
</br>
<div id="koleksi-item">
    <div style="text-align: center; font-size: 14px; background-color: #73b9d7; padding: 10px;
                        color: #fff; text-shadow: 0 1px 2px #222; margin-bottom: 3px; border-radius: 5px;">
                        <b><?= yii::t('app','Daftar Koleksi Selesai Baca Di Tempat')?></b>
    </div>

</div>
<!-- ,/KOLEKSI YANG AKAN DIKEMBALIKAN -->



    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Bacaditempat Kembali',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
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
        'filterModel' => $searchModel,
 'columns' => [
		
            ['class' => 'yii\grid\SerialColumn'],

            // 'NoPengunjung',
            // 'collection_id',
            // 'Member_id',
            // 'Location_Id',
            // 'UpdateDate',
            ['attribute'=>'UpdateDate', 
                // 'format'=> ['date', 'php:d-m-Y H:i:s'],
                'label'=>Yii::t('app', 'Tgl.Pengembalian'),
                'value'=>function($model){
                    $time = strtotime($model->UpdateDate);
                    return date('d-m-Y H:i:s',$time);
                }
            ],  

            // No Barcode, 
            // 'collection.NomorBarcode',
            ['attribute'=>'ColBarcode', 
                'value'=>'collection.NomorBarcode', 
                'label'=>Yii::t('app', 'Nomor Barcode')
            ], 

            //Judul
            [   
                'attribute'=>'CatJudul', 
                'contentOptions'=>['style'=>'max-width: 550px;'],
                'value'=>'collection.catalog.Title', 
                'label'=>Yii::t('app', 'Judul')
            ],  

            // No Anggota, 
            ['attribute'=>'MemberNo', 
                'value'=>'member.MemberNo',
                'label'=>Yii::t('app','Nomor Anggota')
            ],  

            // Nama, 
            // 'member.Fullname',
            ['attribute'=>'MemberFullname', 
                'value'=>'member.Fullname',
                'label'=>Yii::t('app','Nama Anggota')
            ],  

            // No Pengunjung,
            ['attribute'=>'NoPengunjung',
                'label' => Yii::t('app','Nomor Pengunjung')
            ],
            //  Nama, 
            [   
                'attribute'=>'GuestNama', 
                'contentOptions'=>['style'=>'max-width: 550px;'],
                'value'=>'memberguess.Nama', 
                'label'=>Yii::t('app', 'Nama Pengunjung')
            ],  


            ['attribute'=>'CreateDate', 
                // 'format'=> ['date', 'php:d-m-Y H:i:s'],
                'contentOptions'=>['style'=>'width: 120px;'],
                'label'=>Yii::t('app', 'Waktu Mulai Baca'),
                'value'=>function($model){
                    $time = strtotime($model->CreateDate);
                    return date('d-m-Y H:i:s',$time);
                }
            ],  


            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'max-width: 90px;'],
                // 'template' => '{update} {delete}',
                'template' => '{delete}',
                'buttons' => [
                // 'update' => function ($url, $model) {
    //                                 return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['pengembalian-koleksi-baca-ditempat/update','id' => $model->ID,'edit'=>'t']), [
    //                                                 'title' => Yii::t('app', 'Edit'),
    //                                                 'class' => 'btn btn-primary btn-sm'
    //                                               ]);},
                                                  
                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['bacaditempat/pengembalian-koleksi-baca-ditempat/delete','id' => $model->ID,'edit'=>'t']), [
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
            //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>



<?php

$script = <<< JS

$('.fieldNomorBarcode').val('');

if ($('.message').data("messageValue")) {
     swal($('.message').data("messageValue"));
}


JS;

$this->registerJs($script);
?>
