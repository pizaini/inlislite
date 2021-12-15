<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use kartik\grid\GridView;
use kartik\date\DatePicker;

use kartik\widgets\Select2;
use kartik\widgets\ActiveForm;

use yii\widgets\Pjax;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\LockersSearch $searchModel
 */

$this->title = yii::t('app','Laporan Anggota Per Pendaftaran');
$this->params['breadcrumbs'][] = $this->title;

$month = array();
$modelArticleRepeatable = \common\models\Collections::find()->select(['MIN(DATE_FORMAT(TanggalPengadaan,"%Y")) AS TanggalPengadaan'])->One();
     // print_r($modelArticleRepeatable['TanggalPengadaan']); die;
$year = range($modelArticleRepeatable['TanggalPengadaan'] , date('Y'));
rsort($year);
$y=array();

for ($m=1; $m<=12; $m++) 
{
     $month[$m] = date('F', mktime(0,0,0,$m, 1, date('Y')));
     // echo $month. '<br>';
}

foreach ($year as $year => $value) {
    $y[$value] = $value;
}
//print_r($y);

$kriteria = ['' => 'Semua Kriteria',
        'Status_Anggota' => yii::t('app','Status Anggota'),
        'range_umur' => yii::t('app','Kelompok Umur'),
        'jenis_kelamin' => yii::t('app','Jenis Kelamin'),
        'Jenis_Anggota' => yii::t('app','Jenis Anggota'),
        'Pekerjaan' => yii::t('app','Pekerjaan'),
        'Pendidikan' => yii::t('app','Pendidikan'),
        'Fakultas' => yii::t('app','Fakultas'),
        'Jurusan' => yii::t('app','Jurusan'),
        'Kelas' => yii::t('app','Kelas'),
        'lokasi_pinjam' => yii::t('app','Lokasi Pinjam'),
        'kategori_koleksi' => yii::t('app','Kategori Koleksi'),
        'jenis_identitas' => yii::t('app','Jenis Identitas'),
        'propinsi' => yii::t('app','Provinsi Sesuai Identitas'),
        'kecamatan' => yii::t('app','Kecamatan'),
        'kelurahan' => yii::t('app','Kelurahan'),
        'kabupaten' => yii::t('app','Kabupaten/Kota Sesuai Identitas'),
        'propinsi2' => yii::t('app','Provinsi Tempat Tinggal'),
        'kabupaten2' => yii::t('app','Kabupaten/Kota Tempat Tinggal'),
        'nama_institusi' => yii::t('app','Nama Institusi'),
        'data_entry' => yii::t('app','Tanggal Data Entri'),
        'petugas_perpanjangan' => yii::t('app','Penginput Data')
        //'departments' => 'Unit Kerja'
        ];
?>

<style type="text/css">
    .gap-padding10{
        padding-bottom: 10px;
    }
    .padding0{
        padding: 0;
    }

    .select2-container--krajee .select2-selection {
        font-size: 12px;
    }
</style>

