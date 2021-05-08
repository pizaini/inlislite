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

$this->title = yii::t('app','Laporan Peminjaman');
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
        'no_anggota' => yii::t('app','Nomor Anggota'),
        'range_umur' => yii::t('app','Kelompok Umur'),
        'jenis_kelamin' => yii::t('app','Jenis Kelamin'),
        'jenis_anggota' => yii::t('app','Jenis Anggota'),
        'Pekerjaan' => yii::t('app','Pekerjaan'),
        'Pendidikan' => yii::t('app','Pendidikan'),
        'Fakultas_id' => yii::t('app','Fakultas'),
        'Jurusan_id' => yii::t('app','Jurusan'),
        'program_studi_id' => yii::t('app','Program Studi'),
        'Kelas_id' => yii::t('app','Kelas'),
        'unit_kerja' => yii::t('app','Unit Kerja'),
        'jenis_identitas' => yii::t('app','Jenis Identitas'),
        'propinsi' => yii::t('app','Provinsi Sesuai Identitas'),
        'kabupaten' => yii::t('app','Kabupaten/Kota Sesuai Identitas'),
        'kecamatan' => yii::t('app','Kecamatan'),
        'kelurahan' => yii::t('app','Kelurahan'),
        'propinsi2' => yii::t('app','Provinsi Tempat Tinggal'),
        'kabupaten2' => yii::t('app','Kabupaten/Kota Tempat Tinggal'),
        'nama_institusi' => yii::t('app','Nama Institusi'),
        'lokasi_pinjam' => yii::t('app','Lokasi Pinjam')
        ];

