<?php



use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;    
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\QuarantinedCollections $model
 */

$this->title = $model->NomorBarcode;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quarantined Collections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quarantined-collections-view">
   <p> 
 <?= Html::a(Yii::t('app', 'Restore'), ['restore', 'id' => $model->ID], ['class' => 'btn btn-success btn-sm']) ?>
 <?= Html::a(Yii::t('app', 'Back'), ['karantina'], ['class' => 'btn btn-warning btn-sm']) ?>
    </p>

    <?= DetailView::widget([
            'model' => $model,
            
        'attributes' => [
            'NomorBarcode',
            'RFID',
            'NoInduk',
            //'PriceType',
            'TanggalPengadaan:date',
           /* [
                        'attribute'=>'TanggalPengadaan',
                        'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
                        'type'=>DetailView::INPUT_WIDGET,
                        'widgetOptions'=> [
                            'class'=>DateControl::classname(),
                            'type'=>DateControl::FORMAT_DATETIME
                        ]
                    ],*/
            //'Branch_id',
            [
                'attribute'=>'Catalog_id',
                'value'=>$model->catalog->Title,
            ],
            [
                'attribute'=>'Media_id',
                'value'=>$model->media->Name,
            ],
            [
                'attribute'=>'Source_id',
                'value'=>$model->source->Name,
            ],
            [
                'attribute'=>'Category_id',
                'value'=>$model->category->Name,
            ],
            [
                'attribute'=>'Partner_id',
                'value'=>$model->partner->Name,
            ],
            [
                'attribute'=>'Location_Library_id',
                'value'=>$model->locationLibrary->Name,
            ],
            [
                'attribute'=>'Location_id',
                'value'=>$model->location->Name,
            ],
            [
                'attribute'=>'Rule_id',
                'value'=>$model->rule->Name,
            ],
            [
                'attribute'=>'Status_id',
                'value'=>$model->status->Name,
            ],
            'Keterangan_Sumber',
            'Currency',
            'Price',
            'CallNumber',
            'ISOPAC:boolean',
            /*'IsVerified:boolean',
            'QUARANTINEDBY',
            'QUARANTINEDDATE',
            'QUARANTINEDTERMINAL',
            'ISREFERENSI:boolean',
            'EDISISERIAL',
            'NOJILID',
            'TANGGAL_TERBIT_EDISI_SERIAL',
            'BAHAN_SERTAAN',
            'KETERANGAN_LAIN',
            'TGLENTRYJILID',
            'IDJILID',
            'NOMORPANGGILJILID',
            'JILIDCREATEBY',*/
        ],
       
    ]) ?>

</div>
