<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DepositWs;

/**
 * DepositWsSearch represents the model behind the search form about `common\models\DepositWs`.
 */
class DepositWsSearch extends DepositWs
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'id_group_deposit_group_ws', 'id_deposit_kelompok_penerbit_ws', 'ID_deposit_kode_wilayah', 'kode_pos', 'no_telp1', 'no_telp2', 'no_telp3', 'no_fax', 'no_contact', 'koleksi_per_tahun', 'status'], 'integer'],
            [['jenis_penerbit', 'nama_penerbit', 'alamat1', 'alamat2', 'alamat3', 'kabupaten', 'email', 'contact_person', 'keterangan'], 'safe'],
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
        $query = DepositWs::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'ID' => $this->ID,
            'id_group_deposit_group_ws' => $this->id_group_deposit_group_ws,
            'id_deposit_kelompok_penerbit_ws' => $this->id_deposit_kelompok_penerbit_ws,
            'ID_deposit_kode_wilayah' => $this->ID_deposit_kode_wilayah,
            'kode_pos' => $this->kode_pos,
            'no_telp1' => $this->no_telp1,
            'no_telp2' => $this->no_telp2,
            'no_telp3' => $this->no_telp3,
            'no_fax' => $this->no_fax,
            'no_contact' => $this->no_contact,
            'koleksi_per_tahun' => $this->koleksi_per_tahun,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'jenis_penerbit', $this->jenis_penerbit])
            ->andFilterWhere(['like', 'nama_penerbit', $this->nama_penerbit])
            ->andFilterWhere(['like', 'alamat1', $this->alamat1])
            ->andFilterWhere(['like', 'alamat2', $this->alamat2])
            ->andFilterWhere(['like', 'alamat3', $this->alamat3])
            ->andFilterWhere(['like', 'kabupaten', $this->kabupaten])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'contact_person', $this->contact_person])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
