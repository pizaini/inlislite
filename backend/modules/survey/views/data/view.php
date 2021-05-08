<?php



use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Survey $model
 */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Survey', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-view">
   <p>  
        <a class="btn btn-warning" href="index">Kembali</a>        
        <a class="btn btn-primary" href="update?id=<?=$model->ID ?>">Koreksi</a>        
        <a class="btn btn-danger" href="delete?id=<?= $model->ID ?>" data-confirm="Apakah Anda yakin ingin menghapus item ini?" data-method="post">Hapus</a>    
    </p>



    <?= DetailView::widget([
            'model' => $model,
            
        'attributes' => [
            'NamaSurvey',
            [
                        'attribute'=>'TanggalMulai',
                        'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
                       // 'type'=>DetailView::INPUT_WIDGET,
                        'widgetOptions'=> [
                            'class'=>DateControl::classname(),
                            'type'=>DateControl::FORMAT_DATETIME
                        ]
                    ],
            [
                        'attribute'=>'TanggalSelesai',
                        'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
   //                     'type'=>DetailView::INPUT_WIDGET,
                        'widgetOptions'=> [
                            'class'=>DateControl::classname(),
                            'type'=>DateControl::FORMAT_DATETIME
                        ]
                    ],
            'IsActive:boolean',
            'NomorUrut',
            'TargetSurvey',
            'HasilSurveyShow',
            'RedaksiAwal:ntext',
            'RedaksiAkhir:ntext',
            'Keterangan',
        ],
       
    ]) ?>

</div>
