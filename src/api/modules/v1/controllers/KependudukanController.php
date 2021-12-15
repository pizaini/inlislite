<?php

namespace api\modules\v1\controllers;

//use yii\rest\Controller;
// use common\models\Registrasi;
/**
 * Login Controller API
 *
 * @author 
 */
class KependudukanController extends \yii\rest\Controller
{
	
	public function actionIndex(){
    	// $post = \Yii::$app->request->post();
		switch ($_GET['nik']) {
			case '327102927384859':
				$data = 
					array('data' => 
						array('DataPenduduk' => 
							array(
								'ID' => 1,
								'NIK' => 327102927384859,
								'NOMOR_KK' => 327102927384859,
								'NAMA_LENGKAP' => 'Rico Ulul Ilmy',
								'TEMPAT_LAHIR' => 'Kudus',
								'TANGGAL_LAHIR' => '06-10-1993',
								'AGAMA' => 'Islam',
								'STAT_HB_KEL' => '1',
								'STAT_HB_KEL_1' => '1',
								'PENDIDIKAN_AKHIR' => 'D3',
								'JENIS_PEKERJAAN' => 'KARYAWAN SWASTA',
								'JENIS_KELAMIN' => 'Laki laki',
								'NAMA_LGKP_IBU' => 'Ibu Saya',
								'NO_KEC' => '010',
								'NAMA_KEC' => 'Wonogiri',
								'NAMA_KEL' => 'Wonoboyo',
								'ALAMAT' => 'Wonogirio',
								'NO_RT' => '1',
								'NO_RW' => '3'
							)
						)
					);
				break;
			case '327102927364534':
				$data = 
					array('data' => 
						array('DataPenduduk' => 
							array(
								'ID' => 2,
								'NIK' => 327102927364534,
								'NOMOR_KK' => 327102927364534,
								'NAMA_LENGKAP' => 'Arief Yudia Ramadhani',
								'TEMPAT_LAHIR' => 'Bekasi',
								'TANGGAL_LAHIR' => '19-03-1993',
								'AGAMA' => 'Islam',
								'STAT_HB_KEL' => '1',
								'STAT_HB_KEL_1' => '1',
								'PENDIDIKAN_AKHIR' => 'S1',
								'JENIS_PEKERJAAN' => 'KARYAWAN SWASTA',
								'JENIS_KELAMIN' => 'Laki laki',
								'NAMA_LGKP_IBU' => 'Ibu Saya',
								'NO_KEC' => '010',
								'NAMA_KEC' => 'Medan Satria',
								'NAMA_KEL' => 'Pejuang',
								'ALAMAT' => 'Bekasi',
								'NO_RT' => '1',
								'NO_RW' => '3'
							)
						)
					);
				break;
			case '3271029278493274':
				$data = 
					array('data' => 
						array('DataPenduduk' => 
							array(
								'ID' => 3,
								'NIK' => 3271029278493274,
								'NOMOR_KK' => 3271029278493274,
								'NAMA_LENGKAP' => 'Hardi Riyansah',
								'TEMPAT_LAHIR' => 'Jakarta',
								'TANGGAL_LAHIR' => '02-06-1992',
								'AGAMA' => 'Islam',
								'STAT_HB_KEL' => '1',
								'STAT_HB_KEL_1' => '1',
								'PENDIDIKAN_AKHIR' => 'D3',
								'JENIS_PEKERJAAN' => 'KARYAWAN SWASTA',
								'JENIS_KELAMIN' => 'Laki laki',
								'NAMA_LGKP_IBU' => 'Ibu Saya',
								'NO_KEC' => '010',
								'NAMA_KEC' => 'Jatinegara',
								'NAMA_KEL' => 'Kampung Melayu',
								'ALAMAT' => 'Jakarta',
								'NO_RT' => '1',
								'NO_RW' => '3'
							)
						)
					);
				break;
			default:
				$data = 'data tidak ditemukan';
				break;
		}
		return $data;
		// return 
		// 		array('data' => 
		// 			array('DataPenduduk' => 
		// 				array(
		// 					'ID' => 1,
		// 					'NIK' => 3302938479,
		// 					'NOMOR_KK' => 3302938479,
		// 					'NAMA_LENGKAP' => 'Rico Ulul Ilmy',
		// 					'TEMPAT_LAHIR' => 'Kudus',
		// 					'TANGGAL_LAHIR' => '06-10-1993',
		// 					'AGAMA' => 'Islam',
		// 					'STAT_HB_KEL' => '1',
		// 					'STAT_HB_KEL_1' => '1',
		// 					'PENDIDIKAN_AKHIR' => 'D3',
		// 					'JENIS_PEKERJAAN' => 'KARYAWAN SWASTA',
		// 					'JENIS_KELAMIN' => 'Laki laki',
		// 					'NAMA_LGKP_IBU' => 'Ibu Saya',
		// 					'NO_KEC' => '010',
		// 					'NAMA_KEC' => 'Wonogiri',
		// 					'NAMA_KEL' => 'Wonoboyo',
		// 					'ALAMAT' => 'Wonogirio',
		// 					'NO_RT' => '1',
		// 					'NO_RW' => '3'
		// 				)
		// 			)
		// 		);
	}   
}


