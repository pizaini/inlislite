<?php


use chofoteddy\wizard\Wizard;
use drsdre\wizardwidget\WizardWidget;

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

//Model
use common\models\SurveyPilihan;
use common\models\SurveyIsian;

$this->title = Yii::t('app', 'Survei Pemustaka');
Yii::$app->view->params['subTitle'] = '<h3 style="padding-top: 15px;">Survey <br> '.$model['NamaSurvey'].'<h3>';

?>


<?php Pjax::begin();?>
<style type="text/css" media="screen">

.wizard {
    margin: 0px auto;
    background: #fff;
}

    .wizard .nav-tabs {
        position: relative;
        margin: 0px auto;
        margin-bottom: 0;
        border-bottom-color: #e0e0e0;
    }

    .wizard > div.wizard-inner {
        position: relative;
    }

.connecting-line {
    height: 2px;
    background: #e0e0e0;
    position: absolute;
    width: 80%;
    margin: 0 auto;
    left: 0;
    right: 0;
    top: 50%;
    z-index: 1;
}

.wizard .nav-tabs > li.active > a, .wizard .nav-tabs > li.active > a:hover, .wizard .nav-tabs > li.active > a:focus {
    color: #555555;
    cursor: default;
    border: 0;
    border-bottom-color: transparent;
}

span.round-tab {
    width: 70px;
    height: 70px;
    line-height: 70px;
    display: inline-block;
    border-radius: 100px;
    background: #fff;
    border: 2px solid #e0e0e0;
    z-index: 2;
    position: absolute;
    left: 0;
    text-align: center;
    font-size: 25px;
}
span.round-tab i{
    color:#555555;
}
.wizard li.active span.round-tab {
    background: #fff;
    border: 2px solid #5bc0de;
    
}
.wizard li.active span.round-tab i{
    color: #5bc0de;
}

span.round-tab:hover {
    color: #333;
    border: 2px solid #333;
}

.wizard .nav-tabs > li {
    width: 25%;
}

.wizard li:after {
    content: " ";
    position: absolute;
    left: 46%;
    opacity: 0;
    margin: 0 auto;
    bottom: 0px;
    border: 5px solid transparent;
    border-bottom-color: #5bc0de;
    transition: 0.1s ease-in-out;
}

.wizard li.active:after {
    content: " ";
    position: absolute;
    left: 46%;
    opacity: 1;
    margin: 0 auto;
    bottom: 0px;
    border: 10px solid transparent;
    border-bottom-color: #5bc0de;
}

.wizard .nav-tabs > li a {
    width: 70px;
    height: 70px;
    margin: 20px auto;
    border-radius: 100%;
    padding: 0;
}

    .wizard .nav-tabs > li a:hover {
        background: transparent;
    }

.wizard .tab-pane {
    position: relative;
    padding-top: 30px;
}

.wizard h3 {
    margin-top: 0;
}

@media( max-width : 585px ) {

    .wizard {
        width: 90%;
        height: auto !important;
    }

    span.round-tab {
        font-size: 16px;
        width: 50px;
        height: 50px;
        line-height: 50px;
    }

    .wizard .nav-tabs > li a {
        width: 50px;
        height: 50px;
        line-height: 50px;
    }

    .wizard li.active:after {
        content: " ";
        position: absolute;
        left: 35%;
    }
}


/*/////////////Jika tanpa status bar wizard widget///////////////////*/

/*.wizard > div.wizard-inner {
    display: none;
}

.wizard .tab-pane {
    padding-top: 0px;
}

.wizard {
    margin: 0px auto; 
}

.list-inline {
    margin-top: 15px;
}*/

</style>

<div class="">
    <div class="modal-dialog" style="margin-top: 0px">
        <div class="">
            <div class="modal-body">
                <?php
                ($model['HasilSurveyShow'] == 1 ? $showSurvey = '<div class="row" ><a href="'.Yii::$app->urlManager->createUrl(['survey/hasil-survey','id'=>$model['ID']]).'" class="btn btn-primary col-sm-4 col-sm-offset-4"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Lihat hasil survey</a></div><br/>' : $showSurvey = "");
                $wizard_config = [
                'id' => 'stepwizard',
                'default_buttons' => [
                    'prev' => ['title' => Yii::t('app','Previous'), 'options' => [ 'class' => 'btn btn-lg btn-primary', 'type' => 'button']],
                    'next' => ['title' => Yii::t('app','Next'), 'options' => [ 'class' => 'btn btn-lg btn-primary', 'type' => 'button']],
                    'save' => ['title' => Yii::t('app','Save'), 'options' => [ 'class' => 'btn btn-lg btn-success', 'id' => 'save_survey','type' => 'button','disabled'=>'disabled']],
                    'skip' => ['title' => Yii::t('app','Skip'), 'options' => [ 'class' => 'btn btn-lg btn-warning', 'type' => 'button']],
                    ],
                'steps' => $stp,
                    // 'complete_content' => "<h4 class='text-center'>".$model['RedaksiAkhir'].'</h4><br>'.$showSurvey.Html::a('Kembali', Url::to(['/survey']),['class' => 'btn btn-lg btn-warning pull-right','data-pjax'=>'0', ]), // Optional final screen
                    'complete_content' => "<h4 class='text-center'>".$model['RedaksiAkhir'].'</h4><br>'.$showSurvey, // Optional final screen
                    'start_step' => 1, // Optional, start with a specific step
                    ];
                    ?>

                    <?= \drsdre\wizardwidget\WizardWidget::widget($wizard_config); ?>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?= Html::a('Kembali ke survey', Url::to(['/survey']),['class' => 'btn btn-lg btn-danger pull-right','data-pjax'=>'0','style'=>'margin-bottom: 30px;' ])  ?>

