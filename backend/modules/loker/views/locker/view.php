<?php



use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Lockers $model
 */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Lockers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lockers-view">
   <p> <a class="btn btn-warning" href="/inlislite3/backend/gii">Kembali</a>        <a class="btn btn-primary" href="/inlislite3/backend/gii/default/update?id=%24model-%3Eid">Koreksi</a>        <a class="btn btn-danger" href="/inlislite3/backend/gii/default/delete?id=%24model-%3Eid" data-confirm="Apakah Anda yakin ingin menghapus item ini?" data-method="post">Hapus</a>    </p>



    <?= DetailView::widget([
            'model' => $model,
            
        'attributes' => [
            [
            'attribute'=>'No_pinjaman',
            'label'=>yii::t('app','Nomor Pinjaman'),
            ],
            [
            'attribute'=>'no_member',
            'label'=>yii::t('app','Nomor Anggota'),
            ],
            [
            'attribute'=>'no_identitas',
            'label'=>yii::t('app','Nomor Identitas'),
            ],
            [
            'attribute'=>'jenis_jaminan',
            'label'=>yii::t('app','Jenis Jaminan'),
            ],
            [
            'attribute'=>'id_jamin_idt',
            ],
            [
            'attribute'=>'id_jamin_uang',
            ],
            [
            'attribute'=>'loker_id',
            ],
            [
                        'attribute'=>'tanggal_pinjam',
                        'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
                        'type'=>DetailView::INPUT_WIDGET,
                        'widgetOptions'=> [
                            'class'=>DateControl::classname(),
                            'type'=>DateControl::FORMAT_DATETIME
                        ]
                    ],
            [
                        'attribute'=>'tanggal_kembali',
                        'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
                        'type'=>DetailView::INPUT_WIDGET,
                        'widgetOptions'=> [
                            'class'=>DateControl::classname(),
                            'type'=>DateControl::FORMAT_DATETIME
                        ]
                    ],
        ],
       
    ]) ?>

</div>
