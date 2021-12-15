
<?php 
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\KataSandang;
use common\models\Refferenceitems;
use common\widgets\MaskedDatePicker;
?>
<style type="text/css">
  .btm-add-on {
    background-color: #f5f5f5;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
    padding: 5px;
    text-align: center;
    font-size:12px;
    border: 1px solid #C0C0C0;
    border-top: none;
}
.has-add-on {
    border-bottom-left-radius: 0px;
    border-bottom-right-radius: 0px;
}

/* .floating-topright1{
  position: fixed;
  top:295px;
  right: 40px;
  z-index: 2147483647;
} */
</style>
<?php
/*echo '<pre>'; print_r($modelbib); echo '</pre>';*/
/* die;*/
$relatorTerm = ArrayHelper::map(Refferenceitems::findByRelatorCreatorTerm(),'Name','Name');

$divclass='';
if (!$model->isNewRecord && $for == 'coll')
{
  $divclass='disabled';
}

$cekKarto = Yii::$app->db->createCommand('SELECT ISKARTOGRAFI FROM worksheets WHERE ID = '.$worksheetid.'')->queryOne();

// print_r($cekKarto);

?>
<div id="entryBibliografiPanel" class="<?=$divclass?>">
  <div class="box-group" id="accordion">
      <div class="panel panel-default">
        <div class="box-header with-border">
              <div class="col-xs-6 col-sm-6" >
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                          Data Bibliografis
                        </a>
                    </h4>
              </div>
              <div class="col-xs-6 col-sm-6">
                 <small><?php echo Html::a("<i class='glyphicon glyphicon-th'></i>". yii::t('app','  Tampilkan Form MARC'), '#', ['id'=>'btn-change-advance','class' =>'btn bg-navy floating-topright1  pull-right btn-sm','onclick'=>'js:BibliografisToogleForm("advance")']); ?></small>
              </div>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse in">
          <div class="box-body">
            <?php echo Html::hiddenInput('modeform',(string)$isAdvanceEntry,['id'=>'modeform']); ?>
              <div id="simple">

                    <div class="panel panel-default ">
                      <div class="panel-heading"><?= yii::t('app','Judul')?></div>
                      <div class="panel-body">
                        <div class="form-group kv-fieldset-inline">
                          <div class="col-sm-12">
                               <input type="hidden" id="Ruasid_245" name="Ruasid[245]" value="<?=$taglist['ruasid']['245']?>" size="3" />
                               <div class="form-group">
                                  <span class="<?=$listvar['input_required']['245']['status']?>"  id="status-245">
                                  <input type="hidden" id="message-245" value="<?=$listvar['input_required']['245']['message']?>" />
                                    <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'Title'); ?></label>
                                    <div class="col-sm-6">
                                        <?php echo Html::activeTextInput($modelbib,'Title',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_Judul').'...']); ?>
                                        <div id="error-245" class="help-block"></div>
                                    </div>
                                  </span>
                                  <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'KataSandang'); ?></label>
                                  <div class="col-sm-2">
                                    <?php 
                                      echo Select2::widget([
                                      'model' => $modelbib,
                                      'attribute' => 'KataSandang',
                                      'data'=>ArrayHelper::map(KataSandang::find()->all(),'JumlahKarakter','Name'),
                                                        'pluginOptions' => [
                                                            'allowClear' => true,
                                                        ],
                                      'options'=> ['placeholder'=>'--Tidak diawali--']
                                      ]);?>
                                  </div>
                                </div>
                            </div>
                         <!--  <div class="col-sm-1">
                           &nbsp;
                         </div> -->
                        </div>

                        <div class="form-group kv-fieldset-inline">
                          <div class="col-sm-12">
                              <div class="form-group">
                                  <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'TitleAdded'); ?></label>
                                  <div class="col-sm-6">
                                    <?php echo Html::activeTextInput($modelbib,'TitleAdded',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_Anak Judul').'...']); ?>
                                    <div class="help-block"></div>
                                  </div>
                                </div>
                             </div>
                          <!-- <div class="col-sm-1">
                              &nbsp;
                          </div> -->
                        </div>

                        <div class="form-group kv-fieldset-inline">
                          <div class="col-sm-12">
                              <div class="form-group">
                                  <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'PenanggungJawab'); ?></label>
                                  <div class="col-sm-6">
                                    <?php echo Html::activeTextInput($modelbib,'PenanggungJawab',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_Penanggung Jawab').'...']); ?>
                                    <div class="help-block"></div>
                                  </div>
                                </div>
                          </div>
                          <!-- <div class="col-sm-1">
                              &nbsp;
                          </div> -->
                        </div>

                        <?php 
                        if($rda=='1')
                        {
                        ?>
                        <div class="form-group kv-fieldset-inline">
                          <div class="col-sm-12">
                              <div class="form-group">
                                  <span class="<?=$listvar['input_required']['246']['status']?>"  id="status-246">
                                  <input type="hidden" id="message-246" value="<?=$listvar['input_required']['246']['message']?>" />
                                  <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'TitleVarian'); ?></label>
                                  <div class="col-sm-6">
                                    <input id="TitleVarianCount" type="hidden" value="<?=count($listvar['titlevarian'])?>">
                                        <div id="TitleVarianList">
                                          <?php
                                            if(count($listvar['titlevarian']) > 0){
                                              foreach ($listvar['titlevarian'] as $key => $value) {  
                                          ?>
                                          <div id="DivTitleVarian<?=$key?>">
                                            <input type="hidden" id="Ruasid_246_<?=$key?>" name="Ruasid[246][$key]" value="<?=$taglist['ruasid']['246'][$key]?>" size="3" />
                                            <div style="margin-top:5px" class="input-group">
                                              <input value="<?=$value?>" type="text" id="collectionbiblio-TitleVarian-<?=$key?>" class="form-control" name="CollectionBiblio[TitleVarian][<?=$key?>]" style="width:100%" placeholder="Masukan TitleVarian..." onfocus="AutoCopyTitleVarian(this)">
                                              <span class="input-group-btn">
                                              <?php 
                                              if($key == 0)
                                              {
                                              ?>
                                                <button id="btnTitleVarian" class="btn btn-success pull-right" type="button" onclick="AddTitleVarian();"><i class="glyphicon glyphicon-plus"></i></button>
                                              <?php
                                              }else{
                                              ?>
                                                <button class="btn btn-danger btn-flat" type="button" onclick="RemoveTitleVarian(<?=$key?>)"><i class="glyphicon glyphicon-trash"></i></button>
                                              <?php
                                              }
                                              ?>
                                              </span>
                                            </div>
                                          </div>
                                          <?php
                                              }
                                            }else{
                                          ?>

                                          <div id="DivTitleVarian0">
                                            <input type="hidden" id="Ruasid_246_0" name="Ruasid[246][0]" value="<?=$taglist['ruasid']['246'][0]?>" size="3" />
                                            <div class="input-group">
                                              <?php echo Html::activeTextInput($modelbib,'TitleVarian[]',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_Judul Varian').'...','onfocus'=>'AutoCopyTitleVarian(this)']); ?>
                                              <span class="input-group-btn">
                                                <button id="btnTitleVarian" class="btn btn-success pull-right" type="button" onclick="AddTitleVarian();"><i class="glyphicon glyphicon-plus"></i></button>
                                              </span>
                                            </div>
                                          </div>

                                          <?php
                                            }

                                          ?>
                                        </div>
                                        <div id="error-246" class="help-block"></div>
                                  </div>

                                  </span>
                                </div>
                          </div>
                          <!-- <div class="col-sm-6">
                              &nbsp;
                          </div> -->
                        </div>

                        <div class="form-group kv-fieldset-inline">
                          <div class="col-sm-12">
                              <div class="form-group">
                                  <span class="<?=$listvar['input_required']['740']['status']?>"  id="status-740">
                                  <input type="hidden" id="message-740" value="<?=$listvar['input_required']['740']['message']?>" />
                                  <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'TitleOriginal'); ?></label>
                                  <div class="col-sm-6">
                                    <input id="TitleOriginalCount" type="hidden" value="<?=count($listvar['titleoriginal'])?>">
                                        <div id="TitleOriginalList">
                                          <?php
                                            if(count($listvar['titleoriginal']) > 0){
                                              foreach ($listvar['titleoriginal'] as $key => $value) {  
                                          ?>
                                          <div id="DivTitleOriginal<?=$key?>">
                                            <input type="hidden" id="Ruasid_740_<?=$key?>" name="Ruasid[740][$key]" value="<?=$taglist['ruasid']['740'][$key]?>" size="3" />
                                            <div style="margin-top:5px" class="input-group">
                                              <input value="<?=$value?>" type="text" id="collectionbiblio-TitleOriginal-<?=$key?>" class="form-control" name="CollectionBiblio[TitleOriginal][<?=$key?>]" style="width:100%" placeholder="Masukan TitleOriginal..." onfocus="AutoCopyTitleOriginal(this)">
                                              <span class="input-group-btn">
                                              <?php 
                                              if($key == 0)
                                              {
                                              ?>
                                                <button id="btnTitleOriginal" class="btn btn-success pull-right" type="button" onclick="AddTitleOriginal();"><i class="glyphicon glyphicon-plus"></i></button>
                                              <?php
                                              }else{
                                              ?>
                                                <button class="btn btn-danger btn-flat" type="button" onclick="RemoveTitleOriginal(<?=$key?>)"><i class="glyphicon glyphicon-trash"></i></button>
                                              <?php
                                              }
                                              ?>
                                              </span>
                                            </div>
                                          </div>
                                          <?php
                                              }
                                            }else{
                                          ?>

                                          <div id="DivTitleOriginal0">
                                            <input type="hidden" id="Ruasid_740_0" name="Ruasid[740][0]" value="<?=$taglist['ruasid']['740'][0]?>" size="3" />
                                            <div class="input-group">
                                              <?php echo Html::activeTextInput($modelbib,'TitleOriginal[]',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_Judul Original').'...','onfocus'=>'AutoCopyTitleOriginal(this)']); ?>
                                              <span class="input-group-btn">
                                                <button id="btnTitleOriginal" class="btn btn-success pull-right" type="button" onclick="AddTitleOriginal();"><i class="glyphicon glyphicon-plus"></i></button>
                                              </span>
                                            </div>
                                          </div>

                                          <?php
                                            }

                                          ?>
                                        </div>
                                        <div id="error-740" class="help-block"></div>
                                  </div>

                                  </span>
                                </div>
                          </div>
                          <!-- <div class="col-sm-6">
                              &nbsp;
                          </div> -->
                        </div>
                        <div class="form-group kv-fieldset-inline">
                          <div class="col-sm-12">
                              <div class="form-group">
                                  <label class="control-label col-sm-2" for="email">Judul Seragam<?php //echo Html::activeLabel($modelbib,'JudulSeragam'); ?></label>
                                  <div class="col-sm-6">
                                    <?php echo Html::activeTextInput($modelbib,'JudulSeragam',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Judul Seragam').'...']); ?>
                                    <div class="help-block"></div>
                                  </div>
                                </div>
                          </div>
                          <!-- <div class="col-sm-1">
                              &nbsp;
                          </div> -->
                        </div>

                        <?php
                        }
                        ?>
                        <?php if($isSerial == 1) { ?>
                            <div class="form-group kv-fieldset-inline">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                         <span class="<?=$listvar['input_required']['247']['status']?>"  id="status-247">
                                            <input type="hidden" id="message-247" value="<?=$listvar['input_required']['247']['message']?>" />
                                            <label class="control-label col-sm-2" for="email">
                                              Judul Sebelumnya
                                            </label>
                                            <div class="col-sm-9">
                                                <input id="JudulSebelumAddCount" type="hidden" value="<?=count($listvar['judulsebelum'])?>">
                                                <div id="JudulSebelumAddList">
                                                  <?php 
                                                    if(count($listvar['judulsebelum']) > 0){
                                                      $count247=0;
                                                      foreach ($listvar['judulsebelum'] as $key => $value) {
                                                        $indexruas=$count247-1;
                                                  ?>
                                                        <div id="DivJudulSebelumAdded<?=$key?>">
                                                            <input type="hidden" id="Ruasid_<?=$tagjudulsebelum?>_<?=$indexruas?>" name="Ruasid[<?=$tagjudulsebelum?>][<?=$indexruas?>]" value="<?=$taglist['ruasid'][$tagjudulsebelum][$indexruas]?>"/>
                                                            <div class="input-group" style="margin-top:5px">
                                                                <input value="<?=$value?>" type="text" id="collectionbiblio-judulsebelum-<?=$key?>" class="form-control" name="CollectionBiblio[JudulSebelumAdded][<?=$key?>]" style="width:100%" placeholder="Masukan Judul Sebelumnya...">
                                                                <span class="input-group-btn">
                                                                <?php 
                                                                if($key == 0)
                                                                {
                                                                ?>
                                                                  <button id="btnJudulSebelumAdded" class="btn btn-success pull-right" type="button" onclick="AddJudulSebelumDaring();"><i class="glyphicon glyphicon-plus"></i></button>
                                                                <?php
                                                                }else{
                                                                ?>
                                                                  <button class="btn btn-danger btn-flat" type="button" onclick="RemoveJudulSebelumAdded(<?=$key?>,'<?=$tagjudulsebelum?>','<?=$indexruas?>')"><i class="glyphicon glyphicon-trash"></i></button>
                                                                <?php
                                                                }
                                                                ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                  <?php 
                                                      }
                                                    }else{
                                                  ?>
                                                      <div id="DivJudulSebelumAdded0">
                                                          <input type="hidden" id="Ruasid_247_0" name="Ruasid[247][0]" value="<?=$taglist['ruasid'][247][0]?>" size="3"/>
                                                          <div class="input-group">
                                                            <?php echo Html::activeTextInput($modelbib,'JudulSebelumAdded[0]',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', $translateTag7XX).'...']); ?>
                                                            <span class="input-group-btn">
                                                              <button id="btnJudulSebelumAdded" class="btn btn-success pull-right" type="button" onclick="AddJudulSebelumDaring();"><i class="glyphicon glyphicon-plus"></i></button>
                                                            </span>
                                                          </div>
                                                      </div>
                                                  <?php } ?>
                                                </div>
                                            </div>
                                         </span>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                      </div>
                    </div>

                    

                    


                    

                    <div class="panel panel-default ">
                      <div class="panel-heading">
                      <?php if($rda=='1') { echo yii::t('app','Kreator'); $translateTag100='bib_Pengarang_rda'; }else{ echo yii::t('app','Tajuk Pengarang'); $translateTag100='bib_Pengarang';} ?>
                      </div>
                      <div class="panel-body">

                        <div class="form-group kv-fieldset-inline">
                          <div class="col-sm-12">
                              <div class="form-group">
                              <span class="<?=$listvar['input_required']['100']['status']?>"  id="status-100">
                              <input type="hidden" id="message-100" value="<?=$listvar['input_required']['100']['message']?>" />
                              <label class="control-label col-sm-2" for="email"><?=Yii::t('app', $translateTag100);?></label>
                                <div class="col-sm-9">
                                    <input type="hidden" id="Ruasid_100" name="Ruasid[100]" value="<?=$taglist['ruasid']['100']?>" size="3" />
                                    <?php 
                                    if($rda=='1')
                                    {
                                    //jika rda maka ada tambahan relator term
                                    ?>
                                    <div class="row">
                                      <div class="col-sm-3" style="padding-right: 0px">
                                      <?php 
                                        echo  Html::activeDropDownList($modelbib,'AuthorRelatorTerm',
                                        $relatorTerm,
                                        ['class'=>'form-control']
                                      ); ?> 
                                      </div>
                                      
                                        <div class="col-sm-9" style="padding-left: 0px">
                                    <?php echo Html::activeTextInput($modelbib,'Author',['class'=>'form-control tajukpengarang',"onkeyup"=>"AutoCompleteOn(this,'pengarang');",'style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', $translateTag100).'...']); ?>
                                        </div>
                                    </div>
                                    <?php 
                                    }else{
                                      //echo '<pre>'; print_r($listvar);echo '</pre>';
                                      ?>
                                    <!-- //jika aacr maka hanya form pengarang utama -->
                                      <!-- <?//php echo '<pre>';print_r($modelbib->AuthorType);echo '</pre>'; ?> -->
                                      <!-- <?//php echo '<pre>';print_r($listvar);echo '</pre>'; ?> -->
                                      <input type="hidden" id="AuthorTag_value" name="AuthorTag_value"  value="<?= crypt($modelbib->AuthorType,'car') ?>" />
                                      <div class="row">
                                         <div class="col-sm-3" style="padding-right: 0px">
                                          <?php 
                                          $modelbib->AuthorTag = $listvar['AuthorTag'];
                                          echo  Html::activeDropDownList($modelbib,'AuthorTag',
                                            [
                                            '100'=>yii::t('app','Nama Orang'),
                                            '110'=>yii::t('app','Nama Badan'),
                                            '111'=>yii::t('app','Nama Pertemuan')
                                            ],
                                            [
                                            'class'=>'form-control',
                                            'onchange'=>'ShowOptionPengarang(0);'
                                            ]
                                          ); ?>

                                           </div>
                                           <div class="col-sm-9" style="padding-left: 0px">
                                              <div class="input-group">
                                                <?php echo Html::activeTextInput($modelbib,'Author',['class'=>'tag100_0 form-control tajukpengarang',"onkeyup"=>"AutoCompleteOn(this,'pengarang');",'style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', $translateTag100).'...', 'id' => 'TagsValue_100_0']); ?>

                                                  <span class="input-group-btn pick100">
                                                    <a href="javascript:void(0)" id="pickPeng_0" class="btnPeng_0 btn btn-warning pull-right" type="button" data-toggle="modal" data-target="#helper-modal" onclick="PickRuas('39','100','0')"><i class="glyphicon glyphicon-th-list"></i></a>
                                                  </span>

                                                  
                                              </div>
                                           </div>

                                           

                                

                                          
                                        </div>
                                    <!-- echo Html::activeTextInput($modelbib,'Author',['class'=>'form-control tajukpengarang',"onkeyup"=>"AutoCompleteOn(this,'pengarang');",'style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', $translateTag100).'...']);  -->
                                    <?php }
                                    ?>
                                    <div class="btm-add-on" style="text-align:left">
                                         <?php $list = [0 => yii::t('app','Nama Depan'), 1 => yii::t('app','Nama Belakang'), 3 => yii::t('app','Nama Keluarga'), '#' => yii::t('app','Badan Korporasi'), '##' => yii::t('app','Nama Pertemuan')];
                                         // echo Html::activeRadioList($modelbib, 'AuthorType',$list); 
                                         echo Html::activeRadioList($modelbib, 'AuthorType',$list,
                                            [
                                                'item' => function($index, $label, $name, $checked, $value) {

                                                    $return = '<label id="opx'. $index .'_0" '.($index >= 3 ? "style=\"display:none\"" : "" ).'>';
                                                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" id="radio_'. crypt($value,'car') .'" >';
                                                    $return .= '<i></i>';
                                                    $return .= '<span>' . ucwords($label) . '</span>';
                                                    $return .= '</label>';

                                                    return $return;
                                                }
                                            ]
                                         ); 
                                         ?>
                                         <!-- <div id="message"></div> -->
                                    </div>
                                  <div id="error-100" class="help-block"></div>
                                </div>
                                </span>
                              </div>
                          </div>
                        <!-- <div class="col-sm-1">
                            &nbsp;
                        </div> -->
                        </div>

                        

                        <!--<div class="form-group kv-fieldset-inline">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <span class="<//?=$listvar['input_required']['700']['status']?>"  id="status-700">
                                      <input type="hidden" id="message-700" value="<//?=$listvar['input_required']['700']['message']?>" />
                                      <label class="control-label col-sm-2" for="email">
                                      <//?php 
                                      if($rda=='1')
                                      {
                                        $translateTag7XX='bib_Pengarang Tambahan_rda';
                                      }else{
                                        $translateTag7XX='bib_Pengarang Tambahan';
                                      }
                                      ?>

                                      <//?=Yii::t('app', $translateTag7XX);?>
                                      </label>
                                      <div class="col-sm-9">
                                        <input id="AuthorAddCount" type="hidden" value="<//?=count($listvar['author'])?>">
                                        <div id="AuthorAddList">
                                          <//?php
                                              if(count($listvar['author']) > 0 && count($listvar['authortype']) > 0){
                                                $count700=0;
                                                $count710=0;
                                                $count711=0;
                                                foreach ($listvar['author'] as $key => $value) {
                                                $type1=''; $type2=''; $type3=''; $type4=''; $type5='';
                                                switch ($listvar['authortype'][$key]) {
                                                  case '0':
                                                    $type1='checked ';
                                                    $tagpengarangtambahan='700';
                                                    $count700++;
                                                    break;
                                                  case '1':
                                                    $type2='checked ';
                                                    $tagpengarangtambahan='700';
                                                    $count700++;
                                                    break;
                                                  case '3':
                                                    $type3='checked ';
                                                    $tagpengarangtambahan='700';
                                                    $count700++;
                                                    break;
                                                  case '#':
                                                    $type4='checked ';
                                                    $tagpengarangtambahan='710';
                                                    $count710++;
                                                    break;
                                                  case '##':
                                                    $type5='checked ';
                                                    $tagpengarangtambahan='711';
                                                    $count711++;
                                                    break;
                                                  
                                                  default:
                                                    # code...
                                                    break;
                                                }

                                                if($tagpengarangtambahan == '700'){
                                                    $indexruas=$count700-1;
                                                }else if($tagpengarangtambahan == '710'){
                                                    $indexruas=$count710-1;
                                                }else if($tagpengarangtambahan == '711'){
                                                    $indexruas=$count711-1;
                                                }
                                               
                                          ?>
                                          <div id="DivAuthorAdded<?=$key?>">
                                            <input type="hidden" id="Ruasid_<?=$tagpengarangtambahan?>_<?=$indexruas?>" name="Ruasid[<?=$tagpengarangtambahan?>][<?=$indexruas?>]" value="<?=$taglist['ruasid'][$tagpengarangtambahan][$indexruas]?>" size="3" />
                                            <//?php 
                                            if($rda=='1')
                                            {
                                            //jika rda maka ada tambahan relator term
                                            ?>
                                            <div class="row" style="margin-top:5px">

                                                <div class="col-sm-3" style="padding-right: 0px">
                                                 <//?php 
                                                echo  Html::activeDropDownList($modelbib,'AuthorAddedRelatorTerm['.$key.']',
                                                $relatorTerm,
                                                ['class'=>'form-control']
                                              ); ?> 
                                                </div>
                                                
                                                <div class="col-sm-9" style="padding-left: 0px">
                                                  <div class="input-group">
                                                    <input value="<?=$value?>" type="text" id="collectionbiblio-author-<?=$key?>" class="form-control tajukpengarang" onkeyup="AutoCompleteOn(this,'pengarang');" name="CollectionBiblio[AuthorAdded][<?=$key?>]" style="width:100%" placeholder="Masukan <?=Yii::t('app', $translateTag7XX);?>...">
                                                    <span class="input-group-btn">
                                                    <//?php 
                                                    if($key == 0)
                                                    {
                                                    ?>
                                                      <button id="btnAuthorAdded" class="btn btn-success pull-right" type="button" onclick="AddAuthorAdded();"><i class="glyphicon glyphicon-plus"></i></button>
                                                    <//?php
                                                    }else{
                                                    ?>
                                                      <button class="btn btn-danger btn-flat" type="button" onclick="RemoveAuthorAdded(<?=$key?>,'<?=$tagpengarangtambahan?>','<?=$indexruas?>')"><i class="glyphicon glyphicon-trash"></i></button>
                                                    <//?php
                                                    }
                                                    ?>
                                                    </span>
                                                  </div>
                                                </div>
                                            </div>
                                            <//?php
                                            }else{
                                            //jika aacr maka hanya form pengarang utama
                                            ?>
                                            <div class="input-group" style="margin-top:5px">
                                                <input value="<?=$value?>" type="text" id="collectionbiblio-author-<?=$key?>" class="form-control tajukpengarang" onkeyup="AutoCompleteOn(this,'pengarang');" name="CollectionBiblio[AuthorAdded][<?=$key?>]" style="width:100%" placeholder="Masukan <?=Yii::t('app', $translateTag7XX);?>...">
                                                <span class="input-group-btn">
                                                <//?php 
                                                if($key == 0)
                                                {
                                                ?>
                                                  <button id="btnAuthorAdded" class="btn btn-success pull-right" type="button" onclick="AddAuthorAdded();"><i class="glyphicon glyphicon-plus"></i></button>
                                                <//?php
                                                }else{
                                                ?>
                                                  <button class="btn btn-danger btn-flat" type="button" onclick="RemoveAuthorAdded(<?=$key?>,'<?=$tagpengarangtambahan?>','<?=$indexruas?>')"><i class="glyphicon glyphicon-trash"></i></button>
                                                <//?php
                                                }
                                                ?>
                                                </span>
                                              </div>
                                            <//?php
                                            }
                                            ?>
                                            <div class="btm-add-on" style="text-align:left" >
                                              <input type="hidden" name="CollectionBiblio[AuthorAddedType][<?=$key?>]" value="">
                                                <div id="collectionbiblio-authortype-<?=$key?>" >
                                                <label><input <?=$type1?> type="radio" name="CollectionBiblio[AuthorAddedType][<?=$key?>]" value="0"> <?=yii::t('app','Nama Depan')?></label>
                                                <label><input <?=$type2?> type="radio" name="CollectionBiblio[AuthorAddedType][<?=$key?>]" value="1"><?=yii::t('app','Nama Belakang')?></label>
                                                <label><input <?=$type3?> type="radio" name="CollectionBiblio[AuthorAddedType][<?=$key?>]" value="3"> <?=yii::t('app','Nama Keluarga')?></label>
                                                <label><input <?=$type4?> type="radio" name="CollectionBiblio[AuthorAddedType][<?=$key?>]" value="#"> <?=yii::t('app','Badan Korporasi')?></label>
                                                <label><input <?=$type5?> type="radio" name="CollectionBiblio[AuthorAddedType][<?=$key?>]" value="##"> <?=yii::t('app','Nama Pertemuan')?></label>
                                                </div>
                                            </div>
                                          </div>
                                          <//?php
                                                }
                                              }else{
                                          ?>
                                          <div id="DivAuthorAdded0">
                                            <input type="hidden" id="Ruasid_700_0" name="Ruasid[700][0]" value="<?=$taglist['ruasid'][700][0]?>" size="3" />
                                            <//?php 
                                            if($rda=='1')
                                            {
                                            //jika rda maka ada tambahan relator term
                                            ?>
                                            <div class="row">

                                                <div class="col-sm-3" style="padding-right: 0px">
                                                 <//?php 
                                                echo  Html::activeDropDownList($modelbib,'AuthorAddedRelatorTerm[0]',
                                                $relatorTerm,
                                                ['class'=>'form-control']
                                              ); ?> 
                                                </div>
                                                
                                                <div class="col-sm-9" style="padding-left: 0px">
                                                    <div class="input-group">
                                                      <//?php echo Html::activeTextInput($modelbib,'AuthorAdded[0]',['class'=>'form-control tajukpengarang','onkeyup'=>"AutoCompleteOn(this,'pengarang');",'style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', $translateTag7XX).'...']); ?>
                                                      <span class="input-group-btn">
                                                        <button id="btnAuthorAdded" class="btn btn-success pull-right" type="button" onclick="AddAuthorAdded();"><i class="glyphicon glyphicon-plus"></i></button>
                                                      </span>
                                                    </div>
                                                 </div>
                                              
                                            </div>
                                            <//?php 
                                            }else{ 
                                            //jika aacr maka hanya form pengarang utama
                                            ?>
                                            <div class="input-group">
                                              <//?php echo Html::activeTextInput($modelbib,'AuthorAdded[0]',['class'=>'form-control tajukpengarang','onkeyup'=>"AutoCompleteOn(this,'pengarang');",'style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', $translateTag7XX).'...']); ?>
                                              <span class="input-group-btn">
                                                <button id="btnAuthorAdded" class="btn btn-success pull-right" type="button" onclick="AddAuthorAdded();"><i class="glyphicon glyphicon-plus"></i></button>
                                              </span>
                                            </div>
                                            <//?php 
                                            }
                                            ?>


                                            <div class="btm-add-on" style="text-align:left">
                                               <//?php $list = [0 => yii::t('app','Nama Depan'), 1 => yii::t('app','Nama Belakang'), 3 => yii::t('app','Nama Keluarga'), '#' => yii::t('app','Badan Korporasi'), '##' => yii::t('app','Nama Pertemuan')];
                                               echo Html::activeRadioList($modelbib, 'AuthorAddedType[0]',$list); 
                                               ?>
                                            </div>
                                          </div>
                                          <//?php
                                              }
                                          ?>
                                          
                                        </div>
                                        <div id="error-700" class="help-block"></div>
                                      </div>

                                    </span>
                                </div>
                            </div>
                        </div>-->
                      <!-- ----------------------- form baru ----------  -->

                          <div class="form-group kv-fieldset-inline">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <span class="<?=$listvar['input_required']['700']['status']?>"  id="status-700">
                                    <input type="hidden" id="message-700" value="<?=$listvar['input_required']['700']['message']?>" />
                                    <label class="control-label col-sm-2" for="email">
                                      <?php 
                                      if($rda=='1')
                                      {
                                        $translateTag7XX='bib_Pengarang Tambahan_rda';
                                      }else{
                                        $translateTag7XX='bib_Pengarang Tambahan';
                                      }
                                      ?>

                                      <?=Yii::t('app', $translateTag7XX);?>
                                    </label>
                                    <div class="col-sm-9">
                                      <input id="TajukCount" type="hidden" value="<?=count($listvar['tajuk'])?>">
                                          <div id="TajukList">
                                            <?php
                                              if(count($listvar['tajuk']) > 0){
                                                $count700=0;
                                                $count710=0;
                                                $count711=0;
                                                foreach ($listvar['tajuk'] as $key => $value) {
                                                  $type1=''; $type2=''; $type3=''; $type4=''; $type5='';
                                                  // echo'<pre>';print_r($listvar['tajukind'][$key]);echo'</pre>';
                                                  switch ($listvar['tajukind'][$key]) {
                                                    case '#':
                                                      $type1='checked ';
                                                      break;
                                                    case '0':
                                                      $type2='checked ';
                                                      break;
                                                    case '1':
                                                      $type3='checked ';
                                                      break;
                                                    case '2':
                                                      $type4='checked ';
                                                      break;
                                                    case '3':
                                                      $type5='checked ';
                                                      break;
                                                    
                                                    default:
                                                      # code...
                                                      break;
                                                  }

                                                  // if($listvar['tajuktag'][$key] == '700')
                                                  // {
                                                  //   $displaystatus ="";
                                                  // }else{
                                                  //   $displaystatus = "style=\"display: none\"";
                                                  // }

                                                  if($listvar['tajuktag'][$key] == '700'){
                                                      $count700++;
                                                      $indexruas=$count700-1;
                                                      $sort_tag = '72';
                                                  }else if($listvar['tajuktag'][$key] == '710'){
                                                      $count710++;
                                                      $indexruas=$count710-1;
                                                      $sort_tag = '73';
                                                  }else if($listvar['tajuktag'][$key] == '711'){
                                                      $count711++;
                                                      $indexruas=$count711-1;
                                                      $sort_tag = '23';
                                                  }

                                            ?>
                                            <div id="DivTajuk<?=$key?>">
                                               <input type="hidden" id="Ruasid_<?=$listvar['tajuktag'][$key]?>_<?=$indexruas?>" name="Ruasid[<?=$listvar['tajuktag'][$key]?>][<?=$indexruas?>]"  value="<?=$taglist['ruasid'][$listvar['tajuktag'][$key]][$indexruas]?>" size="3" />
                                               <div class="row" style="margin-top: 5px">
                                                   <div class="col-sm-3" style="padding-right: 0px">
                                                  <?php 
                                                  echo  Html::activeDropDownList($modelbib,'TajukTag['.$key.']',
                                                    [
                                                      '700'=>yii::t('app','Nama Orang'),
                                                      '710'=>yii::t('app','Badan Korporasi'),
                                                      '711'=>yii::t('app','Pertemuan')
                                                    ],
                                                    [
                                                    'class'=>'form-control',
                                                    'onchange'=>'ShowOptionTajuk(<?=$key?>);'
                                                    ]
                                                  ); ?> 

                                                   </div>
                                                   <div class="col-sm-9" style="padding-left: 0px">
                                                      <div class="input-group">
                                                        <input value="<?=$value?>" type="text" id="TagsValue_<?=$listvar['tajuktag'][$key]?>_<?=$key?>" class="tag700_<?=$key?> form-control tajukpengarang" onkeyup="AutoCompleteOn(this,'pengarang');" name="CollectionBiblio[Tajuk][<?=$key?>]" style="width:100%" placeholder="Masukan Subject...">
                                                        
                                                        <span class="input-group-btn">
                                                        <?php 
                                                        if($key == 0)
                                                        {
                                                        ?>
                                                          <button class="btnTaj_<?=$key?> btn btn-warning btn-flat" type="button" onclick="PickRuas(<?=$sort_tag?>,'<?=$listvar['tajuktag'][$key]?>','<?=$indexruas?>')" id="pickTaj_<?=$key?>" data-toggle="modal" data-target="#helper-modal"><i class="glyphicon glyphicon-th-list"></i></button>

                                                          <button id="btnSubject" class="btn btn-success" type="button" onclick="AddTajuk();"><i class="glyphicon glyphicon-plus"></i></button>
                                                        <?php
                                                        }else{
                                                        ?>
                                                          <button class="btnTaj_<?=$key?> btn btn-warning btn-flat" type="button" onclick="PickRuas(<?=$sort_tag?>,'<?=$listvar['tajuktag'][$key]?>','<?=$key?>')" id="pickTaj_<?=$key?>" data-toggle="modal" data-target="#helper-modal"><i class="glyphicon glyphicon-th-list"></i></button>

                                                          <button class="btn btn-danger btn-flat" type="button" onclick="RemoveTajuk(<?=$key?>,'<?=$listvar['tajuktag'][$key]?>','<?=$indexruas?>')"><i class="glyphicon glyphicon-trash"></i></button>
                                                        <?php
                                                        }
                                                        ?>
                                                        </span>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="btm-add-on" style="text-align:left">
                                                     <input type="hidden" name="CollectionBiblio[TajukInd][<?=$key?>]" value="">
                                                      <div id="collectionbiblio-tajukind-<?=$key?>">
                                                        <!-- <label id="opt#_<?=$key?>"><input <?=$type1?> type="radio" id="tajukind_X_<?=$key?>" name="CollectionBiblio[TajukInd][<?=$key?>]" value="#"> <?= yii::t('app','Tdk Ada Info Tambahan')?></label>
                                                        <label id="opt0_<?=$key?>" <?=$displaystatus?> ><input <?=$type2?> type="radio" id="subjectind_0_<?=$key?>" name="CollectionBiblio[TajukInd][<?=$key?>]" value="0"> <?= yii::t('app','Nama Depan')?></label>
                                                        <label id="opt1_<?=$key?>" <?=$displaystatus?> ><input <?=$type3?> type="radio" id="subjectind_1_<?=$key?>" name="CollectionBiblio[TajukInd][<?=$key?>]" value="1"> <?= yii::t('app','Nama Belakang')?></label>
                                                        <label id="opt3_<?=$key?>" <?=$displaystatus?> ><input <?=$type4?> type="radio" id="subjectind_3_<?=$key?>" name="CollectionBiblio[TajukInd][<?=$key?>]" value="3"> <?= yii::t('app','Nama Keluarga')?></label>-->                                                    
                                                        <?php if($listvar['tajuktag'][$key] == '700') {?>
                                                          <label id="opttaj0_<?=$key?>"><input <?=$type2?> type="radio" id="tajukind_X_<?=$key?>" name="CollectionBiblio[TajukInd][<?=$key?>]" value="0"> <?= yii::t('app','Nama Depan')?></label>
                                                          <label id="opttaj1_<?=$key?>" <?=$displaystatus?>><input <?=$type3?> type="radio" id="tajukind_0_<?=$key?>" name="CollectionBiblio[TajukInd][<?=$key?>]" value="1"> <?= yii::t('app','Nama Belakang')?></label>
                                                          <label id="opttaj2_<?=$key?>" <?=$displaystatus?>><input <?=$type5?> type="radio" id="tajukind_1_<?=$key?>" name="CollectionBiblio[TajukInd][<?=$key?>]" value="3"> <?= yii::t('app','Nama Keluarga')?></label>
                                                        <?php }else{ ?>
                                                          <label id="opttaj3_<?=$key?>" <?=$displaystatus?>><input <?=$type2?> type="radio" id="tajukind_2_<?=$key?>" name="CollectionBiblio[TajukInd][<?=$key?>]" value="0"> <?= yii::t('app','Nama Dibalik')?></label>
                                                          <label id="opttaj4_<?=$key?>" <?=$displaystatus?>><input <?=$type3?> type="radio" id="tajukind_3_<?=$key?>" name="CollectionBiblio[TajukInd][<?=$key?>]" value="1"> <?= yii::t('app','Nama Yuridiksi')?></label>
                                                          <label id="opttaj5_<?=$key?>" <?=$displaystatus?>><input <?=$type4?> type="radio" id="tajukind_4_<?=$key?>" name="CollectionBiblio[TajukInd][<?=$key?>]" value="2"> <?= yii::t('app','Nama Ditulis Langsung')?></label>
                                                        <?php } ?>
                                                      </div>
                                                  </div>
                                            </div>
                                            <?php
                                                }
                                              }else{
                                            ?>

                                            <div id="DivTajuk0">
                                              <input type="hidden" id="Ruasid_tajuk_0" name="Ruasid[700][0]" value="<?=$taglist['ruasid'][700][0]?>" size="3" />
                                              <div class="row">
                                                   <div class="col-sm-3" style="padding-right: 0px">
                                                  <?php 
                                                  echo  Html::activeDropDownList($modelbib,'TajukTag[0]',
                                                    [
                                                      '700'=>yii::t('app','Nama Orang'),
                                                      '710'=>yii::t('app','Badan Korporasi'),
                                                      '711'=>yii::t('app','Pertemuan')
                                                    ],
                                                    [
                                                    'class'=>'form-control',
                                                    'onchange'=>'ShowOptionTajuk(0);'
                                                    ]
                                                  ); ?> 

                                                   </div>
                                                   <div class="col-sm-9" style="padding-left: 0px">
                                                      <div class="input-group">
                                                        <?php echo Html::activeTextInput($modelbib,'Tajuk[]',['class'=>'tag700_0 form-control tajukpengarang',"onkeyup"=>"AutoCompleteOn(this,'pengarang');",'style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', $translateTag7XX).'...','id' => 'TagsValue_700_0']); ?>

                                                          <span class="input-group-btn">
                                                            <a href="javascript:void(0)" id="pickTaj_0" class="btnTaj_0 btn btn-warning pull-right" type="button" data-toggle="modal" data-target="#helper-modal" onclick="PickRuas('72','700','0')"><i class="glyphicon glyphicon-th-list"></i></a>
                                                          </span>

                                                          
                                                          <span class="input-group-btn">
                                                            <button id="btnTajuk" class="btn btn-success pull-right" type="button" onclick="AddTajuk();"><i class="glyphicon glyphicon-plus"></i></button>
                                                          </span>
                                                      </div>
                                                   </div>
                                              </div>
                                              
                                                <div class="btm-add-on" style="text-align:left">
                                                     <input type="hidden" name="CollectionBiblio[TajukInd][0]" value="">
                                                  <div id="collectionbiblio-tajukind-0">
                                                    
                                                      <label id="opttaj0_0"><input checked type="radio" id="tajukind_X_0" name="CollectionBiblio[TajukInd][0]" value="0"> <?= yii::t('app','Nama Depan')?></label>
                                                      <label id="opttaj1_0"><input type="radio" id="tajukind_0_0" name="CollectionBiblio[TajukInd][0]" value="1"> <?= yii::t('app','Nama Belakang')?></label>
                                                      <label id="opttaj2_0"><input type="radio" id="tajukind_1_0" name="CollectionBiblio[TajukInd][0]" value="3"> <?= yii::t('app','Nama Keluarga')?></label>
                                                    
                                                    
                                                    
                                                      <label id="opttaj3_0" style="display: none"><input type="radio" id="tajukind_2_0" name="CollectionBiblio[TajukInd][0]" value="0"> <?= yii::t('app','Nama Dibalik')?></label>
                                                      <label id="opttaj4_0" style="display: none"><input type="radio" id="tajukind_3_0" name="CollectionBiblio[TajukInd][0]" value="1"> <?= yii::t('app','Nama Yuridiksi')?></label>
                                                      <label id="opttaj5_0" style="display: none"><input type="radio" id="tajukind_4_0" name="CollectionBiblio[TajukInd][0]" value="2"> <?= yii::t('app','Nama Ditulis Langsung')?></label>
                                                    
                                                  
                                                  </div>
                                                </div>
                                              
                                            </div>

                                            <?php
                                              }

                                            ?>
                                          </div>
                                          <div id="error-700" class="help-block"></div>
                                    </div>
                                    </span>
                                  </div>
                            </div>
                          </div>

                      <!-- -------------------------- batas akhir form baru --------------------- -->

                      

                      </div>
                    </div>
                    

                    <div class="panel panel-default">
                        <div class="panel-heading">
                          <?php if($rda=='1') { 
                            echo yii::t('app','Publikasi'); 
                            $translateTag260a='bib_Lokasi terbit_rda'; 
                            $translateTag260b='bib_Penerbit_rda'; 
                            $translateTag260c='bib_Tahun terbit_rda'; 
                            }else{ 
                            echo yii::t('app','Penerbitan'); 
                            $translateTag260a='bib_Lokasi terbit'; 
                            $translateTag260b='bib_Penerbit'; 
                            $translateTag260c='bib_Tahun terbit'; 
                            } ?>
                        </div>
                        <div class="panel-body">
                        
                        <input id="PublicationAddCount" type="hidden" value="<?=count($listvar['publication'])?>">

                        <span class="<?=$listvar['input_required']['260']['status']?>"  id="status-260">
                        <input type="hidden" id="message-260" value="<?=$listvar['input_required']['260']['message']?>" />

                        <div id="PublicationAddList">

                        <?php
                            if($rda=='1')
                            {
                              $tagpublication='264';
                            }else{
                              $tagpublication='260';
                            }
                            if(count($listvar['publication']) > 0){
                              
                              foreach ($listvar['publication'] as $key => $value) {

                              if($key==0){ $stylemargin=''; }else{ $stylemargin='margin-top:15px;';}
                        ?>

                          <div  id="DivPublication<?=$key?>" style="<?=$stylemargin?>">
                            <input type="hidden" id="Ruasid_<?=$tagpublication?>_<?=$key?>" name="Ruasid[<?=$tagpublication?>][<?=$key?>]" value="<?=$taglist['ruasid'][$tagpublication][$key]?>" size="3" />

                            <div class="form-group kv-fieldset-inline">
                              <div class="col-sm-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-2" for="email"><?=Yii::t('app', $translateTag260a)?></label>
                                      <div class="col-sm-6">
                                        <?php echo Html::activeTextInput($modelbib,'PublishLocation['.$key.']',['id'=>'collectionbiblio-publishlocation-'.$key,'class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', $translateTag260a).'...']); ?>
                                      </div>
                                      <?php 
                                      $styleborderbottom="";
                                      if($rda=='1')
                                      {
                                      $styleborderbottom="border-bottom: solid 2px #EEE; padding-bottom:15px";
                                      //jika rda maka publikasi bisa repeatable
                                      ?>
                                      <div class="col-sm-4">
                                        <?php 
                                        if($key == 0)
                                        {
                                        ?>
                                          <button id="btnPublication" class="btn btn-success pull-right" type="button" tabindex="-1" onclick="AddPublication(<?=$tagpublication?>);"><i class="glyphicon glyphicon-plus"></i></button>
                                        <?php
                                        }else{
                                        ?>
                                          <button class="btn btn-danger pull-right" type="button" tabindex="-1" onclick="RemovePublication(<?=$key?>,'<?=$tagpublication?>')"><i class="glyphicon glyphicon-trash"></i></button>
                                        <?php
                                        }
                                        ?>
                                      </div>
                                      <?php 
                                      }
                                      ?>
                                    </div>
                              </div>
                              <!-- <div class="col-sm-6">
                                  &nbsp;
                              </div> -->
                            </div>

                            <div class="form-group kv-fieldset-inline">
                              <div class="col-sm-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-2" for="email"><?=Yii::t('app', $translateTag260b)?></label>
                                      <div class="col-sm-6">
                                        <?php echo Html::activeTextInput($modelbib,'Publisher['.$key.']',['id'=>'collectionbiblio-publisher-'.$key,'class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', $translateTag260b).'...']); ?>
                                      </div>
                                      </span>
                                    </div>
                              </div>
                              <!-- <div class="col-sm-6">
                                  &nbsp;
                              </div> -->
                            </div>

                            <div class="form-group kv-fieldset-inline" style="<?=$styleborderbottom?>">
                              <div class="col-sm-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-2" for="email"><?=Yii::t('app', $translateTag260c)?></label>
                                      <div class="col-sm-4">
                                        <?php echo Html::activeTextInput($modelbib,'PublishYear['.$key.']',['id'=>'collectionbiblio-publishyear-'.$key,'class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', $translateTag260c).'...']); ?>
                                      </div>
                                  </div>
                              </div>
                              <!-- <div class="col-sm-6">
                                  &nbsp;
                              </div> -->
                            </div>
                        </div>

                        <?php
                            }
                        }else{
                        ?>

                        <div id="DivPublication0">
                            <input type="hidden" id="Ruasid_<?=$tagpublication?>_0" name="Ruasid[<?=$tagpublication?>][0]" value="<?=$taglist['ruasid'][$tagpublication][0]?>" size="3" />


                                <div class="form-group kv-fieldset-inline">
                                  <div class="col-sm-12">
                                      <div class="form-group">
                                          <label class="control-label col-sm-2" for="email"><?=Yii::t('app', $translateTag260a)?></label>
                                          <div class="col-sm-6">
                                            <?php echo Html::activeTextInput($modelbib,'PublishLocation[0]',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', $translateTag260a).'...']); ?>
                                          </div>
                                          <?php 
                                          $styleborderbottom="";
                                          if($rda=='1')
                                          {
                                          $styleborderbottom="border-bottom: solid 2px #EEE; padding-bottom:15px";
                                          //jika rda maka publikasi bisa repeatable
                                          ?>
                                          <div class="col-sm-4">
                                            <button id="btnPublication" class="btn btn-success pull-right" type="button" tabindex="-1" onclick="AddPublication(<?=$tagpublication?>);"><i class="glyphicon glyphicon-plus"></i></button>
                                          </div>
                                          <?php 
                                          }
                                          ?>
                                        </div>
                                  </div>
                                  <!-- <div class="col-sm-6">
                                      &nbsp;
                                  </div> -->
                                </div>

                                <div class="form-group kv-fieldset-inline">
                                  <div class="col-sm-12">
                                      <div class="form-group">
                                          <label class="control-label col-sm-2" for="email"><?=Yii::t('app', $translateTag260b)?></label>
                                          <div class="col-sm-6">
                                            <?php echo Html::activeTextInput($modelbib,'Publisher[0]',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', $translateTag260b).'...']); ?>
                                          </div>
                                        </div>
                                  </div>
                                  <!-- <div class="col-sm-6">
                                      &nbsp;
                                  </div> -->
                                </div>

                                <div class="form-group kv-fieldset-inline" style="<?=$styleborderbottom?>">
                                  <div class="col-sm-12">
                                      <div class="form-group">
                                          <label class="control-label col-sm-2" for="email"><?=Yii::t('app', $translateTag260c)?></label>
                                          <div class="col-sm-4">
                                            <?php echo Html::activeTextInput($modelbib,'PublishYear[0]',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', $translateTag260c).'...']); ?>
                                          
                                          </div>
                                        </div>

                                  </div>
                                  <!-- <div class="col-sm-6">
                                      &nbsp;
                                  </div> -->

                                </div>
                            </div>

                            <?php
                              }
                            ?>

                        </div>
                        <?php if($isSerial == 1){ ?>
                          <br>
                          <div class="form-group kv-fieldset-inline">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="email">Frekuensi Saat Ini<?php //echo Html::activeLabel($modelbib,'FrekuensiSaatIni'); ?></label>
                                    <div class="col-sm-6">
                                      <?php echo Html::activeTextInput($modelbib,'FrekuensiSaatIni',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Frekuensi Publikasi Saat Ini').'...']); ?>
                                      <div class="help-block"></div>
                                    </div>
                                  </div>
                            </div>
                            <!-- <div class="col-sm-1">
                                &nbsp;
                            </div> -->
                          </div>
                          <div class="form-group kv-fieldset-inline">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                         <span class="<?=$listvar['input_required']['321']['status']?>"  id="status-321">
                                            <input type="hidden" id="message-321" value="<?=$listvar['input_required']['321']['message']?>" />
                                            <label class="control-label col-sm-2" for="email">
                                              Frekuensi Publikasi Sebelumnya
                                            </label>
                                            <div class="col-sm-9">
                                                <input id="FrekSebelumAddCount" type="hidden" value="<?=count($listvar['frekuensisebelum'])?>">
                                                <div id="FrekSebelumAddList">
                                                  <?php 
                                                    if(count($listvar['frekuensisebelum']) > 0){
                                                      $count321=0;
                                                      foreach ($listvar['frekuensisebelum'] as $key => $value) {
                                                        $indexruas=$count321-1;
                                                  ?>
                                                        <div id="DivFrekSebelumAdded<?=$key?>">
                                                            <input type="hidden" id="Ruasid_<?=$tagfrekuensisebelum?>_<?=$indexruas?>" name="Ruasid[<?=$tagfrekuensisebelum?>][<?=$indexruas?>]" value="<?=$taglist['ruasid'][$tagfrekuensisebelum][$indexruas]?>"/>
                                                            <div class="input-group" style="margin-top:5px">
                                                                <input value="<?=$value?>" type="text" id="collectionbiblio-frekuensisebelum-<?=$key?>" class="form-control" name="CollectionBiblio[FrekuensiSebelumAdded][<?=$key?>]" style="width:100%" placeholder="Masukan Frekuensi Sebelumnya...">
                                                                <span class="input-group-btn">
                                                                <?php 
                                                                if($key == 0)
                                                                {
                                                                ?>
                                                                  <button id="btnFrekuensiSebelumAdded" class="btn btn-success pull-right" type="button" onclick="AddFrekuensiSebelum();"><i class="glyphicon glyphicon-plus"></i></button>
                                                                <?php
                                                                }else{
                                                                ?>
                                                                  <button class="btn btn-danger btn-flat" type="button" onclick="RemoveFrekuensiSebelumAdded(<?=$key?>,'<?=$tagfrekuensisebelum?>','<?=$indexruas?>')"><i class="glyphicon glyphicon-trash"></i></button>
                                                                <?php
                                                                }
                                                                ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                  <?php 
                                                      }
                                                    }else{
                                                  ?>
                                                      <div id="DivFrekSebelumAdded0">
                                                          <input type="hidden" id="Ruasid_247_0" name="Ruasid[247][0]" value="<?=$taglist['ruasid'][321][0]?>" size="3"/>
                                                          <div class="input-group">
                                                            <?php echo Html::activeTextInput($modelbib,'FrekuensiSebelumAdded[0]',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' Frekuensi Publikasi Sebelumnya...']); ?>
                                                            <span class="input-group-btn">
                                                              <button id="btnFrekuensiSebelumAdded" class="btn btn-success pull-right" type="button" onclick="AddFrekuensiSebelum();"><i class="glyphicon glyphicon-plus"></i></button>
                                                            </span>
                                                          </div>
                                                      </div>
                                                  <?php } ?>
                                                </div>
                                            </div>
                                         </span>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <div id="error-260" class="help-block"></div>
                        </span>
                        </div>
                    </div>
                          


                    <div class="panel panel-default ">
                      <div class="panel-heading"><?= yii::t('app','Deskripsi Fisik')?></div>
                      <div class="panel-body">


                          <span class="<?=$listvar['input_required']['300']['status']?>"  id="status-300">
                          <input type="hidden" id="message-300" value="<?=$listvar['input_required']['300']['message']?>" />

                          <div class="form-group kv-fieldset-inline">
                            <div class="col-sm-12">
                            <input type="hidden" id="Ruasid_300" name="Ruasid[300]" value="<?=$taglist['ruasid'][300]?>" size="3" />
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'JumlahHalaman'); ?></label>
                                    <div class="col-sm-6">
                                      <?php echo Html::activeTextInput($modelbib,'JumlahHalaman',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_Jumlah Halaman').'...']); ?>
                                    </div>
                                  </div>
                            </div>
                            <!-- <div class="col-sm-6">
                                &nbsp;
                            </div> -->
                          </div>

                          <div class="form-group kv-fieldset-inline">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'KeteranganIllustrasi'); ?></label>
                                    <div class="col-sm-6">
                                      <?php echo Html::activeTextInput($modelbib,'KeteranganIllustrasi',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_Keterangan Illustrasi').'...']); ?>
                                    </div>
                                  </div>
                            </div>
                            <!-- <div class="col-sm-6">
                                &nbsp;
                            </div> -->
                          </div>

                          <div class="form-group kv-fieldset-inline">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'Dimensi'); ?></label>
                                    <div class="col-sm-3">
                                      <?php echo Html::activeTextInput($modelbib,'Dimensi',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_Dimensi').'...']); ?>
                                    </div>
                                  </div>
                            </div>
                            <!-- <div class="col-sm-6">
                                &nbsp;
                            </div> -->
                          </div>

                          <div class="form-group kv-fieldset-inline">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'BahanSertaan'); ?></label>
                                    <div class="col-sm-3">
                                      <?php echo Html::activeTextInput($modelbib,'BahanSertaan',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_Bahan Sertaan').'...']); ?>
                                    </div>
                                  </div>
                            </div>
                            <!-- <div class="col-sm-6">
                                &nbsp;
                            </div> -->
                          </div>

                          <div id="error-300" class="help-block"></div>
                      </div>

                    </div>

                    <div class="panel panel-default ">
                      <div class="panel-body">

                    <?php 
                    if($rda=='1')
                    {
                    $visibleRDA= 'block';
                    }else{
                    $visibleRDA= 'none';
                    }
                    ?>  
                    <div class="rdainput form-group kv-fieldset-inline" style="display: <?=$visibleRDA?>">
                      <div class="col-sm-12">
                          <div class="form-group">
                              <span class="<?=$listvar['input_required']['336']['status']?>"  id="status-336">
                              <input type="hidden" id="message-336" value="<?=$listvar['input_required']['336']['message']?>" />
                              <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'JenisIsi'); ?></label>
                              <div class="col-sm-6">
                                <input type="hidden" id="Ruasid_336_0" name="Ruasid[336][0]" value="<?=$taglist['ruasid'][336][0]?>" size="3" />
                                <?php echo Html::activeTextInput($modelbib,'JenisIsi',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_JenisIsi').'...']); ?>
                              <div id="error-336" class="help-block"></div>
                              </div>
                              </span>
                            </div>
                      </div>
                      <!-- <div class="col-sm-6">
                          &nbsp;
                      </div> -->
                    </div>
                    <div class="rdainput form-group kv-fieldset-inline" style="display: <?=$visibleRDA?>">
                      <div class="col-sm-12">
                          <div class="form-group">
                              <span class="<?=$listvar['input_required']['337']['status']?>"  id="status-337">
                              <input type="hidden" id="message-337" value="<?=$listvar['input_required']['337']['message']?>" />
                              <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'JenisMedia'); ?></label>
                              <div class="col-sm-6">
                                <input type="hidden" id="Ruasid_337_0" name="Ruasid[337][0]" value="<?=$taglist['ruasid'][337][0]?>" size="3" />
                                <?php echo Html::activeTextInput($modelbib,'JenisMedia',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_JenisMedia').'...']); ?>
                              <div id="error-337" class="help-block"></div>
                              </div>
                              </span>
                            </div>
                      </div>
                      <!-- <div class="col-sm-6">
                          &nbsp;
                      </div> -->
                    </div>
                    <div class="rdainput form-group kv-fieldset-inline" style="display: <?=$visibleRDA?>">
                      <div class="col-sm-12">
                          <div class="form-group">
                              <span class="<?=$listvar['input_required']['338']['status']?>"  id="status-338">
                              <input type="hidden" id="message-338" value="<?=$listvar['input_required']['338']['message']?>" />
                              <label class="control-label col-sm-2" for="email"><?= yii::t('app','Jenis Wadah')?><?php //echo Html::activeLabel($modelbib,'JenisCarrier'); ?></label>
                              <div class="col-sm-6">
                                <input type="hidden" id="Ruasid_338_0" name="Ruasid[338][0]" value="<?=$taglist['ruasid'][338][0]?>" size="3" />
                                <?php echo Html::activeTextInput($modelbib,'JenisCarrier',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' Jenis Wadah...']); ?>
                              <div id="error-338" class="help-block"></div>
                              </div>
                              </span>
                            </div>
                      </div>
                      <!-- <div class="col-sm-6">
                          &nbsp;
                      </div> -->
                    </div>


  <?php
  //Khusus jenis bahan terbitan berkala (serial)
  if($isSerial != 1)
  {
  ?>
    <div class="form-group kv-fieldset-inline">
      <div class="col-sm-12">
          <div class="form-group">
              <span class="<?=$listvar['input_required']['250']['status']?>"  id="status-250">
              <input type="hidden" id="message-250" value="<?=$listvar['input_required']['250']['message']?>" />
              <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'Edition'); ?></label>
              <div class="col-sm-6">
                <input type="hidden" id="Ruasid_250" name="Ruasid[250]" value="<?=$taglist['ruasid'][250]?>" size="3" />
                <?php echo Html::activeTextInput($modelbib,'Edition',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_Edisi').'...']); ?>
              <div id="error-250" class="help-block"></div>
              </div>
              </span>
            </div>
      </div>
      <!-- <div class="col-sm-6">
          &nbsp;
      </div> -->
    </div>



  <?php
  }
  ?>
                                    
    <div class="form-group kv-fieldset-inline">
      <div class="col-sm-12">
          <div class="form-group">
              <span class="<?=$listvar['input_required']['650']['status']?>"  id="status-650">
              <input type="hidden" id="message-650" value="<?=$listvar['input_required']['650']['message']?>" />
              <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'Subject'); ?></label>
              <div class="col-sm-9">
                <input id="SubjectCount" type="hidden" value="<?=count($listvar['subject'])?>">
                    <div id="SubjectList">
                      <?php
                        if(count($listvar['subject']) > 0){
                          $count600=0;
                          $count650=0;
                          $count651=0;
                          foreach ($listvar['subject'] as $key => $value) {
                            $type1=''; $type2=''; $type3=''; $type4='';
                            switch ($listvar['subjectind'][$key]) {
                              case '#':
                                $type1='checked ';
                                break;
                              case '0':
                                $type2='checked ';
                                break;
                              case '1':
                                $type3='checked ';
                                break;
                              case '3':
                                $type4='checked ';
                                break;
                              
                              default:
                                # code...
                                break;
                            }

                            if($listvar['subjecttag'][$key] == '600')
                            {
                              $displaystatus ="";
                            }else{
                              $displaystatus = "style=\"display: none\"";
                            }

                            if($listvar['subjecttag'][$key] == '600'){
                                $count600++;
                                $indexruas=$count600-1;
                                $sort_tag = '66';
                            }else if($listvar['subjecttag'][$key] == '650'){
                                $count650++;
                                $indexruas=$count650-1;
                                $sort_tag = '70';
                            }else if($listvar['subjecttag'][$key] == '651'){
                                $count651++;
                                $indexruas=$count651-1;
                                $sort_tag = '71';
                            }

                      ?>
                      <div id="DivSubject<?=$key?>">
                         <input type="hidden" id="Ruasid_<?=$listvar['subjecttag'][$key]?>_<?=$indexruas?>" name="Ruasid[<?=$listvar['subjecttag'][$key]?>][<?=$indexruas?>]"  value="<?=$taglist['ruasid'][$listvar['subjecttag'][$key]][$indexruas]?>" size="3" />
                         <div class="row" style="margin-top: 5px">
                             <div class="col-sm-3" style="padding-right: 0px">
                            <?php 
                            echo  Html::activeDropDownList($modelbib,'SubjectTag['.$key.']',
                              [
                                '600'=>yii::t('app','Nama Orang'),
                                '650'=>yii::t('app','Topikal'),
                                '651'=>yii::t('app','Nama Geografis')
                              ],
                              [
                              'class'=>'form-control',
                              'onchange'=>'ShowOptionSubject(<?=$key?>);'
                              ]
                            ); ?> 

                             </div>
                             <div class="col-sm-9" style="padding-left: 0px">
                                <div class="input-group">
                                  <input value="<?=$value?>" type="text" id="TagsValue_<?=$listvar['subjecttag'][$key]?>_<?=$key?>" class="tag650_<?=$key?> form-control tajuksubyek" onkeyup="AutoCompleteOn(this,'subyek');" name="CollectionBiblio[Subject][<?=$key?>]" style="width:100%" placeholder="Masukan Subject...">
                                  <!-- <input value="<?=$value?>" type="text" id="collectionbiblio-Subject-<?=$key?>" class="form-control tajuksubyek" onkeyup="AutoCompleteOn(this,'subyek');" name="CollectionBiblio[Subject][<?=$key?>]" style="width:100%" placeholder="Masukan Subject..."> -->
                                  <span class="input-group-btn">
                                  <?php 
                                  if($key == 0)
                                  {
                                    
                                  ?>
                                    <button class="btnSub_<?=$key?> btn btn-warning btn-flat" type="button" onclick="PickRuas(<?=$sort_tag?>,'<?=$listvar['subjecttag'][$key]?>','<?=$indexruas?>')" id="pickSub_<?=$key?>" data-toggle="modal" data-target="#helper-modal"><i class="glyphicon glyphicon-th-list"></i></button>

                                    <button id="btnSubject" class="btn btn-success" type="button" onclick="AddSubject();"><i class="glyphicon glyphicon-plus"></i></button>
                                  <?php
                                  }else{
                                  ?>
                                    <button class="btnSub_<?=$key?> btn btn-warning btn-flat" type="button" onclick="PickRuas(<?=$sort_tag?>,'<?=$listvar['subjecttag'][$key]?>','<?=$key?>')" id="pickSub_<?=$key?>" data-toggle="modal" data-target="#helper-modal"><i class="glyphicon glyphicon-th-list"></i></button>

                                    <button class="btn btn-danger btn-flat" type="button" onclick="RemoveSubject(<?=$key?>,'<?=$listvar['subjecttag'][$key]?>','<?=$indexruas?>')"><i class="glyphicon glyphicon-trash"></i></button>
                                  <?php
                                  }
                                  ?>
                                  </span>
                                </div>
                              </div>
                            </div>
                            <div class="btm-add-on" style="text-align:left">
                               <input type="hidden" name="CollectionBiblio[SubjectInd][<?=$key?>]" value="">
                                <div id="collectionbiblio-subjectind-<?=$key?>">
                                <label id="opt#_<?=$key?>"><input <?=$type1?> type="radio" id="subjectind_X_<?=$key?>" name="CollectionBiblio[SubjectInd][<?=$key?>]" value="#"> <?= yii::t('app','Tdk Ada Info Tambahan')?></label>
                                <label id="opt0_<?=$key?>" <?=$displaystatus?> ><input <?=$type2?> type="radio" id="subjectind_0_<?=$key?>" name="CollectionBiblio[SubjectInd][<?=$key?>]" value="0"> <?= yii::t('app','Nama Depan')?></label>
                                <label id="opt1_<?=$key?>" <?=$displaystatus?> ><input <?=$type3?> type="radio" id="subjectind_1_<?=$key?>" name="CollectionBiblio[SubjectInd][<?=$key?>]" value="1"> <?= yii::t('app','Nama Belakang')?></label>
                                <label id="opt3_<?=$key?>" <?=$displaystatus?> ><input <?=$type4?> type="radio" id="subjectind_3_<?=$key?>" name="CollectionBiblio[SubjectInd][<?=$key?>]" value="3"> <?= yii::t('app','Nama Keluarga')?></label>
                                </div>
                            </div>
                      </div>
                      <?php
                          }
                        }else{
                      ?>

                      <div id="DivSubject0">
                        <input type="hidden" id="Ruasid_650_0" name="Ruasid[650][0]" value="<?=$taglist['ruasid'][650][0]?>" size="3" />
                        <div class="row">
                             <div class="col-sm-3" style="padding-right: 0px">
                            <?php 
                            echo  Html::activeDropDownList($modelbib,'SubjectTag[0]',
                              [
                                '600'=>'Nama Orang',
                                '650'=>'Topikal',
                                '651'=>'Nama Geografis'
                              ],
                              [
                              'class'=>'form-control',
                              'onchange'=>'ShowOptionSubject(0);'
                              ]
                            ); ?> 

                             </div>
                             <div class="col-sm-9" style="padding-left: 0px">
                                <div class="input-group" id="input_subjek">
                                  <?php //echo Html::activeTextInput($modelbib,'Subject[]',['class'=>'form-control tajuksubyek',"onkeyup"=>"AutoCompleteOn(this,'subyek');",'style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_Subject').'...']); ?>

                                  <?php echo Html::activeTextInput($modelbib,'Subject[]',['class'=>'tag650_0 form-control tajuksubyek',"onkeyup"=>"AutoCompleteOn(this,'subyek');",'style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_Subject').'...','id' => 'TagsValue_600_0']); ?>

                                  

                                  <!-- <span class="input-group-btn">
                                    <a href="javascript:void(0)" id="pickSub_0" class="btnSub_0 btn btn-warning pull-right" type="button" data-toggle="modal" data-target="#helper-modal" onclick="PickRuas('66','600','0')"><i class="glyphicon glyphicon-th-list"></i></a>
                                  </span> -->

                                  
                                  <span class="input-group-btn">
                                    <button id="btnSubject" class="btn btn-success pull-right" type="button" onclick="AddSubject();"><i class="glyphicon glyphicon-plus"></i></button>
                                  </span>
                                </div>
                             </div>
                        </div>
                          <div class="btm-add-on" style="text-align:left">
                               <input type="hidden" name="CollectionBiblio[SubjectInd][0]" value="">
                            <div id="collectionbiblio-subjectind-0">
                            <label id="opt#_0"><input checked type="radio" id="subjectind_X_0" name="CollectionBiblio[SubjectInd][0]" value="#"> <?= yii::t('app','Tdk Ada Info Tambahan')?></label>
                            <label id="opt0_0"><input type="radio" id="subjectind_0_0" name="CollectionBiblio[SubjectInd][0]" value="0"> <?= yii::t('app','Nama Depan')?></label>
                            <label id="opt1_0"><input type="radio" id="subjectind_1_0" name="CollectionBiblio[SubjectInd][0]" value="1"> <?= yii::t('app','Nama Belakang')?></label>
                            <label id="opt3_0"><input type="radio" id="subjectind_3_0" name="CollectionBiblio[SubjectInd][0]" value="3"> <?= yii::t('app','Nama Keluarga')?></label>
                            </div>
                          </div>
                        
                      </div>

                      <?php
                        }

                      ?>
                    </div>
                    <div id="error-650" class="help-block"></div>
              </div>
              </span>
            </div>
      </div>
    
    </div>
  
  <div class="form-group kv-fieldset-inline">
    <div class="col-sm-12">
        <div class="form-group">
            <span class="<?=$listvar['input_required']['082']['status']?>"  id="status-082">
            <input type="hidden" id="message-082" value="<?=$listvar['input_required']['082']['message']?>" />
            <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'Class'); ?></label>
            <div class="col-sm-6">
              <input type="hidden" id="Ruasid_082_0" name="Ruasid[082][0]" value="<?=$taglist['ruasid']['082'][0]?>" size="3" />
              <?php echo Html::activeTextInput($modelbib,'Class',['class'=>'form-control','onfocus'=>"AutoCompleteDDC(this);",'style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_Kelas').'...']); ?>
            <div id="error-082" class="help-block"></div>
            </div>
            </span>
        </div>
    </div>
  </div>

  <div class="form-group kv-fieldset-inline">
    <div class="col-sm-12">
        <div class="form-group">
            <span class="<?=$listvar['input_required']['084']['status']?>"  id="status-084">
            <input type="hidden" id="message-084" value="<?=$listvar['input_required']['084']['message']?>" />
            <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'CallNumber'); ?></label>
            <div class="col-sm-6">
              <input id="CallNumberCount" type="hidden" value="<?=count($listvar['callnumber'])?>">
                  <div id="CallNumberList">
                    <?php
                      if(count($listvar['callnumber']) > 0){
                        foreach ($listvar['callnumber'] as $key => $value) {  
                    ?>
                    <div id="DivCallNumber<?=$key?>">
                      <input type="hidden" id="Ruasid_084_<?=$key?>" name="Ruasid[084][$key]" value="<?=$taglist['ruasid']['084'][$key]?>" size="3" />
                      <div style="margin-top:5px" class="input-group">
                        <input value="<?=$value?>" type="text" id="collectionbiblio-CallNumber-<?=$key?>" class="form-control" name="CollectionBiblio[CallNumber][<?=$key?>]" style="width:100%" placeholder="Masukan CallNumber..." onfocus="AutoCopyCallNumber(this)">
                        <span class="input-group-btn">
                        <?php 
                        if($key == 0)
                        {
                        ?>
                          <button id="btnCallNumber" class="btn btn-success pull-right" type="button" onclick="AddCallNumber();"><i class="glyphicon glyphicon-plus"></i></button>
                        <?php
                        }else{
                        ?>
                          <button class="btn btn-danger btn-flat" type="button" onclick="RemoveCallNumber(<?=$key?>)"><i class="glyphicon glyphicon-trash"></i></button>
                        <?php
                        }
                        ?>
                        </span>
                      </div>
                    </div>
                    <?php
                        }
                      }else{
                    ?>

                    <div id="DivCallNumber0">
                      <input type="hidden" id="Ruasid_084_0" name="Ruasid[084][0]" value="<?=$taglist['ruasid']['084'][0]?>" size="3" />
                      <div class="input-group">
                        <?php echo Html::activeTextInput($modelbib,'CallNumber[]',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_CallNumber').'...','onfocus'=>'AutoCopyCallNumber(this)']); ?>
                        <span class="input-group-btn">
                          <button id="btnCallNumber" class="btn btn-success pull-right" type="button" onclick="AddCallNumber();"><i class="glyphicon glyphicon-plus"></i></button>
                        </span>
                      </div>
                    </div>

                    <?php
                      }

                    ?>
                  </div>
                  <div id="error-084" class="help-block"></div>
            </div>

            </span>
          </div>
    </div>
    <!-- <div class="col-sm-6">
        &nbsp;
    </div> -->
  </div>

                                    
<?php
//Khusus jenis bahan terbitan berkala (serial)
if($isSerial ==1)
{
?>


      <div class="form-group kv-fieldset-inline">
        <div class="col-sm-12">
            <div class="form-group">
                <span class="<?=$listvar['input_required']['022']['status']?>"  id="status-022">
                <input type="hidden" id="message-022" value="<?=$listvar['input_required']['022']['message']?>" />
                <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'ISSN'); ?></label>
                <div class="col-sm-6">
                  <input id="ISSNCount" type="hidden" value="<?=count($listvar['issn'])?>">
                      <div id="ISSNList">
                        <?php
                          if(count($listvar['issn']) > 0){
                            foreach ($listvar['issn'] as $key => $value) {  
                        ?>
                        <div id="DivISSN<?=$key?>">
                          <input type="hidden" id="Ruasid_022_<?=$key?>" name="Ruasid[022][<?=$key?>]" value="<?=$taglist['ruasid']['022'][$key]?>" size="3" />
                          <div style="margin-top:5px" class="input-group">
                            <input value="<?=$value?>" type="text" id="collectionbiblio-issn-<?=$key?>" class="form-control" name="CollectionBiblio[ISSN][<?=$key?>]" style="width:100%" placeholder="Masukan ISSN...">
                            <span class="input-group-btn">
                            <?php 
                            if($key == 0)
                            {
                            ?>
                              <button id="btnISSN" class="btn btn-success pull-right" type="button" onclick="AddISSN();"><i class="glyphicon glyphicon-plus"></i></button>
                            <?php
                            }else{
                            ?>
                              <button class="btn btn-danger btn-flat" type="button" onclick="RemoveISSN(<?=$key?>)"><i class="glyphicon glyphicon-trash"></i></button>
                            <?php
                            }
                            ?>
                            </span>
                          </div>
                        </div>
                        <?php
                            }
                          }else{
                        ?>

                        <div id="DivISSN0">
                          <input type="hidden" id="Ruasid_022_0" name="Ruasid[022][0]" value="<?=$taglist['ruasid']['022'][0]?>" size="3" />
                          <div class="input-group">
                            <?php echo Html::activeTextInput($modelbib,'ISSN[]',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_ISSN').'...']); ?>
                            <span class="input-group-btn">
                              <button id="btnISSN" class="btn btn-success pull-right" type="button" onclick="AddISSN();"><i class="glyphicon glyphicon-plus"></i></button>
                            </span>
                          </div>
                        </div>

                        <?php
                          }

                        ?>
                      </div>
                    <div id="error-022" class="help-block"></div>
                </div>
                </span>
              </div>
        </div>
      </div>
                                    
<?php
}else{
?>
    <div class="form-group kv-fieldset-inline">
      <div class="col-sm-12">
          <div class="form-group">
              <span class="<?=$listvar['input_required']['020']['status']?>"  id="status-020">
              <input type="hidden" id="message-020" value="<?=$listvar['input_required']['020']['message']?>" />
              <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'ISBN'); ?></label>
              <div class="col-sm-6">
                <input id="ISBNCount" type="hidden" value="<?=count($listvar['isbn'])?>">
                    <div id="ISBNList">
                      <?php
                        if(count($listvar['isbn']) > 0){
                          foreach ($listvar['isbn'] as $key => $value) {  
                      ?>
                      <div id="DivISBN<?=$key?>">
                        <input type="hidden" id="Ruasid_020_<?=$key?>" name="Ruasid[020][<?=$key?>]" value="<?=$taglist['ruasid']['020'][$key]?>" size="3" />
                        <div style="margin-top:5px" class="input-group">
                          <input value="<?=$value?>" type="text" id="collectionbiblio-isbn-<?=$key?>" class="form-control" name="CollectionBiblio[ISBN][<?=$key?>]" style="width:100%" placeholder="Masukan ISBN...">
                          <span class="input-group-btn">
                          <?php 
                          if($key == 0)
                          {
                          ?>
                            <button id="btnISBN" class="btn btn-success pull-right" type="button" onclick="AddISBN();"><i class="glyphicon glyphicon-plus"></i></button>
                          <?php
                          }else{
                          ?>
                            <button class="btn btn-danger btn-flat" type="button" onclick="RemoveISBN(<?=$key?>)"><i class="glyphicon glyphicon-trash"></i></button>
                          <?php
                          }
                          ?>
                          </span>
                        </div>
                      </div>
                      <?php
                          }
                        }else{
                      ?>

                      <div id="DivISBN0">
                        <input type="hidden" id="Ruasid_020_0" name="Ruasid[020][0]" value="<?=$taglist['ruasid']['020'][0]?>" size="3" />
                        <div class="input-group">
                          <?php echo Html::activeTextInput($modelbib,'ISBN[]',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_ISBN').'...']); ?>
                          <span class="input-group-btn">
                            <button id="btnISBN" class="btn btn-success pull-right" type="button" onclick="AddISBN();"><i class="glyphicon glyphicon-plus"></i></button>
                          </span>
                        </div>
                      </div>

                      <?php
                        }

                      ?>
                    </div>
                  <div id="error-020" class="help-block"></div>
              </div>
              </span>
            </div>
      </div>
    </div>
<?php
}
?>

                                  
                                    

                                    </div>
                                    </div>

                                    <div class="panel panel-default ">
                                        <div class="panel-heading"><?= yii::t('app','Catatan')?></div>
                                        <div class="panel-body">
                                          <div class="form-group kv-fieldset-inline">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <span class="<?=$listvar['input_required']['520']['status']?>"  id="status-520">
                                                    <input type="hidden" id="message-520" value="<?=$listvar['input_required']['520']['message']?>" />
                                                    <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'Note'); ?></label>
                                                    <div class="col-sm-9">
                                                      <input id="NoteCount" type="hidden" value="<?=count($listvar['note'])?>">
                                                          <div id="NoteList">
                                                            <?php
                                                               if($rda=='1')
                                                               {
                                                                  $list = ['520' => yii::t('app','Abstrak / Anotasi'), '502' => yii::t('app','Catatan Disertasi'), '504' => yii::t('app','Catatan Bibliografi'), '505' => yii::t('app','Rincian Isi'), '500' => yii::t('app','Catatan Umum'), '542' => yii::t('app','Informasi Hak Cipta')];
                                                               }else{
                                                                  $list = ['520' => yii::t('app','Abstrak / Anotasi'), '502' => yii::t('app','Catatan Disertasi'), '504' => yii::t('app','Catatan Bibliografi'), '505' => yii::t('app','Rincian Isi'), '500' => yii::t('app','Catatan Umum')];
                                                               }


                                                              if(count($listvar['note']) > 0){
                                                                $count520=0;
                                                                $count502=0;
                                                                $count504=0;
                                                                $count505=0;
                                                                $count500=0;
                                                                $count542=0;
                                                                foreach ($listvar['note'] as $key => $value) {  

                                                                  if($listvar['notetag'][$key] == '520'){
                                                                      $count520++;
                                                                      $indexruas=$count520-1;
                                                                  }else if($listvar['notetag'][$key] == '502'){
                                                                      $count502++;
                                                                      $indexruas=$count502-1;
                                                                  }else if($listvar['notetag'][$key] == '504'){
                                                                      $count504++;
                                                                      $indexruas=$count504-1;
                                                                  }else if($listvar['notetag'][$key] == '505'){
                                                                      $count505++;
                                                                      $indexruas=$count505-1;
                                                                  }else if($listvar['notetag'][$key] == '500'){
                                                                      $count500++;
                                                                      $indexruas=$count500-1;
                                                                  }else if($listvar['notetag'][$key] == '542'){
                                                                      $count542++;
                                                                      $indexruas=$count542-1;
                                                                  }
                                                            ?>
                                                            <div id="DivNote<?=$key?>">
                                                              <input type="hidden" id="Ruasid_<?=$listvar['notetag'][$key]?>_<?=$indexruas?>" name="Ruasid[<?=$listvar['notetag'][$key]?>][<?=$indexruas?>]" value="<?=$taglist['ruasid'][$listvar['notetag'][$key]][$indexruas]?>" size="3" />
                                                              <div style="margin-top:5px" class="input-group">
                                                                <textarea name="CollectionBiblio[Note][<?=$key?>]" rows="2" cols="20" id="collectionbiblio-note-<?=$key?>" style="resize: vertical;height:34px;width:100%;" placeholder="Masukan Catatan..." class="form-control"><?=$value?></textarea>
                                                                <span class="input-group-btn" style="vertical-align: bottom">
                                                                <?php 
                                                                if($key == 0)
                                                                {
                                                                ?>
                                                                  <button id="btnNote" class="btn btn-success pull-right" type="button" onclick="AddNote();"><i class="glyphicon glyphicon-plus"></i></button>
                                                                <?php
                                                                }else{
                                                                ?>
                                                                  <button class="btn btn-danger btn-flat" type="button" onclick="RemoveNote(<?=$key?>,'<?=$listvar['notetag'][$key]?>','<?=$indexruas?>')"><i class="glyphicon glyphicon-trash"></i></button>
                                                                <?php
                                                                }
                                                                ?>
                                                                </span>
                                                              </div>
                                                              <div class="btm-add-on"  style="text-align:left">
                                                                   <?php 
                                                                   echo Html::activeRadioList($modelbib, 'NoteTag['.$key.']',$list); 
                                                                   ?>
                                                                </div>
                                                            </div>
                                                            <?php
                                                                }
                                                              }else{
                                                            ?>

                                                            <div id="DivNote0">
                                                              <input type="hidden" id="Ruasid_520_0"  name="Ruasid[520][0]" 
                                                              value="<?=$taglist['ruasid'][520][0]?>" size="3" />
                                                              <div class="input-group">
                                                                <?php echo Html::activeTextarea($modelbib,'Note[]',['class'=>'form-control','rows'=>'2','cols'=>'20','style'=>'resize: vertical;height:34px;width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'bib_Catatan').'...']); ?>
                                                                <span class="input-group-btn" style="vertical-align: bottom">
                                                                  <button id="btnNote" class="btn btn-success pull-right" type="button" onclick="AddNote();"><i class="glyphicon glyphicon-plus"></i></button>
                                                                </span>
                                                              </div>
                                                               <div class="btm-add-on"  style="text-align:left">
                                                                   <?php
                                                                   echo Html::activeRadioList($modelbib, 'NoteTag[0]',$list); 
                                                                   ?>
                                                                </div>
                                                            </div>

                                                            <?php
                                                              }

                                                            ?>
                                                          </div>
                                                          <div id="error-520" class="help-block"></div>
                                                    </div>
                                                    </span>
                                                  </div>
                                                </div>
                                          </div>

                                          

                                        </div>
                                    </div>


                                    <?php
                                    if($for == 'cat')
                                    {
                                    ?>
                                    <div class="panel panel-default ">
                                      <div class="panel-body">
                                      <input type="hidden" id="Ruasid_008" name="Ruasid[008]" value="<?=$taglist['ruasid']['008']?>" size="3" />
                                      <?php 
                                      if($rulesform['008_Bahasa'] == 1){
                                      ?>
                                          <div class="form-group kv-fieldset-inline">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'Bahasa'); ?></label>
                                                        <div class="col-sm-6">
                                                          <?php 
                                                          echo Select2::widget([
                                                          'model' => $modelbib,
                                                          'attribute' => 'Bahasa',
                                                          'data'=>ArrayHelper::map(Refferenceitems::findByRefferenceId(5),'Code',function($model) {return $model['Name'];}),
                                                          ]);?>
                                                        </div>
                                                      </div>
                                                </div>
                                          </div>
                                      <?php 
                                      }
                                      ?>
                                          
                                      <?php 
                                      if($rulesform['008_KaryaTulis'] == 1){
                                      ?>
                                          <div class="form-group kv-fieldset-inline">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'BentukKaryaTulis'); ?></label>
                                                        <div class="col-sm-6">
                                                          <?php 
                                                          echo Select2::widget([
                                                          'model' => $modelbib,
                                                          'attribute' => 'BentukKaryaTulis',
                                                          'data'=>ArrayHelper::map(Refferenceitems::findByRefferenceId(17),'Code',function($model) {return $model['Name'];}),
                                                          ]);?>
                                                        </div>
                                                      </div>
                                                </div>
                                          </div>
                                        <?php 
                                        }
                                        ?>

                                        <?php 
                                        if($rulesform['008_KelompokSasaran'] == 1){
                                        ?>
                                          <div class="form-group kv-fieldset-inline">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-2" for="email"><?php echo Html::activeLabel($modelbib,'KelompokSasaran'); ?></label>
                                                        <div class="col-sm-6">
                                                          <?php 
                                                          echo Select2::widget([
                                                          'model' => $modelbib,
                                                          'attribute' => 'KelompokSasaran',
                                                          'data'=>ArrayHelper::map(Refferenceitems::findByRefferenceId(2),'Code',function($model) {return $model['Name'];}),
                                                          ]);?>
                                                        </div>
                                                      </div>
                                                </div>
                                          </div>
                                        <?php 
                                        }
                                        ?>
                                      </div>
                                    </div>

                                    <?php
                                    }
                                    ?>

                                    <!-- Jenis Bahan Sumber Elektronik -->
                                    <?php if($worksheetid == '2'){ ?>
                                        <div class="panel panel-default ">
                                            <div class="panel-heading">
                                                Catatan Rincian Sistem
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group kv-fieldset-inline">
                                                  <div class="col-sm-12">
                                                      <div class="form-group">
                                                          <label class="control-label col-sm-2" for="email">Catatan Rincian Sistem<?php //echo Html::activeLabel($modelbib,'CatatanRincianSistem'); ?></label>
                                                          <div class="col-sm-6">
                                                            <?php echo Html::activeTextInput($modelbib,'CatatanRincianSistem',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Catatan Rincian Sistem').'...']); ?>
                                                            <div class="help-block"></div>
                                                          </div>
                                                        </div>
                                                    </div>
                                                  </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!-- Jenis Bahan Sumber Elektronik -->

                                    <!-- Jenis Bahan Kartografi -->
                                    <?php if($cekKarto['ISKARTOGRAFI'] == 1){ ?>
                                        <div class="panel panel-default ">
                                            <div class="panel-heading">
                                                Data Matematis
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group kv-fieldset-inline">
                                                  <div class="col-sm-12">
                                                      <div class="form-group">
                                                          <label class="control-label col-sm-2" for="email">Data Matematis</label>
                                                          <div class="col-sm-6">
                                                            <?php echo Html::activeTextInput($modelbib,'DataMatematis',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' Data Matematis...']); ?>
                                                            <div class="help-block"></div>
                                                          </div>
                                                        </div>
                                                    </div>
                                                  </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!-- Jenis Bahan Kartografi -->

                                    <!-- Lokasi Daring -->
                                    <div class="panel panel-default ">
                                        <div class="panel-heading">
                                            <?= yii::t('app','Lokasi Koleksi Daring')?>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group kv-fieldset-inline">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                         <span class="<?=$listvar['input_required']['856']['status']?>"  id="status-856">
                                                            <input type="hidden" id="message-856" value="<?=$listvar['input_required']['856']['message']?>" />
                                                            <label class="control-label col-sm-2" for="email">
                                                              <?= yii::t('app','Lokasi Koleksi Daring')?>
                                                            </label>
                                                            <div class="col-sm-9">
                                                                <input id="LokasiAddCount" type="hidden" value="<?=count($listvar['lokasidaring'])?>">
                                                                <div id="LokasiAddList">
                                                                  <?php 
                                                                    if(count($listvar['lokasidaring']) > 0){
                                                                      $count856=0;
                                                                      foreach ($listvar['lokasidaring'] as $key => $value) {
                                                                        $indexruas=$count856-1;
                                                                  ?>
                                                                        <div id="DivLokasiAdded<?=$key?>">
                                                                            <input type="hidden" id="Ruasid_<?=$taglokasidaring?>_<?=$indexruas?>" name="Ruasid[<?=$taglokasidaring?>][<?=$indexruas?>]" value="<?=$taglist['ruasid'][$taglokasidaring][$indexruas]?>"/>
                                                                            <div class="input-group" style="margin-top:5px">
                                                                                <input value="<?=$value?>" type="text" id="collectionbiblio-lokasidaring-<?=$key?>" class="form-control" name="CollectionBiblio[LokasiDaringAdded][<?=$key?>]" style="width:100%" placeholder="Masukan Lokasi Koleksi Daring...">
                                                                                <span class="input-group-btn">
                                                                                <?php 
                                                                                if($key == 0)
                                                                                {
                                                                                ?>
                                                                                  <button id="btnLokasiAdded" class="btn btn-success pull-right" type="button" onclick="AddLokasiDaring();"><i class="glyphicon glyphicon-plus"></i></button>
                                                                                <?php
                                                                                }else{
                                                                                ?>
                                                                                  <button class="btn btn-danger btn-flat" type="button" onclick="RemoveLokasiAdded(<?=$key?>,'<?=$taglokasidaring?>','<?=$indexruas?>')"><i class="glyphicon glyphicon-trash"></i></button>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                  <?php 
                                                                      }
                                                                    }else{
                                                                  ?>
                                                                      <div id="DivLokasiAdded0">
                                                                          <input type="hidden" id="Ruasid_856_0" name="Ruasid[856][0]" value="<?=$taglist['ruasid'][856][0]?>" size="3"/>
                                                                          <div class="input-group">
                                                                            <?php echo Html::activeTextInput($modelbib,'LokasiDaringAdded[0]',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Lokasi Koleksi Daring').'...']); ?>
                                                                            <span class="input-group-btn">
                                                                              <button id="btnLokasiAdded" class="btn btn-success pull-right" type="button" onclick="AddLokasiDaring();"><i class="glyphicon glyphicon-plus"></i></button>
                                                                            </span>
                                                                          </div>
                                                                      </div>
                                                                  <?php } ?>
                                                                </div>
                                                            </div>
                                                         </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Lokasi Daring -->
                              </div>
                              <?php //ActiveForm::end(); ?>

                            </div>
                        </div>
                      </div>
  </div>
</div>
<?php
//Khusus jenis bahan terbitan berkala (serial)
if($isSerial ==1 && $for=='coll')
{
?>
<div class="box-group" id="cardexbox">
                    <div class="panel panel-default">
                      <div class="box-header with-border">
                            <div class="col-xs-12 col-sm-12" >
                                  <h4 class="box-title">
                                      <a data-toggle="collapse" data-parent="#cardexbox" href="#collapseTwo2">
                                        Cardex (Edisi Serial)
                                      </a>
                                  </h4>
                            </div>
                      </div>
                      <div id="collapseTwo2" class="panel-collapse collapse in">
                        <div class="box-body">
                                 <div class="form-group kv-fieldset-inline">
                                    <div class="col-sm-8">
                                         <div class="form-group">
                                            <label class="control-label col-sm-4" for="email"><?php echo Html::activeLabel($model,'EDISISERIAL'); ?></label>
                                            <div class="col-sm-8">
                                              <?php echo Html::activeTextInput($model,'EDISISERIAL',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'coll_Edisiserial').'...']); ?>
                                            </div>
                                          </div>
                                    </div>
                                    <div class="col-sm-4">
                                        &nbsp;
                                    </div>
                                  </div>

                                  <div class="form-group kv-fieldset-inline">
                                    <div class="col-sm-8">
                                         <div class="form-group">
                                            <label class="control-label col-sm-4" for="email"><?php echo Html::activeLabel($model,'TANGGAL_TERBIT_EDISI_SERIAL'); ?></label>
                                            <div class="col-sm-8">

                                              <?php 
                                              echo MaskedDatePicker::widget(
                                              [
                                                'model' => $model, 
                                                'attribute' => 'TANGGAL_TERBIT_EDISI_SERIAL',
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
                                          </div>
                                    </div>
                                    <div class="col-sm-4">
                                        &nbsp;
                                    </div>
                                  </div>

                                  <div class="form-group kv-fieldset-inline">
                                    <div class="col-sm-8">
                                         <div class="form-group">
                                            <label class="control-label col-sm-4" for="email"><?php echo Html::activeLabel($model,'BAHAN_SERTAAN'); ?></label>
                                            <div class="col-sm-8">
                                              <?php echo Html::activeTextInput($model,'BAHAN_SERTAAN',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'coll_Bahan  Sertaan').'...']); ?>
                                            </div>
                                          </div>
                                    </div>
                                    <div class="col-sm-4">
                                        &nbsp;
                                    </div>
                                  </div>

                                  <div class="form-group kv-fieldset-inline">
                                    <div class="col-sm-8">
                                         <div class="form-group">
                                            <label class="control-label col-sm-4" for="email"><?php echo Html::activeLabel($model,'KETERANGAN_LAIN'); ?></label>
                                            <div class="col-sm-8">
                                              <?php echo Html::activeTextInput($model,'KETERANGAN_LAIN',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'coll_Keterangan  Lain').'...']); ?>
                                            </div>
                                          </div>
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
<input type="hidden" id="hdnAjaxUrlTajukPengarang" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/tajuk-pengarang"])?>">
<input type="hidden" id="hdnAjaxUrlTajukSubyek" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/tajuk-subyek"])?>">
<input type="hidden" id="hdnAjaxUrlDDC" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/tajuk-ddc"])?>">
<input type="hidden" id="hdnAjaxUrlRuas" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/set-ruas"])?>">
<?php 
/*$taglistnya = \Yii::$app->session['taglist'];  
echo '<pre>'; print_r($taglistnya['inputvalue']); echo '</pre>'; */

$this->registerJsFile( 
    Yii::$app->request->baseUrl.'/assets_b/js/catalogs_simple.js'
);
$this->registerJsFile( 
    Yii::$app->request->baseUrl.'/assets_b/js/catalogs_advance.js'
);

?>

<script type="text/javascript">
  if($("#collectionbiblio-authortag").val()=="100")
  {
    var AuthorTag = $("#AuthorTag_value").val();                                                               
    $("#opx0_0").show();
    $("#opx1_0").show();
    $("#opx2_0").show();
    $("#opx3_0").hide();
    $("#opx4_0").hide();                                                                        
    $("#radio_"+AuthorTag).prop("checked",true);                                                                     
  }else if($("#collectionbiblio-authortag").val()=="110"){
    $("#opx0_0").hide();
    $("#opx1_0").hide();
    $("#opx2_0").hide();
    $("#opx3_0").show();
    $("#opx4_0").hide();                                                                        
    $("#radio_caeLB9nkVyyjw").prop("checked",true);                                                                     
  }else{
    $("#opx0_0").hide();
    $("#opx1_0").hide();
    $("#opx2_0").hide();
    $("#opx3_0").hide();
    $("#opx4_0").show();   
    $("#radio_caVtfkuPOLAyE").prop("checked",true);                                                                     
  }
  function AddLokasiDaring() {
    var html = [];
    var sort = $("#LokasiAddCount").val();
    
    if(sort != '')
    {
      sort = parseInt(sort)+1;
    }
    
    // alert(sort)
    $("#LokasiAddCount").val(sort);
    html.push("<div id='DivLokasiAdded"+sort+"'>");
      
      html.push("<div style='margin-top:5px' class='input-group'>")
        html.push("<input type='text' id='collectionbiblio-lokasiadded-"+sort+"' class='form-control' name='CollectionBiblio[LokasiDaringAdded]["+sort+"]' style='width:100%' placeholder='Masukan Tambahan...'>");
        html.push("<span class='input-group-btn'>");
        html.push("<button class='btn btn-danger' type='button' onclick='RemoveLokasiAdded("+sort+")'><i class='glyphicon glyphicon-trash'></i></button>");
        html.push("</span>");
      html.push("</div>");
      
      
    html.push("</div>");
    $("#LokasiAddList").append(html.join(''));
  }

  function RemoveLokasiAdded(id,tag,index) {
    $.ajax({
        type     :"POST",
        cache    : false,
        url  : $("#hdnAjaxUrlRemoveTag").val(),
        data: {
                  tag : tag,
                  index : index
              },
        success  : function(response) {
           $("#DivLokasiAdded"+id).remove();
           var sort = $("#LokasiAddCount").val();
           if(sort != '')
           {
             sort = parseInt(sort)-1;
           }
           $("#LokasiAddCount").val(sort);
        }
    });
  }

  function AddJudulSebelumDaring() {
    var html = [];
    var sort = $("#JudulSebelumAddCount").val();
    
    if(sort != '')
    {
      sort = parseInt(sort)+1;
    }
    
    // alert(sort)
    $("#JudulSebelumAddCount").val(sort);
    html.push("<div id='DivJudulSebelumAdded"+sort+"'>");
      
      html.push("<div style='margin-top:5px' class='input-group'>")
        html.push("<input type='text' id='collectionbiblio-judulsebelum-"+sort+"' class='form-control' name='CollectionBiblio[JudulSebelumAdded]["+sort+"]' style='width:100%' placeholder='Masukan Tambahan...'>");
        html.push("<span class='input-group-btn'>");
        html.push("<button class='btn btn-danger' type='button' onclick='RemoveJudulSebelumAdded("+sort+")'><i class='glyphicon glyphicon-trash'></i></button>");
        html.push("</span>");
      html.push("</div>");
      
      
    html.push("</div>");
    $("#JudulSebelumAddList").append(html.join(''));
  }

  function RemoveJudulSebelumAdded(id,tag,index) {
    $.ajax({
        type     :"POST",
        cache    : false,
        url  : $("#hdnAjaxUrlRemoveTag").val(),
        data: {
                  tag : tag,
                  index : index
              },
        success  : function(response) {
           $("#DivJudulSebelumAdded"+id).remove();
           var sort = $("#JudulSebelumAddCount").val();
           if(sort != '')
           {
             sort = parseInt(sort)-1;
           }
           $("#JudulSebelumAddCount").val(sort);
        }
    });
  }

  function AddFrekuensiSebelum() {
    var html = [];
    var sort = $("#FrekSebelumAddCount").val();
    
    if(sort != '')
    {
      sort = parseInt(sort)+1;
    }
    
    // alert(sort)
    $("#FrekSebelumAddCount").val(sort);
    html.push("<div id='DivFrekSebelumAdded"+sort+"'>");
      
      html.push("<div style='margin-top:5px' class='input-group'>")
        html.push("<input type='text' id='collectionbiblio-frekuensisebelum-"+sort+"' class='form-control' name='CollectionBiblio[FrekuensiSebelumAdded]["+sort+"]' style='width:100%' placeholder='Masukan Tambahan...'>");
        html.push("<span class='input-group-btn'>");
        html.push("<button class='btn btn-danger' type='button' onclick='RemoveFrekuensiSebelumAdded("+sort+")'><i class='glyphicon glyphicon-trash'></i></button>");
        html.push("</span>");
      html.push("</div>");
      
      
    html.push("</div>");
    $("#FrekSebelumAddList").append(html.join(''));
  }

  function RemoveFrekuensiSebelumAdded(id,tag,index) {
    $.ajax({
        type     :"POST",
        cache    : false,
        url  : $("#hdnAjaxUrlRemoveTag").val(),
        data: {
                  tag : tag,
                  index : index
              },
        success  : function(response) {
           $("#DivFrekSebelumAdded"+id).remove();
           var sort = $("#FrekSebelumAddCount").val();
           if(sort != '')
           {
             sort = parseInt(sort)-1;
           }
           $("#FrekSebelumAddCount").val(sort);
        }
    });
  }
</script>

