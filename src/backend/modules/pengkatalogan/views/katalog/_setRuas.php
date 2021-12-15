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
<b>Tag <?=(string)$tag?> - <?=(string)$tagdesc?></b>
</div>


<div class="modal-body" >


<?php 
if($isAdvanceEntryCatalog == '1'){
    echo GridView::widget([
        'id' => 'fielddatas-grid',
        'dataProvider' => $dataProvider,
        'summary'=>'',
        'emptyText'=>'',
        'columns' => [
            [
                'attribute'=>'Code',
                'contentOptions'=>['style'=>'width: 100px; text-align:center'],
            ],
            [
                 'attribute'=>'Name',
                'contentOptions'=>['style'=>'width: 200px'],
            ],   
            [
                'attribute'=>'Isi',
                'label'=>yii::t('app','Isi'),
                'value'=>function ($data, $key, $index,$column) {
                    return '<input type="text" id="FieldDatas_'.$data->Code.'" name="FieldDatas_'.$data->Code.'" value="'.trim($data->Isi).'" class="form-control" maxlength="255">';
                },
                'format'=>'raw'
            ]
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'bordered'=>true,
        'striped'=>true,
    ]);
}else{
    echo GridView::widget([
        'id' => 'fielddatas-grid',
        'dataProvider' => $dataProvider,
        'summary'=>'',
        'emptyText'=>'',
        'columns' => [
            [
                'attribute'=>'Code',
                'contentOptions'=>['style'=>'width: 100px; text-align:center'],
            ],
            [
                 'attribute'=>'Name',
                'contentOptions'=>['style'=>'width: 200px'],
            ],   
            [
                'attribute'=>'Isi',
                'label'=>yii::t('app','Isi'),
                'value'=>function ($data, $key, $index,$column) {
                    return '<input type="text" id="FieldDatas_'.$data->Code.'" name="FieldDatas_'.$data->Code.'" value="'.trim($data->Isi).'" class="form-control" maxlength="255">';
                },
                'format'=>'raw'
            ]
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'bordered'=>true,
        'striped'=>true,
    ]);
}
 
?>


</div>

<div class="modal-footer" >
  <?php 

    if($sort != ''){
        $idtext = (string)$tag.'_'.(string)$sort;
    }else{
        $idtext = (string)$tag;
    }
  echo Html::a(Yii::t('app', 'OK'), '#', 
    [
      'id' => "ok-ruas-config-modal",
      'class' => 'btn btn-success',
      'onclick' => 'js:SendRuas(TagsValue_'.$idtext.','.$isAdvanceEntryCatalog.');',

    ]);

    ?>

    <?=Html::a(Yii::t('app', 'Cancel'), 'javascript:void()', 
        [
            'id' => "cancel-ruas-config-modal",
            'class' => 'btn btn-warning',
            'data-dismiss' => 'modal'

        ])?>
</div>


</div>

