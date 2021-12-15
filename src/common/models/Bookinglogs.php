<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use leandrogehlen\querybuilder\Translator;

use \common\models\base\Bookinglogs as BaseBookinglogs;

/**
 * This is the model class for table "bookinglogs".
 *
 *
 * @property \common\models\Collections $collections
 */
class Bookinglogs extends BaseBookinglogs
{

    public $status;

   public function behaviors()
    {
        return [
        
        ];
    }




     /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollections()
    {
        return $this->hasOne(\common\models\Collections::className(), ['ID' => 'collectionId']);
    }



    public function searchBooking($MemberNo = null,$isExpired = false){

        $query = Bookinglogs::find();
    	if(!is_null($MemberNo)){
    		$query->Where(['memberID' => $MemberNo]);	
    	}

    	if($isExpired){
    		$query->andWhere(['<','bookingExpired',date('Y-m-d H:i:s')]);
    	}else{
    		$query->andWhere(['>=','bookingExpired',date('Y-m-d H:i:s')]);
    	}
        $query->orderBy(['bookingDate' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'pagination' => false,
            /*'sort' =>[
                'defaultOrder' => [
                    'ID' => SORT_DESC
                ]
            ],*/
        ]);

        return $dataProvider;
    }

}
