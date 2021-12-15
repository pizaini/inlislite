<?php
/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2017
 * @package Nik
 * @version 1.0.0
 * @author rico <rico.ulul@gmail.com>
 */

namespace common\components;
class Registrasi
{

  public static function registrasi($param,$url)
  {
	   // print_r($param);die;
      $out = "";
      $request = \Yii::$app->request;
      if ($request->isAjax) {
          // echo "Yes";
          $context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
          $urlLink = $url.'namaPerpustakaan='.urlencode($param['namaPerpus']).'&jenisPerpustakaan='.urlencode($param['jenisPerpus']).'&activationCode='.$param['kodeRegis'].'&negara='.$param['negara'].'&provinsi='.$param['provinsi'].'&ip='.$param['ip'];
          // print_r($urlLink);die;
          $xml = @file_get_contents($urlLink, false, $context);
           // print_r($xml);die;
          if ($xml == TRUE) {
            // print_r($xml);die;
              $xml = simplexml_load_string($xml);
              $out = self::xml2array($xml);

     //          echo('<pre>');
			  // print_r($out);
			  // die;

              if ($out['status']==='success'){
                  return true;
              } else
              {
                  return false;
              }
			  
          }

          return $out;
      }else{
          return "Gak Boleh gan";
      }
  }
  function xml2array ( $xmlObject, $out = array () )
  {
      foreach ( (array) $xmlObject as $index => $node )
          $out[$index] = ( is_object ( $node ) ) ? self::xml2array ( $node ) : $node;
      return $out;
  }
  
  public static function detail($param,$url){

      $out = "";
      $request = \Yii::$app->request;
      if ($request->isAjax) {
          // echo "Yes";
          $context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
          $urlLink = $url.'?noReg='.$param['noReg'];
          // print_r($urlLink);die;
          $xml = @file_get_contents($urlLink, false, $context);
           // print_r($xml);die;
          if ($xml == TRUE) {
            print_r($xml);die;
              $out = self::xml2array($xml);

              if ($out['status']==='success'){
                echo json_encode($out);
                  // return true;
              } else
              {
                  return false;
              }
        
          }

          return $xml;
      }else{
          return "Gak Boleh gan";
      }
  }

}
?>