$kriteria_dipinjam = [
        '' => 'Semua Kriteria',
        'PublishLocation' => yii::t('app','Kota Terbit'),
        'Publisher' => yii::t('app','Nama Penerbit'),
        'PublishYear' => yii::t('app','Tahun Terbit'),
        'locations' => yii::t('app','Ruang Perpustakaan'),
        'collectionsources' => yii::t('app','Jenis Sumber Perolehan'),
        'partners' => yii::t('app','Nama Sumber/Rekanan Perolehan'),
        'currency' => yii::t('app','Mata Uang'),
        'harga' => yii::t('app','Harga'),
        'collectioncategorys' => yii::t('app','Kategori'),
        'collectionrules' => yii::t('app','Jenis Akses'),
        'worksheets' => yii::t('app','Jenis Bahan'),
        'collectionmedias' => yii::t('app','Bentuk Fisik'),
        'Subject' => yii::t('app','Subjek'),
        'no_klas' => yii::t('app','Nomor Klas'),
        'no_panggil' => yii::t('app','Nomor Panggil')
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
                        <label for="pilihKriteria" class="col-sm-2 control-label"><?= Yii::t('app','Kriteria Anggota Peminjam') ?> </label>

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

                    <!-- Pilih Kriteria Dipinjam-->
                    <div class="form-group multi-field-wrapper" id="pilihan-Kriteria-dipinjam" style="margin-left:-4px;">
                        <label for="pilihKriteriaDipinjam" class="col-sm-2 control-label"><?= Yii::t('app','Kriteria Koleksi Dipinjam')?> </label>

                        <!-- Group all Content and append here-->
                        <div class="col-sm-10 container-fluid padding0 multi-fields-dipinjam" id="appendContentHere">
                            
                            <!-- Group plus minus dan pilih kriteria -->
                            <div class="row col-sm-12 gap-padding10 multi-field">
                                <div class="col-sm-4 padding0">
                                    <div class="input-group">

                                        <div class="input-group-btn">
                                            <!-- <button type="button" class="btn btn-danger remove-field"><span class="glyphicon glyphicon-minus-sign"></span></button> -->
                                            <button type="button" class="btn btn-success add-field-dipinjam"><span class="glyphicon glyphicon-plus-sign"></span></button>
                                        </div>

                                        <div class="input-group">
                                            <?= Select2::widget([
                                                'name' => 'kriterias[]',
                                                'data'=> $kriteria_dipinjam,
                                                'options' => [
                                                'placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Kriteria'),
                                                'class' => 'pilihKriteriaDipinjam',
                                                // 'id' => 'pilihKriteria'
                                                ],
                                                ]); ?>
                                        </div>
                                    </div>
                                </div>

                                <div id="" class="col-sm-8 content-kriteria-dipinjam" >

                                </div>
                            </div>
                            <!-- /Group plus minus dan pilih kriteria -->
                            
                        </div><!-- /Group all Content and append here-->
                    </div>
                    <!-- /Pilih Kriteria Dipinjam-->


                        <div class="form-group" style="margin-left: -20px; margin-top: -10px;">
                            <label for="" class="col-sm-2 control-label" style="margin-left:10px;"><?= Yii::t('app','Petugas Transaksi') ?></label>

                            <div id="" class="col-sm-8 content-tujuan" style="margin-left:-15px;" Name="kriteria-tujuan">
                            </div>
                        </div>
                    </div>
                    <!-- /Pilih Kriteria -->                    

                    <div class="form-group" style="margin-top: -20px;">
                        <label for="kriteria-pengunjung" name="kriteria-pengunjung" class="col-sm-2 control-label"><?= Yii::t('app','Kriteria Pengunjung') ?> </label>

                        <div class="col-sm-10 row">
                            <div class="checkbox">
                                <label style="margin-right: 50px;"><input type="checkbox" Name="belum_kembali" value="anggota"> <?= Yii::t('app','Belum dikembalikan') ?> </label>
                                <label style="margin-right: 50px;"><input type="checkbox" Name="sudah_kembali" value="non_anggota"> <?= Yii::t('app','Sudah dikembalikan') ?></label>
                                <label style="margin-right: 50px;"><input type="checkbox" Name="belum_tgl_jatuh" value="rombongan"> <?= Yii::t('app','Belum melewati tanggal jatuh tempo') ?></label>
                                <label style="margin-right: 50px;"><input type="checkbox" Name="sudah_tgl_jatuh" value="rombongan"> <?= Yii::t('app','Sudah melewati tanggal jatuh tempo') ?></label>
                            </div> 
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="kop" class="col-sm-2 control-label"><?= Yii::t('app','Kop') ?> </label>

                        <div class="col-sm-10 row">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"  Name="kop"> <?= yii::t('app','Ya / Tidak')?>
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
                                Export &nbsp<span class="caret"></span>
                           </button>
                           <ul class="dropdown-menu">
                             <li><a id="export-excel-laporan-peminjaman-frekuensi">Excel-frekuensi</a></li>
                             <li><a id="export-excel-odt-laporan-peminjaman-frekuensi">Open-Office-Excel-frekuensi</a></li>
                             <li><a id="export-excel-laporan-peminjaman-data">Excel-data</a></li>
                             <li><a id="export-excel-odt-laporan-peminjaman-data">Open-Office-Excel-data</a></li>
                             <li><a id="export-word-laporan-peminjaman-frekuensi">Word-frekuensi</a></li>
                             <li><a id="export-odt-laporan-peminjaman-frekuensi">Open-Office-Word-frekuensi</a></li>
                             <li><a id="export-word-laporan-peminjaman-data">Word-data</a></li>
                             <li><a id="export-odt-laporan-peminjaman-data">Open-Office-Word-data</a></li>
                             <li><a id="export-pdf-laporan-peminjaman-frekuensi">PDF-Frekuensi</a></li>
                             <li><a id="export-pdf-laporan-peminjaman-data">PDF-data</a></li>
                           </ul>
                        </div> 
                        <button id="reset" type="button" class="btn btn-sm btn-warning"><?= Yii::t('app','Reset') ?> <?= Yii::t('app','Kriteria') ?> </button>
                    </div>
                   
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
    </form> 


    <div hidden="hidden" class="col-sm-4 padding0">
        <?= Select2::widget([
            'name' => '',
            'data' => [],
            'options' => [],
            ]); ?>
    </div>

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

    $.get('load-filter-kriteria',{kriteria : 'tujuan'},function(data){
    
       $( '.content-tujuan' ).html( data ); 
       $('.content-tujuan').find('.select2').select2({
        // allowClear: true,
        }); 
    });

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
        $.get('load-selecter-laporan-peminjaman',{ i : i },function(data){
            $('.multi-fields').append(data);        
            // $('.multi-fields').find('.select2').select2();
            i++;
        });
    });

