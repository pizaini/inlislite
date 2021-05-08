<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Collectionloans;

/**
 * CollectionloanSearch represents the model behind the search form about `common\models\Collectionloans`.
 */
class CollectionloanSearch extends Collectionloans
{
    public function rules()
    {
        return [
            [['ID', 'Member_id', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal'], 'safe'],
            [['CollectionCount', 'LateCount', 'ExtendCount', 'LoanCount', 'ReturnCount', 'Branch_id', 'CreateBy', 'UpdateBy'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Collectionloans::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'CollectionCount' => $this->CollectionCount,
            'LateCount' => $this->LateCount,
            'ExtendCount' => $this->ExtendCount,
            'LoanCount' => $this->LoanCount,
            'ReturnCount' => $this->ReturnCount,
            'Branch_id' => $this->Branch_id,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'ID', $this->ID])
            ->andFilterWhere(['like', 'Member_id', $this->Member_id])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }


    public function searchForMember($params)
    {
        $query = Collectionloans::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'CollectionCount' => $this->CollectionCount,
            'LateCount' => $this->LateCount,
            'ExtendCount' => $this->ExtendCount,
            'LoanCount' => $this->LoanCount,
            'ReturnCount' => $this->ReturnCount,
            'Branch_id' => $this->Branch_id,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'ID', $this->ID])
            ->andFilterWhere(['=', 'Member_id', $this->Member_id])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
