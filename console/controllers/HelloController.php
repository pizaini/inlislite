<?php
namespace console\controllers;

use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\FileHelper;
use Yii;



class HelloController extends Controller{

	public function actionIndex($filePath)
    {
		
        try{
			// Yii::$app->getSession();
			session_start();
            // $path = $filePath;
			$path = Yii::getAlias('@uploaded_files') . '/temporary/marc/imported/'.$filePath;
            
            
            
            $context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
            $xml = @file_get_contents($path, false, $context);

            if ($xml == TRUE) {
                // $xml = simplexml_load_string($xml);
                // $xml = $result->GetWeatherResult;
                $xml = preg_replace('#&(?=[a-z_0-9]+=)#', '&amp;', $xml);
                $xml = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $xml);  
                // $xml = preg_replace('#&(?=[a-z_0-9]+=)#', '&amp;', $xml);
                // $xml = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xml);
                $out = self::xml2array($xml);
                // echo'<pre>';print_r($out);die;
                if($out){
                    foreach ($out as $key => $value) {
                        // $getXml = self::xml2array($value);
                        $getXml = simplexml_load_string($value);
                        // echo'<pre>';print_r($getXml);die;
                        // foreach ($getXml->Record as $keyXml => $cek) {
                        foreach ($getXml->RecordAuthorityData as $keyXml => $cek) {
                            $macrLOC = self::xml2array($cek->MARCCode);
                            // echo'<pre>';print_r($macrLOC[0]);die;

                            $data = explode('&30;', $cek->MARCCode);
                            $authID = trim(str_replace('&31;', '', $data[4]));
                            // print_r($authID);die;
                            $cekHeader = Yii::$app->db->createCommand('SELECT Auth_ID FROM auth_header WHERE Auth_ID = "'.$authID.'"')->queryAll();
                            if(!$cekHeader){
                                foreach($data as $dk => $dv){
                                    $authHeader = new \common\models\AuthHeaderImport;
                                    $authHeader->Worksheet_id = 1;
                                    $authHeader->Auth_ID = $authID;
                                    $authHeader->MARC_LOC = $macrLOC[0];
                                    $authHeader->CreateBy = 33;
                                    $authHeader->CreateDate = '2017-11-09 16:01:46';
                                    $authHeader->CreateTerminal = '::1';
									$authHeader->UpdateBy = 33;
                                    $authHeader->UpdateDate = '2017-11-09 16:01:46';
                                    $authHeader->UpdateTerminal = '::1';
                                    // $authHeader->save();
                                    if($authHeader->save()){
                                        $controlField = $cek->ControlField;
                                        $variabelField = $cek->VariableField;
                                        foreach ((array)$controlField as $keyField => $valueField) {
                                            foreach ($valueField as $kField => $vField) {
                                                // echo'<pre>';print_r(self::xml2array($vField->Tag)[0]);die;
                                                $authData = new \common\models\AuthDataImport;
                                                $array = array('|', ' ');
                                                $authData->Auth_Header_ID = $authHeader->ID;
                                                $authData->Tag = self::xml2array($vField->Tag)[0];
                                                $authData->Indicator1 = Null;
                                                $authData->Indicator2 = Null;
                                                $authData->Value = (self::xml2array($vField->Tag)[0] == '008') ? str_replace($array, '*', self::xml2array($vField->Value)[0]) : self::xml2array($vField->Value)[0];
                                                $authData->CreateBy = 33;
												$authData->CreateDate = '2017-11-09 16:01:46';
												$authData->CreateTerminal = '::1';
												$authData->UpdateBy = 33;
												$authData->UpdateDate = '2017-11-09 16:01:46';
												$authData->UpdateTerminal = '::1';
												$authData->save();
                                                // echo'<pre>';print_r($authData->getErrors());
                                            }
                                        }

                                        foreach ((array)$variabelField as $keyVariabel => $valueVariabel) {
                                            foreach ($valueVariabel as $kVariabel => $vVariabel) {
                                                // echo'<pre>';print_r(self::xml2array($vField->Tag)[0]);die;
                                                $authData = new \common\models\AuthData;
                                                // $array = array('|', ' ');
                                                $authData->Auth_Header_ID = $authHeader->ID;
                                                $authData->Tag = self::xml2array($vVariabel->Tag)[0];
                                                $authData->Indicator1 = self::xml2array($vVariabel->Indicator1)[0];
                                                $authData->Indicator2 = self::xml2array($vVariabel->Indicator2)[0];
                                                $authData->Value = self::xml2array($vVariabel->Value)[0];
                                                $authData->CreateBy = 33;
												$authData->CreateDate = '2017-11-09 16:01:46';
												$authData->CreateTerminal = '::1';
												$authData->UpdateBy = 33;
												$authData->UpdateDate = '2017-11-09 16:01:46';
												$authData->UpdateTerminal = '::1';
												$authData->save();
                                                // echo'<pre>';print_r($authData->getErrors());
                                            }
                                        }
                                    }
                                }
                            }
                            // $controlField = $cek->ControlField;
                            // foreach ((array)$controlField as $keyField => $valueField) {
                            //  // echo'<pre>';print_r($valueField);die;
                            // }
                            
                          }
                    }
                    // foreach($out as $value){
                    //     $cek = simplexml_load_string($value);
                    //     $cek = self::xml2array($cek);
                    //     foreach($cek as $value){
                    //         foreach($value as $k => $v){
                    //             // echo'<pre>';print_r($v);
                    //             $data = explode('&30;', $v);
                    //             $authID = trim(str_replace('&31;', '$', $data[4]));
                    //             $cekHeader = Yii::$app->db->createCommand('SELECT Auth_ID FROM auth_header WHERE Auth_ID = "'.$authID.'"')->queryAll();
                    //             // echo'<pre>';print_r($data);die;
                    //             if(!$cekHeader){
                    //                 foreach($data as $dk => $dv){
                    //                     $authHeader = new \common\models\AuthHeader;
                    //                     $authHeader->Worksheet_id = 1;
                    //                     $authHeader->Auth_ID = $authID;
                    //                     $authHeader->MARC_LOC = $v;
                    //                     // $authHeader->save();
                    //                     // echo'<pre>';print_r($authHeader->getErrors());

                    //                     if($authHeader->save()){
                    //                         // echo'<pre>';print_r($data);die;
                    //                         foreach($data as $cekKey => $cek){
                    //                             $valueData = trim(str_replace('&31;', '$', $cek));
                    //                             $authData = new \common\models\AuthData;
                    //                             // print_r($dv);
                    //                             $authData->Auth_Header_ID = $authHeader->ID;
                    //                             if($cekKey == '0'){
                                                
                    //                             }
                    //                             else if($cekKey == '1'){
                    //                                 $authData->Tag = '100';
                    //                                 $authData->Indicator1 = Null;
                    //                                 $authData->Indicator2 = Null;
                    //                                 $authData->Value = $valueData;
                    //                             }
                    //                             else if($cekKey == '2'){
                    //                                 $authData->Tag = '005';
                    //                                 $authData->Indicator1 = Null;
                    //                                 $authData->Indicator2 = Null;
                    //                                 $authData->Value = $valueData;
                    //                             }
                    //                             else if($cekKey == '3'){
                    //                                 $array = array('|', ' ');
                    //                                 $authData->Tag = '008';
                    //                                 $authData->Indicator1 = Null;
                    //                                 $authData->Indicator2 = Null;
                    //                                 $authData->Value = str_replace($array, '*', $valueData);
                    //                             }
                    //                             else if($cekKey == '4'){
                    //                                 $authData->Tag = '035';
                    //                                 $authData->Indicator1 = '#';
                    //                                 $authData->Indicator2 = '#';
                    //                                 $authData->Value = $valueData;
                    //                             }
                    //                             else if($cekKey == '5'){
                    //                                 $authData->Tag = '039';
                    //                                 $authData->Indicator1 = '#';
                    //                                 $authData->Indicator2 = '#';
                    //                                 $authData->Value = $valueData;
                    //                             }
                    //                             else{
                    //                                 $find360   = '$i';
                    //                                 $pos360 = strpos($valueData, $find360);
                    //                                 if($pos360 !== false){
                    //                                     $authData->Tag = '360';
                    //                                     $authData->Indicator1 = '#';
                    //                                     $authData->Indicator2 = '#';
                    //                                     $authData->Value = $valueData;
                    //                                 }
                    //                                 $findme   = 'Dapat ditambahkan subdivisi geografis';
                    //                                 $pos = strpos($valueData, $findme);
                    //                                 if($pos !== false){
                    //                                     $authData->Tag = '667';
                    //                                     $authData->Indicator1 = '#';
                    //                                     $authData->Indicator2 = '#';
                    //                                     $authData->Value = $valueData;
                    //                                 }
                    //                                 $findDollarw   = '$w';
                    //                                 $posDollarw = strpos($valueData, $findDollarw);
                    //                                 if($posDollarw !== false){
                    //                                     $authData->Tag = '550';
                    //                                     $authData->Indicator1 = '#';
                    //                                     $authData->Indicator2 = '#';
                    //                                     $authData->Value = $valueData;
                    //                                 }
                    //                                 $find990   = '$a 00';
                    //                                 $pos990 = strpos($valueData, $find990);
                    //                                 if($pos990 !== false){
                    //                                     $authData->Tag = '990';
                    //                                     $authData->Indicator1 = '#';
                    //                                     $authData->Indicator2 = '#';
                    //                                     $authData->Value = $valueData;
                    //                                 }
                                                    
                    //                             }
                                                
                    //                             $authData->save();
                    //                             // echo'<pre>';print_r($authData->getErrors());
                    //                             // echo'<pre>';print_r($cek);die;
                    //                         }
                    //                     }
                    //                     // die;
                    //                     // return true;
                    //                 }
                                        
                    //                 // return true;
                    //             }else{
                    //                 return false;
                    //             }
                                
                    //         }
                    //     }
                    //     // print_r($cek);die;
                        
                        
                    // }
                    // echo 'oke';
                    return $out;
                }else{
                    return false;
                }



                // if ($out['status']==='success'){
                  // return true;
                // } else
                // {
                  // return false;
                // }

            }
        }
        catch(ErrorException $e){
            Yii::warning($e);
            echo $e;
            // return $members->getErrors();;
        }
    }
	
	function xml2array ( $xmlObject, $out = array () )
    {
        foreach ( (array) $xmlObject as $index => $node )
          $out[$index] = ( is_object ( $node ) ) ? self::xml2array ( $node ) : $node;
        return $out;
    }
}