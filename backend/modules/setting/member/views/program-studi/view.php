<?php



use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\MasterProgramStudi $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Program Studis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-program-studi-view">
   <p> <a class="btn btn-warning" href="/gii">Kembali</a>        <a class="btn btn-primary" href="/gii/default/update?id=%24model-%3Eid">Koreksi</a>        <a class="btn btn-danger" href="/gii/default/delete?id=%24model-%3Eid" data-confirm="Apakah Anda yakin ingin menghapus item ini?" data-method="post">Hapus</a>    </p>



    <?= DetailView::widget([
            'model' => $model,
            
        'attributes' => [
            'id',
            'id_jurusan',
            'Nama',
            [
                        'attribute'=>'KIILastUploadDate',
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
