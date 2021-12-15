<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Fieldindicator1s;

/**
 * CollectionmediaSearch represents the model behind the search form about `common\models\Collectionmedias`.
 */
class Fieldindicator1Search extends Fielddatas
{
    /*
    public function rules()
    {
        return [
            [['ID', 'IsDelete'], 'integer'],
            [['JumlahKoleksi','Worksheet_id','Code', 'Name', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }
    */

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    public function loadIndicatorEntry($id)
    {
        $query = Fieldindicator1s::find()
        ->where(['Field_id'=>$id]);
        $query->joinWith(['field'],' fields.ID = fieldindicator1s.Field_id');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
            'defaultOrder'=>[
                'SortNo' => SORT_ASC,
                ]
            ]
        ]);

        /*
        $dataProvider->setSort([
            'attributes' => [
                'Code' => [
                    'asc' => ['fielddatas.Code' => SORT_ASC],
                    'desc' => ['fielddatas.Code' => SORT_DESC],
                    'label' => 'Code',
                    'default' => SORT_ASC
                ],
                'Name' => [
                    'asc' => ['fielddatas.Name' => SORT_ASC],
                    'desc' => ['fielddatas.Name' => SORT_DESC],
                    'label' => 'Name'
                ],
            ]
        ]);
        */
        //$this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }


        return $dataProvider;
    }
}
