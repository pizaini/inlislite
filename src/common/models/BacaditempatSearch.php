<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Bacaditempat;
use yii\db\Expression;

/**
 * BacaditempatSearch represents the model behind the search form about `common\models\Bacaditempat`.
 */
class BacaditempatSearch extends Bacaditempat
{
    // public $WaktuKunjungan;
    public $MemberNo;
    public $MemberFullname;
    public $MemberPekerjaan;
    public $MemberPendidikan;
    public $MemberJenisKelamin;
    public $ColBarcode;
    public $statusAnggota;
    public $CatJudul;
    public $CatEdition;
    public $CatPublisher;
    public $GuestNama;
    public $collectionmediaName;
    public $LocationName;
    public $locationlocationLibraryName;
    public $IsReturn;
    public function rules()
    {
        return [
            [['ID', 'NoPengunjung', 'collection_id', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'Member_id','statusAnggota','MemberNo','MemberFullname','MemberPekerjaan','MemberPendidikan','MemberJenisKelamin','LocationName','ColBarcode','CatJudul','CatEdition','CatPublisher','GuestNama','collectionmediaName','locationlocationLibraryName','IsReturn'], 'safe'],
            [['CreateBy', 'UpdateBy', 'Location_Id'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Bacaditempat::find()
        ->leftJoin('members','bacaditempat.Member_id = members.ID')
        ->leftJoin('master_pekerjaan','members.Job_id = master_pekerjaan.id')
        ->leftJoin('master_pendidikan','members.EducationLevel_id = master_pendidikan.id')
        ->leftJoin('jenis_kelamin','members.Sex_id = jenis_kelamin.ID')
        ->leftJoin('locations','bacaditempat.Location_Id = locations.ID')
        ->leftJoin('location_library', 'location_library.ID = locations.LocationLibrary_id')
        ->leftJoin('collections','bacaditempat.collection_id = collections.ID')
        ->leftJoin('collectionmedias','collectionmedias.ID = collections.Media_id')
        ->leftJoin('catalogs','collections.Catalog_id = catalogs.ID')
        ->leftJoin('memberguesses','bacaditempat.NoPengunjung = memberguesses.NoPengunjung');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'CreateDate' => SORT_DESC,
                ],

              'attributes' => [
                  'NoPengunjung', 'collection_id', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'Member_id','statusAnggota','MemberNo',
                  'MemberFullname' => [
                      'asc' => ['members.Fullname' => SORT_ASC],
                      'desc' => ['members.Fullname' => SORT_DESC],
                      'label' => Yii::t('app', 'Nama'),
                      // 'default' => SORT_ASC
                  ],
                  'MemberPekerjaan' => [
                      'asc' => ['master_pekerjaan.Pekerjaan' => SORT_ASC],
                      'desc' => ['master_pekerjaan.Pekerjaan' => SORT_DESC],
                      'label' => Yii::t('app', 'Pekerjaan'),
                      // 'default' => SORT_ASC
                  ],
                  'MemberPendidikan' => [
                      'asc' => ['master_pendidikan.Nama' => SORT_ASC],
                      'desc' => ['master_pendidikan.Nama' => SORT_DESC],
                      'label' => Yii::t('app', 'Pendidikan Terakhir'),
                      // 'default' => SORT_ASC
                  ],
                  'MemberJenisKelamin' => [
                      'asc' => ['jenis_kelamin.Name' => SORT_ASC],
                      'desc' => ['jenis_kelamin.Name' => SORT_DESC],
                      'label' => Yii::t('app', 'Jenis Kelamin'),
                      // 'default' => SORT_ASC
                  ],
                  'LocationName' => [
                      'asc' => ['locations.Name' => SORT_ASC],
                      'desc' => ['locations.Name' => SORT_DESC],
                      'label' => Yii::t('app', 'Lokasi Ruangan'),
                      // 'default' => SORT_ASC
                  ],
                  'ColBarcode' => [
                      'asc' => ['collections.NomorBarcode' => SORT_ASC],
                      'desc' => ['collections.NomorBarcode' => SORT_DESC],
                      'label' => Yii::t('app', 'Nomor Barcode'),
                      // 'default' => SORT_ASC
                  ],
                  'CatJudul' => [
                      'asc' => ['catalogs.Judul' => SORT_ASC],
                      'desc' => ['catalogs.Judul' => SORT_DESC],
                      'label' => Yii::t('app', 'Judul'),
                      // 'default' => SORT_ASC
                  ],
                  'CatEdition' => [
                      'asc' => ['catalogs.Edition' => SORT_ASC],
                      'desc' => ['catalogs.Edition' => SORT_DESC],
                      'label' => Yii::t('app', 'Edisi'),
                      // 'default' => SORT_ASC
                  ],
                  'CatPublisher' => [
                      'asc' => ['catalogs.Publisher' => SORT_ASC],
                      'desc' => ['catalogs.Publisher' => SORT_DESC],
                      'label' => Yii::t('app', 'Publisher'),
                      // 'default' => SORT_ASC
                  ],
                  'GuestNama' => [
                      'asc' => ['memberguesses.Nama' => SORT_ASC],
                      'desc' => ['memberguesses.Nama' => SORT_DESC],
                      'label' => Yii::t('app', 'Nama'),
                      // 'default' => SORT_ASC
                  ],
                  'collectionmediaName' => [
                      'asc' => ['collectionmedias.Name' => SORT_ASC],
                      'desc' => ['collectionmedias.Name' => SORT_DESC],
                      'label' => Yii::t('app', 'Bnetuk Fisik'),
                      // 'default' => SORT_ASC
                  ],
                 	'locationlocationLibraryName' => [
                         'asc' => ['location_library.Name' => SORT_ASC],
                         'desc' => ['location_library.Name' => SORT_DESC],
                         'label' => Yii::t('app', 'Lokasi Perpustakaan'),
                         'default' => SORT_ASC
                  ],
               ]

            ],
        ]);

//&& $this->validate()
        if (!($this->load($params) )) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'bacaditempat.CreateBy' => $this->CreateBy,
            // 'bacaditempat.CreateDate' => $this->CreateDate,
            'bacaditempat.UpdateBy' => $this->UpdateBy,
            'bacaditempat.Location_Id' => $this->Location_Id,
        ]);


        // $asd = date_create($this->CreateDate)->format('Y-m-d H:i:s');
        // $asd = \DateTime::createFromFormat('d-m-Y H:i:s', $this->CreateDate)->format('Y-m-d H:i:s');

        $query->andFilterWhere(['like', 'bacaditempat.ID', $this->ID])
            ->andFilterWhere(['like', 'DATE_FORMAT(bacaditempat.UpdateDate, "%d-%m-%Y %H:%i:%s")', $this->UpdateDate])
            // ->andFilterWhere(['like', 'bacaditempat.CreateDate', $this->WaktuKunjungan])
            ->andFilterWhere(['like', 'bacaditempat.NoPengunjung', $this->NoPengunjung])
            ->andFilterWhere(['like', 'bacaditempat.collection_id', $this->collection_id])
            ->andFilterWhere(['like', 'bacaditempat.CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'bacaditempat.UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'bacaditempat.Member_id', $this->Member_id])
            ->andFilterWhere(['like', 'members.MemberNo', $this->MemberNo])
            ->andFilterWhere(['like', 'members.Fullname', $this->MemberFullname])
            ->andFilterWhere(['like', 'master_pekerjaan.Pekerjaan', $this->MemberPekerjaan])
            ->andFilterWhere(['like', 'master_pendidikan.Nama', $this->MemberPendidikan])
            ->andFilterWhere(['like', 'jenis_kelamin.Name', $this->MemberJenisKelamin])
            ->andFilterWhere(['like', 'collections.NomorBarcode', $this->ColBarcode])
            ->andFilterWhere(['like', 'locations.Name', $this->LocationName])
            ->andFilterWhere(['like', 'catalogs.Title', $this->CatJudul])
            ->andFilterWhere(['like', 'catalogs.Edition', $this->CatEdition])
            ->andFilterWhere(['like', 'catalogs.Publisher', $this->CatPublisher])
            ->andFilterWhere(['like', 'memberguesses.Nama', $this->GuestNama])
            ->andFilterWhere(['like', 'collectionmedias.Name', $this->collectionmediaName])
            ->andFilterWhere(['like', 'location_library.Name', $this->locationlocationLibraryName])
            ->andFilterWhere(['like', 'bacaditempat.Is_return', $this->IsReturn]);

            if ($this->statusAnggota)
            {
                    $query->andFilterWhere(['IS', 'bacaditempat.Member_id', (new Expression($this->statusAnggota))]);
            }


        return $dataProvider;
    }

    public function search2($params)
    {
        $query = Bacaditempat::find()
        ->leftJoin('members','bacaditempat.Member_id = members.ID')
        ->leftJoin('master_pekerjaan','members.Job_id = master_pekerjaan.id')
        ->leftJoin('master_pendidikan','members.EducationLevel_id = master_pendidikan.id')
        ->leftJoin('jenis_kelamin','members.Sex_id = jenis_kelamin.ID')
        ->leftJoin('locations','bacaditempat.Location_Id = locations.ID')
        ->leftJoin('location_library', 'location_library.ID = locations.LocationLibrary_id')
        ->leftJoin('collections','bacaditempat.collection_id = collections.ID')
        ->leftJoin('collectionmedias','collectionmedias.ID = collections.Media_id')
        ->leftJoin('catalogs','collections.Catalog_id = catalogs.ID')
        ->leftJoin('memberguesses','bacaditempat.NoPengunjung = memberguesses.NoPengunjung');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'CreateDate' => SORT_DESC,
                ],

              'attributes' => [
                  'NoPengunjung', 'collection_id', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'Member_id','statusAnggota','MemberNo',
                  'MemberFullname' => [
                      'asc' => ['members.Fullname' => SORT_ASC],
                      'desc' => ['members.Fullname' => SORT_DESC],
                      'label' => Yii::t('app', 'Nama'),
                      // 'default' => SORT_ASC
                  ],
                  'MemberPekerjaan' => [
                      'asc' => ['master_pekerjaan.Pekerjaan' => SORT_ASC],
                      'desc' => ['master_pekerjaan.Pekerjaan' => SORT_DESC],
                      'label' => Yii::t('app', 'Pekerjaan'),
                      // 'default' => SORT_ASC
                  ],
                  'MemberPendidikan' => [
                      'asc' => ['master_pendidikan.Nama' => SORT_ASC],
                      'desc' => ['master_pendidikan.Nama' => SORT_DESC],
                      'label' => Yii::t('app', 'Pendidikan Terakhir'),
                      // 'default' => SORT_ASC
                  ],
                  'MemberJenisKelamin' => [
                      'asc' => ['jenis_kelamin.Name' => SORT_ASC],
                      'desc' => ['jenis_kelamin.Name' => SORT_DESC],
                      'label' => Yii::t('app', 'Jenis Kelamin'),
                      // 'default' => SORT_ASC
                  ],
                  'LocationName' => [
                      'asc' => ['locations.Name' => SORT_ASC],
                      'desc' => ['locations.Name' => SORT_DESC],
                      'label' => Yii::t('app', 'Lokasi Ruangan'),
                      // 'default' => SORT_ASC
                  ],
                  'ColBarcode' => [
                      'asc' => ['collections.NomorBarcode' => SORT_ASC],
                      'desc' => ['collections.NomorBarcode' => SORT_DESC],
                      'label' => Yii::t('app', 'Nomor Barcode'),
                      // 'default' => SORT_ASC
                  ],
                  'CatJudul' => [
                      'asc' => ['catalogs.Judul' => SORT_ASC],
                      'desc' => ['catalogs.Judul' => SORT_DESC],
                      'label' => Yii::t('app', 'Judul'),
                      // 'default' => SORT_ASC
                  ],
                  'CatEdition' => [
                      'asc' => ['catalogs.Edition' => SORT_ASC],
                      'desc' => ['catalogs.Edition' => SORT_DESC],
                      'label' => Yii::t('app', 'Edisi'),
                      // 'default' => SORT_ASC
                  ],
                  'CatPublisher' => [
                      'asc' => ['catalogs.Publisher' => SORT_ASC],
                      'desc' => ['catalogs.Publisher' => SORT_DESC],
                      'label' => Yii::t('app', 'Publisher'),
                      // 'default' => SORT_ASC
                  ],
                  'GuestNama' => [
                      'asc' => ['memberguesses.Nama' => SORT_ASC],
                      'desc' => ['memberguesses.Nama' => SORT_DESC],
                      'label' => Yii::t('app', 'Nama'),
                      // 'default' => SORT_ASC
                  ],
                  'collectionmediaName' => [
                      'asc' => ['collectionmedias.Name' => SORT_ASC],
                      'desc' => ['collectionmedias.Name' => SORT_DESC],
                      'label' => Yii::t('app', 'Bnetuk Fisik'),
                      // 'default' => SORT_ASC
                  ],
                  'locationlocationLibraryName' => [
                         'asc' => ['location_library.Name' => SORT_ASC],
                         'desc' => ['location_library.Name' => SORT_DESC],
                         'label' => Yii::t('app', 'Lokasi Perpustakaan'),
                         'default' => SORT_ASC
                  ],
               ]

            ],
        ]);

