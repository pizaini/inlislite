<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Locations;

/**
 * LocationsSearch represents the model behind the search form about `common\models\Locations`.
 */
class LocationsSearch extends Locations
{
    public function rules()
    {
        return [
            [['ID', 'IsDelete', 'ISPUSTELING', 'IsPrintBarcode', 'IsGenerateVisitorNumber', 'IsInformationSought', 'IsVisitsDestination'], 'integer'],
            [['Code', 'Name', 'Description', 'UrlLogo', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Locations::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'IsDelete' => $this->IsDelete,
            'ISPUSTELING' => $this->ISPUSTELING,
            'IsPrintBarcode' => $this->IsPrintBarcode,
            'IsGenerateVisitorNumber' => $this->IsGenerateVisitorNumber,
            'IsInformationSought' => $this->IsInformationSought,
            'IsVisitsDestination' => $this->IsVisitsDestination,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'Code', $this->Code])
            ->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'Description', $this->Description])
            ->andFilterWhere(['like', 'UrlLogo', $this->UrlLogo])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
