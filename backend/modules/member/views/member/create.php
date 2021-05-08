<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
/**
 * @var yii\web\View $this
 * @var common\models\Members $model
 */

$this->title = Yii::t('app', 'Tambah Anggota');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php 

// loadMasaBerlaku
$masaBerlaku = \common\models\JenisAnggota::find()->one(); // select 1st jenisAnggota as default
$registerDate = date("d-m-Y");
$endDate = date("d-m-Y");

$endDate = \common\components\Helpers::addDayswithdate(date("Y-m-d"),$masaBerlaku->MasaBerlakuAnggota); //RegisterDate.AddDays(Jumlah);


$endDate = \common\components\Helpers::DateTimeToViewFormat($endDate);
//- loadMasaBerlaku

?>

<?php $form = ActiveForm::begin(
    [
        'type'=>ActiveForm::TYPE_HORIZONTAL,
        'enableClientValidation' => true,
        'formConfig' => [
            'labelSpan' => '3',
            //'deviceSize' => ActiveForm::SIZE_TINY,
            'showErrors'=>false,
        ],
       /*'fieldConfig' => [
                        'template' => "<div class=\"row\">
                                        \n<div class=\"col-sm-12\">{label} {input}</div>
                                        \n
                                        <div class=\"col-xs-offset-3 col-xs-9\">
                                        <div style=\"margin-top: 5px;margin-bottom: 10px;\"></div></div>
                                        </div>",
                    ],*/
    ]
    );
?>

<div class="row-fluid control-label-narrow">
    <div class="page-header">
        <h3>
            &nbsp;
            <!-- <span class="glyphicon glyphicon-plus-sign"></span> Tambah -->

            <div class="pull-left">
            <?php
                echo '<p>';
                // echo  Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
                // echo  '&nbsp;' . Html::a(Yii::t('app', 'Salin dari Data Kependudukan'), ['detail-kependudukan'], ['class' => 'btn btn-primary','data-toggle'=>"modal",
                //                                     'data-target'=>"#myModal",
                //                                     'data-title'=>"Detail Data",]);
                echo '&nbsp;' . Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app', 'Salin dari Data Kependudukan'), 'javascript:void(0)', ['id'=>'btnAddTag','class' => 'AddTag btn btn-primary','data-toggle' => 'tooltip']);
                // echo  '&nbsp;' . Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-warning']);
                
                //echo  '&nbsp;' . Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-warning']) . '</p>';
                echo '</p>';
            ?>
            </div>
        </h3>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
        'memberNo' => $memberNo,
        'membersForm'=>$membersForm,
        'form'=>$form,
        'pendaftaran'=>$pendaftaran,
        'endDate'=>$endDate
    ]) ?>

<div class="rowa">
    <div class="col-sm-12" style="border-top: 1px solid #eee;margin-top: 10px ">
<br/>
        
            <?php
                echo '<p>';
                echo  Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
                // echo  '&nbsp;' . Html::a(Yii::t('app', 'Salin dari Data Kependudukan'), ['detail-kependudukan'], ['class' => 'btn btn-primary','data-toggle'=>"modal",
                //                                     'data-target'=>"#myModal",
                //                                     'data-title'=>"Detail Data",]);
                echo  '&nbsp;&nbsp;' . Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-warning']);
                
                //echo  '&nbsp;' . Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-warning']) . '</p>';
                echo '</p>';
            ?>
        
    </div>

</div>

<input type="hidden" id="hdnAjaxUrlAddTag" value="<?=Yii::$app->urlManager->createUrl(['member/member/detail-kependudukan'])?>">

<?php 

$result = \yii\helpers\ArrayHelper::getColumn($membersForm, 'Member_Field_id');
   
$value = \yii\helpers\ArrayHelper::getValue($result, 7 - 1);

ActiveForm::end();
?>





</div>
<?php 
Modal::begin([
  'id' => 'tag-modal'
  
]);
?>
<div id="tag-body"></div>
<?php
Modal::end();

$this->registerJs("

    $('#members-tglregisterdate').val('".$registerDate."');
    
     $('#endField').hide();

    $('#members-tglenddate').val('".$endDate."');
    isLoading = false;
    $('#myModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var modal = $(this)
        var title = button.data('title') 
        var href = button.attr('href') 
        modal.find('.modal-title').html(title)
        modal.find('.modal-body').html('<i class=\"fa fa-spinner fa-spin\"></i>')
        isLoading = false;
        $.post(href)
            .done(function( data ) {
                modal.find('.modal-body').html(response)
            });
        });


    $('#members-tglregisterdate').change(function () {
       $.get('masa-berlaku?jenis='+$('#members-jenisanggota_id').val()+'&registerDate='+$('#members-tglregisterdate').val(), function( data ) {
                                            console.log(data);
                                            $('#masa-berlaku').text($('#members-tglregisterdate').val() +' s.d ' + data);
                                             $('#members-tglenddate').val(data);

                                        });
    });

    $('#btnAddTag').click(function(){
        $.ajax({
            type     :\"POST\",
            cache    : false,
            url  : $(\"#hdnAjaxUrlAddTag\").val(),
            beforeSend : function(){
              $(\"#tag-body\").html(\"<center>Loading form...</center>\");
            },
            success  : function(response) {
                $(\"#tag-body\").html(response);
            },
            async: true
        });
          $(\"#tag-modal\").modal(\"show\");
    })

    
");
?>