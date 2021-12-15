<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Smslogs;
use leandrogehlen\querybuilder\Translator;

/**
 * SmslogsSearch represents the model behind the search form about `common\models\Smslogs`.
 */
class SmslogsSearch extends Smslogs
{
    public function rules()
    {
        return [
            [['recieverID'], 'number'],
            [['Text'], 'string'],
            [['CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['phoneNumber'], 'string', 'max' => 20],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100]
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    public function search($params)
    {
        $query = Smslogs::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'recieverID' => $this->recieverID,
            'phoneNumber' => $this->phoneNumber,
            'Text' => $this->Text,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'recieverID', $this->recieverID])
            ->andFilterWhere(['like', 'phoneNumber', $this->phoneNumber])
            ->andFilterWhere(['like', 'Text', $this->Text])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
