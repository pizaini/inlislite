<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TujuanKunjungan;

/**
 * TujuanKunjunganSearch represents the model behind the search form about `common\models\TujuanKunjungan`.
 */
class TujuanKunjunganSearch extends TujuanKunjungan
{
    public function rules()
    {
        return [
            [['ID', 'Member', 'NonMember', 'Rombongan', 'CreateBy', 'UpdateBy'], 'integer'],
            [['Code', 'TujuanKunjungan', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TujuanKunjungan::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'Member' => $this->Member,
            'NonMember' => $this->NonMember,
            'Rombongan' => $this->Rombongan,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'Code', $this->Code])
            ->andFilterWhere(['like', 'TujuanKunjungan', $this->TujuanKunjungan])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
