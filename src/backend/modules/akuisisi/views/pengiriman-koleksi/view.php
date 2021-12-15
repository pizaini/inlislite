<?php



use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\PengirimanKoleksi $model
 */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengiriman Koleksis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengiriman-koleksi-view">
   <p> <a class="btn btn-warning" href="/perpus/inlislite_32/backend/gii">Kembali</a>        <a class="btn btn-primary" href="/perpus/inlislite_32/backend/gii/default/update?id=%24model-%3Eid">Koreksi</a>        <a class="btn btn-danger" href="/perpus/inlislite_32/backend/gii/default/delete?id=%24model-%3Eid" data-confirm="Apakah Anda yakin ingin menghapus item ini?" data-method="post">Hapus</a>    </p>



    <?= DetailView::widget([
            'model' => $model,
            
        'attributes' => [
            'BIBID',
            'JUDUL',
            'TAHUNTERBIT',
            'CALLNUMBER',
            'NOBARCODE',
            'NOINDUK',
            'QUANTITY',
            [
                        'attribute'=>'TANGGALKIRIM',
                        'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'],
                        'type'=>DetailView::INPUT_WIDGET,
                        'widgetOptions'=> [
                            'class'=>DateControl::classname(),
                            'type'=>DateControl::FORMAT_DATE
                        ]
                    ],
        ],
       
    ]) ?>

</div>
