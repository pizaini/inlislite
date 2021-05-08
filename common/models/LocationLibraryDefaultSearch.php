<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LocationLibraryDefault;

/**
 * LocationLibraryDefaultSearch represents the model behind the search form about `common\models\LocationLibraryDefault`.
 */
class LocationLibraryDefaultSearch extends LocationLibraryDefault
{
    public function rules()
    {
        return [
            [['ID', 'Location_Library_id', 'JenisAnggota_id'], 'integer'],
            [['CreateBy', 'CreateDate', 'CreateTeminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = LocationLibraryDefault::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'Location_Library_id' => $this->Location_Library_id,
            'JenisAnggota_id' => $this->JenisAnggota_id,
            'CreateDate' => $this->CreateDate,
        ]);

        $query->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTeminal', $this->CreateTeminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateDate', $this->UpdateDate])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
