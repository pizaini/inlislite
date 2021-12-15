<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Fielddatas;

/**
 * CollectionmediaSearch represents the model behind the search form about `common\models\Collectionmedias`.
 */
class FielddataSearch extends Fielddatas
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

    public function search($params)
    {
        $query = Fielddatas::find();
        $query->joinWith(['field'],' fields.ID = fielddatas.Field_id');
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
            'defaultOrder'=>[
                'SortNo' => SORT_ASC,
                ]
            ]
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'Code' => [
                    'asc' => ['fielddatas.Code' => SORT_ASC],
                    'desc' => ['fielddatas.Code' => SORT_DESC],
                    'label' => 'Tag',
                    'default' => SORT_ASC
                ],
                'Name' => [
                    'asc' => ['fielddatas.Name' => SORT_ASC],
                    'desc' => ['fielddatas.Name' => SORT_DESC],
                    'label' => 'Nama'
                ],
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'fields.Tag', $this->Tag])
        ->andFilterWhere(['like', 'fields.Name', $this->Field_id]);

        return $dataProvider;
    }

    public function loadSubTagEntry($tag,$isrda,$isshow)
    {
        if((string)$isrda == 0 && (string)$tag == 700){
            $query = Fielddatas::find()
                     ->where(['fields.Tag' => (string)$tag, 'fielddatas.IsShow' => (int)$isshow])
                     ->andWhere(
                        ['!=', 'fielddatas.Code', 'e'
                    ]);
        }else{
            $query = Fielddatas::find()
                     ->where(['fields.Tag' => (string)$tag, 'fielddatas.IsShow' => (int)$isshow]);
        }
        $query->joinWith(['field'],' fields.ID = fielddatas.Field_id');
        $query->orderBy('SortNo ASC');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query
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