var i = 1;
    $('.add-field-dipinjam').click(function(e) {    
        $.get('load-selecter-laporan-dipinjam',{ i : i },function(data){
            $('.multi-fields-dipinjam').append(data);        
            // $('.multi-fields-dipinjam').find('.select2').select2();
            i++;
        });
    });
 

    // Pilih Kriteria per Row
    $('#pilihan-Kriteria-dipinjam').on('change','.pilihKriteriaDipinjam',function(){ 
        $( '.content-kriteria-dipinjam' ).html('<div style=\"padding-top: 10px;\">Loading...</div>'); 
        var kriteria = $(this).val();
        console.log(kriteria);
     
        $.get('load-filter-kriteria-dipinjam',{kriteria : kriteria},function(data){
            if (data == '') 
            {
                $( '.content-kriteria-dipinjam' ).html( '' );   
            } 
            else 
            {
               $( '.content-kriteria-dipinjam' ).html( data ); 
               $('.content-kriteria-dipinjam').find('.select2').select2({
                // allowClear: true,
                }); 
               // $('.content-kriteria-dipinjam').find('.datepicker').datepicker();
            }
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
               $('.content-kriteria').find('#w0').kvDatepicker();
               $('.content-kriteria').find('#w0-2').kvDatepicker();
            }
        });

    });



    // Tampilkan Frekuensi
    var form = $('#form-SearchFilter');
    $('#tampilkan_frekuensi').click(function(){
        $.ajax({
            type:\"POST\",
            url:'show-pdf?tampilkan=laporan-peminjaman-frekuensi',
            data:form.serialize(),
            success: function(response){
                console.log(response);  
                $( '#show-pdf-content' ).html( response ); 
                $('#export-excel-laporan-peminjaman-frekuensi').show();
                $('#export-excel-odt-laporan-peminjaman-frekuensi').show();
                $('#export-word-laporan-peminjaman-frekuensi').show();
                $('#export-odt-laporan-peminjaman-frekuensi').show();
                $('#export-pdf-laporan-peminjaman-frekuensi').show();
                $('#export-excel-laporan-peminjaman-data').hide();
                $('#export-excel-odt-laporan-peminjaman-data').hide();
                $('#export-word-laporan-peminjaman-data').hide();
                $('#export-odt-laporan-peminjaman-data').hide();
                $('#export-pdf-laporan-peminjaman-data').hide();
            }
        });
    });
    $('#tampilkan_data').click(function(){
        $.ajax({
            type:\"POST\",
            url:'show-pdf?tampilkan=laporan-peminjaman-data',
            data:form.serialize(),
            success: function(response){
                console.log(response);  
                $( '#show-pdf-content' ).html( response ); 
                $('#export-excel-laporan-peminjaman-frekuensi').hide();
                $('#export-excel-odt-laporan-peminjaman-frekuensi').hide();
                $('#export-word-laporan-peminjaman-frekuensi').hide();
                $('#export-odt-laporan-peminjaman-frekuensi').hide();
                $('#export-pdf-laporan-peminjaman-frekuensi').hide();
                $('#export-excel-laporan-peminjaman-data').show();
                $('#export-excel-odt-laporan-peminjaman-data').show();
                $('#export-word-laporan-peminjaman-data').show();
                $('#export-odt-laporan-peminjaman-data').show();
                $('#export-pdf-laporan-peminjaman-data').show();
            }
        });
    });
    $('#export-excel-laporan-peminjaman-frekuensi').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-excel-laporan-peminjaman-frekuensi',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-excel-laporan-peminjaman-frekuensi')
              }
            });
            
    });
    $('#export-excel-odt-laporan-peminjaman-frekuensi').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-excel-odt-laporan-peminjaman-frekuensi',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-excel-odt-laporan-peminjaman-frekuensi')
              }
            });
            
    });
    $('#export-excel-laporan-peminjaman-data').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-excel-laporan-peminjaman-data',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-excel-laporan-peminjaman-data')
              }
            });
            
    });
    $('#export-excel-odt-laporan-peminjaman-data').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-excel-odt-laporan-peminjaman-data',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-excel-odt-laporan-peminjaman-data')
              }
            });
            
    });
    $('#export-word-laporan-peminjaman-frekuensi').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-word-laporan-peminjaman-frekuensi',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-word-laporan-peminjaman-frekuensi?type=doc')
              }
            });
            
    });
    $('#export-odt-laporan-peminjaman-frekuensi').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-odt-laporan-peminjaman-frekuensi',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-word-laporan-peminjaman-frekuensi?type=odt')
              }
            });
            
    });
    $('#export-word-laporan-peminjaman-data').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-word-laporan-peminjaman-data',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-word-laporan-peminjaman-data?type=doc')
              }
            });
            
    });
    $('#export-odt-laporan-peminjaman-data').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-odt-laporan-peminjaman-data',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-word-laporan-peminjaman-data?type=odt')
              }
            });
            
    });
    $('#export-pdf-laporan-peminjaman-frekuensi').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-pdf-laporan-peminjaman-frekuensi',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-pdf-laporan-peminjaman-frekuensi')
              }
            });
            
    });
    $('#export-pdf-laporan-peminjaman-data').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-pdf-laporan-peminjaman-data',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-pdf-laporan-peminjaman-data')
              }
            });
            
    });
    $('#reset').click(function(){
        location.reload();
    });

");
?>
