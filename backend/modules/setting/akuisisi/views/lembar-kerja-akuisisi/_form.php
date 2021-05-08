<?php

$this->registerJs(
   '$("document").ready(function(){ 
        $("#worksheetsInput").on("pjax:end", function() {
            $.pjax.reload({container:"#worksheetfieldGridview"});  //Reload GridView
        });
    });'
);
?>

<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use common\models\Worksheets;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="settingparameters-form">

<?php Pjax::begin(['id' => 'worksheetsInput']); ?>

<?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'formConfig' => ['labelSpan' => 2, 'deviceSize' => ActiveForm::SIZE_SMALL],'options' => ['data-pjax' => true ]]); ?>

<?php echo '<p>'.$form->field($model, 'ID')->widget('\kartik\widgets\Select2', [
    'data' => ArrayHelper::map(Worksheets::find()->addSelect(['ID','(CASE WHEN Keterangan IS NULL OR Keterangan = "" THEN Name ELSE CONCAT(Name,\'(\',Keterangan,\')\') END) AS Name'])->all(),'ID','Name'),
    'pluginOptions' => [
        // 'allowClear' => true,
        'width'=> '300px',
    ],
    'options' => ['onchange' => 'this.form.submit()'],
])->label(Yii::t('app','Jenis Bahan'));?>

<?php ActiveForm::end(); ?>

<?php Pjax::end(); ?>

<?php Pjax::begin(['id' => 'worksheetfieldGridview']); ?>

<?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'Tag',
                'value'=>'field.Tag',
                'contentOptions' => ['style' => 'width: 100px;'],
                'filter'=>false,
            ],
            [
                'attribute'=>'Field_id',
                'value'=>'field.Name',
                'filter'=>false,
            ],
            [
                'name' => 'checkbox-column',
                'header' => false,
                'class'=>'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($searchModel, $key, $index, $column) {
                    return [
                        'value' => $searchModel->IsAkuisisi,
                        'checked' => $searchModel->IsAkuisisi,
                        'onchange' => 'var checked = $(this).is(":checked");$.ajax("is-akuisisi", {data: {id: $(this).closest("tr").data("key"), checked: checked}});',
                    ];
                }
            ],
            
        ],
        'pjax'=>true,
        'pjaxSettings'=>[
            'neverTimeout'=>true,
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'summary'=>'',
        'options' => ['style' => 'width: 550px;'],
    ]); ?>

<?php Pjax::end(); ?>

<p><i><?= yii::t('app','Catatan')?> : <?= yii::t('app','Data otomatis tersimpan ketika cek dicentang atau tidak dicentang.')?></i> </p>
</div>


