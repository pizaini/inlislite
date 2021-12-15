<?php



use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Letter $model
 */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Letters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="letter-view">
   <p> <a class="btn btn-warning" href="/inlis32/backend/gii">Kembali</a>        <a class="btn btn-primary" href="/inlis32/backend/gii/default/update?id=%24model-%3Eid">Koreksi</a>        <a class="btn btn-danger" href="/inlis32/backend/gii/default/delete?id=%24model-%3Eid" data-confirm="Apakah Anda yakin ingin menghapus item ini?" data-method="post">Hapus</a>    </p>



    <?= DetailView::widget([
            'model' => $model,
            
        'attributes' => [
            'TYPE_OF_DELIVERY',
            [
                        'attribute'=>'LETTER_DATE',
                        'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'],
                        // 'type'=>DetailView::INPUT_WIDGET,
                        'widgetOptions'=> [
                            'class'=>DateControl::classname(),
                            'type'=>DateControl::FORMAT_DATE
                        ]
                    ],
            'LETTER_NUMBER',
            [
                        'attribute'=>'ACCEPT_DATE',
                        'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'],
                        // 'type'=>DetailView::INPUT_WIDGET,
                        'widgetOptions'=> [
                            'class'=>DateControl::classname(),
                            'type'=>DateControl::FORMAT_DATE
                        ]
                    ],
            'SENDER',
            'PHONE',
            'INTENDED_TO',
            'IS_PRINTED',
            'PUBLISHER_ID',
            'LETTER_NUMBER_UT',
            'IS_SENDEDEMAIL:email',
            'IS_NOTE',
            'LANG',
        ],
       
    ]) ?>

</div>
