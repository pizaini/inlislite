<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MasterKelasBesar;

/**
 * JenisPerpustakaanSearch represents the model behind the search form about `common\models\JenisPerpustakaan`.
 */
class MasterKelasBesarSearch extends MasterKelasBesar
{
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['Copies','kdKelas', 'namakelas', 'warna', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = MasterKelasBesar::find()->addSelect(["ID,kdKelas,namakelas,warna,(SELECT COUNT(*) FROM collections
                                            LEFT JOIN catalogs ON collections.Catalog_id = catalogs.id
                                            WHERE SUBSTR(kdKelas,1,1) COLLATE latin1_general_ci=SUBSTR(DeweyNo,1,1) COLLATE latin1_general_ci) AS Copies 
                                            "]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

         $dataProvider->setSort([
            'attributes' => [
                'kdKelas',
                'namakelas',
                'warna',
                'Copies',
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $eksemplar = '(SELECT COUNT(*) FROM collections
                                LEFT JOIN catalogs ON collections.Catalog_id = catalogs.id
                                WHERE SUBSTR(kdKelas,1,1) =SUBSTR(DeweyNo,1,1))';

        $query->andFilterWhere(['like', 'kdKelas', $this->kdKelas])
            ->andFilterWhere(['like', 'namakelas', $this->namakelas])
            ->andFilterWhere(['like', 'warna', $this->warna])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
	    ->andFilterWhere(['like', $eksemplar, $this->Copies]);

        return $dataProvider;
    }


}
