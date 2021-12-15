<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\CollectionSearchKardeks;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\QuarantinedCollectionSearch $searchModel
 */
/*$model=$dataProvider->getModels();
\common\components\OpacHelpers::print__r($model);*/


?>

<?php echo GridView::widget([
    /*'id'=>'myGrid3',
    'pjax'=>true,*/
    'dataProvider' => $dataProvider,
    /*'toolbar'=> [
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
    ],*/
    'filterSelector' => 'select[name="per-page"]',
    //'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'NomorBarcode',
        'CallNumber',
        'akses',
        'lokasi',
        'ketersediaan',
        [
            'attribute' => 'Boleh di pinjam',
            'format' => 'raw',
            'value' => function ($data) {
                $dateNow = new \DateTime("now");
                $isbooking = Yii::$app->config->get('IsBookingActivated');
                if ($data['BookingMemberID'] == $noAnggota && $data['BookingExpiredDate'] > $dateNow->format("Y-m-d H:i:sO")) {
                    $data['ketersediaan'] = "Sudah Anda pesan";
                    //$data['akses'] = "Di Booking";
                } else
                    if ($data['BookingExpiredDate'] > $dateNow->format("Y-m-d H:i:s")) {
                        $data['ketersediaan'] = "Sudah Dipesan";
                        //$data['akses'] = "Di Booking";
                    }
                if ($isbooking=='1' && $data['ketersediaan'] == "Tersedia" && ($data['akses'] == "Dapat dipinjam" || $data['akses'] == "Tersedia" )) {
                    if (!isset($noAnggota)) {
                        $booking = "
                          <br>
                           <a href=\"javascript:void(0)\" class=\"btn btn-success btn-xs navbar-btn\" onclick='tampilLogin()'>pesan</a>
 

                
                        ";
                    } else
                        $booking = "
                            <form>
                            <input type=\"button\" onclick=\"booking(" . $data['Catalog_id'] . "," . $data['id'] . ")\" class=\"btn btn-success btn-xs navbar-btn\" value=\"pesan\">

                            </form>                 
                        ";
                } else {
                    $booking = "";
                }




                return $data['ketersediaan'].$booking;
            },
        ],
    ],
    'summary' => false,
    'responsive' => true,
    'containerOptions' => ['style' => 'font-size:13px'],
    'hover' => true,
    'condensed' => true,
    'headerRowOptions' => ['class' => GridView::TYPE_SUCCESS],
    'options' => ['font-size' => '12px']
]); ?>
