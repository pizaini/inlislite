<?php

namespace common\models;

use Yii;
use \common\models\base\AuthData as BaseAuthData;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "auth_data".
 */
class AuthData extends BaseAuthData
{

	

    public function getAuthDataPengarang($cleanDollar) {
        $resfinal=[];
        $qtxt = "SELECT DISTINCT Value FROM auth_data WHERE Tag IN('100','110','111')";
        $res = BaseAuthData::findBySql($qtxt)->addSelect(['Value'])->asArray()->all();
        $res = ArrayHelper::map($res,'Value','Value');
        foreach ($res as $key => $value) {
           if($cleanDollar==true)
        	{
        		$resfinal[] = trim(preg_replace('/(\$\w)(.*?)(\$?)/', '', $value));
        	}else{
        		$resfinal[] = trim($value);
        	}
        }
        return $resfinal;
    }

    public function getAuthDataSubyek($cleanDollar) {
        $resfinal=[];
        $qtxt = "SELECT DISTINCT Value FROM auth_data WHERE Tag IN('150')";
        $res = BaseAuthData::findBySql($qtxt)->addSelect(['Value'])->asArray()->all();
        $res = ArrayHelper::map($res,'Value','Value');
        foreach ($res as $key => $value) {
        	if($cleanDollar==true)
        	{
        		$resfinal[] = trim(preg_replace('/(\$\w)(.*?)(\$?)/', '', $value));
        	}else{
        		$resfinal[] = trim($value);
        	}
           
        }
        return $resfinal;
    }

    public function getAuthDataDDC($cleanDollar,$subject) {
        $resfinal=[];
        if(!empty($subject))
        {
            $a='$a';
            $x='$x';
            $z='$z';
            $qsubject = "SELECT DISTINCT Auth_Header_ID FROM auth_data WHERE  (Tag='150' OR Tag='151') AND REPLACE(REPLACE(REPLACE(VALUE, '$a ', ''),'$x ',''),'$z ','') =:subject";
            //$rsubject = BaseAuthData::findBySql($qsubject)->addSelect(['Auth_Header_ID'])->asArray()->all();
            $command = Yii::$app->db->createCommand($qsubject);
            $command->bindValue(':subject', $subject);
            $rsubject=$command->queryAll();

            $rsubject = ArrayHelper::map($rsubject,'Auth_Header_ID','Auth_Header_ID');
            $rsubject = implode(",", $rsubject);

            if(!empty($rsubject))
            {

                $qtxt = "SELECT DISTINCT Value FROM auth_data WHERE Tag IN('082') and Auth_Header_ID IN(".$rsubject.")";
                $res = BaseAuthData::findBySql($qtxt)->addSelect(['Value'])->asArray()->all();
                $res = ArrayHelper::map($res,'Value','Value');
                foreach ($res as $key => $value) {
                    if($cleanDollar==true)
                    {
                        $resfinal[] = trim(preg_replace('/(\$\w)(.*?)(\$?)/', '', $value));
                    }else{
                        $resfinal[] = trim($value);
                    }
                   
                }
            }
        }
        return $resfinal;
    }
}
