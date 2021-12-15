<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Settingparameters;

/**
 * SettingparameterSearch represents the model behind the search form about `common\models\Settingparameters`.
 */
class SettingparameterSearch extends Settingparameters
{
    
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['NomorInduk','NomorIndukTengah','Name', 'Value', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Settingparameters::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'Value', $this->Value])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
