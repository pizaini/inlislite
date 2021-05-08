<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var common\models\Collections $model
 */

$this->title = $model->Title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Proposed Collections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quarantined-collections-view">
   <p> <?= Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) ?>
    </p>

    <?= DetailView::widget([
            'model' => $model,
            
        'attributes' => [
            /*'ID',
            'Type',*/
            'Title',
            /*'Subject',*/
            'Author',
            //'Publishment',
            'PublishLocation',
            'PublishYear',
            'Publisher',
            'DateRequest:date',
            /*'Comments',*/
            'member.MemberNo',
            /*'CallNumber',
            'ControlNumber',*/
            'Status',
            'CreateBy',
            'CreateDate',
            'CreateTerminal',
            /*[
                'attribute'=>'WorksheetID',
                'value'=>'worksheet.Name',
            ]*/
            
        ],
       
    ]) ?>

</div>