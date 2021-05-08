<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use common\widgets\AjaxButton;

/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 */

$this->title = Yii::t('app', 'Pengaturan Setting Clean Assets');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Clean Assets'), 'url' => Url::to(['/setting/umum'])];
$this->params['breadcrumbs'][] = $this->title;
$homeUrl=Yii::$app->homeUrl;

$ajaxOptions = [
        'type' => 'POST',
        'url'  => 'clean-assets',
        'success'=>new yii\web\JsExpression('
            function(data){ 
                console.log(data);
                location.reload();
            }'),
       'error' => new yii\web\JsExpression('function(xhr, ajaxOptions, thrownError){ 
            alert("memang belum");
        }'),
    ];

?>

<style type="text/css">
    .col-sm-4 label{
        font-weight: normal;
    }

    .table{
        margin-bottom: 0px;
    }

    .form-group > .col-sm-offset-3  {
        margin-left: 0px;
    }
</style>


<div class="settingparameters-create">
    <div class="page-header">
        <!-- <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?> -->
        <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    </div>
<div class="settingparameters-form">
  <div class="form-group">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]]); ?>
        

        <div class="form-group field-dynamicmodel-isactivatingimportingauthoritydata required">
            <label class="control-label col-sm-3" for="dynamicmodel-isactivatingimportingauthoritydata"><?= Yii::t('app','Clean Assets Inlislite') ?></label>
            <div class="col-sm-offset-3 col-sm-9">
                <div class="checkbox">
                   <?php 
                    echo AjaxButton::widget([
                        'label' => Yii::t('app','Clean Assets'),
                        'ajaxOptions' => $ajaxOptions,
                        'htmlOptions' => [
                            'class' => 'btn btn-info',
                            'id' => 'btnCleanAssets',
                            'type' => 'submit',
                            'value' => 'cleanAssets'
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <div class="col-sm-offset-3 col-sm-9"></div>
            <div class="col-sm-offset-3 col-sm-9"><div class="help-block"></div></div>
        </div>

        
    </div>

    
    

</div>

</div>


