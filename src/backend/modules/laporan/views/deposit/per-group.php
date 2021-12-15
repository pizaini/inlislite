<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use kartik\grid\GridView;
use kartik\date\DatePicker;

use kartik\widgets\Select2;
use kartik\widgets\ActiveForm;

use yii\widgets\Pjax;
use yii\web\JsExpression;
use common\models\DepositGroupWs;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\LockersSearch $searchModel
 */

$this->title = yii::t('app','SSKCKR Per Group');
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

                    <!-- Pilih Kriteria -->
                    <div class="form-group multi-field-wrapper" id="pilihan-Kriteria">


                        <!-- Group all Content and append here-->

                            
                            <!-- Group plus minus dan pilih kriteria -->
                            <!-- Hanya untuk prasarat select2 agar js terpanggil -->
                            <div hidden="hidden" class="col-sm-12 padding0">
                                <?= Select2::widget([
                                    'name' => '',
                                    'data' => [],
                                    'options' => [],
                                    ]); ?>
                            </div>
                            <!-- /Group plus minus dan pilih kriteria -->
                            <div class="form-group">
                                <label for="namaSumber" class="col-sm-2 control-label"><?= Yii::t('app','Nama Sumber Perolehan') ?></label>

                                <div id="" class="col-sm-8 content-group" >
                                </div>
                            </div>
                            

                    </div>
                    <!-- /Pilih Kriteria -->

                    <!-- <div class="form-group">
                        <label for="kop" class="col-sm-2 control-label"><?//= Yii::t('app','Kop') ?> </label>

                        <div class="col-sm-10 row">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="kop"> <?//= yii::t('app','Ya / Tidak')?>
                                </label>
                            </div>
                        </div>
                    </div> -->
                </div>
                <!-- /.box-body -->
                <div class="form-group padding0">
                    <div class="col-sm-10 col-sm-offset-2 padding0">
                        <button id="tampilkan_frekuensi" type="button" class="btn btn-sm btn-primary"><?= Yii::t('app','Tampilkan') ?></button>
                        <div class="btn-group" style="cursor:pointer;">
                           <button type="button" id="export" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 4px 12px !important; display: none;">
                                Export   <span class="caret"></span>
                           </button>
                           <ul class="dropdown-menu">
                             <li><a id="export-excel">Donwload-Excel</a></li>
                             <!-- <li><a id="export-excel-data">Excel-data</a></li> -->
                             <li><a id="export-excel-odt">Donwload-Open-Office-Excel</a></li>
                             <!-- <li><a id="export-excel-odt-data">Open-Office-Excel-data</a></li> -->
                             <li><a id="export-word">Donwload-Word</a></li>
                             <!-- <li><a id="export-word-data">Word-data</a></li> -->
                             <li><a id="export-word-odt">Donwload-Open-Office-Word</a></li>
                             <!-- <li><a id="export-word-odt-data">Open-Office-Word-data</a></li> -->
                             <li><a id="export-pdf">Donwload-PDF</a></li>
                             <!-- <li><a id="export-pdf-data">PDF-data</a></li> -->
                           </ul>
                        </div>  
                        <button id="reset" type="button" class="btn btn-sm btn-warning"><?= Yii::t('app','Reset') ?> <?= Yii::t('app','Kriteria') ?> </button>
                    </div>
                   
                </div>
                <!-- /.box-footer -->
        </div>
    </form> 



    <hr class="col-sm-12">
    
    <div id="show-pdf-content" class="col-sm-12">
        <!-- Nanti show PDF Disini -->
    </div>

</div>

<?php
$this->registerJs("

    $.fn.select2.defaults.set('theme', 'krajee');
    $.get('load-filter-kriteria',{kriteria : 'group'},function(data){
    
       $( '.content-group' ).html( data ); 
       $('.content-group').find('.select2').select2({
        // allowClear: true,
        }); 
    });

    var i = 1;
    $('.add-field').click(function(e) {    
        $.get('load-selecter-laporan-periodik',{ i : i },function(data){
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
               $( '.content-kriteria ' ).html(data); 
               
                $('.content-kriteria select').select2({
                    
                });
            }
        });

    });

    // Tampilkan Frekuensi
    var form = $('#form-SearchFilter');
    $('#tampilkan_frekuensi').click(function(){
        $.ajax({
            type:\"POST\",
            url:'show-pdf?tampilkan=laporan-deposit-group',
            data:form.serialize(),
            success: function(response){
                console.log(response);  
                $('#show-pdf-content' ).html( response ); 
                $('#export-excel').show();
                $('#export-excel-odt').show();
                $('#export-word').show();
                $('#export-word-odt').show();
                $('#export-pdf').show();
            }
        });
    });

    $('#export-excel').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-excel-deposit-group',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-excel-deposit-group')
              }
            });
            
    });

    $('#export-excel-odt').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-excel-odt-deposit-group',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-excel-odt-deposit-group')
              }
            });
            
    });

    $('#export-word').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-word-deposit-group',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-word-deposit-group?type=doc')
              }
            });
            
    });
    $('#export-word-odt').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-word-odt-deposit-group',
            data:form.serialize(),
            async: false,
              context: document.body,
              success: function(){ 
                 window.location.assign('export-word-deposit-group?type=odt')
              }
            });
            
    });
    
    $('#export-pdf').click(function(){
        $.ajax({
            type:\"POST\",
            url: 'show-pdf?tampilkan=export-pdf-deposit-group',
            data:form.serialize(),

              context: document.body,
              success: function(){ 
                 window.location.assign('export-pdf-deposit-group')
              }
            });
            
    });

    $('#reset').click(function(){
        location.reload();
    });
");
?>

