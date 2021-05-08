<?php


use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use common\models\Formats;
use kartik\grid\GridView;

/**
 * @var yii\web\View $this
 * @var common\models\Fields $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="fields-form">

<div class="col-xs-6 col-sm-4">
<fieldset>
            <legend style="background-color: #f2f0f0">
                <div class="row">
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 18px">Format penamaan dan aturan tag</span>
                </div>
            </legend>
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'Format_id'=>[
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\Select2', 
            'options'=>[
                'data'=>ArrayHelper::map(Formats::find()->all(),'ID','Name'),
                'options'=> ['placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'Format')], 
                'pluginOptions' => [
                    'allowClear' => true,
                    'width'=> '200px',
                ],

            ],
        ],

'Tag'=>['type'=> Form::INPUT_TEXT, 'options'=>['style'=>'width:50%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Tag').'...', 'maxlength'=>3]], 

'Name'=>['type'=> Form::INPUT_TEXT, 'options'=>['style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nama').'...', 'maxlength'=>100]], 

//'Format_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Format ID').'...']], 

//'Group_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Group ID').'...']], 

'Fixed'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Fixed').'...']], 

'Enabled'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Enabled').'...']], 

'Length'=>['type'=> Form::INPUT_TEXT, 'options'=>['style'=>'width:150px','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Length').'...']], 

'Repeatable'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Repeatable').'...']], 

'Mandatory'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Mandatory').'...']], 

'IsCustomable'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Is Customable').'...']], 

//'IsDelete'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Is Delete').'...']], 

//'ISSUBSERIAL'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Issubserial').'...']], 

//'DEFAULTSUBTAG'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Defaultsubtag').'...', 'maxlength'=>12]], 

    ]


    ]);
    ?>

</fieldset>
</div>
    
<div class="col-xs-6 col-sm-8" style="display: <?php echo $model->Fixed == 1 ? "none" : "block"; ?>">
        <fieldset>
            <legend style="background-color: #f2f0f0; margin-bottom: -3px">
                <div class="row">
                    <div class="col-xs-6 col-sm-6" >
                        &nbsp;<span style="font-size: 18px">Indikator 1</span>
                    </div>
                    <div class="col-xs-6 col-sm-6">
                        <small><?php echo Html::a("<i class='glyphicon glyphicon-plus'></i> Tambah Indikator 1", '#', ['class' => 'btn pull-right btn-primary btn-sm', 'data-toggle' => 'modal', 'data-target' => '#indikator1-modal']); ?></small>
                    </div>
                </div>
            </legend>
            <?php 

            echo GridView::widget([
                'id' => 'indikator1-grid',
                'dataProvider' => $indikator1,
                'summary'=>'',
                'emptyText'=>'',
                'columns' => [
                    [
                        'attribute'=>'Code',
                        'value'=>function ($data, $key, $index,$column) {
                           return '<input type="text" name="['.$index.']Code" value="'.$data->Code.'" class="form-control" maxlength="255">';
                        },
                        'contentOptions'=>['style'=>'width: 75px;text-align:center;'],
                        'format'=>'raw'
                    ],
                    [
                        'attribute'=>'Name',
                        'value'=>function ($data, $key, $index,$column) {
                           return '<input type="text" name="['.$index.']Name" value="'.$data->Name.'" class="form-control" maxlength="255">';
                        },
                        'format'=>'raw'
                    ],   
                    
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions'=>['style'=>'width: 50px;text-align:center'],
                        'template' => '{delete}',
                        'buttons' => [
                                                          
                        'delete' => function ($url, $data) {
                                            return Html::a('<span class="glyphicon glyphicon-remove"></span>', 'javascript:void()', [
                                                            'title' => Yii::t('app', 'Delete'),
                                                            'class' => 'btn-danger btn-sm',
                                                            'onclick' => 'js:removeIndikator(this,event);'
                                                          ]);},

                        ],
                    ],
                ],
                'responsive'=>true,
                'hover'=>true,
                'condensed'=>true,
                'bordered'=>true,
                'striped'=>true,
            ]);  ?>

           
        </fieldset>
        <fieldset>
            <legend style="background-color: #f2f0f0; margin-bottom: -3px">
                <div class="row">
                    <div class="col-xs-6 col-sm-6" >
                         &nbsp;<span style="font-size: 18px">Indikator 2</span>
                    </div>
                    <div class="col-xs-6 col-sm-6">
                        <small><?php echo Html::a("<i class='glyphicon glyphicon-plus'></i> Tambah Indikator 2", '#', ['class' => 'btn pull-right btn-primary btn-sm', 'data-toggle' => 'modal', 'data-target' => '#indikator2-modal']); ?></small>
                    </div>
                </div>
            </legend>
            <?php 

           
            echo GridView::widget([
                'id' => 'indikator2-grid',
                'dataProvider' => $indikator2,
                'summary'=>'',
                'emptyText'=>'',
                'columns' => [
                    [
                        'attribute'=>'Code',
                        'value'=>function ($data, $key, $index,$column) {
                           return '<input type="text" name="['.$index.']Code" value="'.$data->Code.'" class="form-control" maxlength="255">';
                        },
                        'contentOptions'=>['style'=>'width: 75px;text-align:center;'],
                        'format'=>'raw'
                    ],
                    [
                        'attribute'=>'Name',
                        'value'=>function ($data, $key, $index,$column) {
                           return '<input type="text" name="['.$index.']Name" value="'.$data->Name.'" class="form-control" maxlength="255">';
                        },
                        'format'=>'raw'
                    ],   
                    
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions'=>['style'=>'width: 50px;text-align:center'],
                        'template' => '{delete}',
                        'buttons' => [
                                                          
                        'delete' => function ($url, $data) {
                                            return Html::a('<span class="glyphicon glyphicon-remove"></span>', 'javascript:void()', [
                                                            'title' => Yii::t('app', 'Delete'),
                                                            'class' => 'btn-danger btn-sm',
                                                            'onclick' => 'js:removeIndikator(this,event);'
                                                          ]);},

                        ],
                    ],
                ],
                'responsive'=>true,
                'hover'=>true,
                'condensed'=>true,
                'bordered'=>true,
                'striped'=>true,
            ]);  ?>
        </fieldset>
        <fieldset>
            <legend style="background-color: #f2f0f0; margin-bottom: -3px">
                <div class="row">
                    <div class="col-xs-6 col-sm-6" >
                         &nbsp;<span style="font-size: 18px">Sub Ruas</span>
                    </div>
                    <div class="col-xs-6 col-sm-6">
                        <small><?php echo Html::a("<i class='glyphicon glyphicon-plus'></i> Tambah Subruas", '#', ['class' => 'btn pull-right btn-primary btn-sm', 'data-toggle' => 'modal', 'data-target' => '#ruas-modal']); ?></small>
                    </div>
                </div>
            </legend>
            <?php 
            
            echo GridView::widget([
                'id' => 'ruas-grid',
                'dataProvider' => $subruas,
                'summary'=>'',
                'emptyText'=>'',
                'columns' => [
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions'=>['style'=>'width: 90px; text-align:center'],
                        'template' => '<p>{Up}&nbsp;{Down}</p>',
                        'buttons' => [
                                                          
                        'Up' => function ($url, $model) {
                                            return Html::a('<span class="glyphicon glyphicon-arrow-up"></span>', '#', 
                                                          [
                                                            'class' => 'btn-success btn-sm',
                                                            'title' => Yii::t('app', 'Up'),
                                                            'onclick' => 'js:moveIndikator(1,this,event);'
                                                          ]);},
                        'Down' => function ($url, $model) {
                                            return Html::a('<span class="glyphicon glyphicon-arrow-down"></span>', '#', 
                                                           [
                                                            'class' => 'btn-success btn-sm',
                                                            'title' => Yii::t('app', 'Down'),
                                                            'onclick' => 'js:moveIndikator(-1,this,event);'
                                                            
                                                          ]);},

                        ],
                    ],
                    [
                        'attribute'=>'Code',
                        'value'=>function ($data, $key, $index,$column) {
                           return '<input type="text" name="['.$index.']Code" value="'.$data->Code.'" class="form-control" maxlength="255">';
                        },
                        'contentOptions'=>['style'=>'width: 75px'],
                        'format'=>'raw'
                    ],
                    [
                        'attribute'=>'Name',
                        'value'=>function ($data, $key, $index,$column) {
                           return '<input type="text" name="['.$index.']Name" value="'.$data->Name.'" class="form-control" maxlength="255">';
                        },
                        'format'=>'raw'
                    ],
                    [
                        'attribute'=>'Delimiter',
                        'value'=>function ($data, $key, $index,$column) {
                           return '<input type="text" name="['.$index.']Delimiter" value="'.$data->Delimiter.'" class="form-control" maxlength="5">';
                        },
                        'contentOptions'=>['style'=>'width: 75px'],
                        'format'=>'raw'
                    ],
                    [
                        'attribute'=>'Repeatable',
                        'value'=>function ($data, $key, $index,$column) {
                            if ($data->Repeatable == "1" ){
                                return '<input type="checkbox" name="['.$index.']Repeatable" checked >';
                            }else{
                                return '<input type="checkbox" name="['.$index.']Repeatable"  >';
                            }
                           
                        },
                        'contentOptions'=>['style'=>'width: 75px; text-align:center'],
                        'format'=>'raw'
                    ],  
                    [
                        'attribute'=>'IsShow',
                        'value'=>function ($data, $key, $index,$column) {
                            if ($data->IsShow == "1" ){
                                return '<input type="checkbox" name="['.$index.']IsShow" checked >';
                            }else{
                                return '<input type="checkbox" name="['.$index.']IsShow"  >';
                            }
                           
                        },
                        'contentOptions'=>['style'=>'width: 75px; text-align:center'],
                        'format'=>'raw'
                    ], 
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions'=>['style'=>'width: 50px;text-align:center'],
                        'template' => '{delete}',
                        'buttons' => [
                                                          
                        'delete' => function ($url, $data) {
                                            return Html::a('<span class="glyphicon glyphicon-remove"></span>', 'javascript:void()', [
                                                            'title' => Yii::t('app', 'Delete'),
                                                            'class' => 'btn-danger btn-sm',
                                                            'onclick' => 'js:removeIndikator(this,event);'
                                                          ]);},

                        ],
                    ],
                ],
                'responsive'=>true,
                'hover'=>true,
                'condensed'=>true,
                'bordered'=>true,
                'striped'=>true,
            ]);   ?>

           
        </fieldset>


    </div>
    <?php
      echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;'. Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

    echo  '&nbsp;' . Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-warning']) . '</p>';
    
    ActiveForm::end();
    ?>
    
    <?php 

    echo $this->render('_modalIndikator', 
        [
        'id' => "indikator1-modal", 
        'header' => "Tambah Indikator 1", 
        'model' => $newIndikator1, 
        'targetGridId' => 'indikator1-grid'
        ]);

    echo $this->render('_modalIndikator', 
        [
        'id' => "indikator2-modal", 
        'header' => "Tambah Indikator 2", 
        'model' => $newIndikator2, 
        'targetGridId' => 'indikator2-grid'
        ]);

    echo $this->render('_modalRuas', 
        [
        'id' => "ruas-modal", 
        'header' => "Tambah Ruas", 
        'model' => $newSubruas, 
        'targetGridId' => 'ruas-grid'
        ]);
    ?>
</div>
