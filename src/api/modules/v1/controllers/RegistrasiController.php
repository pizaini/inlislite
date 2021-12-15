<?php

namespace api\modules\v1\controllers;

//use yii\rest\Controller;
use common\models\Registrasi;
/**
 * Login Controller API
 *
 * @author Rico <rico.ulul@gmail.com>
 */
class RegistrasiController extends \yii\rest\Controller
{
	
	public function actionIndex(){
		$get = \Yii::$app->request->get();
    	// echo'<pre>';print_r($get);die;
		$activationCode = !empty($_REQUEST['activationCode'])?$_REQUEST['activationCode'] : "";
		$response = [];
		if(empty($activationCode)){
			$response = [
			'status' => 'error',
			'message' => 'harap isi semua data!',
			'data' => '',
			];
		}
		else{
			$user = \common\models\Registrasi::findByActivationCode($activationCode);
			if(empty($user)){
			    $user = new \common\models\Registrasi();
                $user->ActivationCode=$activationCode;
                $user->NamaPerpustakaan = $get['namaPerpustakaan'];
                $user->JenisPerpustakaan = $get['jenisPerpustakaan'];
                $user->Negara = $get['negara'];
                $user->Provinsi = $get['provinsi'];
                $user->CreateDate = date('Y-m-d H:i:s');
                $user->CreateTerminal = \Yii::$app->request->userIP;


                $user->save();


					$response = [
						'status' => 'success',
						'message' => 'Registrasi berhasil!',
						'data' => $user->getErrors(),
					];
			}
			else{
				$response = [
				'status' => 'error',
				'message' => 'Anda Sudah Terdaftar',
				'data' => '',
				];
			}
		}

		return $response;
    	
	}

	public function actionView()
	{
		
		$response = [];
		if($_GET['noReg']){
			$data = \Yii::$app->db->createCommand('SELECT registrasi.*, propinsi.NamaPropinsi, propinsi.ID AS provID FROM registrasi INNER JOIN propinsi ON propinsi.ID = registrasi.Provinsi WHERE ActivationCode = "'.$_GET['noReg'].'"')->queryOne();
			if($data){
				echo json_encode($data);
			}else{
				echo json_encode('Tidak ada Nomor Registrasi');
			}	
			
		}else{
			$response = [
				'status' => 'error',
				'message' => 'Tidak ada Nomor Registrasi',
				'data' => '',
			];
		}
	}
}


