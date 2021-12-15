

<?php 
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use common\models\Worksheets;
use common\models\Collectionsources;
use common\models\Collectionmedias;
use common\models\Collectioncategorys;
use common\models\Collectionrules;
use common\models\Partners;
use common\models\Locations;
use common\models\LocationLibrary;
use common\models\Collectionstatus;
use common\models\Currency;
use yii\helpers\ArrayHelper;
use common\widgets\MaskedDatePicker;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use kartik\select2\Select2Asset;

//handle for pjax reload on select2
Select2Asset::register($this);
/**
 * @var yii\web\View $this
 * @var common\models\Fields $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<script type="text/javascript">
    function SaveArticle() {
        isLoading = false;
        $('#form-article-modal').data('yiiActiveForm').submitting = true;
        $('#form-article-modal').yiiActiveForm('validate');
    }
</script>

<style type="text/css">
.ui-autocomplete-input {
  z-index: 1511;
}
.ui-autocomplete {
  z-index: 1510 !important;
}
</style>
<input type="hidden" id="hdnCollectionId" value="<?=$library['id']?>">
<?php 

$form = ActiveForm::begin([
'id'=>"form-edisi-serial",
'method'=>'post',
//'enableAjaxValidation' => true,
'enableClientValidation' => true,
'type'=>ActiveForm::TYPE_HORIZONTAL,
'formConfig' => ['labelSpan'=>4,'deviceSize' => ActiveForm::SIZE_SMALL],
'action'=> ['save-edisi-serial?id='.$id.'&refer='.$refer.'&catalogId='.$catalogid],
]); ?>

<div class="modal-header" >
<h4 class="modal-title"><?=$header?></h4>
</div>
<br>
<input type="hidden" id="MasterEdisiSerial" name="MasterEdisiSerial[Catalog_id]" value="<?=$library['catalog_id']?>">
              <!-- <?//php echo '<pre>'; echo $library['catalog_id']; echo '</pre>'; ?> -->
                <div class="form-group kv-fieldset-inline">
                    <div class="col-sm-8">
                        <?= $form->field($model,'no_edisi_serial')->textInput(['inline'=>true,'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Title').'...'])->label(yii::t('app','Nomor Edisi Serial'))?>
                    </div>
                    <div class="col-sm-4">
                        &nbsp;
                    </div>
                </div>

                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">

                  <?php 
                  echo $form->field($model, 'tgl_edisi_serial')->widget(MaskedDatePicker::classname(), 
                  [
                    'enableMaskedInput' => true,
                    'maskedInputOptions' => [
                        'mask' => '9999-99-99',
                        'pluginEvents' => [
                            'complete' => "function(){console.log('complete');}"
                        ]
                    ],
                   'removeButton' => false,
                   'options'=>[
                                    'style'=>'width:170px',
                                ],
                    'pluginOptions' => [
                                  'autoclose' => true,
                                  'todayHighlight' => true,
                                  'format'=>'yyyy-mm-dd',
                                ]
                  ])->label(yii::t('app','Tanggal Terbit Edisi Serial'));
                  ?>
                  </div>
                  <div class="col-sm-4">
                      &nbsp;
                  </div>
                </div>


                <hr>               

<div class="modal-footer" >
  <?=Html::a(Yii::t('app', 'Save'), 'javascript:void(0)', 
    [
      'id' => "add-collection-modal",
      'class' => 'btn btn-success',
      'onClick' => "js:SaveSerial();",

    ])?>
  <?=Html::a(Yii::t('app', 'Cancel'), 'javascript:void(0)', 
    [
      'id' => "cancel-collection-modal",
      'class' => 'btn btn-warning',
      'data-dismiss' => 'modal'

    ])?>
</div>

<?php
ActiveForm::end(); 
?>
<!-- HISTORY -->
<?php
  if(!$model->isNewRecord)
  {
    echo \common\widgets\Histori::widget([
            'model'=>$model,
            'id'=>'collection-catalogs',
            'urlHistori'=>'detail-histori?id='.$model->ID
        
    ]);
  }
?>
