<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Opaclogs;

/**
 * opaclogsSearch represents the model behind the search form about `common\models\Opaclogs`.
 */
class opaclogsSearch extends Opaclogs
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'isLKD', 'CreateBy', 'UpdateBy'], 'integer'],
            [['User_id', 'ip', 'jenis_pencarian', 'keyword', 'jenis_bahan', 'waktu', 'url', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'Target_Pembaca', 'Bahasa', 'Bentuk_Karya'], 'safe'],
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
        $query = Opaclogs::find()->where(['jenis_pencarian' => $params['pencarian']]);

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
        $dataProvider->setSort([
            'attributes' => [
                'User_id',
                'ip',
                'jenis_pencarian',
                'keyword',
                'Bahasa',
                'Bentuk_Karya',
                'Target_Pembaca',
                'jenis_bahan',
                'waktu',
                'url',
                
            ]
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'isLKD' => 0,
        ]);
        $query->orderBy(['ID' => SORT_DESC]);

        $query->andFilterWhere(['like', 'User_id', $this->User_id])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'jenis_pencarian', $this->jenis_pencarian])
            ->andFilterWhere(['like', 'keyword', $this->keyword])
            ->andFilterWhere(['like', 'jenis_bahan', $this->jenis_bahan])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'Target_Pembaca', $this->Target_Pembaca])
            ->andFilterWhere(['like', 'Bahasa', $this->Bahasa])
            ->andFilterWhere(['like', 'Bentuk_Karya', $this->Bentuk_Karya]);

        return $dataProvider;
    }
    public function searchLKD($params)
    {
        $query = Opaclogs::find()->where(['jenis_pencarian' => $params['pencarian']]);

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
        $dataProvider->setSort([
            'attributes' => [
                'User_id',
                'ip',
                'jenis_pencarian',
                'keyword',
                'Bahasa',
                'Bentuk_Karya',
                'Target_Pembaca',
                'jenis_bahan',
                'waktu',
                'url',
                
            ]
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'isLKD' =>1,
        ]);
        $query->orderBy(['ID' => SORT_DESC]);

        $query->andFilterWhere(['like', 'User_id', $this->User_id])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'jenis_pencarian', $this->jenis_pencarian])
            ->andFilterWhere(['like', 'keyword', $this->keyword])
            ->andFilterWhere(['like', 'jenis_bahan', $this->jenis_bahan])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'Target_Pembaca', $this->Target_Pembaca])
            ->andFilterWhere(['like', 'Bahasa', $this->Bahasa])
            ->andFilterWhere(['like', 'Bentuk_Karya', $this->Bentuk_Karya]);

        return $dataProvider;
    }
}
