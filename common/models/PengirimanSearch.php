<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Pengiriman;

/**
 * PengirimanSearch represents the model behind the search form about `\common\models\Pengiriman`.
 */
class PengirimanSearch extends Pengiriman
{
    public function rules()
    {
        return [
            [['ID', 'CreateBy', 'UpdateBy'], 'integer'],
            [['JudulKiriman', 'PenanggungJawab', 'NipPenanggungJawab', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Pengiriman::find();

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

        $query->andFilterWhere(['like', 'JudulKiriman', $this->JudulKiriman])
            ->andFilterWhere(['like', 'PenanggungJawab', $this->PenanggungJawab])
            ->andFilterWhere(['like', 'NipPenanggungJawab', $this->NipPenanggungJawab])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
