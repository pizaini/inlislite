<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\KriteriaKoleksi;

/**
 * CollectionmediaSearch represents the model behind the search form about `common\models\Collectionmedias`.
 */
class KoleksiSeringDipinjamSearch extends KriteriaKoleksi
{
   
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['jns_kriteria','nomor_urut','catalog_id', 'title', 'author', 'alamat_image','PublishYear','Jumlah','worksheet_name', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
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
             'jns_kriteria' => 'koleksi_sering_dipinjam',
            //'IsDelete' => $this->IsDelete,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

       /* $query->andFilterWhere(['like', 'collectionmedias.Code', $this->Code])
            ->andFilterWhere(['like', 'collectionmedias.Name', $this->Name])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'worksheets.Name', $this->Worksheet_id])
            ->andFilterWhere(['like', 'collectionCount.Copies', $this->Copies]);*/

        return $dataProvider;
    }
}
