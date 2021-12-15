<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Warnaddc;

/**
 * WarnaddcSearch represents the model behind the search form about `common\models\Warnaddc`.
 */
class WarnaddcSearch extends Warnaddc
{
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['Copies','KodeDDC', 'Warna', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal',], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        //$query = Warnaddc::findBySql("SELECT ID,KodeDDC,Warna,(SELECT COUNT(*) FROM collections
        //                            LEFT JOIN catalogs ON collections.Catalog_id = catalogs.id
        //                            WHERE SUBSTR(KodeDDC,1,1) COLLATE latin1_general_ci=SUBSTR(DeweyNo,1,1) COLLATE latin1_general_ci) AS Copies FROM warnaddc
        //                            ");
        $query = Warnaddc::find()->addSelect(["ID,KodeDDC,Warna,(SELECT COUNT(*) FROM collections
                                            LEFT JOIN catalogs ON collections.Catalog_id = catalogs.id
                                            WHERE SUBSTR(KodeDDC,1,1) COLLATE latin1_general_ci=SUBSTR(DeweyNo,1,1) COLLATE latin1_general_ci) AS Copies 
                                            "]);

        // $query = Warnaddc::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

         $dataProvider->setSort([
            'attributes' => [
                'KodeDDC',
                'Warna',
                'Copies',
            ]
        ]);
        
        
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'KodeDDC', $this->KodeDDC])
            ->andFilterWhere(['like', 'Warna', $this->Warna])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'Copies', $this->Copies]);

        return $dataProvider;
    }
}
