

<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use kartik\grid\GridView;



/**
 * @var yii\web\View $this
 * @var common\models\Collections $model
 * @var yii\widgets\ActiveForm $form
 */

//print_r($dataProvider->allModels);
?>
<?php 
function encode_items(&$item, $key)
{
    $item = utf8_encode($item);
}

echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'width: 50px;'],
                'template' => '<span style="display:inline">{choose}</span>',
                'buttons' => [
                    'choose' => function ($url, $model)  {
                    /*$id= $model->ID;
                    $code= $model->Tag;
                    $desc= $model->Name;
                    $fixed= (int)$model->Fixed;
                    $enabled= (int)$model->Enabled;
                    $panjang= $model->Length;
                    $mandatory= (int)$model->Mandatory;
                    $iscustomable= (int)$model->IsCustomable;
                    $repeatable= (int)$model->Repeatable;*/

                    array_walk_recursive($model['Taglist'], 'encode_items');
                    $taglist= json_encode($model['Taglist']);
                    if($model['Taglist']['inputvalue']['264']){
                        $rda=1;
                    }else{
                        $rda=0;
                    }
                    return Html::a('<span class="glyphicon glyphicon-check"></span> '.Yii::t('app','Choose'), '#', [
                                  'title' => Yii::t('app', 'Choose'), 
                                  //'data-toggle' => 'tooltip',
                                  'data-dismiss'=>'modal',
                                  'class' => 'btn bg-maroon btn-sm',
                                  'onClick' => 'js:sendTaglist('.$taglist.','.$rda.');'
                                ]);},

                ],
            ],
            ['class' => 'yii\grid\SerialColumn'],
            'Title',
            'Author',
            'PublishLocation',
            'Publisher',
            'PublishYear',
            'Subject',
            [
                         //'label'=>'Nama',
                         'format'=>'raw',
                         'attribute'=>'Mode',
                         'value' => function($data){
                            if($data['Mode']=='1')
                            {
                              return '<span class="label label-success">RDA&nbsp;&nbsp;</span>';
                            }else{
                              return '<span class="label label-primary">AACR</span>';
                            }
                         }
            ]
            
        ],
        'containerOptions'=>['style'=>'font-size:12px'],
        //'summary'=>'',
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
    ]);  ?>



