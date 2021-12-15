

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

<div class="form-group" style="padding-bottom:30px;">
  <div class="col-md-3">
      <?php 

  echo kartik\widgets\Select2::widget([
    'id' => 'cbLibrary',
    'name' => 'cbLibrary',
    'data' => $library,
    'size'=>'sm',
    /*'pluginOptions' => [
        'allowClear' => true
    ],*/
    //'theme' => Select2::THEME_BOOTSTRAP,
    'pluginEvents' => [
        "select2:select" => 'function() { 
            var id = $("#cbLibrary").val();
             $.ajax({
                type     :"POST",
                cache    : false,
                url  : "'.Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/get-dropdown-salinkatalog"]).'?id="+id,
                success  : function(response) {
                    $("#actionDropdown").html(response);
                }
            });
        }',
    ]
]);

  ?>
  </div>
   <div id="actionDropdown">
       
       <?php 
       $model = new \backend\models\ImportMarcForm();
       echo $this->render('_subDropdownSalinkatalog', [
                'processid' => 0,
                'model'=>$model,
            ]); 
       ?>
   </div>
</div>

<div id="result"></div>
<div id="msgerror"></div>
<input type="hidden" id="hdnFor" value="<?=$for?>">
<input type="hidden" id="hdnAjaxUrlSalinKatalog" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/salin-katalog-proses"])?>">
<script type="text/javascript">
$(document).ready(function(){

  $('input[type=file]').change(function(){

    $(this).simpleUpload("salin-katalog-upload?type="+$("#cbCriteria").val(), {

      start: function(file){
        //upload started
        $('#result').html("");
      },

      progress: function(progress){
        //received progress
        $('#result').html("<center><img src='../../assets_b/images/loading.gif' width='150px' /></center>");
      },

      success: function(data){
        //upload successful
        $('#result').html(data);
      },

      error: function(error){
        //upload failed
        $('#result').html("Failure!<br>" + error.name + ": " + error.message);
      }

    });

  });

});

sendTaglist = function(datataglist,rda) {
  $.ajax({
      type     :"POST",
      cache    : false,
      url  : $("#hdnAjaxUrlSalinKatalog").val()+"?for="+$("#hdnFor").val()+"&rda="+$("#hdnRda").val(),
      data: datataglist,
      success  : function(response) {
          $("#catalogs-worksheet_id").val("1").trigger("change");
          $("#entryBibliografi").html(response);

          var oldtext = $(".content-wrapper .content-header h1").html();
          var newtext = oldtext;
          if(rda=='1' && oldtext.indexOf("(RDA)") == -1)
          {
            $("#hdnRda").val("1");
            newtext = oldtext+" (RDA)";
            $(".rdainput").show();
          }

          if(rda=='0' && oldtext.indexOf("(RDA)") != -1)
          {
            $("#hdnRda").val("0");
            newtext = oldtext.replace("(RDA)","");
            $(".rdainput").hide();
          }


          $(".content-wrapper .content-header h1").html(newtext);
          $(".content-wrapper .content-header ul.breadcrumb li.active").html(newtext);
          document.title = newtext;
      }
  });
}
</script>
