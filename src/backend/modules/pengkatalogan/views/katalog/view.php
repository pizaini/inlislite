<?php



use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Collections $model
 */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collections-view">
   <p> <a class="btn btn-warning" href="/inlislite3/backend/gii">Kembali</a>        <a class="btn btn-primary" href="/inlislite3/backend/gii/default/update?id=%24model-%3Eid">Koreksi</a>        <a class="btn btn-danger" href="/inlislite3/backend/gii/default/delete?id=%24model-%3Eid" data-confirm="Apakah Anda yakin ingin menghapus item ini?" data-method="post">Hapus</a>    </p>



    <?= DetailView::widget([
            'model' => $model,
            
        'attributes' => [
            'NoInduk',
            'Currency',
            'RFID',
            'Price',
            [
                        'attribute'=>'TanggalPengadaan',
                        'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
                        'type'=>DetailView::INPUT_WIDGET,
                        'widgetOptions'=> [
                            'class'=>DateControl::classname(),
                            'type'=>DateControl::FORMAT_DATETIME
                        ]
                    ],
            'CallNumber',
            'IsDelete',
            'Branch_id',
            'Catalog_id',
            'Partner_id',
            'Location_id',
            'Rule_id',
            'Category_id',
            'Media_id',
            'Source_id',
            'GroupingNumber',
            'NomorBarcode',
            'Status',
            'Keterangan_Sumber',
            'IsVerified',
            'QUARANTINEDBY',
            [
                        'attribute'=>'QUARANTINEDDATE',
                        'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'],
                        'type'=>DetailView::INPUT_WIDGET,
                        'widgetOptions'=> [
                            'class'=>DateControl::classname(),
                            'type'=>DateControl::FORMAT_DATE
                        ]
                    ],
            'QUARANTINEDTERMINAL',
            'STATUSAKUISISI',
            'ISREFERENSI:boolean',
            'EDISISERIAL',
            'NOJILID',
            [
                        'attribute'=>'TANGGAL_TERBIT_EDISI_SERIAL',
                        'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'],
                        'type'=>DetailView::INPUT_WIDGET,
                        'widgetOptions'=> [
                            'class'=>DateControl::classname(),
                            'type'=>DateControl::FORMAT_DATE
                        ]
                    ],
            'BAHAN_SERTAAN',
            'KETERANGAN_LAIN',
            [
                        'attribute'=>'TGLENTRYJILID',
                        'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'],
                        'type'=>DetailView::INPUT_WIDGET,
                        'widgetOptions'=> [
                            'class'=>DateControl::classname(),
                            'type'=>DateControl::FORMAT_DATE
                        ]
                    ],
            'IDJILID',
            'NOMORPANGGILJILID',
            'ISOPAC:boolean',
            'JILIDCREATEBY',
        ],
       
    ]) ?>

</div>
