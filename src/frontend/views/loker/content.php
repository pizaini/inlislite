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

<style type="text/css" media="screen">
    .wizard > div.wizard-inner {
   /*position: relative;*/
    display: none;
}

.wizard .tab-pane {
    /*position: relative;*/
    padding-top: 0px;
}

.wizard {
    margin: 0px auto; 
    /*background: #fff;*/
}

.list-inline {
    margin-top: 15px;
}

</style>

<div class="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h4 class="modal-title"><?= $model['NamaSurvey'] ?></h4>
            </div>
            <div class="modal-body">
                <?php
                $wizard_config = [
                'id' => 'stepwizard',
                'default_buttons' => [
                    'prev' => ['title' => Yii::t('app','Previous'), 'options' => [ 'class' => 'btn btn-default', 'type' => 'button']],
                    'next' => ['title' => Yii::t('app','Next'), 'options' => [ 'class' => 'btn btn-default', 'type' => 'button']],
                    'save' => ['title' => Yii::t('app','Save'), 'options' => [ 'class' => 'btn btn-success', 'id' => 'save_survey','type' => 'button','disabled'=>'disabled']],
                    'skip' => ['title' => Yii::t('app','Skip'), 'options' => [ 'class' => 'btn btn-default', 'type' => 'button']],
                    ],
                'steps' => $stp,
                    'complete_content' => $model['RedaksiAkhir'].'<br>'.Html::a('Kembali', Yii::$app->request->referrer,['class' => 'btn btn-warning pull-right','data-pjax'=>'0', ]), // Optional final screen
                    'start_step' => 1, // Optional, start with a specific step
                    ];
                    ?>

                    <?= \drsdre\wizardwidget\WizardWidget::widget($wizard_config); ?>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
    function CheckInput(qst){

        // var myElems = document.getElementsByName(qst);\
        var myElem = document.getElementsByName(qst);
        if ($(myElem).is("textarea"))
        {
                if (!$("textarea[name="+qst+"]").val()) 
                {
                    $("#next_step"+qst).attr( "disabled",true );
                    $("#save_survey").attr( "disabled",true );
                    // $("#next_step"+qst).removeAttr( "disabled" );
                }
                else
                {
                    $("#next_step"+qst).attr( "disabled",false );
                    $("#save_survey").attr( "disabled",false );
                }
            $("textarea[name="+qst+"]").keyup(function(){
                if (!$("textarea[name="+qst+"]").val()) 
                {
                    $("#next_step"+qst).attr( "disabled",true );
                    $("#save_survey").attr( "disabled",true );
                    // $("#next_step"+qst).removeAttr( "disabled" );
                }else
                {
                    $("#next_step"+qst).attr( "disabled",false );
                    $("#save_survey").attr( "disabled",false );
                }
            });
        }
        else
        {
            if ($("input[name="+qst+"]").is(':checked')) 
            {
                // $("#next_step"+qst).removeAttr( "disabled" );
                $("#next_step"+qst).attr( "disabled",false );
                $("#save_survey").attr( "disabled",false );
            }else 
            {
                $("#next_step"+qst).attr( "disabled",true );
                $("#save_survey").attr( "disabled",true );
            }
        }   
    }


    function nextSteps(){
        $("#save_survey").attr( "disabled",true );
    }
</script>

<?php
$this->registerJs('


    $("#save_survey").click(function() {

        var srvid = '.$model['ID'].';
        var allVals = [];
        $("input[id=Pilihan]:checked").each(function() {
           allVals.push($(this).val());
        });

        $("#isian-survey").each(function() {
            if ($(this).val() != "") {
                //allVals.push($(this).val());
                $.ajax({
                    type     :"POST",
                    cache    : false,
                    url  : "entry-isian?qstid="+$(this).attr("name")+"&isian="+$(this).val(),
                    success  : function(response) {
                        //alert(response);
                    }
                });
            }
        });

        if (allVals != "") {
           // alert(allVals);
            $.ajax({
                type     :"POST",
                cache    : false,
                url  : "entry-survey?srvid="+srvid+"&pilihan="+allVals,
                // data: $("input[id=Pilihan]:checked").serialize,
                success  : function(response) {
                    //alert(response);
                }
            });
        } else {
            //alert("Data Kosong");
        }
    });


');

?>
<!-- $('input[id=Pilihan]:checked').each(function() {
       allVals.push($(this).val());
     });



 -->
<?php Pjax::end();?>