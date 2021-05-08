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

$this->title = yii::t('app','Laporan Ucapan Terima Kasih');
$this->params['breadcrumbs'][] = $this->title;


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

                    <!-- Pilih Nama Sumber Perolehan -->
                    <div class="form-group">
                        <label for="namaSumber" class="col-sm-2 control-label"><?= Yii::t('app','Nama Sumber Perolehan') ?></label>

                        <div id="" class="col-sm-8 content-partners" >
                        </div>
                    </div>
                    <!-- /Pilih Nama Sumber Perolehan  -->
                    <!-- Pilih Jenis Perolehan -->
                    <div class="form-group">
                        <label for="JenisPerolehan" class="col-sm-2 control-label"><?= Yii::t('app','Jenis Perolehan') ?></label>

                        <div id="" class="col-sm-8 content-collectionsources" >
                        </div>
                    </div>
                    <!-- /Pilih Jenis Perolehan  -->
                    <!-- Pilih Tanggal Perolehan -->
                    <div class="form-group">
                        <label for="tanggalPerolehan" class="col-sm-2 control-label"><?= Yii::t('app','Tanggal Perolehan') ?></label>

                        <div id="" class="col-sm-8 content-tanggal" >
                            <div class="col-sm-6 padding0">
                            <?=  DatePicker::widget([
                                'name' => 'perolehan_date', 
                                'class' => 'col-sm-6',
                                // 'type' => DatePicker::TYPE_RANGE,
                                'value' => date('d-m-Y'),
                                // 'options' => ['placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Date')],
                                'pluginOptions' => [
                                'format' => 'dd-mm-yyyy',
                                'todayHighlight' => true,
                                'autoclose'=>true,
                                'id' => 'pilihTanggalPerolehan',
                                ]
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- /Pilih Tanggal Perolehan  -->
                 

                    <div class="form-group">
                        <label for="kop" class="col-sm-2 control-label"><?= Yii::t('app','Kop') ?> </label>

                        <div class="col-sm-10 row">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="kop" id="kop"> <?= yii::t('app','Ya / Tidak')?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="form-group padding0">
                    <div class="col-sm-10 col-sm-offset-2 padding0">
                        <button id="tampilkan_data" type="button" class="btn btn-sm btn-primary"><?= Yii::t('app','Tampilkan') ?></button>
                        <div class="btn-group" style="cursor:pointer;">
                           <button type="button" id="export" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 4px 12px !important; display: none;">
                                Export   <span class="caret"></span>
                           </button>
                           <ul class="dropdown-menu">
                             <li><a id="export-excel-ucapan-terima-kasih">Excel-data</a></li>
                             <li><a id="export-excel-odt-ucapan-terima-kasih">Open-Office-Excel-data</a></li>
                             <li><a id="export-word-ucapan-terima-kasih">Word-data</a></li>
                             <li><a id="export-odt-ucapan-terima-kasih">Open-Office-Word-data</a></li>
                             <li><a id="export-pdf-ucapan-terima-kasih">PDF-data</a></li>
                           </ul>
                        </div> 
                        <button type="button" onclick="location.reload();" class="btn btn-sm btn-warning"><?= Yii::t('app','Reset') ?> <?= Yii::t('app','Kriteria') ?> </button>
                    </div>
                   
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
    </form> 


    <!-- Hanya untuk prasarat select2 agar js terpanggil -->
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


<?php
$this->registerJs("

    $.fn.select2.defaults.set('theme', 'krajee');

    $.get('load-filter-kriteria',{kriteria : 'partners'},function(data){
    
       $( '.content-partners' ).html( data ); 
       $('.content-partners').find('.select2').select2({
        // allowClear: true,
        }); 
    });

    $.get('load-filter-kriteria',{kriteria : 'collectionsources'},function(data){
    
       $( '.content-collectionsources' ).html( data ); 
       $('.content-collectionsources').find('.select2').select2({
        // allowClear: true,
        }); 
    });



    var form = $('#form-SearchFilter');
    $('#tampilkan_data').click(function(){
        $.ajax({
            type:\"POST\",
            url:'show-pdf?tampilkan=dataUcapanTerimakasih',
            data:form.serialize(),
            success: function(response){
                console.log(response);  
                $( '#show-pdf-content' ).html( response );
                $('#export-excel-ucapan-terima-kasih').show(); 
                $('#export-excel-odt-ucapan-terima-kasih').show(); 
                $('#export-word-ucapan-terima-kasih').show(); 
                $('#export-odt-ucapan-terima-kasih').show(); 
                $('#export-pdf-ucapan-terima-kasih').show(); 
            }
        });
    });

    $('#export-excel-ucapan-terima-kasih').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-excel-ucapan-terima-kasih',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-excel-ucapan-terima-kasih')
              }
            });
            
    });
    $('#export-excel-odt-ucapan-terima-kasih').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-excel-odt-ucapan-terima-kasih',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-excel-odt-ucapan-terima-kasih')
              }
            });
            
    });
    $('#export-word-ucapan-terima-kasih').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-word-ucapan-terima-kasih',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-word-ucapan-terima-kasih?type=doc')
              }
            });
            
    });
    $('#export-odt-ucapan-terima-kasih').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-odt-ucapan-terima-kasih',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-word-ucapan-terima-kasih?type=odt')
              }
            });
            
    });
    $('#export-pdf-ucapan-terima-kasih').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-pdf-ucapan-terima-kasih',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-pdf-ucapan-terima-kasih')
              }
            });
            
    });

");
?>


