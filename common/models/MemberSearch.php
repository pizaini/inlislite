<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


use common\models\Members;
use leandrogehlen\querybuilder\Translator;



/**
 * MemberSearch represents the model behind the search form about `common\models\Members`.
 */
class MemberSearch extends Members
{
    public $status;
    //public $User_id;
    
    public function rules()
    {
        return [
           [['ID', 'BiayaPendaftaran'], 'number'],
           [['MemberNo', 'Fullname', 'PlaceOfBirth', 'DateOfBirth', 'Address', 'AddressNow', 'Phone', 'InstitutionName', 'InstitutionAddress', 'InstitutionPhone', 'IdentityNo', 'RegisterDate', 'EndDate', 'MotherMaidenName', 'Email', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'NoHp', 'NamaDarurat', 'TelpDarurat', 'AlamatDarurat', 'StatusHubunganDarurat', 'City', 'Province', 'CityNow', 'ProvinceNow', 'TahunAjaran', 'KeteranganLain', 'TanggalBebasPustaka', 'KIILastUploadDate'], 'safe'],
           [['IdentityType_id', 'EducationLevel_id', 'Sex_id', 'MaritalStatus_id', 'Job_id', 'JenisPermohonan_id', 'JenisAnggota_id', 'StatusAnggota_id', 'LoanReturnLateCount', 'Branch_id', 'CreateBy', 'UpdateBy', 'Kelas_id', 'Agama_id',/* 'MasaBerlaku_id',*/ 'Jurusan_id', 'Fakultas_id', 'UnitKerja_id'], 'integer'],
           [['IsLunasBiayaPendaftaran'], 'boolean'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function advancedSearch($rules){
      $query = Members::find();
         
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
            'defaultOrder' => ['ID'=>SORT_DESC],
            'attributes' => [
                'ID',
                'MemberNo',
                'Fullname',
                'PlaceOfBirth',
                'DateOfBirth',
                'Address',
                'status' => [
                    'asc' => ['status_anggota.Nama' => SORT_ASC], // tabel_name
                    'desc' => ['status_anggota.Nama' => SORT_DESC], // tabel_name
                    'label' => 'Status',
                    //'default' => SORT_ASC
                ],
                'NoHp',
                'JenisIdentitas'=>[
                    'asc' => ['master_jenis_identitas.Nama' => SORT_ASC], // tabel_name
                    'desc' => ['master_jenis_identitas.Nama' => SORT_DESC], // tabel_name
                    'label' => 'Jenis Identitas',
                   // 'default' => SORT_ASC
                ],
                'sex'=>[
                    'asc' => ['jenis_kelamin.Name' => SORT_ASC], // tabel_name
                    'desc' => ['jenis_kelamin.Name' => SORT_DESC], // tabel_name
                    'label' => 'P/W',
                    //'default' => SORT_ASC
                ],
                'JenisAnggota'=>[
                    'asc' => ['jenis_anggota.jenisanggota' => SORT_ASC], // tabel_name
                    'desc' => ['jenis_anggota.jenisanggota' => SORT_DESC], // tabel_name
                    //'default' => SORT_ASC
                ],
            ],

        ]);



        $query->joinWith('statusAnggota');
        $query->joinWith('identityType');
        $query->joinWith('sex');
        $query->joinWith('jenisAnggota');

      return $dataProvider;
    }


    public function advancedSearch2($rules){

       $queryKeranjang = \common\models\KeranjangAnggota::find()->asArray()->all();
         foreach ($queryKeranjang as $row) {
                    if ($row != null) {
                      $newItem[] = $row['Member_id'];
                    }
        }

      //var_dump($queryKeranjang);
      if(isset($queryKeranjang)){
        $newItem[] = ['0'=>'0'];
      }

      
      $query = Members::find();

          $query->where(['IN', 'keranjang_anggota.Member_id', $newItem]);
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
                'MemberNo',
                'Fullname',
                'PlaceOfBirth',
                'DateOfBirth',
                'Address',
                'status' => [
                    'asc' => ['status_anggota.Nama' => SORT_ASC], // tabel_name
                    'desc' => ['status_anggota.Nama' => SORT_DESC], // tabel_name
                    'label' => 'Status',
                    'default' => SORT_ASC
                ],
                'NoHp',
                'JenisIdentitas'=>[
                    'asc' => ['master_jenis_identitas.Nama' => SORT_ASC], // tabel_name
                    'desc' => ['master_jenis_identitas.Nama' => SORT_DESC], // tabel_name
                    'label' => 'Jenis Identitas',
                    'default' => SORT_ASC
                ],
                'sex'=>[
                    'asc' => ['jenis_kelamin.Name' => SORT_ASC], // tabel_name
                    'desc' => ['jenis_kelamin.Name' => SORT_DESC], // tabel_name
                    'label' => 'P/W',
                    'default' => SORT_ASC
                ],
                'JenisAnggota'=>[
                    'asc' => ['jenis_anggota.jenisanggota' => SORT_ASC], // tabel_name
                    'desc' => ['jenis_anggota.jenisanggota' => SORT_DESC], // tabel_name
                    'default' => SORT_ASC
                ],
                
                
            ]
        ]);
      
        $query->joinWith('statusAnggota');
        $query->joinWith('identityType');
        $query->joinWith('sex');
        $query->joinWith('jenisAnggota');
        $query->joinWith('keranjangAnggotas');

      return $dataProvider;
    }


    public function searchDodot($rules)
    {
        $sql = 'SELECT Catalog_id,IDJILID,
            SUBSTR(IDJILID,6,4) AS TahunJilid,
            NOMORPANGGILJILID,
            JumlahKoleksi,
            (SELECT CONCAT(\'<b>\',catalogs.Title,\'</b>\',\'<br/>\',\'<br/>\',\'Penerbitan : \',catalogs.PublishLocation,\' \',catalogs.Publisher,\' \',catalogs.PublishYear) FROM catalogs WHERE a.Catalog_ID = catalogs.ID) AS DataBib 
            FROM (
                SELECT IDJilid AS IDJILID,
                NomorPanggilJilid AS NOMORPANGGILJILID,
                Catalog_ID,
                COUNT(*) AS JumlahKoleksi 
                FROM collections  
                INNER JOIN catalogs ON collections.Catalog_ID=catalogs.ID  
                WHERE 1=1  
                AND IDJilid IS NOT NULL 
                AND catalogs.Worksheet_id=4 
                GROUP BY IDJilid,NomorPanggilJilid,Catalog_ID  ORDER BY IDJilid
                ) a ';

        if ($rules) {
              $translator = new Translator($rules);
              $sql .= 'WHERE ' . $translator->where();
              $query = \common\models\Collections::findBySql($sql,$translator->params());
              
          }else{
            
            $query = \common\models\Collections::findBySql($sql);
          }
        

        
       

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'IDJILID', 
                'TahunJilid', 
                'NOMORPANGGILJILID',
                'JumlahKoleksi',
                'DataBib'
            ]
        ]);

        return $dataProvider;
    }

    public function search($params)
    {
        $query = Members::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'MemberNo',
                'Fullname',
                'PlaceOfBirth',
                'DateOfBirth',
                'Address',
                'status' => [
                    'asc' => ['status_anggota.Nama' => SORT_ASC], // tabel_name
                    'desc' => ['status_anggota.Nama' => SORT_DESC], // tabel_name
                    'label' => 'Status',
                    'default' => SORT_ASC
                ],
                'NoHp',
                'JenisIdentitas'=>[
                    'asc' => ['master_jenis_identitas.Nama' => SORT_ASC], // tabel_name
                    'desc' => ['master_jenis_identitas.Nama' => SORT_DESC], // tabel_name
                    'label' => 'Jenis Identitas',
                    'default' => SORT_ASC
                ],
                'sex'=>[
                    'asc' => ['jenis_kelamin.Name' => SORT_ASC], // tabel_name
                    'desc' => ['jenis_kelamin.Name' => SORT_DESC], // tabel_name
                    'label' => 'P/W',
                    'default' => SORT_ASC
                ],
                'JenisAnggota'=>[
                    'asc' => ['jenis_anggota.jenisanggota' => SORT_ASC], // tabel_name
                    'desc' => ['jenis_anggota.jenisanggota' => SORT_DESC], // tabel_name
                    'default' => SORT_ASC
                ],
                
                
            ]
        ]);

        // Name Of Relation On Model.
        $query->joinWith('statusAnggota');
        $query->joinWith('identityType');
        $query->joinWith('sex');
        $query->joinWith('jenisAnggota');

         $this->load($params);

        /*if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }*/

        if (!$this->validate()) {
           // uncomment the following line if you do not want to return any records when validation fails
           // $query->where('0=1');
           return $dataProvider;
       }


        
       // grid filtering conditions
       $query->andFilterWhere([
           'ID' => $this->ID,
           'DateOfBirth' => $this->DateOfBirth,
           'IdentityType_id' => $this->IdentityType_id,
           'EducationLevel_id' => $this->EducationLevel_id,
           'Sex_id' => $this->Sex_id,
           'MaritalStatus_id' => $this->MaritalStatus_id,
           'Job_id' => $this->Job_id,
           'RegisterDate' => $this->RegisterDate,
           'EndDate' => $this->EndDate,
           'JenisPermohonan_id' => $this->JenisPermohonan_id,
           'JenisAnggota_id' => $this->JenisAnggota_id,
           'StatusAnggota_id' => $this->StatusAnggota_id,
           'LoanReturnLateCount' => $this->LoanReturnLateCount,
           'Branch_id' => $this->Branch_id,
           'CreateBy' => $this->CreateBy,
           'CreateDate' => $this->CreateDate,
           'UpdateBy' => $this->UpdateBy,
           'UpdateDate' => $this->UpdateDate,
           'Kelas_id' => $this->Kelas_id,
           'Agama_id' => $this->Agama_id,
           //'MasaBerlaku_id' => $this->MasaBerlaku_id,
           'Jurusan_id' => $this->Jurusan_id,
           'Fakultas_id' => $this->Fakultas_id,
           'UnitKerja_id' => $this->UnitKerja_id,
           'IsLunasBiayaPendaftaran' => $this->IsLunasBiayaPendaftaran,
           'BiayaPendaftaran' => $this->BiayaPendaftaran,
           'TanggalBebasPustaka' => $this->TanggalBebasPustaka,
           'KIILastUploadDate' => $this->KIILastUploadDate,
       ]);

       $query->andFilterWhere(['like', 'MemberNo', $this->MemberNo])
           ->andFilterWhere(['like', 'Fullname', $this->Fullname])
           ->andFilterWhere(['like', 'PlaceOfBirth', $this->PlaceOfBirth])
           ->andFilterWhere(['like', 'Address', $this->Address])
           ->andFilterWhere(['like', 'AddressNow', $this->AddressNow])
           ->andFilterWhere(['like', 'Phone', $this->Phone])
           ->andFilterWhere(['like', 'InstitutionName', $this->InstitutionName])
           ->andFilterWhere(['like', 'InstitutionAddress', $this->InstitutionAddress])
           ->andFilterWhere(['like', 'InstitutionPhone', $this->InstitutionPhone])
           ->andFilterWhere(['like', 'IdentityNo', $this->IdentityNo])
           ->andFilterWhere(['like', 'MotherMaidenName', $this->MotherMaidenName])
           ->andFilterWhere(['like', 'Email', $this->Email])
           ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
           ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
           ->andFilterWhere(['like', 'NoHp', $this->NoHp])
           ->andFilterWhere(['like', 'NamaDarurat', $this->NamaDarurat])
           ->andFilterWhere(['like', 'TelpDarurat', $this->TelpDarurat])
           ->andFilterWhere(['like', 'AlamatDarurat', $this->AlamatDarurat])
           ->andFilterWhere(['like', 'StatusHubunganDarurat', $this->StatusHubunganDarurat])
           ->andFilterWhere(['like', 'City', $this->City])
           ->andFilterWhere(['like', 'Province', $this->Province])
           ->andFilterWhere(['like', 'CityNow', $this->CityNow])
           ->andFilterWhere(['like', 'ProvinceNow', $this->ProvinceNow])
           ->andFilterWhere(['like', 'TahunAjaran', $this->TahunAjaran])
           ->andFilterWhere(['like', 'KeteranganLain', $this->KeteranganLain]);
       return $dataProvider;
        
    }
}
