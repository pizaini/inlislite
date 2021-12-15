<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MasterRangeUmur;

/**
 * MasterRangeUmurSearch represents the model behind the search form about `common\models\MasterRangeUmur`.
 */
class MasterRangeUmurSearch extends MasterRangeUmur
{
    public function rules()
    {
        return [
            [['id', 'umur1', 'umur2'], 'integer'],
            [['Keterangan', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = MasterRangeUmur::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'umur1' => $this->umur1,
            'umur2' => $this->umur2,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,

        ]);

        $query->andFilterWhere(['like', 'Keterangan', $this->Keterangan])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
