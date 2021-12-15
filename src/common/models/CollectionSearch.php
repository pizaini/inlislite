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
class CollectionSearch extends Collections
{

    public $Worksheet_id;

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


    public  function  searchBooking($params){

        $query = Collections::find();
        /*$queryHistoriLoan->Where([
            'member_id' => $modelAnggota->ID,
        ]);*/
        $query->Where(['not', ['BookingMemberID' => null]]);
        $query->andWhere(['>=','BookingExpiredDate',date('Y-m-d H:i:s')]);
        $query->orderBy(['ID' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'pagination' => false,
            /*'sort' =>[
                'defaultOrder' => [
                    'ID' => SORT_DESC
                ]
            ],*/
        ]);

        return $dataProvider;
    }

    public function advancedSearchByCatalogId($id,$rules)
    {
        $query = Collections::find()
                ->addSelect(["collections.*", "CONCAT('<div style=width:300px >','<b>',catalogs.Title,'</b>','<br/>'
                        ,(CASE WHEN worksheets.ID <> 4 AND catalogs.edition IS NOT NULL AND NOT LENGTH(catalogs.edition) = 0 THEN CONCAT('<br/>',catalogs.edition) ELSE '' END)
                        ,'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher
                        ,' ',catalogs.PublishYear
                        ,(CASE WHEN catalogs.PhysicalDescription IS NOT NULL THEN CONCAT('<br/>',catalogs.PhysicalDescription) ELSE '' END)
                        ,(CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END)
                        ,'<br/>',worksheets.name,'</div>'
                        ) AS DataBib"])
                ->leftJoin('catalogs',' collections.Catalog_id = catalogs.id')
                ->leftJoin('worksheets',' catalogs.Worksheet_id = worksheets.id')
                ->joinWith('source')
                ->joinWith('media')
                // ->joinWith('catalog')
                ->joinWith('category')
                ->joinWith('rule')
                ->joinWith('location')
                ->joinWith('locationLibrary')
                ->joinWith('status');
        $query->where(['Catalog_Id'=>$id]);
        // $query->orderBy(['collections.ID' => SORT_DESC]);

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
            'defaultOrder' => [
                'ID' => SORT_DESC,
            ],
            'attributes' => [
                'ID', 
                'NomorBarcode', 
                'TanggalPengadaan',
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

    public function advancedSearch($keranjang,$rules)
    {
        $query = Collections::find()
                ->addSelect(["collections.*", "CONCAT('<div style=width:300px >','<b>',catalogs.Title,'</b>','<br/>'
                        ,(CASE WHEN worksheets.ID <> 4 AND catalogs.edition IS NOT NULL AND NOT LENGTH(catalogs.edition) = 0 THEN CONCAT('<br/>',catalogs.edition) ELSE '' END)
                        ,'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher
                        ,' ',catalogs.PublishYear
                        ,(CASE WHEN catalogs.PhysicalDescription IS NOT NULL THEN CONCAT('<br/>',catalogs.PhysicalDescription) ELSE '' END)
                        ,(CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END)
                        ,'<br/>',worksheets.name,'</div>'
                        ) AS DataBib"])
                // ->addSelect([""])
                ->leftJoin('catalogs',' collections.Catalog_id = catalogs.id')
                ->leftJoin('worksheets',' catalogs.Worksheet_id = worksheets.id')
                ->leftJoin('users usr_create',' usr_create.ID = collections.CreateBy')
                ->leftJoin('users usr_update',' usr_update.ID = collections.UpdateBy')
                ->joinWith('source')
                ->joinWith('media')
                ->joinWith('category')
                ->joinWith('rule')
                ->joinWith('location')
                ->joinWith('locationLibrary')
                ->joinWith('status');
        if($keranjang==1)
        {
            $query->innerJoin('keranjang_koleksi',' keranjang_koleksi.Collection_id=collections.ID AND keranjang_koleksi.CreateBy='.(string)Yii::$app->user->identity->ID);
            $query->where(['keranjang_koleksi.CreateBy'=>(string)Yii::$app->user->identity->ID]);
        }
        // $query->orderBy(['collections.ID' => SORT_DESC]);
        
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
            'defaultOrder' => [
                'ID' => SORT_DESC,
            ],
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
                'usernameCreate',
                
                
            ]
        ]);
        
        
        return $dataProvider;
    }

    public function advancedSearchDeposit($rules)
    {
        $query = Collections::find()
                ->addSelect(["collections.*", "CONCAT('<div style=width:300px >','<b>',catalogs.Title,'</b>','<br/>'
                        ,(CASE WHEN worksheets.ID <> 4 AND catalogs.edition IS NOT NULL AND NOT LENGTH(catalogs.edition) = 0 THEN CONCAT('<br/>',catalogs.edition) ELSE '' END)
                        ,'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher
                        ,' ',catalogs.PublishYear
                        ,(CASE WHEN catalogs.PhysicalDescription IS NOT NULL THEN CONCAT('<br/>',catalogs.PhysicalDescription) ELSE '' END)
                        ,(CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END)
                        ,'<br/>',worksheets.name,'</div>'
                        ) AS DataBib"])
                // ->addSelect([""])
                ->leftJoin('catalogs',' collections.Catalog_id = catalogs.id')
                ->leftJoin('worksheets',' catalogs.Worksheet_id = worksheets.id')
                ->leftJoin('users usr_create',' usr_create.ID = collections.CreateBy')
                ->leftJoin('users usr_update',' usr_update.ID = collections.UpdateBy')
                ->joinWith('source')
                ->joinWith('media')
                ->joinWith('category')
                ->joinWith('rule')
                ->joinWith('location')
                ->joinWith('locationLibrary')
                ->joinWith('status')
                ->where(['collections.IsDeposit' => '1']);


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
            'defaultOrder' => [
                'ID' => SORT_DESC,
            ],
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
                'usernameCreate',
                
                
            ]
        ]);
        
        
        return $dataProvider;
    }

    public function search($keranjang,$params)
    {
        $query = Collections::find()
                ->addSelect(["collections.*", "CONCAT('<div style=width:300px >','<b>',catalogs.Title,'</b>','<br/>'
                        ,(CASE WHEN worksheets.ID <> 4 AND catalogs.edition IS NOT NULL AND NOT LENGTH(catalogs.edition) = 0 THEN CONCAT('<br/>',catalogs.edition) ELSE '' END)
                        ,'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher
                        ,' ',catalogs.PublishYear
                        ,(CASE WHEN catalogs.PhysicalDescription IS NOT NULL THEN CONCAT('<br/>',catalogs.PhysicalDescription) ELSE '' END)
                        ,(CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END)
                        ,'<br/>',worksheets.name,'</div>'
                        ) AS DataBib"])
                ->leftJoin('catalogs',' collections.Catalog_id = catalogs.id')
                ->leftJoin('worksheets',' catalogs.Worksheet_id = worksheets.id')
                ->joinWith('source')
                ->joinWith('media')
                ->joinWith('category')
                ->joinWith('rule')
                ->joinWith('location')
                ->joinWith('locationLibrary')
                ->joinWith('status');
        if($keranjang==1)
        {
            $query->innerJoin('keranjang_koleksi',' keranjang_koleksi.Collection_id=collections.ID AND keranjang_koleksi.CreateBy='.(string)Yii::$app->user->identity->ID);
        }
        $query->orderBy(['catalogs.CreateDate' => SORT_DESC]);
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
        
        
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        

        $query->andFilterWhere([
            'TanggalPengadaan' => $this->TanggalPengadaan,
            //'IsDelete' => $this->IsDelete,
            'catalogs.Worksheet_id' => $this->Worksheet_id,
            'Branch_id' => $this->Branch_id,
            'Partner_id' => $this->Partner_id,
            'Location_id' => $this->Location_id,
            'Location_Library_id' => $this->Location_Library_id,
            'Rule_id' => $this->Rule_id,
            'Category_id' => $this->Category_id,
            'Media_id' => $this->Media_id,
            'Source_id' => $this->Source_id,
            'Status_id' => $this->Status_id,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
            'IsVerified' => $this->IsVerified,
            'QUARANTINEDDATE' => $this->QUARANTINEDDATE,
            'ISREFERENSI' => $this->ISREFERENSI,
            'NOJILID' => $this->NOJILID,
            'TANGGAL_TERBIT_EDISI_SERIAL' => $this->TANGGAL_TERBIT_EDISI_SERIAL,
            'TGLENTRYJILID' => $this->TGLENTRYJILID,
            'ISOPAC' => $this->ISOPAC,
        ]);

        $query->andFilterWhere(['like', 'ID', $this->ID])
            ->andFilterWhere(['like', 'NoInduk', $this->NoInduk])
            ->andFilterWhere(['like', 'Currency', $this->Currency])
            ->andFilterWhere(['like', 'RFID', $this->RFID])
            ->andFilterWhere(['like', 'Price', $this->Price])
            ->andFilterWhere(['like', 'CallNumber', $this->CallNumber])
            ->andFilterWhere(['like', 'Catalog_id', $this->Catalog_id])
            //->andFilterWhere(['like', 'GroupingNumber', $this->GroupingNumber])
            ->andFilterWhere(['like', 'NomorBarcode', $this->NomorBarcode])
            ->andFilterWhere(['like', 'Keterangan_Sumber', $this->Keterangan_Sumber])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'QUARANTINEDBY', $this->QUARANTINEDBY])
            ->andFilterWhere(['like', 'QUARANTINEDTERMINAL', $this->QUARANTINEDTERMINAL])
            //->andFilterWhere(['like', 'STATUSAKUISISI', $this->STATUSAKUISISI])
            ->andFilterWhere(['like', 'EDISISERIAL', $this->EDISISERIAL])
            ->andFilterWhere(['like', 'BAHAN_SERTAAN', $this->BAHAN_SERTAAN])
            ->andFilterWhere(['like', 'KETERANGAN_LAIN', $this->KETERANGAN_LAIN])
            ->andFilterWhere(['like', 'IDJILID', $this->IDJILID])
            ->andFilterWhere(['like', 'NOMORPANGGILJILID', $this->NOMORPANGGILJILID])
            ->andFilterWhere(['like', 'JILIDCREATEBY', $this->JILIDCREATEBY])
            ->andFilterWhere(['like', 'DataBib', $this->DataBib]);
        return $dataProvider;
    }
}
