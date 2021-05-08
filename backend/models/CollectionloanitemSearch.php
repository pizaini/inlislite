<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Collectionloanitems;

/**
 * CollectionloanitemSearch represents the model behind the search form about `common\models\Collectionloanitems`.
 */
class CollectionloanitemSearch extends Collectionloanitems
{
    public function rules()
    {
        return [
            [['ID', 'CollectionLoan_id', 'LoanDate', 'DueDate', 'ActualReturn', 'LoanStatus', 'Collection_id', 'member_id', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'KIILastUploadDate'], 'safe'],
            [['LateDays', 'CreateBy', 'UpdateBy'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Collectionloanitems::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'LoanDate' => $this->LoanDate,
            'DueDate' => $this->DueDate,
            'ActualReturn' => $this->ActualReturn,
            'LateDays' => $this->LateDays,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
            'KIILastUploadDate' => $this->KIILastUploadDate,
        ]);

        $query->andFilterWhere(['like', 'ID', $this->ID])
            ->andFilterWhere(['like', 'CollectionLoan_id', $this->CollectionLoan_id])
            ->andFilterWhere(['like', 'LoanStatus', $this->LoanStatus])
            ->andFilterWhere(['like', 'Collection_id', $this->Collection_id])
            ->andFilterWhere(['like', 'member_id', $this->member_id])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