<div class="lockers-index">

    <form id="form-SearchFilter" method="POST" action="show-pdf">    
        <div id="SearchFilter" class="col-sm-12">
            <div class="form-horizontal">
                <div class="box-body">

                    <!-- Pilih Periode -->
                    <div class="form-group">
                        <label for="pilihPeriode" class="col-sm-2 control-label"><?= Yii::t('app','Periode')//.' '.Yii::t('app','Pengadaan') ?></label>

                        <div class="col-sm-10 row">
                            <div class="col-sm-4 padding0">
                                <?= Select2::widget([
                                'name' => 'periode',
                                'data' => ['harian' => yii::t('app','Harian'),'bulanan' => yii::t('app','Bulanan'),'tahunan' => yii::t('app','Tahunan')],
                                'options' => [
                                // 'placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Periode'),
                                'id' => 'pilihPeriode',
                                'class' => 'select2'
                                ],
                                ]); ?>
                            </div>
                            
                            <!-- Harian -->
                            <div class="col-sm-8" id="periodeHarian"  >
                                <?=  DatePicker::widget([
                                    'name' => 'from_date', 
                                    'type' => DatePicker::TYPE_RANGE,
                                    'value' => date('d-m-Y'),
                                    'name2' => 'to_date', 
                                    'value2' => date('d-m-Y'),
                                    'separator' => 's/d',
                                    'options' => ['placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Date')],
                                    'pluginOptions' => [
                                    'format' => 'dd-mm-yyyy',
                                    'todayHighlight' => true,
                                    'autoclose'=>true,
                                    'id' => 'rangeHarian',
                                    ]
                                    ]);
                                    ?>
                            </div><!-- /Harian -->
                            <!-- Bulanan -->
                            <div class="col-sm-8" id="periodeBulanan" hidden="hidden">
                                <div class="input-group"> 
                                    <div class="container-fluid padding0 col-sm-5">
                                        <div class="col-sm-6 padding0">
                                            <?= Select2::widget([
                                                'name' => 'fromBulan',
                                                'value' => date('m'),
                                                'data' => $month,
                                                'options' => [
                                                // 'placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Month'),
                                                'id' => 'fromBulan',
                                                'class' => 'padding0'
                                                ],
                                                ]); ?>
                                        </div>
                                        <div class="col-sm-6 padding0">
                                            <?= Select2::widget([
                                                'name' => 'fromTahun',
                                                'data' => $y,
                                                'value' => date('Y'),
                                                'options' => [
                                                // 'placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Year'),
                                                'id' => 'fromTahun',
                                                'class' => 'padding0'
                                                ],
                                                ]); ?>
                                        </div>
                                    </div>
                                    
                                    <center class="col-sm-1" id="basic-addon1" style="padding-top: 10px"> s/d </center> 

                                    <div class="container-fluid padding0 col-sm-5">
                                        <div class="col-sm-6 padding0">
                                            <?= Select2::widget([
                                                'name' => 'toBulan',
                                                'data' => $month,
                                                'value' => date('m'),
                                                'options' => [
                                                // 'placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Month'),
                                                'id' => 'toBulan',
                                                'class' => 'padding0'
                                                ],
                                                ]); ?>
                                        </div>
                                        <div class="col-sm-6 padding0" >
                                            <?= Select2::widget([
                                                'name' => 'toTahun',
                                                'data' => $y,
                                                'value' => date('Y'),
                                                'options' => [
                                                // 'placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Year'),
                                                'id' => 'toTahun',
                                                'class' => 'padding0'
                                                ],
                                                ]); ?>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /Bulanan -->
                            <!-- Tahunan -->
                            <div class="col-sm-8" id="periodeTahunan" hidden="hidden" >
                                <div class="input-group"> 
                                    <div class="">
                                        <?= Select2::widget([
                                            'name' => 'fromTahunan',
                                            'value' => date('Y'),
                                            'data' => $y,
                                            'options' => [
                                            // 'placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Year'),
                                            'id' => 'fromTahunan',
                                            'class' => 'padding0'
                                            ],
                                            ]); ?>
                                    </div>
                                    
                                    <center class="input-group-addon" id="basic-addon1"> s/d </center> 

                                    <div class="">
                                        <?= Select2::widget([
                                            'name' => 'toTahunan',
                                            'value' => date('Y'),
                                            'data' => $y,
                                            'options' => [
                                            // 'placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Year'),
                                            'id' => 'toTahunan',
                                            'class' => 'padding0'
                                            ],
                                            ]); ?>
                                    </div>
                                </div>
                            </div><!-- /Tahunan -->

                        </div>

                    </div>
                    <!-- /Pilih Periode -->
                    <!-- Pilih Kriteria -->
                    <div class="form-group multi-field-wrapper" id="pilihan-Kriteria">
                        <label for="pilihKriteria" class="col-sm-2 control-label"><?= Yii::t('app','Kriteria') ?> </label>

                        <!-- Group all Content and append here-->
                        <div class="col-sm-10 container-fluid padding0 multi-fields" id="appendContentHere">
                            
                            <!-- Group plus minus dan pilih kriteria -->
                            <div class="row col-sm-12 gap-padding10 multi-field">
                                <div class="col-sm-4 padding0">
                                    <div class="input-group">

                                        <div class="input-group-btn">
                                            <!-- <button type="button" class="btn btn-danger remove-field"><span class="glyphicon glyphicon-minus-sign"></span></button> -->
                                            <button type="button" class="btn btn-success add-field"><span class="glyphicon glyphicon-plus-sign"></span></button>
                                        </div>

                                        <div class="input-group">
                                            <?= Select2::widget([
                                                'name' => 'kriterias[]',
                                                'data'=> $kriteria,
                                                'options' => [
                                                'placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Kriteria'),
                                                'class' => 'pilihKriteria',
                                                // 'id' => 'pilihKriteria'
                                                ],
                                                ]); ?>
                                        </div>
                                    </div>
                                </div>

                                <div id="" class="col-sm-8 content-kriteria" >

                                </div>
                            </div>
                            <!-- /Group plus minus dan pilih kriteria -->
                            
                        </div><!-- /Group all Content and append here-->
                    </div>
                    <!-- /Pilih Kriteria -->

                    <div class="form-group">
                        <label for="kop" class="col-sm-2 control-label"><?= Yii::t('app','Kop') ?> </label>

                        <div class="col-sm-10 row">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="kop"> <?= yii::t('app','Ya / Tidak')?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="form-group padding0">
                    <div class="col-sm-10 col-sm-offset-2 padding0">
                        <button id="tampilkan_frekuensi" type="button" class="btn btn-sm btn-primary"><?= Yii::t('app','Tampilkan') ?> <?= Yii::t('app','Frekuensi') ?></button>
                        <button id="tampilkan_data" type="button" class="btn btn-sm btn-primary"><?= Yii::t('app','Tampilkan Detail Data') ?></button>
                        <div class="btn-group" style="cursor:pointer;">
                           <button type="button" id="export" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 4px 12px !important; display: none;">
                                Export   <span class="caret"></span>
                           </button>
                           <ul class="dropdown-menu">
                             <li><a id="export-excel-perpendaftaran-frekuensi">Excel-frekuensi</a></li>
                             <li><a id="export-excel-odt-perpendaftaran-frekuensi">Open-Office-Excel-frekuensi</a></li>
                             <li><a id="export-excel-perpendaftaran-data">Excel-data</a></li>
                             <li><a id="export-excel-odt-perpendaftaran-data">Open-Office-Excel-data</a></li>
                             <li><a id="export-word-perpendaftaran-frekuensi">Word-frekuensi</a></li>
                             <li><a id="export-odt-perpendaftaran-frekuensi">Open-Office-Word-frekuensi</a></li>
                             <li><a id="export-word-perpendaftaran-data">Word-data</a></li>
                             <li><a id="export-odt-perpendaftaran-data">Open-Office-Word-data</a></li>
                             <li><a id="export-pdf-perpendaftaran-frekuensi">PDF-Frekuensi</a></li>
                             <li><a id="export-pdf-perpendaftaran-data">PDF-data</a></li>
                           </ul>
                        </div>
                        <button id="reset" type="button" class="btn btn-sm btn-warning"><?= Yii::t('app','Reset') ?> <?= Yii::t('app','Kriteria') ?> </button>
                    </div>
                   
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
    </form> 



    <hr class="col-sm-12">
    
    <div id="show-pdf-content" class="col-sm-12">
        <!-- Nanti show PDF Disini -->
    </div>

