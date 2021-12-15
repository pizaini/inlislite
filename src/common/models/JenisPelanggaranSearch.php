<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\JenisPelanggaran;

/**
 * JenisPelanggaranSearch represents the model behind the search form about `common\models\JenisPelanggaran`.
 */
class JenisPelanggaranSearch extends JenisPelanggaran
{
    public function rules()
    {
        return [
            [['ID', 'CreateBy', 'UpdateBy'], 'integer'],
            [['JenisPelanggaran', 'Keterangan', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = JenisPelanggaran::find();

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

        $query->andFilterWhere(['like', 'JenisPelanggaran', $this->JenisPelanggaran])
            ->andFilterWhere(['like', 'Keterangan', $this->Keterangan])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
