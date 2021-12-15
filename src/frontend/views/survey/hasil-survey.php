<?php


use chofoteddy\wizard\Wizard;
use drsdre\wizardwidget\WizardWidget;

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

use dosamigos\highcharts\HighCharts;

//Model
use common\models\SurveyPilihan;
use common\models\SurveyIsian;

$this->title = 'Survey Pemustaka';
Yii::$app->view->params['subTitle'] = '<h3 style="padding-top: 15px;">Survey <br> '.$model['NamaSurvey'].'<h3>';

?>



<?php 
// // Variable label untuk Diagram PIE
// $labelChart = array();

// // Variable untuk diagram batang
// $valueChart = array();
// $categoriesChart = array();

// foreach ($forChart as $forChart) {

//     array_push($labelChart, ['y' => intval($forChart['ChoosenCount']),
//         'name' => $forChart['Pilihan']]);

//     array_push($valueChart,intval($forChart['ChoosenCount']));
//     array_push($categoriesChart,$forChart['Pilihan']);
// }

?>

<?php 
// echo HighCharts::widget([
//     'clientOptions' => [
//         'chart' => [
//                 'type' => 'pie'
//         ],
//         'title' => [
//              'text' => 'Survey Pilihan'
//              ],

//         'tooltip' => [
//              'pointFormat' => '{series.name}: <b>{point.percentage:.1f}%</b>'
//              ],

//         'plotOptions' => [
//              'pie' => [
//                  'allowPointSelect' => true,
//                  'cursor' => 'pointer',
//                  'dataLabels' => ['enabled' => false],
//                  'showInLegend' => true,
//                  ],
//              ],
//         'series' => [
//             [
//                 'name' => 'Pilihan',
//                 'colorByPoint' => 'true',
//                 'data' => 
//                     $labelChart
//                 ,
//             ]
//         ],
//     ]
// ]);
 ?>


<?php 
// echo HighCharts::widget([
//     'clientOptions' => [
//         'chart' => [
//                 'type' => 'column'
//         ],
//         'title' => [
//              'text' => 'Survey Pilihan'
//              ],
//         'xAxis' => [
//             'categories' => $categoriesChart
//         ],
//         'yAxis' => [
//             'title' => [
//                 'text' => 'Diagram Batang Survey Pilihan'
//             ]
//         ],
//         'series' => [
//             [
//             'name'=>'Pilihan',
//             'data' => $valueChart
//             ]
//         ]
        
//     ]
// ]);
?>



<?php 
if(isset($chartContent)){
	foreach ($chartContent as $chartContent) {
	    echo $chartContent;
	    echo "<hr>";
	}	
}

if(isset($contentIsian)){
	foreach ($contentIsian as $contentIsian) {
		echo $contentIsian;
	    echo "<hr>";
	}
}


echo Html::a('Kembali ke survey', Url::to(['/survey']),['class' => 'btn btn-lg btn-primary pull-right','data-pjax'=>'0','style'=>'margin-bottom: 30px;' ]);


?>