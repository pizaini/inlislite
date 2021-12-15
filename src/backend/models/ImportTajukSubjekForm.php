<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

use common\components\Helpers;
use common\components\DirectoryHelpers;

class ImportTajukSubjekForm extends Model
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
        $path = Yii::getAlias('@uploaded_files') . '/temporary/marc/imported/';
        // echo'<pre>';print_r($path);
        // echo'<pre>';print_r($this->file->baseName);
        // echo'<pre>';print_r($this->file->extension);
        // die;
        if ($this->validate()) {
            $this->file->saveAs($path. $this->file->baseName . '.' . $this->file->extension);
            return true;
        } else {
            return false;
        }

    }

    public function import(){
        $filePath = $this->file->baseName . '.' . $this->file->extension;
        $path = dirname(dirname(__DIR__));
        $start_php = dirname(dirname($_SERVER['MIBDIRS'])).'/php.exe';
        // print_r($start_php);die;
        // $err = exec($path.'/yii import-tajuk-subjek/index "'.$filePath.'"');
        // "d:/path/to/php.exe d:/wamp/www/diplomski/program/defender/tester.php"
        $err = exec($start_php.' '.$path. '/yii import-tajuk-subjek/index "'.$filePath.'"');
        // print_r($err);die;
        // $err = Yii::$app->runAction('hello/index', [
        //     $filePath
        // ]);;
        // try{
        //     $path = Yii::getAlias('@uploaded_files') . '/temporary/marc/imported/'.$this->file->baseName . '.' . $this->file->extension;
            
            
            
        //     $context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
        //     $xml = @file_get_contents($path, false, $context);

        //     if ($xml == TRUE) {
        //         // $xml = simplexml_load_string($xml);
        //         // $xml = $result->GetWeatherResult;
        //         $xml = preg_replace('#&(?=[a-z_0-9]+=)#', '&amp;', $xml);
        //         $xml = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $xml);  
        //         // $xml = preg_replace('#&(?=[a-z_0-9]+=)#', '&amp;', $xml);
        //         // $xml = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xml);
        //         $out = self::xml2array($xml);
        //         // echo'<pre>';print_r($out);die;
        //         if($out){
        //             foreach ($out as $key => $value) {
        //                 // $getXml = self::xml2array($value);
        //                 $getXml = simplexml_load_string($value);
        //                 // echo'<pre>';print_r($getXml);die;
        //                 /* format sebelumnya */
        //                 foreach ($getXml->Record as $keyXml => $cek) {
        //                 /* end format sebelumnya */


        //                 /* format baru */
        //                 // foreach ($getXml->RecordAuthorityData as $keyXml => $cek) {
        //                 /* end format baru */
        //                     $macrLOC = self::xml2array($cek->MARCCode);
        //                     // echo'<pre>';print_r($macrLOC[0]);die;

        //                     $data = explode('&30;', $cek->MARCCode);
        //                     $authID = trim(str_replace('&31;', '', $data[4]));
        //                     // print_r($authID);die;
        //                     $cekHeader = Yii::$app->db->createCommand('SELECT Auth_ID FROM auth_header WHERE Auth_ID = "'.$authID.'"')->queryAll();
        //                     if(!$cekHeader){
        //                         foreach($data as $dk => $dv){
        //                             $authHeader = new \common\models\AuthHeader;
        //                             $authHeader->Worksheet_id = 1;
        //                             $authHeader->Auth_ID = $authID;
        //                             $authHeader->MARC_LOC = $macrLOC[0];
        //                             // $authHeader->save();
        //                             if($authHeader->save()){
        //                                 $controlField = $cek->ControlField;
        //                                 $variabelField = $cek->VariableField;
        //                                 foreach ((array)$controlField as $keyField => $valueField) {
        //                                     foreach ($valueField as $kField => $vField) {
        //                                         // echo'<pre>';print_r(self::xml2array($vField->Tag)[0]);die;
        //                                         $authData = new \common\models\AuthData;
        //                                         $array = array('|', ' ');
        //                                         $authData->Auth_Header_ID = $authHeader->ID;
        //                                         $authData->Tag = self::xml2array($vField->Tag)[0];
        //                                         $authData->Indicator1 = Null;
        //                                         $authData->Indicator2 = Null;
        //                                         $authData->Value = (self::xml2array($vField->Tag)[0] == '008') ? str_replace($array, '*', self::xml2array($vField->Value)[0]) : self::xml2array($vField->Value)[0];
        //                                         $authData->save();
        //                                         // echo'<pre>';print_r($authData->getErrors());
        //                                     }
        //                                 }

        //                                 foreach ((array)$variabelField as $keyVariabel => $valueVariabel) {
        //                                     foreach ($valueVariabel as $kVariabel => $vVariabel) {
        //                                         // echo'<pre>';print_r(self::xml2array($vField->Tag)[0]);die;
        //                                         $authData = new \common\models\AuthData;
        //                                         // $array = array('|', ' ');
        //                                         $authData->Auth_Header_ID = $authHeader->ID;
        //                                         $authData->Tag = self::xml2array($vVariabel->Tag)[0];
        //                                         $authData->Indicator1 = self::xml2array($vVariabel->Indicator1)[0];
        //                                         $authData->Indicator2 = self::xml2array($vVariabel->Indicator2)[0];
        //                                         $authData->Value = self::xml2array($vVariabel->Value)[0];
        //                                         $authData->save();
        //                                         // echo'<pre>';print_r($authData->getErrors());
        //                                     }
        //                                 }
        //                             }
        //                         }
        //                     }
        //                     // $controlField = $cek->ControlField;
        //                     // foreach ((array)$controlField as $keyField => $valueField) {
        //                     //  // echo'<pre>';print_r($valueField);die;
        //                     // }
                            
        //                   }
        //             }

        //             /* Format sebelumnya */
        //             // foreach($out as $value){
        //             //     $cek = simplexml_load_string($value);
        //             //     $cek = self::xml2array($cek);
        //             //     foreach($cek as $value){
        //             //         foreach($value as $k => $v){
        //             //             // echo'<pre>';print_r($v);
        //             //             $data = explode('&30;', $v);
        //             //             $authID = trim(str_replace('&31;', '$', $data[4]));
        //             //             $cekHeader = Yii::$app->db->createCommand('SELECT Auth_ID FROM auth_header WHERE Auth_ID = "'.$authID.'"')->queryAll();
        //             //             // echo'<pre>';print_r($data);die;
        //             //             if(!$cekHeader){
        //             //                 foreach($data as $dk => $dv){
        //             //                     $authHeader = new \common\models\AuthHeader;
        //             //                     $authHeader->Worksheet_id = 1;
        //             //                     $authHeader->Auth_ID = $authID;
        //             //                     $authHeader->MARC_LOC = $v;
        //             //                     // $authHeader->save();
        //             //                     // echo'<pre>';print_r($authHeader->getErrors());

        //             //                     if($authHeader->save()){
        //             //                         // echo'<pre>';print_r($data);die;
        //             //                         foreach($data as $cekKey => $cek){
        //             //                             $valueData = trim(str_replace('&31;', '$', $cek));
        //             //                             $authData = new \common\models\AuthData;
        //             //                             // print_r($dv);
        //             //                             $authData->Auth_Header_ID = $authHeader->ID;
        //             //                             if($cekKey == '0'){
                                                
        //             //                             }
        //             //                             else if($cekKey == '1'){
        //             //                                 $authData->Tag = '100';
        //             //                                 $authData->Indicator1 = Null;
        //             //                                 $authData->Indicator2 = Null;
        //             //                                 $authData->Value = $valueData;
        //             //                             }
        //             //                             else if($cekKey == '2'){
        //             //                                 $authData->Tag = '005';
        //             //                                 $authData->Indicator1 = Null;
        //             //                                 $authData->Indicator2 = Null;
        //             //                                 $authData->Value = $valueData;
        //             //                             }
        //             //                             else if($cekKey == '3'){
        //             //                                 $array = array('|', ' ');
        //             //                                 $authData->Tag = '008';
        //             //                                 $authData->Indicator1 = Null;
        //             //                                 $authData->Indicator2 = Null;
        //             //                                 $authData->Value = str_replace($array, '*', $valueData);
        //             //                             }
        //             //                             else if($cekKey == '4'){
        //             //                                 $authData->Tag = '035';
        //             //                                 $authData->Indicator1 = '#';
        //             //                                 $authData->Indicator2 = '#';
        //             //                                 $authData->Value = $valueData;
        //             //                             }
        //             //                             else if($cekKey == '5'){
        //             //                                 $authData->Tag = '039';
        //             //                                 $authData->Indicator1 = '#';
        //             //                                 $authData->Indicator2 = '#';
        //             //                                 $authData->Value = $valueData;
        //             //                             }
        //             //                             else{
        //             //                                 $find360   = '$i';
        //             //                                 $pos360 = strpos($valueData, $find360);
        //             //                                 if($pos360 !== false){
        //             //                                     $authData->Tag = '360';
        //             //                                     $authData->Indicator1 = '#';
        //             //                                     $authData->Indicator2 = '#';
        //             //                                     $authData->Value = $valueData;
        //             //                                 }
        //             //                                 $findme   = 'Dapat ditambahkan subdivisi geografis';
        //             //                                 $pos = strpos($valueData, $findme);
        //             //                                 if($pos !== false){
        //             //                                     $authData->Tag = '667';
        //             //                                     $authData->Indicator1 = '#';
        //             //                                     $authData->Indicator2 = '#';
        //             //                                     $authData->Value = $valueData;
        //             //                                 }
        //             //                                 $findDollarw   = '$w';
        //             //                                 $posDollarw = strpos($valueData, $findDollarw);
        //             //                                 if($posDollarw !== false){
        //             //                                     $authData->Tag = '550';
        //             //                                     $authData->Indicator1 = '#';
        //             //                                     $authData->Indicator2 = '#';
        //             //                                     $authData->Value = $valueData;
        //             //                                 }
        //             //                                 $find990   = '$a 00';
        //             //                                 $pos990 = strpos($valueData, $find990);
        //             //                                 if($pos990 !== false){
        //             //                                     $authData->Tag = '990';
        //             //                                     $authData->Indicator1 = '#';
        //             //                                     $authData->Indicator2 = '#';
        //             //                                     $authData->Value = $valueData;
        //             //                                 }
                                                    
        //             //                             }
                                                
        //             //                             $authData->save();
        //             //                             // echo'<pre>';print_r($authData->getErrors());
        //             //                             // echo'<pre>';print_r($cek);die;
        //             //                         }
        //             //                     }
        //             //                     // die;
        //             //                     // return true;
        //             //                 }
                                        
        //             //                 // return true;
        //             //             }else{
        //             //                 return false;
        //             //             }
                                
        //             //         }
        //             //     }
        //             //     // print_r($cek);die;
                        
                        
        //             // }
        //             // echo 'oke';
        //             return $out;
        //         }else{
        //             return false;
        //         }



        //         // if ($out['status']==='success'){
        //           // return true;
        //         // } else
        //         // {
        //           // return false;
        //         // }

        //     }
        // }
        // catch(ErrorException $e){
        //     Yii::warning($e);
        //     echo $e;
        //     // return $members->getErrors();;
        // }
    }

    function xml2array ( $xmlObject, $out = array () )
    {
        foreach ( (array) $xmlObject as $index => $node )
          $out[$index] = ( is_object ( $node ) ) ? self::xml2array ( $node ) : $node;
        return $out;
    }

     
   
    /**
    * Process deletion of file imported
    *
    * @return boolean the status of deletion
    */
    public function deleteFile() {
        $path = Yii::getAlias('@uploaded_files') . '/temporary/marc/imported/';
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