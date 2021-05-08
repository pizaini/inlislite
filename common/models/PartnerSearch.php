<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Partners;

/**
 * PartnerSearch represents the model behind the search form about `common\models\Partners`.
 */
class PartnerSearch extends Partners
{
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['Copies','Name', 'Address', 'Phone', 'Fax', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        // $query = Partners::findBySql("SELECT `partners`.ID, `partners`.Name, `partners`.Address FROM `partners` LEFT JOIN (SELECT `Partner_id`, COUNT(ID) AS Copies FROM `collections`) `collectionCount` ON  collectionCount.Partner_id = partners.id ORDER BY IF(TRIM(`Name`) = '' OR TRIM(`Name`) IS NULL,1,0),REPLACE(TRIM(`Name`),'(','') ");

        $query = Partners::find()->addSelect(["ID, Name, Address, (SELECT COUNT(ID) AS Copies FROM `collections` WHERE collections.`Partner_id` = partners.id) AS Copies"]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->setSort([
            'attributes' => [
                'ID',
                'Name',
                'Address',
                // 'Phone',
                // 'Fax',
                'Copies' => [
                    'asc' => ['collectionCount.Copies' => SORT_ASC],
                    'desc' => ['collectionCount.Copies' => SORT_DESC],
                    'label' => 'Jumlah Koleksi',
                    'default' => SORT_ASC
                ],
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $eksemplar = '(SELECT COUNT(ID) AS Copies FROM `collections` WHERE collections.`Partner_id` = partners.id)';

        $query->andFilterWhere([
            'ID' => $this->ID,
            //'IsDelete' => $this->IsDelete,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'Address', $this->Address])
            ->andFilterWhere(['like', 'Phone', $this->Phone])
            // // ->andFilterWhere(['like', 'Fax', $this->Fax])
            // ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            // ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            // ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            // ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', $eksemplar, $this->Copies]);

        return $dataProvider;
    }
}
