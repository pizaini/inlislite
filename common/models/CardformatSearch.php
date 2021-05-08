<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Cardformats;

/**
 * CardformatSearch represents the model behind the search form about `common\models\Cardformats`.
 */
class CardformatSearch extends Cardformats
{
    public function rules()
    {
        return [
            [['ID', 'Width', 'Height', 'FontSize', 'IsDelete'], 'integer'],
            [['Name', 'FontName', 'FormatTeks', 'FormatTeksNoAuthor', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Cardformats::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'Width' => $this->Width,
            'Height' => $this->Height,
            'FontSize' => $this->FontSize,
            'IsDelete' => $this->IsDelete,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'FontName', $this->FontName])
            ->andFilterWhere(['like', 'FormatTeks', $this->FormatTeks])
            ->andFilterWhere(['like', 'FormatTeksNoAuthor', $this->FormatTeksNoAuthor])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
