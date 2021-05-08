<?php
/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2016
 * @version 1.0.0
 * @author Andy Kurniawan <dodot.kurniawan@gmail.com>
 */

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 * @var yii\widgets\ActiveForm $form
 */

$datacheckbox1 = ['0'=>'-Kosong-','1'=>'Manual Input','2'=>'Kode Jenis Bahan','3'=>'Kode Kategori Koleksi','4'=>'Kode Bentuk Fisik','5'=>'Kode Jenis Sumber Pengadaan','6'=>'99999','7'=>'YYYY'];
// $datacheckbox2 = ['0'=>'99999','1'=>'YYYYY'];
$datacheckbox3 = ['2'=>'Kosong','3'=>'/','4'=>'-','5'=>'.'];
if (strtolower($model->NomorInduk) != 'otomatis')
{
  $disabletemplate =true;
  $disabled =true;

}
$FormatNomorInduks=explode('|',$model->FormatNomorInduk);
$FormatNomorInduksx=explode('|',$model->FormatNomorIndukx);
$data = Yii::$app->db->createCommand('SELECT * FROM collectionsources')->queryAll(); 
$options = ArrayHelper::map($data,'Code','Code');
// print_r($datacheckbox1);echo '<br />';
// print_r($options);echo '<br />';
// print_r($FormatNomorInduks);echo '<br />';
// echo '<pre>';print_r($model);echo '</pre>';
// print_r($model->FormatNomorInduk);echo '<br />';
// print_r($model->NomorInduk);echo '<br />';
if($FormatNomorInduksx[0] == 1)
{

    $dataTemplateInput1=trim(str_replace('}','',str_replace('{','',$FormatNomorInduks[0])));
    $FormatNomorInduks[0]=1;
}else{
    $display1='display:none;';
}
if($FormatNomorInduksx[0] == 5)
{

    $FormatNomorInduks[0]=5;
}
// ===============================================================================================
if($FormatNomorInduksx[2] == 1)
{

    $dataTemplateInput2=trim(str_replace('}','',str_replace('{','',$FormatNomorInduks[2])));
    $FormatNomorInduks[2]=1;
}else{
    $display2='display:none;';
}
if($FormatNomorInduksx[2] == 5)
{

    $FormatNomorInduks[2]=5;
}
// ===============================================================================================
if($FormatNomorInduksx[4] == 1)
{

    $dataTemplateInput4=trim(str_replace('}','',str_replace('{','',$FormatNomorInduks[4])));
    $FormatNomorInduks[4]=1;
}else{
    $display3='display:none;';
}
if($FormatNomorInduksx[4] == 5)
{

    $FormatNomorInduks[4]=5;
}

// ===============================================================================================
if($FormatNomorInduksx[6] == 1)
{

    $dataTemplateInput6=trim(str_replace('}','',str_replace('{','',$FormatNomorInduks[6])));
    $FormatNomorInduks[6]=1;
}else{
    $display4='display:none;';
}
if($FormatNomorInduksx[6] == 5)
{

    $FormatNomorInduks[6]=5;
}

// ===============================================================================================
if($FormatNomorInduksx[8] == 1)
{

    $dataTemplateInput8=trim(str_replace('}','',str_replace('{','',$FormatNomorInduks[8])));
    $FormatNomorInduks[8]=1;
}else{
    $display5='display:none;';
}
if($FormatNomorInduksx[8] == 5)
{

    $FormatNomorInduks[8]=5;
}

