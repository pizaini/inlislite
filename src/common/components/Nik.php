<?php
/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2017
 * @package Nik
 * @version 1.0.0
 * @author rico <rico.ulul@gmail.com>
 */

namespace common\components;
use common\models\MasterPekerjaan;
use common\models\MasterPendidikan;
class Nik
{

  public static function getNIK($param = '',$url)
  {

      $out = "";
      $request = \Yii::$app->request;
      // echo'<pre>';print_r($request->isAjax);die;
      if ($request->isAjax) {
          // echo "Yes";
          $context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
          $urlLink = $url.$param;
          $xml = @file_get_contents($urlLink, false, $context);
          // echo'<pre>';print_r($xml);die;
          if ($xml == TRUE) {
              $xml = simplexml_load_string($xml);
              // echo('<pre>');
              $out = self::xml2array($xml);
              if (count($out['data']) < 1){
                 return $out;
              }else{
                  $out = self::getRelationID($out['data']['DataPenduduk']);
                  return $out;
              }
          }
          return $out;
      }else{
          return "Gak Boleh";
      }
  }
  function xml2array ( $xmlObject, $out = array () )
  {
      foreach ( (array) $xmlObject as $index => $node )
          $out[$index] = ( is_object ( $node ) ) ? self::xml2array ( $node ) : $node;
      return $out;
  }
  function getRelationID($data) {

    $connection = \Yii::$app->getDb();
    foreach ($data as $key => &$value) {
      
      switch ($key) {
        case 'NIK':
        case 'NOMOR_KK':
          $value=strtok($value, '.');
          break;
        case 'AGAMA':
          //$user = Agama::find()->where(['name' => $value])->one();
          $datas = $connection->createCommand("select ID from Agama where Name like '%".$value."%' ")->queryScalar();
          if ($datas) $value = $datas;;           
          break;
        case 'PENDIDIKAN_AKHIR':
          $datas = $connection->createCommand("select ID from master_pendidikan where Nama like '%".$value."%' ")->queryScalar();
          if ($datas){
            $value = $datas;
          } else {
            $models = new MasterPendidikan;
            $models->Nama = $value;
            $models->save();
            $value = $models->getPrimaryKey();
          }           
          break;
        case 'JENIS_PEKERJAAN':
          switch ($value) {
              case 'KARYAWAN SWASTA':
                $value ="Pegawai Swasta";
                break;
              case 'PNS':
                $value ="Pegawai Negeri";
                break;
            }
          $datas = $connection->createCommand("select ID from master_pekerjaan where Pekerjaan like '%".$value."%' ")->queryScalar();            
          if ($datas){
            $value = $datas;
          } else {
            $models = new MasterPekerjaan;
            $models->Pekerjaan = $value;
            $models->save();
            $value = $models->getPrimaryKey();
          }    

          break;
        case 'JENIS_KELAMIN':

          switch ($value) {
            case 'Laki laki':
              $value ="Laki-laki";
              break;
          }
          $datas = $connection->createCommand("select ID from jenis_kelamin where Name like '%".$value."%' ")->queryScalar();
          if ($datas) $value = $datas;; 
          break;
        case 'TANGGAL_LAHIR':
          $datas =  date("d/m/Y", strtotime($value));
          if ($datas) $value = $datas;; 
          break;
                   
      }

    }

    return $data;

  }
   

}
?>