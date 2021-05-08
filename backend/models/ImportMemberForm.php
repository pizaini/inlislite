<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

use common\components\MemberHelpers;
use common\components\Helpers;
use common\models\Memberloanauthorizelocation;
use common\models\Memberloanauthorizecategory;
use common\components\DirectoryHelpers;

class ImportMemberForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

   /* public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls,xlsx','checkExtensionByMimeType' => false],
        ];
    }*/
    
    public function upload()
    {
        $path = Yii::getAlias('@uploaded_files') . '/temporary/imported_data_sheet/imported/';

        if ($this->validate()) {
            $this->file->saveAs($path. $this->file->baseName . '.' . $this->file->extension);
            return true;
        } else {
            return false;
        }

    }

    public function import(){
        try{
                    $path = Yii::getAlias('@uploaded_files') . '/temporary/imported_data_sheet/imported/'.$this->file->baseName . '.' . $this->file->extension;

                    $data = \moonland\phpexcel\Excel::widget([
                            'mode' => 'import', 
                            'fileName' => $path, 
                            'setFirstRecordAsKeys' => true, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel. 
                            'setIndexSheetByName' => true, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric. 
                            'getOnlySheet' => 'Sheet1', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
                        ]);
                          //echo '<pre>'; print_r($data); echo '</pre>';die;
                    foreach ($data as $row) {

                        $members = new \common\models\Members;
                        $members->MemberNo = Helpers::collapseSpaces($row['NO ANGGOTA']);
                        $members->Fullname = Helpers::collapseSpaces($row['NAMA']);
                        $members->PlaceOfBirth = Helpers::collapseSpaces($row['TEMPAT LAHIR']);
                        $members->DateOfBirth = Helpers::collapseSpaces(Helpers::DateToMysqlFormat('-',$row['TANGGAL LAHIR']));
                        $members->Address = Helpers::collapseSpaces($row['ALAMAT SESUAI KTP']);
                        $members->Kecamatan = Helpers::collapseSpaces($row['KECAMATAN SESUAI KTP']);
                        $members->Kelurahan = Helpers::collapseSpaces($row['KELURAHAN SESUAI KTP']);
                        $members->RT = Helpers::collapseSpaces($row['RT SESUAI KTP']);
                        $members->RW = Helpers::collapseSpaces($row['RW SESUAI KTP']);
                        $members->Province = Helpers::collapseSpaces($row['PROPINSI SESUAI KTP']);
                        $members->City = Helpers::collapseSpaces($row['KABUPATEN / KOTA SESUAI KTP']);


                        $members->AddressNow = Helpers::collapseSpaces($row['ALAMAT TEMPAT TINGGAL SEKARANG']);
                        $members->KecamatanNow = Helpers::collapseSpaces($row['KECAMATAN SEKARANG']);
                        $members->KelurahanNow = Helpers::collapseSpaces($row['KELURAHAN SEKARANG']);
                        $members->RTNow = Helpers::collapseSpaces($row['RT SEKARANG']);
                        $members->RWNow = Helpers::collapseSpaces($row['RW SEKARANG']);
                        $members->ProvinceNow = Helpers::collapseSpaces($row['PROPINSI SEKARANG']);
                        $members->CityNow = Helpers::collapseSpaces($row['KABUPATEN / KOTA SEKARANG']);

                        $members->NoHp = Helpers::collapseSpaces($row['NO. HP']);
                        $members->Phone = Helpers::collapseSpaces($row['NO. TELP RUMAH']);
                         $identityTypeId = \common\models\MasterJenisIdentitas::find()->where(['Nama' => Helpers::collapseSpaces($row['JENIS IDENTITAS'])])->one();
                         if(is_null($identityTypeId)){
                            // INSERT IDENTITY TYPE BARU
                            $masterJenisIdentitas = new \common\models\MasterJenisIdentitas;
                            $masterJenisIdentitas->Nama = Helpers::collapseSpaces($row['JENIS IDENTITAS']);
                            $masterJenisIdentitas->save();
                            $identityTypeId =  $masterJenisIdentitas->getPrimaryKey();
                        }else{
                             $identityTypeId = $identityTypeId->id;
                        }
                         $members->IdentityType_id = $identityTypeId;
                        $members->IdentityNo = Helpers::collapseSpaces($row['NO. IDENTITAS']);



                        $members->InstitutionName = Helpers::collapseSpaces($row['NAMA INSTITUSI']);
                        $members->InstitutionAddress = Helpers::collapseSpaces($row['ALAMAT INSTITUSI']);
                        $members->InstitutionPhone = Helpers::collapseSpaces($row['NO TELP INSTITUSI']);
                       

                        // CHECK MASTER PENDIDIKAN
                        $education = \common\models\MasterPendidikan::find()->where(['Nama' => Helpers::collapseSpaces($row['PENDIDIKAN TERAKHIR'])])->one();
                        
                        if(is_null($education)){
                            // INSERT MASTER PENDIDIKAN BARU
                            $masterEducation = new \common\models\MasterPendidikan;
                            $masterEducation->Nama = Helpers::collapseSpaces($row['PENDIDIKAN TERAKHIR']);
                            $masterEducation->save();
                            $education =  $masterEducation->getPrimaryKey();
                        }else{
                            $education = $education->id;
                        }

                        $members->EducationLevel_id = $education;

                        // CHECK SEX
                        $sex = \common\models\JenisKelamin::find()->where(['Name' => Helpers::collapseSpaces($row['JENIS KELAMIN'])])->one();
                        
                        if(is_null($sex)){
                            // INSERT MASTER Jenis Kelamin
                            $masterSex = new \common\models\JenisKelamin;
                            $masterSex->Name = Helpers::collapseSpaces($row['JENIS KELAMIN']);
                            $masterSex->save();
                            $sex =  $masterSex->getPrimaryKey();
                        }else{
                            $sex = $sex->ID;
                        }

                        $members->Sex_id = $sex;

                        // CHECK MASTER PERKAWINAN
                        $statusPerkawinan = \common\models\MasterStatusPerkawinan::find()->where(['Nama' => Helpers::collapseSpaces($row['STATUS PERKAWINAN'])])->one();
                        
                        if(is_null($statusPerkawinan)){
                            // INSERT MASTER PENDIDIKAN BARU
                            $masterStatusPerkawinan = new \common\models\MasterStatusPerkawinan;
                            $masterStatusPerkawinan->Nama = Helpers::collapseSpaces($row['STATUS PERKAWINAN']);
                            $masterStatusPerkawinan->save();
                            $statusPerkawinan =  $masterStatusPerkawinan->getPrimaryKey();
                        }else{
                            $statusPerkawinan = $statusPerkawinan->id;
                        }

                        $members->MaritalStatus_id = $statusPerkawinan;

                         // CHECK MASTER PERKAWINAN
                        $statusPerkawinan = \common\models\MasterStatusPerkawinan::find()->where(['Nama' => Helpers::collapseSpaces($row['STATUS PERKAWINAN'])])->one();
                        
                        if(is_null($statusPerkawinan)){
                            // INSERT MASTER PERKAWINAN BARU
                            $masterStatusPerkawinan = new \common\models\MasterStatusPerkawinan;
                            $masterStatusPerkawinan->Nama = Helpers::collapseSpaces($row['STATUS PERKAWINAN']);
                            $masterStatusPerkawinan->save();
                            $statusPerkawinan =  $masterStatusPerkawinan->getPrimaryKey();
                        }else{
                            $statusPerkawinan = $statusPerkawinan->id;
                        }

                        $members->MaritalStatus_id = $statusPerkawinan;

                         // CHECK MASTER PEKERJAAN
                        $pekerjaaan = \common\models\MasterPekerjaan::find()->where(['Pekerjaan' => Helpers::collapseSpaces($row['PEKERJAAN'])])->one();
            
                        if(is_null($pekerjaaan)){
                            // INSERT MASTER PEKERJAAN BARU
                            $masterPekerjaan = new \common\models\MasterPekerjaan;
                            $masterPekerjaan->Pekerjaan = Helpers::collapseSpaces($row['PEKERJAAN']);
                            $masterPekerjaan->save();
                            $pekerjaaan =  $masterPekerjaan->getPrimaryKey();
                        }else{
                            $pekerjaaan = $pekerjaaan->id;
                        }

                        $members->Job_id = $pekerjaaan;
                        //echo Helpers::collapseSpaces(Helpers::DateToMysqlFormat('-',$row['TANGGAL PENDAFTARAN']));
                        $members->RegisterDate = Helpers::collapseSpaces(Helpers::DateToMysqlFormat('-',$row['TANGGAL PENDAFTARAN']));
                        $members->EndDate = Helpers::collapseSpaces(Helpers::DateToMysqlFormat('-',$row['TANGGAL AKHIR BERLAKU']));
                        $members->MotherMaidenName =  Helpers::collapseSpaces($row['IBU KANDUNG']);
                        $members->Email =  Helpers::collapseSpaces($row['ALAMAT EMAIL']);

                        // CHECK MASTER JENIS PERMOHONAN
                        $jenisPermohonan = \common\models\JenisPermohonan::find()->where(['Name' => Helpers::collapseSpaces($row['JENIS PERMOHONAN'])])->one();
            
                        if(is_null($jenisPermohonan)){
                            // INSERT MASTER JENIS PERMOHONAN
                            $masterPermohonan = new \common\models\JenisPermohonan;
                            $masterPermohonan->Name = Helpers::collapseSpaces($row['JENIS PERMOHONAN']);
                            $masterPermohonan->save();
                            $jenisPermohonan =  $masterPermohonan->getPrimaryKey();
                        }else{
                            $jenisPermohonan = $jenisPermohonan->ID;
                        }
                        $members->JenisPermohonan_id = $jenisPermohonan;

                       // CHECK MASTER STATUS ANGGOTA
                        $statusAnggota = \common\models\StatusAnggota::find()->where(['Nama' => Helpers::collapseSpaces($row['STATUS ANGGOTA'])])->one();
            
                        if(is_null($statusAnggota)){
                            // INSERT MASTER STATUS ANGGOTA
                            $masterStatusAnggota = new \common\models\StatusAnggota;
                            $masterStatusAnggota->Nama = Helpers::collapseSpaces($row['STATUS ANGGOTA']);
                            $masterStatusAnggota->save();
                            $statusAnggota =  $masterStatusAnggota->getPrimaryKey();
                        }else{
                            $statusAnggota = $statusAnggota->id;
                        }
                        $members->StatusAnggota_id = $statusAnggota;

                        // CHECK MASTER JENIS ANGGOTA
                        $jenisAnggota = \common\models\JenisAnggota::find()->where(['jenisanggota' => Helpers::collapseSpaces($row['JENIS ANGGOTA'])])->one();
            
                        if(is_null($jenisAnggota)){
                            // INSERT MASTER JENIS ANGGOTA
                            $masterJenisAnggota = new \common\models\JenisAnggota;
                            $masterJenisAnggota->jenisanggota = Helpers::collapseSpaces($row['JENIS ANGGOTA']);
                            $masterJenisAnggota->save();
                            $jenisAnggota =  $masterJenisAnggota->getPrimaryKey();
                        }else{
                            $jenisAnggota = $jenisAnggota->id;
                        }
                        $members->JenisAnggota_id = $jenisAnggota;
                        
                       
                        $members->NamaDarurat = Helpers::collapseSpaces($row['NAMA (KEADAAN DARURAT)']);
                        $members->TelpDarurat = Helpers::collapseSpaces($row['NO TELP (KEADAAN DARURAT)']);
                        $members->AlamatDarurat = Helpers::collapseSpaces($row['ALAMAT (KEADAAN DARURAT)']);
                        $members->StatusHubunganDarurat = Helpers::collapseSpaces($row['STATUS HUBUNGAN (DARURAT)']);

                        $members->TahunAjaran = Helpers::collapseSpaces($row['TAHUN AJARAN']);

                        // CHECK MASTER KELAS
                        $kelas = \common\models\KelasSiswa::find()->where(['namakelassiswa' => Helpers::collapseSpaces($row['KELAS SISWA'])])->one();
            
                        if(is_null($kelas)){
                            // INSERT MASTER KELAS
                            $masterKelas = new \common\models\KelasSiswa;
                            $masterKelas->namakelassiswa = Helpers::collapseSpaces($row['KELAS SISWA']);
                            $masterKelas->save();
                            $kelas =  $masterKelas->getPrimaryKey();
                        }else{
                            $kelas = $kelas->id;
                        }
                        $members->Kelas_id = $kelas;

                         // CHECK MASTER AGAMA
                        $agama = \common\models\Agama::find()->where(['Name' => Helpers::collapseSpaces($row['AGAMA'])])->one();
            
                        if(is_null($agama)){
                            // INSERT MASTER AGAMA
                            $masterAgama = new \common\models\Agama;
                            $masterAgama->Name = Helpers::collapseSpaces($row['AGAMA']);
                            $masterAgama->save();
                            $agama =  $masterAgama->getPrimaryKey();
                        }else{
                            $agama = $agama->ID;
                        }
                        $members->Agama_id = $agama;

                         // CHECK MASTER FAKULTAS
                        $fakultas = \common\models\MasterFakultas::find()->where(['Nama' => Helpers::collapseSpaces($row['FAKULTAS'])])->one();
            
                        if(is_null($fakultas)){
                            // INSERT MASTER FAKULTAS
                            $masterFakultas = new \common\models\MasterFakultas;
                            $masterFakultas->Nama = Helpers::collapseSpaces($row['FAKULTAS']);
                            $masterFakultas->save();
                            $fakultas =  $masterFakultas->getPrimaryKey();
                        }else{
                            $fakultas = $fakultas->id;
                        }
                        $members->Fakultas_id = $fakultas;

                        // CHECK MASTER JURUSAN
                        $jurusan = \common\models\MasterJurusan::find()->where(['Nama' => Helpers::collapseSpaces($row['JURUSAN'])])->one();
            
                        if(is_null($jurusan)){
                            // INSERT MASTER JURUSAN
                            $masterJurusan = new \common\models\MasterJurusan;
                            $masterJurusan->Nama = Helpers::collapseSpaces($row['JURUSAN']);
                            $masterJurusan->id_fakultas = $fakultas;
                            $masterJurusan->save();
                            $jurusan =  $masterJurusan->getPrimaryKey();
                        }else{
                            $jurusan = $jurusan->id;
                        }
                        $members->Jurusan_id = $jurusan;


                        // Program Studi
                        // 
                        // CHECK MASTER Program Studi
                        $prodi = \common\models\MasterProgramStudi::find()->where(['Nama' => Helpers::collapseSpaces($row['PROGRAM STUDI'])])->one();
            
                        if(is_null($prodi)){
                            // INSERT MASTER Program Studi
                            $masterProdi = new \common\models\MasterProgramStudi;
                            $masterProdi->Nama = Helpers::collapseSpaces($row['PROGRAM STUDI']);
                            $masterProdi->id_jurusan = $jurusan;
                            $masterProdi->save();
                            $prodi =  $masterProdi->getPrimaryKey();
                        }else{
                            $prodi = $prodi->id;
                        }
                        $members->ProgramStudi_id = $prodi;

                        
                        // CHECK MASTER UNIT KERJA
                        $unitKerja = \common\models\Departments::find()->where(['Name' => Helpers::collapseSpaces($row['UNIT KERJA'])])->one();
            
                        if(is_null($jurusan)){
                            // INSERT MASTER UNIT KERJA
                            $masterUnitKerja = new \common\models\Departments;
                            $masterUnitKerja->Name = Helpers::collapseSpaces($row['UNIT KERJA']);
                            $masterUnitKerja->Code =  Helpers::collapseSpaces($row['UNIT KERJA']);
                            $masterUnitKerja->save();
                            $unitKerja =  $masterUnitKerja->getPrimaryKey();
                        }else{
                            $unitKerja = $unitKerja->ID;
                        }
                        $members->UnitKerja_id = $unitKerja;

                        $namafile = Helpers::collapseSpaces($row['PHOTO URL']);
                      
                        $filepath = Yii::getAlias('@uploaded_files/foto_anggota/'.$namafile);
                        $dirpath = Yii::getAlias('@uploaded_files/foto_anggota/');


                        if (file_exists($filepath)) {
                            $newFileName = DirectoryHelpers::getNewFileName($dirpath ,$filepath,$namafile);
                        }else{
                            $newFileName = $namafile;
                        }
                        
                        $members->PhotoUrl = $newFileName;

                        if($members->save()){
                            
                            $memID = $members->getPrimaryKey();
                            //delete dlu di memberloanauthorizeLocaitons where NoAnggota
                            $rowDeleted = \common\models\Memberloanauthorizelocation::deleteAll('Member_id = :memberId', [':memberId' => $members->ID]);
                            
                             //delete dlu di memberloanauthorizeCategory where NoAnggota
                             $rowDeletedCategory = \common\models\Memberloanauthorizecategory::deleteAll('Member_id = :memberId', [':memberId' => $members->ID]);


                             $memloan = Yii::$app->db->createCommand("
                                    select Location_Library_id 
                                    from location_library_default l
                                    left join jenis_anggota j on l.JenisAnggota_id = j.id 
                                    where jenisanggota LIKE '%".Helpers::collapseSpaces($row['JENIS ANGGOTA'])."%' order by Location_Library_id ;
                                ")->queryAll();
                             $memloancat = Yii::$app->db->createCommand("
                                    select CollectionCategory_id 
                                    from collectioncategorysdefault l
                                    left join jenis_anggota j on l.JenisAnggota_id = j.id 
                                    where jenisanggota LIKE '%".Helpers::collapseSpaces($row['JENIS ANGGOTA'])."%' order by CollectionCategory_id ;

                                ")->queryAll();


                            // Jika Lokasi tidak null maka insert ke memberloanauthorizeLocaitons
                            if ($memloan != "") {
                                foreach ($memloan as $key => $value) {
                                    $modelMemberLoanAuth = new Memberloanauthorizelocation();
                                    $modelMemberLoanAuth->Member_id = $memID;
                                    $modelMemberLoanAuth->LocationLoan_id = $value['Location_Library_id'];
                                    $modelMemberLoanAuth->save();
                                }
                            }
                            // Jika Jenis Koleksi tidak null maka insert ke memberloanauthorizeCategory
                            if ($memloancat != "") {
                                foreach ($memloancat as $key => $value) {
                                    $modelMemberLoanCat = new Memberloanauthorizecategory();
                                    $modelMemberLoanCat->Member_id = $memID;
                                    $modelMemberLoanCat->CategoryLoan_id = $value['CollectionCategory_id'];
                                    $modelMemberLoanCat->save();
                                }
                            }

                        }else{

                            //print_r($members->getErrors());
                            if($members->hasErrors()){
                              //$members->addErrors(\yii\helpers\Html::errorSummary($members));
                              echo \yii\helpers\Html::errorSummary($members);
                            }
                            echo "Import Data Anggota Gagal.";
                            return $members->getErrors();
                        }
                        

                    }
                    return $members->getErrors();

                    //echo "<pre>"; print_r($members->getErrors()); die;

                }catch(ErrorException $e){
                    Yii::warning($e);
                    echo $e;
                    return $members->getErrors();;
                }
    } 
   
    /**
    * Process deletion of file imported
    *
    * @return boolean the status of deletion
    */
    public function deleteFile() {
        $path = Yii::getAlias('@uploaded_files') . '/temporary/imported_data_sheet/imported/';
        $file = $path. $this->file->baseName . '.' . $this->file->extension;
        chmod($file, 0666);
        // check if file exists on server
        if (empty($file) || !file_exists($file)) {
            return false;
        }
 
        // check if uploaded file can be deleted on server
        if (!unlink($file)) {
            return false;
        }
 
        return true;
    }
}