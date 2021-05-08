<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Currency;

/**
 * CurrencySearch represents the model behind the search form about `common\models\Currency`.
 */
class CurrencySearch extends Currency
{
    public function rules()
    {
        return [
            [['Copies','Currency', 'Description', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
            [['Sort_ID'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Currency::find();
        $queryCollections = Collections::find()
                ->select('Currency,count(ID) AS Copies')
                ->groupby('Currency');
        $query->leftJoin(['collectionCount' => $queryCollections],' collectionCount.Currency = currency.Currency')
              ->orderBy('Sort_ID');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'Currency',
                'Sort_ID',
                'Description',
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

        $query->andFilterWhere([
            'Sort_ID' => $this->Sort_ID,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'currency.Currency', $this->Currency])
            ->andFilterWhere(['like', 'currency.Description', $this->Description])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'collectionCount.Copies', $this->Copies]);

        return $dataProvider;
    }
}
