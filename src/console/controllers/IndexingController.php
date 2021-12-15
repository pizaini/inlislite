<?php
namespace console\controllers;

use yii\console\Controller;
use Yii;
use common\components\ElasticHelper;
//no time limit
set_time_limit(0);
ini_set("memory_limit", "-1");

class IndexingController extends Controller{

	public function actionIndex()
    {
        $memAwal= memory_get_usage(true);
        $memAwal2 =self::convert($memAwal);
        echo 'Penggunaan Memory awal ='.$memAwal2. PHP_EOL;
        $time_start = microtime(true);
        ElasticHelper::CreateAllIndexAdvance();
        $time_end = microtime(true);
        echo 'Indexing selesai'. PHP_EOL;
        echo 'Processing for '.($time_end-$time_start).' seconds'. PHP_EOL;
        $memAkhir= memory_get_usage(true);
        $memAkhir2 =self::convert($memAkhir);
        echo 'Penggunaan Memory Akhir ='.$memAkhir2. PHP_EOL;
        echo 'Total Penggunaan Memory ='.self::convert($memAkhir-$memAwal). PHP_EOL;
        echo PHP_EOL;
    }


    function convert($size)
    {
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }


}