<?php 
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\Librarysearchcriteria;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
?>


<?php

if($processid==0){
        ?>

        <div class="form-group">
            <div class="row">
              <label class="control-label col-sm-2" for="email"><?php echo Html::label('Format File'); ?></label>
              <div class="col-sm-4">
                <?php
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
                ?>
               </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
              <label class="control-label col-sm-2" for="email">
                  &nbsp;
              </label>
              <div class="col-sm-4">
              <?php 
                        $url = Yii::$app->urlManager->createUrl(['../uploaded_files/templates/contoh_file/ContohFile.zip']);
                        echo Html::a(yii::t('app','Unduh Contoh File'), $url, ['class'=>'btn btn-primary btn-xs']);
                  ?>
               </div>
            </div>
        </div>

        <?php
        $form = ActiveForm::begin(['options' => ['id'=>'marcform','enctype' => 'multipart/form-data']]);
        ?>
        <div class="form-group">
            <div class="row">
              <label class="control-label col-sm-2" for="email"><?php echo Html::label(yii::t('app','Pilih File')); ?></label>
                <div class="col-sm-4">

                <?php
                echo $form->field($model, 'file')->fileInput()->label(false);
                ?>
                </div>
            </div>
        </div>
        <?php
        ActiveForm::end();

}else{
        ?>
        <div class="form-group">
            <div class="row">
              <label class="control-label col-sm-2" for="email"><?php echo Html::label('Kriteria'); ?></label>
                <div class="col-sm-4">
                <?php
                echo Select2::widget([
                    'id' => 'cbCriteria',
                    'name' => 'cbCriteria',
                    'data' => ArrayHelper::map(Librarysearchcriteria::loadCriteriaByLibrary($processid),'CRITERIANAME','CRITERIANAME'),
                    'size'=>'sm',
                ]);
                ?>
                </div>
            </div>
        </div>


        <div class="form-group">
            <div class="row">
              <label class="control-label col-sm-2" for="email"><?php echo Html::label('Maks'); ?></label>
                <div class="col-sm-4">
                <?php
                echo Select2::widget([
                    'id' => 'cbPagesize',
                    'name' => 'cbPagesize',
                    'data' => array(
                            '10'=>'10 per Halaman',
                            '20'=>'20 per Halaman',
                            '50'=>'50 per Halaman',
                            '100'=>'100 per Halaman',
                            '200'=>'200 per Halaman',
                            '500'=>'500 per Halaman',
                            '1000'=>'1000 per Halaman'),
                    'size'=>'sm',
                ]);
                ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
              <label class="control-label col-sm-2" for="email"><?php echo Html::label('Kata Kunci'); ?></label>
                <div class="col-sm-4">
                <?php
                 echo  Html::textInput("txtSearch", "",[
                    'id'=>'txtSearch',
                    'class'=>'form-control input-sm'

                    ]);
                ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
              <label class="control-label col-sm-2" for="email">&nbsp;</label>
                <div class="col-sm-4">
                <?php
                  echo  Html::button('<i class="glyphicon glyphicon-search"></i> '.Yii::t('app', 'Search'), [
                    'id'=>'btnSearch',
                    'class' =>'btn btn-success btn-sm',
                    'onclick' => '

                        var LIBID = '.$processid.';
                        var CRITID = $(\'#cbCriteria\').val();
                        var QUERY = $(\'#txtSearch\').val();
                        var MAXRECORD = $(\'#cbPagesize\').val();
                        if(QUERY !== ""){
                        $.ajax({
                              type: \'POST\',
                              url : "'.Yii::$app->urlManager->createUrl(["pengkatalogan/katalog-salin/sru"]).'",
                              data : {libId: LIBID, critId: CRITID, query : QUERY, maxRecord : MAXRECORD},
                              success : function(response) {
                                  endLoading();
                                  $("#result").html(response);
                              }
                          });
                        }else{
                            alertSwal("Silahkan masukan kata kunci","warning");
                            $(\'#txtSearch\').focus();
                        }
                    '

                    ]);
                ?>
                </div>
            </div>
        </div>


        <?php
        
    
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


