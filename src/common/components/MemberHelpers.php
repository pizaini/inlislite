<?php

/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2015
 * @package helpers
 * @version 1.0.0
 * @author Henry <alvin_vna@yahoo.com>
 */

namespace common\components;

use Yii;
// MODEL
use common\models\Members;
use common\models\JenisKelamin;
use common\models\MasterJenisIdentitas;

class MemberHelpers
{


    /**
     * Function for get new member number format
     * @param  string $maxMemberNo value of GetMaxMemberNo.
     * @param  int $template    from settingparameter Tipe Penomoran Anggota.
     * @param  int $jk          Jenis Kelamin.
     * @return string $no the new number member.
     */
    public static function getNewMemberNo($maxMemberNo,$template,$jk = null) {
        $location = $_SESSION['location'];
        if($template == 1){
            //Format YYMMDD99999
            if (isset($maxMemberNo)) {
                $tambah = ($maxMemberNo + 1);
                $rest = substr($tambah, -5);
                $tanggaldepan = date("ymd");
                $potongtanggal = substr($tanggaldepan, -6);
                $batas = 10000;
                $jumlah = ($batas + $rest);
                $jumlahtotal = $potongtanggal . $jumlah;
                $no = substr_replace($jumlahtotal, '0', 6, 1);

            } else {
                $rest = 1;
                $tanggaldepan = date("ymd");
                $potongtanggal = substr($tanggaldepan, -6);
                $batas = 10000;
                $jumlah = ($batas + $rest);
                $jumlahtotal = $potongtanggal . $jumlah;
                $no = substr_replace($jumlahtotal, '0', 6, 1);

            }
        }elseif($template == 2){
         // Format YYYYMM999
            // print_r($maxMemberNo);die;
            if (isset($maxMemberNo)) {
                $tambah = ($maxMemberNo + 1);
                $rest = substr($tambah, -4);
                $tanggaldepan = date("Ym");
                $potongtanggal = substr($tanggaldepan, -6);
                //$batas = 100;
                $batas = 0;
                $jumlah = ($batas + $rest);
                $jumlahtotal = $potongtanggal . $rest;
                //$no = substr_replace($jumlahtotal, '', 6, 1);
                $no = substr_replace($jumlahtotal, '', 6, 1);
            } else {
                $rest = 1;
                $tanggaldepan = date("Ym");
                $potongtanggal = substr($tanggaldepan, -6);
                $batas = 100;
                $jumlah = ($batas + $rest);
                $jumlahtotal = $potongtanggal . $jumlah;
                $no = substr_replace($jumlahtotal, '0', 6, 1);
            }
            
        }elseif($template == 3){
            // Format 99999L2015
            // 99999L2015


            if($jk == 1){$jk= "L";}else{$jk="P";}

            if (isset($maxMemberNo)) {
                $maxMemberNo = substr($maxMemberNo,1,5);
                $tambah = ($maxMemberNo + 1);
                $rest = substr($tambah, -5);
                $tanggaldepan = date("Y");
                $potongtanggal = substr($tanggaldepan, -6);

                $batas = 10000;
                $jumlah = ($batas + $rest);
                $jumlahtotal = $jumlah;
                $replace = substr_replace($jumlahtotal, '0', 0, 1);
                $no = $replace.$jk.$potongtanggal;

            } else {
                $rest = 1;
                /*$tanggaldepan = date("Y");
                $potongtanggal = substr($tanggaldepan, -6);
                $batas = 10000;
                $jumlah = ($batas + $rest);
                $jumlahtotal = $potongtanggal . $jumlah;
                $no = substr_replace($jumlahtotal, '0', 6, 1);*/
                $tanggaldepan = date("Y");
                $potongtanggal = substr($tanggaldepan, -6);

                $batas = 10000;
                $jumlah = ($batas + $rest);
                $jumlahtotal = $jumlah;
                $replace = substr_replace($jumlahtotal, '0', 0, 1);
                $no = $replace.$jk.$potongtanggal;

            }
        }
        $loc = (strlen($location) == 1) ? '0'.$location : $location;
        return $loc.$no;
    }


/**
 * Function for get last member no.
 * @param  int $template from settingparameter Tipe Penomoran Anggota.
 * @return string $result last member no.
 */
    public static function getMaxMemberNo($template) {

        $location = $_SESSION['location'];
        $loc = (strlen($location) == 1) ? '0'.$location : $location;
        if($template == 1){
            $tanggal = date("ymd");
            //Format YYMMDD99999
            //$potongtanggal = substr($tanggal, -6);
            $row = Members::find()->select(['max(MemberNo) as MemberNo'])
                    ->andFilterWhere(['like', 'substr(MemberNo,1,8)', $loc.$tanggal])
                    ->one();
             $result = $row['MemberNo'];
        }elseif($template == 2){
            $tanggal = date("Ym");
            // Format YYYYMM999
            $row = Members::find()->select(['max(MemberNo) as MemberNo'])
                    ->andFilterWhere(['like', 'substr(MemberNo,1,8)', $loc.$tanggal])
                    ->one();
             $result = $row['MemberNo'];

        }elseif($template == 3){
             $tanggal = date("Y");
            // Format 99999L2015
             $row = Members::find()->select(['max(MemberNo) as MemberNo'])
                    ->andFilterWhere(['like', 'substr(MemberNo,8,4)', $tanggal])
                    ->one();
             $result = $row['MemberNo'];

        }
        return $result;
    }

