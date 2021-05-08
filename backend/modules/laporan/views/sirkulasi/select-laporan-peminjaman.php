<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use kartik\widgets\Select2;


// Pilihan Filter Kriteria
$ckriteria = [
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
?>
<!-- Group plus minus dan pilih kriteria -->
<div class="row col-sm-12 gap-padding10 multi-field">
    <div class="col-sm-4 padding0">
        <div class="input-group">

            <div class="input-group-btn">
                <button type="button" class="btn btn-danger remove-field"><span class="glyphicon glyphicon-minus-sign"></span></button>
                <!-- <button type="button" class="btn btn-success add2"><span class="glyphicon glyphicon-plus-sign"></span></button> -->
            </div>

            <div class="input-group">
                <?= Html::dropDownList( 'kriterias[]',
                    'selected option',  
                    $ckriteria, 
                    ['class' => 'col-sm-12 select2 pilihKriteria'.$i,'placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Kriteria')]
                    ); ?>
            </div>
        </div>
    </div>

    <div id="" class="col-sm-8 content-kriteria<?= $i ?>" >

    </div>
</div>
<!-- /Group plus minus dan pilih kriteria -->

<script type="text/javascript">
    $('.pilihKriteria<?= $i ?>').select2();
    $('.remove-field').click(function(e) {
        $(this).parent('.input-group-btn').parent('.input-group').parent('.col-sm-4').parent('.multi-field').remove();
        //klo tinggal satu ga bisa di apus
        // if($('.multi-field').length > 1) {
            // $(this).parent('.input-group-btn').parent('.input-group').parent('.col-sm-4').parent('.multi-field').remove();
        // }
    });

        // Pilih Kriteria per Row
    $('#pilihan-Kriteria').on('change','.pilihKriteria<?= $i ?>',function(){ 
        $( '.content-kriteria<?= $i ?> ' ).html('<div style=\"padding-top: 10px;\">Loading...</div>'); 
        var kriteria = $(this).val();
        console.log(kriteria);
     
        $.get('load-filter-kriteria',{kriteria : kriteria},function(data){
            if (data == '') 
            {
                $( '.content-kriteria<?= $i ?>' ).html( '' );   
            } 
            else 
            {
                $( '.content-kriteria<?= $i ?>' ).html( data ); 
                $('.content-kriteria<?= $i ?>').find('.select2').select2({
                // allowClear: true,
                });
               // $('.content-kriteria<?= $i ?>').find('.krajee-datepicker').datepicker();
               // $('.content-kriteria<?= $i ?>').find('.krajee-datepicker').datepicker("show");

            }
        });
    });
</script>