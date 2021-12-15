<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use kartik\widgets\Select2;


// Pilihan Filter Kriteria
$ckriteria = [
        'PublishLocation' => yii::t('app','Kota Terbit'),
        'Publisher' => yii::t('app','Nama Penerbit'),
        'PublishYear' => yii::t('app','Tahun Terbit'),
        'location_library' => yii::t('app','Lokasi Perpustakaan'),
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
        'no_panggil' => yii::t('app','Nomor Panggil')];
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