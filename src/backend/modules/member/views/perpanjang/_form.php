<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\jui\AutoComplete;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use common\widgets\MaskedDatePicker;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use common\models\JenisAnggota;
/**
 * @var yii\web\View $this
 * @var common\models\MemberPerpanjangan $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="member-perpanjangan-form">
    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'formConfig' => ['labelSpan' => 4, 'deviceSize' => ActiveForm::SIZE_SMALL]]); ?>
    <div class="page-header">
        <!-- Button -->
        <?php
        echo Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-primary']);
        echo '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']);
        ?>
        <!-- ./Button -->
    </div>
        <!-- <div class="col-md-1">
            
        </div> -->
        <div class="col-md-6">
            
              <?php
                $member_name = (new \yii\db\Query())
                        ->from('members')
                        //\common\models\Members::find()
                        ->select(['(CONCAT(MemberNo," - ",Fullname)) as label'])
                        //->asArray()
                        ->all();

                echo $form->field($model, 'Member_id', ['addon' => ['append' => ['content' => '<button type="button" class="btn btn-sm btn-danger" id="cari-anggota">Cari</button>', 'asButton'=> true]]
                ])->widget(AutoComplete::className(), [
                    'options' => [
                        'class' => 'form-control',
                        'placeholder' => 'Masukan Anggota',
                        'style' => 'width:300px;',
                        'maxlength' => 255,
                    ],
                    'clientOptions' => ['source' => $member_name]
                ])->label(Yii::t('app', 'Anggota'));
                ?>        
              <!-- <span class="input-group-btn">
                <button class="btn btn-danger" type="button" style="margin-top: -16%">Cari</button>
              </span> -->
            
            

            <div id="check">
                <div id="member">
                </div>   

                <div id="paijo">
                </div> 
             
                <div class="form-group field-memberperpanjangan-biaya" id="MasaBerlaku" >
                    <label class="control-label col-md-4" for="memberperpanjangan-biaya">Masa Berlaku Anggota Saat Ini </label>
                    <div class="col-md-8">
                        <label  id="IsiMasaBerlaku" class="control-label" style="font-size: 13px;" for="memberperpanjangan-biaya"></label>

                    </div>
                </div>

                <?php
                echo $form->field($model, 'Biaya')->textInput([
                    'placeholder' => $model->getAttributeLabel('Biaya'),
                    //'readonly'=>true,
                    //'value'=>$memberNo,
                    'style' => 'font-weight:bold;width:250px;',
                    'type' => 'number',
                    'maxlength' => 10
                ]);


                echo $form->field($model, 'Tanggal')->widget(MaskedDatePicker::classname(), [
                    'enableMaskedInput' => true,
                    'maskedInputOptions' => [
                        'mask' => '99-99-9999',
                        'pluginEvents' => [
                            'complete' => "function(){console.log('complete');}"
                        ]
                    ],
                    'removeButton' => false,
                    'options' => [
                        'style' => 'width:170px',
                    ],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'format' => 'dd-mm-yyyy',
                    ]
                ])->label(Yii::t('app', 'Tanggal Berakhir'));
                ?>

                <div class="form-group field-members-rw">
                    <label class="control-label col-md-4" for="members-rw">Status Pelunasan</label>
                    <div class="col-md-8">
                        <label><?=Html::activeCheckbox($model,'IsLunas')?></label>
                    </div>
                    <div class="col-md-offset-3 col-md-9"></div>

                </div>

                <?=$form->field($model, 'Keterangan', [
                ])->textArea([
                    'placeholder' => Yii::t('app', 'Keterangan'),
                    'style' => 'width:350px;',
                    'maxlength' => 255,
                ]);?>        
            </div>
        </div>
        <div class="col-md-6">
            <div id="gmb_anggota" style="margin-top : 12%">
            </div>
        </div>
    <?php ActiveForm::end();?>
</div>

    
    <?php
    $this->registerJs("


    	$(document).ready(function(){
            $('#MasaBerlaku').hide();
            $('#check').hide();
    	 	$('#memberperpanjangan-member_id').keyup(function(e){
                if(e.keyCode == 13)
                {
                    validasi();
                }
            });

            $('#cari-anggota').click(function(){
                validasi();
            });
        });

        function validasi(){
             var NoAnggota = $('#memberperpanjangan-member_id').val();
             var res = NoAnggota.split('-');
             var name = NoAnggota.replace(/[!#$%&()*+,.\/:;<=>?@[\]^`{|}~]/g, '')
             var result = $(name).text().split('-');
             $.getJSON('check-membership',{ memberNo : res[0] },function(data){
            
                    $('#MasaBerlaku').show();
                    $('#check').show();
                    $('#IsiMasaBerlaku').html(data.EndDate);
                    $('#memberperpanjangan-biaya').val(data.Biaya);
                    $('#memberperpanjangan-tanggal').val(data.Expired);
                    //$('#members-jenisanggota').val('1');
                    //$('#members-jenisanggota').select2('val', 'test gan');


            $.ajax({
                type: 'POST',
                cache: false,
                url: '".Yii::$app->urlManager->createUrl(["member/perpanjang/jenis-anggota"])."?id='+res[0],
                success: function (response) {
                    $('#paijo').html(response);
                }
            });

            $.ajax({
                type: 'POST',
                cache: false,
                url: '".Yii::$app->urlManager->createUrl(["member/perpanjang/get-member"])."?id='+res[0],
                success: function (response) {
                    $('#member').html(response);
                }
            });

                    //alert(data.jenisAnggota);
                
            }).error(function(jqXHR) {
                if (jqXHR.status == 404) {
                    $('#MasaBerlaku').hide();
                    $('#check').hide();
                    $('#memberperpanjangan-member_id').val('');
                    $('#memberperpanjangan-member_id').focus();
                    // alert(\"No.Anggota tidak ditemukan.\");
                    sweetAlert(\"Maaf..\", \"No.Anggota tidak ditemukan\", \"error\");
                } else {
                    alert(\"Other non-handled error type\");
                }
            });
        }

    ");
    ?>

    <?php
echo \common\widgets\Histori::widget([
    'model' => $model,
    'id' => 'memebr_perpanjangan',
    'urlHistori' => 'detail-histori?id=' . $model->ID
]);
?>
