<?php
/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2018
 * @package Nik
 * @version 1.0.0
 * @author rico <rico.ulul@gmail.com>
 */

namespace common\components;

use Yii;
use common\models\elastic\Catalogruas;
//no time limit
set_time_limit(0);
ini_set("memory_limit", "-1");

class ElasticHelper
{

  public static function DeleteAllIndex(){
      Catalogruas::deleteIndex();
  }
  public static function CreateAllIndex($limit = false){
      Catalogruas::deleteIndex();
      Catalogruas::createIndex();
      if($limit){
          $cat = Yii::$app->db->createCommand("SELECT *,(SELECT NAME FROM worksheets w WHERE w.id =c.Worksheet_id) AS worksheet_name,	(SELECT ISSERIAL FROM worksheets w WHERE w.id =c.Worksheet_id) AS ISSERIAL FROM catalogs c  ORDER BY c.ID DESC LIMIT ".$limit.";")->queryAll();
      } else {
          $cat = Yii::$app->db->createCommand("SELECT *,(SELECT NAME FROM worksheets w WHERE w.id =c.Worksheet_id) AS worksheet_name,	(SELECT ISSERIAL FROM worksheets w WHERE w.id =c.Worksheet_id) AS ISSERIAL FROM catalogs c  ORDER BY c.ID DESC ;")->queryAll();
      }
      $err=array();
      foreach ($cat as $key => $value) {
          $sub = Yii::$app->db->createCommand("SELECT cs.ID AS subID,cr.`CatalogId`,cr.`Tag`,cr.`Indicator1` AS ind1, cr.`Indicator2` AS ind2, cs.RuasID, cs.SubRuas, LOWER(cs.Value) AS Value, cs.Sequence FROM catalog_subruas cs LEFT JOIN catalog_ruas cr ON cr.id = cs.RuasID WHERE cr.CatalogID = ".$value['ID'].";")->queryAll();
        
	 $elastic = new Catalogruas();
          $i=0;
          //add index
          foreach ($value as $keys => $values) {
              $elastic->$keys = $values;
              $i++;
              //insert ruas on last index
              if ($i == sizeof($value)){
                  $elastic->subruas = $sub;
              }
          }
          //save record
          if ($elastic->addRecord($elastic)) {
              //free from memory
              unset($elastic);
          } else {
              array_push($err, $value['ID']);
              //free from memory
              unset($elastic);
          }
      }
      return $err;
  }


    public static function CreateAllIndexAdvance(){
        Catalogruas::deleteIndex();
        Catalogruas::createIndex();

        $countCat = Yii::$app->db->createCommand("SELECT count(1) FROM catalogs c  ORDER BY c.ID DESC ;")->queryScalar();
        $pembagi = 50;
        $loop = ceil($countCat/$pembagi);

        for ($i=0;$i<$loop;$i++){
             //query awal
            if ($i==0){
                $cat = Yii::$app->db->createCommand("SELECT *,(SELECT NAME FROM worksheets w WHERE w.id =c.Worksheet_id) AS worksheet_name,	(SELECT ISSERIAL FROM worksheets w WHERE w.id =c.Worksheet_id) AS ISSERIAL FROM catalogs c  ORDER BY c.ID DESC LIMIT 0,".$pembagi.";")->queryAll();
                self::Insert($cat);
                unset($cat);
            } else {
                $awal=$i*$pembagi+1;
                $akhir=($i+1)*$pembagi;
                $cat = Yii::$app->db->createCommand("SELECT *,(SELECT NAME FROM worksheets w WHERE w.id =c.Worksheet_id) AS worksheet_name,	(SELECT ISSERIAL FROM worksheets w WHERE w.id =c.Worksheet_id) AS ISSERIAL FROM catalogs c  ORDER BY c.ID DESC LIMIT ".$awal.",".$pembagi.";")->queryAll();
                self::Insert($cat);
                unset($cat);
            }
        };
    }

    public static function Insert($cat){
        $err=array();
        foreach ($cat as $key => $value) {
            $sub = Yii::$app->db->createCommand("SELECT cs.ID AS subID,cr.`CatalogId`,cr.`Tag`,cr.`Indicator1` AS ind1, cr.`Indicator2` AS ind2, cs.RuasID, cs.SubRuas, LOWER(cs.Value) AS Value, cs.Sequence FROM catalog_subruas cs LEFT JOIN catalog_ruas cr ON cr.id = cs.RuasID WHERE cr.CatalogID = ".$value['ID'].";")->queryAll();

            $elastic = new Catalogruas();
            $i=0;

                //add index
                foreach ($value as $keys => $values) {
                    $elastic->$keys = $values;
                    $i++;
                    //insert ruas on last index
                    if ($i == sizeof($value)){
                        //jika subruas kosong maka tidak kita isi subruasnya
                        if (sizeof($sub !=0)){
                            $elastic->subruas = $sub;
                        }
                    }
                }
                //save record
                if ($elastic->addRecord($elastic)) {
                    //free from memory
                    unset($elastic);
                    unset($sub);
                } else {
                    array_push($err, $value['ID']);
                    //free from memory
                    unset($elastic);
                    unset($sub);
                }

        }
        unset($value);
        unset($key);
        unset($cat);


        return $err;
    }

  public static function CreateIndexByID($catID){
      $cat = Yii::$app->db->createCommand("SELECT *,(SELECT NAME FROM worksheets w WHERE w.id =c.Worksheet_id) AS worksheet_name,	(SELECT ISSERIAL FROM worksheets w WHERE w.id =c.Worksheet_id) AS ISSERIAL FROM catalogs c  where c.ID =".$catID."; ")->queryAll();
      $err=array();
      foreach ($cat as $key => $value) {
          $sub = Yii::$app->db->createCommand("SELECT cs.ID AS subID,cr.`CatalogId`,cr.`Tag`,cr.`Indicator1` AS ind1, cr.`Indicator2` AS ind2, cs.RuasID, cs.SubRuas, LOWER(cs.Value) AS Value, cs.Sequence FROM catalog_subruas cs LEFT JOIN catalog_ruas cr ON cr.id = cs.RuasID WHERE cr.CatalogID = ".$value['ID'].";")->queryAll();
          $elastic = new Catalogruas();
          $i=0;
          //add index
          foreach ($value as $keys => $values) {
              $elastic->$keys = $values;
              $i++;
              //insert ruas on last index
              if ($i == sizeof($value)){
                  $elastic->subruas = $sub;
              }
          }
          //save record
          if ($elastic->addRecord($elastic)) {
              //free from memory
              unset($elastic);
          } else {
              array_push($err, $value['ID']);
              //free from memory
              unset($elastic);
          }
      }
      return $err;
  }
}
?>
