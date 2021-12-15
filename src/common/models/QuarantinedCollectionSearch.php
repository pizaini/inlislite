<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\QuarantinedCollections;
use leandrogehlen\querybuilder\Translator;

/**
 * QuarantinedCollectionSearch represents the model behind the search form about `common\models\QuarantinedCollections`.
 */
class QuarantinedCollectionSearch extends QuarantinedCollections
{
    public function rules()
    {
        return [
            [['DataBib','ID', 'NoInduk', 'Currency', 'RFID', 'Price', 'TanggalPengadaan', 'CallNumber', 'Catalog_id', 'NomorBarcode', 'Keterangan_Sumber', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal', 'QUARANTINEDBY', 'QUARANTINEDDATE', 'QUARANTINEDTERMINAL',  'EDISISERIAL', 'TANGGAL_TERBIT_EDISI_SERIAL', 'BAHAN_SERTAAN', 'KETERANGAN_LAIN', 'TGLENTRYJILID', 'IDJILID', 'NOMORPANGGILJILID', 'JILIDCREATEBY'], 'safe'],
            [[ 'Location_Library_id','Branch_id', 'Partner_id', 'Location_id', 'Rule_id', 'Category_id', 'Media_id', 'Source_id','Status_id', 'IsVerified', 'NOJILID'], 'integer'],
            [['ISREFERENSI', 'ISOPAC'], 'boolean'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function advancedSearch($rules)
    {
        $query = QuarantinedCollections::find()
                ->addSelect(["quarantined_collections.*", "CONCAT('<div style=width:300px >','<b>',catalogs.Title,'</b>','<br/>'
                        ,(CASE WHEN worksheets.ID <> 4 AND catalogs.edition IS NOT NULL AND NOT LENGTH(catalogs.edition) = 0 THEN CONCAT('<br/>',catalogs.edition) ELSE '' END)
                        ,'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher
                        ,' ',catalogs.PublishYear
                        ,(CASE WHEN catalogs.PhysicalDescription IS NOT NULL THEN CONCAT('<br/>',catalogs.PhysicalDescription) ELSE '' END)
                        ,(CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END)
                        ,'<br/>',worksheets.name,'</div>'
                        ) AS DataBib"])
                ->leftJoin('catalogs',' quarantined_collections.Catalog_id = catalogs.id')
                ->leftJoin('worksheets',' catalogs.Worksheet_id = worksheets.id')
                ->joinWith('source')
                ->joinWith('media')
                ->joinWith('category')
                ->joinWith('rule')
                ->joinWith('location')
                ->joinWith('locationLibrary')
                ->joinWith('status');

        if ($rules) {
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
                'ID', 
                'TanggalPengadaan',
                'NomorBarcode', 
                'NoInduk',
                'RFID',
                'DataBib',
                'Source_id' => [
                    'asc' => ['collectionsources.name' => SORT_ASC],
                    'desc' => ['collectionsources.name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'Media_id' => [
                    'asc' => ['collectionmedias.name' => SORT_ASC],
                    'desc' => ['collectionmedias.name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'Category_id' => [
                    'asc' => ['collectioncategorys.name' => SORT_ASC],
                    'desc' => ['collectioncategorys.name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'Rule_id' => [
                    'asc' => ['collectionrules.name' => SORT_ASC],
                    'desc' => ['collectionrules.name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'Status_id' => [
                    'asc' => ['collectionstatus.name' => SORT_ASC],
                    'desc' => ['collectionstatus.name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'Location_Library_id' => [
                    'asc' => ['location_library.name' => SORT_ASC],
                    'desc' => ['location_library.name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'Location_id' => [
                    'asc' => ['locations.name' => SORT_ASC],
                    'desc' => ['locations.name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'ISOPAC',
                
                
            ]
        ]);
        
        
       
        return $dataProvider;
    }
}
