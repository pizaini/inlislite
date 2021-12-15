<?php 
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\Librarysearchcriteria;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
?>


<?php

switch ($processid) {

    case '0':
        echo '<div class="col-md-3">';
        echo Select2::widget([
            'id' => 'cbCriteria',
            'name' => 'cbCriteria',
            'data' => array(
                    'MARC21MRC'=>'MARC 21 (MRC)',
                    'MARC21XML'=>'MARC 21 (XML)',
                    'DUBLINXML'=>'DUBLIN (XML)',
                    'MODSXML'=>'MODS (XML)'),
            'size'=>'sm',
        ]);
        echo '</div>';
        echo '<div class="col-md-2">';
        $url = Yii::$app->urlManager->createUrl(['../uploaded_files/templates/contoh_file/ContohFile.zip']);
        echo Html::a('Unduh Contoh File', $url, ['class'=>'btn btn-primary btn-xs']);
        echo '</div>';
        $form = ActiveForm::begin(['options' => ['id'=>'marcform','enctype' => 'multipart/form-data']]);
        echo '<div class="col-md-3">';
        echo $form->field($model, 'file')->fileInput()->label(false);
        echo '</div>';
        ActiveForm::end();

        break;
    default:
        echo '<div class="col-md-3">';
        echo Select2::widget([
            'id' => 'cbCriteria',
            'name' => 'cbCriteria',
            'data' => ArrayHelper::map(Librarysearchcriteria::loadCriteriaByLibrary($processid),'CRITERIANAME','CRITERIANAME'),
            'size'=>'sm',
        ]);
        echo '</div>';
        echo '<div class="col-md-3">';
        echo  Html::textInput("txtSearch", "",['id'=>'txtSearch','class'=>'form-control input-sm']);
        echo '</div>';
        echo '<div class="col-md-1">';
        echo  Html::button('<i class="glyphicon glyphicon-search"></i> '.Yii::t('app', 'Search'), [
                    'id'=>'btnSearch',
                    'class' =>'btn btn-success btn-sm',
                    'onclick' => '

                        var LIBID = '.$processid.';
                        var CRITID = $(\'#cbCriteria\').val();
                        var QUERY = $(\'#txtSearch\').val();
                        var MAXRECORD = 10;
                        if(QUERY !== ""){
                        isLoading=false;
                        $.ajax({
                              type: \'POST\',
                              url : "'.Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/salin-katalog-sru"]).'",
                              data : {libId: LIBID, critId: CRITID, query : QUERY, maxRecord : MAXRECORD},
                              beforeSend : function()
                              {
                                  $("#result").html("<center><img src=\'../../assets_b/images/loading.gif\' width=\'150px\' /></center>");
                              },
                              success : function(response) {
                                  $("#result").html(response);
                              }
                          });
                        }else{
                            alertSwal("Silahkan masukan kata kunci","warning");
                            $(\'#txtSearch\').focus();
                        }
                    '

                    ]);
        echo '</div>';
        break;
        
                    
}

?>

<script type="text/javascript">
    $("#txtSearch").ready(function() {
      $("#txtSearch").keydown(function(event){
        if(event.keyCode == 13) {
          event.preventDefault();
          $("#btnSearch").click();
          return false;
        }
      });
    });
</script>


