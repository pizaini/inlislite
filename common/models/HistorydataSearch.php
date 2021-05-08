<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Historydata;

/**
 * HistorydataSearch represents the model behind the search form about `common\models\Historydata`.
 */
class HistorydataSearch extends Historydata
{
    public function rules()
    {
        return [
            [['ID', 'CreateBy', 'UpdateBy'], 'integer'],
            [['Action', 'TableName', 'IDRef', 'Note', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'Member_id'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Historydata::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'Action', $this->Action])
            ->andFilterWhere(['like', 'TableName', $this->TableName])
            ->andFilterWhere(['like', 'IDRef', $this->IDRef])
            ->andFilterWhere(['like', 'Note', $this->Note])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'Member_id', $this->Member_id]);

        return $dataProvider;
    }
}
