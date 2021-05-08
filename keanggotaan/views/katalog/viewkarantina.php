<?php



use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;    
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\QuarantinedCollections $model
 */

$this->title = $model->BIBID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quarantined Catalogs'), 'url' => ['karantina']];
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
            /*[
                'attribute'=>'Worksheet_id',
                'value'=>$model->worksheet->Name,
            ],*/
            'Title',
            'Author',
            'Edition',
            'Publisher',
            'PublishLocation',
            'PublishYear',
            'PublishYear',
            'PhysicalDescription',
            'Subject',
            'ISBN',
            'CallNumber',
            'Note',
            'IsOPAC:boolean'
        ],
       
    ]) ?>

</div>
