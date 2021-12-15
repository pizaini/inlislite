<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MasterPelanggaranLocker;

/**
 * MasterPelanggaranLockerSearch represents the model behind the search form about `common\models\MasterPelanggaranLocker`.
 */
class MasterPelanggaranLockerSearch extends MasterPelanggaranLocker
{
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['jenis_pelanggaran', 'deskripsi', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
            [['denda'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = MasterPelanggaranLocker::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'denda' => $this->denda,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'jenis_pelanggaran', $this->jenis_pelanggaran])
            ->andFilterWhere(['like', 'deskripsi', $this->deskripsi])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
