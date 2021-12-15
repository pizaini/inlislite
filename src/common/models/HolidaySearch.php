<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Holidays;

/**
 * HolidaySearch represents the model behind the search form about `common\models\Holidays`.
 */
class HolidaySearch extends Holidays
{
    public function rules()
    {
        return [
            [['ID', 'CreateBy', 'UpdateBy'], 'integer'],
            [['Dates', 'Names', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Holidays::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'Dates' => SORT_DESC,
                ],
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            // 'Dates' => $this->Dates,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'Names', $this->Names])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', "DATE_FORMAT(`Dates`,'%d-%m-%Y %H:%i:%s')", $this->Dates])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
