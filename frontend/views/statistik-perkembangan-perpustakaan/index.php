<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\detail\DetailView;
use yii\widgets\Pjax;
use yii\web\JsExpression;
// use yii\widgets\DetailView;

use dosamigos\highcharts\HighCharts;
use kartik\date\DatePicker;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\SurveyIsianSearch $searchModel
 */


$now = date("d-m-Y");
$forChart = array();

$this->title = Yii::t('app', 'Statistik Perkembangan Perpustakaan');
Yii::$app->view->params['subTitle'] = '<h3 style="padding-top: 15px;">'.Yii::t('app', 'Selamat Datang').'<br>'.Yii::t('app', 'Statistik Perkembangan Perpustakaan').'<h3>';


//print_r(implode($valNonAnggota));

// $valArray = [1=> 0,2=> 0,3=> 0,4=> 0,5=> 0,6=> 0,7=> 0,8=> 0,9=> 0,10=> 0,11=> 0,12=> 0,];
// $valNonAnggota = $valArray;
// $valAnggota = $valArray;
// $valRombongan = $valArray;
// $valPertumbuhananggota = $valArray;


// foreach ($nonanggota as $key => $value) {
//     $valNonAnggota[$value['bulan']] = intval($value['jumlah']); 
//     // echo $value['bulan'];
// }

// foreach ($anggota as $key => $value) {
//     $valAnggota[$value['bulan']] = intval($value['jumlah']); 
//     // echo $value['bulan'];
// }
// foreach ($rombongan as $key => $value) {
//     $valRombongan[$value['bulan']] = intval($value['jumlah']); 
//     // echo $value['bulan'];
// }

// foreach ($pertumbuhananggota as $key => $value) {
//     $valPertumbuhananggota[$value['bulan']] = intval($value['jumlah']); 
//     // echo $value['bulan'];
// }
//print_r($valPertumbuhananggota);

//print_r(implode($valNonAnggota));


$month = array();
$year = range(2015, date('Y'));
rsort($year);
$y=array();

for ($m=1; $m<=12; $m++) 
{
     $month[$m] = date('F', mktime(0,0,0,$m, 1, date('Y')));
     // echo '<pre>';print_r($month); echo '</pre>';
}

foreach ($year as $year => $value) {
    $y[$value] = $value;
}


?>


<?php Pjax::begin();?>

<!-- <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/funnel.js"></script> -->
<style type="text/css">
.widget-user-2 .widget-user-header {
        padding: 20px;
        border-top-right-radius: 3px;
        border-top-left-radius: 3px;
}

.widget-user-2 .widget-user-image>img {
        width: 65px;
        height: 65px;
        float: left;
        border: none;
}
.widget-user-2 .widget-user-username, .widget-user-2 .widget-user-desc {
        margin-left: 75px;
        margin-top: 0;
}
.nav > li {
        position: relative;
        display: block;
        padding: 10px 15px;
}

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


