<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Stockopname;

/**
 * StockopnameSearch represents the model behind the search form about `common\models\Stockopname`.
 */
class StockopnameSearch extends Stockopname
{
    public function rules()
    {
        return [
            [['ID', 'Tahun', 'CreateBy', 'UpdateBy'], 'integer'],
            [['ProjectName', 'TglMulai', 'Koordinator', 'Keterangan', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Stockopname::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'TglMulai' => $this->TglMulai,
            'Tahun' => $this->Tahun,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'ProjectName', $this->ProjectName])
            ->andFilterWhere(['like', 'Koordinator', $this->Koordinator])
            ->andFilterWhere(['like', 'Keterangan', $this->Keterangan])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
