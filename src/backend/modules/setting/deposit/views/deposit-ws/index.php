<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $searchModel common\models\DepositWsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'SSKCKR Wajib Serah');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposit-ws-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Deposit Ws'), ['create'], ['class' => 'btn btn-success  btn-sm','data-toggle'=>"modal",'data-target'=>"#deposit-form"]) ?>
        
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ID',
            'nama_penerbit',
            [
            'attribute'=>'alamat1',
            'label'=>yii::t('app','Alamat'),
            ],
            // 'jenis_penerbit',
            'email',
            [
            'attribute'=>'no_telp1',
            'label'=>yii::t('app','Nomor Telpon'),
            ],
            // 'id_group_deposit_group_ws',
            // 'id_deposit_kelompok_penerbit_ws',
            // 'alamat2',
            // 'alamat3',
            // 'kabupaten',
            // 'id_wilayah_ws',
            // 'kode_pos',
            // 'no_telp2',
            // 'no_telp3',
            // 'no_fax',
            // 'contact_person',
            // 'no_contact',
            // 'koleksi_per_tahun',
            // 'keterangan',
            // 'status',

            // ['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'max-width: 60px;'],
                'template' => '{update} {delete}',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['setting/deposit/deposit-ws/update','id' => $model->ID]), [
                                        'title' => Yii::t('app', 'Edit'),
                                        'class' => 'btn btn-success  btn-sm',
                                        'data-toggle'=>"modal",
                                        'data-target'=>"#deposit-update"]);},
                                                  
                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['setting/deposit/deposit-ws/delete','id' => $model->ID]), [
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
    ]); ?>

<div class="modal remote fade" id="deposit-form" style="overflow-y: auto !important;">
        <div class="modal-dialog" style="width:700px;">
            <div class="modal-content loader-lg"></div>
        </div>
</div>
<div class="modal remote fade" id="deposit-update" style="overflow-y: auto !important;">
        <div class="modal-dialog" style="width:700px;">
            <div class="modal-content loader-lg"></div>
        </div>
</div>

<?php
Modal::begin(['id' => 'rekanan-modal','options'=>[
  'style'=>['z-index'=>9999],
  'data-backdrop'=>'static'
]]);
echo "<div id='modalPartners'></div>";
Modal::end();
?>

<?php Pjax::end(); ?></div>