    /**
     * Fungsi untuk mengetahui jenis identitas mana yang digunakan sebagai NIK.
     * @return int $result id
     */
    public static function getJenisIdentitasNik() {

        $row = MasterJenisIdentitas::find()->select(['id'])
                    ->where(['IsNIK' => 1])
                    ->one();
             $result = $row['id'];

        return $result;
    }

    /**
     * Fungsi untuk mendapatkan id pendidikan berdasarkan nama.
     * @param  string $name nama pendidikan
     * @return int id
     */
    public static function getIdPendidikanByName($name) {

        $row = \common\models\MasterPendidikan::find()->select(['id'])
                    ->where(['LIKE', 'Nama', $name])
                    ->one();
             $result = $row['id'];

        return $result;
    }

    /**
     * Fungsi untuk mendapatkan id pekerjaan berdasarkan nama.
     * @param  string $name nama pekerjaan
     * @return int id
     */
    public static function getIdPekerjaanByName($name) {

        $row = \common\models\MasterPekerjaan::find()->select(['id'])
                    ->where(['LIKE', 'Pekerjaan', $name])
                    ->one();
             $result = $row['id'];

        return $result;
    }

    /**
     * Fungsi untuk mendapatkan id status perkawinan berdasarkan nama.
     * @param  string $name nama status perkawinan
     * @return int id
     */
    public static function getIdStatusPerkawinanByName($name) {

        $row = \common\models\MasterStatusPerkawinan::find()->select(['id'])
                    ->where(['LIKE', 'Nama', $name])
                    ->one();
             $result = $row['id'];

        return $result;
    }

    /**
     * Fungsi untuk mendapatkan id agama berdasarkan nama.
     * @param  string $name nama agama
     * @return int id
     */
    public static function getIdAgamaByName($name) {

        $row = \common\models\Agama::find()->select(['ID'])
                    ->where(['LIKE', 'Name', $name])
                    ->one();
             $result = $row['ID'];

        return $result;
    }


    
    public static function loadMasaBerlaku()
    {

        $masaBerlakuID = Yii::$app->config->get('MasaBerlakuAnggota'); 
        if (!empty($masaBerlakuID))
        {
               $model = \common\models\MasaBerlakuAnggota::findOne($masaBerlakuID);
                if ($model === null)
                    throw new CHttpException(404, 'The requested page does not exist.');
               
                
        }
        return $model;
    }


    /**
     * Fungsi untuk mengambil real path foto anggota.
     * @return [type] [description]
     */
    public static function getRealPathFotoAnggota(){
        return Yii::getAlias('@uploaded_files') . '/' .Yii::$app->params['pathFotoAnggota'].'/';
    }

    /**
     * Fungsi untuk mengambil real path foto anggota.
     * @return [type] [description]
     */
    public static function getRealPathFotoAnggotaThumb(){
        return Yii::getAlias('@uploaded_files') . '/' .Yii::$app->params['pathFotoAnggota']. '/temp';
    }

    /**
     * [getPathFotoAnggota description]
     * @return [type] [description]
     */
    public static function getPathFotoAnggota(){
        return '../../../uploaded_files/' .Yii::$app->params['pathFotoAnggota'] . '/temp/';
    }

     /**
     * [getPathFotoAnggota description]
     * @return [type] [description]
     */
    public static function getPathFotoAnggotaOriginal(){
        return '../../../uploaded_files/' .Yii::$app->params['pathFotoAnggota'] .'/';
    }

    public static function getMemberByNoAnggota($noAnggota){
        $row = \common\models\Members::find()
                    ->where(['LIKE', 'MemberNo', $noAnggota])
                    ->one();
        return $row;
    }
    
