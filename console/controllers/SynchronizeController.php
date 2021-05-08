<?php
namespace console\controllers;

use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\FileHelper;
use yii\db\Query;
use Yii;

use common\models\Members;
use common\components\ElasticHelper;

set_time_limit(0);
ini_set("memory_limit", "-1");

class SynchronizeController extends Controller{

	public $menu = [];
    public $tables = [];
    public $fp;
    public $file_name;
    public $_path = null;
    public $back_temp_file = 'db_inlislitev3_backup_';

	public static function actionGetDb() {
		// print_r(Yii::$app->config->get('OpacIndexer'));die;
		if (Yii::$app->config->get('OpacIndexer')==1){
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
        // die;
        return Yii::$app->get('db2'); // new database
    }

	public function actionUpServer($time = ''){
		$server = Yii::$app->db2;
		$transaction = $server->beginTransaction();

		$server->open();
		try {
			if ($server->getIsActive()) {
		       $proses = $this->actionProsesSinkronisasiServer();
		    }else{
		        $errorMsg = 'Incorrect Configurations';
		        print_r($errorMsg);die;
		    }

		    $transaction->commit();
		} catch(\Exception $e) {
		    $transaction->rollBack();
		    throw $e;
		} catch(\Throwable $e) {
		    $transaction->rollBack();
		    throw $e;
		}
		die;
	}

	public function actionProsesSinkronisasiServer(){
		$err=[];
        $err2=[];

        try {
        	$path = Yii::getAlias('@uploaded_files') . '/temporary/log_sinkronisasi/';
			$file = "/logs_local_to_server".date('Y-m-d').".txt";
			
			// check time execute synchronize db
			if(date('Y-m-d H:i:s') <= date('Y-m-d').' '.$time){
				
				if(file_exists($path.$file)){
					$new_data = "\n".date('Y-m-d H:i:s').' Belum waktunya synchronize'."\n";
					$myfile = file_put_contents($path.$file, $new_data , FILE_APPEND | LOCK_EX);
					fwrite($myfile, $new_data);

				}else{
					$content = date('Y-m-d H:i:s').' Belum waktunya synchronize'."\n";
					$fp = fopen($path . "/logs_local_to_server".date('Y-m-d').".txt","w");
					fwrite($fp,$content);
					fclose($fp);
				}
				
			}else{

				$gap = 0;
				// check last id member di db online
				$check_remote_last_member = Yii::$app->db2->createCommand('SELECT ID FROM members ORDER BY ID DESC')->queryOne();

				$check_local_last_member = Yii::$app->db->createCommand('SELECT ID FROM members ORDER BY ID DESC')->queryOne();

				$gap = $check_remote_last_member['ID'] - $check_local_last_member['ID'];
				
				// set gap
				if($gap < 0){
					$gap = str_replace('-', '', $gap);
				}else{
					$gap = 0;
				}
				
				// update ID anggota dulu agar tidak bentrok di FK yang terhubung di db offline + 5
				$changeIDmember = Yii::$app->db->createCommand('
						SET @rowid:= '.$check_remote_last_member['ID'].' + '.$gap.';
						UPDATE members SET ID=@rowid:=@rowid+1
						WHERE DATE(`CreateDate`) = DATE(NOW()) OR DATE(`UpdateDate`) = DATE(NOW());
						
					')->execute();
				
				// get data member register per hari ini
				$member_local = Yii::$app->db->createCommand('SELECT * FROM members WHERE DATE(`CreateDate`) = DATE(NOW()) OR DATE(`UpdateDate`) = DATE(NOW())')->queryAll();
				
				foreach ($member_local as $key => $value) {
					$path_foto = Yii::getAlias('@uploaded_files') . '/foto_anggota/';
					$file_foto = $value['PhotoUrl'];

					
					// update filed_id tabel members di modelhistory
					$get_model_history_member = Yii::$app->db->createCommand('SELECT field_id FROM modelhistory WHERE new_value = "'.$value['MemberNo'].'" AND `table` = "members"')->queryOne();
					
					// update modelhistory member local
					Yii::$app->db->createCommand('UPDATE modelhistory SET field_id = "'.$value['ID'].'" WHERE `table` = "members" AND field_id = "'.$get_model_history_member['field_id'].'"')->execute();
					// // update modelhistory member server
					// Yii::$app->db2->createCommand('UPDATE modelhistory SET field_id = "'.$value['ID'].'" WHERE `table` = "members" AND field_id = "'.$get_model_history_member['field_id'].'"')->execute();


					// cek no anggota di server
					$member_remote_check = Yii::$app->db2->createCommand('SELECT MemberNo FROM members WHERE MemberNo = "'.$value['MemberNo'].'"')->queryOne();

					// jika sudah ada nomor anggota, akan tetapi ada perubahan data, maka akan update ke server
					if($member_remote_check['MemberNo']){
						// rename foto anggota
						if(file_exists($path_foto.$file_foto)){
							if($file_foto !== NULL){
								rename($path_foto.$file_foto, $path_foto.$value['ID'].'.jpg');

								Yii::$app->db->createCommand('UPDATE members SET PhotoUrl = CONCAT("'.$value['ID'].'",".jpg") WHERE ID = "'.$value['ID'].'"')->execute();
							}
						}
						

						$update_member = Yii::$app->db2->createCommand()->update('members', [
						    'ID' => $value['ID'],
						    'MemberNo' => $value['MemberNo'],
						    'Fullname' => $value['Fullname'],
						    'PlaceOfBirth' => $value['PlaceOfBirth'],
						    'DateOfBirth' => $value['DateOfBirth'],
						    'Address' => $value['Address'],
						    'AddressNow' => $value['AddressNow'],
						    'Phone' => $value['Phone'],
						    'InstitutionName' => $value['InstitutionName'],
						    'InstitutionAddress' => $value['InstitutionAddress'],
						    'InstitutionPhone' => $value['InstitutionPhone'],
						    'IdentityType_id' => $value['IdentityType_id'],
						    'IdentityNo' => $value['IdentityNo'],
						    'EducationLevel_id' => $value['EducationLevel_id'],
						    'Sex_id' => $value['Sex_id'],
						    'MaritalStatus_id' => $value['MaritalStatus_id'],
						    'Job_id' => $value['Job_id'],
						    'RegisterDate' => $value['RegisterDate'],
						    'EndDate' => $value['EndDate'],
						    'MotherMaidenName' => $value['MotherMaidenName'],
						    'Email' => $value['Email'],
						    'JenisPermohonan_id' => $value['JenisPermohonan_id'],
						    'JenisAnggota_id' => $value['JenisAnggota_id'],
						    'StatusAnggota_id' => $value['StatusAnggota_id'],
						    'LoanReturnLateCount' => $value['LoanReturnLateCount'],
						    'Branch_id' => $value['Branch_id'],
						    'PhotoUrl' => $value['ID'].'.jpg',
						    'CreateBy' => $value['CreateBy'],
						    'CreateDate' => $value['CreateDate'],
						    'CreateTerminal' => $value['CreateTerminal'],
						    'UpdateBy' => $value['UpdateBy'],
						    'UpdateDate' => $value['UpdateDate'],
						    'UpdateTerminal' => $value['UpdateTerminal'],
						    'NoHp' => $value['NoHp'],
						    'NamaDarurat' => $value['NamaDarurat'],
						    'TelpDarurat' => $value['TelpDarurat'],
						    'AlamatDarurat' => $value['AlamatDarurat'],
						    'StatusHubunganDarurat' => $value['StatusHubunganDarurat'],
						    'City' => $value['City'],
						    'Province' => $value['Province'],
						    'CityNow' => $value['CityNow'],
						    'ProvinceNow' => $value['ProvinceNow'],
						    'Kecamatan' => $value['Kecamatan'],
						    'Kelurahan' => $value['Kelurahan'],
						    'RT' => $value['RT'],
						    'RW' => $value['RW'],
						    'KecamatanNow' => $value['KecamatanNow'],
						    'KelurahanNow' => $value['KelurahanNow'],
						    'RTNow' => $value['RTNow'],
						    'Kelas_id' => $value['Kelas_id'],
						    'TahunAjaran' => $value['TahunAjaran'],
						    'Agama_id' => $value['Agama_id'],
						    'Fakultas_id' => $value['Fakultas_id'],
						    'Jurusan_id' => $value['Jurusan_id'],
						    'ProgramStudi_id' => $value['ProgramStudi_id'],
						    'JenjangPendidikan_id' => $value['JenjangPendidikan_id'],
						    'UnitKerja_id' => $value['UnitKerja_id'],
						    'KeteranganLain' => $value['KeteranganLain'],
						    'IsLunasBiayaPendaftaran' => $value['IsLunasBiayaPendaftaran'],
						    'BiayaPendaftaran' => $value['BiayaPendaftaran'],
						    'TanggalBebasPustaka' => $value['TanggalBebasPustaka'],
						    'KIILastUploadDate' => $value['KIILastUploadDate'],
						], 'MemberNo = "'.$value['MemberNo'].'"')->execute();

						if($update_member){
							Yii::$app->db2->createCommand('DELETE FROM memberloanauthorizecategory WHERE Member_id = "'.$value['ID'].'"')->execute();

							$memberloanauthcategory_local = Yii::$app->db->createCommand('SELECT * FROM memberloanauthorizecategory WHERE Member_id = "'.$value['ID'].'"')->queryAll();
							
							// save data memberloanauthorizecategory to db server
							foreach ($memberloanauthcategory_local as $keyauthcat => $valueauthcat) {
								// echo'<pre>';print_r($valueauthcat);
								$save_memberloanauthcategory = Yii::$app->db2->createCommand()->insert('memberloanauthorizecategory', [
								    'Member_id' => $valueauthcat['Member_id'],
								    'CategoryLoan_id' => $valueauthcat['CategoryLoan_id'],
								    'CreateBy' => $valueauthcat['CreateBy'],
								    'CreateDate' => $valueauthcat['CreateDate'],
								    'CreateTerminal' => $valueauthcat['CreateTerminal'],
								    'UpdateBy' => $valueauthcat['UpdateBy'],
								    'UpdateDate' => $valueauthcat['UpdateDate'],
								    'UpdateTerminal' => $valueauthcat['UpdateTerminal'],
								])->execute();
							}

							Yii::$app->db2->createCommand('DELETE FROM memberloanauthorizelocation WHERE Member_id = "'.$value['ID'].'"')->execute();

							$memberloanauthlocation_local = Yii::$app->db->createCommand('SELECT * FROM memberloanauthorizelocation WHERE Member_id = "'.$value['ID'].'"')->queryAll();
							
							// save data memberloanauthorizelocation to db server
							foreach ($memberloanauthlocation_local as $keyauthcat => $valueauthcat) {
								// echo'<pre>';print_r($valueauthcat);
								$save_memberloanauthcategory = Yii::$app->db2->createCommand()->insert('memberloanauthorizelocation', [
								    'Member_id' => $valueauthcat['Member_id'],
								    'LocationLoan_id' => $valueauthcat['LocationLoan_id'],
								    'CreateBy' => $valueauthcat['CreateBy'],
								    'CreateDate' => $valueauthcat['CreateDate'],
								    'CreateTerminal' => $valueauthcat['CreateTerminal'],
								    'UpdateBy' => $valueauthcat['UpdateBy'],
								    'UpdateDate' => $valueauthcat['UpdateDate'],
								    'UpdateTerminal' => $valueauthcat['UpdateTerminal'],
								])->execute();
							}


						}

						unset($update_member);
					}

					// jika nomor anggota tidak ada, akan insert baru ke server
					else
					{
						if(file_exists($path_foto.$file_foto)){
							// rename foto anggota
							if($file_foto !== NULL){
								rename($path_foto.$file_foto, $path_foto.$value['ID'].'.jpg');

								Yii::$app->db->createCommand('UPDATE members SET PhotoUrl = CONCAT("'.$value['ID'].'",".jpg") WHERE ID = "'.$value['ID'].'"')->execute();
							}
						}
						
						// save data member to db server
						$save_member = Yii::$app->db2->createCommand()->insert('members', [
						    'ID' => $value['ID'],
						    'MemberNo' => $value['MemberNo'],
						    'Fullname' => $value['Fullname'],
						    'PlaceOfBirth' => $value['PlaceOfBirth'],
						    'DateOfBirth' => $value['DateOfBirth'],
						    'Address' => $value['Address'],
						    'AddressNow' => $value['AddressNow'],
						    'Phone' => $value['Phone'],
						    'InstitutionName' => $value['InstitutionName'],
						    'InstitutionAddress' => $value['InstitutionAddress'],
						    'InstitutionPhone' => $value['InstitutionPhone'],
						    'IdentityType_id' => $value['IdentityType_id'],
						    'IdentityNo' => $value['IdentityNo'],
						    'EducationLevel_id' => $value['EducationLevel_id'],
						    'Sex_id' => $value['Sex_id'],
						    'MaritalStatus_id' => $value['MaritalStatus_id'],
						    'Job_id' => $value['Job_id'],
						    'RegisterDate' => $value['RegisterDate'],
						    'EndDate' => $value['EndDate'],
						    'MotherMaidenName' => $value['MotherMaidenName'],
						    'Email' => $value['Email'],
						    'JenisPermohonan_id' => $value['JenisPermohonan_id'],
						    'JenisAnggota_id' => $value['JenisAnggota_id'],
						    'StatusAnggota_id' => $value['StatusAnggota_id'],
						    'LoanReturnLateCount' => $value['LoanReturnLateCount'],
						    'Branch_id' => $value['Branch_id'],
						    'PhotoUrl' => $value['ID'].'.jpg',
						    'CreateBy' => $value['CreateBy'],
						    'CreateDate' => $value['CreateDate'],
						    'CreateTerminal' => $value['CreateTerminal'],
						    'UpdateBy' => $value['UpdateBy'],
						    'UpdateDate' => $value['UpdateDate'],
						    'UpdateTerminal' => $value['UpdateTerminal'],
						    'NoHp' => $value['NoHp'],
						    'NamaDarurat' => $value['NamaDarurat'],
						    'TelpDarurat' => $value['TelpDarurat'],
						    'AlamatDarurat' => $value['AlamatDarurat'],
						    'StatusHubunganDarurat' => $value['StatusHubunganDarurat'],
						    'City' => $value['City'],
						    'Province' => $value['Province'],
						    'CityNow' => $value['CityNow'],
						    'ProvinceNow' => $value['ProvinceNow'],
						    'Kecamatan' => $value['Kecamatan'],
						    'Kelurahan' => $value['Kelurahan'],
						    'RT' => $value['RT'],
						    'RW' => $value['RW'],
						    'KecamatanNow' => $value['KecamatanNow'],
						    'KelurahanNow' => $value['KelurahanNow'],
						    'RTNow' => $value['RTNow'],
						    'Kelas_id' => $value['Kelas_id'],
						    'TahunAjaran' => $value['TahunAjaran'],
						    'Agama_id' => $value['Agama_id'],
						    'Fakultas_id' => $value['Fakultas_id'],
						    'Jurusan_id' => $value['Jurusan_id'],
						    'ProgramStudi_id' => $value['ProgramStudi_id'],
						    'JenjangPendidikan_id' => $value['JenjangPendidikan_id'],
						    'UnitKerja_id' => $value['UnitKerja_id'],
						    'KeteranganLain' => $value['KeteranganLain'],
						    'IsLunasBiayaPendaftaran' => $value['IsLunasBiayaPendaftaran'],
						    'BiayaPendaftaran' => $value['BiayaPendaftaran'],
						    'TanggalBebasPustaka' => $value['TanggalBebasPustaka'],
						    'KIILastUploadDate' => $value['KIILastUploadDate'],
						])->execute();

						if($save_member){
							$memberloanauthcategory_local = Yii::$app->db->createCommand('SELECT * FROM memberloanauthorizecategory WHERE Member_id = "'.$value['ID'].'"')->queryAll();
							
							// save data memberloanauthorizecategory to db server
							foreach ($memberloanauthcategory_local as $keyauthcat => $valueauthcat) {
								// echo'<pre>';print_r($valueauthcat);
								$save_memberloanauthcategory = Yii::$app->db2->createCommand()->insert('memberloanauthorizecategory', [
								    'Member_id' => $valueauthcat['Member_id'],
								    'CategoryLoan_id' => $valueauthcat['CategoryLoan_id'],
								    'CreateBy' => $valueauthcat['CreateBy'],
								    'CreateDate' => $valueauthcat['CreateDate'],
								    'CreateTerminal' => $valueauthcat['CreateTerminal'],
								    'UpdateBy' => $valueauthcat['UpdateBy'],
								    'UpdateDate' => $valueauthcat['UpdateDate'],
								    'UpdateTerminal' => $valueauthcat['UpdateTerminal'],
								])->execute();
							}

							$memberloanauthlocation_local = Yii::$app->db->createCommand('SELECT * FROM memberloanauthorizelocation WHERE Member_id = "'.$value['ID'].'"')->queryAll();
							
							// save data memberloanauthorizelocation to db server
							foreach ($memberloanauthlocation_local as $keyauthcat => $valueauthcat) {
								// echo'<pre>';print_r($valueauthcat);
								$save_memberloanauthcategory = Yii::$app->db2->createCommand()->insert('memberloanauthorizelocation', [
								    'Member_id' => $valueauthcat['Member_id'],
								    'LocationLoan_id' => $valueauthcat['LocationLoan_id'],
								    'CreateBy' => $valueauthcat['CreateBy'],
								    'CreateDate' => $valueauthcat['CreateDate'],
								    'CreateTerminal' => $valueauthcat['CreateTerminal'],
								    'UpdateBy' => $valueauthcat['UpdateBy'],
								    'UpdateDate' => $valueauthcat['UpdateDate'],
								    'UpdateTerminal' => $valueauthcat['UpdateTerminal'],
								])->execute();
							}
						}

						unset($save_member);
					}
					// echo'<pre>';print_r($member_remote_check);die;

					$log .= date('Y-m-d H:i:s').' Sinkronisasi Anggota'.$value['MemberNo'].PHP_EOL;
					
				}

				// get data member perpanjangan per hari ini
				$member_perpanjangan_local = Yii::$app->db->createCommand('SELECT * FROM member_perpanjangan WHERE DATE(`CreateDate`) = DATE(NOW()) OR DATE(`UpdateDate`) = DATE(NOW())')->queryAll();
				foreach ($member_perpanjangan_local as $keymember_perpanjang => $valuemember_perpanjang) {

					// check data member_perpanjangan di server
					$check_remote_id_perpanjangan = Yii::$app->db2->createCommand('SELECT ID FROM member_perpanjangan WHERE Member_id = "'.$valuemember_perpanjang['Member_id'].'" AND DATE(`CreateDate`) = DATE("'.$valuemember_perpanjang['CreateDate'].'")')->queryOne();

					if($check_remote_id_perpanjangan['ID']){
						// update data member perpanjangan to db server
						$member_perpanjangan = Yii::$app->db2->createCommand()->update('member_perpanjangan', [
								    'Member_id' => $valuemember_perpanjang['Member_id'],
								    'Tanggal' => $valuemember_perpanjang['Tanggal'],
								    'Biaya' => $valuemember_perpanjang['Biaya'],
								    'IsLunas' => $valuemember_perpanjang['IsLunas'],
								    'Keterangan' => $valuemember_perpanjang['Keterangan'],
								    'CreateBy' => $valuemember_perpanjang['CreateBy'],
								    'CreateDate' => $valuemember_perpanjang['CreateDate'],
								    'CreateTerminal' => $valuemember_perpanjang['CreateTerminal'],
								    'UpdateBy' => $valuemember_perpanjang['UpdateBy'],
								    'UpdateDate' => $valuemember_perpanjang['UpdateDate'],
								    'UpdateTerminal' => $valuemember_perpanjang['UpdateTerminal'],
								], 'ID = "'.$valuemember_perpanjang['ID'].'"')->execute();
					}else{
						// save data member perpanjangan to db server
						$member_perpanjangan = Yii::$app->db2->createCommand()->insert('member_perpanjangan', [
								    'Member_id' => $valuemember_perpanjang['Member_id'],
								    'Tanggal' => $valuemember_perpanjang['Tanggal'],
								    'Biaya' => $valuemember_perpanjang['Biaya'],
								    'IsLunas' => $valuemember_perpanjang['IsLunas'],
								    'Keterangan' => $valuemember_perpanjang['Keterangan'],
								    'CreateBy' => $valuemember_perpanjang['CreateBy'],
								    'CreateDate' => $valuemember_perpanjang['CreateDate'],
								    'CreateTerminal' => $valuemember_perpanjang['CreateTerminal'],
								    'UpdateBy' => $valuemember_perpanjang['UpdateBy'],
								    'UpdateDate' => $valuemember_perpanjang['UpdateDate'],
								    'UpdateTerminal' => $valuemember_perpanjang['UpdateTerminal'],
								])->execute();

						$lastID = Yii::$app->db2->getLastInsertID();

						// update filed_id tabel member_perpanjangan di modelhistory
						$get_model_history_member = Yii::$app->db2->createCommand('SELECT field_id FROM modelhistory WHERE field_id = "'.$valuemember_perpanjang['ID'].'" AND `table` = "member_perpanjangan"')->queryOne();
						
						// update modelhistory member_perpanjangan local
						Yii::$app->db->createCommand('UPDATE modelhistory SET field_id = "'.$lastID.'" WHERE `table` = "member_perpanjangan" AND field_id = "'.$valuemember_perpanjang['ID'].'"')->execute();
						// update modelhistory member_perpanjangan server
						Yii::$app->db2->createCommand('UPDATE modelhistory SET field_id = "'.$lastID.'" WHERE `table` = "member_perpanjangan" AND field_id = "'.$valuemember_perpanjang['ID'].'"')->execute();
					}


					$log .= date('Y-m-d H:i:s').' Sinkronisasi Perpanjangan Anggota'.$valuemember_perpanjang['Member_id'].PHP_EOL;
					unset($member_perpanjangan);
				}

				// get data bacaditempat per hari ini
				$bacaditempat_local = Yii::$app->db->createCommand('SELECT * FROM bacaditempat WHERE DATE(`CreateDate`) = DATE(NOW())')->queryAll();
				foreach ($bacaditempat_local as $keybaca => $valuebaca) {
					// save data bacaditempat to db server
					$bacaditempat = Yii::$app->db2->createCommand()->insert('bacaditempat', [
							    // 'ID' => $valuebaca['ID'],
							    'NoPengunjung' => $valuebaca['NoPengunjung'],
							    'collection_id' => $valuebaca['collection_id'],
							    'CreateBy' => $valuebaca['CreateBy'],
							    'CreateDate' => $valuebaca['CreateDate'],
							    'CreateTerminal' => $valuebaca['CreateTerminal'],
							    'UpdateBy' => $valuebaca['UpdateBy'],
							    'UpdateDate' => $valuebaca['UpdateDate'],
							    'UpdateTerminal' => $valuebaca['UpdateTerminal'],
							    'Member_id' => $valuebaca['Member_id'],
							    'Location_Id' => $valuebaca['Location_Id'],
							    'Is_return' => $valuebaca['Is_return'],
							])->execute();

					$log .= date('Y-m-d H:i:s').' Sinkronisasi Baca Ditempat'.$valuebaca['NoPengunjung'].' - Koleksi ID - '.$valuebaca['collection_id'].PHP_EOL;
					unset($bacaditempat);
				}

				// get data bukutamu member dan non member per hari ini
				$bukutamumember_local = Yii::$app->db->createCommand('SELECT * FROM memberguesses  WHERE DATE(`CreateDate`) = DATE(NOW())')->queryAll();
				if(!empty($bukutamumember_local)){
					$datamember_bacaditempat = array();
					foreach($bukutamumember_local as $keybacaditempat_member=>$valuebacaditempat_member){
					    $datamember_bacaditempat[] = [
					    	$valuebacaditempat_member['NoAnggota'],
					    	$valuebacaditempat_member['Nama'], 
					    	$valuebacaditempat_member['Status_id'], 
					    	$valuebacaditempat_member['MasaBerlaku_id'], 
					    	$valuebacaditempat_member['Profesi_id'], 
					    	$valuebacaditempat_member['PendidikanTerakhir_id'], 
					    	$valuebacaditempat_member['JenisKelamin_id'], 
					    	$valuebacaditempat_member['Alamat'],
					    	$valuebacaditempat_member['CreateBy'],
					    	$valuebacaditempat_member['CreateDate'],
					    	$valuebacaditempat_member['CreateTerminal'],
					    	$valuebacaditempat_member['UpdateBy'],
					    	$valuebacaditempat_member['UpdateDate'],
					    	$valuebacaditempat_member['UpdateTerminal'],
					    	$valuebacaditempat_member['Deskripsi'],
					    	$valuebacaditempat_member['LOCATIONLOANS_ID'],
					    	$valuebacaditempat_member['Location_Id'],
					    	$valuebacaditempat_member['TujuanKunjungan_Id'],
					    	$valuebacaditempat_member['Information'],
					    	$valuebacaditempat_member['NoPengunjung'],
					    ];
					}
					$simpan = Yii::$app->db2->createCommand()
					->batchInsert('memberguesses', ['NoAnggota','Nama', 'Status_id','MasaBerlaku_id','Profesi_id','PendidikanTerakhir_id','JenisKelamin_id','Alamat','CreateBy','CreateDate','CreateTerminal','UpdateBy','UpdateDate','UpdateTerminal','Deskripsi','LOCATIONLOANS_ID','Location_Id','TujuanKunjungan_Id','Information','NoPengunjung'],$datamember_bacaditempat)
					->execute();

					$log .= date('Y-m-d H:i:s').' Sinkronisasi Buku Tamu Anggota'.PHP_EOL;
					// unset($simpan);
				}
				

				// get data bukutamu group per hari ini
				$bukutamugroup_local = Yii::$app->db->createCommand('SELECT * FROM groupguesses  WHERE DATE(`CreateDate`) = DATE(NOW())')->queryAll();
				// echo'<pre>';print(count($bukutamugroup_local));die;
				if(!empty($bukutamugroup_local)){
					$datagroup_bacaditempat = array();
					foreach($bukutamugroup_local as $keybacaditempat_group=>$valuebacaditempat_group){
					    $datagroup_bacaditempat[] = [
					    	$valuebacaditempat_group['NamaKetua'],
					    	$valuebacaditempat_group['NomerTelponKetua'], 
					    	$valuebacaditempat_group['AsalInstansi'], 
					    	$valuebacaditempat_group['AlamatInstansi'], 
					    	$valuebacaditempat_group['CountPersonel'], 
					    	$valuebacaditempat_group['CountPNS'], 
					    	$valuebacaditempat_group['CountPSwasta'], 
					    	$valuebacaditempat_group['CountPeneliti'],
					    	$valuebacaditempat_group['CountGuru'],
					    	$valuebacaditempat_group['CountDosen'],
					    	$valuebacaditempat_group['CountPensiunan'],
					    	$valuebacaditempat_group['CountTNI'],
					    	$valuebacaditempat_group['CountWiraswasta'],
					    	$valuebacaditempat_group['CountPelajar'],
					    	$valuebacaditempat_group['CountMahasiswa'],
					    	$valuebacaditempat_group['CountLainnya'],
					    	$valuebacaditempat_group['CountSD'],
					    	$valuebacaditempat_group['CountSMP'],
					    	$valuebacaditempat_group['CountSMA'],
					    	$valuebacaditempat_group['CountD1'],
					    	$valuebacaditempat_group['CountD2'],
					    	$valuebacaditempat_group['CountD3'],
					    	$valuebacaditempat_group['CountS1'],
					    	$valuebacaditempat_group['CountS2'],
					    	$valuebacaditempat_group['CountS3'],
					    	$valuebacaditempat_group['CountLaki'],
					    	$valuebacaditempat_group['CountPerempuan'],
					    	$valuebacaditempat_group['TujuanKunjungan_ID'],
					    	$valuebacaditempat_group['CreateBy'],
					    	$valuebacaditempat_group['CreateDate'],
					    	$valuebacaditempat_group['CreateTerminal'],
					    	$valuebacaditempat_group['UpdateBy'],
					    	$valuebacaditempat_group['UpdateDate'],
					    	$valuebacaditempat_group['UpdateTerminal'],
					    	$valuebacaditempat_group['LocationLoans_ID'],
					    	$valuebacaditempat_group['Location_ID'],
					    	$valuebacaditempat_group['TeleponInstansi'],
					    	$valuebacaditempat_group['EmailInstansi'],
					    	$valuebacaditempat_group['Information'],
					    	$valuebacaditempat_group['NoPengunjung'],
					    ];
					}
					Yii::$app->db2->createCommand()
					->batchInsert('groupguesses', ['NamaKetua','NomerTelponKetua', 'AsalInstansi','AlamatInstansi',
						'CountPersonel','CountPNS','CountPSwasta','CountPeneliti','CountGuru','CountDosen',
						'CountPensiunan','CountTNI','CountWiraswasta','CountPelajar','CountMahasiswa','CountLainnya',
						'CountSD','CountSMP','CountSMA','CountD1',
						'CountD2','CountD3','CountS1','CountS2','CountS3','CountLaki','CountPerempuan',
						'TujuanKunjungan_ID','CreateBy','CreateDate','CreateTerminal','UpdateBy','UpdateDate',
						'UpdateTerminal','LocationLoans_ID','Location_ID','TeleponInstansi','EmailInstansi',
						'Information','NoPengunjung'],$datagroup_bacaditempat)
					->execute();

					$log .= date('Y-m-d H:i:s').' Sinkronisasi Buku Tamu Group'.PHP_EOL;
				}
				

				

				/************************ COLLECTION ****************************/
				$gap_collectionloanitem = 0;
				// check last id collectionloanitem di db online
				$check_remote_last_loanitem = Yii::$app->db2->createCommand('SELECT ID FROM collectionloanitems ORDER BY ID DESC')->queryOne();

				$check_local_last_loanitem = Yii::$app->db->createCommand('SELECT ID FROM collectionloanitems ORDER BY ID DESC')->queryOne();

				$gap_collectionloanitem = $check_remote_last_loanitem['ID'] - $check_local_last_loanitem['ID'];
				
				// set gap
				if($gap_collectionloanitem < 0){
					$gap_collectionloanitem = str_replace('-', '', $gap_collectionloanitem);
				}else{
					$gap_collectionloanitem = 0;
				}

				

				// get data loan sirkulasi per hari ini
				$loan_local = Yii::$app->db->createCommand('SELECT * FROM collectionloans WHERE DATE(`CreateDate`) = DATE(NOW()) OR DATE(`UpdateDate`) = DATE(NOW())')->queryAll();
				// echo'<pre>';print_r($loan_local);die;

				foreach ($loan_local as $key_loan => $value_loan) {
					// cek no transaksi loan items di server
					$loan_remote_check = Yii::$app->db2->createCommand('SELECT ID FROM collectionloans WHERE ID = "'.$value_loan['ID'].'"')->queryOne();

					if($loan_remote_check['ID']){

					}else{
						$save_loan = Yii::$app->db2->createCommand()->insert('collectionloans',[
							'ID' => $value_loan['ID'],
							'CollectionCount' => $value_loan['CollectionCount'],
							'LateCount' => $value_loan['LateCount'],
							'ExtendCount' => $value_loan['ExtendCount'],
							'LoanCount' => $value_loan['LoanCount'],
							'ReturnCount' => $value_loan['ReturnCount'],
							'Member_id' => $value_loan['Member_id'],
							'Branch_id' => $value_loan['Branch_id'],
							'CreateBy' => $value_loan['CreateBy'],
							'CreateDate' => $value_loan['CreateDate'],
							'CreateTerminal' => $value_loan['CreateTerminal'],
							'UpdateBy' => $value_loan['UpdateBy'],
							'UpdateDate' => $value_loan['UpdateDate'],
							'UpdateTerminal' => $value_loan['UpdateTerminal'],
							'KIILastUploadDate' => $value_loan['KIILastUploadDate'],
							'LocationLibrary_id' => $value_loan['LocationLibrary_id']
						])->execute();

						
					}

					$log .= date('Y-m-d H:i:s').' Sinkronisasi Collectionloans ID: '.$value_loan['ID'].PHP_EOL;
				}

				// update ID collectionloanitem dulu agar tidak bentrok di FK yang terhubung di db offline + 5
				$changeIDloanitem = Yii::$app->db->createCommand('
						SET @rowid:= '.$check_remote_last_loanitem['ID'].' + '.$gap_collectionloanitem.';
						UPDATE collectionloanitems SET ID=@rowid:=@rowid+1
						WHERE DATE(`CreateDate`) = DATE(NOW());
						
					')->execute();

				// get data loan items local
				$loan_item_local = Yii::$app->db->createCommand('SELECT * FROM collectionloanitems WHERE DATE(`CreateDate`) = DATE(NOW()) OR DATE(`UpdateDate`) = DATE(NOW())')->queryAll();
				// echo'<pre>';print_r($loan_item_local);die;
				foreach ($loan_item_local as $key_loan_item => $value_loan_item) {
					$check_remote_loan_item = Yii::$app->db2->createCommand('SELECT ID, CollectionLoan_id FROM collectionloanitems WHERE CollectionLoan_id = "'.$value_loan_item['CollectionLoan_id'].'" AND Collection_id = "'.$value_loan_item['Collection_id'].'"')->queryOne();
					
					if($check_remote_loan_item['CollectionLoan_id']){
						$update_loan_item = Yii::$app->db2->createCommand()->update('collectionloanitems',[
							'CollectionLoan_id' => $value_loan_item['CollectionLoan_id'],
							'LoanDate' => $value_loan_item['LoanDate'],
							'DueDate' => $value_loan_item['DueDate'],
							'ActualReturn' => $value_loan_item['ActualReturn'],
							'LateDays' => $value_loan_item['LateDays'],
							'LoanStatus' => $value_loan_item['LoanStatus'],
							'Collection_id' => $value_loan_item['Collection_id'],
							'member_id' => $value_loan_item['member_id'],
							'CreateBy' => $value_loan_item['CreateBy'],
							'CreateDate' => $value_loan_item['CreateDate'],
							'CreateTerminal' => $value_loan_item['CreateTerminal'],
							'UpdateBy' => $value_loan_item['UpdateBy'],
							'UpdateDate' => $value_loan_item['UpdateDate'],
							'UpdateTerminal' => $value_loan_item['UpdateTerminal'],
							'KIILastUploadDate' => $value_loan_item['KIILastUploadDate'],
						],'CollectionLoan_id = "'.$value_loan_item['CollectionLoan_id'].'" AND Collection_id = "'.$value_loan_item['Collection_id'].'" ')->execute();

						// update status collections
						$status = ($value_loan_item['LoanStatus'] == 'Loan') ? '5' : '1';
						Yii::$app->db2->createCommand()->update('collections', ['Status_id' => $status], 'ID = "'.$value_loan_item['Collection_id'].'"')->execute();
					

						// get data pelanggaran local
						$get_pelanggaran_local = Yii::$app->db->createCommand('SELECT * FROM pelanggaran WHERE CollectionLoan_id = "'.$value_loan_item['CollectionLoan_id'].'" AND Collection_id = "'.$value_loan_item['Collection_id'].'" AND Date(CreateDate) = DATE(NOW())')->queryAll();

						foreach ($get_pelanggaran_local as $keypelanggaran => $valuepelanggaran) {
							$check_pelanggaran_remote = Yii::$app->db2->createCommand('SELECT ID FROM pelanggaran WHERE CollectionLoan_id = "'.$valuepelanggaran['CollectionLoan_id'].'" AND Collection_id = "'.$valuepelanggaran['Collection_id'].'" AND Date(CreateDate) = DATE(NOW())')->queryOne();

							if($check_pelanggaran_remote['ID']){

							}else{
								$save_pelanggaran = Yii::$app->db2->createCommand()->insert('pelanggaran', [
									'CollectionLoan_id' => $valuepelanggaran['CollectionLoan_id'],
									'CollectionLoanItem_id' => $valuepelanggaran['CollectionLoanItem_id'],
									'JenisPelanggaran_id' => $valuepelanggaran['JenisPelanggaran_id'],
									'JenisDenda_id' => $valuepelanggaran['JenisDenda_id'],
									'JumlahDenda' => $valuepelanggaran['JumlahDenda'],
									'CreateBy' => $valuepelanggaran['CreateBy'],
									'CreateDate' => $valuepelanggaran['CreateDate'],
									'CreateTerminal' => $valuepelanggaran['CreateTerminal'],
									'UpdateBy' => $valuepelanggaran['UpdateBy'],
									'UpdateDate' => $valuepelanggaran['UpdateDate'],
									'UpdateTerminal' => $valuepelanggaran['UpdateTerminal'],
									'JumlahSuspend' => $valuepelanggaran['JumlahSuspend'],
									'Paid' => $valuepelanggaran['Paid'],
									'Member_id' => $valuepelanggaran['Member_id'],
									'Collection_id' => $valuepelanggaran['Collection_id'],
								])->execute();

								$lastID = Yii::$app->db2->getLastInsertID();
						
								// update filed_id tabel pelanggaran di modelhistory
								$get_model_history_pelanggaran = Yii::$app->db2->createCommand('SELECT field_id FROM modelhistory WHERE field_id = "'.$valuepelanggaran['ID'].'" AND `table` = "pelanggaran"')->queryOne();
								
								// update modelhistory member_perpanjangan local
								Yii::$app->db->createCommand('UPDATE modelhistory SET field_id = "'.$lastID.'" WHERE `table` = "pelanggaran" AND field_id = "'.$valuepelanggaran['ID'].'"')->execute();
								// update modelhistory member_perpanjangan server
								Yii::$app->db2->createCommand('UPDATE modelhistory SET field_id = "'.$lastID.'" WHERE `table` = "pelanggaran" AND field_id = "'.$valuepelanggaran['ID'].'"')->execute();
							}
						}

					}else{

						// update filed_id tabel collectionloanitems di modelhistory
						$get_model_history_loanitems = Yii::$app->db->createCommand('SELECT field_id FROM modelhistory WHERE (field_name = "Collection_id" AND new_value IN ("'.$value_loan_item['CollectionLoan_id'].'", "'.$value_loan_item['Collection_id'].'")) AND `table` = "collectionloanitems"')->queryOne();
						
						// update modelhistory collectionloanitems local
						Yii::$app->db->createCommand('UPDATE modelhistory SET field_id = "'.$value_loan_item['ID'].'" WHERE `table` = "collectionloanitems" AND field_id = "'.$get_model_history_loanitems['field_id'].'"')->execute();
						// update modelhistory collectionloanitems server
						Yii::$app->db2->createCommand('UPDATE modelhistory SET field_id = "'.$value_loan_item['ID'].'" WHERE `table` = "collectionloanitems" AND field_id = "'.$get_model_history_loanitems['field_id'].'"')->execute();

						$save_loan_item = Yii::$app->db2->createCommand()->insert('collectionloanitems',[
							'ID' => $value_loan_item['ID'],
							'CollectionLoan_id' => $value_loan_item['CollectionLoan_id'],
							'LoanDate' => $value_loan_item['LoanDate'],
							'DueDate' => $value_loan_item['DueDate'],
							'ActualReturn' => $value_loan_item['ActualReturn'],
							'LateDays' => $value_loan_item['LateDays'],
							'LoanStatus' => $value_loan_item['LoanStatus'],
							'Collection_id' => $value_loan_item['Collection_id'],
							'member_id' => $value_loan_item['member_id'],
							'CreateBy' => $value_loan_item['CreateBy'],
							'CreateDate' => $value_loan_item['CreateDate'],
							'CreateTerminal' => $value_loan_item['CreateTerminal'],
							'UpdateBy' => $value_loan_item['UpdateBy'],
							'UpdateDate' => $value_loan_item['UpdateDate'],
							'UpdateTerminal' => $value_loan_item['UpdateTerminal'],
							'KIILastUploadDate' => $value_loan_item['KIILastUploadDate'],
						])->execute();

						// update status collections
						$status = ($value_loan_item['LoanStatus'] == 'Loan') ? 5 : 1;
						Yii::$app->db2->createCommand()->update('collections', ['Status_id' => $status], 'ID = "'.$value_loan_item['Collection_id'].'"')->execute();
						
					}

					$log .= date('Y-m-d H:i:s').' Sinkronisasi Collectionloansitem ID: '.$value_loan_item['CollectionLoan_id'].' Koleksi ID :'.$value_loan_item['Collection_id'].' Status Peminjaman : '.$value_loan_item['LoanStatus'].PHP_EOL;
				}

				// get data loan extends local
				$loan_extend_local = Yii::$app->db->createCommand('SELECT * FROM collectionloanextends WHERE DATE(`CreateDate`) = DATE(NOW())')->queryAll();

				foreach ($loan_extend_local as $key_loan_extend => $value_loan_extend) {
					// $check_remote_loan_extend = Yii::$app->db2->createCommand('SELECT CollectionLoan_id FROM collectionloanitems WHERE CollectionLoan_id = "'.$value_loan_item['CollectionLoan_id'].'" AND Collection_id = "'.$value_loan_item['Collection_id'].'"')->queryOne();
					// // echo'<pre>';print_r($check_remote_loan_item);die;
					// if($check_remote_loan_item['CollectionLoan_id']){
					// 	$update_loan_item = Yii::$app->db2->createCommand()->update('collectionloanitems',[
					// 		'CollectionLoan_id' => $value_loan_item['CollectionLoan_id'],
					// 		'LoanDate' => $value_loan_item['LoanDate'],
					// 		'DueDate' => $value_loan_item['DueDate'],
					// 		'ActualReturn' => $value_loan_item['ActualReturn'],
					// 		'LateDays' => $value_loan_item['LateDays'],
					// 		'LoanStatus' => $value_loan_item['LoanStatus'],
					// 		'Collection_id' => $value_loan_item['Collection_id'],
					// 		'member_id' => $value_loan_item['member_id'],
					// 		'CreateBy' => $value_loan_item['CreateBy'],
					// 		'CreateDate' => $value_loan_item['CreateDate'],
					// 		'CreateTerminal' => $value_loan_item['CreateTerminal'],
					// 		'UpdateBy' => $value_loan_item['UpdateBy'],
					// 		'UpdateDate' => $value_loan_item['UpdateDate'],
					// 		'UpdateTerminal' => $value_loan_item['UpdateTerminal'],
					// 		'KIILastUploadDate' => $value_loan_item['KIILastUploadDate'],
					// 	],'CollectionLoan_id = "'.$value_loan_item['CollectionLoan_id'].'" AND Collection_id = "'.$value_loan_item['Collection_id'].'" ')->execute();
					// }else{
						$save_loan_extend = Yii::$app->db2->createCommand()->insert('collectionloanextends',[
							'CollectionLoan_id' => $value_loan_extend['CollectionLoan_id'],
							'CollectionLoanItem_id' => $value_loan_extend['CollectionLoanItem_id'],
							'Collection_id' => $value_loan_extend['Collection_id'],
							'Member_id' => $value_loan_extend['Member_id'],
							'DateExtend' => $value_loan_extend['DateExtend'],
							'DueDateExtend' => $value_loan_extend['DueDateExtend'],
							'CreateBy' => $value_loan_extend['CreateBy'],
							'CreateDate' => $value_loan_extend['CreateDate'],
							'CreateTerminal' => $value_loan_extend['CreateTerminal'],
							'UpdateBy' => $value_loan_extend['UpdateBy'],
							'UpdateDate' => $value_loan_extend['UpdateDate'],
							'UpdateTerminal' => $value_loan_extend['UpdateTerminal'],
						])->execute();

						
					// }

					$log .= date('Y-m-d H:i:s').' Sinkronisasi Collectionloansextend -  LoanID: '.$value_loan_extend['CollectionLoan_id'].' Koleksi ID :'.$value_loan_extend['CollectionLoanItem_id'].PHP_EOL;
				}

				// generate new auto increment collectionloanitems
				$checklastid_loanitem = Yii::$app->db->createCommand('SELECT MAX(ID) AS ID FROM collectionloanitems')->queryOne();
				
				$update_autoincrement_loanitem_local = Yii::$app->db->createCommand('ALTER TABLE collectionloanitems AUTO_INCREMENT = '.$checklastid_loanitem['ID'].'')->execute();

				$update_autoincrement_loanitem_remote = Yii::$app->db2->createCommand('ALTER TABLE collectionloanitems AUTO_INCREMENT = '.$checklastid_loanitem['ID'].'')->execute();

				// generate new auto increment members
				$checklastid_member = Yii::$app->db2->createCommand('SELECT MAX(ID) AS ID FROM members')->queryOne();

				$update_autoincrement_members_local = Yii::$app->db->createCommand('ALTER TABLE members AUTO_INCREMENT = '.$checklastid_member['ID'].'')->execute();

				$update_autoincrement_members_remote = Yii::$app->db2->createCommand('ALTER TABLE members AUTO_INCREMENT = '.$checklastid_member['ID'].'')->execute();


				// get data modelhistory per hari ini
				$modelhistory_local = Yii::$app->db->createCommand('SELECT * FROM modelhistory WHERE DATE(`date`) = DATE(NOW())')->queryAll();
				foreach ($modelhistory_local as $keymodelhistory => $valuemodelhistory) {
					// save data modelhistory to db server
					$modelhistory = Yii::$app->db2->createCommand()->insert('modelhistory', [
							    'date' => $valuemodelhistory['date'],
							    'table' => $valuemodelhistory['table'],
							    'field_name' => $valuemodelhistory['field_name'],
							    'field_id' => $valuemodelhistory['field_id'],
							    'old_value' => $valuemodelhistory['old_value'],
							    'new_value' => $valuemodelhistory['new_value'],
							    'type' => $valuemodelhistory['type'],
							    'user_id' => $valuemodelhistory['user_id'],
							])->execute();
				}
				
				// // batch insert modelhistory
				// $data = array();
				// foreach($modelhistory_local as $keymodelhistory=>$valuemodelhistory){
				//     $data[] = [
				//     	$valuemodelhistory['date'],
				//     	$valuemodelhistory['table'], 
				//     	$valuemodelhistory['field_name'], 
				//     	$valuemodelhistory['field_id'], 
				//     	$valuemodelhistory['old_value'], 
				//     	$valuemodelhistory['new_value'], 
				//     	$valuemodelhistory['type'], 
				//     	$valuemodelhistory['user_id']
				//     ];
				// }
				// Yii::$app->db2->createCommand()
				// ->batchInsert('modelhistory', ['date','table', 'field_name','field_id','old_value','new_value','type','user_id'],$data)
				// ->execute();


				if(file_exists($path.$file)){
					$new_data = $log;
					$myfile = file_put_contents($path.$file, $new_data , FILE_APPEND | LOCK_EX);
					fwrite($myfile, $new_data);

				}else{
					$content = $log;
					$fp = fopen($path . "/logs_local_to_server".date('Y-m-d').".txt","w");
					fwrite($fp,$content);
					fclose($fp);
				}
				
			}
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }
	}

	function convert($size)
    {
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }

    public function actionDownLocal(){
    	$server = Yii::$app->db2;
		$transaction = $server->beginTransaction();

		$server->open();
		try {
			if ($server->getIsActive()) {
		       $proses = $this->actionProsesSinkronisasiLocal();
		    }else{
		        $errorMsg = 'Incorrect Configurations'. PHP_EOL;
		        if(file_exists($path.$file)){
					$new_data = $errorMsg;
					$myfile = file_put_contents($path.$file, $new_data , FILE_APPEND | LOCK_EX);
					fwrite($myfile, $new_data);

				}else{
					$content = $errorMsg;
					$fp = fopen($path . "/logs_server_to_local_".date("Y-m-d").".txt","w");
					fwrite($fp,$content);
					fclose($fp);
				}
		    }

		    $transaction->commit();
		} catch(\Exception $e) {
		    $transaction->rollBack();
		    throw $e;
		    if(file_exists($path.$file)){
				$new_data = $e->getMessage();
				$myfile = file_put_contents($path.$file, $new_data , FILE_APPEND | LOCK_EX);
				fwrite($myfile, $new_data);

			}else{
				$content = $errorMsg;
				$fp = fopen($path . "/logs_server_to_local_".date("Y-m-d").".txt","w");
				fwrite($fp,$content);
				fclose($fp);
			}
		} catch(\Throwable $e) {
		    $transaction->rollBack();
		    throw $e;
		    if(file_exists($path.$file)){
				$new_data = $e->getMessage();
				$myfile = file_put_contents($path.$file, $new_data , FILE_APPEND | LOCK_EX);
				fwrite($myfile, $new_data);

			}else{
				$content = $errorMsg;
				$fp = fopen($path . "/logs_server_to_local_".date("Y-m-d").".txt","w");
				fwrite($fp,$content);
				fclose($fp);
			}
		}

    }

    public function actionProsesSinkronisasiLocal(){
    	$path = Yii::getAlias('@uploaded_files') . '/temporary/log_sinkronisasi/';
    	$file = "/logs_server_to_local_".date("Y-m-d").".txt";
    	// echo'<pre>';print_r($path);die;

    	$err=[];
        $err2=[];

    	try {
    		$start_time = ini_set('max_execution_time', 9000);

	    	$memAwal= memory_get_usage(true);
	        $memAwal2 =self::convert($memAwal);
	        echo 'Penggunaan Memory awal ='.$memAwal2. PHP_EOL;
	        $time_start = microtime(true);

	        $flashError = '';
	        $flashMsg = '';
	        
	        if($start_time > 9000){
	        	// echo 'lebih';die;
	            $flashError = 'success';
	            $flashMsg = 'The file was created !!!';

	            \Yii::$app->getSession()->setFlash($flashError, $flashMsg);
	            $this->redirect(array('index'));
	        }else{
	        	// echo 'ok';die;
	            $tables = $this->getTables();


	            Yii::$app->db->createCommand('
	            	SET AUTOCOMMIT=0;
					START TRANSACTION;
					SET SQL_QUOTE_SHOW_CREATE = 1;
					SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
					SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
				')->execute();

	            $log_table = '';
	            foreach ($tables as $tableName) {
	            	switch ($tableName) {
	            		case 'app_installed':
	            		case 'applications':
	            		case 'backup':
	            		case 'bibidavailable':
	            		case 'branchs':
	            		case 'catalogstaging':
	            		case 'opac_counter':
	            		case 'opaclogs':
	            		case 'opaclogs_keyword':
	            		case 'logsdownload':
	            		case 'logsdownload_article':
	            		case 'penduduk':
	            		// case 'quarantined_auth_data':
	            		// case 'quarantined_auth_header':
	            		// case 'quarantined_catalog_ruas':
	            		// case 'quarantined_catalog_subruas':
	            		// case 'quarantined_catalogs':
	            		// case 'quarantined_collections':
	            		// case 'quarantined_pengiriman':
	            		// case 'session':
	            			# code...
	            			break;
	            		
	            		default :
	            		// case 'catalogs':
	            			$this->getData($tableName);
	            			$time_end = microtime(true);
	            			$log_table .=  date('Y-m-d H:i:s').' Sinkronisasi table '.$tableName. PHP_EOL;
				            echo 'Sinkronisasi table '.$tableName. PHP_EOL;
					        echo 'Processing for '.($time_end-$time_start).' seconds'. PHP_EOL;
							break;
	            	}
	                // $this->getData($tableName);
	            }
	            

	            Yii::$app->db->createCommand('
	            	SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
					SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
					COMMIT;
	            ')->execute();

	            $time_end = microtime(true);
	            echo 'Sinkronisasi selesai'. PHP_EOL;
		        echo 'Processing for '.($time_end-$time_start).' seconds'. PHP_EOL;
		        $memAkhir= memory_get_usage(true);
		        $memAkhir2 =self::convert($memAkhir);
		        echo 'Penggunaan Memory Akhir ='.$memAkhir2. PHP_EOL;
		        echo 'Total Penggunaan Memory ='.self::convert($memAkhir-$memAwal). PHP_EOL;
		        echo PHP_EOL;

		        if(file_exists($path.$file)){
					$new_data = $log_table;
					$myfile = file_put_contents($path.$file, $new_data , FILE_APPEND | LOCK_EX);
					fwrite($myfile, $new_data);

				}else{
					$content = $log_table;
					$fp = fopen($path . "/logs_server_to_local_".date("Y-m-d").".txt","w");
					fwrite($fp,$content);
					fclose($fp);
				}

	            $flashError = 'success';
	            $flashMsg = 'The file was created !!!';
	            // print_r($file_name);die;

	            \Yii::$app->getSession()->setFlash($flashError, $flashMsg);
	            
	            // $this->redirect(array('index'));
	        }
    	} catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }
    }

    public function getTables($dbName = null) {
        $sql = 'SHOW FULL TABLES WHERE Table_Type = "BASE TABLE"';
        $cmd = Yii::$app->db2->createCommand($sql);
        $tables = $cmd->queryColumn();
        return $tables;
    }


    public function getData($tableName) {
    	$transaction = Yii::$app->db2->beginTransaction();
		try {
		    $delete_table = Yii::$app->db->createCommand()->delete($tableName)->execute();

	        $sql = 'SELECT * FROM ' . $tableName;
	        $cmd = Yii::$app->db2->createCommand($sql);
	        $dataReader = $cmd->query();

	        $data_string = '';

	        foreach ($dataReader as $data) {
	        	switch ($tableName) {
	        		case 'catalogs':
	        			$data['IsBNI'] = $data['IsBNI'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['IsKIN'] = $data['IsKIN'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['IsRDA'] = $data['IsRDA'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'catalogfiles':
	        			$data['IsFromMember'] = $data['IsFromMember'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'collections':
	        			$data['IsVerified'] = $data['IsVerified'] == 0 ? 'FALSE' : 'TRUE' ;
	        			if($data['ISREFERENSI'] !== NULL){
	        				$data['ISREFERENSI'] = $data['ISREFERENSI'] == 0 ? 'FALSE' : 'TRUE' ;
	        			}
	        			break;
	        		case 'fielddatas':
	        			$data['Repeatable'] = $data['Repeatable'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['IsShow'] = $data['IsShow'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'fields':
	        			$data['Fixed'] = $data['Fixed'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['Enabled'] = $data['Enabled'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['Repeatable'] = $data['Repeatable'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['Mandatory'] = $data['Mandatory'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['IsCustomable'] = $data['IsCustomable'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['ISSUBSERIAL'] = $data['ISSUBSERIAL'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'locations':
	        			$data['ISPUSTELING'] = $data['ISPUSTELING'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'mailserver':
	        			$data['EnableSsl'] = $data['EnableSsl'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['IsActive'] = $data['IsActive'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'master_jenis_identitas':
	        			$data['IsNIK'] = $data['IsNIK'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'member_fields':
	        			$data['mandatory'] = $data['mandatory'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'member_perpanjangan':
	        			$data['IsLunas'] = $data['IsLunas'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'memberrules':
	        			$data['isPublish'] = $data['isPublish'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'members':
	        			$data['IsLunasBiayaPendaftaran'] = $data['IsLunasBiayaPendaftaran'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'modules':
	        			$data['IsPublish'] = $data['IsPublish'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'pelanggaran':
	        			$data['Paid'] = $data['Paid'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'pengiriman_koleksi':
	        			$data['IsCheck'] = $data['IsCheck'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'quarantined_catalogs':
	        			$data['IsBNI'] = $data['IsBNI'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['IsKIN'] = $data['IsKIN'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['IsRDA'] = $data['IsRDA'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'quarantined_collections':
	        			$data['IsVerified'] = $data['IsVerified'] == 0 ? 'FALSE' : 'TRUE' ;
	        			if($data['ISREFERENSI'] !== NULL){
	        				$data['ISREFERENSI'] = $data['ISREFERENSI'] == 0 ? 'FALSE' : 'TRUE' ;
	        			}
	        			break;
	        		case 'serial_articlefiles':
	        			$data['IsFromMember'] = $data['IsFromMember'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'serial_articles':
	        			$data['ISOPAC'] = $data['ISOPAC'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'survey':
	        			$data['IsActive'] = $data['IsActive'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'survey_pertanyaan':
	        			$data['IsMandatory'] = $data['IsMandatory'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['IsCanMultipleAnswer'] = $data['IsCanMultipleAnswer'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'users':
	        			$data['IsActive'] = $data['IsActive'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['IsCanResetUserPassword'] = $data['IsCanResetUserPassword'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['IsCanResetMemberPassword'] = $data['IsCanResetMemberPassword'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['IsAdvanceEntryCatalog'] = $data['IsAdvanceEntryCatalog'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['IsAdvanceEntryCollection'] = $data['IsAdvanceEntryCollection'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'worksheetfields':
	        			$data['IsAkuisisi'] = $data['IsAkuisisi'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['ISDEPOSIT'] = $data['ISDEPOSIT'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['ISDETAILKOLEKSI_PENGOLAHAN'] = $data['ISDETAILKOLEKSI_PENGOLAHAN'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['ISDETAILKOLEKSI_AKUISISI'] = $data['ISDETAILKOLEKSI_AKUISISI'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		case 'worksheets':
	        			$data['ISMUSIK'] = $data['ISMUSIK'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['ISKARTOGRAFI'] = $data['ISKARTOGRAFI'] == 0 ? 'FALSE' : 'TRUE' ;
	        			$data['IsBerisiArtikel'] = $data['IsBerisiArtikel'] == 0 ? 'FALSE' : 'TRUE' ;
	        			break;
	        		default:
	        			break;
	        	}
	            $itemNames = array_keys($data);
	            $itemNames = array_map("addslashes", $itemNames);
	            $items = join('`,`', $itemNames);

	            $array = array_values($data);
	            $array2 = array_map(function($value) {
	               return $value === null ? 'NULL' : $value;
	            }, $array);
	            $itemValues = array_map("addslashes", $array2);
	            $valueString = join("','", $itemValues);
	            $valueString = "('" . $valueString . "'),";
	            //$char = array("['NULL']", "['FALSE']", "['TRUE']");
	            $valueString = preg_replace("['NULL']","NULL", $valueString);
	            $valueString = preg_replace("['FALSE']","FALSE", $valueString);
	            $valueString = preg_replace("['TRUE']","TRUE", $valueString);
	            $values = "\n" . $valueString;
	            if ($values != "") {
	                // $data_string = "INSERT INTO `$tableName` (`$items`) VALUES" . rtrim($values, ",") . ";" . PHP_EOL;
	                Yii::$app->db->createCommand("INSERT INTO `$tableName` (`$items`) VALUES" . rtrim($values, ",") . ";")->execute();
	            }
	            // echo'<pre>';print_r($data_string);die;
	        }

	        if ($data_string == '')
	            return null;

	        // if ($this->fp) {
	        //     $this->writeComment('TABLE DATA ' . $tableName);
	        //     $final = $data_string . PHP_EOL . PHP_EOL . PHP_EOL;
	        //     fwrite($this->fp, $final);
	        // } else {
	            $this->tables[$tableName]['data'] = $data_string;
	            // return $data_string;
	        // }
		} catch (Exception $e) {
		    $transaction->rollBack();
		}
    	
    }



    /************************* one by one *******************************/
 	// public function actionDownLocal($time = ''){
	// 	$memAwal= memory_get_usage(true);
 //        $memAwal2 =self::convert($memAwal);
 //        echo 'Penggunaan Memory awal ='.$memAwal2. PHP_EOL;
 //        $time_start = microtime(true);

 //        /************************** members ******************/
 //        // delete local members
 //        Yii::$app->db->createCommand()->delete('members')->execute();

 //        // get data members remote
 //        $member_remote = Yii::$app->db2->createCommand('SELECT * FROM members')->queryAll();

 //        $data_member = array();
 //        foreach ($member_remote as $key_member_remote => $value_member_remote) {
 //        	$data_member[] = [
 //        		$value_member_remote['ID'],
 //        		$value_member_remote['MemberNo'],
 //        		$value_member_remote['Fullname'],
 //        		$value_member_remote['PlaceOfBirth'],
 //        		$value_member_remote['DateOfBirth'],
 //        		$value_member_remote['Address'],
 //        		$value_member_remote['AddressNow'],
 //        		$value_member_remote['Phone'],
 //        		$value_member_remote['InstitutionName'],
 //        		$value_member_remote['InstitutionAddress'],
 //        		$value_member_remote['InstitutionPhone'],
 //        		$value_member_remote['IdentityType_id'],
 //        		$value_member_remote['IdentityNo'],
 //        		$value_member_remote['EducationLevel_id'],
 //        		$value_member_remote['Sex_id'],
 //        		$value_member_remote['MaritalStatus_id'],
 //        		$value_member_remote['Job_id'],
 //        		$value_member_remote['RegisterDate'],
 //        		$value_member_remote['EndDate'],
 //        		$value_member_remote['MotherMaidenName'],
 //        		$value_member_remote['Email'],
 //        		$value_member_remote['JenisPermohonan_id'],
 //        		$value_member_remote['JenisAnggota_id'],
 //        		$value_member_remote['StatusAnggota_id'],
 //        		$value_member_remote['LoanReturnLateCount'],
 //        		$value_member_remote['Branch_id'],
 //        		$value_member_remote['PhotoUrl'],
 //        		$value_member_remote['CreateBy'],
 //        		$value_member_remote['CreateDate'],
 //        		$value_member_remote['CreateTerminal'],
 //        		$value_member_remote['UpdateBy'],
 //        		$value_member_remote['UpdateDate'],
 //        		$value_member_remote['UpdateTerminal'],
 //        		$value_member_remote['NoHp'],
 //        		$value_member_remote['NamaDarurat'],
 //        		$value_member_remote['TelpDarurat'],
 //        		$value_member_remote['AlamatDarurat'],
 //        		$value_member_remote['StatusHubunganDarurat'],
 //        		$value_member_remote['City'],
 //        		$value_member_remote['Province'],
 //        		$value_member_remote['CityNow'],
 //        		$value_member_remote['ProvinceNow'],
 //        		$value_member_remote['Kecamatan'],
 //        		$value_member_remote['Kelurahan'],
 //        		$value_member_remote['RT'],
 //        		$value_member_remote['RW'],
 //        		$value_member_remote['KecamatanNow'],
 //        		$value_member_remote['KelurahanNow'],
 //        		$value_member_remote['RTNow'],
 //        		$value_member_remote['RWNow'],
 //        		$value_member_remote['Kelas_id'],
 //        		$value_member_remote['TahunAjaran'],
 //        		$value_member_remote['Agama_id'],
 //        		$value_member_remote['Fakultas_id'],
 //        		$value_member_remote['Jurusan_id'],
 //        		$value_member_remote['ProgramStudi_id'],
 //        		$value_member_remote['JenjangPendidikan_id'],
 //        		$value_member_remote['UnitKerja_id'],
 //        		$value_member_remote['KeteranganLain'],
 //        		$value_member_remote['IsLunasBiayaPendaftaran'],
 //        		$value_member_remote['BiayaPendaftaran'],
 //        		$value_member_remote['TanggalBebasPustaka'],
 //        		$value_member_remote['KIILastUploadDate'],
 //        	];
 //        }

 //        // save members to local
 //        $save_member = Yii::$app->db->createCommand()->batchInsert('members',['ID','MemberNo','Fullname','PlaceOfBirth','DateOfBirth','Address','AddressNow','Phone','InstitutionName','InstitutionAddress','InstitutionPhone','IdentityType_id','IdentityNo','EducationLevel_id','Sex_id','MaritalStatus_id','Job_id','RegisterDate','EndDate','MotherMaidenName','Email','JenisPermohonan_id','JenisAnggota_id','StatusAnggota_id','LoanReturnLateCount','Branch_id','PhotoUrl','CreateBy','CreateDate','CreateTerminal','UpdateBy','UpdateDate','UpdateTerminal','NoHp','NamaDarurat','TelpDarurat','AlamatDarurat','StatusHubunganDarurat','City','Province','CityNow','ProvinceNow','Kecamatan','Kelurahan','RT','RW','KecamatanNow','KelurahanNow','RTNow','RWNow','Kelas_id','TahunAjaran','Agama_id','Fakultas_id','Jurusan_id','ProgramStudi_id','JenjangPendidikan_id','UnitKerja_id','KeteranganLain','IsLunasBiayaPendaftaran','BiayaPendaftaran','TanggalBebasPustaka','KIILastUploadDate'], $data_member)
 //        ->execute();
 //        unset($save_member);

 //        // delete local memberloanauthcategory
 //        Yii::$app->db->createCommand()->delete('memberloanauthorizecategory')->execute();

	// 	$memberloanauthcategory_remote = Yii::$app->db2->createCommand('SELECT * FROM memberloanauthorizecategory')->queryAll();
		
	// 	$data_memberauthcategory = array();
	// 	foreach ($memberloanauthcategory_remote as $keyauthcat => $valueauthcat) {
	// 		$data_memberauthcategory[] = [
	// 			$valueauthcat['Member_id'],
	// 			$valueauthcat['CategoryLoan_id'],
	// 			$valueauthcat['CreateBy'],
	// 			$valueauthcat['CreateDate'],
	// 			$valueauthcat['CreateTerminal'],
	// 			$valueauthcat['UpdateBy'],
	// 			$valueauthcat['UpdateDate'],
	// 			$valueauthcat['UpdateTerminal'],
	// 		];
	// 	}

	// 	// save data memberloanauthorizecategory to db local
	// 	$save_memberloanauthcategory = Yii::$app->db->createCommand()->batchInsert('memberloanauthorizecategory', ['Member_id', 'CategoryLoan_id', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'],$data_memberauthcategory)->execute();
	// 	unset($save_memberloanauthcategory);

	// 	// delete local memberloanauthlocation
 //        Yii::$app->db->createCommand()->delete('memberloanauthorizelocation')->execute();

	// 	$memberloanauthlocation_remote = Yii::$app->db2->createCommand('SELECT * FROM memberloanauthorizelocation')->queryAll();
							
	// 	$data_memberauthlocation = array();
	// 	foreach ($memberloanauthlocation_remote as $keyauthcat => $valueauthcat) {
	// 		// echo'<pre>';print_r($valueauthcat);
	// 		$data_memberauthlocation[] = [
	// 		    $valueauthcat['Member_id'],
	// 		    $valueauthcat['LocationLoan_id'],
	// 		    $valueauthcat['CreateBy'],
	// 		    $valueauthcat['CreateDate'],
	// 		    $valueauthcat['CreateTerminal'],
	// 		    $valueauthcat['UpdateBy'],
	// 		    $valueauthcat['UpdateDate'],
	// 		    $valueauthcat['UpdateTerminal'],
	// 		];
	// 	}

	// 	// save data memberloanauthorizelocation to db local
	// 	$save_memberloanauthcategory = Yii::$app->db->createCommand()->batchInsert('memberloanauthorizelocation', ['Member_id', 'LocationLoan_id', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'],$data_memberauthlocation)->execute();
	// 	unset($save_memberloanauthcategory);

	// 	// delete local member_perpanjangan
	// 	Yii::$app->db->createCommand()->delete('member_perpanjangan')->execute();

	// 	// get data member perpanjangan per hari ini
	// 	$member_perpanjangan_remote = Yii::$app->db2->createCommand('SELECT * FROM member_perpanjangan')->queryAll();
	// 	$member_perpanjangan = array();
	// 	foreach ($member_perpanjangan_remote as $keymember_perpanjang => $valuemember_perpanjang) {
			
	// 		$member_perpanjangan[] = [
	// 				    $valuemember_perpanjang['ID'],
	// 				    $valuemember_perpanjang['Member_id'],
	// 				    $valuemember_perpanjang['Tanggal'],
	// 				    $valuemember_perpanjang['Biaya'],
	// 				    $valuemember_perpanjang['IsLunas'],
	// 				    $valuemember_perpanjang['Keterangan'],
	// 				    $valuemember_perpanjang['CreateBy'],
	// 				    $valuemember_perpanjang['CreateDate'],
	// 				    $valuemember_perpanjang['CreateTerminal'],
	// 				    $valuemember_perpanjang['UpdateBy'],
	// 				    $valuemember_perpanjang['UpdateDate'],
	// 				    $valuemember_perpanjang['UpdateTerminal'],
	// 				];
	// 	}

	// 	// save data member_perpanjangan to db local
	// 	$save_member_perpanjangan = Yii::$app->db->createCommand()->batchInsert('member_perpanjangan', ['ID', 'Member_id', 'Tanggal', 'Biaya', 'IsLunas', 'Keterangan', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'],$member_perpanjangan)->execute();
	// 	unset($save_member_perpanjangan);

	// 	// delete local bacaditempat
	// 	Yii::$app->db->createCommand()->delete('bacaditempat')->execute();
		
	// 	// get data bacaditempat server
	// 	$bacaditempat_remote = Yii::$app->db2->createCommand('SELECT * FROM bacaditempat')->queryAll();
	// 	$bacaditempat = array();
	// 	foreach ($bacaditempat_remote as $keybaca => $valuebaca) {
	// 		// save data bacaditempat to db server
	// 		$bacaditempat[] = [
	// 				    'ID' => $valuebaca['ID'],
	// 				    'NoPengunjung' => $valuebaca['NoPengunjung'],
	// 				    'collection_id' => $valuebaca['collection_id'],
	// 				    'CreateBy' => $valuebaca['CreateBy'],
	// 				    'CreateDate' => $valuebaca['CreateDate'],
	// 				    'CreateTerminal' => $valuebaca['CreateTerminal'],
	// 				    'UpdateBy' => $valuebaca['UpdateBy'],
	// 				    'UpdateDate' => $valuebaca['UpdateDate'],
	// 				    'UpdateTerminal' => $valuebaca['UpdateTerminal'],
	// 				    'Member_id' => $valuebaca['Member_id'],
	// 				    'Location_Id' => $valuebaca['Location_Id'],
	// 				    'Is_return' => $valuebaca['Is_return'],
	// 				];
	// 	}

	// 	// save data bacaditempat to db local
	// 	$save_bacaditempat = Yii::$app->db->createCommand()->batchInsert('bacaditempat', ['ID', 'NoPengunjung', 'collection_id', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal','Member_id','Location_Id','Is_return'],$bacaditempat)->execute();
	// 	unset($save_bacaditempat);


	// 	// get data bukutamu member dan non member
	// 	$bukutamumember_remote = Yii::$app->db2->createCommand('SELECT * FROM memberguesses')->queryAll();
	// 	if(!empty($bukutamumember_remote)){
	// 		// delete local bukutamu
	// 		Yii::$app->db->createCommand()->delete('memberguesses')->execute();

	// 		$datamember_bacaditempat = array();
	// 		foreach($bukutamumember_remote as $keybacaditempat_member=>$valuebacaditempat_member){
	// 		    $datamember_bacaditempat[] = [
	// 		    	$valuebacaditempat_member['NoAnggota'],
	// 		    	$valuebacaditempat_member['Nama'], 
	// 		    	$valuebacaditempat_member['Status_id'], 
	// 		    	$valuebacaditempat_member['MasaBerlaku_id'], 
	// 		    	$valuebacaditempat_member['Profesi_id'], 
	// 		    	$valuebacaditempat_member['PendidikanTerakhir_id'], 
	// 		    	$valuebacaditempat_member['JenisKelamin_id'], 
	// 		    	$valuebacaditempat_member['Alamat'],
	// 		    	$valuebacaditempat_member['CreateBy'],
	// 		    	$valuebacaditempat_member['CreateDate'],
	// 		    	$valuebacaditempat_member['CreateTerminal'],
	// 		    	$valuebacaditempat_member['UpdateBy'],
	// 		    	$valuebacaditempat_member['UpdateDate'],
	// 		    	$valuebacaditempat_member['UpdateTerminal'],
	// 		    	$valuebacaditempat_member['Deskripsi'],
	// 		    	$valuebacaditempat_member['LOCATIONLOANS_ID'],
	// 		    	$valuebacaditempat_member['Location_Id'],
	// 		    	$valuebacaditempat_member['TujuanKunjungan_Id'],
	// 		    	$valuebacaditempat_member['Information'],
	// 		    	$valuebacaditempat_member['NoPengunjung'],
	// 		    ];
	// 		}
	// 		$save_bukutamumember = Yii::$app->db->createCommand()
	// 		->batchInsert('memberguesses', ['NoAnggota','Nama', 'Status_id','MasaBerlaku_id','Profesi_id','PendidikanTerakhir_id','JenisKelamin_id','Alamat','CreateBy','CreateDate','CreateTerminal','UpdateBy','UpdateDate','UpdateTerminal','Deskripsi','LOCATIONLOANS_ID','Location_Id','TujuanKunjungan_Id','Information','NoPengunjung'],$datamember_bacaditempat)
	// 		->execute();

	// 		unset($save_bukutamumember);
	// 	}

	// 	// get data bukutamu group
	// 	$bukutamugroup_remote = Yii::$app->db2->createCommand('SELECT * FROM groupguesses')->queryAll();
	// 	// echo'<pre>';print(count($bukutamugroup_local));die;
	// 	if(!empty($bukutamugroup_remote)){
	// 		// delete local bukutamu group
	// 		Yii::$app->db->createCommand()->delete('groupguesses')->execute();

	// 		$datagroup_bacaditempat = array();
	// 		foreach($bukutamugroup_remote as $keybacaditempat_group=>$valuebacaditempat_group){
	// 		    $datagroup_bacaditempat[] = [
	// 		    	$valuebacaditempat_group['NamaKetua'],
	// 		    	$valuebacaditempat_group['NomerTelponKetua'], 
	// 		    	$valuebacaditempat_group['AsalInstansi'], 
	// 		    	$valuebacaditempat_group['AlamatInstansi'], 
	// 		    	$valuebacaditempat_group['CountPersonel'], 
	// 		    	$valuebacaditempat_group['CountPNS'], 
	// 		    	$valuebacaditempat_group['CountPSwasta'], 
	// 		    	$valuebacaditempat_group['CountPeneliti'],
	// 		    	$valuebacaditempat_group['CountGuru'],
	// 		    	$valuebacaditempat_group['CountDosen'],
	// 		    	$valuebacaditempat_group['CountPensiunan'],
	// 		    	$valuebacaditempat_group['CountTNI'],
	// 		    	$valuebacaditempat_group['CountWiraswasta'],
	// 		    	$valuebacaditempat_group['CountPelajar'],
	// 		    	$valuebacaditempat_group['CountMahasiswa'],
	// 		    	$valuebacaditempat_group['CountLainnya'],
	// 		    	$valuebacaditempat_group['CountSD'],
	// 		    	$valuebacaditempat_group['CountSMP'],
	// 		    	$valuebacaditempat_group['CountSMA'],
	// 		    	$valuebacaditempat_group['CountD1'],
	// 		    	$valuebacaditempat_group['CountD2'],
	// 		    	$valuebacaditempat_group['CountD3'],
	// 		    	$valuebacaditempat_group['CountS1'],
	// 		    	$valuebacaditempat_group['CountS2'],
	// 		    	$valuebacaditempat_group['CountS3'],
	// 		    	$valuebacaditempat_group['CountLaki'],
	// 		    	$valuebacaditempat_group['CountPerempuan'],
	// 		    	$valuebacaditempat_group['TujuanKunjungan_ID'],
	// 		    	$valuebacaditempat_group['CreateBy'],
	// 		    	$valuebacaditempat_group['CreateDate'],
	// 		    	$valuebacaditempat_group['CreateTerminal'],
	// 		    	$valuebacaditempat_group['UpdateBy'],
	// 		    	$valuebacaditempat_group['UpdateDate'],
	// 		    	$valuebacaditempat_group['UpdateTerminal'],
	// 		    	$valuebacaditempat_group['LocationLoans_ID'],
	// 		    	$valuebacaditempat_group['Location_ID'],
	// 		    	$valuebacaditempat_group['TeleponInstansi'],
	// 		    	$valuebacaditempat_group['EmailInstansi'],
	// 		    	$valuebacaditempat_group['Information'],
	// 		    	$valuebacaditempat_group['NoPengunjung'],
	// 		    ];
	// 		}
			
	// 		$save_bukutamu_group = Yii::$app->db->createCommand()
	// 		->batchInsert('groupguesses', ['NamaKetua','NomerTelponKetua', 'AsalInstansi','AlamatInstansi',
	// 			'CountPersonel','CountPNS','CountPSwasta','CountPeneliti','CountGuru','CountDosen',
	// 			'CountPensiunan','CountTNI','CountWiraswasta','CountPelajar','CountMahasiswa','CountLainnya',
	// 			'CountSD','CountSMP','CountSMA','CountD1',
	// 			'CountD2','CountD3','CountS1','CountS2','CountS3','CountLaki','CountPerempuan',
	// 			'TujuanKunjungan_ID','CreateBy','CreateDate','CreateTerminal','UpdateBy','UpdateDate',
	// 			'UpdateTerminal','LocationLoans_ID','Location_ID','TeleponInstansi','EmailInstansi',
	// 			'Information','NoPengunjung'],$datagroup_bacaditempat)
	// 		->execute();

	// 		unset($save_bukutamu_group);
	// 	}

 //        /************************** collections ******************/
	// 	// delete local collections
	// 	Yii::$app->db->createCommand()->delete('collections')->execute();
	// 	// get data collections remote
	// 	$collections_remote = Yii::$app->db2->createCommand('SELECT * FROM collections')->queryAll();
		
	// 	$data = array();
	// 	foreach($collections_remote as $keycollections_local=>$valuecollections_local){
	// 	    $data[] = [
	// 	    	$valuecollections_local['ID'],
	// 	    	$valuecollections_local['NomorBarcode'], 
	// 	    	$valuecollections_local['NoInduk'], 
	// 	    	$valuecollections_local['Currency'], 
	// 	    	$valuecollections_local['RFID'], 
	// 	    	$valuecollections_local['Price'], 
	// 	    	$valuecollections_local['PriceType'], 
	// 	    	$valuecollections_local['TanggalPengadaan'],
	// 	    	$valuecollections_local['CallNumber'],
	// 	    	$valuecollections_local['Branch_id'],
	// 	    	$valuecollections_local['Catalog_id'],
	// 	    	$valuecollections_local['Partner_id'],
	// 	    	$valuecollections_local['Location_id'],
	// 	    	$valuecollections_local['Rule_id'],
	// 	    	$valuecollections_local['Category_id'],
	// 	    	$valuecollections_local['Media_id'],
	// 	    	$valuecollections_local['Source_id'],
	// 	    	$valuecollections_local['Status_id'],
	// 	    	$valuecollections_local['Location_Library_id'],
	// 	    	$valuecollections_local['Keterangan_Sumber'],
	// 	    	$valuecollections_local['CreateBy'],
	// 	    	$valuecollections_local['CreateDate'],
	// 	    	$valuecollections_local['CreateTerminal'],
	// 	    	$valuecollections_local['UpdateBy'],
	// 	    	$valuecollections_local['UpdateDate'],
	// 	    	$valuecollections_local['UpdateTerminal'],
	// 	    	$valuecollections_local['IsVerified'],
	// 	    	$valuecollections_local['QUARANTINEDBY'],
	// 	    	$valuecollections_local['QUARANTINEDDATE'],
	// 	    	$valuecollections_local['QUARANTINEDTERMINAL'],
	// 	    	$valuecollections_local['ISREFERENSI'],
	// 	    	$valuecollections_local['EDISISERIAL'],
	// 	    	$valuecollections_local['NOJILID'],
	// 	    	$valuecollections_local['TANGGAL_TERBIT_EDISI_SERIAL'],
	// 	    	$valuecollections_local['BAHAN_SERTAAN'],
	// 	    	$valuecollections_local['KETERANGAN_LAIN'],
	// 	    	$valuecollections_local['TGLENTRYJILID'],
	// 	    	$valuecollections_local['IDJILID'],
	// 	    	$valuecollections_local['NOMORPANGGILJILID'],
	// 	    	$valuecollections_local['ISOPAC'],
	// 	    	$valuecollections_local['JILIDCREATEBY'],
	// 	    	$valuecollections_local['KIILastUploadDate'],
	// 	    	$valuecollections_local['BookingMemberID'],
	// 	    	$valuecollections_local['BookingExpiredDate'],
	// 	    ];
	// 	}

	// 	// Yii::$app->db->createCommand('ALTER TABLE modelhistory AUTO_INCREMENT = 1')->execute();

	// 	$save_collections = Yii::$app->db->createCommand()->batchInsert('collections', ['ID','NomorBarcode', 'NoInduk','Currency','RFID','Price','PriceType','TanggalPengadaan','CallNumber','Branch_id','Catalog_id','Partner_id','Location_id','Rule_id','Category_id','Media_id','Source_id','Status_id','Location_Library_id','Keterangan_Sumber','CreateBy','CreateDate','CreateTerminal','UpdateBy','UpdateDate','UpdateTerminal','IsVerified','QUARANTINEDBY','QUARANTINEDDATE','QUARANTINEDTERMINAL','ISREFERENSI','EDISISERIAL','NOJILID','TANGGAL_TERBIT_EDISI_SERIAL','BAHAN_SERTAAN','KETERANGAN_LAIN','TGLENTRYJILID','IDJILID','NOMORPANGGILJILID','ISOPAC','JILIDCREATEBY','KIILastUploadDate','BookingMemberID','BookingExpiredDate'],$data)
	// 	->execute();
	// 	unset($save_collections);

	// 	// /************************ catalogs **********************/
	// 	// // delete local catalogs
	// 	// Yii::$app->db->createCommand()->delete('catalogs')->execute();

	// 	// // get data catalogs remote
	// 	// $catalogs_remote = Yii::$app->db2->createCommand('SELECT * FROM catalogs')->queryAll();

	// 	// $data_catalogs = array();
	// 	// foreach ($catalogs_remote as $key_catalogs => $value_catalogs) {
	// 	// 	$data_catalogs[] = [
	// 	// 		$value_catalogs['ID'],
	// 	// 		$value_catalogs['ControlNumber'],
	// 	// 		$value_catalogs['BIBID'],
	// 	// 		$value_catalogs['Title'],
	// 	// 		$value_catalogs['Author'],
	// 	// 		$value_catalogs['Edition'],
	// 	// 		$value_catalogs['Publisher'],
	// 	// 		$value_catalogs['PublishLocation'],
	// 	// 		$value_catalogs['PublishYear'],
	// 	// 		$value_catalogs['Author'],
	// 	// 	];
	// 	// }

	// 	// $save_catalogs = Yii::$app->db->createCommand()->batchInsert('catalogs', ['ID', 'ControlNumber','BIBID','Title','Author','Edition','Publisher','PublishLocation','PublishYear'], $data_catalogs)
	// 	// ->execute();
	// 	// unset($save_catalogs);

	// 	// modelhistory
	// 	Yii::$app->db->createCommand()
 //        ->delete('modelhistory')
 //        ->execute();
	// 	// get data modelhistory 
	// 	$modelhistory_local = Yii::$app->db2->createCommand('SELECT * FROM modelhistory')->queryAll();
		
	// 	$data = array();
	// 	foreach($modelhistory_local as $keymodelhistory=>$valuemodelhistory){
	// 	    $data[] = [
	// 	    	$valuemodelhistory['date'],
	// 	    	$valuemodelhistory['table'], 
	// 	    	$valuemodelhistory['field_name'], 
	// 	    	$valuemodelhistory['field_id'], 
	// 	    	$valuemodelhistory['old_value'], 
	// 	    	$valuemodelhistory['new_value'], 
	// 	    	$valuemodelhistory['type'], 
	// 	    	$valuemodelhistory['user_id']
	// 	    ];
	// 	}

	// 	Yii::$app->db->createCommand('ALTER TABLE modelhistory AUTO_INCREMENT = 1')->execute();

	// 	Yii::$app->db->createCommand()->batchInsert('modelhistory', ['date','table', 'field_name','field_id','old_value','new_value','type','user_id'],$data)
	// 	->execute();

	// 	$time_end = microtime(true);
 //        echo 'Sinkronisasi selesai'. PHP_EOL;
 //        echo 'Processing for '.($time_end-$time_start).' seconds'. PHP_EOL;
 //        $memAkhir= memory_get_usage(true);
 //        $memAkhir2 =self::convert($memAkhir);
 //        echo 'Penggunaan Memory Akhir ='.$memAkhir2. PHP_EOL;
 //        echo 'Total Penggunaan Memory ='.self::convert($memAkhir-$memAwal). PHP_EOL;
 //        echo PHP_EOL;
	// }
    
}