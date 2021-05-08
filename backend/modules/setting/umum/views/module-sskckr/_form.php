<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\helpers\ArrayHelper;
    use kartik\widgets\ActiveForm;

    /**
     * @var yii\web\View $this
     * @var common\models\Collectioncategorys $model
     */

    $this->title = Yii::t('app', 'Pengaturan Module SSKCKR');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), ];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Umum'), ];
    $this->params['breadcrumbs'][] = $this->title;

    ?>

    <style type="text/css">
        .col-sm-4 label {
            font-weight: normal;
        }

        .table {
            margin-bottom: 0px;
        }
    </style>


    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="settingparameters-create">
        <div class="page-header">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => ' btn btn-primary', 'id' => 'btnSave']) ?>
        </div>

        <div class="settingparameters-form">
            <div class="col-sm-9">



                <div class="form-horizontal"> <!-- hidden="hidden" -->
                    <table class="table" style="table-layout: fixed;">
                        <thead>
                        <tr>
                            <td class="col-sm-3">
                                <label class="control-label" style="color : red">
                                    <?= Yii::t('app', 'Petunjuk Mengaktifkan Modul SSKCKR') ?>
                                </label>
                            </td>
                            <td>
                                - Copy-kan folder updater khusus SSKCKR ke dalam folder aplikasi Inlislite <br>
                                - Lakukan ceklist pada form dibawah ini <br>
                                - Lalu tekan tombol simpan <br>
                                - Setelah tersimpan dan kembali lagi ke halaman ini, akan muncul tombol Update <br>
                                - Tekan tombol Update, untuk menambahkan modul SSKCKR pada aplikasi Inlislite <br>
                                - Tunggu sampai prosesnya selesai
                            </td>
                        </tr>
                        </thead>
                    </table>

                    <table class="table" style="table-layout: fixed;">
                        <thead>
                        <tr>
                            <td class="col-sm-3">
                                <label class="control-label" style="color : red">
                                    <?= Yii::t('app', 'Petunjuk Nonaktifkan Modul SSKCKR') ?>
                                </label>
                            </td>
                            <td>
                                - Hapus ceklist pada form dibawah ini <br>
                                - Lalu tekan tombol simpan <br>
                                - Tunggu sampai prosesnya selesai
                            </td>
                        </tr>
                        </thead>
                    </table>

                    <table class="table" style="table-layout: fixed;">
                        <thead>
                        <tr>
                            <td class="col-sm-3"><label
                                        class="control-label"><?= Yii::t('app', 'Aktifkan Module SSKCKR') ?></label></td>
                            <td>
                                <div class="col-sm-4" style="padding: 0;margin-left: 13px;">
                                    <?= $form->field($model, 'Value1')->checkbox(['label'=>'Ya', 'id'=>'sskckr'])->label(false); ?>

                                </div>
                                <div class="padding0 col-sm-9"><b class="hint-uang"></b></div>
                            </td>
                        </tr>
                        </thead>
                    </table>
                </div>


                <?php ActiveForm::end() ?>
                <?php if(Yii::$app->config->get('ModuleDeposit') == 1) { ?>
                    <div id="modulsskckcr">
                        <hr class="" /> 
                        <div class="form-horizontal"> <!-- hidden="hidden" -->
                            <table class="table" style="table-layout: fixed;">
                                <thead>
                                    <tr>
                                        <td class="col-sm-3"><label class="control-label"><?= Yii::t('app','Update Struktur DB SSKCKR') ?></label></td>
                                        <td>
                                       <!-- <button type="button" class="btn btn-md btn-info" id="updateQuery" onclick="#" disabled><i class="fa fa-edit"></i> Update</button>-->
                                         <button type="button" class="btn btn-md btn-info" id="updateQuery" onclick="updateCek();"><i class="fa fa-edit"></i> Update</button>
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div id="errorLog"></div>
                <?php } ?>
                
                

            </div>
        </div>
    </div>

    <script type="text/javascript">
        function updateCek(){
            a = '../setting-update/update';
            // alert(url);
            // alert('a');
            $.ajax({
                type     :"POST",
                cache    : false,
                url      : a,
                success  : function(response) {
                $("#errorLog").html(response);
                }
            });
            
            // sweetAlert('', 'Tampung katalog akan dibersihkan');
            
        }

        $('#sskckr').click(function(){
            var $this = $(this);
            if ($this.is(':checked')) {
                // the checkbox was checked 
                
            } else {
                var box= confirm("Apakah kamu ingin nonaktfikan modul SSKCKR?");
                if (box==true){
                    return true;}
                else{
                    //alert(true);
                    document.getElementById('sskckr').checked = true;
                }
            }
        })


    </script>
    




