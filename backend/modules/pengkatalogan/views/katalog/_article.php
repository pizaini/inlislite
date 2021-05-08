

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
use common\models\MasterEdisiSerial;
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
                      'data'=>ArrayHelper::map(MasterEdisiSerial::find()->where(['Catalog_id' => $catalogid])->all(),'id','no_edisi_serial'),
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
                <div class="col-sm-12">
                 <div class="form-group">
                  <?php if(count($articleRepeat['Kreator']) != '0'){
                            echo "<label class='control-label col-sm-3' style='margin-left: -24px;'>Kreator</label>";
                            echo "<div class='col-sm-9' style='margin-top: -13px;'>";
                    }else{
                            echo "<div class='col-sm-9'>";
                    }  ?>
                    <input id="CreatorAddCount" type="hidden" value="<?=count($articleRepeat['Kreator'])?>">
                     <div id="CreatorAddList">
                        <?php 
                        // echo '<pre>';print_r(count($articleRepeat['Kreator']));echo '</pre> <br />';
                            if(count($articleRepeat['Kreator']) > 0)
                            {
                              $countKreator=0;
                              foreach ($articleRepeat['Kreator'] as $key => $value) 
                                {
                                $indexruas=$countKreator-1;
                        ?>
                            <div id="DivCreatorNumber<?=$key?>" style="margin-top: 11px;">
                                <div class="col-sm-7" style="margin-right: -15px; margin-bottom: 5px;">
                                    <div class='form-group field-SerialArticlesRepeatable-Creator-<?=$key?>' >
                                        

                                        <input value="<?=$value?>" type="text" id="SerialArticlesRepeatable-Creator-<?=$key?>" class="form-control" name="SerialArticlesRepeatable[value][Creator][<?=$key?>]" style="width:100%" placeholder="Masukan Creator...">

                                    </div>
                                </div>
                                    <span class="input-group-btn">
                                    <?php 
                                    if($key == 0)
                                    {
                                    ?>
                                      <button class="btn btn-success" type="button" onclick="AddCreatorNumber();"><i class="glyphicon glyphicon-plus"></i></button>
                                    <?php
                                    }else{
                                    ?>
                                      <button class="btn btn-danger btn-flat" type="button" onclick="RemoveCreatorAdded(<?=$key?>)"><i class="glyphicon glyphicon-trash"></i></button>
                                    <?php
                                    }
                                    ?>
                                    </span>
                            </div>
                          <?php 
                                }
                            }else{
                          ?>
                         <div id="DivCreatorNumber0" style="margin-top:3px;">
                            <div class="col-sm-11" style="margin-left: -12px; margin-right: -15px;">
                                <?= $form->field($modelReaped,'value[Creator][0]')->textInput(['inline'=>true,'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Creator').'...'])->label('Creator')?>          
                            </div>
                            <span class="input-group-btn">
                                  <button id="btnCreatorNumber" class="btn btn-success" type="button" onclick="AddCreatorNumber();"><i class="glyphicon glyphicon-plus"></i></button>
                            </span>
                         </div>
                        <?php } ?>
                     </div>
                    </div>
                 </div>
                </div>
                </div>

                <div class="form-group kv-fieldset-inline">
                <div class="col-sm-12">
                 <div class="form-group">
                  <?php if(count($articleRepeat['Kontributor']) != '0'){
                            echo "<label class='control-label col-sm-3' style='margin-left: -24px;'>Kontributor</label>";
                            echo "<div class='col-sm-9' style='margin-top: -13px;'>";
                    }else{
                            echo "<div class='col-sm-9'>";
                    }  ?>
                    <input id="ContributorAddCount" type="hidden" value="<?=count($articleRepeat['Kontributor'])?>">
                     <div id="ContributorAddList">
                        <?php 
                        // echo '<pre>';print_r(count($articleRepeat['Kreator']));echo '</pre> <br />';
                        // echo '<pre>';print_r(count($articleRepeat['Kontributor']));echo '</pre> <br />';
                        // echo '<pre>';print_r(count($articleRepeat['Subjek']));echo '</pre> <br />';
                        // echo '<pre>';print_r($modelArticleRepeat);echo '</pre>';

                            if(count($articleRepeat['Kontributor']) > 0)
                            {
                              $countKontributor=0;
                              foreach ($articleRepeat['Kontributor'] as $key => $value) 
                                {
                                $indexruas=$countKontributor-1;
                        ?>
                            <div id="DivContributorNumber<?=$key?>" style="margin-top: 11px;">
                                <div class="col-sm-7" style="margin-right: -15px; margin-bottom: 5px;">
                                    <div class='form-group field-SerialArticlesRepeatable-Contributor-<?=$key?>' >
                                        

                                        <input value="<?=$value?>" type="text" id="SerialArticlesRepeatable-Contributor-<?=$key?>" class="form-control" name="SerialArticlesRepeatable[value][Contributor][<?=$key?>]" style="width:100%" placeholder="Masukan Contributor...">

                                    </div>
                                </div>
                                    <span class="input-group-btn">
                                    <?php 
                                    if($key == 0)
                                    {
                                    ?>
                                      <button class="btn btn-success" type="button" onclick="AddContributorNumber();"><i class="glyphicon glyphicon-plus"></i></button>
                                    <?php
                                    }else{
                                    ?>
                                      <button class="btn btn-danger btn-flat" type="button" onclick="RemoveContributorAdded(<?=$key?>)"><i class="glyphicon glyphicon-trash"></i></button>
                                    <?php
                                    }
                                    ?>
                                    </span>
                            </div>
                          <?php 
                                }
                            }else{
                          ?>
                         <div id="DivContributorNumber0" style="margin-top:3px;">
                            <div class="col-sm-11" style="margin-left: -12px; margin-right: -15px;">
                                <?= $form->field($modelReaped,'value[Contributor][0]')->textInput(['inline'=>true,'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Contributor').'...'])->label('Contributor')?>          
                            </div>
                            </div>
                            <span class="input-group-btn">
                                  <button id="btnContributorNumber" class="btn btn-success" type="button" onclick="AddContributorNumber();"><i class="glyphicon glyphicon-plus"></i></button>
                            </span>
                         </div>
                        <?php } ?>
                     </div>
                    </div>
                 </div>
                </div>
                </div>


                <div class="form-group kv-fieldset-inline">
                    <div class="col-sm-8" style="margin-top:3px;">
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
                <div class="col-sm-12">
                 <div class="form-group">
                    <?php if(count($articleRepeat['Subjek']) != '0'){
                            echo "<label class='control-label col-sm-3' style='margin-left: -24px;'>Subject</label>";
                            echo "<div class='col-sm-9' style='margin-top: -13px;'>";
                    }else{
                            echo "<div class='col-sm-9'>";
                    }  ?>
                    <input id="SubjectAddCount" type="hidden" value="<?=count($articleRepeat['Subjek'])?>">
                     <div id="SubjectAddList">
                        <?php 
                        // echo '<pre>';print_r(count($articleRepeat['Kreator']));echo '</pre> <br />';
                        // echo '<pre>';print_r(count($articleRepeat['Kontributor']));echo '</pre> <br />';
                        // echo '<pre>';print_r($articleRepeat);echo '</pre> <br />';
                        // echo '<pre>';print_r($modelArticleRepeat);echo '</pre>';

                            if(count($articleRepeat['Subjek']) > 0)
                            {
                              $countSubjek=0;
                              foreach ($articleRepeat['Subjek'] as $key => $value) 
                                {
                                $indexruas=$countSubjek-1;
                        ?>
                            <div id="DivSubjectNumber<?=$key?>" style="margin-top: 11px;">
                                <div class="col-sm-7" style="margin-right: -15px; margin-bottom: 5px;">
                                    <div class='form-group field-SerialArticlesRepeatable-Subject-<?=$key?>' >
                                        

                                        <input value="<?=$value?>" type="text" id="SerialArticlesRepeatable-Subject-<?=$key?>" class="form-control" name="SerialArticlesRepeatable[value][Subject][<?=$key?>]" style="width:100%" placeholder="Masukan Subjek...">

                                    </div>
                                </div>
                                    <span class="input-group-btn">
                                    <?php 
                                    if($key == 0)
                                    {
                                    ?>
                                      <button class="btn btn-success" type="button" onclick="AddSubjectNumber();"><i class="glyphicon glyphicon-plus"></i></button>
                                    <?php
                                    }else{
                                    ?>
                                      <button class="btn btn-danger btn-flat" type="button" onclick="RemoveSubjectAdded(<?=$key?>)"><i class="glyphicon glyphicon-trash"></i></button>
                                    <?php
                                    }
                                    ?>
                                    </span>
                            </div>
                          <?php 
                                }
                            }else{
                          ?>
                         <div id="DivSubjectNumber0">
                            <div class="col-sm-11" style="margin-left: -12px; margin-right: -15px;">
                                <?= $form->field($modelReaped,'value[Subject][0]')->textInput(['inline'=>true,'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Subject').'...'])->label('Subject')?>
                            </div>
                            <span class="input-group-btn">
                                  <button id="btnSubjectNumber" class="btn btn-success" type="button" onclick="AddSubjectNumber();"><i class="glyphicon glyphicon-plus"></i></button>
                            </span>
                         </div>
                        <?php } ?>
                     </div>
                    </div>
                 </div>
                </div>
                </div>

                <!-- <div class="form-group kv-fieldset-inline">
                    <div class="col-sm-8">
                        </*?= $form->field($model,'Subject')->textInput(['inline'=>true,'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Subject').'...'])?>          </div>
                    <div class="col-sm-4">
                        &nbsp;
                    </div>
                </div> -->

                <div class="form-group kv-fieldset-inline">
                    <div class="col-sm-8" style="margin-top:3px;">
                        <?= $form->field($model,'Abstract')->textarea(['inline'=>true,'placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Abstract').'...'])?>          </div>
                    <div class="col-sm-4">
                        &nbsp;
                    </div>
                </div>

                <div class="form-group kv-fieldset-inline">
                    <div class="col-sm-8">
                        <?= $form->field($model,'ISOPAC')->checkbox(['label'=>yii::t('app','Tampil di Artikel'),'style'=>'font-weight:bold'],['inline'=>true])?>
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

<input type="hidden" id="hdnAjaxUrlRemoveTag" value="<?=Yii::$app->urlManager->createUrl(['pengkatalogan/katalog/remove-taglist'])?>">

<script type="text/javascript">
  function AddCreatorNumber() {
    var html = [];
    var sort = $("#CreatorAddCount").val();
    var CreatorCountStatis = "<?=count($articleRepeat['Kreator'])?>";
    var placeholder = "<?= yii::t('app','Masukan Tambahan...') ?>";
    
    if(sort != '')
    {
      sort = parseInt(sort)+1;
    }
    
    // alert(sort)
    $("#CreatorAddCount").val(sort);
    html.push("<div id='DivCreatorNumber"+sort+"' style='margin-top:11px;'>");   

        html.push((CreatorCountStatis == 0 ? "<div class='col-sm-11' style='margin-left: -15px; margin-right: -15px;'>" : "<div class='col-sm-7' style='width: 56%;'>"))
            html.push("<div class='form-group field-SerialArticlesRepeatable-creator-"+sort+"'>")
            html.push((CreatorCountStatis == 0 ? "<label class='control-label col-sm-4' for='SerialArticlesRepeatable-creator-"+sort+"'>&nbsp;</label>" : ""))
                html.push((CreatorCountStatis == 0 ? "<div class='col-sm-8'>" : ""))
                    html.push("<input type='text' id='SerialArticlesRepeatable-creator-"+sort+"' class='form-control' name='SerialArticlesRepeatable[value][Creator]["+sort+"]' style='width:100%' placeholder="+placeholder+">");
                html.push((CreatorCountStatis == 0 ? "</div>" : ""));
            html.push("</div>");
        html.push("</div>");
        html.push("<span class='input-group-btn'>");
            html.push("<button class='btn btn-danger' type='button' onclick='RemoveCreatorAdded("+sort+")'><i class='glyphicon glyphicon-trash'></i></button>");
        html.push("</span>");
        
    html.push("</div>");
    $("#CreatorAddList").append(html.join(''));   
     
  }

  function RemoveCreatorAdded(id) {
    $.ajax({
        type     :"POST",
        cache    : false,
        url  : $("#hdnAjaxUrlRemoveTag").val(),

        success  : function(response) {
           $("#DivCreatorNumber"+id).remove();
           
        }
    });
  }

  function AddContributorNumber() {
    var html = [];
    var sort = $("#ContributorAddCount").val();
    var ContributorCountStatis = "<?=count($articleRepeat['Kontributor'])?>";
    var placeholder = "<?= yii::t('app','Masukan Tambahan...') ?>";
    
    if(sort != '')
    {
      sort = parseInt(sort)+1;
    }
    
    // alert(sort)
    $("#ContributorAddCount").val(sort);
    html.push("<div id='DivContributorNumber"+sort+"' style='margin-top:11px;'>");   

        html.push((ContributorCountStatis == 0 ? "<div class='col-sm-11' style='margin-left: -15px; margin-right: -15px;'>" : "<div class='col-sm-7' style='width: 56%;'>"))
            html.push("<div class='form-group field-SerialArticlesRepeatable-Contributor-"+sort+"'>")
            html.push((ContributorCountStatis == 0 ? "<label class='control-label col-sm-4' for='SerialArticlesRepeatable-Contributor-"+sort+"'>&nbsp;</label>" : ""))
                html.push((ContributorCountStatis == 0 ? "<div class='col-sm-8'>" : ""))
                    html.push("<input type='text' id='SerialArticlesRepeatable-Contributor-"+sort+"' class='form-control' name='SerialArticlesRepeatable[value][Contributor]["+sort+"]' style='width:100%' placeholder="+placeholder+">");
                html.push((ContributorCountStatis == 0 ? "</div>" : ""));
            html.push("</div>");
        html.push("</div>");
        html.push("<span class='input-group-btn'>");
            html.push("<button class='btn btn-danger' type='button' onclick='RemoveContributorAdded("+sort+")'><i class='glyphicon glyphicon-trash'></i></button>");
        html.push("</span>");
        
    html.push("</div>");
    $("#ContributorAddList").append(html.join(''));   
     
  }

  function RemoveContributorAdded(id) {
    $.ajax({
        type     :"POST",
        cache    : false,
        url  : $("#hdnAjaxUrlRemoveTag").val(),

        success  : function(response) {
           $("#DivContributorNumber"+id).remove();
           
        }
    });
  }

  function AddSubjectNumber() {
    var html = [];
    var sort = $("#SubjectAddCount").val();
    var SubjectCountStatis = "<?=count($articleRepeat['Subjek'])?>";
    var placeholder = "<?= yii::t('app','Masukan Tambahan...') ?>";
    
    if(sort != '')
    {
      sort = parseInt(sort)+1;
    }
    
    
    // alert(placeholderxxx)
    $("#SubjectAddCount").val(sort);
    html.push("<div id='DivSubjectNumber"+sort+"' style='margin-top:11px;'>");   

        html.push((SubjectCountStatis == 0 ? "<div class='col-sm-11' style='margin-left: -15px; margin-right: -15px;'>" : "<div class='col-sm-7' style='width: 56%;'>"))
            html.push("<div class='form-group field-serialarticles-Subject-"+sort+"'>")
            html.push((SubjectCountStatis == 0 ? "<label class='control-label col-sm-4' for='serialarticles-Subject-"+sort+"'>&nbsp;</label>" : ""))
                html.push((SubjectCountStatis == 0 ? "<div class='col-sm-8'>" : ""))
                    html.push("<input type='text' id='serialarticles-Subject-"+sort+"' class='form-control' name='SerialArticlesRepeatable[value][Subject]["+sort+"]' style='width:100%' placeholder="+placeholder+">");
                html.push((SubjectCountStatis == 0 ? "</div>" : ""));
            html.push("</div>");
        html.push("</div>");
        html.push("<span class='input-group-btn'>");
            html.push("<button class='btn btn-danger' type='button' onclick='RemoveSubjectAdded("+sort+")'><i class='glyphicon glyphicon-trash'></i></button>");
        html.push("</span>");
        
    html.push("</div>");
    $("#SubjectAddList").append(html.join(''));   
     
  }

  function RemoveSubjectAdded(id) {
    $.ajax({
        type     :"POST",
        cache    : false,
        url  : $("#hdnAjaxUrlRemoveTag").val(),

        success  : function(response) {
           $("#DivSubjectNumber"+id).remove();
           
        }
    });
  }
  </script>
