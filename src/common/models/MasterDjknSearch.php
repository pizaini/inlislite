<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MasterDjkn;

/**
 * MasterDjknSearch represents the model behind the search form about `common\models\MasterDjkn`.
 */
class MasterDjknSearch extends MasterDjkn
{
    public function rules()
    {
        return [
            [['ID', 'CreateBy', 'UpdateBy'], 'integer'],
            [['Option_id', 'Option_Name', 'Value', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = MasterDjkn::find();

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

        $query->andFilterWhere(['like', 'Option_id', $this->Option_id])
            ->andFilterWhere(['like', 'Option_Name', $this->Option_Name])
            ->andFilterWhere(['like', 'Value', $this->Value])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
