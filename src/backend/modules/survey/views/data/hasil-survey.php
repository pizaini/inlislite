<?php


use drsdre\wizardwidget\WizardWidget;

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

use dosamigos\highcharts\HighCharts;

//Model
use common\models\SurveyPilihan;
use common\models\SurveyIsian;


?>


<?php 

foreach ($chartContent as $chartContent) {
    echo $chartContent;
    echo "<hr>";
}

// echo Html::a('Kembali ke survey', Url::to(['/survey']),['class' => 'btn btn-lg btn-primary pull-right','data-pjax'=>'0','style'=>'margin-bottom: 30px;' ]);

?>