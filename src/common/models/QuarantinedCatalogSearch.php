<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\QuarantinedCatalogs;
use leandrogehlen\querybuilder\Translator;

/**
 * CatalogSearch represents the model behind the search form about `common\models\Catalogs`.
 */
class QuarantinedCatalogSearch extends QuarantinedCatalogs
{

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function advancedSearch($rules){
        $query = QuarantinedCatalogs::find()
        ->addSelect([
            'quarantined_catalogs.ID',
            'CONCAT("<div style=width:120px >",quarantined_catalogs.BIBID,"</div>") AS BIBID',
            'CONCAT("<div style=width:250px >",quarantined_catalogs.Title,"</div>") AS Title',
            'quarantined_catalogs.Author',
            'quarantined_catalogs.Edition',
            'quarantined_catalogs.PhysicalDescription',
            'quarantined_catalogs.Subject',
            'quarantined_catalogs.CallNumber',
            'quarantined_catalogs.Worksheet_id',
            'CONCAT_WS(" ",quarantined_catalogs.PublishLocation,
                quarantined_catalogs.Publisher,
                quarantined_catalogs.PublishYear
                ) AS Publishment'
        ]);
        // $queryCollections = Collections::find()
        //         ->select('Catalog_id,count(ID) AS Eksemplar')
        //         ->groupby('Catalog_id');
        // $queryCatalogfiles = Catalogfiles::find()
        //         ->select('Catalog_id,count(ID) AS KontenDigital, (CASE WHEN count(ID) > 0 THEN 1 ELSE 0 END) AS PunyaKontenDigital')
        //         ->groupby('Catalog_id');
        // $query->leftJoin(['collectionCount' => $queryCollections],' collectionCount.Catalog_id = quarantined_catalogs.ID');
        // $query->leftJoin(['catalogfilesCount' => $queryCatalogfiles],' catalogfilesCount.Catalog_id = quarantined_catalogs.ID');
        $query->orderBy(['quarantined_catalogs.CreateDate' => SORT_DESC]);
         
        if ($rules) {
            //bypass when type date
            $rules=\common\components\Helpers::changeRulesDateAdvanceSearch($rules);
            $translator = new Translator($rules);
            $query
                ->andWhere($translator->where())
                ->addParams($translator->params());
        }

          $dataProvider = new ActiveDataProvider([
              'query' => $query,
          ]);

          $dataProvider->setSort([
            'attributes' => [
                'BIBID', 
                'Title',  
                'Author', 
                'Edition',
                'Publishment',
                'PhysicalDescription',
                'Subject',
                'CallNumber',
               /* 'KontenDigital' => [
                    'asc' => ['catalogfilesCount.KontenDigital' => SORT_ASC],
                    'desc' => ['catalogfilesCount.KontenDigital' => SORT_DESC],
                    'label' => Yii::t('app', 'KontenDigital'),
                    'default' => SORT_ASC
                ],
                'Eksemplar' => [
                    'asc' => ['collectionCount.Eksemplar' => SORT_ASC],
                    'desc' => ['collectionCount.Eksemplar' => SORT_DESC],
                    'label' => Yii::t('app', 'Eksemplar'),
                    'default' => SORT_ASC
                ],*/
            ]
        ]);
      //$query->joinWith('collections');
      return $dataProvider;
    }

    public function search($params)
    {
        $query = QuarantinedCatalogs::find()
        ->addSelect([
            'quarantined_catalogs.ID',
            'quarantined_catalogs.BIBID',
            'quarantined_catalogs.Title',
            'quarantined_catalogs.Edition',
            'quarantined_catalogs.PhysicalDescription',
            'quarantined_catalogs.Subject',
            'quarantined_catalogs.CallNumber',
            'CONCAT_WS(" ",quarantined_catalogs.PublishLocation,
                quarantined_catalogs.Publisher,
                quarantined_catalogs.PublishYear
                ) AS Publishment'
        ]);
        /*$queryCollections = Collections::find()
                ->select('Catalog_id,count(ID) AS JumlahKoleksi')
                ->groupby('Catalog_id');
        $queryCatalogfiles = Catalogfiles::find()
                ->select('Catalog_id,count(ID) AS JumlahKontenDigital')
                ->groupby('Catalog_id');
        $query->leftJoin(['collectionCount' => $queryCollections],' collectionCount.Catalog_id = quarantined_catalogs.ID');
        $query->leftJoin(['catalogfilesCount' => $queryCatalogfiles],' catalogfilesCount.Catalog_id = quarantined_catalogs.ID');*/

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'BIBID', 
                'Title', 
                'Edition',
                'Publishment',
                'PhysicalDescription',
                'Subject',
                'CallNumber',
                'JumlahKontenDigital',
                'JumlahKoleksi'
            ]
        ]);
        
        
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        

        $query->andFilterWhere([
            'BIBID'=> $this->$BIBID, 
            'Title'=> $this->$Title, 
            'Edition'=> $this->$Edition,
            'Publishment'=> $this->$Publishment,
            'PhysicalDescription'=> $this->$PhysicalDescription,
            'Subject'=> $this->$Subject,
            'CallNumber'=> $this->$CallNumber,
            /*'JumlahKontenDigital'=> $this->JumlahKontenDigital,
            'JumlahKoleksi'=> $this->JumlahKoleksi*/
        ]);

        $query->andFilterWhere(['like', 'BIBID', $this->BIBID])
            ->andFilterWhere(['like', 'Title', $this->Title])
            ->andFilterWhere(['like', 'Edition', $this->Edition])
            ->andFilterWhere(['like', 'Publishment', $this->Publishment])
            ->andFilterWhere(['like', 'PhysicalDescription', $this->PhysicalDescription])
            ->andFilterWhere(['like', 'Subject', $this->Subject])
            ->andFilterWhere(['like', 'CallNumber', $this->CallNumber])
            /*->andFilterWhere(['like', 'JumlahKontenDigital', $this->JumlahKontenDigital])
            ->andFilterWhere(['like', 'JumlahKoleksi', $this->JumlahKoleksi])*/;
        return $dataProvider;
    }

}
