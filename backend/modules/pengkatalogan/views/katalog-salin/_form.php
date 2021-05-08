

<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\widgets\Select2;
use common\models\Worksheets;
use common\models\Library;



/**
 * @var yii\web\View $this
 * @var common\models\Collections $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<style type="text/css">
.modal .modal-dialog { width: 95%; }
.modal .modal-body {
    max-height: 550px;
    overflow-y: auto;
}
</style>
<div id="salin-katalog-create" class="salin-katalog-create">

<div id="infoMessage"></div>
  <!-- form start -->
  <?php echo Html::beginForm('', 'post', ['enctype' => 'multipart/form-data']); ?>
  <form role="form">
    <div class="box-body">


      <div class="form-group">
        <div class="row">
          <label class="control-label col-sm-2" for="email"><?php echo Html::label(yii::t('app','Jenis Bahan')); ?></label>
          <div class="col-sm-4">
            <?php 
                  echo Select2::widget([
                    'id' => 'cbWorksheets',
                    'name' => 'cbWorksheets',
                    'data'=>ArrayHelper::map(Worksheets::find()->all(),'ID','Name'),
                                      'pluginOptions' => [
                                          'allowClear' => true,
                                      ],
                    'size'=>'sm',
                    ]);?>
          </div>
        </div>
      </div>

      <div class="form-group">
        <div class="row">
          <label class="control-label col-sm-2" for="email"><?php echo Html::label('Sumber'); ?></label>
          <div class="col-sm-4">
            <?php 
                  echo Select2::widget([
                    'id' => 'cbLibrary',
                    'name' => 'cbLibrary',
                    'data'=>$library,
                    'size'=>'sm',
                    'pluginEvents' => [
                          "select2:select" => 'function() { 
                              var id = $("#cbLibrary").val();
                               $.ajax({
                                  type     :"POST",
                                  cache    : false,
                                  url  : "'.Yii::$app->urlManager->createUrl(["pengkatalogan/katalog-salin/get-dropdown-salinkatalog"]).'?id="+id,
                                  success  : function(response) {
                                      $("#actionDropdown").html(response);
                                      $("#result").html("");
                                  }
                              });
                          }',
                      ]
                    ]);?>
          </div>
        </div>
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
  </form>

  <?php echo Html::endForm(); ?>

  <div id="result">

  </div>
</div>


</div>
<script type="text/javascript">
$(document).ready(function(){
  $('input[type=file]').change(function(){

    $(this).simpleUpload("upload?type="+$("#cbCriteria").val(), {

      start: function(file){
        //upload started
      },

      progress: function(progress){
        //received progress
        //$('#result').html("<center><img src='../../assets_b/images/loading.gif' width='150px' /></center>");
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

</script>
