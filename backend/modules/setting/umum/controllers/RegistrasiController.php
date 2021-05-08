<?php

namespace backend\modules\setting\umum\controllers;

use Yii;

use yii\base\DynamicModel;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\httpclient\Client;

class RegistrasiController extends \yii\web\Controller
{
    public function actionIndex(){
		
		$cekRegistrasi = 'SELECT `Value` FROM settingparameters WHERE Name = "KodeRegistrasi"';
        $cekRegistrasi = Yii::$app->db->createCommand($cekRegistrasi)->queryOne();
		
		// $getIP = getHostByName(getHostName());
        return $this->render('index',['data' => $cekRegistrasi]);
    }

    public function actionProses(){
        $data_post = $_POST;
        $data_post['ip'] = $this->getIP();
        if($data_post){
            $cekSettingParam = Yii::$app->db->createCommand('SELECT `Name` FROM settingparameters WHERE `Name` = "KodeRegistrasi"')->queryOne();
            if($cekSettingParam){
                $out = \common\components\Registrasi::registrasi($data_post,'http://registrasi.inlislite.perpusnas.go.id/api/web/v1/registrasi?');

                if ($out){
                    echo json_encode(Array('status' => 'TRUE'));
                    $data = Yii::$app->db->createCommand('UPDATE settingparameters SET `Value` = "'.$_POST['kodeRegis'].'" WHERE Name = "KodeRegistrasi"')->execute();
                } else {
                    echo json_encode(Array('status' => 'FALSE'));
                }
            }else{

                $out = \common\components\Registrasi::registrasi($data_post,'http://registrasi.inlislite.perpusnas.go.id/api/web/v1/registrasi?');
                
				if ($out){
                    echo json_encode(Array('status' => 'TRUE'));
                    $data = Yii::$app->db->createCommand('INSERT settingparameters SET `Name` = "KodeRegistrasi", `Value` = "'.$_POST['kodeRegis'].'"')->execute();
                } else {
                    echo json_encode(Array('status' => 'FALSE'));
                }
            }
            
        }else{
            echo json_encode(Array('status' => 'FALSE'));
        }
    }

    public function actionGetip(){
        $client = new Client(['baseUrl' => 'http://ip-api.com/']);
        $response = $client->get('json')->send();
        // echo'<pre>';print_r($response);
    }

    public function actionDetail(){
        $api = \common\components\Registrasi::detail($_GET, 'http://registrasi.inlislite.perpusnas.go.id/api/web/v1/registrasi/view');
    }

    public function actionGetProv(){
        $prov = Yii::$app->db->createCommand('SELECT ID, NamaPropinsi FROM propinsi WHERE ID = "'.$_GET['prov'].'"')->queryOne();
        echo json_encode($prov);
    }

    public function getIP(){
        $ip = getenv('HTTP_CLIENT_IP')?:
                getenv('HTTP_X_FORWARDED_FOR')?:
                getenv('HTTP_X_FORWARDED')?:
                getenv('HTTP_FORWARDED_FOR')?:
                getenv('HTTP_FORWARDED')?:
                getHostByName(getHostName())?:
                getenv('REMOTE_ADDR');
        return $ip;
    }

}