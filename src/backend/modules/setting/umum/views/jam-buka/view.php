<?php



use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\MasterJamBuka $model
 */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Jam Bukas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-jam-buka-view">
   <p> <a class="btn btn-warning" href="/inlislite3SalahUpdategan/gii">Kembali</a>        <a class="btn btn-primary" href="/inlislite3SalahUpdategan/gii/default/update?id=%24model-%3Eid">Koreksi</a>        <a class="btn btn-danger" href="/inlislite3SalahUpdategan/gii/default/delete?id=%24model-%3Eid" data-confirm="Apakah Anda yakin ingin menghapus item ini?" data-method="post">Hapus</a>    </p>



    <?= DetailView::widget([
            'model' => $model,
            
        'attributes' => [
            'hari',
            [
                        'attribute'=>'jam_buka',
                        'format'=>['time',(isset(Yii::$app->modules['datecontrol']['displaySettings']['time'])) ? Yii::$app->modules['datecontrol']['displaySettings']['time'] : 'H:i:s A'],
                        'type'=>DetailView::INPUT_WIDGET,
                        'widgetOptions'=> [
                            'class'=>DateControl::classname(),
                            'type'=>DateControl::FORMAT_TIME
                        ]
                    ],
            [
                        'attribute'=>'jam_tutup',
                        'format'=>['time',(isset(Yii::$app->modules['datecontrol']['displaySettings']['time'])) ? Yii::$app->modules['datecontrol']['displaySettings']['time'] : 'H:i:s A'],
                        'type'=>DetailView::INPUT_WIDGET,
                        'widgetOptions'=> [
                            'class'=>DateControl::classname(),
                            'type'=>DateControl::FORMAT_TIME
                        ]
                    ],
        ],
       
    ]) ?>

</div>