echo Html::beginForm ('', 'post');
?>
<div class="settingparameters-form">
    <div class="form-group">
        <div class="page-header">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
        </div>

         <div class="form-group kv-fieldset-inline">
            <div class="col-sm-12">
              <div class="form-group">
                  <label class="control-label col-sm-2" for="email"><?php echo Html::label(Yii::t('app', 'Induk Number')); ?></label>
                  <div class="col-sm-6">
                    <?php echo Html::activeRadioList(
                    $model,
                    'NomorInduk',
                    ['Otomatis' => Yii::t('app', 'Automatic'), 'Manual' => Yii::t('app', 'Manual')]
                    ); ?>
                  </div>
                </div>
             </div>
        </div>
        <br><br><br>
         <div id="modelForm" class="form-group kv-fieldset-inline" >
            <div class="col-sm-12">
              <div class="form-group">
                  <label class="control-label col-sm-2" for="email"><?php echo Html::label(Yii::t('app', 'Middle Induk Number')); ?></label>
                        <div class="col-sm-2" style="width:11%; margin-right:-12px;">
                        <?php 
                        echo Select2::widget([
                                'id' => 'cbTemplate1',
                                'name' => 'cbTemplate[]',
                                'data' => $datacheckbox1,
                                'size'=>'sm',
                                'disabled'=>$disabletemplate,
                                'value'=>$FormatNomorInduks[0],
                                'pluginEvents' => [
                                    "select2:select" => 'function() { 
                                        var id = $("#cbTemplate1").val();
                                        if(id==1)
                                        {
                                            $("#cbTemplateInput1").show();
                                            $("#cbTemplateInput1x").hide();
                                        }else if(id==5){
                                            $("#cbTemplateInput1x").show();
                                            $("#cbTemplateInput1").hide();
                                        }else{
                                            $("#cbTemplateInput1x").hide();
                                            $("#cbTemplateInput1").hide();
                                        }
                                    }',
                                ]
                            ]);
                        echo Html::textInput('cbTemplateInput[0]',$dataTemplateInput1,['id'=>'cbTemplateInput1','class'=>'form-control manualinp','style'=> $display1.'width:100%;','disabled' => $disabled]);
                        // echo Html::dropDownList("cbTemplateInput[5]","select",$options,["id"=>"cbTemplateInput1x","class"=>"form-control manualinp","style"=> $display1x."width:100%;"]);
                        echo Html::hiddenInput('cbTemplateInput[50]', 'dump');
                        // echo Html::dropDownList("cbTemplateInput[51]",'Select',$options, array("id"=>"cbTemplateInput1x","class"=>"form-control manualinp","style"=> $display1x."width:100%;", 'options' => array($dataTemplateInput1=>array('selected'=>true))));

                        ?>
                        </div>
                        <div class="col-sm-1" style="width:8%; margin-right:-12px;">
                        <?php 
                        echo Select2::widget([
                                'id' => 'cbTemplate2',
                                'name' => 'cbTemplate[]',
                                'data' => $datacheckbox3,
                                'size'=>'sm',
                                'disabled'=>$disabletemplate,
                                'value'=>$FormatNomorInduks[1],
                                'pluginEvents' => [
                                    "select2:select" => 'function() { 
                                        var id = $("#cbTemplate2").val();
                                    }',
                                ]
                            ]);
                        ?>
                        </div>
                        <div class="col-sm-2" style="width:11%; margin-right:-12px;">
                        <?php 
                        echo Select2::widget([
                                'id' => 'cbTemplate3',
                                'name' => 'cbTemplate[]',
                                'data' => $datacheckbox1,
                                'size'=>'sm',
                                'disabled'=>$disabletemplate,
                                'value'=>$FormatNomorInduks[2],
                                'pluginEvents' => [
                                    "select2:select" => 'function() { 
                                        var id = $("#cbTemplate3").val();
                                        if(id==1)
                                        {
                                            $("#cbTemplateInput2").show();
                                        }else{
                                            $("#cbTemplateInput2").hide();
                                        }
                                    }',
                                ]
                            ]);
                        echo Html::textInput('cbTemplateInput[2]',$dataTemplateInput2,['id'=>'cbTemplateInput2','class'=>'form-control manualinp','style'=>$display2.'width:100%','disabled' => $disabled]);
                        echo Html::hiddenInput('cbTemplateInput[52]', 'dump');
                        ?>
                        </div>
                        <div class="col-sm-1" style="width:8%; margin-right:-12px;">
                        <?php 
                        echo Select2::widget([
                                'id' => 'cbTemplate4',
                                'name' => 'cbTemplate[]',
                                'data' => $datacheckbox3,
                                'size'=>'sm',
                                'disabled'=>$disabletemplate,
                                'value'=>$FormatNomorInduks[3],
                                'pluginEvents' => [
                                    "select2:select" => 'function() { 
                                        var id = $("#cbTemplate4").val();
                                    }',
                                ]
                            ]);
                        ?>
                        </div>
                        <div class="col-sm-2" style="width:11%; margin-right:-12px;">
                        <?php 
                        echo Select2::widget([
                                'id' => 'cbTemplate5',
                                'name' => 'cbTemplate[]',
                                'data' => $datacheckbox1,
                                'size'=>'sm',
                                'disabled'=>$disabletemplate,
                                'value'=>$FormatNomorInduks[4],
                                'pluginEvents' => [
                                    "select2:select" => 'function() { 
                                        var id = $("#cbTemplate5").val();
                                        if(id==1)
                                        {
                                            $("#cbTemplateInput4").show();
                                        }else{
                                            $("#cbTemplateInput4").hide();
                                        }
                                    }',
                                ]
                            ]);
                        echo Html::textInput('cbTemplateInput[4]',$dataTemplateInput4,['id'=>'cbTemplateInput4','class'=>'form-control manualinp','style'=>$display3.'width:100%','disabled' => $disabled]);
                        echo Html::hiddenInput('cbTemplateInput[54]', 'dump');
                        ?>
                        </div>
                        <div class="col-sm-1" style="width:8%; margin-right:-12px;">
                        <?php 
                        echo Select2::widget([
                                'id' => 'cbTemplate6',
                                'name' => 'cbTemplate[]',
                                'data' => $datacheckbox3,
                                'size'=>'sm',
                                'disabled'=>$disabletemplate,
                                'value'=>$FormatNomorInduks[5],
                                'pluginEvents' => [
                                    "select2:select" => 'function() { 
                                        var id = $("#cbTemplate6").val();
                                    }',
                                ]
                            ]);
                        ?>
                        </div>
                        <div class="col-sm-2" style="width:11%; margin-right:-12px;">
                        <?php 
                        echo Select2::widget([
                                'id' => 'cbTemplate7',
                                'name' => 'cbTemplate[]',
                                'data' => $datacheckbox1,
                                'size'=>'sm',
                                'disabled'=>$disabletemplate,
                                'value'=>$FormatNomorInduks[6],
                                'pluginEvents' => [
                                    "select2:select" => 'function() { 
                                        var id = $("#cbTemplate7").val();
                                        if(id==1)
                                        {
                                            $("#cbTemplateInput6").show();
                                        }else{
                                            $("#cbTemplateInput6").hide();
                                        }
                                    }',
                                ]
                            ]);
                        echo Html::textInput('cbTemplateInput[6]',$dataTemplateInput6,['id'=>'cbTemplateInput6','class'=>'form-control manualinp','style'=>$display4.'width:100%','disabled' => $disabled]);
                        echo Html::hiddenInput('cbTemplateInput[56]', 'dump');
                        ?>
                        </div>
                        <div class="col-sm-1" style="width:8%; margin-right:-12px;">
                        <?php 
                        echo Select2::widget([
                                'id' => 'cbTemplate8',
                                'name' => 'cbTemplate[]',
                                'data' => $datacheckbox3,
                                'size'=>'sm',
                                'disabled'=>$disabletemplate,
                                'value'=>$FormatNomorInduks[7],
                                'pluginEvents' => [
                                    "select2:select" => 'function() { 
                                        var id = $("#cbTemplate8").val();
                                    }',
                                ]
                            ]);
                        ?>
                        </div>
                        <div class="col-sm-2" style="width:11%;">
                        <?php 
                        echo Select2::widget([
                                'id' => 'cbTemplate9',
                                'name' => 'cbTemplate[]',
                                'data' => $datacheckbox1,
                                'size'=>'sm',
                                'disabled'=>$disabletemplate,
                                'value'=>$FormatNomorInduks[8],
                                'pluginEvents' => [
                                    "select2:select" => 'function() { 
                                        var id = $("#cbTemplate9").val();
                                        if(id==1)
                                        {
                                            $("#cbTemplateInput8").show();
                                        }else{
                                            $("#cbTemplateInput8").hide();
                                        }
                                    }',
                                ]
                            ]);
                        echo Html::textInput('cbTemplateInput[8]',$dataTemplateInput8,['id'=>'cbTemplateInput8','class'=>'form-control manualinp','style'=>$display5.'width:100%','disabled' => $disabled]);
                        echo Html::hiddenInput('cbTemplateInput[58]', 'dump');
                        ?>
                        </div>
                </div>
             </div>
        </div>
        <br><br><br><br>
         <div class="form-group kv-fieldset-inline"  >
            <div class="col-sm-12">
              <div class="form-group">
                  <label class="control-label col-sm-2" for="email"><?php echo Html::label(Yii::t('app', 'Format Number Barcode')); ?></label>
                  <div class="col-sm-6">
                    <?php echo Html::activeRadioList($model,'FormatNomorBarcode',['Item ID' => 'Item ID', 'No. Induk' => 'No.Induk']); ?>
                  </div>
                </div>
             </div>
        </div>
        <br><br><br>
        <div class="form-group kv-fieldset-inline" >
            <div class="col-sm-12">
              <div class="form-group">
                  <label class="control-label col-sm-2" for="email"><?php echo Html::label(Yii::t('app', 'Format Number RFID')); ?></label>
                  <div class="col-sm-6">
                    <?php echo Html::activeRadioList($model,'FormatNomorRFID',['Item ID' => 'Item ID', 'No. Induk' => 'No.Induk']); ?>
                  </div>
                </div>
             </div>
        </div>


    </div>
<?php 
echo Html::endForm();
?>

</div>
<script type="text/javascript">
     $('input:radio[name ="DynamicModel[NomorInduk]"]').click(function(){
        if($('input:radio[name ="DynamicModel[NomorInduk]"]:checked').val() != 'Otomatis')
        {
            $('#modelForm select,input:text').prop('disabled', true);
        }else{
            $('#modelForm select,input:text').prop('disabled', false);
        }
     });

      $('.manualinp').keypress(function(e){
        //disable kurawal
        if(e.which == 123 || e.which == 125){
          return false;
        } else {
        }
      });
</script>