<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Stockopnamedetail;

/**
 * StockopnamedetailSearch represents the model behind the search form about `common\models\Stockopnamedetail`.
 */
class StockopnamedetailSearch extends Stockopnamedetail
{
    public function rules()
    {
        return [
            [['ID', 'CollectionID', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal'], 'safe'],
            [['StockOpnameID', 'PrevLocationID', 'CurrentLocationID', 'PrevStatusID', 'CurrentStatusID', 'PrevCollectionRuleID', 'CurrentCollectionRuleID', 'CreateBy', 'UpdateBy'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Stockopnamedetail::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['ID'=>SORT_DESC]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'StockOpnameID' => $this->StockOpnameID,
            'PrevLocationID' => $this->PrevLocationID,
            'CurrentLocationID' => $this->CurrentLocationID,
            'PrevStatusID' => $this->PrevStatusID,
            'CurrentStatusID' => $this->CurrentStatusID,
            'PrevCollectionRuleID' => $this->PrevCollectionRuleID,
            'CurrentCollectionRuleID' => $this->CurrentCollectionRuleID,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'ID', $this->ID])
            ->andFilterWhere(['like', 'CollectionID', $this->CollectionID])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