</div>


<script type="text/javascript">
    
</script>



<?php
$this->registerJs("

    $.fn.select2.defaults.set('theme', 'krajee');

    // Filter Periode
    $('#pilihPeriode').change(function(){
        var periode = $(this).val();
        // alert(periode);
        if (periode == 'harian') 
        {
            $('#periodeHarian').show();
            $('#periodeBulanan').hide();
            $('#periodeTahunan').hide();
           
        } 
        else if (periode == 'bulanan') 
        {
            $('#periodeHarian').hide();
            $('#periodeBulanan').show();
            $('#periodeTahunan').hide();
        }
        else 
        {
            $('#periodeHarian').hide();
            $('#periodeBulanan').hide();
            $('#periodeTahunan').show();
        }
    });

    var i = 1;
    $('.add-field').click(function(e) {    
        $.get('load-selecter-kriteria',{ i : i },function(data){
            $('.multi-fields').append(data);        
            // $('.multi-fields').find('.select2').select2();
            i++;
        });
    });
  

    // Pilih Kriteria per Row
    $('#pilihan-Kriteria').on('change','.pilihKriteria',function(){ 
        $( '.content-kriteria' ).html('<div style=\"padding-top: 10px;\">Loading...</div>'); 
        var kriteria = $(this).val();
        console.log(kriteria);
     
        $.get('load-filter-kriteria',{kriteria : kriteria},function(data){
            if (data == '') 
            {
                $( '.content-kriteria' ).html( '' );   
            } 
            else 
            {
               $( '.content-kriteria' ).html( data ); 
               $('.content-kriteria').find('.select2').select2({
                // allowClear: true,
                }); 
               $('.content-kriteria').find('#w0').kvDatepicker({format: 'dd-mm-yyyy', todayHighlight: true, autoclose: true});
               $('.content-kriteria').find('#w0-2').kvDatepicker({format: 'dd-mm-yyyy',todayHighlight: true, autoclose: true});
            }
        });

    });



    // Tampilkan Frekuensi
    var form = $('#form-SearchFilter');
    $('#tampilkan_frekuensi').click(function(){
        $.ajax({
            type:\"POST\",
            url:'show-pdf?tampilkan=perpendaftaran-frekuensi',
            data:form.serialize(),
            success: function(response){
                console.log(response);  
                $( '#show-pdf-content' ).html( response );
                $('#export-excel-perpendaftaran-frekuensi').show(); 
                $('#export-excel-odt-perpendaftaran-frekuensi').show(); 
                $('#export-word-perpendaftaran-frekuensi').show(); 
                $('#export-odt-perpendaftaran-frekuensi').show(); 
                $('#export-pdf-perpendaftaran-frekuensi').show(); 
                $('#export-excel-perpendaftaran-data').hide(); 
                $('#export-excel-odt-perpendaftaran-data').hide(); 
                $('#export-word-perpendaftaran-data').hide(); 
                $('#export-odt-perpendaftaran-data').hide(); 
                $('#export-pdf-perpendaftaran-data').hide(); 
            }
        });
    });
    $('#tampilkan_data').click(function(){
            $.ajax({
                type:\"POST\",
                url:'show-pdf?tampilkan=perpendaftaran-data',
                data:form.serialize(),
                success: function(response){
                    console.log(response);  
                    $( '#show-pdf-content' ).html( response ); 
                    $('#export-excel-perpendaftaran-frekuensi').hide(); 
                    $('#export-excel-odt-perpendaftaran-frekuensi').hide(); 
                    $('#export-word-perpendaftaran-frekuensi').hide(); 
                    $('#export-odt-perpendaftaran-frekuensi').hide(); 
                    $('#export-pdf-perpendaftaran-frekuensi').hide(); 
                    $('#export-excel-perpendaftaran-data').show(); 
                    $('#export-excel-odt-perpendaftaran-data').show(); 
                    $('#export-word-perpendaftaran-data').show(); 
                    $('#export-odt-perpendaftaran-data').show(); 
                    $('#export-pdf-perpendaftaran-data').show(); 
                }
            });
        });
    $('#export-excel-perpendaftaran-frekuensi').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-excel-perpendaftaran-frekuensi',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-excel-perpendaftaran-frekuensi')
              }
            });
            
    });
    $('#export-excel-odt-perpendaftaran-frekuensi').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-excel-odt-perpendaftaran-frekuensi',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-excel-odt-perpendaftaran-frekuensi')
              }
            });
            
    });
    $('#export-excel-perpendaftaran-data').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-excel-perpendaftaran-data',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-excel-perpendaftaran-data')
              }
            });
            
    });
    $('#export-excel-odt-perpendaftaran-data').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-excel-odt-perpendaftaran-data',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-excel-odt-perpendaftaran-data')
              }
            });
            
    });
    $('#export-word-perpendaftaran-frekuensi').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-word-perpendaftaran-frekuensi',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-word-perpendaftaran-frekuensi?type=doc')
              }
            });
            
    });
    $('#export-odt-perpendaftaran-frekuensi').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-odt-perpendaftaran-frekuensi',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-word-perpendaftaran-frekuensi?type=odt')
              }
            });
            
    });
    $('#export-word-perpendaftaran-data').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-word-perpendaftaran-data',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-word-perpendaftaran-data?type=doc')
              }
            });
            
    });
    $('#export-odt-perpendaftaran-data').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-odt-perpendaftaran-data',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-word-perpendaftaran-data?type=odt')
              }
            });
            
    });
    $('#export-pdf-perpendaftaran-frekuensi').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-pdf-perpendaftaran-frekuensi',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-pdf-perpendaftaran-frekuensi')
              }
            });
            
    });
    $('#export-pdf-perpendaftaran-data').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-pdf-perpendaftaran-data',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-pdf-perpendaftaran-data')
              }
            });
            
    });
    $('#reset').click(function(){
        location.reload();
    });

");
?>
