<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MasterLoker;

/**
 * MasterLokerSearch represents the model behind the search form about `common\models\MasterLoker`.
 */
class MasterLokerSearch extends MasterLoker
{

    public $locationsName;
    public $libraryName;
    public $libraryID;

    public function rules()
    {
        return [
            [['ID', 'locations_id'], 'integer'],
            [['No', 'Name', 'status', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal','locationsName','libraryID','libraryName'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = MasterLoker::find()->leftJoin('locations', 'locations.ID = master_loker.locations_id')->leftJoin('location_library', 'locations.LocationLibrary_id = location_library.ID');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'No',
                'Name',
                'status',
                'locationsName' => 
                [
                    'asc' => ['locations.Name' => SORT_ASC],
                    'desc' => ['locations.Name' => SORT_DESC],
                    'label' => Yii::t('app', 'Lokasi'),
                    'default' => SORT_ASC
                ],
                'libraryName' => 
                [
                    'asc' => ['location_library.Name' => SORT_ASC],
                    'desc' => ['location_library.Name' => SORT_DESC],
                    'label' => Yii::t('app', 'Lokasi Perpustakaan'),
                    'default' => SORT_ASC
                ],
            ]
        ]);


        $this->load($params);
        if (!$this->validate()) 
        {
            return $dataProvider;
        }


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'locations_id' => $this->locations_id,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere([
            'locations.LocationLibrary_id' => $this->libraryID,
        ]);

        $query->andFilterWhere(['like', 'locations.Name', $this->locationsName]);

        $query->andFilterWhere(['like', 'No', $this->No])
            ->andFilterWhere(['like', 'master_loker.Name', $this->Name])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'location_library.Name', $this->libraryName]);

        return $dataProvider;
    }
}
