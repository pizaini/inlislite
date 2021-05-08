<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SurveyPilihan;

/**
 * SurveyPilihanSearch represents the model behind the search form about `common\models\SurveyPilihan`.
 */
class SurveyPilihanSearch extends SurveyPilihan
{
    public function rules()
    {
        return [
            [['ID', 'Survey_Pertanyaan_id', 'ChoosenCount', 'CreateBy', 'UpdateBy'], 'integer'],
            [['Pilihan', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = SurveyPilihan::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'Survey_Pertanyaan_id' => $this->Survey_Pertanyaan_id,
            'ChoosenCount' => $this->ChoosenCount,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'Pilihan', $this->Pilihan])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
