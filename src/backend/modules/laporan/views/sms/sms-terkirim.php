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

$this->title = yii::t('app','SMS Terkirim');
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
                    <div class="form-group">
                            <label for="" class="col-sm-2 control-label" style="margin-left:0px;"><?= Yii::t('app','Nama Anggota') ?></label>

                            <div id="" class="col-sm-8 content-raport" style="margin-left:-15px;" Name="kriteria-kataloger">
                            </div>
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
                             <li><a id="export-excel-sms-terkirim-frekuensi">Excel-frekuensi</a></li>
                             <li><a id="export-excel-odt-sms-terkirim-frekuensi">Open-Office-Excel-frekuensi</a></li>
                             <li><a id="export-word-sms-terkirim-frekuensi">Word-frekuensi</a></li>
                             <li><a id="export-word-odt-sms-terkirim-frekuensi">Open-Office-Word-frekuensi</a></li>
                             <li><a id="export-pdf-sms-terkirim-frekuensi">PDF-Frekuensi</a></li>
                             <li><a id="export-excel-sms-terkirim-data">Excel-data</a></li>
                             <li><a id="export-excel-odt-sms-terkirim-data">Open-Office-Excel-data</a></li>
                             <li><a id="export-word-sms-terkirim-data">Word-data</a></li>
                             <li><a id="export-word-odt-sms-terkirim-data">Open-Office-Word-data</a></li>
                             <li><a id="export-pdf-sms-terkirim-data">PDF-data</a></li>
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

    $.get('load-filter-kriteria',{kriteria : 'no_anggota'},function(data){
    
       $( '.content-raport' ).html( data ); 
       $('.content-raport').find('.select2').select2({
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
            url:'show-pdf?tampilkan=sms-terkirim-frekuensi',
            data:form.serialize(),
            success: function(response){
                console.log(response);  
                $( '#show-pdf-content' ).html( response );
                $('#export-excel-sms-terkirim-frekuensi').show(); 
                $('#export-excel-odt-sms-terkirim-frekuensi').show(); 
                $('#export-word-sms-terkirim-frekuensi').show(); 
                $('#export-word-odt-sms-terkirim-frekuensi').show(); 
                $('#export-pdf-sms-terkirim-frekuensi').show(); 
                $('#export-excel-sms-terkirim-data').hide(); 
                $('#export-excel-odt-sms-terkirim-data').hide(); 
                $('#export-word-sms-terkirim-data').hide(); 
                $('#export-word-odt-sms-terkirim-data').hide(); 
                $('#export-pdf-sms-terkirim-data').hide(); 
            }
        });
    });
    $('#tampilkan_data').click(function(){
            $.ajax({
                type:\"POST\",
                url:'show-pdf?tampilkan=sms-terkirim-data',
                data:form.serialize(),
                success: function(response){
                    console.log(response);  
                    $( '#show-pdf-content' ).html( response ); 
                    $('#export-excel-sms-terkirim-frekuensi').hide();
                    $('#export-excel-odt-sms-terkirim-frekuensi').hide(); 
                    $('#export-word-sms-terkirim-frekuensi').hide(); 
                    $('#export-word-odt-sms-terkirim-frekuensi').hide(); 
                    $('#export-pdf-sms-terkirim-frekuensi').hide(); 
                    $('#export-excel-sms-terkirim-data').show(); 
                    $('#export-excel-odt-sms-terkirim-data').show(); 
                    $('#export-word-sms-terkirim-data').show(); 
                    $('#export-word-odt-sms-terkirim-data').show(); 
                    $('#export-pdf-sms-terkirim-data').show(); 
                }
            });
        });
    $('#export-excel-sms-terkirim-frekuensi').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-excel-sms-terkirim-frekuensi',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-excel-sms-terkirim-frekuensi')
              }
            });
            
    });
    $('#export-excel-odt-sms-terkirim-frekuensi').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-excel-odt-sms-terkirim-frekuensi',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-excel-odt-sms-terkirim-frekuensi')
              }
            });
            
    });
    $('#export-word-sms-terkirim-frekuensi').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-word-sms-terkirim-frekuensi',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-word-sms-terkirim-frekuensi?type=doc')
              }
            });
            
    });
    $('#export-word-odt-sms-terkirim-frekuensi').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-word-sms-terkirim-frekuensi',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-word-sms-terkirim-frekuensi?type=odt')
              }
            });
            
    });
    $('#export-pdf-sms-terkirim-frekuensi').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-pdf-sms-terkirim-frekuensi',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-pdf-sms-terkirim-frekuensi')
              }
            });
            
    });
    $('#export-excel-sms-terkirim-data').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-excel-sms-terkirim-data',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-excel-sms-terkirim-data')
              }
            });
            
    });
    $('#export-excel-odt-sms-terkirim-data').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-excel-odt-sms-terkirim-data',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-excel-odt-sms-terkirim-data')
              }
            });
            
    });
    $('#export-word-sms-terkirim-data').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-word-sms-terkirim-data',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-word-sms-terkirim-data?type=doc')
              }
            });
            
    });
    $('#export-word-odt-sms-terkirim-data').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-word-sms-terkirim-data',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-word-sms-terkirim-data?type=odt')
              }
            });
            
    });


    $('#export-pdf-sms-terkirim-data').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-pdf-sms-terkirim-data',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-pdf-sms-terkirim-data')
              }
            });
            
    });
    $('#reset').click(function(){
        location.reload();
    });

");
?>
