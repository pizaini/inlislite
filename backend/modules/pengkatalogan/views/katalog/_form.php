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
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use common\widgets\AjaxSubmitButton;
use common\widgets\MaskedDatePicker;
use kartik\widgets\Select2;
use kartik\select2\Select2Asset;

//handle for pjax reload on select2
Select2Asset::register($this);



/**
 * @var yii\web\View $this
 * @var common\models\Collections $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<style type="text/css">
  .standard-error-summary
  {
    background-color: #ffedff;
    padding: 5px;
    border:dashed 2px #ff868c;
    margin-bottom: 10px;
  }

  div.disabled
  {
    pointer-events: none;

    /* for "disabled" effect */
    opacity: 0.8;
    background: #CCC;
  }

  .ui-autocomplete-loading { background:url('../../assets_b/images/loading.gif') no-repeat right center; background-size: 50px; z-index: 1510 !important; }

  #msgform{
      border: dotted 1px red; 
      background-color: #faffe1; 
      margin-bottom: 10px; 
      padding: 10px; 
      display: none;
      font-weight : bold;
      color:red;
  }
</style>

<div class="collections-form">

<div id="msgform"></div>

<?php $form = ActiveForm::begin([
  'id'=>'mainForm',
  'type'=>ActiveForm::TYPE_HORIZONTAL,
  'errorSummaryCssClass'=> 'standard-error-summary',
  'formConfig'=>['labelSpan'=>4, 'deviceSize'=>ActiveForm::SIZE_SMALL],
  ]); ?>

<!-- <?= $form->errorSummary($modelcat); ?> -->

<div class="form-group kv-fieldset-inline">
  <div class="col-sm-8">
  <?php 
  if($for=='cat' && $model->isNewRecord)
  {
    $modelcat->Worksheet_id=1;
  }
$workhsetdisabled=false;
if (!$model->isNewRecord && $for == 'coll')
{
  $workhsetdisabled =true;
}

  echo $form->field($modelcat, 'Worksheet_id')->widget('\kartik\widgets\Select2', [
  'data' => ArrayHelper::map(Worksheets::find()->addSelect(['ID','(CASE WHEN Keterangan IS NULL THEN Name ELSE CONCAT(Name,\'(\',Keterangan,\')\') END) AS Name'])->orderby('NoUrut ASC')->all(),'ID','Name'),
  'options'=> ['placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'cat_Worksheet ID')],
  'pluginOptions' => [
      'allowClear' => ($for == 'cat') ? false : true
  ],
  'disabled'=>$workhsetdisabled,
  'pluginEvents' => [
      "select2:select" => 'function() { 
          var id = $("#catalogs-worksheet_id").val();
          isLoading=true;
           $.ajax({
              type     :"POST",
              cache    : false,
              url  : "'.Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/entry-bib-by-worksheet"]).'?id="+id+"&for='.$for.'&rda='.$rda.'",
              data: $("#entryBibliografi :input").serialize(),
              success  : function(response) {
                  $("#entryBibliografi").html(response);
              }
          });
      }',
  ]
  ])->label(yii::t('app','Jenis Bahan'));?>
  </div>
  <div class="col-sm-4">
      &nbsp;
  </div>
</div>
<?php
if($mode == "create")
{
?>
<div id="entryBibliografi">
<?php 
if($for=='cat')
{
  if($isAdvanceEntry == 1)
  {
    echo $this->render('_entryBibliografiAdvance', [
                'worksheetid' => 1, 
                'isSerial'=> (int)Worksheets::findOne(1)->ISSERIAL,
                'model' => $model, 
                'taglist' => $taglist, 
                'isAdvanceEntry' => $isAdvanceEntry,
                'for'=> $for,
                'rda'=>$rda
            ]); 
  }else{
    echo $this->render('_entryBibliografiSimple', [
                'worksheetid' => 1, 
                'isSerial'=> (int)Worksheets::findOne(1)->ISSERIAL,
                'model' => $model, 
                'modelbib' => $modelbib, 
                'models' => $modelbib, 
                'taglist' => $taglist, 
                'listvar'=>$listvar,
                'isAdvanceEntry' => $isAdvanceEntry,
                'for'=> $for,
                'rda'=>$rda,
                'rulesform'=>$rulesform,
            ]); 
  }
}
?>
</div>
<?php
}else{

?>
<div id="entryBibliografi">
  <?php 
  if($isAdvanceEntry == 1)
  {
    echo $this->render('_entryBibliografiAdvance', [
                'worksheetid' => $worksheetid, 
                'isSerial'=> (int)Worksheets::findOne($worksheetid)->ISSERIAL,
                'model' => $model, 
                'taglist' => $taglist, 
                'isAdvanceEntry' => $isAdvanceEntry,
                'for'=> $for,
                'rda'=>$rda
            ]); 
  }else{
    echo $this->render('_entryBibliografiSimple', [
                'worksheetid' => $worksheetid, 
                'isSerial'=> (int)Worksheets::findOne($worksheetid)->ISSERIAL,
                'model' => $model, 
                'modelbib' => $modelbib, 
                'models' => $modelbib,
                'taglist' => $taglist,  
                'listvar'=>$listvar,
                'isAdvanceEntry' => $isAdvanceEntry,
                'for'=> $for,
                'rda'=>$rda,
                'rulesform'=>$rulesform,
            ]); 
  }
  ?>
</div>
<?php
}
?>

