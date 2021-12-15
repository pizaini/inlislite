<?php

use yii\helpers\Html;
use kartik\grid\GridView;
// use yii\widgets\DetailView;
use kartik\detail\DetailView;
use yii\widgets\Pjax;
use yii\web\JsExpression;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\SurveyIsianSearch $searchModel
 */


?>

<?php Pjax::begin();?>
<div class="box box-primary box-solid direct-chat direct-chat-primary">
  <div class="box-header">
    <h3 class="box-title" id="surveyTitle">Silahkan isi Survey berikut ini :</h3>       <!-- Survey TITLE -->
    <div class="box-tools pull-right">
      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      <!-- <button class="btn btn-box-tool" data-toggle="tooltip" data-widget="chat-pane-toggle" data-original-title="Contacts"><i class="fa fa-comments"></i></button> -->
      <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
    </div>
  </div><!-- /.box-header -->
  <div class="box-body row" style="display: block;"><br>

    <!-- Surveys List are loaded here -->
    <div class="col-sm-12">
      <ul id="list-survey">                                                           <!-- List Survey -->
        <?php foreach ($model as $row) { ?>
        <li id="nama-survey-<?=$row['ID']?>">
          <!-- <a href="javascript:redaksi(<?= $row['ID'] ?>);" ><?=$row['NamaSurvey']?></a> -->
          <?= Html::a($row['NamaSurvey'], ['pertanyaan', 'id' => $row['ID']],['id'=>'pertanyaan-'.$row['ID']]) ?>
        </li>
        <p hidden="hidden" id="redaksi-awal-<?=$row['ID']?>"><?= $row['RedaksiAwal'] ?></p>
        <?php } ?>
      </ul>
    </div><!-- /.Surveys List -->  

  </div>
  <div class="box-footer" style="display: block;">
    <form action="#" method="post">
      <div class="input-group">

      </div>
    </form>
  </div><!-- /.box-footer-->
</div>
<?php Pjax::end();?>


<script type="text/javascript">
    function redaksi(data) {
      alert(data);
      //var idper = data;
      //$('#redaksi-awal-'+idper).show();
  }
</script>


<?php
$this->registerJs("

 // $('#nama-survey-1').on('click', function(){
 //  alert('yohohoho');
 //  $('#redaksi-awal-'+idper).show();
 // });

");
?>

