<?php 
use yii\helpers\Html;
use kartik\grid\GridView;

/**
 * @var yii\web\View $this
 * @var common\models\Fields $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="modal-header" >
<b>Indicator 1 - Tag <?=(string)$tag?></b>
</div>


<div class="modal-body" >


<?php 
echo GridView::widget([
    'id' => 'fieldindicator1s-grid',
    'dataProvider' => $dataProvider,
    'summary'=>'',
    'emptyText'=>'',
    'columns' => [
        [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'width: 50px;'],
                'template' => '<span style="display:inline">{choose}</span>',
                'buttons' => [
                  'choose' => function ($url, $model) use ($sort,$tag) {
                    if($sort != ''){
                        $idtext = (string)$tag.'_'.(string)$sort;
                    }else{
                        $idtext = (string)$tag;
                    }
                    return Html::a('<span class="glyphicon glyphicon-check"></span> '.Yii::t('app','Choose'), '#', [
                                  'title' => Yii::t('app', 'Choose'), 
                                  //'data-toggle' => 'tooltip',
                                  'class' => 'btn btn-primary btn-sm',
                                  'onClick' => 'js:SendIndicator("Indicator1_'.$idtext.'","'.$model->Code.'");'
                                ]);},

                ],
            ],
        [
            'attribute'=>'Code',
            'contentOptions'=>['style'=>'width: 50px; text-align:center'],
        ],
        [
             'attribute'=>'Name',
        ]
    ],
    'responsive'=>true,
    'hover'=>true,
    'condensed'=>true,
    'bordered'=>true,
    'striped'=>true,
]); 
?>


</div>


</div>

