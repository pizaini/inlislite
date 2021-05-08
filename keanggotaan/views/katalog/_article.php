

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
use kartik\widgets\Select2;

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
<input type="hidden" id="hdnCollectionId" value="<?=$id?>">
<?php
$edisi = Yii::$app->db->createCommand("SELECT col.EDISISERIAL 

FROM collections col 
JOIN catalogs c ON col.Catalog_id = c.id
JOIN worksheets w ON c.`Worksheet_id`=w.`ID` 

WHERE w.`ISSERIAL` =1 and c.id = ".$catalogid."
GROUP BY col.EDISISERIAL 
ORDER BY c.`Title` ASC;")->queryAll();

if ($edisi){
    foreach ($edisi as $key => $value){
        $dataEdisiSerial[$value['EDISISERIAL']]= $value['EDISISERIAL'];
    }
}

$terbit = Yii::$app->db->createCommand("SELECT col.EDISISERIAL 

FROM collections col 
JOIN catalogs c ON col.Catalog_id = c.id
JOIN worksheets w ON c.`Worksheet_id`=w.`ID` 

WHERE w.`ISSERIAL` =1 and c.id = ".$catalogid."
GROUP BY col.EDISISERIAL 
ORDER BY c.`Title` ASC;")->queryAll();

if ($terbit){
    foreach ($terbit as $key => $value){
        $dataTGLterbit[$value['EDISISERIAL']]= $value['EDISISERIAL'];
    }
}
$form = ActiveForm::begin([
'id'=>"form-article-modal",
'method'=>'post',
//'enableAjaxValidation' => true,
'enableClientValidation' => true,
'type'=>ActiveForm::TYPE_HORIZONTAL,
'formConfig' => ['labelSpan'=>4,'deviceSize' => ActiveForm::SIZE_SMALL],
'action'=> ['save-catalogs-article?id='.$id.'&refer='.$refer.'&catalogId='.$catalogid],
]); ?>

<div class="modal-header" >
<h4 class="modal-title"><?=$header?></h4>
</div>
<br>
<input type="hidden" id="articleAct" name="ArticleAct" value="save">

                <div class="form-group kv-fieldset-inline">
                    <div class="col-sm-8">
                        <?= $form->field($model,'Title')->textInput(['inline'=>true,'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Title').'...'])?>
                    </div>
                    <div class="col-sm-4">
                        &nbsp;
                    </div>
                </div>
                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                     <?= $form->field($model, 'EDISISERIAL')->widget(Select2::classname(), [
                      'data' => $dataEdisiSerial,
                      'options' => ['placeholder' => Yii::t('app', 'Enter').' '.Yii::t('app', 'coll_Edisiserial')],
                      'pluginOptions' => [
                      'allowClear' => true,

                      ],
                      ]); ?>
                  </div>
                  <div class="col-sm-4">
                    &nbsp;
                  </div>
                </div>

                <div class="form-group kv-fieldset-inline">
                    <div class="col-sm-8">
                        <?= $form->field($model, 'TANGGAL_TERBIT_EDISI_SERIAL')->widget(Select2::classname(), [
                            'data' => $dataTGLterbit,
                            'options' => ['placeholder' => Yii::t('app', 'Enter').' '.Yii::t('app', 'TANGGAL_TERBIT_EDISI_SERIAL')],
                            'pluginOptions' => [
                                'allowClear' => true,

                            ],
                        ]); ?>
                    </div>
                    <div class="col-sm-4">
                        &nbsp;
                    </div>
                </div>

                <div class="form-group kv-fieldset-inline">
                    <div class="col-sm-8">
                        <?= $form->field($model,'Article_type')->textInput(['inline'=>true,'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Article_type').'...'])?>          </div>
                    <div class="col-sm-4">
                        &nbsp;
                    </div>
                </div>
                <div class="form-group kv-fieldset-inline">
                    <div class="col-sm-8">
                        <?= $form->field($model,'Creator')->textInput(['inline'=>true,'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Creator').'...'])?>          </div>
                    <div class="col-sm-4">
                        &nbsp;
                    </div>
                </div>


                <div class="form-group kv-fieldset-inline">
                    <div class="col-sm-8">
                        <?= $form->field($model,'Contributor')->textInput(['inline'=>true,'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Contributor').'...'])?>          </div>
                    <div class="col-sm-4">
                        &nbsp;
                    </div>
                </div>


                <div class="form-group kv-fieldset-inline">
                    <div class="col-sm-8">
                        <?= $form->field($model,'StartPage')->textInput(['type' => 'number','inline'=>true,'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'StartPage').'...'])?>          </div>
                    <div class="col-sm-4">
                        &nbsp;
                    </div>
                </div>


                <div class="form-group kv-fieldset-inline">
                    <div class="col-sm-8">
                        <?= $form->field($model,'Pages')->textInput(['type' => 'number','inline'=>true,'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Pages').'...'])?>          </div>
                    <div class="col-sm-4">
                        &nbsp;
                    </div>
                </div>

                <div class="form-group kv-fieldset-inline">
                    <div class="col-sm-8">
                        <?= $form->field($model,'Subject')->textInput(['inline'=>true,'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Subject').'...'])?>          </div>
                    <div class="col-sm-4">
                        &nbsp;
                    </div>
                </div>

                <div class="form-group kv-fieldset-inline">
                    <div class="col-sm-8">
                        <?= $form->field($model,'Abstract')->textarea(['inline'=>true,'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Abstract').'...'])?>          </div>
                    <div class="col-sm-4">
                        &nbsp;
                    </div>
                </div>

                <div class="form-group kv-fieldset-inline">
                    <div class="col-sm-8">
                        <?= $form->field($model,'ISOPAC')->checkbox(['label'=>'Tampil di OPAC','style'=>'font-weight:bold'],['inline'=>true])?>
                    </div>
                    <div class="col-sm-4">
                        &nbsp;
                    </div>
                </div>



            <div class="modal-footer" >
                <?=Html::a(Yii::t('app', 'Save'), 'javascript:void(0)',
                    [
                        'id' => "add-collection-modal",
                        'class' => 'btn btn-success',
                        'onClick' => "js:SaveArticle();",

                    ])?>
                <?=Html::a(Yii::t('app', 'Cancel'), 'javascript:void(0)',
                    [
                        'id' => "cancel-collection-modal",
                        'class' => 'btn btn-warning',
                        'data-dismiss' => 'modal'

                    ])?>
            </div>


                <hr>


<?php
ActiveForm::end(); 
?>
<!-- HISTORY -->
<?php
  if(!$model->isNewRecord)
  {
    echo \common\widgets\Histori::widget([
            'model'=>$model,
            'id'=>'article-catalogs',
            'urlHistori'=>'detail-histori-article?id='.$model->id
        
    ]);
  }
?>