<section class="content">
    <div class="col-sm-12">
        <!-- <div class="col-sm-1"></div> -->
        <!-- <div class="col-sm-10"> -->
            <!-- <center> -->
                <form action="statistik-perkembangan-perpustakaan" method="GET">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><?= yii::t('app', 'Pilih Periode') ?></h3>
                        <div class="box-tools pull-right">
                            <!-- <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <!-- Pilih Periode -->
                        
                            <div id="SearchFilter" class="form-group">
                                <label for="pilihPeriode" class="col-sm-1 control-label"><?= Yii::t('app','Periode')//.' '.Yii::t('app','Pengadaan') ?></label>

                                <div class="col-sm-9">
                                    <div class="col-sm-3 padding0">
                                        <?= Select2::widget([
                                        'name' => 'periode',
                                        'data' => ['bulanan' => yii::t('app','Bulanan'),'tahunan' => yii::t('app','Tahunan')],
                                        'options' => [
                                        // 'placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Periode'),
                                        'id' => 'pilihPeriode',
                                        'class' => 'select2'
                                        ],
                                        ]); ?>
                                    </div>
                                    
                                    
                                    <!-- Bulanan -->
                                    <div class="col-sm-9" id="periodeBulanan">
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

                                <div class="col-sm-2">
                                    <!-- <div class="btn-group"> -->
                                        <button id="tampilkan_data" type="submit" class="btn btn-sm btn-primary"><?= Yii::t('app','<i class="fa fa-search"></i>') ?></button>
                                        <button id="reset" type="button" title="Reset" class="btn btn-sm btn-warning"><?= Yii::t('app','<i class="fa fa-refresh"></i>') ?></button>    
                                    <!-- </div> -->
                                </div>

                            </div>
                        
                        
                        <!-- /Pilih Periode -->
                    </div>
                </div>
                </form>
            <!-- </center> -->
        <!-- </div> -->
        <!-- <div class="col-sm-1"></div> -->
    </div>
    <div class="col-sm-8">
        <!-- Line Chart Pertumbuhan Jumlah Kunjungan -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><?= yii::t('app', 'Pertumbuhan Jumlah Kunjungan') ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">

                <?php 
                echo HighCharts::widget([
                    'clientOptions' => [
                        // 'chart' => [
                        //         'type' => 'line'
                        // ],
                        'title' => [
                             'text' => yii::t('app', 'Berdasarkan Jenis Pengunjung'),
                             'x' => -20
                             ],
                        'subtitle' => [
                             'text' => $rangeTahun,
                             'x' => -20
                             ],
                        'xAxis' => [
                            'categories' => $catbulan
                        ],
                        'yAxis' => [
                            'title' => [
                                'text' => yii::t('app', 'Jumlah Anggota')
                            ],
                            'plotLines' => [
                            [ 
                            'value' => 0,
                            'width' => 1,
                            'color' => '#808080'
                            ]
                            ]
                        ],
                        'tooltip' => [
                            
                            'valueSuffix'=>' Pengunjung'
                            
                        ],
                        'legend' => [
                            
                            'layout' => 'vertical',
                            'align' => 'right',
                            'verticalAlign' => 'middle',
                            'borderWidth' => 0
                            
                        ],
                        'series' => [
                            [
                            'name'=> yii::t('app', 'NonAnggota'),
                            'data' => $valNonAnggota
                            ],
                            [
                            'name'=> yii::t('app', 'Anggota'),
                            'data' => $valAnggota
                            ],
                            [
                            'name'=> yii::t('app', 'Rombongan'),
                            'data' => $valRombongan
                            ],
                        ],
                        
                    ]
                ]);
                ?>
            </div><!-- /.box-body -->   
        </div>
    </div>

    <!-- Range Umur -->
    <div class="col-sm-4">
        <!-- Pie Chart Pertumbuhan Jumlah Kunjungan Range Umur-->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><?= yii::t('app', 'Total Kunjungan Anggota') ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php 
                echo HighCharts::widget([
                    'clientOptions' => [
                        'chart' => [
                                'plotBackgroundColor' => null,
                                'plotBorderWidth' => null,
                                'plotShadow' => false,
                                'type' => 'pie',
                        ],
                        'title' => [
                             'text' => yii::t('app', 'Berdasarkan Kelompok Usia')
                             ],

                        'tooltip' => [
                             'pointFormat' => '{series.name}: <b>{point.percentage:.1f}%</b>'
                             ],

                        'plotOptions' => [
                             'pie' => [
                                 'allowPointSelect' => true,
                                 'cursor' => 'pointer',
                                 'dataLabels' => [
                                    'enabled' => true,
                                    'format' => '<b>{point.name}</b>: {point.percentage:.1f}%',
                                    'style' => ['color'=> ('Highcharts.theme && Highcharts.theme.contrastTextColor') || 'black'],
                                    ],
                                 'showInLegend' => true,
                                 ],
                             ],
                        'series' => [
                            [
                                'name' => 'Total',
                                'colorByPoint' => 'true',
                                'data' =>
                                    $rangeUmur
                            ]
                        ],
                    ]
                ]);
                 ?>
            </div><!-- /.box-body -->   
        </div>
    </div><!-- Range Umur -->


    <!-- Pertumbuhan Jumlah Anggota -->
    <div class="col-sm-8">
        <!-- Line Chart Pertumbuhan Jumlah Kunjungan -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><?= yii::t('app', 'Pertumbuhan Jumlah Anggota') ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div id="w30"></div>


                <?php 
                // echo HighCharts::widget([
                //     'clientOptions' => [
                //         'chart' => [
                //                 'type' => 'column'
                //         ],
                //         'title' => [
                //              'text' => 'Berdasarkan Jenis Anggota',
                //         ],
                //         'subtitle' => [
                //              'text' => $rangeTahun,
                //              ],
                //         'xAxis' => [
                //             'categories' => $catbulan,
                //             'crosshair' => true,
                //         ],
                //         'yAxis' => [
                //             'min' => 0,
                //             'title' => [
                //                 'text' => 'Jumlah Anggota'
                //             ],
                //         ],
                //         'tooltip' => [
                //             'headerFormat' => '<span style="font-size:10px">{point.key}</span><table>',
                //             'pointFormat' => '<tr><td style="color:{series.color};padding:0">{series.name}: </td> <td style="padding:0"><b>{point.y} </b></td></tr>',
                //             'footerFormat' => '</table>',
                //             'shared'=>true,
                //             'useHTML'=> true,
                //         ],
                //         'plotOptions' => [
                //             'column' => [
                //                 'pointPadding'=>0.2,
                //                 'borderWidth'=>0,
                //             ],
                //         ],
                //         'series' => $totalJenis
                //     ]
                // ]);
                ?>




                <?php 
                echo HighCharts::widget([
                    'clientOptions' => [
                        'chart' => [
                                'type' => 'area'
                        ],
                        'title' => [
                             'text' => yii::t('app', 'Berdasarkan Jenis Anggota'),
                        ],
                        'subtitle' => [
                             'text' => $rangeTahun,
                             ],
                        'xAxis' => [
                            'categories' => $catbulan,
                            'tickmarkPlacement' => 'on',
                            'title' => [
                                'enabled' => false
                            ],
                        ],
                        'yAxis' => [
                            'title' => [
                                'text' => yii::t('app', 'Jumlah Anggota')
                            ],
                        ],
                        'tooltip' => [
                            'shared'=>true,
                        ],
                        'plotOptions' => [
                            'area' => [
                                'stacking'=>'normal',
                                'lineColor'=>'#666666',
                                'lineWidth'=>1,
                                'marker'=>[
                                    'lineWidth'=> 1,
                                    'lineColor'=> '#666666',
                                ],
                            ],
                        ],
                        'series' => 
                            $totalJenis, 
                    ]
                ]);
                ?>
            </div><!-- /.box-body -->   
        </div>
    </div><!-- Pertumbuhan Jumlah Anggota -->



    <!-- Jenis Pendidikan -->
    <div class="col-sm-4">
        <!-- Pie Chart Pertumbuhan Jumlah Kunjungan Range Umur-->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><?= yii::t('app', 'Total Jumlah Anggota') ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php 
                echo HighCharts::widget([
                    'clientOptions' => [
                        'chart' => [
                            'type'=> 'pie',
                            'options3d'=> [
                                'enabled'=> true,
                                'alpha'=> 45
                            ]
                        ],
                        'title' => [
                             'text' => yii::t('app', 'Berdasarkan Pendidikan'),
                             'align' => 'center',
                             // 'verticalAlign' => 'top',
                             //'y' => 40,
                        ],

                        'subtitle'=> [
                            'text'=> $rangeTahun
                        ],

                        'tooltip' => [
                             'pointFormat' => '{series.name}: <b>{point.percentage:.0f}%</b>'
                        ],

                        'plotOptions'=> [
                            'pie'=> [
                                'dataLabels' => [
                                    'enabled' => true,
                                    // 'format' => '<b>{point.name}</b>: {point.percentage:.0f} %',                                  
                                    'format' => '{point.name}: {point.percentage:.0f}%',                                  
                                    'distance' => -50,
                                    'style' => ['fontWeight'=> 'bold','color'=> 'white','textShadow'=> '0px 1px 2px black'],
                                    ],


                                'innerSize'=> 100,
                                'depth'=> 45
                            ]
                        ],
                        'series'=> [[
                            'name'=> 'Jumlah anggota',
                            'data'=> $jenisPendidikan
                        ]
                    ]]
                ]);
                 ?>
            </div><!-- /.box-body -->   











        </div>
    </div><!-- Jenis Pendidikan -->




    <!-- Pertumbuhan Jumlah Koleksi -->
    <div class="col-sm-8">
        <!-- Bar Chart Pertumbuhan Jumlah Koleksi -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><?= yii::t('app', 'Pertumbuhan Jumlah Koleksi') ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php 
                echo HighCharts::widget([
                    'clientOptions' => [
                        'chart' => [
                                'type' => 'column'
                        ],
                        'title' => [
                             'text' => yii::t('app', 'Pertumbuhan Jumlah Koleksi'),
                        ],
                        'subtitle' => [
                             'text' => $rangeTahun,
                             ],
                        'xAxis' => [
                            'categories' => $catbulan,
                            'crosshair' => true,
                        ],
                        'yAxis' => [
                            'min' => 0,
                            'title' => [
                                'text' => 'Jumlah Koleksi'
                            ],
                        ],
                        'tooltip' => [
                            'headerFormat' => '<span style="font-size:10px">{point.key}</span><table>',
                            'pointFormat' => '<tr><td style="color:{series.color};padding:0">{series.name}: </td> <td style="padding:0"><b>{point.y} </b></td></tr>',
                            'footerFormat' => '</table>',
                            'shared'=>true,
                            'useHTML'=> true,
                        ],
                        'plotOptions' => [
                            'column' => [
                                'pointPadding'=>0.2,
                                'borderWidth'=>0,
                            ],
                        ],
                        'series' => $jumlahKoleksi
                    ]
                ]);
                ?>
            </div><!-- /.box-body -->   
        </div>
    </div><!-- Pertumbuhan Jumlah Koleksi -->





    <!-- Klas Subject -->
    <div class="col-sm-4">
        <!-- Pie Chart Klas Subject-->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><?= yii::t('app', 'Total Jumlah Koleksi') ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php 
                echo HighCharts::widget([
                    'clientOptions' => [
                        'chart' => [
                                'plotBackgroundColor' => null,
                                'plotBorderWidth' => null,
                                'plotShadow' => false,
                                'type' => 'pie',
                        ],
                        'title' => [
                             'text' => yii::t('app', 'Berdasarkan Klas Subject')
                             ],

                        'tooltip' => [
                             'pointFormat' => '{series.name}: <b>{point.percentage:.1f}%</b>'
                             ],

                        'plotOptions' => [
                             'pie' => [
                                 'allowPointSelect' => true,
                                 'cursor' => 'pointer',
                                 'dataLabels' => [
                                    'enabled' => true,
                                    'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    'style' => ['color'=> ('Highcharts.theme && Highcharts.theme.contrastTextColor') || 'black'],
                                    ],
                                 'showInLegend' => true,
                                 ],
                             ],
                        'series' => [
                            [
                                'name' => 'Umur',
                                'colorByPoint' => 'true',
                                'data' =>
                                    $kelasSubject
                            ]
                        ],
                    ]
                ]);
                 ?>
            </div><!-- /.box-body -->   
        </div>
    </div><!-- Klas Subject -->







    <!-- Pertumbuhan Jumlah Koleksi Dipinjam-->
    <div class="col-sm-8">
        <!-- Bar Chart Pertumbuhan Jumlah Koleksi Dipinjam -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><?= yii::t('app', 'Pertumbuhan Jumlah Koleksi Dipinjam') ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php 
                echo HighCharts::widget([
                    'clientOptions' => [
                        'chart' => [
                                'type' => 'column'
                        ],
                        'title' => [
                             'text' => yii::t('app', 'Pertumbuhan Jumlah Koleksi Dipinjam'),
                        ],
                        'subtitle' => [
                             'text' => $rangeTahun,
                             ],
                        'xAxis' => [
                            'categories' => $catbulan,
                            'crosshair' => true,
                        ],
                        'yAxis' => [
                            'min' => 0,
                            'title' => [
                                'text' => yii::t('app', 'Jumlah Koleksi')
                            ],
                        ],
                        'tooltip' => [
                            'headerFormat' => '<span style="font-size:10px">{point.key}</span><table>',
                            'pointFormat' => '<tr><td style="color:{series.color};padding:0">{series.name}: </td> <td style="padding:0"><b>{point.y} </b></td></tr>',
                            'footerFormat' => '</table>',
                            'shared'=>true,
                            'useHTML'=> true,
                        ],
                        'plotOptions' => [
                            'column' => [
                                'pointPadding'=>0.2,
                                'borderWidth'=>0,
                            ],
                        ],
                        'series' => $jumlahKoleksiDipinjam
                    ]
                ]);
                ?>
            </div><!-- /.box-body -->   
        </div>
    </div><!-- Pertumbuhan Jumlah Koleksi Dipinjam -->










    <!-- Klas Subject Koleksi Dipinjam-->
    <div class="col-sm-4">
        <!-- Pie Chart Klas Subject-->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><?= yii::t('app', 'Total Jumlah Koleksi Dipinjam') ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php 
                echo HighCharts::widget([
                    'clientOptions' => [
                        'chart' => [
                                'plotBackgroundColor' => null,
                                'plotBorderWidth' => null,
                                'plotShadow' => false,
                                'type' => 'pie',
                        ],
                        'title' => [
                             'text' => yii::t('app', 'Berdasarkan Klas Subject')
                             ],

                        'tooltip' => [
                             'pointFormat' => '{series.name}: <b>{point.percentage:.1f}%</b>'
                             ],

                        'plotOptions' => [
                             'pie' => [
                                 'allowPointSelect' => true,
                                 'cursor' => 'pointer',
                                 'dataLabels' => [
                                    'enabled' => true,
                                    'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    'style' => ['color'=> ('Highcharts.theme && Highcharts.theme.contrastTextColor') || 'black'],
                                    ],
                                 'showInLegend' => true,
                                 ],
                             ],
                        'series' => [
                            [
                                'name' => 'Umur',
                                'colorByPoint' => 'true',
                                'data' =>
                                    $kelasSubjectKolDipinjam
                            ]
                        ],
                    ]
                ]);
                 ?>
            </div><!-- /.box-body -->   
        </div>
    </div><!-- Klas Subject Koleksi Dipinjam -->






    <!-- Pertumbuhan Jumlah Koleksi Dibaca-->
    <div class="col-sm-8">
        <!-- Bar Chart Pertumbuhan Jumlah Koleksi Dibaca -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><?= yii::t('app', 'Pertumbuhan Jumlah Koleksi Dibaca') ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php 
                echo HighCharts::widget([
                    'clientOptions' => [
                        'chart' => [
                                'type' => 'column'
                        ],
                        'title' => [
                             'text' => yii::t('app', 'Pertumbuhan Jumlah Koleksi Dibaca') ,
                        ],
                        'subtitle' => [
                             'text' => $rangeTahun,
                             ],
                        'xAxis' => [
                            'categories' => $catbulan,
                            'crosshair' => true,
                        ],
                        'yAxis' => [
                            'min' => 0,
                            'title' => [
                                'text' => yii::t('app', 'Jumlah Koleksi')
                            ],
                        ],
                        'tooltip' => [
                            'headerFormat' => '<span style="font-size:10px">{point.key}</span><table>',
                            'pointFormat' => '<tr><td style="color:{series.color};padding:0">{series.name}: </td> <td style="padding:0"><b>{point.y} </b></td></tr>',
                            'footerFormat' => '</table>',
                            'shared'=>true,
                            'useHTML'=> true,
                        ],
                        'plotOptions' => [
                            'column' => [
                                'pointPadding'=>0.2,
                                'borderWidth'=>0,
                            ],
                        ],
                        'series' => $jumlahKoleksiDibaca
                    ]
                ]);
                ?>
            </div><!-- /.box-body -->   
        </div>
    </div><!-- Pertumbuhan Jumlah Koleksi Dibaca -->









    <!-- Klas Subject Koleksi Dibaca-->
    <div class="col-sm-4">
        <!-- Pie Chart Klas Subject-->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><?= yii::t('app', 'Total Jumlah Koleksi Dibaca') ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php 
                echo HighCharts::widget([
                    'clientOptions' => [
                        'chart' => [
                                'plotBackgroundColor' => null,
                                'plotBorderWidth' => null,
                                'plotShadow' => false,
                                'type' => 'pie',
                        ],
                        'title' => [
                             'text' => yii::t('app', 'Berdasarkan Klas Subject')
                             ],

                        'tooltip' => [
                             'pointFormat' => '{series.name}: <b>{point.percentage:.1f}%</b>'
                             ],

                        'plotOptions' => [
                             'pie' => [
                                 'allowPointSelect' => true,
                                 'cursor' => 'pointer',
                                 'dataLabels' => [
                                    'enabled' => true,
                                    'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    'style' => ['color'=> ('Highcharts.theme && Highcharts.theme.contrastTextColor') || 'black'],
                                    ],
                                 'showInLegend' => true,
                                 ],
                             ],
                        'series' => [
                            [
                                'name' => 'Total',
                                'colorByPoint' => 'true',
                                'data' =>
                                    $kelasSubjectKolDibaca
                            ]
                        ],
                    ]
                ]);
                 ?>
            </div><!-- /.box-body -->   
        </div>
    </div><!-- Klas Subject Koleksi Dibaca -->









</section>

    

<?php Pjax::end();?>





<?php
$this->registerJs("
    // Filter Periode
    $('#pilihPeriode').change(function(){
        var periode = $(this).val();
        // alert(periode);
        if (periode == 'bulanan') 
        {
            $('#periodeBulanan').show();
            $('#periodeTahunan').hide();
        }
        else 
        {
            $('#periodeBulanan').hide();
            $('#periodeTahunan').show();
        }
    });




   setInterval(function() {
      window.location.reload();
    }, 300000); 

     $(\"#box-widget\").activateBox();
    var chart;


    $(\"#reset\").click(function(){
        window.location.href = 'statistik-perkembangan-perpustakaan';
    });


");
?>