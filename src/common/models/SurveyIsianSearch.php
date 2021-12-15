<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SurveyIsian;

/**
 * SurveyIsianSearch represents the model behind the search form about `common\models\SurveyIsian`.
 */
class SurveyIsianSearch extends SurveyIsian
{
    public function rules()
    {
        return [
            [['ID', 'Survey_Pertanyaan_id'], 'integer'],
            [['Sesi', 'MemberNo', 'Isian'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = SurveyIsian::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'Survey_Pertanyaan_id' => $this->Survey_Pertanyaan_id,
        ]);

        $query->andFilterWhere(['like', 'Sesi', $this->Sesi])
            ->andFilterWhere(['like', 'MemberNo', $this->MemberNo])
            ->andFilterWhere(['like', 'Isian', $this->Isian]);

        return $dataProvider;
    }
}
