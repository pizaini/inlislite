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
use common\models\DepositKodeWilayah;
use common\models\DepositWs;
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

$year = range('1968' , date('Y'));
rsort($year);

foreach ($year as $year => $value) {
    $y[$value] = $value;
}
// echo '<pre>';print_r($y);echo '</pre>';
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

                <!-- <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                    <?= $form->field($model,'JumlahEksemplar')->hiddenInput(['value'=> '1'])->label(false);?>

                  </div>
                </div> -->

                <!-- <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                  <?= $form->field($model,'NomorBarcode')->textInput(['style'=>'width:180px'], ['inline'=>true])->label('Nomor Deposit')?>
                  </div>
                  <div class="col-sm-4">
                        &nbsp;
                  </div>
                </div> -->

                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                    <?= $form->field($model,'NomorDeposit')->textInput(['inline'=>true,'class'=>'callnumber','placeholder'=>'Masukan Nomor Deposit'])->label('Nomor Deposit')?>
                  </div>
                  <div class="col-sm-4">
                    &nbsp;
                  </div>
                </div>


                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                  <?php Pjax::begin(['id' => 'pjax-collection-partners','timeout' => false ]); ?>
                       <?= $form->field($model,'deposit_ws_ID')->widget('\kartik\widgets\Select2',[

                            'data'=>ArrayHelper::map(DepositWs::find()->all(),'ID','nama_penerbit'),
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                            //'options'=> ['placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'coll_Partner ID')],
                        ])?>
                  <?php Pjax::end(); ?>
                  </div>
                  <div class="col-sm-4" style="padding-left: 0px">

                      <?php 
                      echo '<p>'.Html::a(Yii::t('app', 'Create Deposit Ws'), ['../setting/deposit/deposit-ws/create','dep'=>'1'], ['class' => 'btn btn-success  btn-sm','data-toggle'=>"modal",'data-target'=>"#deposit-form"]).'</p>';
                      ?>

                  </div>
                </div>

                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                  <?= $form->field($model,'deposit_kode_wilayah_ID')->widget('\kartik\widgets\select2',['data'=>ArrayHelper::map(DepositKodeWilayah::find()->all(),'ID',function($model) {
                        return $model['kode_wilayah'].' - '.$model['nama_wilayah'];
                      }),'pluginOptions'=>['allowClear'=>true,],
                      'options'=>['placeholder'=>yii::t('app','Pilih Wilayah Terbit')]])->label('Wilayah Terbit')?>
                  </div>
                  <div class="col-sm-4">
                      &nbsp;
                  </div>
                </div>

                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                    <?= $form->field($model,'ThnTerbitDeposit')->widget('\kartik\widgets\select2',
                      ['data' => $y,
                      'value' => date('Y'),
                      'pluginOptions'=>['allowClear'=>true,],
                      'options'=>[
                        // 'placeholder'=>yii::t('app','Pilih Tahun Terbit'),
                        'style'=>'width:10%'
                      ],
                      'pluginOptions' => [
                          'width' => '30%'
                      ],
                      ])->label('Tahun Terbit')?>
                  </div>
                  <div class="col-sm-4">
                      &nbsp;
                  </div>
                </div>

                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                    <?= $form->field($model,'NOJILID')->textInput(['style'=>'width:70px'], ['inline'=>true])->label('Jilid')?>
                    <!-- <?= $form->field($model,'JumlahEksemplar')->textInput(['style'=>'width:70px','onblur'=>'js:RenderNoInduk();'], ['inline'=>true])?> -->
                  </div>
                  <div class="col-sm-4">
                    &nbsp;
                  </div>
                </div>

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
                  ])->label('Tanggal Penerimaan');
                  ?>
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
              <table class="table2 table-striped" width="96%">
                <div class="form-group kv-fieldset-inline">
                <tr>
                  <td>
                  <div class="col-sm-8">
                    <?= $form->field($model,'Price')->widget(kartik\money\MaskMoney::classname(), [
                        'pluginOptions' => [
                            'allowNegative' => false
                        ]
                    ])?>
                  
                  </div>
                  <div class="col-sm-2" style="padding-left: 0px">
                      <?php 
                      echo '<p>'. Html::a(Yii::t('app', 'Taksiran Harga'), 'javascript:void(0)', ['id'=>'btnAddPartners','onclick'=>'kontenDigital()','class' => 'btn bg-maroon btn-sm','data-toggle'=>'collapse','data-target'=>'#collapseKontenDigital']);
                      ?>
                  </div>
                  </td>
                </div>
              </table> 
              <div class="row">
                  <div class="col-sm-1">&nbsp;</div>
                  <div class="col-sm-6">

                      <div class="collapse" id="collapseKontenDigital" style="background-color:#bda3ff4a;">
                          <div id="kontenDigitalShow">
                            <div class="modal-body" >
                            <?= $form->field($modeltaksiran,'cover')->widget('\kartik\widgets\select2',[
                                  'data' => ['Hard Cover' => yii::t('app','Hard Cover'), 'Kertas Karton Dengan Laminating' => yii::t('app','Kertas Karton Dengan Laminating'),
                                             'Karton Tanpa Laminating' => yii::t('app','Karton Tanpa Laminating'),'Kertas Tanpa Laminating' => yii::t('app','Kertas Tanpa Laminating')],
                                  'pluginOptions'=>['allowClear'=>true,],'options'=>['placeholder'=>yii::t('app','Pilih kulit muka buku (cover)')]
                                ])->label('kulit muka buku (cover)')?>
                            <?= $form->field($modeltaksiran,'muka_buku')->widget('\kartik\widgets\select2',[
                                  'data' => ['Poly Emas' => yii::t('app','Poly Emas'), 'UV Spot' => yii::t('app','UV Spot'), 'Embossed' => yii::t('app','Embossed')],
                                  'pluginOptions'=>['allowClear'=>true,],'options'=>['placeholder'=>yii::t('app','Pilih Finishing Kulit Muka Buku')]
                                ])->label('Finishing Kulit Muka Buku')?>
                            <?= $form->field($modeltaksiran,'hard_cover')->widget('\kartik\widgets\select2',[
                                  'data' => ['Satu Halaman' => yii::t('app','Satu Halaman'), 'Setengah Halaman' => yii::t('app','Setengah Halaman'), 'Sepertiga Halaman' => yii::t('app','Sepertiga Halaman'),
                                             'Jaket' => yii::t('app','Jaket')],
                                  'pluginOptions'=>['allowClear'=>true,],'options'=>['placeholder'=>yii::t('app','Pilih Bentuk finishing Hard cover')]
                                ])->label('Bentuk finishing Hard cover')?>
                            <?= $form->field($modeltaksiran,'penjilidan')->widget('\kartik\widgets\select2',[
                                  'data' => ['Hard Cover' => yii::t('app','Hard Cover'), 'Perfect Binding' => yii::t('app','Perfect Binding'), 'Spiral Binding' => yii::t('app','Spiral Binding'),
                                             'Vero Binding' => yii::t('app','Vero Binding'), 'Steples Binding' => yii::t('app','Steples Binding')],
                                  'pluginOptions'=>['allowClear'=>true,],'options'=>['placeholder'=>yii::t('app','Pilih Punggung Buku (Penjilidan)')]
                                ])->label('Punggung Buku (Penjilidan)')?>
                            <?= $form->field($modeltaksiran, 'jumlah_halaman')->textInput(['type' => 'number']) ?>
                            <?= $form->field($modeltaksiran,'jenis_kertas_buku')->widget('\kartik\widgets\select2',[
                                  'data' => ['Kertas Koran' => yii::t('app','Kertas Koran'), 'HVS 60 gr' => yii::t('app','HVS 60 gr'), 'HVS 70 gr' => yii::t('app','HVS 70 gr'), 
                                             'HVS 80 gr' => yii::t('app','HVS 80 gr'), 'HVS 100 gr' => yii::t('app','HVS 100 gr'), 'Art Paper 85 gr' => yii::t('app','Art Paper 85 gr'),
                                             'Art Paper 100 gr' => yii::t('app','Art Paper 100 gr'), 'Art Paper 120 gr' => yii::t('app','Art Paper 120 gr'), 'Art Paper 150 gr' => yii::t('app','Art Paper 150 gr')],
                                  'pluginOptions'=>['allowClear'=>true,],'options'=>['placeholder'=>yii::t('app','Pilih Jenis Kertas Buku')]
                                ])?>
                            <?= $form->field($modeltaksiran,'ukuran_buku')->widget('\kartik\widgets\select2',[
                                  'data' => ['A0' => yii::t('app','A0'), 'A1' => yii::t('app','A1'), 'A2' => yii::t('app','A2'), 'A3' => yii::t('app','A3'), 'A4' => yii::t('app','A4'), 'A5' => yii::t('app','A5'),
                                             'A6' => yii::t('app','A6'), 'A7' => yii::t('app','A7'), 'A8' => yii::t('app','A8'), 'A9' => yii::t('app','A9'), 'A10' => yii::t('app','A10'), 'F4' => yii::t('app','F4')],
                                  'pluginOptions'=>['allowClear'=>true,],'options'=>['placeholder'=>yii::t('app','Pilih Ukuran Buku')]
                                ])?>
                            <?= $form->field($modeltaksiran,'kondisi_buku')->widget('\kartik\widgets\select2',[
                                  'data' => ['Sangat Baik' => yii::t('app','Sangat Baik'), 'Baik' => yii::t('app','Baik'), 'Sedang' => yii::t('app','Sedang'), 'Buruk' => yii::t('app','Buruk')],
                                  'pluginOptions'=>['allowClear'=>true,],'options'=>['placeholder'=>yii::t('app','Pilih Kondisi Buku')]
                                ])?>
                            <?= $form->field($modeltaksiran,'kondisi_usang')->widget('\kartik\widgets\select2',[
                                  'data' => ['Di Bawah 5 Tahun' => yii::t('app','Di Bawah 5 Tahun'), 'Di Atas 5 Tahun' => yii::t('app','Di Atas 5 Tahun'), 'Teknologi' => yii::t('app','Teknologi')],
                                  'pluginOptions'=>['allowClear'=>true,],'options'=>['placeholder'=>yii::t('app','Pilih Kondisi Usang')]
                                ])?>
                            <?= $form->field($modeltaksiran,'full_color')->widget('\kartik\widgets\select2',[
                                  'data' => ['Ya' => yii::t('app','Ya'), 'Tidak' => yii::t('app','Tidak')],
                                  'pluginOptions'=>['allowClear'=>true,],'options'=>['placeholder'=>yii::t('app','Pilih Kriteria Warna')]
                                ])?>

                            </div>
                          </div>
                      </div>
                      <br>
                  </div>
              </div>         

                <div class="form-group kv-fieldset-inline">
                  <div class="col-sm-8">
                    <?= $form->field($model,'JumlahEksemplar')->textInput(['style'=>'width:70px'], ['inline'=>true])?>
                    <!-- <?= $form->field($model,'JumlahEksemplar')->textInput(['style'=>'width:70px','onblur'=>'js:RenderNoInduk();'], ['inline'=>true])?> -->
                  </div>
                  <div class="col-sm-4">
                    &nbsp;
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
                  ])->label('Jenis Koleksi')?>
                  </div>
                  <div class="col-sm-4">
                    <div class="pull-left" style="margin-left: -70px">
                      <?php /*echo $form->field($model,'ISREFERENSI')->checkbox(['label' => Yii::t('app', 'Referensi'), 'labelOptions'=>['style'=>'font-weight:bold']]);*/ ?>
                    </div>
                  </div>
                </div>   
              <?php if($edit){ ?>
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
                  <?= $form->field($model,'Location_id')->widget('\kartik\widgets\Select2',[
                      'data'=>ArrayHelper::map(Locations::find()->all(),'ID','Name'),
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
              <?php }?>                           

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
<div class="modal remote fade" id="deposit-form" style="overflow-y: auto !important;">
        <div class="modal-dialog" style="width:700px;">
            <div class="modal-content loader-lg"></div>
        </div>
</div>

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
