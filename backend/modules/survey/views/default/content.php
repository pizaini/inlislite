<?php


use chofoteddy\wizard\Wizard;
use drsdre\wizardwidget\WizardWidget;

use yii\helpers\Html;

use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

//Model
use common\models\SurveyPilihan;
use common\models\SurveyIsian;
?>


<?php Pjax::begin();?>
<div class="box box-primary box-solid direct-chat direct-chat-primary">
    <div class="box-header">
        <h3 class="box-title" id="surveyTitle"><?= $model['NamaSurvey'] ?></h3>       <!-- Survey TITLE -->
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button class="btn btn-box-tool" data-toggle="tooltip" data-widget="chat-pane-toggle" data-original-title="Contacts"><i class="fa fa-comments"></i></button> -->
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div><!-- /.box-header -->
    <div class="box-body row" style="display: block;">

        <!-- Surveys List are loaded here -->
        <div class="col-sm-12">
            <ul id="list-survey">                                                           <!-- List Survey -->
       
                <?php
                $wizard_config = [
                    'id' => 'stepwizard',
                    // 'nav-tabs'=> 'disabled',
                    'steps' => $stp,
                    'complete_content' => $model['RedaksiAkhir'].'<br>'.Html::a('Kembali', Yii::$app->request->referrer,['class' => 'btn btn-warning pull-right','data-pjax'=>'0', ]), // Optional final screen
                    'start_step' => 1, // Optional, start with a specific step
                ];
                ?>

                <?= \drsdre\wizardwidget\WizardWidget::widget($wizard_config); ?>

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



<?php 
$js = <<< 'SCRIPT'
$(function() {
$('[data-toggle="tooltip"]').tooltip();
});


SCRIPT;
// Register tooltip/popover initialization javascript
$this->registerJs($js);
 ?>