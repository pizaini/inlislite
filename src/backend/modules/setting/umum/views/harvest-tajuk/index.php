<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\DatePicker;
use kartik\widgets\FileInput;

$this->title = Yii::t('app', 'Salin Tajuk Subjek');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Umum'), 'url' => Url::to(['/setting/umum'])];
$this->params['breadcrumbs'][] = $this->title;

?>
<style type="text/css">
    .col-sm-4 label{
        font-weight: normal;
    }

    .table{
        margin-bottom: 0px;
    }

    .form-group > .col-md-offset-2, .col-md-10{
        margin-left: 0px;
    }
</style>



<div class="settingparameters-create">
    <div class="nav-tabs-custom" id="anggota-area">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#upload-file" data-toggle="tab"><?= Yii::t('app','File Record')?></a></li>
            <li><a href="#salin-online" data-toggle="tab"><?= Yii::t('app','Web Tajuk Online Perpusnas')?></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane" id="salin-online">
                
                <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);  ?>
                <div class="page-header">
                    <?= Html::submitButton(Yii::t('app', 'Proses'), ['class' => 'btn btn-primary', 'id' => 'salinOnline']) ?>
                    <br>
                    <!-- <div class="col-md-12"> -->
                        <!-- <div class="form-group"> -->
                            <?php 
                                    // echo '<label>Tanggal</label>';
                                    // echo DatePicker::widget([
                                    //     'name' => 'tanggal',
                                    //     'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                                    //     'pluginOptions' => [
                                    //         'autoclose'=>true,
                                    //         'format' => 'yyyy-mm-dd'
                                    //     ]
                                    // ]);
                            ?>

                        <!-- </div> -->
                    <!-- </div> -->
                </div>
                <?php ActiveForm::end();  ?>
            </div>
            <div class="tab-pane active" id="upload-file">
                <div class="page-header">
                    <div class="alert alert-warning" style="display: none" role="alert"><?= yii::t('app','Harap tunggu! Proses penyalinan tajuk sedang berlangsung')?></div>
                    <!-- <div class="col-md-12"> -->
                        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
                        <?= $form->field($model, 'file')->widget(FileInput::classname(), [
                            'options'=>['accept'=>'.xml, application/xml'],
                            'pluginOptions'=>[
                                'allowedFileExtensions'=>['xml'],
                                'showPreview' => false,
                                'autoReplace' => true,
                                'showCaption' => true,
                                'showRemove' => true,
                                'showUpload' => true,
                                'uploadLabel' => Yii::t('app','Proses'),
                                'uploadUrl' => Url::to(['/setting/umum/harvest-tajuk/import']),
                            ]
                        ]);?>
                        <?php ActiveForm::end() ?>
                    <!-- </div> -->
                </div>
            </div>
            
        </div>
    </div>
    <h5><?= yii::t('app','Petunjuk penyalinan tajuk subjek')?> : </h5>
    <ol>
       <li>
            <?= yii::t('app','Untuk salin tajuk subjek secara online, pastikan koneksi internet tidak terputus saat proses penyalinan berlangsung')?>
        </li>
       <li>
           <?= yii::t('app','Pastikan tidak ada kegiatan entri atau pemutahiran data katalog saat proses penyalinan berlangsung')?>
       </li>
       <li>
           <?= yii::t('app','Proses penyalinan dapat berlangsung cukup lama, tergantung banyaknya record tajuk yang harus disalin')?>
       </li>
       <li>
           <?= yii::t('app','Sebaiknya proses penyalinan dilakukan diluar jam kerja / pelayanan')?>
       </li>
    </ol>

</div>

<?php $this->registerJs('
    $("#salinOnline").click(function(e) {
        swal({
          title: "Harap tunggu!",
          text: "Proses penyalinan tajuk sedang berlangsung",
          
          showConfirmButton: false
        })
    });

    $(".fileinput-upload-button").click(function(){
        $(".alert-warning").show();
    });

    $(".kv-upload-progress").hide();
    ');
?>



