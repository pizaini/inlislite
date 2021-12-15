<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\KriteriaKoleksi;

/**
 * CollectionmediaSearch represents the model behind the search form about `common\models\Collectionmedias`.
 */
class KoleksiUnggulanSearch extends KriteriaKoleksi
{
   
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['jns_kriteria','catalog_id', 'title', 'author', 'alamat_image','PublishYear','Jumlah','worksheet_name', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }


    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = KriteriaKoleksi::find();
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'ID',
                'jns_kriteria',
                'title',
                'author',
                'PublishYear',
                'Worksheer_name',
                
            ]
        ]);
        
        
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'ID' => $this->ID,
            'jns_kriteria' => 'koleksi_unggul',
            //'IsDelete' => $this->IsDelete,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);


        return $dataProvider;
    }
    public function searchLKD($params)
    {
        $query = KriteriaKoleksi::find();
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'ID',
                'jns_kriteria',
                'title',
                'author',
                'PublishYear',
                'Worksheer_name',
                
            ]
        ]);
        
        
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'ID' => $this->ID,
            'jns_kriteria' => 'koleksi_unggul',
            'isLKD' => '1',
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);


        return $dataProvider;
    }
}
