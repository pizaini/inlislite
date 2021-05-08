<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Modules;

/**
 * JenisPerpustakaanSearch represents the model behind the search form about `common\models\JenisPerpustakaan`.
 */
class ModulesSearch extends Modules
{
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['Name', 'URL', 'SortNo', 'Application_id', 'ClassName', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Modules::find();

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
            ->andFilterWhere(['like', 'URL', $this->URL])
            ->andFilterWhere(['like', 'SortNo', $this->SortNo])
                ->andFilterWhere(['like', 'Application_id', $this->Application_id])
                ->andFilterWhere(['like', 'ClassName', $this->ClassName])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }


}
