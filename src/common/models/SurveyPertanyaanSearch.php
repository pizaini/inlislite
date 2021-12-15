<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SurveyPertanyaan;

/**
 * SurveyPertanyaanSearch represents the model behind the search form about `common\models\SurveyPertanyaan`.
 */
class SurveyPertanyaanSearch extends SurveyPertanyaan
{
    public function rules()
    {
        return [
            [['ID', 'Survey_id', 'NoUrut', 'CreateBy', 'UpdateBy'], 'integer'],
            [['Pertanyaan', 'JenisPertanyaan', 'Orientation', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal'], 'safe'],
            [['IsMandatory', 'IsCanMultipleAnswer'], 'boolean'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = SurveyPertanyaan::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'Survey_id' => $this->Survey_id,
            'IsMandatory' => $this->IsMandatory,
            'IsCanMultipleAnswer' => $this->IsCanMultipleAnswer,
            'NoUrut' => $this->NoUrut,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'Pertanyaan', $this->Pertanyaan])
            ->andFilterWhere(['like', 'JenisPertanyaan', $this->JenisPertanyaan])
            ->andFilterWhere(['like', 'Orientation', $this->Orientation])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
