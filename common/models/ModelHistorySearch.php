<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Modelhistory;

/**
 * ModelHistorySearch represents the model behind the search form about `common\models\Modelhistory`.
 */
class ModelHistorySearch extends Modelhistory
{
    public $deskripsi;
    public function rules()
    {
        return [
            [['id', 'type'], 'integer'],
            [['date', 'table', 'field_name', 'field_id', 'old_value', 'new_value', 'user_id'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Modelhistory::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'date' => SORT_DESC,
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            // 'date' => $this->date,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'table', $this->table])
            ->andFilterWhere(['like', 'field_name', $this->field_name])
            ->andFilterWhere(['like', 'field_id', $this->field_id])
            ->andFilterWhere(['like', 'old_value', $this->old_value])
            ->andFilterWhere(['like', 'new_value', $this->new_value])
            ->andFilterWhere(['like', 'user_id', $this->user_id])
            ->andFilterWhere(['like', "DATE_FORMAT(modelhistory.date,'%d-%m-%Y %H:%i:%s')", $this->date])
            ;

        return $dataProvider;
    }
}
