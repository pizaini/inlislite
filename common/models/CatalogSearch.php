<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Users;
use common\models\Catalogs;
use common\models\Catalogfiles;
use common\models\Collections;
use leandrogehlen\querybuilder\Translator;

/**
 * CatalogSearch represents the model behind the search form about `common\models\Catalogs`.
 */
class CatalogSearch extends Catalogs
{

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function advancedSearchByMemberID($keranjang,$rules,&$jumlahJudul=0,&$jumlahEksemplar=0,$memberID){
        $query = Catalogs::find()
            ->addSelect([
                'catalogs.ID',
                'catalogs.IsRDA',
                'CONCAT("<div style=width:120px >",catalogs.BIBID,"</div>") AS BIBID',
                'CONCAT("<div style=width:250px >",catalogs.Title,"</div>") AS Title',
                'catalogs.Author',
                'catalogs.Edition',
                'catalogs.PhysicalDescription',
                'catalogs.Subject',
                'catalogs.CallNumber',
                'catalogs.Worksheet_id',
                'catalogs.Publikasi'
                /*'CONCAT_WS(" ",catalogs.PublishLocation,
                    catalogs.Publisher,
                    catalogs.PublishYear
                    ) AS Publishment'*/
            ]);
        $query->andWhere(['Member_id' => $memberID]);
        $queryCollections = Collections::find()
            ->select('Catalog_id,count(ID) AS Eksemplar')
            ->groupby('Catalog_id');
        $queryCatalogfiles = Catalogfiles::find()
            ->select('Catalog_id,count(ID) AS KontenDigital, (CASE WHEN count(ID) > 0 THEN 1 ELSE 0 END) AS PunyaKontenDigital')
            ->groupby('Catalog_id');
        $queryUsersCreate = Users::find()
            ->select('ID,username');
        if($keranjang==1)
        {
            $query->innerJoin('keranjang_katalog',' keranjang_katalog.Catalog_id=catalogs.ID');
            $query->where(['keranjang_katalog.CreateBy'=>(string)Yii::$app->user->identity->ID]);
        }
        $query->leftJoin(['collectionCount' => $queryCollections],' collectionCount.Catalog_id = catalogs.ID');
        $query->leftJoin(['catalogfilesCount' => $queryCatalogfiles],' catalogfilesCount.Catalog_id = catalogs.ID');
        $query->leftJoin(['usercreateby' => $queryUsersCreate],' usercreateby.ID = catalogs.CreateBy');
        $query->leftJoin(['userupdateby' => $queryUsersCreate],' userupdateby.ID = catalogs.UpdateBy');
        //$query->joinWith('catalogfiles');
        $query->orderBy(['catalogs.CreateDate' => SORT_DESC]);

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
                'Publikasi',
                //'Publishment',
                'PhysicalDescription',
                'Subject',
                'CallNumber',
                'KontenDigital' => [
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
                ],
            ]
        ]);

        $jumlahJudul = $query->count();
        //Proses mendapatkan jumlah eksemplar
        $queryCount = Catalogs::find()
            ->addSelect([
                'catalogs.ID',
                'CONCAT("<div style=width:120px >",catalogs.BIBID,"</div>") AS BIBID',
                'CONCAT("<div style=width:250px >",catalogs.Title,"</div>") AS Title',
                'catalogs.Author',
                'catalogs.Edition',
                'catalogs.PhysicalDescription',
                'catalogs.Subject',
                'catalogs.CallNumber',
                'catalogs.Worksheet_id',
                'catalogs.Publikasi'
                /*'CONCAT_WS(" ",catalogs.PublishLocation,
                    catalogs.Publisher,
                    catalogs.PublishYear
                    ) AS Publishment'*/
            ]);
        $queryCollections = Collections::find()
            ->select('Catalog_id,count(ID) AS Eksemplar')
            ->groupby('Catalog_id');
        $queryCatalogfiles = Catalogfiles::find()
            ->select('Catalog_id,count(ID) AS KontenDigital, (CASE WHEN count(ID) > 0 THEN 1 ELSE 0 END) AS PunyaKontenDigital')
            ->groupby('Catalog_id');
        $queryUsersCreate = Users::find()
            ->select('ID,username');
        if($keranjang==1)
        {
            $queryCount->innerJoin('keranjang_katalog',' keranjang_katalog.Catalog_id=catalogs.ID');
            $queryCount->where(['keranjang_katalog.CreateBy'=>(string)Yii::$app->user->identity->ID]);
        }
        $queryCount->leftJoin(['collectionCount' => $queryCollections],' collectionCount.Catalog_id = catalogs.ID');
        $queryCount->leftJoin(['catalogfilesCount' => $queryCatalogfiles],' catalogfilesCount.Catalog_id = catalogs.ID');
        $queryCount->leftJoin(['usercreateby' => $queryUsersCreate],' usercreateby.ID = catalogs.CreateBy');
        $queryCount->leftJoin(['userupdateby' => $queryUsersCreate],' userupdateby.ID = catalogs.UpdateBy');
        //$queryCount->joinWith('catalogfiles');
        $queryCount->orderBy(['catalogs.CreateDate' => SORT_DESC]);

        if ($rules) {
            //bypass when type date
            $rules=\common\components\Helpers::changeRulesDateAdvanceSearch($rules);
            $translator = new Translator($rules);
            $query
                ->andWhere($translator->where())
                ->addParams($translator->params());
        }

        $queryCount->rightJoin('collections',' collections.Catalog_id = catalogs.ID');
        $jumlahEksemplar = $queryCount->count();
        //End Proses mendapatkan jumlah eksemplar
        //
        return $dataProvider;
    }

    public function advancedSearch($keranjang,$rules,&$jumlahJudul=0,&$jumlahEksemplar=0){
        $query = Catalogs::find()
        ->addSelect([
            'catalogs.ID',
            'catalogs.IsRDA',
            'catalogs.Member_id',
            'catalogs.CreateBy',
            'CONCAT("<div style=width:120px >",catalogs.BIBID,"</div>") AS BIBID',
            'CONCAT("<div style=width:250px >",catalogs.Title,"</div>") AS Title',
            'catalogs.Author',
            'catalogs.Edition',
            'catalogs.PhysicalDescription',
            'catalogs.Subject',
            'catalogs.CallNumber',
            'catalogs.Worksheet_id',
            'catalogs.Publikasi'
            /*'CONCAT_WS(" ",catalogs.PublishLocation,
                catalogs.Publisher,
                catalogs.PublishYear
                ) AS Publishment'*/
        ]);
        $queryCollections = Collections::find()
                ->select('Catalog_id,count(ID) AS Eksemplar')
                ->groupby('Catalog_id');
        $queryCatalogfiles = Catalogfiles::find()
                ->select('Catalog_id,count(ID) AS KontenDigital, (CASE WHEN count(ID) > 0 THEN 1 ELSE 0 END) AS PunyaKontenDigital')
                ->groupby('Catalog_id');
        $queryUsersCreate = Users::find()
                ->select('ID,username');
        if($keranjang==1)
        {
            $query->innerJoin('keranjang_katalog',' keranjang_katalog.Catalog_id=catalogs.ID');
            $query->where(['keranjang_katalog.CreateBy'=>(string)Yii::$app->user->identity->ID]);
        }
        $query->leftJoin(['collectionCount' => $queryCollections],' collectionCount.Catalog_id = catalogs.ID');
        $query->leftJoin(['catalogfilesCount' => $queryCatalogfiles],' catalogfilesCount.Catalog_id = catalogs.ID');
        $query->leftJoin(['usercreateby' => $queryUsersCreate],' usercreateby.ID = catalogs.CreateBy');
        $query->leftJoin(['userupdateby' => $queryUsersCreate],' userupdateby.ID = catalogs.UpdateBy');
        //$query->joinWith('catalogfiles');
        $query->orderBy(['catalogs.CreateDate' => SORT_DESC]);
         
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
                'Publikasi',
                //'Publishment',
                'PhysicalDescription',
                'Subject',
                'CallNumber',
                'KontenDigital' => [
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
                ],
            ]
        ]);

        $jumlahJudul = $query->count();
        //Proses mendapatkan jumlah eksemplar
        $queryCount = Catalogs::find()
        ->addSelect([
            'catalogs.ID',
            'CONCAT("<div style=width:120px >",catalogs.BIBID,"</div>") AS BIBID',
            'CONCAT("<div style=width:250px >",catalogs.Title,"</div>") AS Title',
            'catalogs.Author',
            'catalogs.Edition',
            'catalogs.PhysicalDescription',
            'catalogs.Subject',
            'catalogs.CallNumber',
            'catalogs.Worksheet_id',
            'catalogs.Publikasi'
            /*'CONCAT_WS(" ",catalogs.PublishLocation,
                catalogs.Publisher,
                catalogs.PublishYear
                ) AS Publishment'*/
        ]);
        $queryCollections = Collections::find()
                ->select('Catalog_id,count(ID) AS Eksemplar')
                ->groupby('Catalog_id');
        $queryCatalogfiles = Catalogfiles::find()
                ->select('Catalog_id,count(ID) AS KontenDigital, (CASE WHEN count(ID) > 0 THEN 1 ELSE 0 END) AS PunyaKontenDigital')
                ->groupby('Catalog_id');
        $queryUsersCreate = Users::find()
                ->select('ID,username');
        if($keranjang==1)
        {
            $queryCount->innerJoin('keranjang_katalog',' keranjang_katalog.Catalog_id=catalogs.ID');
            $queryCount->where(['keranjang_katalog.CreateBy'=>(string)Yii::$app->user->identity->ID]);
        }
        $queryCount->leftJoin(['collectionCount' => $queryCollections],' collectionCount.Catalog_id = catalogs.ID');
        $queryCount->leftJoin(['catalogfilesCount' => $queryCatalogfiles],' catalogfilesCount.Catalog_id = catalogs.ID');
        $queryCount->leftJoin(['usercreateby' => $queryUsersCreate],' usercreateby.ID = catalogs.CreateBy');
        $queryCount->leftJoin(['userupdateby' => $queryUsersCreate],' userupdateby.ID = catalogs.UpdateBy');
        //$queryCount->joinWith('catalogfiles');
        $queryCount->orderBy(['catalogs.CreateDate' => SORT_DESC]);
         
        if ($rules) {
            //bypass when type date
            $rules=\common\components\Helpers::changeRulesDateAdvanceSearch($rules);
            $translator = new Translator($rules);
            $query
                ->andWhere($translator->where())
                ->addParams($translator->params());
        }

        $queryCount->rightJoin('collections',' collections.Catalog_id = catalogs.ID');
        $jumlahEksemplar = $queryCount->count();
        //End Proses mendapatkan jumlah eksemplar
        //
      return $dataProvider;
    }
    public function advancedSearchLKD($keranjang,$rules,&$jumlahJudul=0,&$jumlahEksemplar=0){
        $query = Catalogs::find()
        ->addSelect([
            'catalogs.ID',
            'catalogs.IsRDA',
            'CONCAT("<div style=width:120px >",catalogs.BIBID,"</div>") AS BIBID',
            'CONCAT("<div style=width:250px >",catalogs.Title,"</div>") AS Title',
            'catalogs.Author',
            'catalogs.Edition',
            'catalogs.PhysicalDescription',
            'catalogs.Subject',
            'catalogs.CallNumber',
            'catalogs.Worksheet_id',
            'CONCAT_WS(" ",catalogs.PublishLocation,
                catalogs.Publisher,
                catalogs.PublishYear
                ) AS Publishment'
        ]);
        $queryCollections = Collections::find()
                ->select('Catalog_id,count(ID) AS Eksemplar')
                ->groupby('Catalog_id');
        $queryCatalogfiles = Catalogfiles::find()
                ->select('Catalog_id,count(ID) AS KontenDigital, (CASE WHEN count(ID) > 0 THEN 1 ELSE 0 END) AS PunyaKontenDigital')
                ->groupby('Catalog_id');
        $queryUsersCreate = Users::find()
                ->select('ID,username');
        if($keranjang==1)
        {
            $query->innerJoin('keranjang_katalog',' keranjang_katalog.Catalog_id=catalogs.ID');
            $query->where(['keranjang_katalog.CreateBy'=>(string)Yii::$app->user->identity->ID]);
        }
        $query->leftJoin(['collectionCount' => $queryCollections],' collectionCount.Catalog_id = catalogs.ID');
        $query->leftJoin(['catalogfilesCount' => $queryCatalogfiles],' catalogfilesCount.Catalog_id = catalogs.ID');
        $query->leftJoin(['usercreateby' => $queryUsersCreate],' usercreateby.ID = catalogs.CreateBy');
        $query->leftJoin(['userupdateby' => $queryUsersCreate],' userupdateby.ID = catalogs.UpdateBy');
        $query->where('catalogfilesCount.KontenDigital <> 0');
        //$query->joinWith('catalogfiles');
        $query->orderBy(['catalogs.CreateDate' => SORT_DESC]);
         
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
                'KontenDigital' => [
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
                ],
            ]
        ]);

        $jumlahJudul = $query->count();
        //Proses mendapatkan jumlah eksemplar
        $queryCount = Catalogs::find()
        ->addSelect([
            'catalogs.ID',
            'CONCAT("<div style=width:120px >",catalogs.BIBID,"</div>") AS BIBID',
            'CONCAT("<div style=width:250px >",catalogs.Title,"</div>") AS Title',
            'catalogs.Author',
            'catalogs.Edition',
            'catalogs.PhysicalDescription',
            'catalogs.Subject',
            'catalogs.CallNumber',
            'catalogs.Worksheet_id',
            'CONCAT_WS(" ",catalogs.PublishLocation,
                catalogs.Publisher,
                catalogs.PublishYear
                ) AS Publishment'
        ]);
        $queryCollections = Collections::find()
                ->select('Catalog_id,count(ID) AS Eksemplar')
                ->groupby('Catalog_id');
        $queryCatalogfiles = Catalogfiles::find()
                ->select('Catalog_id,count(ID) AS KontenDigital, (CASE WHEN count(ID) > 0 THEN 1 ELSE 0 END) AS PunyaKontenDigital')
                ->groupby('Catalog_id');
        $queryUsersCreate = Users::find()
                ->select('ID,username');
        if($keranjang==1)
        {
            $queryCount->innerJoin('keranjang_katalog',' keranjang_katalog.Catalog_id=catalogs.ID');
            $queryCount->where(['keranjang_katalog.CreateBy'=>(string)Yii::$app->user->identity->ID]);
        }
        $queryCount->leftJoin(['collectionCount' => $queryCollections],' collectionCount.Catalog_id = catalogs.ID');
        $queryCount->leftJoin(['catalogfilesCount' => $queryCatalogfiles],' catalogfilesCount.Catalog_id = catalogs.ID');
        $queryCount->leftJoin(['usercreateby' => $queryUsersCreate],' usercreateby.ID = catalogs.CreateBy');
        $queryCount->leftJoin(['userupdateby' => $queryUsersCreate],' userupdateby.ID = catalogs.UpdateBy');
        //$queryCount->joinWith('catalogfiles');
        $query->where('catalogfilesCount.KontenDigital <> 0');
        $queryCount->orderBy(['catalogs.CreateDate' => SORT_DESC]);
         
        if ($rules) {
            //bypass when type date
            $rules=\common\components\Helpers::changeRulesDateAdvanceSearch($rules);
            $translator = new Translator($rules);
            $query
                ->andWhere($translator->where())
                ->addParams($translator->params());
        }

        $queryCount->rightJoin('collections',' collections.Catalog_id = catalogs.ID');
        $jumlahEksemplar = $queryCount->count();
        //End Proses mendapatkan jumlah eksemplar
        //
      return $dataProvider;
    }

    public function search($keranjang,$params)
    {
        $query = Catalogs::find()
        ->addSelect([
            'catalogs.ID',
            'CONCAT("<div style=width:120px >",catalogs.BIBID,"</div>") AS BIBID',
            'CONCAT("<div style=width:250px >",catalogs.Title,"</div>") AS Title',
            'catalogs.Author',
            'catalogs.Edition',
            'catalogs.PhysicalDescription',
            'catalogs.Subject',
            'catalogs.CallNumber',
            'catalogs.Worksheet_id',
            'CONCAT_WS(" ",catalogs.PublishLocation,
                catalogs.Publisher,
                catalogs.PublishYear
                ) AS Publishment'
        ]);
        $queryCollections = Collections::find()
                ->select('Catalog_id,count(ID) AS Eksemplar')
                ->groupby('Catalog_id');
        $queryCatalogfiles = Catalogfiles::find()
                ->select('Catalog_id,count(ID) AS KontenDigital')
                ->groupby('Catalog_id');
        if($keranjang==1)
        {
            $query->innerJoin('keranjang_katalog',' keranjang_katalog.Catalog_id=catalogs.ID');
        }
        $query->leftJoin(['collectionCount' => $queryCollections],' collectionCount.Catalog_id = catalogs.ID');
        $query->leftJoin(['catalogfilesCount' => $queryCatalogfiles],' catalogfilesCount.Catalog_id = catalogs.ID');
        $query->orderBy(['catalogs.UpdateDate' => SORT_DESC]);
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
                'KontenDigital' => [
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
                ],
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
            'KontenDigital'=> $this->KontenDigital,
            'Eksemplar'=> $this->Eksemplar
        ]);

        $query->andFilterWhere(['like', 'BIBID', $this->BIBID])
            ->andFilterWhere(['like', 'Title', $this->Title])
            ->andFilterWhere(['like', 'Edition', $this->Edition])
            ->andFilterWhere(['like', 'Publishment', $this->Publishment])
            ->andFilterWhere(['like', 'PhysicalDescription', $this->PhysicalDescription])
            ->andFilterWhere(['like', 'Subject', $this->Subject])
            ->andFilterWhere(['like', 'CallNumber', $this->CallNumber])
            ->andFilterWhere(['like', 'KontenDigital', $this->KontenDigital])
            ->andFilterWhere(['like', 'Eksemplar', $this->Eksemplar]);
        return $dataProvider;
    }

    public function searchKatalogDataTag($params)
    {
        $stringSQL = "SELECT catalogs.ID,catalogs.Title,catalogs.Author,catalogs.Publisher,catalogs.PublishYear,";
        for ($i=1; $i < 1000 ; $i++) { 
            $tag =  str_pad($i, 3, '0', STR_PAD_LEFT);
            $comma = ($i == 999) ? '' : ',';
            $stringSQL .= "(SELECT Value FROM catalog_ruas WHERE CatalogId=catalogs.ID AND Tag='".$tag."' LIMIT 1) AS 't".$tag."'".$comma;
        }
        $stringSQL .=" FROM catalogs";

        /*$query = Catalogs::findbySql($stringSQL);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $sortcolumns = array();
        $sortcolumns[] = "ID";
        $sortcolumns[] = "Title";
        $sortcolumns[] = "Author";
        $sortcolumns[] = "Publisher";
        $sortcolumns[] = "PublishYear";
        for ($i=1; $i < 1000 ; $i++) { 
            $tag =  str_pad($i, 3, '0', STR_PAD_LEFT);
            $sortcolumns[] = "t".$tag;
        }
        $dataProvider->setSort([
            'attributes' => $sortcolumns
        ]);
        */

        $count = Yii::$app->db->createCommand("SELECT count(*) from catalogs")->queryScalar();
        $dataProvider = new \yii\data\SqlDataProvider([
            'sql' => $stringSQL,
            'totalCount' => $count,
        ]);
        
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        
        return $dataProvider;
    }

    public function searchKatalogCetakKartu($params)
    {
        $query = Catalogs::find()
        ->addSelect([
            'ID',
            'BIBID',
            'Title',
            'Author',
            'CONCAT_WS(" ",PublishLocation,
                Publisher,
                PublishYear
                ) AS Publishment',
            'CallNumber'
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $sortcolumns = array();
        $sortcolumns[] = "ID";
        $sortcolumns[] = "BIBID";
        $sortcolumns[] = "Title";
        $sortcolumns[] = "Author";
        $sortcolumns[] = "Publishment";
        $sortcolumns[] = "CallNumber";
        $dataProvider->setSort([
            'attributes' => $sortcolumns
        ]);
        
        
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        
        return $dataProvider;
    }

    public function searchKatalogCetakLabel($params)
    {
        $query = Collections::find()
                ->addSelect([
                    'collections.ID', 
                    'collections.NomorBarcode', 
                    'collections.NoInduk', 
                    'collections.CallNumber', 
                    'collections.Source_id', 
                    'collections.Media_id', 
                    'collections.Category_id', 
                    'collections.Rule_id', 
                    'catalogs.Title',
                    'catalogs.Author',
                    'CONCAT_WS(" ",catalogs.PublishLocation,
                        catalogs.Publisher,
                        catalogs.PublishYear
                        ) AS Publishment',
                    'catalogs.PhysicalDescription'])
                ->leftJoin('catalogs',' collections.Catalog_id = catalogs.id')
                ->joinWith('source')
                ->joinWith('media')
                ->joinWith('category')
                ->joinWith('rule');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'ID', 
                'NomorBarcode', 
                'NoInduk',
                'CallNumber',
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
                'Title',
                'Author',
                'Publishment',
                'PhysicalDescription'
            ]
        ]);
        
        
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        
        return $dataProvider;
    }

    public function searchKatalogKontenDigital($rules)
    {
        $query = Catalogfiles::find()
                ->addSelect([
                    "catalogfiles.Catalog_id", 
                    "catalogfiles.ID", 
                    "catalogfiles.FileURL", 
                    "catalogfiles.IsPublish", 
                    "catalogfiles.CreateDate",
                    "catalogs.BIBID",
                    "catalogs.Title",
                    "catalogs.Author",
                    "catalogs.Author",
                    "CONCAT(worksheets.name,'<br/>'
                        ,'<b>',catalogs.Title,'</b>','<br/>'
                        ,(CASE WHEN worksheets.ID <> 4
                         AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE '' END)
                        ,'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher
                        ,' ',catalogs.PublishYear
                        ,(CASE WHEN catalogs.PhysicalDescription IS NOT NULL THEN CONCAT('<br/>',catalogs.PhysicalDescription) ELSE '' END)
                        ) AS DataBib"])
                ->leftJoin('catalogs',' catalogfiles.Catalog_id = catalogs.id')
                ->leftJoin('worksheets',' catalogs.Worksheet_id = worksheets.id');
                $queryUsersCreate = Users::find()
                ->select('ID,username');
                $query->leftJoin(['usercreateby' => $queryUsersCreate],' usercreateby.ID = catalogfiles.CreateBy');
                $query->leftJoin(['userupdateby' => $queryUsersCreate],' userupdateby.ID = catalogfiles.UpdateBy');
                $query->orderBy(['catalogfiles.CreateDate' => SORT_DESC]);

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
                'FileURL', 
                'IsPublish',
                'CreateDate',
                'BIBID',
                'FileType',
                'FileSize',
                'DataBib'
            ]
        ]);
        
        
        return $dataProvider;
    }
}
