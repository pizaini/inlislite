<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

use common\components\MemberHelpers;
use common\components\Helpers;

class ImportKependudukanForm extends Model
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

                    foreach ($data as $row) {
                        $kepend = new \common\models\MasterKependudukan;
                        $kepend->nomorkk = Helpers::collapseSpaces($row['nomorkk']);
                        $kepend->nik = Helpers::collapseSpaces($row['nik']);
                        $kepend->namalengkap = Helpers::collapseSpaces($row['namalengkap']);
                        $kepend->nama_ibu = Helpers::collapseSpaces($row['nama_ibu']);
                        //$kepend->DateOfBirth = Helpers::collapseSpaces(Helpers::DateToMysqlFormat('-',$row['TANGGAL LAHIR']));
                        $kepend->al1 = Helpers::collapseSpaces($row['al1']);
                        $kepend->rt = Helpers::collapseSpaces($row['rt']);
                        $kepend->rw = Helpers::collapseSpaces($row['rw']);
                        $kepend->kodekel = Helpers::collapseSpaces($row['kodekel']);
                        $kepend->kodekec = Helpers::collapseSpaces($row['kodekec']);
                        $kepend->nama_kec = Helpers::collapseSpaces($row['nama_kec']);
                        $kepend->nama_kel = Helpers::collapseSpaces($row['nama_kel']);
                        $kepend->nama_kab = Helpers::collapseSpaces($row['nama_kab']);
                        $kepend->nama_prov = Helpers::collapseSpaces($row['nama_prov']);
                        $kepend->alamat = Helpers::collapseSpaces($row['alamat']);
                        $kepend->lhrtempat = Helpers::collapseSpaces($row['lhrtempat']);
                        $kepend->lhrtanggal = Helpers::collapseSpaces($row['lhrtanggal']);
                        $kepend->ttl = Helpers::collapseSpaces($row['ttl']);
                        $kepend->umur = Helpers::collapseSpaces($row['umur']);
                        $kepend->jk = intval(Helpers::collapseSpaces($row['jk']));
                        $kepend->jenis = Helpers::collapseSpaces($row['jenis']);
                        $kepend->status = intval(Helpers::collapseSpaces($row['status']));
                        $kepend->sts = Helpers::collapseSpaces($row['sts']);
                        $kepend->hub = Helpers::collapseSpaces($row['hub']);
                        $kepend->agama = intval(Helpers::collapseSpaces($row['agama']));
                        $kepend->agm = Helpers::collapseSpaces($row['agm']);
                        $kepend->pendidikan = Helpers::collapseSpaces($row['pendidikan']);
                        $kepend->pekerjaan = Helpers::collapseSpaces($row['pekerjaan']);
                        $kepend->klain_fisik = Helpers::collapseSpaces($row['klain_fisik']);
                        $kepend->aktalhr = Helpers::collapseSpaces($row['aktalhr']);
                        $kepend->aktakawin = Helpers::collapseSpaces($row['aktakawin']);
                        $kepend->aktacerai = Helpers::collapseSpaces($row['aktacerai']);
                        $kepend->nocacat = Helpers::collapseSpaces($row['nocacat']);
        
                        $kepend->save(false);                
                        // if($kepend->save(false)){
                        //     echo "Import Data Kependudukan Selesai.";
                        //     return true;
                        // }else{
                        //     //print_r($members->getErrors());
                        //     if($kepend->hasErrors()){
                        //       echo \yii\helpers\Html::errorSummary($kepend);
                        //     }
                        //     echo "Import Data Kependudukan Gagal.";
                        //     return false;
                        // }
                                          

                        // $kepend->IdentityType_id = $identityTypeId;
                        // $kepend->IdentityNo = Helpers::collapseSpaces($row['NO. IDENTITAS']);

                        
                        // $kepend->NoHp = Helpers::collapseSpaces($row['NO. HP']);
                        // $kepend->NamaDarurat = Helpers::collapseSpaces($row['NAMA (KEADAAN DARURAT)']);
                        // $kepend->TelpDarurat = Helpers::collapseSpaces($row['NO TELP (KEADAAN DARURAT)']);
                        // $kepend->AlamatDarurat = Helpers::collapseSpaces($row['ALAMAT (KEADAAN DARURAT)']);
                        // $kepend->StatusHubunganDarurat = Helpers::collapseSpaces($row['STATUS HUBUNGAN (DARURAT)']);
                        // $kepend->Province = Helpers::collapseSpaces($row['PROPINSI']);
                        // $kepend->City = Helpers::collapseSpaces($row['KABUPATEN / KOTA']);
                        // $kepend->ProvinceNow = Helpers::collapseSpaces($row['PROPINSI SEKARANG']);
                        // $kepend->CityNow = Helpers::collapseSpaces($row['KABUPATEN / KOTA SEKARANG']);
                        // $kepend->TahunAjaran = Helpers::collapseSpaces($row['TAHUN AJARAN']);


                    }

                }catch(ErrorException $e){
                    Yii::warning($e);
                    echo $e;
                    return false;
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