    /**
     * Mengambil data custom field keanggotan form.
     * @param type (1=Member,2=MemberOnline,3=Daftar BO,4=Form Entri Peminjaman)
     * @param type $type
     * @return boolean
     */
    public static function customMemberForm($memberFieldId,$type = '1'){
        
        if($type == '1'){
            // Ambil Data MembersForm Berdasarkan Jenis Perpustakaan
            $membersForm = \common\models\base\MembersForm::find()
                        ->where(['Jenis_Perpustakaan_id' => Yii::$app->config->get('JenisPerpustakaan')])
                        ->andWhere(['Member_Field_id' => $memberFieldId])
                        ->asArray()->all();
            
        }else if($type == '2'){
             // Ambil Data MembersOnline Berdasarkan Jenis Perpustakaan
            $membersForm = \common\models\base\MembersOnlineForm::find()
                        ->where(['Jenis_Perpustakaan_id' => Yii::$app->config->get('JenisPerpustakaan')])
                        ->andWhere(['Member_Field_id' => $memberFieldId])
                        ->asArray()->all();
        }else if($type == '3'){
             // Ambil Data MembersForm Berdasarkan Jenis Perpustakaan
            $membersForm = \common\models\base\MembersFormList::find()
                        ->where(['Jenis_Perpustakaan_id' => Yii::$app->config->get('JenisPerpustakaan')])
                        ->andWhere(['Member_Field_id' => $memberFieldId])
                        ->asArray()->all();
        }else if($type == '4'){
             // Ambil Data MembersForm Berdasarkan Jenis Perpustakaan
            $membersForm = \common\models\base\MembersLoanForm::find()
                        ->where(['Jenis_Perpustakaan_id' => Yii::$app->config->get('JenisPerpustakaan')])
                        ->andWhere(['Member_Field_id' => $memberFieldId])
                        ->asArray()->all();
        }
       
        
        if($membersForm == null){
           return false;
        }
    
        
        return true;
    }
    /**
     * Check apakah Nomor Anggota Menggunakan Template / Manual.
     * @param type $noAnggota
     * @return boolean
     */
    public static function isCustomNumber($noAnggota){


        $isNikValid = self::cekValidNIK($noAnggota);

        switch (strlen($noAnggota)) {


            //tipe Nomor Anggota 1
            //YYMMDD99999
            case 11:

                    if (!is_numeric($noAnggota)) return true;
                    if (substr($noAnggota, 0,2) > date('y') ) return true;
                    if (substr($noAnggota, 2,2) > 12 ) return true;
                    if (substr($noAnggota, 4,2) > 31 ) return true;

                    return false;

                break;

            //tipe Nomor Anggota 2
            //YYYYMM999
            case 9:
                    if (!is_numeric($noAnggota)) return true;
                    if (substr($noAnggota, 0,4) > date('Y') ) return true;
                    if (substr($noAnggota, 4,2) > 12 ) return true;

                return false;

                break;

            //tipe Nomor Anggota 3
            //99999L2015
            case 10:
                    if (is_numeric($noAnggota)) return true;
                    if (substr($noAnggota, 6,4) > date('Y') ) return true;
  
                return false;

                break;

            //tipe Nomor Anggota 4
            //NIK
            case 16:
                    if (!$isNikValid) return true;

                return false;
                
                break;

            default:
                return true;
                break;
        }
    }
    

    public static function cekValidNIK($NIK){

        $kodeProv=substr($NIK, 0,2);
        $kodeKab=substr($NIK, 2,2);
        $kodeKec=substr($NIK, 4,2);
        $kodeTgl=substr($NIK, 6,2);
        $kodeBulan=substr($NIK, 8,2);
        $kodeTahun=substr($NIK, 10,2);

        $provinsi= array(
            11 => 'Aceh', 
            12 => 'Sumatera Utara', 
            13 => 'Sumatera Barat', 
            14 => 'Riau', 
            15 => 'Jambi', 
            16 => 'Sumatera Selatan', 
            17 => 'Bengkulu', 
            18 => 'Lampung', 
            19 => 'Kep. Bangka Belitung', 
            21 => 'Kep. Riau', 
            31 => 'DKI Jakarta', 
            32 => 'Jawa Barat', 
            33 => 'Jawa Tengah', 
            34 => 'Yogyakarta', 
            35 => 'Jawa Timur', 
            36 => 'Banten', 
            51 => 'Bali', 
            52 => 'Nusa Tenggara Barat', 
            53 => 'Nusa Tenggara Timur', 
            61 => 'Kalimantan Barat', 
            62 => 'Kalimantan Tengah', 
            63 => 'Kalimantan Selatan', 
            64 => 'Kalimantan Timur', 
            71 => 'Sulawesi Utara', 
            72 => 'Sulawesi Tengah', 
            73 => 'Sulawesi Selatan', 
            74 => 'Sulawesi Tenggara', 
            75 => 'Gorontalo', 
            76 => 'Sulawesi Barat', 
            81 => 'Maluku', 
            82 => 'Maluku Utara', 
            91 => 'Papua Barat', 
            94 => 'Papua' 
        );

        if (strlen($NIK) != 16) return false;

        //cek kode provinsi
        if (!array_key_exists($kodeProv, $provinsi)) return false;

        /*//cek kode kota/kab
        if (substr($NIK, 2,4)) {
            # code...
        }

        //kode kecamatan
        if (substr($NIK, 4,6)) {
            # code...
        }*/

        //kode tanggal lahir
        //tgl max 31
        //untuk wanita +40
        if ($kodeTgl>71) return false;

        //kode bulan lahir
        if ($kodeBulan>12) return false;

        //kode tahun lahir
        //if ($kodeTahun > date('Y')) return false;

        return true; 

    }
    
}