<?php 
if($for == 'coll')
{
?>
<div class="box-group" id="accordion2">
            <div class="panel panel-default">
              <div class="box-header with-border">
                <div class="col-xs-6 col-sm-12" >
                <h4 class="box-title">
                  <a data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
                    <?= yii::t('app','Data Pengadaan')?>
                  </a>
                </h4>
                </div>
              </div>
              <div id="collapseThree" class="panel-collapse collapse in">
                <div class="box-body">
                <?php
                if($mode == "create")
                {
                ?>
                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                    <?= $form->field($model,'JumlahEksemplar')->textInput(['style'=>'width:70px','onblur'=>'js:RenderNoInduk();'], ['inline'=>true])?>
                  </div>
                  <div class="col-sm-4">
                    &nbsp;
                  </div>
                </div>

                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8" id="listNoInduk">
                      
                  </div>
                  <div class="col-sm-4">
                        &nbsp;
                  </div>
                </div>
                <?php
                }else{
                ?>
                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                  <?= $form->field($model,'NoInduk')->textInput(['style'=>'width:180px'], ['inline'=>true])?>
                  </div>
                  <div class="col-sm-4">
                        &nbsp;
                  </div>
                </div>
                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                  <?= $form->field($model,'NomorBarcode')->textInput(['style'=>'width:180px'], ['inline'=>true])?>
                  </div>
                  <div class="col-sm-4">
                        &nbsp;
                  </div>
                </div>
                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                  <?= $form->field($model,'RFID')->textInput(['style'=>'width:180px'], ['inline'=>true])?>
                  </div>
                  <div class="col-sm-4">
                        &nbsp;
                  </div>
                </div>
                <?php  
                }
                ?>

                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">

                  <?php 
                  echo $form->field($model, 'TanggalPengadaan')->widget(MaskedDatePicker::classname(), 
                  [
                    'enableMaskedInput' => true,
                    'maskedInputOptions' => [
                        'mask' => '99-99-9999',
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
                                  'format'=>'dd-mm-yyyy',
                                ]
                  ]);
                  ?>
                  </div>
                  <div class="col-sm-4">
                      &nbsp;
                  </div>
                </div>

                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                  <?= $form->field($model,'Source_id')->widget('\kartik\widgets\Select2',[
                      'data'=>ArrayHelper::map(Collectionsources::find()->all(),'ID','Name'),
                      'pluginOptions' => [
                          'allowClear' => true,
                      ],
                      'options'=> ['placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'coll_Source ID')]
                  ])?>
                  </div>
                  <div class="col-sm-4">
                      &nbsp;
                  </div>
                </div>
                
                 <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                  <?php Pjax::begin(['id' => 'pjax-collection-partners','timeout' => false ]); ?>
                       <?= $form->field($model,'Partner_id')->widget('\kartik\widgets\Select2',[
                            'data'=>ArrayHelper::map(Partners::getOrderAlphabhet(),'ID','Name'),
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                            //'options'=> ['placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'coll_Partner ID')],
                        ])?>
                  <?php Pjax::end(); ?>
                  </div>
                  <div class="col-sm-4" style="padding-left: 0px">

                      <?php 
                      echo '<p>'. Html::a(Yii::t('app', 'Tambah'), 'javascript:void(0)', ['id'=>'btnAddPartners','onclick'=>'js:AddPartners();','class' => 'btn bg-maroon btn-sm']);

                      echo  '&nbsp;' . Html::a(Yii::t('app', 'Edit'), 'javascript:void(0)', ['id'=>'btnEditPartners','onclick'=>'js:EditPartners();','class' => 'btn bg-maroon btn-sm']) . '</p>';
                      ?>

                  </div>
                </div>

                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                  <?= $form->field($model,'Media_id')->widget('\kartik\widgets\Select2',[
                      'data'=>ArrayHelper::map(Collectionmedias::find()->all(),'ID','Name'),
                      'pluginOptions' => [
                          'allowClear' => true,
                      ],
                      //'options'=> ['placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'coll_Media ID')]
                  ])?>
                  </div>
                  <div class="col-sm-4">
                    <div class="pull-left" style="margin-left: -70px">
                      <?php /*echo $form->field($model,'ISREFERENSI')->checkbox(['label' => Yii::t('app', 'Referensi'), 'labelOptions'=>['style'=>'font-weight:bold']]);*/ ?>
                    </div>
                  </div>
                </div>

                <div class="form-group kv-fieldset-inline">

                  <div class="col-sm-8">
                  <?= $form->field($model,'Category_id')->widget('\kartik\widgets\Select2',[
                      'data'=>ArrayHelper::map(Collectioncategorys::find()->all(),'ID','Name'),
                      'pluginOptions' => [
                          'allowClear' => true,
                      ],
                      //'options'=> ['placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'coll_Category ID')]
                  ])?>
                  </div>
                  <div class="col-sm-4">
                        &nbsp;
                  </div>

                </div>

                <div class="form-group kv-fieldset-inline">

                  <div class="col-sm-8">
                  <?= $form->field($model,'Rule_id')->widget('\kartik\widgets\Select2',[
                      'data'=>ArrayHelper::map(Collectionrules::find()->all(),'ID','Name'),
                      'pluginOptions' => [
                          'allowClear' => true,
                      ],
                      //'options'=> ['placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'coll_Rule ID')]
                  ])?>
                  </div>
                  <div class="col-sm-4">
                      &nbsp;
                  </div>

                </div>

                <div class="form-group kv-fieldset-inline">

                  <div class="col-sm-8">
                  <div class="form-group field-collections-location_library_id">
                    <label class="control-label col-sm-4" for="collections-location_library_id"><?= yii::t('app','Lokasi Perpustakaan')?></label>
                    <div class="col-sm-8">
                    <?php 
                    $modelloclib = LocationLibrary::find()
                    ->addSelect([
                        'location_library.ID',
                        'location_library.Name'
                    ])
                    ->innerJoin('userloclibforcol',' location_library.ID=userloclibforcol.LocLib_id')
                    ->where(['userloclibforcol.User_id'=>(string)Yii::$app->user->identity->ID])
                    ->all();
                    
                    $loclib_id='';
                    if($model->isNewRecord)
                    {
                      foreach ($modelloclib as $key => $value) {
                          if($key==0)
                          {
                              $loclib_id = $value->ID;
                              break;
                          }
                      }
                    }else{
                      $loclib_id = $model->location->LocationLibrary_id;
                    }
                    echo Select2::widget([
                    'model' => $model,
                    'attribute' => 'Location_Library_id',
                    'data'=>ArrayHelper::map($modelloclib,'ID','Name'),
                    'pluginOptions' => [
                          'allowClear' => true,
                      ],
                    'pluginEvents' => [
                            "select2:select" => 'function() { 
                                var id = $("#collections-location_library_id").val();
                                 isLoading=true;
                                 $.ajax({
                                    type     :"POST",
                                    cache    : false,
                                    url  : "'.Yii::$app->urlManager->createUrl(["akuisisi/koleksi/get-ruang"]).'?id="+id,
                                    success  : function(response) {
                                        $("#actionRuang").html(response);
                                    }
                                });
                            }',
                        ]
                    ]);
                    ?>
                    </div>
                    <div class="col-sm-offset-4 col-sm-8"></div>
                    <div class="col-sm-offset-4 col-sm-8"><div class="help-block"></div></div>
                  </div>
                  </div>
                   <div class="col-sm-4">
                    &nbsp;
                  </div>

                </div>
                

                <div class="form-group kv-fieldset-inline">

                  <div class="col-sm-8">
                    <div class="form-group field-collections-location_id required">
                    <label class="control-label col-sm-4" for="collections-location_id"><?= yii::t('app','Lokasi Ruang')?></label>
                    <div class="col-sm-8" id="actionRuang">
                    <?php 
                      echo Select2::widget([
                      'model' => $model,
                      'attribute' => 'Location_id',
                      'data'=>ArrayHelper::map(Locations::find()->where(['LocationLibrary_id'=>$loclib_id ])->all(),'ID','Name'),
                      'pluginOptions' => [
                            'allowClear' => true,
                        ],
                      ]);
                      ?>
                    </div>
                    <div class="col-sm-offset-4 col-sm-8"></div>
                    <div class="col-sm-offset-4 col-sm-8"><div class="help-block"></div></div>
                    </div>
                  </div>
                   <div class="col-sm-4">
                    &nbsp;
                  </div>

                </div>
                

                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                  <?= $form->field($model,'Status_id')->widget('\kartik\widgets\Select2',[
                      'data'=>ArrayHelper::map(Collectionstatus::find()->all(),'ID','Name'),
                      'pluginOptions' => [
                          'allowClear' => true,
                      ],
                      //'options'=> ['placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'coll_Status')]
                  ])?>
                  </div>
                  <div class="col-sm-4">
                    &nbsp;
                  </div>
                </div>

                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                  <?= $form->field($model,'Currency')->widget('\kartik\widgets\Select2',[
                      'data'=>ArrayHelper::map(Currency::find()->all(),'Currency','Description'),
                      'pluginOptions' => [
                          'allowClear' => true,
                      ],
                      //'options'=> ['placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'coll_Currency')]
                  ])?>
                  </div>
                  <div class="col-sm-4">
                    &nbsp;
                  </div>
                </div>

                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                    <?= $form->field($model,'Price')->widget(kartik\money\MaskMoney::classname(), [
                        'pluginOptions' => [
                            'allowNegative' => false
                        ]
                    ])?>
                  
                  </div>
                  <div class="col-sm-4"  style="padding-left: 0px">
                     <?= $form->field($model,'PriceType')->widget('\kartik\widgets\Select2',[
                          'data'=>array(
                                  'Per eksemplar'=>'Per eksemplar',
                                  'Per jilid'=>'Per jilid'),
                          'pluginOptions' => [
                              'allowClear' => true,
                          ],
                      ])->label(false)?>
                  </div>
                </div>

                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                    <?= $form->field($model,'CallNumber')->textInput(['inline'=>true,'class'=>'callnumber','onfocus'=>"AutoSuggestOn(this,'callnumber');"])?>
                  </div>
                  <div class="col-sm-4">
                    &nbsp;
                  </div>
                </div>
               
               

                </div>
              </div>
            </div>
          </div>
<?php
} 
?>
<br>
<div class="col-sm-12">
<?php echo Html::activeCheckbox($modelcat,'IsOPAC',['label'=> yii::t('app','Tampil di OPAC'),'style'=>'font-weight:bold']); ?>
</div>
<br>
<div id="output"></div>
<input type="hidden" name='catalogid' value="<?=Yii::$app->getRequest()->getQueryParam('id')?>">
<input type="hidden" id="hdnRda" name="rdastatus" value="<?=$rda?>">
<input type="hidden" id="hdnPilihJudul" name="pilihjudul">
<input type="hidden" id="hdnReferUrl" name="referUrl" value="<?=\common\components\CatalogHelpers::encrypt_decrypt('encrypt',$referrerUrl)?>">
<input type="hidden" id="hdnAjaxUrlAutosuggestCallnumber" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/auto-suggest-call-number"])?>">

