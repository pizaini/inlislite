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
class BookinglogsSearch extends BaseBookinglogs
{

    public $status;
	
	public function rules()
    {
        return [
            [['memberId', 'collectionId', 'bookingDate', 'bookingExpired','status'], 'safe'],
        ];
    }
	
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



	
	
    public function search($params)
    {
        $query = Bookinglogs::find();

        $query->addSelect([
            "bookinglogs.*",' (CASE  
                WHEN bookingExpired > "'.date('Y-m-d H:i:s').'" THEN "Menunggu"
                ELSE
                    "Selesai"
                END 
                 ) AS status',
        ]);
		// $query->orderBy(['bookingDate' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


                
                

        $dataProvider->setSort([
            'defaultOrder' => [
                    'bookingDate' => SORT_DESC,
                ],
            'attributes' => [
                'memberId', 
                'collectionId', 
                'bookingDate', 
                'bookingExpired',
                'status' => 
                [
                    'asc' => ['status' => SORT_ASC],
                    'desc' => ['status' => SORT_DESC],
                    'label' => Yii::t('app', 'Status'),
                    'default' => SORT_ASC
                ],
                // 'libraryName' => 
                // [
                //     'asc' => ['location_library.Name' => SORT_ASC],
                //     'desc' => ['location_library.Name' => SORT_DESC],
                //     'label' => Yii::t('app', 'Lokasi Perpustakaan'),
                //     'default' => SORT_ASC
                // ],
            ]
        ]);


        $this->load($params);
        if (!$this->validate()) 
        {
            return $dataProvider;
        }


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            // 'memberId' => $this->memberId,
            // 'collectionId' => $this->collectionId,
            // 'bookingDate' => $this->bookingDate,
            // 'bookingExpired' => $this->bookingExpired,
        ]);

        $query->andFilterWhere(['like', 'memberId', $this->memberId])
            ->andFilterWhere(['like', 'collectionId', $this->collectionId])
            ->andFilterWhere(['like', 'bookingDate', $this->bookingDate])
            ->andFilterWhere(['like', 'bookingExpired', $this->bookingExpired])
            // ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            // ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            // ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            // ->andFilterWhere(['like', 'location_library.Name', $this->libraryName])
            ;

        return $dataProvider;
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
