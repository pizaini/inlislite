<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MasterJurusan;

/**
 * MasterJurusanSearch represents the model behind the search form about `common\models\MasterJurusan`.
 */
class MasterJurusanSearch extends MasterJurusan
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['id_fakultas','Nama', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = MasterJurusan::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

         $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Name Of Relation On Model.
        $query->joinWith('idFakultas');

        $query->andFilterWhere([
            'id' => $this->id,
            //'id_fakultas' => $this->id_fakultas,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'Nama', $this->Nama])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            // master_fakultas => tabel name.
            ->andFilterWhere(['like', 'master_fakultas.Nama', $this->id_fakultas]);

        return $dataProvider;
    }
}
