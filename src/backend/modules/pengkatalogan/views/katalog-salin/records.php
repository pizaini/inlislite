

<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use kartik\grid\GridView;



/**
 * @var yii\web\View $this
 * @var common\models\Collections $model
 * @var yii\widgets\ActiveForm $form
 */

?>
<style type="text/css">
    /* Methods */
.method .header, .method .cell {
  padding: 6px 6px 6px 10px; }
.method .list-header .header {
  font-weight: bold;
  text-transform: uppercase;
  font-size: 1.0em;
  color: #333;
  background-color: #eee; 
  height: 30px;}
.method [class^="row"],
.method [class*=" row"] {
  border-bottom: 1px solid #ddd; }
  .method [class^="row"]:hover,
  .method [class*=" row"]:hover {
    background-color: #f7f7f7; }
.method .cell {
  font-size: 1.0em; }
  .method .cell .mobile-isrequired {
    display: none;
    font-weight: normal;
    text-transform: uppercase;
    color: #aaa;
    font-size: 0.8em; }
  .method .cell .propertyname {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis; }
  .method .cell .type {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis; }
  .method .cell code {
    color: #428bca; }
  .method .cell a, .method .cell a:hover {
    text-decoration: none; }
  .method .cell code.custom {
    color: #8a6d3b;
    text-decoration: none; }
  .method .cell .text-muted {
    color: #ddd; }
@media (max-width: 991px) {
  .method [class^="row"],
  .method [class*=" row"] {
    padding-top: 10px;
    padding-bottom: 10px; }
  .method .cell {
    padding: 0 10px; }
    .method .cell .propertyname {
      font-weight: bold;
      font-size: 1.2em; }
      .method .cell .propertyname .lookuplink {
        font-weight: normal;
        font-size: 1.5em;
        position: absolute;
        top: 0;
        right: 10px; }
    .method .cell .type {
      padding-left: 10px;
      font-size: 1.1em; }
    .method .cell .isrequired {
      padding-left: 10px;
      display: none; }
    .method .cell .description {
      padding-left: 10px; }
    .method .cell .mobile-isrequired {
      display: inline; } }


/* Row Utilities */
[class^='row'].margin-0,
[class*=' row'].margin-0,
[class^='form-group'].margin-0,
[class*=' form-group'].margin-0 {
  margin-left: -0px;
  margin-right: -0px; }
  [class^='row'].margin-0 > [class^='col-'],
  [class^='row'].margin-0 > [class*=' col-'],
  [class*=' row'].margin-0 > [class^='col-'],
  [class*=' row'].margin-0 > [class*=' col-'],
  [class^='form-group'].margin-0 > [class^='col-'],
  [class^='form-group'].margin-0 > [class*=' col-'],
  [class*=' form-group'].margin-0 > [class^='col-'],
  [class*=' form-group'].margin-0 > [class*=' col-'] {
    padding-right: 0px;
    padding-left: 0px; }
  [class^='row'].margin-0 [class^='row'],
  [class^='row'].margin-0 [class*=' row'],
  [class^='row'].margin-0 [class^='form-group'],
  [class^='row'].margin-0 [class*=' form-group'],
  [class*=' row'].margin-0 [class^='row'],
  [class*=' row'].margin-0 [class*=' row'],
  [class*=' row'].margin-0 [class^='form-group'],
  [class*=' row'].margin-0 [class*=' form-group'],
  [class^='form-group'].margin-0 [class^='row'],
  [class^='form-group'].margin-0 [class*=' row'],
  [class^='form-group'].margin-0 [class^='form-group'],
  [class^='form-group'].margin-0 [class*=' form-group'],
  [class*=' form-group'].margin-0 [class^='row'],
  [class*=' form-group'].margin-0 [class*=' row'],
  [class*=' form-group'].margin-0 [class^='form-group'],
  [class*=' form-group'].margin-0 [class*=' form-group'] {
    margin-left: 0;
    margin-right: 0; }
    
    .panelrow {
        display: none;
        padding: 50px;
    }

    .checkedrow {
        background-color: #d9edf7;
    }
</style>
<button  id="btnSave" class="btn btn-primary btn-sm" style="margin-bottom: 10px" onclick="saveRecords();" ><i class="glyphicon glyphicon-check"></i> Simpan</button> <i style="float:right"><b>Silahkan pilih data, lalu klik simpan</b></i>.
<div id="listRecords" class="method">
        <div class="row margin-0 list-header hidden-sm hidden-xs">
            <div class="col-md-1"><div class="header">
            <input type="checkbox" name="ckall" id="ckall" style="margin-right: 30px"><span style="vertical-align: top;">No</span>
            </div></div>
            <div class="col-md-3"><div class="header">
            <input type="checkbox" name="ckall2" id="ckall2" style="display:none">Judul</div></div>
            <div class="col-md-2"><div class="header">Pengarang</div></div>
            <div class="col-md-2"><div class="header">Tempat terbit</div></div>
            <div class="col-md-1"><div class="header">Penerbit</div></div>
            <div class="col-md-1"><div class="header">Th terbit</div></div>
            <div class="col-md-2"><div class="header">Subjek</div></div>
        </div>
<?php
$no=0;
function encode_items(&$item, $key)
{
    $item = utf8_encode($item);
}

foreach ($dataProvider->allModels as $key => $data) {
$no++;

//echo '<pre>'; print_r($data['Taglist']); echo '</pre>';die;
array_walk_recursive($data['Taglist'], 'encode_items');
$taglist= json_encode($data['Taglist'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
?>


        <div id="row<?=$key?>" class="row margin-0" >
            <div class="col-md-1">
                <div class="cell">
                    <div class="propertyname">
                        <input type="checkbox" name="ck[<?=$key?>]" id="ck-<?=$key?>" style="margin-right: 30px" value='<?=$taglist?>' >
                        <?=$no?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="cell">
                    <div class="propertyname">
                        <a href="javascript:void(0)" onclick="$('#panel<?=$key?>').slideToggle('fast');"> <?=$data['Title']?> </a></span>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="cell">
                    <div class="propertyname">
                        <?=$data['Author']?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="cell">
                    <div class="propertyname">
                        <?=$data['PublishLocation']?>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="cell">
                    <div class="propertyname">
                        <?=$data['Publisher']?>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="cell">
                    <div class="propertyname">
                        <?=$data['PublishYear']?>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="cell">
                    <div class="propertyname">
                        <?=$data['Subject']?>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="cell">
                    <div class="propertyname" style="text-align: right">
                        <?php 

                        if($data['Mode']=='1')
                        {
                          echo'<span class="label label-success">RDA&nbsp;&nbsp;&nbsp;</span>';
                        }else{
                          echo'<span class="label label-primary">AACR</span>';
                        }

                        ?>
                    </div>
                </div>
            </div>
            
        </div>
        <div id="panel<?=$key?>" class="panelrow">
            <div class="nav-tabs-custom" style="margin: -40px;">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#lengkap<?=$no?>" data-toggle="tab"><?= Yii::t('app','Bentuk Lengkap')?></a></li>
                  <li><a href="#marc<?=$no?>" data-toggle="tab"><?= Yii::t('app','Bentuk MARC')?></a></li>
                </ul>
                <div class="tab-content">
                  
                    
                  <!-- LENGKAP -->
                  <div class="tab-pane fade active in" id="lengkap<?=$no?>">
                    <table class="table table-striped">
                        <tbody>
                        <?php 
                        foreach ($data['Detail'] as $key2 => $value) {
                        ?>
                        <tr>
                          <td style="width: 200px"><?=$value['Label']?></td>
                          <td style="width: 10px">:</td>
                          <td><?=$value['Value']?></td>
                        </tr>
                        <?php
                        }
                        ?>

                        
                      </tbody>
                    </table>

                  </div>
                  <!-- MARC  -->
                  <div class="tab-pane fade" id="marc<?=$no?>">
                     <table class="table table-striped">
                        <tbody>
                        <tr style="background-color: #eeeeee">
                          <td style="width: 70px">Tag</td>
                          <td style="width: 70px">Indikator 1</td>
                          <td style="width: 70px">Indikator 2</td>
                          <td>Isi</td>
                        </tr>
                        <?php 
                        foreach ($data['Taglist'] as $index => $datatags) {
                        ?>
                          <tr>
                            <td><?=$datatags['tag']?></td>
                            <td><?=$datatags['ind1']?></td>
                            <td><?=$datatags['ind2']?></td>
                            <td><?=$datatags['value']?></td>
                          </tr>
                        <?php
                        }
                        ?>
                        
                      </tbody>
                    </table>

                  </div>

                </div><!-- /.tab-content -->
            </div><!-- /.nav-tabs-custom -->
       </div>
        
<?php
}
?>

    </div>

<input type="hidden" id="hdnAjaxUrlSaveRecords" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog-salin/save-records"])?>">
<script type="text/javascript">


    $('#ckall').click(function() {
      var checkedStatus = this.checked;
      $('#listRecords').find(':checkbox').each(function() {
        $(this).prop('checked', checkedStatus);
      });
    });

    function saveRecords() {
      $.ajax({
              type     :"POST",
              cache    : false,
              url  : $("#hdnAjaxUrlSaveRecords").val()+"?wksid="+$("#cbWorksheets").val(),
              data: $("#listRecords input[type='checkbox']:checked").serialize(),
              success  : function(response) {
                $('#result').html(response);
                endLoading();
                alertSwal('Data terpilih berhasil disimpan','success','2000');
              }
          });
    }


</script>
