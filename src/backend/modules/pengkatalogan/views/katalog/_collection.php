

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
use common\models\MasterEdisiSerial;
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

<style type="text/css">
.ui-autocomplete-input {
  z-index: 1511;
}
.ui-autocomplete {
  z-index: 1510 !important;
}
</style>
<input type="hidden" id="hdnCollectionId" value="<?=$catalogid?>">
<?php 

$form = ActiveForm::begin([
'id'=>"form-collection-modal",
'method'=>'post',
'enableAjaxValidation' => true,
'enableClientValidation' => false,
'type'=>ActiveForm::TYPE_HORIZONTAL,
'formConfig' => ['labelSpan'=>4,'deviceSize' => ActiveForm::SIZE_SMALL],
'action'=> ['save-catalogs-collection?id='.$id.'&refer='.$refer],
]); ?>

<div class="modal-header" >
<h4 class="modal-title"><?=$header?></h4>
</div>
<br>
<input type="hidden" id="collections_catalog_id" name="Collections[Catalog_id]" value="<?=$catalogid?>">
                <?php
                //Khusus jenis bahan terbitan berkala (serial)
                if($isSerial ==1)
                {
                ?>
                

                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                  <?= $form->field($model,'EDISISERIAL')->widget('\kartik\widgets\Select2',[
                      'data'=>ArrayHelper::map(MasterEdisiSerial::find()->where(['Catalog_id' => $catalogid])->all(),'id','no_edisi_serial'),
                      'pluginOptions' => [
                          'allowClear' => true,
                      ],
                      'options'=> ['placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'Edisi Serial')]
                  ])?>
                  </div>
                  <div class="col-sm-4">
                      &nbsp;
                  </div>
                </div>

                <!-- <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">

                  <?php 
                  // echo $form->field($model, 'TANGGAL_TERBIT_EDISI_SERIAL')->widget(MaskedDatePicker::classname(), 
                  // [
                  //   'enableMaskedInput' => true,
                  //   'maskedInputOptions' => [
                  //       'mask' => '99-99-9999',
                  //       'pluginEvents' => [
                  //           'complete' => "function(){console.log('complete');}"
                  //       ]
                  //   ],
                  //  'removeButton' => false,
                  //  'options'=>[
                  //                   'style'=>'width:170px',
                  //               ],
                  //   'pluginOptions' => [
                  //                 'autoclose' => true,
                  //                 'todayHighlight' => true,
                  //                 'format'=>'dd-mm-yyyy',
                  //               ]
                  // ])->label(yii::t('app','Tanggal Terbit Edisi Serial'));
                  ?>
                  </div>
                  <div class="col-sm-4">
                      &nbsp;
                  </div>
                </div> -->

                 <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                    <?= $form->field($model,'BAHAN_SERTAAN')->textInput(['inline'=>true,'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'coll_Bahan  Sertaan').'...'])->label(yii::t('app','Bahan Sertaan'))?>
                  </div>
                  <div class="col-sm-4">
                    &nbsp;
                  </div>
                </div>

                 <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                    <?= $form->field($model,'KETERANGAN_LAIN')->textInput(['inline'=>true,'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'coll_Keterangan  Lain').'...'])->label(yii::t('app','Keterangan Lain'))?>
                  </div>
                  <div class="col-sm-4">
                    &nbsp;
                  </div>
                </div>

                <hr>
                <?php 
                }

                if(Yii::$app->getRequest()->getQueryParam('id') == 0)
                {
                ?>
                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                    <?= $form->field($model,'JumlahEksemplar')->textInput(['style'=>'width:70px','onblur'=>'js:RenderNoIndukCatColl()'], ['inline'=>true])?>
                  </div>
                  <div class="col-sm-4">
                    &nbsp;
                  </div>
                </div>

                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8" id="listNoIndukCatColl">
                     
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
                  <?php Pjax::begin(['id' => 'pjax-collection-partners-catcoll', 'timeout' => false]); ?>
                       <?= $form->field($model,'Partner_id')->widget('\kartik\widgets\Select2',[
                            'data'=>ArrayHelper::map(Partners::getOrderAlphabhet(),'ID','Name'),
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                            //'options'=> ['placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'coll_Partner ID')],
                        ])?>
                  <?php Pjax::end(); ?>
                  </div>
                  <div class="col-sm-4" style="padding-left: 0px; float:right">

                      <?php 

                      /*echo Html::a(Yii::t('app', 'Pjax Reload'), 'javascript:void(0)', ['id'=>'btnTestPjax','onclick'=>'js:TestAjax();','class' => 'btn bg-maroon btn-sm']);*/
                      echo '<p>'. Html::a(Yii::t('app', 'Tambah'), 'javascript:void(0)', ['id'=>'btnAddPartnersCatColl','onclick'=>'js:AddPartnerCatColl();','class' => 'btn bg-maroon btn-sm']);

                      echo  '&nbsp;' . Html::a(Yii::t('app', 'Edit'), 'javascript:void(0)', ['id'=>'btnEditPartnersCatColl','onclick'=>'js:EditPartnerCatColl();','class' => 'btn bg-maroon btn-sm']) . '</p>';
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
                    echo \kartik\widgets\Select2::widget([
                    'model' => $model,
                    'attribute' => 'Location_Library_id',
                    'data'=>ArrayHelper::map($modelloclib,'ID','Name'),
                    'pluginOptions' => [
                          'allowClear' => true,
                      ],
                    'pluginEvents' => [
                            "select2:select" => 'function() { 
                                var id = $("#collections-location_library_id").val();
                                 isLoading=false;
                                 $.ajax({
                                    type     :"POST",
                                    cache    : false,
                                    url  : "'.Yii::$app->urlManager->createUrl(["akuisisi/koleksi/get-ruang"]).'?id="+id,
                                    beforeSend : function(response) {
                                        $("#actionRuang").html(\'<span style="color:green">Sedang memuat... </span><img src="'.Yii::$app->getUrlManager()->getBaseUrl().'/assets_b/images/loading.gif" width="50px" />\');
                                    },
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
                      echo \kartik\widgets\Select2::widget([
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
			'onClick' => "js:SaveCollection();",

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

  $this->registerJs("

    ");
?>
