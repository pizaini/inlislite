<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DepositTaksiranHarga;

/**
 * DepositTaksiranHargaSearch represents the model behind the search form about `common\models\DepositTaksiranHarga`.
 */
class DepositTaksiranHargaSearch extends DepositTaksiranHarga
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'jumlah_halaman'], 'integer'],
            [['ID_collections'], 'number'],
            [['cover', 'muka_buku', 'hard_cover', 'penjilidan', 'jenis_kertas_buku', 'ukuran_buku', 'kondisi_buku', 'kondisi_usang', 'full_color'], 'safe'],
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
        $query = DepositTaksiranHarga::find();

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
            'ID_collections' => $this->ID_collections,
            'jumlah_halaman' => $this->jumlah_halaman,
        ]);

        $query->andFilterWhere(['like', 'cover', $this->cover])
            ->andFilterWhere(['like', 'muka_buku', $this->muka_buku])
            ->andFilterWhere(['like', 'hard_cover', $this->hard_cover])
            ->andFilterWhere(['like', 'penjilidan', $this->penjilidan])
            ->andFilterWhere(['like', 'jenis_kertas_buku', $this->jenis_kertas_buku])
            ->andFilterWhere(['like', 'ukuran_buku', $this->ukuran_buku])
            ->andFilterWhere(['like', 'kondisi_buku', $this->kondisi_buku])
            ->andFilterWhere(['like', 'kondisi_usang', $this->kondisi_usang])
            ->andFilterWhere(['like', 'full_color', $this->full_color]);

        return $dataProvider;
    }
}
