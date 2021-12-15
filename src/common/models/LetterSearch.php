<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Letter;

/**
 * LetterSearch represents the model behind the search form about `common\models\Letter`.
 */
class LetterSearch extends Letter
{
    public function rules()
    {
        return [
            [['ID', 'PHONE', 'IS_PRINTED', 'CreateBy', 'UpdateBy', 'PUBLISHER_ID', 'IS_SENDEDEMAIL', 'IS_NOTE'], 'integer'],
            [['TYPE_OF_DELIVERY', 'LETTER_DATE', 'LETTER_NUMBER', 'ACCEPT_DATE', 'SENDER', 'INTENDED_TO', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'LETTER_NUMBER_UT', 'LANG'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Letter::find()
                ->joinWith('depositWs')
                ->joinWith('users');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'LETTER_DATE' => $this->LETTER_DATE,
            'ACCEPT_DATE' => $this->ACCEPT_DATE,
            'PHONE' => $this->PHONE,
            'IS_PRINTED' => $this->IS_PRINTED,
            'CreateDate' => $this->CreateDate,
            'CreateBy' => $this->CreateBy,
            'UpdateDate' => $this->UpdateDate,
            'UpdateBy' => $this->UpdateBy,
            'PUBLISHER_ID' => $this->PUBLISHER_ID,
            'IS_SENDEDEMAIL' => $this->IS_SENDEDEMAIL,
            'IS_NOTE' => $this->IS_NOTE,
        ]);

        $query->andFilterWhere(['like', 'TYPE_OF_DELIVERY', $this->TYPE_OF_DELIVERY])
            ->andFilterWhere(['like', 'LETTER_NUMBER', $this->LETTER_NUMBER])
            ->andFilterWhere(['like', 'SENDER', $this->SENDER])
            ->andFilterWhere(['like', 'INTENDED_TO', $this->INTENDED_TO])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'LETTER_NUMBER_UT', $this->LETTER_NUMBER_UT])
            ->andFilterWhere(['like', 'LANG', $this->LANG]);

        return $dataProvider;
    }
}
