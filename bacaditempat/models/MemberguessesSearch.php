<?php

namespace bacaditempat\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Memberguesses;

/**
 * MemberguessesSearch represents the model behind the search form about `checkpoint\models\Memberguesses`.
 */
class MemberguessesSearch extends Memberguesses
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'LOCATIONLOANS_ID'], 'integer'],
            [['NoAnggota', 'Nama', 'Status', 'MasaBerlaku', 'Profesi', 'PendidikanTerakhir', 'JenisKelamin', 'Alamat', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal', 'Deskripsi'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Memberguesses::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
            'LOCATIONLOANS_ID' => $this->LOCATIONLOANS_ID,
        ]);

        $query->andFilterWhere(['like', 'NoAnggota', $this->NoAnggota])
            ->andFilterWhere(['like', 'Nama', $this->Nama])
            ->andFilterWhere(['like', 'Status', $this->Status])
            ->andFilterWhere(['like', 'MasaBerlaku', $this->MasaBerlaku])
            ->andFilterWhere(['like', 'Profesi', $this->Profesi])
            ->andFilterWhere(['like', 'PendidikanTerakhir', $this->PendidikanTerakhir])
            ->andFilterWhere(['like', 'JenisKelamin', $this->JenisKelamin])
            ->andFilterWhere(['like', 'Alamat', $this->Alamat])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'Deskripsi', $this->Deskripsi]);

        return $dataProvider;
    }
}