<?php ActiveForm::end(); ?>


<?php
Modal::begin(['id' => 'rekanan-modal','options'=>[
  'style'=>['z-index'=>9999],
  'data-backdrop'=>'static'
]]);
echo "<div id='modalPartners'></div>";
Modal::end();
?>



<?php 
Modal::begin([
  'id' => 'helper-modal',
  'options' => [
      'max-height' => '400px',
  ],
  
]);
?>
<div id="helper-body"></div>
<?php
Modal::end();
?>

<?php 
Modal::begin([
  'id' => 'tag-modal'
  
]);
?>
<div id="tag-body"></div>
<?php
Modal::end();
?>

<?php 
Modal::begin([
  'id' => 'tagfixed-modal'
  
]);
?>
<div id="tagfixed-body"></div>
<?php
Modal::end();
?>

<?php
Modal::begin([
    'id' => 'pilihsalin-modal',
    'size'=>'modal-lg',
    'header' => '<h4 class="modal-title">...</h4>',
    'options' => [
      'width' => '900px',
  ],
]);
 
echo '...';
 
Modal::end();
?>


</div>

<input type="hidden" id="hdnFor" value="<?=$for?>">
<input type="hidden" id="hdnCrudmode" value="<?=$mode?>">
<input type="hidden" id="hdnAjaxUrlPilihJudul" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/pilih-judul"])?>">
<input type="hidden" id="hdnAjaxUrlFormSimple" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/entry-simple"])?>">
<input type="hidden" id="hdnAjaxUrlFormAdvance" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/entry-advance"])?>">
<input type="hidden" id="hdnAjaxUrlNoInduk" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/bind-no-induk"])?>">
<input type="hidden" id="hdnAjaxUrlPartner" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/bind-partners"])?>">
<input type="hidden" id="hdnAjaxUrlCheckDuplicate" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/check-duplicate"])?>">
<input type="hidden" id="hdnAjaxUrlRemoveTag" value="<?=Yii::$app->urlManager->createUrl(['pengkatalogan/katalog/remove-taglist'])?>">

<?php 
$this->registerJsFile( 
    Yii::$app->request->baseUrl.'/assets_b/js/catalogs_form.js'
);

?>
