<?php
/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2016
 * @version 1.0.0
 * @author Andy Kurniawan <dodot.kurniawan@gmail.com>
 */

namespace backend\controllers;

use Yii;
use yii\base\DynamicModel;
use common\models\Settingparameters;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\components\Language;

$DB = array_merge(
        require(__DIR__ . '/../../common/config/main-local.php')
);

$connect = array_shift($DB['components']);
$connection = new \yii\db\Connection([
	'dsn' => $connect['dsn'],
	// 'dsn' => 'mysql:host=localhost;dbname=inlislite_v3_160',
    'username' => $connect['username'],
    'password' => $connect['password'],
    'charset' => $connect['charset'],
]);

$connection->open();
$options = $connection->createCommand('SELECT * FROM settingparameters WHERE settingparameters.Name = "language"')->queryAll();
$connection->close();
// echo '<pre>';print_r($connect);print_r($connection);die;echo '</pre>';
return ['lang' => $options[0]['Value']];


