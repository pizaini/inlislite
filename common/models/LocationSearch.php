<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Locations;

/**
 * LocationSearch represents the model behind the search form about `common\models\Locations`.
 */
class LocationSearch extends Locations
{
    public $LocationLibrary;
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['Copies','LocationLibrary','Code', 'Name', 'Description', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
            [['ISPUSTELING'], 'boolean'],
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
        $queryCollections = Collections::find()
                ->select('Location_id,count(ID) AS Copies')
                ->groupby('Location_id');
        $query->leftJoin(['collectionCount' => $queryCollections],' collectionCount.Location_id = locations.id');
        $query->leftJoin('location_library','location_library.ID = locations.LocationLibrary_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'ID',
                'Code',
                'Name',
                'Description',
                'Copies' => [
                    'asc' => ['collectionCount.Copies' => SORT_ASC],
                    'desc' => ['collectionCount.Copies' => SORT_DESC],
                    'label' => 'Jumlah Koleksi',
                    'default' => SORT_ASC
                ],
                'LocationLibrary' => [
                    'asc' => ['location_library.Name' => SORT_ASC],
                    'desc' => ['location_library.Name' => SORT_DESC],
                    'label' => 'Jumlah Koleksi',
                    'default' => SORT_ASC
                ],
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            //'IsDelete' => $this->IsDelete,
            'ISPUSTELING' => $this->ISPUSTELING,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'locations.Code', $this->Code])
            ->andFilterWhere(['like', 'locations.Name', $this->Name])
            ->andFilterWhere(['like', 'Description', $this->Description])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'collectionCount.Copies', $this->Copies])
            ->andFilterWhere(['like', 'location_library.Name', $this->LocationLibrary]);

        return $dataProvider;
    }
}