<script type="text/javascript">
    var akhir = '';
    var valakhir = '';
    var types = 0;

    var jawab = [];
    // var total = sports.push('football', 'swimming');

    function nextSteps(qst, type){
        // alert(type)
        if(type == '1'){
            jawab.push({
                idqst : qst,
                jwb : $("textarea[name="+qst+"]").val()
            })
            // if ($("textarea[name="+qst+"]").val() != "") {
            //     // alert($("textarea[name="+akhir+"]").val())
                
            //     $.ajax({
            //         type     :"POST",
            //         cache    : false,
            //         url  : "entry-isian?qstid="+qst+"&isian="+$("textarea[name="+qst+"]").val(),
            //        // url  : "entry-isian?qstid="+$(this).attr("name")+"&isian="+$(this).val(),
            //         success  : function(response) {
            //             $("#save_survey").attr( "disabled",true );
            //         }
            //     });
            // }
        }
        types = type
    }

    function CheckInput(qst,type){

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
        akhir = qst;
        types = type;
    }

    function enableSaveButton(qst, type){
        $("#save_survey").attr( 'disabled',false );
        akhir = qst;
        types = type;
    }

    


</script>


<?php
$this->registerJs('


    if ('.$model['TargetSurvey'].' == 1) 
    {
        function checkLogin()
        {
            var nomember = $("#login-name").val();
            var passmember = $("#login-pass").val();
            $.ajax({
                type     :"POST",
                cache    : false,
                url  : "check-membership",
                data     : {nomember : nomember,passmember : passmember},
                // data: $("input[id=Pilihan]:checked").serialize,
                success  : function(response) {
                   if (response =="null") {
                        swal({ 
                            title: "Error",
                            text: "Nomor Anggota dan Password tidak valid",
                            // type: "error" 
                          },
                          function(){
                            window.location.reload(true);
                        });
                   }
                   else
                   {
                        var $active = $(".wizard .nav-tabs li.active");
                        $active.next().removeClass("disabled");
                        nextTab($active);
                   }
                },

            });
        }



        $("#stepwizard_step1_next_wahooo").click(function(){
            checkLogin();
        });  

        $("#login-name").keypress(function (e) {
            var key = e.which;
            if(key == 13)  // the enter key code
            {
                checkLogin();
            }
        }); 
        $("#login-pass").keypress(function (e) {
            var key = e.which;
            if(key == 13)  // the enter key code
            {
                checkLogin();
            }
        }); 


    } 
    


    //Refresh page when iddle for 1 minutes
    var time = new Date().getTime();
    $(document.body).bind("mousemove keypress", function(e) {
        time = new Date().getTime();
    });

    function refresh() {
        if(new Date().getTime() - time >= 60000) 
            // window.location.reload(true);
            window.location.href = "index";
        else 
            setTimeout(refresh, 10000);
    }
    setTimeout(refresh, 10000);


    $("#save_survey").click(function() {
        
        var srvid = '.$model['ID'].';
        var allVals = [];
        $("input[id=Pilihan]:checked").each(function() {
           allVals.push($(this).val());
        });

        //Untuk Save isian survey
        if(types == "1"){
            $("#isian-survey").each(function() {
                var results = [];
                if ($("textarea[name="+akhir+"]").val() != "") {
                    jawab.push({
                        idqst : akhir,
                        jwb : $("textarea[name="+akhir+"]").val()
                    })

                    jawab.forEach(function (a) {
                        if (!this[a.idqst]) {
                            this[a.idqst] = { idqst: a.idqst, jwb: $("textarea[name="+akhir+"]").val() };
                            results.push(this[a.idqst]);
                        }
                        this[a.idqst].jwb = a.jwb;
                    }, Object.create(null));

                    //allVals.push($(this).val());
                    $.ajax({
                        type     :"POST",
                        cache    : false,
                        data : {jawaban : results},
                        url : "entry-isian",
                        // url  : "entry-isian?qstid="+akhir+"&isian="+$("textarea[name="+akhir+"]").val(),
                       // url  : "entry-isian?qstid="+$(this).attr("name")+"&isian="+$(this).val(),
                        success  : function(response) {
                            //alert(response);
                        }
                    });
                }
            });
        }
        
        
        //Untuk Save pilihan survey
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
