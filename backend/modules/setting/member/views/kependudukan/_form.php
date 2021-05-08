<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\widgets\DatePicker;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;

use common\models\Agama;
use common\models\MasterPendidikan;
use common\models\MasterPekerjaan;
/**
 * @var yii\web\View $this
 * @var common\models\JenisPerpustakaan $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<style type="text/css">
    .jenis-perpustakaan-form > .col-sm-6 > .form-horizontal > .form-group >.col-sm-offset-3{
        margin-left: 0px;
    }
</style>


<div class="jenis-perpustakaan-form">
    <div class="col-sm-6">
        <?php
        $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]]);
        
        echo '<div class="page-header">';
        echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
        echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) ;
        echo '</div>';
    
        echo Form::widget([

            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'nomorkk' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Nomor KK') . '...', 'maxlength' => 50]],
                'nik' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'NIK') . '...', 'maxlength' => 50]],
                'namalengkap' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Nama Lengkap') . '...', 'maxlength' => 100]],
                'nama_ibu' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Nama Ibu') . '...', 'maxlength' => 100]],
                'alamat' => ['type' => Form::INPUT_TEXTAREA, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Alamat') . '...', 'rows' => 2]],
                'rt' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'RT') . '...', 'maxlength' => 50]],
                'rw' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'RW') . '...', 'maxlength' => 50]],
                

                // 'al1' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'kelurahan/ desa') . '...', 'maxlength' => 255]],
                'kodekec' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Kode Kecamatan') . '...', 'maxlength' => 50]],
                'kodekel' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Kode Kelurahan') . '...', 'maxlength' => 50]],
                'nama_kec' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Nama Kecamatan') . '...', 'maxlength' => 50]],
                'nama_kel' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Nama Kelurahan') . '...', 'maxlength' => 50]],
                'nama_kab' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Nama Kabupaten') . '...', 'maxlength' => 50]],
                'nama_prov' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Nama Provinsi') . '...', 'maxlength' => 50]],
                'lhrtempat' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Tempat Lahir') . '...', 'maxlength' => 50]],

                'lhrtanggal'=>['type'=> Form::INPUT_WIDGET,'label'=> Yii::t('app', 'Tanggal Lahir'),'widgetClass' => DatePicker::classname(), 'options'=>[
                        'options' => [
                            // 'style' => 'width:170px'
                        ],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'todayHighlight' => true,
                            'format' => 'dd/mm/yyyy',
                        ]
                    ],
                ], 

                //'lhrtanggal'=>['type'=> Form::INPUT_TEXT, 'options'=>['style'=>'width:300px','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Tanggal Lahir').'...', 'maxlength'=>50]],
                'umur' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Umur') . '...', 'maxlength' => 50]],
                // 'hub' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Hubungan') . '...', 'maxlength' => 50]],
            ]
        ]);
        // echo $form->field($model, 'lhrtanggal', [
        //         /* 'template' => '{input}',
        //           'options'  => [
        //           'class' => 'input-group form-group'
        //           ], */
        // ])->widget(DatePicker::classname(), [
        //     'removeButton' => false,
        //     'options' => [
        //         'style' => 'width:170px'
        //     ],
        //     //'type' => DatePicker::TYPE_COMPONENT_APPEND,
        //     'pluginOptions' => [
        //         'autoclose' => true,
        //         'todayHighlight' => true,
        //         'format' => 'dd/mm/yyyy',
        //     ]
        // ])->label(Yii::t('app', 'Tanggal Lahir'));
        // echo $form->field($model, 'jenis')->dropDownList(['P' => 'Perempuan', 'L' => 'Laki Laki'])->label(Yii::t('app','Jenis Kelamin'));
        echo $form->field($model, 'jenis')->widget(Select2::classname(), [
            'data' => ['P' => 'Perempuan', 'L' => 'Laki Laki'],
            // 'options' => ['placeholder' => 'Pilih Jenis Kelamin ...'],
            'pluginOptions' => [
            // 'allowClear' => true
            ],
        ]) ->label(Yii::t('app','Jenis Kelamin'));     

        // echo $form->field($model, 'status')->dropDownList(['0' => 'Belum Kawin', '1' => 'Kawin']);
        // echo $form->field($model, 'sts')->widget(Select2::classname(), [
        //     'data' => ['BELUM KAWIN' => 'Belum Menikah', 'KAWIN' => 'Menikah'],
        //     'pluginOptions' => [
        //     ],
        // ]);

        echo $form->field($model, 'status')->widget(Select2::classname(), [
            'data' => ['0' => 'Belum Menikah', '1' => 'Menikah'],
            'pluginOptions' => [
            ],
        ])->label(Yii::t('app','Status Perkawinan'));

        echo $form->field($model,'hub');

        // echo $form->field($model, 'agama')->dropDownList(ArrayHelper::map(Agama::find()->all(),'ID','Name'))->label('Agama');
        echo $form->field($model, 'agama')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Agama::find()->all(),'ID','Name'),
            'pluginOptions' => [
            ],
        ]);

        // echo $form->field($model, 'pendidikan')->dropDownList(ArrayHelper::map(MasterPendidikan::find()->all(),'Nama','Nama'))->label('Pendidikan');
        echo $form->field($model, 'pendidikan')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(MasterPendidikan::find()->all(),'Nama','Nama'),
            'pluginOptions' => [
            ],
        ]);

        // echo $form->field($model, 'pekerjaan')->dropDownList(ArrayHelper::map(MasterPekerjaan::find()->all(),'Pekerjaan','Pekerjaan'))->label('Pekerjaan');
        echo $form->field($model, 'pekerjaan')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(MasterPekerjaan::find()->all(),'Pekerjaan','Pekerjaan'),
            'pluginOptions' => [
            ],
        ]);

        echo $form->field($model, 'klain_fisik')->checkbox(array('label'=>'Ya'))->label(yii::t('app','Kelainan Fisik'));
        echo $form->field($model, 'aktalhr')->checkbox(array('label'=>'Ya'))->label(yii::t('app','Akta Lahir'));
        echo $form->field($model, 'aktakawin')->checkbox(array('label'=>'Ya'))->label(yii::t('app','Akta Kawin'));
        echo $form->field($model, 'aktacerai')->checkbox(array('label'=>'Ya'))->label(yii::t('app','Akta Cerai'));
        

        ActiveForm::end();
        ?>
    </div>
</div>
