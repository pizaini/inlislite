<?php



use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Requestcatalog $model
 */

$this->title = $model->Title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Requestcatalogs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requestcatalog-view">
   <p> <a class="btn btn-warning" href="/inlislite3/keanggotaan/gii">Kembali</a>        <a class="btn btn-primary" href="/inlislite3/keanggotaan/gii/default/update?id=%24model-%3Eid">Koreksi</a>        <a class="btn btn-danger" href="/inlislite3/keanggotaan/gii/default/delete?id=%24model-%3Eid" data-confirm="Apakah Anda yakin ingin menghapus item ini?" data-method="post">Hapus</a>    </p>



    <?= DetailView::widget([
            'model' => $model,
            
        'attributes' => [
            'Type',
            'Title',
            'Subject',
            'Author',
            'PublishLocation',
            'PublishYear',
            'Publisher',
            'Comments:ntext',
            'MemberID',
            'CallNumber',
            'ControlNumber',
            [
                        'attribute'=>'DateRequest',
                        'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
                        'type'=>DetailView::INPUT_WIDGET,
                        'widgetOptions'=> [
                            'class'=>DateControl::classname(),
                            'type'=>DateControl::FORMAT_DATETIME
                        ]
                    ],
            'Status',
            'WorksheetID',
        ],
       
    ]) ?>

</div>
