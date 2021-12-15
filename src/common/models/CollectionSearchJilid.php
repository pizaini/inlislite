<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Collections;
use leandrogehlen\querybuilder\Translator;

/**
 * CollectionSearch represents the model behind the search form about `common\models\Collections`.
 */
class CollectionSearchJilid extends Collections
{
 /*   public function rules()
    {
        return [
            [['DataBib','ID', 'NoInduk', 'Currency', 'RFID', 'Price', 'TanggalPengadaan', 'CallNumber', 'Catalog_id', 'NomorBarcode', 'Keterangan_Sumber', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal', 'QUARANTINEDBY', 'QUARANTINEDDATE', 'QUARANTINEDTERMINAL',  'EDISISERIAL', 'TANGGAL_TERBIT_EDISI_SERIAL', 'BAHAN_SERTAAN', 'KETERANGAN_LAIN', 'TGLENTRYJILID', 'IDJILID', 'NOMORPANGGILJILID', 'JILIDCREATEBY'], 'safe'],
            [[ 'Location_Library_id','Branch_id', 'Partner_id', 'Location_id', 'Rule_id', 'Category_id', 'Media_id', 'Source_id','Status_id', 'IsVerified', 'NOJILID'], 'integer'],
            [['ISREFERENSI', 'ISOPAC'], 'boolean'],
        ];
    }*/

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function collectionView($idJilid,$catId)
    {
       
        $query = Collections::find()
                ->addSelect(["collections.*", "CONCAT('<b>',catalogs.Title,'</b>','<br/>'
                        ,(CASE WHEN worksheets.ID <> 4 AND catalogs.edition IS NOT NULL AND NOT LENGTH(catalogs.edition) = 0 THEN CONCAT('<br/>',catalogs.edition) ELSE '' END)
                        ,'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher
                        ,' ',catalogs.PublishYear
                        ,(CASE WHEN catalogs.PhysicalDescription IS NOT NULL THEN CONCAT('<br/>',catalogs.PhysicalDescription) ELSE '' END)
                        ,(CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END)
                        ,'<br/>',worksheets.name
                        ) AS DataBib"])
                ->leftJoin('catalogs',' collections.Catalog_id = catalogs.id')
                ->leftJoin('worksheets',' catalogs.Worksheet_id = worksheets.id')
                ->where(['Catalog_id'=>$catId,'IDJILID'=>$idJilid]);

        
        $query->orderBy(['collections.TANGGAL_TERBIT_EDISI_SERIAL' => SORT_DESC]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'attributes' => [
                'ID', 
                'NomorBarcode', 
                'NoInduk',
                'DataBib',
                'CallNumber',
                'EDISISERIAL',
                'TANGGAL_TERBIT_EDISI_SERIAL',
            ]
        ]);
        
        
        //$this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function serialCollectionList($params,$notInId)
    {
        if(!empty($notInId))
        {
            $query = Collections::find()
                    ->addSelect(["collections.*", "CONCAT('<b>',catalogs.Title,'</b>','<br/>'
                            ,(CASE WHEN worksheets.ID <> 4 AND catalogs.edition IS NOT NULL AND NOT LENGTH(catalogs.edition) = 0 THEN CONCAT('<br/>',catalogs.edition) ELSE '' END)
                            ,'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher
                            ,' ',catalogs.PublishYear
                            ,(CASE WHEN catalogs.PhysicalDescription IS NOT NULL THEN CONCAT('<br/>',catalogs.PhysicalDescription) ELSE '' END)
                            ,(CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END)
                            ,'<br/>',worksheets.name
                            ) AS DataBib"])
                    ->leftJoin('catalogs',' collections.Catalog_id = catalogs.id')
                    ->leftJoin('worksheets',' catalogs.Worksheet_id = worksheets.id')
                    ->joinWith('source')
                    ->joinWith('media')
                    ->joinWith('category')
                    ->joinWith('rule')
                    ->joinWith('location')
                    ->joinWith('locationLibrary')
                    ->joinWith('status')
                    ->where(['and','catalogs.Worksheet_id = 4',['not in','collections.ID',$notInId]]);
        }else{
            $query = Collections::find()
                    ->addSelect(["collections.*", "CONCAT('<b>',catalogs.Title,'</b>','<br/>'
                            ,(CASE WHEN worksheets.ID <> 4 AND catalogs.edition IS NOT NULL AND NOT LENGTH(catalogs.edition) = 0 THEN CONCAT('<br/>',catalogs.edition) ELSE '' END)
                            ,'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher
                            ,' ',catalogs.PublishYear
                            ,(CASE WHEN catalogs.PhysicalDescription IS NOT NULL THEN CONCAT('<br/>',catalogs.PhysicalDescription) ELSE '' END)
                            ,(CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END)
                            ,'<br/>',worksheets.name
                            ) AS DataBib"])
                    ->leftJoin('catalogs',' collections.Catalog_id = catalogs.id')
                    ->leftJoin('worksheets',' catalogs.Worksheet_id = worksheets.id')
                    ->joinWith('source')
                    ->joinWith('media')
                    ->joinWith('category')
                    ->joinWith('rule')
                    ->joinWith('location')
                    ->joinWith('locationLibrary')
                    ->joinWith('status')
                    ->where(['catalogs.Worksheet_id'=>4]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'ID', 
                'NomorBarcode', 
                'NoInduk',
                'RFID',
                'DataBib',
                'CallNumber',
                'EDISISERIAL',
                'TANGGAL_TERBIT_EDISI_SERIAL',
                'IDJILID',
                'NOMORPANGGILJILID',
            ]
        ]);
        
        
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function advancedSearch($rules)
    {
        $sql = 'SELECT catalogs.*,Catalog_id,IDJILID,
            SUBSTR(IDJILID,6,4) AS TahunJilid,
            NOMORPANGGILJILID,
            Eksemplar,
            (SELECT CONCAT(\'<div style=width:450px >\',\'<b>\',catalogs.Title,\'</b>\',\'<br/>\',\'<br/>\',\'Penerbitan : \',catalogs.PublishLocation,\' \',catalogs.Publisher,\' \',catalogs.PublishYear,\'</div>\') FROM catalogs WHERE a.Catalog_ID = catalogs.ID) AS DataBib 
            FROM (
                SELECT IDJilid AS IDJILID,
                NomorPanggilJilid AS NOMORPANGGILJILID,
                Catalog_id,
                COUNT(*) AS Eksemplar 
                FROM collections  
                INNER JOIN catalogs ON collections.Catalog_ID=catalogs.ID  
                WHERE 1=1  
                AND IDJilid IS NOT NULL 
                AND catalogs.Worksheet_id=4 
                GROUP BY IDJilid,NomorPanggilJilid,Catalog_ID  ORDER BY IDJilid
                ) a LEFT JOIN catalogs ON a.Catalog_id=catalogs.ID';
        if($rules)
        {
            //bypass when type date
            $rules=\common\components\Helpers::changeRulesDateAdvanceSearch($rules);
            $translator = new Translator($rules);
            $sql .= ' WHERE ' . $translator->where();
            $query =  Collections::findBySql($sql,$translator->params());
        }else{
             $query = Collections::findBySql($sql);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'attributes' => [
                'IDJILID', 
                'TahunJilid', 
                'NOMORPANGGILJILID',
                'Eksemplar',
                'DataBib'
            ]
        ]);
        return $dataProvider;
    }

   
}
