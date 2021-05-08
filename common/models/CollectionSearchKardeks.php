<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Collections;
use common\models\Catalogs;
use leandrogehlen\querybuilder\Translator;
use yii\data\SqlDataProvider;
/**
 * CollectionSearch represents the model behind the search form about `common\models\Collections`.
 */
class CollectionSearchKardeks extends Collections
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

    public function advancedSearch($rules)
    {
        $query = Catalogs::find()
        ->addSelect([
            'catalogs.ID',
            'CONCAT("<div style=width:120px >",catalogs.BIBID,"</div>") AS BIBID',
            'CONCAT("<div style=width:300px >",catalogs.Title,"</div>") AS Title',
            'catalogs.Author',
            'catalogs.Edition',
            'catalogs.PhysicalDescription',
            'catalogs.Subject',
            'catalogs.CallNumber',
            'catalogs.ControlNumber',
            'catalogs.PublishLocation',
            'catalogs.Publisher',
            'catalogs.PublishYear',
            'catalogs.ISBN',
            'collectionCount.Eksemplar',
            'collectionSerialCount.JumlahEdisiSerial'
        ])->where(['worksheets.ISSERIAL'=>1]);
        $queryCollectionsSerialDistinct = Collections::find()
                ->select('Catalog_id,EDISISERIAL,TANGGAL_TERBIT_EDISI_SERIAL')
                ->distinct();

        $queryCollectionsSerial = Collections::find()
                ->select('Catalog_id,count(*) AS JumlahEdisiSerial')
                ->from(['collections'=>$queryCollectionsSerialDistinct])
                ->where(['not', ['EDISISERIAL' => null]])
                ->andWhere(['not', ['EDISISERIAL' => '']])
                ->groupby(['Catalog_id']);

        $queryCollections = Collections::find()
                ->select('Catalog_id,count(ID) AS Eksemplar')
                ->groupby('Catalog_id');

        $queryWorksheets = Worksheets::find()->select('ID,ISSERIAL');

        $query->leftJoin(['collectionCount' => $queryCollections],' collectionCount.Catalog_id = catalogs.ID');
        $query->leftJoin(['collectionSerialCount' => $queryCollectionsSerial],' collectionSerialCount.Catalog_id = catalogs.ID');
        $query->leftJoin(['worksheets' => $queryWorksheets],' worksheets.ID = catalogs.Worksheet_id');
        $query->orderBy('catalogs.CreateDate DESC');

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
                'ID', 
                'BIBID', 
                'Title',
                'Author',
                'PublishLocation',
                'Publisher',
                'PublishYear',
                'JumlahEdisiSerial',
                'Eksemplar'
            ]
        ]);
        
        
       

        return $dataProvider;
    }

    
    public function search($params)
    {
        $sql = 'SELECT 
        ID,
        BIBID,
        Title,
        Author,
        PublishLocation,
        Publisher,
        PublishYear,
        (SELECT COUNT(*) FROM collections WHERE Catalog_ID=catalogs.ID AND EdisiSerial IS NOT NULL) AS JumlahEdisiSerial, 
        (SELECT COUNT(*) FROM collections WHERE Catalog_ID=catalogs.ID ) AS JumlahKoleksi 
        FROM catalogs  WHERE catalogs.Worksheet_id=4';
        $query = Catalogs::findBySql($sql);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'ID', 
                'BIBID', 
                'Title',
                'Author',
                'PublishLocation',
                'Publisher',
                'PublishYear',
                'JumlahEdisiSerial',
                'JumlahKoleksi'
            ]
        ]);
        
        
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function search2($params)
    {
        $CatalogId = $params['CatalogId'];
        $sql = 'SELECT
        CONCAT(Catalog_id,@rownum := @rownum + 1) AS Edisi_id,  
        Catalog_id,
        EDISISERIAL,
        TANGGAL_TERBIT_EDISI_SERIAL,
        COUNT(*) AS Eksemplar 
        FROM collections,
        (SELECT @rownum := 0) r WHERE Catalog_id='.$CatalogId.' 
        GROUP BY Catalog_id,
        EDISISERIAL,
        TANGGAL_TERBIT_EDISI_SERIAL
        ORDER BY EDISISERIAL DESC
        ';


        $query = Collections::findBySql($sql);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        /*$dataProvider->setSort([
            'attributes' => [
                'Catalog_id',
                'EDISISERIAL', 
                'TANGGAL_TERBIT_EDISI_SERIAL', 
                'JumlahEksemplar'
            ]
        ]);*/

        $dataProvider->setSort(false);
        
        
        //$this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function search3($params)
    {
        $CatalogId = $params['CatalogId'];
        $EdisiSerial = $params['EdisiSerial'];
        $query = Collections::find()->where(['Catalog_id'=>$CatalogId,'EdisiSerial'=>$EdisiSerial]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false
        ]);

        /*$dataProvider->setSort([
            'attributes' => [
                'ID', 
                'NomorBarcode', 
                'NoInduk', 
                'Source_Id', 
                'Media_Id', 
                'Category_id', 
                'TanggalPengadaan',
                'Price'
            ]
        ]);*/

        $dataProvider->setSort(false);
        
        //$this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
    public function search4($params)
    {
        $CatalogId = $params['CatalogId'];
        $EdisiSerial = $params['EdisiSerial'];
        //$var_is_greater_than_two = ($var > 2 ? true : false);
        $q = ($EdisiSerial=='' ? $q=" c.EdisiSerial is null;" : $q="c.EdisiSerial='".$EdisiSerial."';" );
        //if($EdisiSerial==''){$q=" c.EdisiSerial is null";} else {$q="c.EdisiSerial='".$EdisiSerial;} 
        $sqlSearch = "SELECT c.id,c.Catalog_id, m.Name media,c.NomorBarcode, c.NoInduk, c.CallNumber, r.Name akses, Concat(CONCAT(loc.Name,'- '),l.Name) lokasi, s.Name ketersediaan, c.BookingMemberID, c.BookingExpiredDate
            FROM collections c 
            LEFT JOIN collectionmedias m ON c.Media_id=m.ID 
            LEFT JOIN collectionrules r ON c.Rule_id=r.ID 
            LEFT JOIN collectionstatus s ON c.Status_id=s.ID   
            LEFT JOIN locations l ON c.Location_id=l.ID
            LEFT join location_library loc on c.Location_Library_id = loc.id
            WHERE c.Catalog_id=".$CatalogId." and ".$q;
        $dataProvider = new SqlDataProvider([
                'sql' => $sqlSearch,
                'pagination' => false,
                    //'db' =>  Yii::$app->db2,
                    //'pagination' => [ 'pageSize' => 20,],
            ]);
        //echo"<pre>"; print_r($sqlSearch); echo"</pre>"; die;

        $dataProvider->setSort(false);
        
        //$this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