//&& $this->validate()
        /*if (!($this->load($params) )) {
            return $dataProvider;
        }*/

        $query->andFilterWhere([
            'bacaditempat.CreateBy' => $this->CreateBy,
            // 'bacaditempat.CreateDate' => $this->CreateDate,
            'bacaditempat.UpdateBy' => $this->UpdateBy,
            'bacaditempat.UpdateDate' => $this->UpdateDate,
            'bacaditempat.Location_Id' => $this->Location_Id,
        ]);


        // $asd = date_create($this->CreateDate)->format('Y-m-d H:i:s');
        // $asd = \DateTime::createFromFormat('d-m-Y H:i:s', $this->CreateDate)->format('Y-m-d H:i:s');

        $query->andFilterWhere(['like', 'bacaditempat.ID', $this->ID])
            // ->andFilterWhere(['like', 'bacaditempat.CreateDate', $this->CreateDate])
            // ->andFilterWhere(['like', 'bacaditempat.CreateDate', $this->WaktuKunjungan])
            ->andFilterWhere(['like', 'bacaditempat.NoPengunjung', $this->NoPengunjung])
            ->andFilterWhere(['like', 'bacaditempat.collection_id', $this->collection_id])
            ->andFilterWhere(['like', 'bacaditempat.CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'bacaditempat.UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'bacaditempat.Member_id', $this->Member_id])
            ->andFilterWhere(['like', 'members.MemberNo', $this->MemberNo])
            ->andFilterWhere(['like', 'members.Fullname', $this->MemberFullname])
            ->andFilterWhere(['like', 'master_pekerjaan.Pekerjaan', $this->MemberPekerjaan])
            ->andFilterWhere(['like', 'master_pendidikan.Nama', $this->MemberPendidikan])
            ->andFilterWhere(['like', 'jenis_kelamin.Name', $this->MemberJenisKelamin])
            ->andFilterWhere(['like', 'collections.NomorBarcode', $this->ColBarcode])
            ->andFilterWhere(['like', 'locations.Name', $this->LocationName])
            ->andFilterWhere(['like', 'catalogs.Title', $this->CatJudul])
            ->andFilterWhere(['like', 'catalogs.Edition', $this->CatEdition])
            ->andFilterWhere(['like', 'catalogs.Publisher', $this->CatPublisher])
            ->andFilterWhere(['like', 'memberguesses.Nama', $this->GuestNama])
            ->andFilterWhere(['like', 'collectionmedias.Name', $this->collectionmediaName])
            ->andFilterWhere(['like', 'location_library.Name', $this->locationlocationLibraryName])
            ->andFilterWhere(['like', 'bacaditempat.Is_return', $this->IsReturn]);

            if ($this->statusAnggota)
            {
                    $query->andFilterWhere(['IS', 'bacaditempat.Member_id', (new Expression($this->statusAnggota))]);
            }


        return $dataProvider;
    }
}
