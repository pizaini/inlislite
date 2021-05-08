<?php
/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2015
 * @package _form.php
 * @version 1.0.0
 * @author Henry <alvin_vna@yahoo.com>
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

// Kartik Widgets
//use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\DatePicker;
use kartik\widgets\Typeahead;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use kartik\daterange\DateRangePicker;
use common\widgets\MaskedDatePicker;

// MODEL
use common\models\MasterJenisIdentitas;
use common\models\MasterJenjangPendidikan;
use common\models\JenisKelamin;
use common\models\MasterPekerjaan;
use common\models\MasterPendidikan;
use common\models\Jenisanggota;
use common\models\Agama;
use common\models\KelasSiswa;
use common\models\Departments;
use common\models\MasterJurusan;
use common\models\MasterFakultas;
use common\models\MasterProgramStudi;
use common\models\MasterStatusPerkawinan;
use common\models\JenisPermohonan;
use common\models\StatusAnggota;
use common\models\LocationLibrary;
use common\models\Collectioncategorys;

/**
 * @var yii\web\View $this
 * @var common\models\Members $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="members-form">

<?= $form->errorSummary($model); ?>
    <div class="col-sm-6">
        <?php
        // Tipe Nomor Anggota Otomatis / Manual.
        if(Yii::$app->config->get('TipeNomorAnggota') === 'Otomatis'){
            echo $form->field($model, 'MemberNo', [
                    /*'template' => '<span class="input-group-addon" style="width: 136px">' . $model->getAttributeLabel('MemberNo') . ' *</span>{input}',
                    'options'  => [
                        'class' => 'input-group form-group'
                    ],*/
                ])->textInput([
                    'placeholder' => $model->getAttributeLabel('MemberNo'),
                    'readonly'=>true,
                    'value'=>'Otomatis',
                    'style'=>'font-weight:bold;width:250px;',
                    'maxlength'=>255,
                 ]);
            //echo "<span style=\"margin-bottom:10px\">&nbsp;</span>";


        }else{
            echo $form->field($model, 'MemberNo', [
                        /*'template' => '<span class="input-group-addon" style="width: 136px">' . $model->getAttributeLabel('MemberNo') . ' *</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                ])->textInput([
                    'placeholder' => $model->getAttributeLabel('MemberNo'),
                    //'readonly'=>true,
                    //'value'=>$memberNo,
                    'style'=>'font-weight:bold;width:250px;',
                    'maxlength'=>255,
                 ]);
        }
        ?>


<?php
    //$val = \yii\helpers\ArrayHelper::getValue($membersForm, ['13']);
    //if(!is_null($val)){
?>

    <?=$form->field($model, 'IdentityType_id')->widget(Select2::classname(),[
                        'data'=>ArrayHelper::map(MasterJenisIdentitas::find()->all(),'id','Nama'),
                        'size'=>'sm',
                        'options'=>[

                                        'placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'Type Identity'),
                                   ],
                        'pluginOptions' => [

                                       'allowClear' => true,
                                       //'width'=> '150px',
                        ],
                    ]

                )->label(Yii::t('app','Jenis Identitas *'))?>


                <?=$form->field($model, 'IdentityNo', [
                   /* 'template' => '{input}',
                    'options'  => [
                        'class' => 'input-group form-group'
                    ],*/
                ])->textInput([
                    'placeholder' => 'Masukan Nomor Identitas',
                    //'style'=>'width:194px;',
                    'maxlength'=>255,
                 ])->label(Yii::t('app', 'Nomor Identitas *'))?>

<?php //}?>

        <?=$form->field($model, 'Fullname', [
                    /*'template' => '<span class="input-group-addon" style="width: 136px">' . $model->getAttributeLabel('Fullname') . ' *</span>{input}',
                    'options'  => [
                        'class' => 'input-group form-group'
                    ],*/
                ])->textInput([
                    'placeholder' => $model->getAttributeLabel('Fullname'),
                    'style'=>'width:354px;text-transform: uppercase',
                    'maxlength'=>255,
                 ])->label(Yii::t('app', 'Nama Lemgkap *'))?>

        <?=$form->field($model, 'PlaceOfBirth', [
                       /* 'template' => '<span class="input-group-addon" style="width: 136px">'.Yii::t('app','Tempat')." ,".Yii::t('app','Tgl.Lahir') .' *</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->textInput([
                        'placeholder' => $model->getAttributeLabel('PlaceOfBirth'),
                        //'style'=>'width:200px;',
                        'maxlength'=>255,
                     ])->label(Yii::t('app','Tempat Lahir')) ?>

                    <?=$form->field($model, 'TglLahir', [
                        /*'template' => '{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                            ],*/
                            ])->widget(MaskedDatePicker::classname(), [
                            'enableMaskedInput' => true,
                            'maskedInputOptions' => [
                                'mask' => '99-99-9999',
                                'pluginEvents' => [
                                    'complete' => "function(){console.log('complete');}"
                                ]
                            ],    
                            'removeButton' => false,
                            'options'=>[
                                'style'=>'width:170px',
                            ],
                            //'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                            'autoclose' => true,
                            'todayHighlight' => true,
                            'format'=>'dd-mm-yyyy',
                            // 'startDate'=> "01-03-2019",
                            'endDate'=> date("d-m-Y"),

                            ]
                            ])->label(Yii::t('app','Tanggal Lahir *')) ?>

        <?=$form->field($model, 'Address', [
                       /* 'template' => '<span class="input-group-addon" style="width: 136px">'.Yii::t('app','Address') .' *</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->textArea([
                        'placeholder' => Yii::t('app', 'Masukkan Alamat Sesuai KTP'),
                        'style'=>'width:350px;',
                        'maxlength'=>255,
                     ])?>
    
        
    <?php
            $val1 = common\components\MemberHelpers::customMemberForm(7);
            if($val1){
    ?>    
    <?php
    // List Propinsi
    $province_name = common\models\Propinsi::find()
        ->select(['(NamaPropinsi) as label'])
        ->asArray()
        ->all();
    ?>

    <?=$form->field($model, 'Province', [
                       /* 'template' => '<span class="input-group-addon" style="width: 136px">'.Yii::t('app','Propinsi').'</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->widget(AutoComplete::className(),
                    [
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => $model->getAttributeLabel('Province'),
                            'style'=>'width:300px;',
                            'maxlength'=>255,
                        ],
                        'clientOptions' => ['source' => $province_name]

                     ])?>
    <?php } ?>
    <?php
            $val2 = common\components\MemberHelpers::customMemberForm(6);
            if($val2){
    ?>    
    <?=$form->field($model, 'City', [
                        /*'template' => '<span class="input-group-addon" style="width: 136px">'.Yii::t('app','Kota').'</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->widget(AutoComplete::className(),
                    [
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => $model->getAttributeLabel('City'),
                            'style'=>'width:300px;',
                            'maxlength'=>255,
                        ],
                        'clientOptions' => [
                             'source' => new JsExpression('function(request, response) {
                                   $.ajax({
                                       url: "' . Url::to(['member/kabupaten-list']) . '",
                                       dataType: "json",
                                       data: {
                                           term: request.term,
                                           prop: $("#members-province").val()
                                       },
                                       success: function (data) {
                                               response(data);
                                       }
                                   })
                                }'),
                        ]

                     ])?>
            <?php } ?>


        <?php

        $valKecamatan = common\components\MemberHelpers::customMemberForm(39);
        if ($valKecamatan) {

            echo $form->field($model, 'Kecamatan')->textInput([
                'placeholder' => Yii::t('app', 'Kecamatan'),
                 'style'=>'width:300px;',
                'maxlength' => 255,
            ]);
        }
        ?>


        <?php

        $valKel = common\components\MemberHelpers::customMemberForm(40);
        if ($valKel) {

            echo $form->field($model, 'Kelurahan')->textInput([
                'placeholder' => Yii::t('app', 'Kelurahan'),
                'style'=>'width:300px;',
                'maxlength' => 255,
            ]);
        }
        ?>

        <?php

        $valRT = common\components\MemberHelpers::customMemberForm(41);
        if ($valRT) {

            echo $form->field($model, 'RT')->textInput([
                'placeholder' => Yii::t('app', 'RT'),
                'style'=>'width:300px;',
                'maxlength' => 255,
            ]);
        }
        ?>

        <?php

        $valRW = common\components\MemberHelpers::customMemberForm(42);
        if ($valRW) {

            echo $form->field($model, 'RW')->textInput([
                'placeholder' => Yii::t('app', 'RW'),
                'style'=>'width:300px;',
                'maxlength' => 255,
            ]);
        }
        ?>
        <div class="form-group field-members-rw">
            <label class="control-label col-md-3" for="members-rw"></label>
            <div class="col-md-9">
                <label><?=Html::checkbox('duplicateAddrs',false,['id'=>'duplicateAddrs'])?> <?= yii::t('app','Alamat tinggal sama dengan alamat Identitas');?></label>
            </div>
            <div class="col-md-offset-3 col-md-9"></div>

        </div>
         
     <?php
            $val3 = common\components\MemberHelpers::customMemberForm(8);
            if($val3){
       ?>    
    <?=$form->field($model, 'AddressNow', [
                        /*'template' => '<span class="input-group-addon" style="width: 136px">'.Yii::t('app','Alamat Tempat').'<br/>'.Yii::t('app','Tinggal saat ini') .'</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->textArea([
                        'placeholder' => Yii::t('app', 'Masukkan Alamat Tempat Tinggal Sekarang'),
                        'style'=>'width:350px;',
                        'maxlength'=>255,
                     ])->label(Yii::t('app','Alamat Tempat Tinggal Saat Ini'))?>
            <?php } ?>
<?php
    $valPropinsi = common\components\MemberHelpers::customMemberForm(10);
    if($valPropinsi){
?>
    <?=$form->field($model, 'ProvinceNow', [
                        /*'template' => '<span class="input-group-addon" style="width: 136px">'.Yii::t('app','Propinsi saat ini').'</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->widget(AutoComplete::className(),
                    [
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => Yii::t('app','Masukan propinsi saat ini'),
                            'style'=>'width:300px;',
                            'maxlength'=>255,
                        ],
                        'clientOptions' => ['source' => $province_name]

                     ])->label(Yii::t('app','Propinsi Tinggal Sekarang'))?>
<?php }?>

<?php
    $valKota = common\components\MemberHelpers::customMemberForm(9);
    if($valKota){
?>
    <?=$form->field($model, 'CityNow', [
                       /* 'template' => '<span class="input-group-addon" style="width: 136px">'.Yii::t('app','Kota saat ini').'</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->widget(AutoComplete::className(),
                    [
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => Yii::t('app','Masukkan Kabupaten/Kota saat ini'),
                            'style'=>'width:300px;',
                            'maxlength'=>255,
                        ],
                        'clientOptions' => [
                             'source' => new JsExpression('function(request, response) {
                                   $.ajax({
                                       url: "' . Url::to(['member/kabupaten-list']) . '",
                                       dataType: "json",
                                       data: {
                                           term: request.term,
                                           prop: $("#members-provincenow").val()
                                       },
                                       success: function (data) {
                                               response(data);
                                       }
                                   })
                                }'),
                        ]

                     ])->label(Yii::t('app','Kabupaten/Kota'))?>
<?php }?>

        <?php

        $valKecamatanNow = common\components\MemberHelpers::customMemberForm(43);
        if ($valKecamatanNow) {

            echo $form->field($model, 'KecamatanNow')->textInput([
                'placeholder' => Yii::t('app', 'Kecamatan saat ini'),
                'style'=>'width:300px;',
                'maxlength' => 255,
            ]);
        }
        ?>

        <?php

        $valKelNow = common\components\MemberHelpers::customMemberForm(44);
        if ($valKelNow) {

            echo $form->field($model, 'KelurahanNow')->textInput([
                'placeholder' => Yii::t('app', 'Kelurahan saat ini'),
                'style'=>'width:300px;',
                'maxlength' => 255,
            ]);
        }
        ?>

        <?php

        $valRTNow = common\components\MemberHelpers::customMemberForm(45);
        if ($valRTNow) {

            echo $form->field($model, 'RTNow')->textInput([
                'placeholder' => Yii::t('app', 'RT saat ini'),
                'style'=>'width:300px;',
                'maxlength' => 255,
            ]);
        }
        ?>


        <?php

        $valRWNow = common\components\MemberHelpers::customMemberForm(46);
        if ($valRWNow) {

            echo $form->field($model, 'RWNow')->textInput([
                'placeholder' => Yii::t('app', 'RW saat ini'),
                'style'=>'width:300px;',
                'maxlength' => 255,
            ]);
        }
        ?>


 <?php
            $val4 = common\components\MemberHelpers::customMemberForm(11);
            if($val4){
        ?>
    <?=$form->field($model, 'NoHp', [
                    /*'template' => '<span class="input-group-addon" style="width: 136px">' . $model->getAttributeLabel('NoHp') . '</span>{input}',
                    'options'  => [
                        'class' => 'input-group form-group'
                    ],*/
                ])->textInput([
                    'placeholder' => $model->getAttributeLabel('NoHp'),
                    'style'=>'width:350px;',
                    'maxlength'=>255,
                 ])?>
