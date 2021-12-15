<?php


use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use common\models\MasterJenisIdentitas;
use common\models\MasterUangJaminan;
use common\models\MasterLoker;
use common\models\Lockers;

/**
 * @var yii\web\View $this
 * @var common\models\Lockers $model
 * @var yii\widgets\ActiveForm $form
 */
$this->title = Yii::t('app', 'Transaksi');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Locker'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style type="text/css">
    .btm10{
        margin-bottom: 10px;
    }
    .padding0{
        padding: 0px;
    }
</style>

<div class="lockers-form">
    <ul id="w3" class="nav nav-tabs">
        <li class="active"><a href="#tab-peminjaman" data-toggle="tab" aria-expanded="true"><?= yii::t('app','Peminjaman')?></a></li>
        <li>
            <!-- <a href="#tab-pengembalian" data-toggle="tab" aria-expanded="false">Pengembalian</a> -->
            <a href="pengembalian" aria-expanded="false"><?= yii::t('app','Pengembalian')?></a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content col-lg-9">
        <!-- Tab Peminjaman-->
        <div id="tab-peminjaman" class="tab-pane active">
            <h3><?= yii::t('app','Peminjaman')?></h3>
            <?php $form = ActiveForm::begin(); ?>
            <div class="form-horizontal">
                <table class="table table-condensed table-bordered table-striped table-hover request-table" style="table-layout: fixed;">
                    <tbody>
                        <tr>
                            <td class="col-sm-3"><label class="control-label"><?= Yii::t('app','No.Anggota') ?></label></td>
                            <td >
                                <div class="input-group">
                                    <?= Html::activeTextInput($model,'no_member',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Masukkan No.Angota')]); ?>
                                    <div class="input-group-btn" >
                                      <button class="btn btn-success" type="button" id="searchMembership"><i class="glyphicon glyphicon-check"></i>&nbsp;<?= Yii::t('app','Ok') ?></button>
                                    </div><!-- /btn-group -->
                                </div>
                                <div class="hint-block col-sm-9"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <!-- Table Pilih Locker -->
            <div class="form-horizontal" id="pilihLocker" hidden="hidden" > <!-- hidden="hidden" -->
                <table class="table table-condensed table-bordered table-striped table-hover request-table" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <td class="col-sm-3"><label class="control-label"><?= Yii::t('app','Scan Barcode Kunci')?></label></td>
                            <td>
                                <div class="col-sm-9" style="padding: 0;">
                                    <div class="input-group">
                                        <input id="scanBarcode" type="text" class="form-control" placeholder="<?= Yii::t('app','Scan Barcode Kunci')?>" name="barcode_kunci" required>
                                        <div class="input-group-btn">
                                            <button class="btn btn-success" type="button" id="okBarcode"><i class="glyphicon glyphicon-check"></i>&nbsp;Ok</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3" style="padding-right: 0" >
                                    <button data-toggle="modal" type="button" data-target="#piliLokerModal" class="btn btn-primary col-sm-12"><?= Yii::t('app','Pilih Loker')?></button>
                                </div>
                                <div class="padding0 col-sm-9"><b class="hint-barcode"></b></div>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>



            <!-- Table Jaminan -->
            <div class="" id="jaminan" hidden="hidden" > <!-- hidden="hidden" -->
                <table class="table table-condensed table-bordered table-striped table-hover request-table" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <th class="col-sm-3 "><label class="control-label"><?= Yii::t('app','Jaminan')?></label></th>
                            <th>
                                <div class="form-group">
                                    <div class="radio col-sm-9 ">
                                        <label id="groupRadioKartu" class="col-sm-6" hidden="hidden">
                                            <input type="radio" class="minimal" name="jenisjaminan" id="radioIdentitas" >
                                            <b><?= Yii::t('app','Kartu Identitas')?></b>
                                        </label>
                                        <label id="groupRadioUang" class="col-sm-6" hidden="hidden">
                                            <input type="radio" name="jenisjaminan" id="radioUang" >
                                            <b><?= Yii::t('app','Uang')?></b>
                                        </label>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="jaminanIdentitas" hidden="hidden">
                            <td class=""><label class="control-label"><?= Yii::t('app','Jaminan Identitas')?></label></td>
                            <td>
                                <div class="form-group kv-fieldset-inline">
                                    <div class="col-sm-6" style="padding: 0;">
                                    <?= $form->field($model, 'id_jamin_idt')->widget('\kartik\widgets\Select2',[
                                        'data'=>ArrayHelper::map(MasterJenisIdentitas::find()->all(),'id','Nama'),
                                        'pluginOptions' => [
                                        'allowClear' => true,
                                        ],
                                        'options'=> ['placeholder'=>Yii::t('app', 'Jenis Identitas')]
                                        ])->label(false); ?>
                                    </div>
                                    <div class="col-sm-6">
                                    <?= $form->field($model, 'no_identitas')->textInput(
                                        ['placeholder'=>Yii::t('app', 'Masukkan nomor identitas')]
                                        )->label(false); ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <div id="FieldJenisJaminan" hidden="hidden">
                        <?= $form->field($model, 'jenis_jaminan')->textInput(
                            ['placeholder'=>Yii::t('app', 'Jenis Jaminan')]
                            )->label(false); ?>
                        </div>
                        <tr id="jaminanUang" hidden="hidden">
                            <td class=""><label class="control-label"><?= Yii::t('app','Jaminan Uang')?></label></td>
                            <td>
                                <div class="col-sm-6" style="padding: 0;">

                                <?= $form->field($model, 'id_jamin_uang')->widget('\kartik\widgets\Select2',[
                                    'data'=>ArrayHelper::map(MasterUangJaminan::find()->all(),'ID',function($model) {
                                        return 'Rp.'.strrev(implode('.',str_split(strrev(strval($model['No'])),3))).' ('.$model['Name'].')';
                                    }),
                                    'pluginOptions' => [
                                    'allowClear' => true,
                                    ],
                                    'options'=> ['placeholder'=>Yii::t('app', 'Jaminan Uang')]
                                    ])->label(false)->hint('Berapa Rupiah'); ?>

                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-primary pull-right']); ?>
            </div>


            <?= $form->field($model, 'No_pinjaman')->hiddenInput(['placeholder'=>Yii::t('app', 'Nomor Pinjaman')])->label(false); ?> 
            <?= $form->field($model, 'loker_id')->hiddenInput()->label(false); ?> 
            <?php $form = ActiveForm::end(); ?>
        </div>

        <!-- Tab Pengembalian-->
        <div id="tab-pengembalian" class="tab-pane">
            <h3><?= yii::t('app','Pengembalian')?></h3>
          
        </div>
    </div>
</div>




<!-- Modal -->
<div id="piliLokerModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app','Loker') ?></h4>
            </div>
            <div class="modal-body row">
                
                <?php foreach ($lockerReady as $lockerReady): ?>
                <div class="col-sm-4">
                    <button class="btn btn-primary btn-social btm10"  onclick="isiBarcodeFromPilih('<?= $lockerReady['ID'] ?>','<?= $lockerReady['No'] ?>','<?= $lockerReady['Name'] ?>')">
                        <i class="fa fa-key"></i> <?= $lockerReady['Name']  ?>
                    </button>
                </div>
                <?php endforeach ?>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= yii::t('app','Tutup')?></button>
            </div>
        </div>

    </div>
</div>


<script type="text/javascript">
        
    //Menampilkan jaminan berdasarkan setting parameter
    function isiBarcodeFromPilih(id,nomor,name){
        $('#scanBarcode').val(nomor);
        $('#piliLokerModal').modal('hide');
        $('.hint-barcode').html("<i class=\"text-primary\">" +name+ "</i>");
        $('#lockers-loker_id').attr('value', id);
    }
    
</script>


<?php
$this->registerJs("

    //Prevent action submit form when press enter
    $(document).ready(function() {
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    });

    //Cursor focus on field when page loaded
    $('#lockers-no_member').focus();

    //get var From setting parameters
    var IsJaminan = '".Yii::$app->config->get('IsJaminanLoker')."';
    var jaminUang = '".Yii::$app->config->get('JaminanUangLoker')."';
    var jaminIdt = '".Yii::$app->config->get('JaminanIdentitasLoker')."';
    var pinjamLebihDariSatu = '".Yii::$app->config->get('IsMemberAllowedToBorrowMultipleLocker')."';


    //ketika radiobuton jaminan idt dipilih/berubah 
    $('#radioIdentitas').change(function(){
        if($(this).is(':checked'))
        {
            // $('#pilihLocker').show();
            $('#jaminanUang').hide();
            $('#jaminanIdentitas').show();
            $('#lockers-jenis_jaminan').val('Kartu Identitas');
            $('#lockers-id_jamin_uang').select2('val', '');
            document.getElementById('lockers-no_identitas').required = true;
            document.getElementById('lockers-id_jamin_idt').required = true;
            document.getElementById('lockers-id_jamin_uang').required = false;
        }
    });


    //ketika radiobuton uang jaminan dipilih/berubah 
    $('#radioUang').change(function(){
        if($(this).is(':checked'))
        {
            // $('#pilihLocker').show();
            $('#jaminanUang').show();
            $('#jaminanIdentitas').hide();
            $('#lockers-no_identitas').val('');
            $('#lockers-jenis_jaminan').val('Uang');
            $('#lockers-id_jamin_idt').select2('val', '');
            document.getElementById('lockers-no_identitas').required = false;
            document.getElementById('lockers-id_jamin_idt').required = false;
            document.getElementById('lockers-id_jamin_uang').required = true;
        }
    });

    //Mengambil waktu saat ini dari pc dan menjadikannya kode unik untuk nomor transaksi
    var timestamp = new Date().getTime();;
    $('#lockers-no_pinjaman').attr('value', timestamp);

    //Validasi nomor anggota atau member
    function validasi(){
        var noMember = $('#lockers-no_member').val();
        $('.hint-block').html('Loading...');

        $.get('checkmembership',{ noMember : noMember },function(data){
          //  alert(data);
            if (!data) 
            {
                $('#jaminan').hide();
                $('#pilihLocker').hide();
                $('.hint-block').html('".yii::t('app','Data member atau Memberguess tidak ditemukan')."');
                $('#lockers-no_member').val('');
            } 
            else 
            {
                // 
                $.get('pinjamanbynomember',
                { 
                    noMember : noMember 
                },
                function(datas)
                {
                    $('.hint-block').html(datas);
                    if (pinjamLebihDariSatu == 0) 
                    {
                        if (!datas) 
                        {
                            $('#pilihLocker').show();
                            $('#scanBarcode').focus();
                            showJaminan();             
                        } 
                        else 
                        {
                            $('#jaminan').hide();
                            $('#pilihLocker').hide();
                            $('#groupRadioUang').hide();
                            $('#groupRadioKartu').hide();
                        }
                    }
                    else
                    {
                        $('#pilihLocker').show();
                        $('#scanBarcode').focus();
                        showJaminan();
                    }
                });    
            }  
        });
    }


    //Ketika klik enter di field Scan Barcode
    $('#scanBarcode').keydown(function(event){
        if(event.keyCode == 13) {
            barcodeLoker();
        }
    });


    //Ketika klik ok di scan barcode
    $('#okBarcode').on('click', function(){
            barcodeLoker();
    });

    //Validasi nomor barcode loker
    function barcodeLoker(){
        var noBarcode = $('#scanBarcode').val();
        $('.hint-barcode').html('<i class=\"text-warning\">Loading...</i>');

        $.get('check-barcode-loker',{ noBarcode : noBarcode},function(data){
            if (data=='null') {
                document.getElementById('scanBarcode').innerHTML = '';   
                $('.hint-barcode').html('<i class=\"text-danger\">Data loker tidak ditemukan / loker tidak dapat digunakan..</i>');
                $('#lockers-loker_id').attr('value', '');
                $('#scanBarcode').val('');
            } else {
                var data = $.parseJSON(data);
                // var fined = (data.denda).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
                // $('#denda').attr('value',);
                $('.hint-barcode').html('<i class=\"text-success\">' +data.Name+ '</i>');
                $('#lockers-loker_id').attr('value', data.ID);


                if (jaminUang == '0' && jaminIdt == '0') {
                    $('#w0').submit();
                } 
                
            }
        });
    }

    //Menampilkan jaminan berdasarkan setting parameter
    function showJaminan(){
        if (jaminUang == '1' || jaminIdt == '1') 
        { 
            $('#jaminan').show(); 
            
            if (jaminUang == '1' && jaminIdt == '1') {
                $('#groupRadioUang').show();
                $('#groupRadioKartu').show();  
                document.getElementById('lockers-id_jamin_idt').required = true;              
            } else {
                if (jaminUang == '1') {
                    $('#radioUang').prop('checked',true);
                    $('#groupRadioUang').show();
                    $('#jaminanUang').show();
                    document.getElementById('lockers-id_jamin_uang').required = true;
                    document.getElementById('lockers-no_identitas').required = false;
                    document.getElementById('lockers-id_jamin_idt').required = false;
                } else if (jaminIdt == '1'){
                    $('#radioIdentitas').prop('checked',true);
                    $('#groupRadioKartu').show();
                    $('#jaminanIdentitas').show();
                    document.getElementById('lockers-id_jamin_uang').required = false;
                    document.getElementById('lockers-no_identitas').required = true;
                    document.getElementById('lockers-id_jamin_idt').required = true;
                }
            }
        } 
        else 
        {
            $('#jaminan').hide(); 
        }  
    }


    //Ketika klik enter di field locker no_member
    $('#lockers-no_member').keydown(function(event){
        if(event.keyCode == 13) {
			validasi();
		}
	});

    //Ketika klik ok ketika search
    $('#searchMembership').on('click', function(){
			validasi();
    });




");
?>