<?php }?>


 <?php
            $val5 = common\components\MemberHelpers::customMemberForm(12);
            if($val5){
        ?>
    <?=$form->field($model, 'Phone', [
                    /*'template' => '<span class="input-group-addon" style="width: 136px">' . $model->getAttributeLabel('Phone') . '</span>{input}',
                    'options'  => [
                        'class' => 'input-group form-group'
                    ],*/
                ])->textInput([
                    'placeholder' => $model->getAttributeLabel('Phone'),
                    'style'=>'width:350px;',
                    'maxlength'=>255,
                 ])?>
<?php }?>


 <?php
            $val6 = common\components\MemberHelpers::customMemberForm(15);
            if($val6){
        ?>

     <?=$form->field($model, 'Sex_id', [
                      /*  'template' => '<span class="input-group-addon" style="width: 136px">'.Yii::t('app','Jenis Kelamin').'</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->widget(Select2::classname(),[
                        'data'=>ArrayHelper::map(JenisKelamin::find()->all(),'ID','Name'),
                        'size'=>'sm',
                        'options'=> [
                                        'placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'Jenis Kelamin'),
                                   ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'width'=> '150px',
                                    ],
                    ]

                )->label(Yii::t('app', 'Jenis Kelamin *'))?>
<?php } ?>

 <?php
            $val7 = common\components\MemberHelpers::customMemberForm(19);
            if($val7){
        ?>
     <?=$form->field($model, 'EducationLevel_id', [
                      /*  'template' => '<span class="input-group-addon" style="width: 136px">'.Yii::t('app','EducationLevel_id').'<br/>'.Yii::t('app','EducationLevel_id1').'</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->widget(Select2::classname(),[
                        'data'=>ArrayHelper::map(MasterPendidikan::find()->all(),'id','Nama'),
                        'size'=>'sm',
                        'options'=> [
                                        'placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'EducationLevel_id'),
                                   ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'width'=> '150px',
                                    ],
                    ]

                )->label(Yii::t('app', 'Pendidikan'))?>

<?php } ?>

 <?php
            $val8 = common\components\MemberHelpers::customMemberForm(16);
            if($val8){
 ?>
     <?=$form->field($model, 'Job_id', [
                        /*'template' => '<span class="input-group-addon" style="width: 136px">'.Yii::t('app','Job_id').'</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->widget(Select2::classname(),[
                        'data'=>ArrayHelper::map(MasterPekerjaan::find()->all(),'id','Pekerjaan'),
                        'size'=>'sm',
                        'options'=> [
                                        'placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'Job_id'),
                                   ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'width'=> '150px',
                                    ],
                    ]

                )->label(Yii::t('app', 'Pekerjaan'))?>

<?php } ?>

 <?php
            $val9 = common\components\MemberHelpers::customMemberForm(29);
            if($val9){
        ?>
    <?=$form->field($model, 'Email', [
                   /* 'template' => '<span class="input-group-addon" style="width: 136px">' . Yii::t('app','Email') . '</span>{input}',
                    'options'  => [
                        'class' => 'input-group form-group'
                    ],*/
                ])->textInput([
                    'placeholder' =>  Yii::t('app','Email'),
                    'style'=>'width:350px;',
                    'maxlength'=>255,
                 ])->label(Yii::t('app', 'Email'))?>

<?php } ?>

 <?php
            $val10 = common\components\MemberHelpers::customMemberForm(25);
            if($val10){
        ?>
    <?=$form->field($model, 'MotherMaidenName', [
                  /*  'template' => '<span class="input-group-addon" style="width: 136px">' . Yii::t('app','MotherMaidenName') . '</span>{input}',
                    'options'  => [
                        'class' => 'input-group form-group'
                    ],*/
                ])->textInput([
                    'placeholder' =>  Yii::t('app','Enter') . ' '. Yii::t('app','MotherMaidenName'),
                    'style'=>'width:350px;',
                    'maxlength'=>255,
                 ])->label(Yii::t('app', 'Ibu Kandung'))?>
<?php } ?>


    
 <?php
            $val11 = common\components\MemberHelpers::customMemberForm(26);
            if($val11){
        ?>
    <br/>
    <br/>
    <legend><?php echo Yii::t('app', 'Data Pekerjaan / Perguruan Tinggi / Sekolah :') ?></legend>
    <?=$form->field($model, 'InstitutionName', [
                   /* 'template' => '<span class="input-group-addon" style="width: 136px">' . Yii::t('app','InstitutionName') . '</span>{input}',
                    'options'  => [
                        'class' => 'input-group form-group'
                    ],*/
                ])->textInput([
                    'placeholder' =>  Yii::t('app','InstitutionName'),
                    'style'=>'width:350px;',
                    'maxlength'=>255,
                 ])->label(Yii::t('app', 'InstitutionName'))?>

<?php }?>

 <?php
            $val12 = common\components\MemberHelpers::customMemberForm(27);
            if($val12){
        ?>

    <?=$form->field($model, 'InstitutionAddress', [
                       /* 'template' => '<span class="input-group-addon" style="width: 136px">'.Yii::t('app','InstitutionAddress') .'</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->textArea([
                        'placeholder' => Yii::t('app','InstitutionAddress'),
                        'style'=>'width:350px;',
                        'maxlength'=>255,
                     ])->label(Yii::t('app','InstitutionAddress'))?>
<?php } ?>

 <?php
            $val13 = common\components\MemberHelpers::customMemberForm(28);
            if($val13){
        ?>

    <?=$form->field($model, 'InstitutionPhone', [
                    /*'template' => '<span class="input-group-addon" style="width: 136px">' . Yii::t('app','InstitutionPhone') . '</span>{input}',
                    'options'  => [
                        'class' => 'input-group form-group'
                    ],*/
                ])->textInput([
                    'placeholder' =>  Yii::t('app','InstitutionPhone'),
                    'style'=>'width:350px;',
                    'maxlength'=>20,
                 ])->label(Yii::t('app','InstitutionPhone'))?>

<?php } ?>
   

 <?php
            $val14 = common\components\MemberHelpers::customMemberForm(30);
            if($val14){
        ?>
     <br/>
    <br/>
    <legend><?php echo Yii::t('app', 'Dalam keadaan darurat pihak yang dapat dihubungi :') ?></legend>
    <?=$form->field($model, 'NamaDarurat', [
                   /* 'template' => '<span class="input-group-addon" style="width: 136px">' . Yii::t('app','NamaDarurat') . '</span>{input}',
                    'options'  => [
                        'class' => 'input-group form-group'
                    ],*/
                ])->textInput([
                    'placeholder' =>  Yii::t('app','NamaDarurat'),
                    'style'=>'width:350px;',
                    'maxlength'=>255,
                 ])->label(Yii::t('app','Nama Darurat'))?>

<?php } ?>

 <?php
            $val15 = common\components\MemberHelpers::customMemberForm(31);
            if($val15){
        ?>
    <?=$form->field($model, 'AlamatDarurat', [
                        /*'template' => '<span class="input-group-addon" style="width: 136px">'.Yii::t('app','AlamatDarurat') .'</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->textArea([
                        'placeholder' => Yii::t('app','AlamatDarurat'),
                        'style'=>'width:350px;',
                        'maxlength'=>255,
                     ])->label(Yii::t('app','Alamat Darurat'))?>

<?php } ?>

 <?php
            $val16 = common\components\MemberHelpers::customMemberForm(32);
            if($val16){
        ?>

    <?=$form->field($model, 'TelpDarurat', [
                    /*'template' => '<span class="input-group-addon" style="width: 136px">' . Yii::t('app','TelpDarurat') . '</span>{input}',
                    'options'  => [
                        'class' => 'input-group form-group'
                    ],*/
                ])->textInput([
                    'placeholder' =>  Yii::t('app','TelpDarurat'),
                    'style'=>'width:350px;',
                    'maxlength'=>20,
                 ])->label(Yii::t('app','Telp Darurat'))?>

<?php } ?>

 <?php
            $val17 = common\components\MemberHelpers::customMemberForm(33);
            if($val17){
        ?>
    <?=$form->field($model, 'StatusHubunganDarurat', [
                   /* 'template' => '<span class="input-group-addon" style="width: 136px">' . Yii::t('app','StatusHubunganDarurat') . '</span>{input}',
                    'options'  => [
                        'class' => 'input-group form-group'
                    ],*/
                ])->textInput([
                    'placeholder' =>  Yii::t('app','StatusHubunganDarurat'),
                    'style'=>'width:350px;',
                    'maxlength'=>255,
                 ])->label(Yii::t('app','Status Hubungan Darurat'))?>
<?php } ?>
    </div>

    <!-- KOLOM KANAN-->
    <div class="col-sm-6">

 <?php
            $val18 = common\components\MemberHelpers::customMemberForm(34);
            if($val18){
        ?>
    <?=$form->field($model, 'TahunAjaran', [
                   /* 'template' => '<span class="input-group-addon" style="width: 144px">' . Yii::t('app','tahunAjaran') . '</span>{input}',
                    'options'  => [
                        'class' => 'input-group form-group'
                    ],*/
                ])->textInput([
                    'placeholder' =>  Yii::t('app','tahun Ajaran'),
                    'style'=>'width:150px;',
                    'maxlength'=>255,
                 ])->label(Yii::t('app','tahun Ajaran'))?>
<?php } ?>

 <?php
            $val19 = common\components\MemberHelpers::customMemberForm(35);
            if($val19){
        ?>

    <?=$form->field($model, 'Kelas_id', [
                        /*'template' => '<span class="input-group-addon" style="width: 144px">'.Yii::t('app','Kelas_id').'</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->widget(Select2::classname(),[
                        'data'=>ArrayHelper::map(KelasSiswa::find()->all(),'id','namakelassiswa'),
                        'size'=>'sm',
                        'options'=> [
                                        'placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'Kelas'),
                                   ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'width'=> '150px',
                                    ],
                    ]

                )->label(Yii::t('app','Kelas'))?>

<?php } ?>

 <?php
            $val20 = common\components\MemberHelpers::customMemberForm(18);
            if($val20){

        ?>

    <?=$form->field($model, 'JenisAnggota_id', [
                      /*  'template' => '<span class="input-group-addon" style="width: 144px">'.Yii::t('app','JenisAnggota_id').'</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->widget(Select2::classname(),[
                        'data'=>ArrayHelper::map(JenisAnggota::find()->all(),'id','jenisanggota'),
                        'size'=>'sm',
                        'options'=> [
                                'placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'JenisAnggota_id'),
                                'onchange'=>'$.post( "'.Yii::$app->urlManager->createUrl(["member/member/testing"]).'",{ id: $("#members-jenisanggota_id").val()},function(data,status){
                                        if(status == "success"){
                                            $("#test_div").html(data);
                                        }

                                    })'
                                        //'placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'JenisAnggota_id'),
                                   ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'width'=> '150px',
                                    ],
                        'pluginEvents' => [
                            "select2:select" => 'function() {

                                    $.get("masa-berlaku?jenis="+$("#members-jenisanggota_id").val()+"&registerDate="+$("#members-tglregisterdate").val(), function( data ) {
                                            console.log(data);
                                            $("#masa-berlaku").text($("#members-tglregisterdate").val() + " s.d " + data);
                                             $("#members-tglenddate").val(data);

                                        });
                                        console.log("complete");

                                   $.post( "'.Yii::$app->urlManager->createUrl(["member/member/get-biaya-pendaftaran"]).'",{ id: $("#members-jenisanggota_id").val()},function(data,status){
                                        if(status == "success"){
                                            $("#members-biayapendaftaran").val(data);
                                            if(data == 0){
                                                $("#biaya").hide();
                                            }else{
                                                $("#biaya").show();
                                            }

                                            //alert(data);
                                        }

                                    })
                            }',
                        ]
                    ]

                )->label(Yii::t('app','Jenis Anggota *'))?>

<?php } ?>

 <?php
            $val21 = common\components\MemberHelpers::customMemberForm(20);
            if($val21){
        ?>
    <?=$form->field($model, 'MaritalStatus_id', [
                        /*'template' => '<span class="input-group-addon" style="width: 144px">'.Yii::t('app','MaritalStatus_id').'</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->widget(Select2::classname(),[
                        'data'=>ArrayHelper::map(MasterStatusPerkawinan::find()->all(),'id','Nama'),
                        'size'=>'sm',
                        'options'=> [
                                        'placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'MaritalStatus_id'),
                                   ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'width'=> '150px',
                                    ],
                    ]

                )->label(Yii::t('app','MaritalStatus_id'))?>

<?php } ?>

 <?php
            $val22 = common\components\MemberHelpers::customMemberForm(17);
            if($val22){
        ?>
     <?=$form->field($model, 'Agama_id', [
                       /* 'template' => '<span class="input-group-addon" style="width: 144px">'.Yii::t('app','Agama_id').'</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->widget(Select2::classname(),[
                        'data'=>ArrayHelper::map(Agama::find()->all(),'ID','Name'),
                        'size'=>'sm',
                        'options'=> [
                                        'placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'Agama_id'),
                                   ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'width'=> '150px',
                                    ],
                    ]

                )->label(Yii::t('app','Agama_id'))?>
<?php } ?>

 <?php
            $val23 = common\components\MemberHelpers::customMemberForm(36);
            if($val23){
        ?>
    <?=$form->field($model, 'UnitKerja_id', [
                        /*'template' => '<span class="input-group-addon" style="width: 144px">'.Yii::t('app','UnitKerja_id').'</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->widget(Select2::classname(),[
                        'data'=>ArrayHelper::map(Departments::find()->all(),'ID','Name'),
                        'size'=>'sm',
                        'options'=> [
                                        'placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'UnitKerja_id'),
                                   ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'width'=> '150px',
                                    ],
                    ]

                )->label(Yii::t('app','UnitKerja_id'))?>

<?php } ?>

 <?php
            $val24 = common\components\MemberHelpers::customMemberForm(37);
            if($val24){
        ?>
    <?=$form->field($model, 'Fakultas_id', [
                       /* 'template' => '<span class="input-group-addon" style="width: 144px">'.Yii::t('app','Fakultas_id').'</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->widget(Select2::classname(),[
                        'data'=>ArrayHelper::map(MasterFakultas::find()->all(),'id','Nama'),
                        'size'=>'sm',
                        'options'=> [
                                        'placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'Fakultas_id'),
                                   ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'width'=> '150px',
                                    ],
                    ]

                )->label(Yii::t('app','Fakultas_id'))?>

<?php } ?>

 <?php
            $val25 = common\components\MemberHelpers::customMemberForm(38);
            if($val25){
        ?>
    <?=$form->field($model, 'Jurusan_id', [
                        /*'template' => '<span class="input-group-addon" style="width: 144px">'.Yii::t('app','Jurusan_id').'</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
            ])->widget(DepDrop::classname(), [
            'type'=>DepDrop::TYPE_SELECT2,
            'data'=>ArrayHelper::map(MasterJurusan::find()->all(),'id','Nama'),
            //'size'=>'sm',
            'options'=>[
                'id'=>'Jurusan',
                 'placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'Jurusan_id'),
            ],
            'select2Options'=>
                [
                    'pluginOptions'=>[
                            'allowClear'=>true,
                            'width'=> '150px',
                        ]
                ],
            'pluginOptions'=>[
                'loadingText'=>'Please wait...',
                'placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'Jurusan_id'),
                'depends'=>['members-fakultas_id'],
                'url'=>Url::to(['member/jurusan']),
            ]
        ]
    )->label(Yii::t('app','Jurusan_id'))?>
<?php } ?>

<?php
            $val48 = common\components\MemberHelpers::customMemberForm(48);
            if($val48){
        ?>
    <?=$form->field($model, 'ProgramStudi_id', [
                        /*'template' => '<span class="input-group-addon" style="width: 144px">'.Yii::t('app','Jurusan_id').'</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
            ])->widget(DepDrop::classname(), [
            'type'=>DepDrop::TYPE_SELECT2,
            'data'=>ArrayHelper::map(MasterProgramStudi::find()->all(),'id','Nama'),
            //'size'=>'sm',
            'options'=>[
                'id'=>'ProgramStudi',
                 'placeholder'=>Yii::t('app', 'Choose').' Program Studi',
            ],
            'select2Options'=>
                [
                    'pluginOptions'=>[
                            'allowClear'=>true,
                            'width'=> '150px',
                        ]
                ],
            'pluginOptions'=>[
                'loadingText'=>'Please wait...',
                'placeholder'=>Yii::t('app', 'Choose').' Program Studi',
                'depends'=>['Jurusan'],
                'url'=>Url::to(['member/prodi']),
            ]
        ]
    )->label(Yii::t('app','Program Studi'))?>
<?php } ?>



<?php
            $val49 = common\components\MemberHelpers::customMemberForm(49);
            if($val49){
        ?>

    <?=$form->field($model, 'JenjangPendidikan_id', [
                    ])->widget(Select2::classname(),[
                        'data'=>ArrayHelper::map(MasterJenjangPendidikan::find()->all(),'ID','jenjang_pendidikan'),
                        'size'=>'sm',
                        'options'=> [
                                        'placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'JenjangPendidikan_id'),
                                   ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'width'=> '150px',
                                    ],
                    ]

                )->label(Yii::t('app','JenjangPendidikan_id'))?>

        <?php } ?>


    <?=$form->field($model, 'TglRegisterDate', [
                       /*'template' => '<span class="input-group-addon" style="width: 144px">'.Yii::t('app','RegisterDate').' *</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->widget(MaskedDatePicker::classname(), [
                        'enableMaskedInput' => true,
                            'maskedInputOptions' => [
                                'mask' => '99-99-9999',
                               'pluginEvents' => [
                                    'complete' => "function(){
                                       $.get('masa-berlaku?jenis='+$('#members-jenisanggota_id').val()+'&registerDate='+$('#members-tglregisterdate').val(), function( data ) {
                                            console.log(data);
                                            $('#masa-berlaku').text($('#members-tglregisterdate').val() +' s.d ' + data);
                                             $('#members-tglenddate').val(data);

                                        });
                                        console.log('complete');
                                    }"
                                ]
                            ],    
                        'removeButton' => false,
                        'options'=>[
                                'style'=>'width:150px',

                            ],
                        //'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'todayHighlight' => true,
                                'format'=>'dd-mm-yyyy',
                            ],
                    ])->label(Yii::t('app','RegisterDate'))?>
     <!-- Masa Berlaku -->
    <div class="form-group field-members-tglenddate1 required">
    <label class="control-label col-md-3" for="members-tglenddate1"><?= yii::t('app','Masa Berlaku')?></label>
    <div class="col-md-9">

       <label id="masa-berlaku"><?= date('d-m-Y')?> s.d <?= $endDate?></label>
       
    </div>
    <div class="col-md-offset-3 col-md-9"></div>

    </div>
    <div id="endField">
     <!-- ./Masa Berlaku -->   
     <?=$form->field($model, 'TglEndDate', [
                       /*'template' => '<span class="input-group-addon" style="width: 144px">'.Yii::t('app','RegisterDate').' *</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->widget(MaskedDatePicker::classname(), [
                        'enableMaskedInput' => true,
                            'maskedInputOptions' => [
                                'mask' => '99-99-9999',
                                'pluginEvents' => [
                                    'complete' => "function(){console.log('complete');}"
                                ]
                            ],    
                        'removeButton' => false,
                        'options'=>[
                                'style'=>'width:150px'
                            ],
                        //'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'todayHighlight' => true,
                                'format'=>'dd-mm-yyyy'
                            ]
                    ])->label(Yii::t('app','EndDate'))?>
    </div>
    <?php
// JENIS PERPUSTAKAAN
/*
    $form->field($model, 'JenisPermohonan_id', [
                        'template' => '<span class="input-group-addon" style="width: 141px">'.Yii::t('app','JenisPermohonan_id').' *</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],
                    ])->widget(Select2::classname(),[
                        'data'=>ArrayHelper::map(JenisPermohonan::find()->all(),'ID','Name'),
                        'options'=> [
                                        'placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'JenisPermohonan_id'),
                                   ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'width'=> '150px',
                                    ],
                    ]

                )->label(Yii::t('app','JenisPermohonan_id'))

                    */
        ?>

    <?=$form->field($model, 'StatusAnggota_id', [
                        /*'template' => '<span class="input-group-addon" style="width: 144px">'.Yii::t('app','StatusAnggota_id').' *</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->widget(Select2::classname(),[
                        'data'=>ArrayHelper::map(StatusAnggota::find()->all(),'id','Nama'),
                        'size'=>'sm',
                        'options'=> [
                                        'placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'StatusAnggota_id'),
                                   ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'width'=> '150px',
                                    ],
                    ]

                )->label(Yii::t('app','StatusAnggota_id'))?>

    <?=$form->field($model, 'KeteranganLain', [

                    ])->textArea([
                        'placeholder' => Yii::t('app', 'Keterangan Lain'),
                        'style'=>'width:350px;',
                        'maxlength'=>255,
                     ])->label(Yii::t('app','Keterangan Lain'))?>
<?php

    if($pendaftaran == "0"){
        $div = "style=\"display:none\"";
    }else{
        $div = "";
    }
?>
    <div id="biaya" <?=$div?>>
    <?=$form->field($model, 'BiayaPendaftaran', [

                       /* 'template' => '<span class="input-group-addon" style="width: 144px">' . Yii::t('app','tahunAjaran') . '</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->textInput([
                        'placeholder' =>  Yii::t('app','BiayaPendaftaran'),
                        'style'=>'width:150px;',
                        'maxlength'=>255,
                        'value'=>$pendaftaran
                     ])->label(Yii::t('app','BiayaPendaftaran'))?>

     <?=$form->field($model, 'IsLunasBiayaPendaftaran', [
                       /* 'template' => '<span class="input-group-addon" style="width: 144px">' . Yii::t('app','tahunAjaran') . '</span>{input}',
                        'options'  => [
                            'class' => 'input-group form-group'
                        ],*/
                    ])->checkbox([
                        //'placeholder' =>  Yii::t('app','IsLunasBiayaPendaftaran'),
                        //'style'=>'width:150px;',
                        //'maxlength'=>255,
                        //'value'=>$pendaftaran
                     ])?>
    </div>

    <br/>
    <legend>&nbsp;</legend>

        <div id="test_div">
            <h5>Lokasi Pinjam</h5>
            <div class="klas test_div" style="background-color: #ffffb5"><input id="selectAll-lok" type="checkbox"> &nbsp;<label for='selectAll'>Select All</label></div>
            <?= $form->field($model,'locationCategory',[
                    //'options'=>[ 'style'=>'width: 821px;']
                ])->checkboxList(ArrayHelper::map(LocationLibrary::find()->all(),'ID','Name'))
                     ->label(false)?>
<br>
            <h5>Koleksi yang dapat dipinjam</h5>
            <div class="klas test_div" style="background-color: #ffffb5"><input id="selectAll-kol" type="checkbox"> &nbsp;<label for='selectAll'>Select All</label></div>
            <?= $form->field($model,'collectionCategory',[
                    //'options'=>[ 'style'=>'width: 821px;']
                ])->checkboxList(
                                ArrayHelper::map(Collectioncategorys::find()->all(),'ID','Name'))
                     ->label(false)?>
        </div>

    </div>
    


</div>
<?php
$this->registerJs("
    $('#duplicateAddrs').click(function() {
    if(this.checked){
        $('#members-addressnow').val($('#members-address').val());
        $('#members-provincenow').val($('#members-province').val());
        $('#members-citynow').val($('#members-city').val());
        $('#members-kecamatannow').val($('#members-kecamatan').val());
        $('#members-kelurahannow').val($('#members-kelurahan').val());
        $('#members-rtnow').val($('#members-rt').val());
        $('#members-rwnow').val($('#members-rw').val());
    }
});

$('#selectAll-lok').click(function(){
        $('#members-locationcategory').find('input[type=checkbox]').prop('checked', $(this).prop('checked'));
    
});
$('#selectAll-kol').click(function(){
        $('#members-collectioncategory').find('input[type=checkbox]').prop('checked', $(this).prop('checked'));
    
});
    ");
?>