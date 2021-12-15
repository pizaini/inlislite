<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Memberguesses;
use yii\db\Expression;

/**
 * MemberguessesSearch represents the model behind the search form about `common\models\Memberguesses`.
 */
class MemberguessesSearch extends Memberguesses
{
    public $statusAnggota;
    public $memberinfoFullname;
    public $memberinfoJenisAnggota_id;
    public $memberinfoAddressNow;
    public $memberinfoPhone;
    public $memberinfoEmail;
    public $locationName;
    public $locationlocationLibraryName;
    public $libraryID;
    public $TujuanKunjungan;
    public function rules()
    {
        return [
            [['ID', 'CreateBy', 'UpdateBy', 'LOCATIONLOANS_ID'], 'integer'],
            [['NoAnggota', 'Nama', 'Alamat', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'Deskripsi', 'Information', 'NoPengunjung','Status_id', 'Profesi_id', 'PendidikanTerakhir_id', 'JenisKelamin_id', 'Location_Id', 'TujuanKunjungan_Id','statusAnggota','memberinfoFullname','memberinfoJenisAnggota_id','memberinfoAddressNow','memberinfoPhone','memberinfoEmail','locationName','locationlocationLibraryName','libraryID','TujuanKunjungan' ], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Memberguesses::find()
        ->addSelect([' memberguesses.ID , memberguesses.TujuanKunjungan_Id , memberguesses.Nama , memberguesses.NoAnggota, memberguesses.Alamat, memberguesses.CreateDate,memberguesses.NoPengunjung,memberguesses.Location_Id,
         (CASE WHEN memberguesses.NoAnggota IS NOT NULL THEN members.Job_id ELSE memberguesses.Profesi_id END) AS Profesi_id,
         (CASE WHEN memberguesses.NoAnggota IS NOT NULL THEN members.JenisAnggota_id ELSE memberguesses.Status_id END) AS Status_id,

         (CASE WHEN memberguesses.NoAnggota IS NOT NULL THEN members.EducationLevel_id ELSE memberguesses.PendidikanTerakhir_id END) AS PendidikanTerakhir_id,
         (CASE WHEN memberguesses.NoAnggota IS NOT NULL THEN members.Sex_id ELSE memberguesses.JenisKelamin_id END) AS JenisKelamin_id, tujuan_kunjungan.TujuanKunjungan '])
        ->leftJoin('members','memberguesses.NoAnggota = members.MemberNo')
        ->leftJoin('jenis_anggota','members.JenisAnggota_id = jenis_anggota.id')
        ->leftJoin('locations', 'locations.ID = memberguesses.Location_Id')
        ->leftJoin('location_library', 'location_library.ID = locations.LocationLibrary_id')
        ->joinWith('pendidikanTerakhir')
        ->joinWith('tujuanKunjungan');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		    $dataProvider->setSort([
            'defaultOrder' => [
                'CreateDate' => SORT_DESC,
            ],
            'attributes' => [
                'ID',
                'NoAnggota',
                'NoPengunjung',
                'Nama',
                'Profesi_id' => [
                    'asc' => ['master_pekerjaan.Pekerjaan' => SORT_ASC],
                    'desc' => ['master_pekerjaan.Pekerjaan' => SORT_DESC],
                    'label' => Yii::t('app', 'Profesi'),
                    'default' => SORT_ASC
                ],
                'PendidikanTerakhir_id' => [
                    'asc' => ['master_pendidikan.Nama' => SORT_ASC],
                    'desc' => ['master_pendidikan.Nama' => SORT_DESC],
                    'label' => Yii::t('app', 'Pendidikan Terakhir'),
                    'default' => SORT_ASC
                ],
		        'JenisKelamin_id' => [
                    'asc' => ['jenis_kelamin.name' => SORT_ASC],
                    'desc' => ['jenis_kelamin.name' => SORT_DESC],
                    'label' => Yii::t('app', 'Jenis Kelamin'),
                    'default' => SORT_ASC
                ],
                'memberinfoFullname' => [
                    'asc' => ['members.Fullname' => SORT_ASC],
                    'desc' => ['members.Fullname' => SORT_DESC],
                    'label' => Yii::t('app', 'Nama Lengkap'),
                    'default' => SORT_ASC
                ],
		        'memberinfoJenisAnggota_id' => [
                    'asc' => ['jenis_anggota.jenisanggota' => SORT_ASC],
                    'desc' => ['jenis_anggota.jenisanggota' => SORT_DESC],
                    'label' => Yii::t('app', 'Jenis Anggota'),
                    'default' => SORT_ASC
                ],
		        'memberinfoAddressNow' => [
                    'asc' => ['members.AddressNow' => SORT_ASC],
                    'desc' => ['members.AddressNow' => SORT_DESC],
                    'label' => Yii::t('app', 'Alamat'),
                    'default' => SORT_ASC
                ],
		        'memberinfoPhone' => [
                    'asc' => ['members.Phone' => SORT_ASC],
                    'desc' => ['members.Phone' => SORT_DESC],
                    'label' => Yii::t('app', 'No. Telpon'),
                    'default' => SORT_ASC
                ],
                'memberinfoEmail' => [
                    'asc' => ['members.Email' => SORT_ASC],
                    'desc' => ['members.Email' => SORT_DESC],
                    'label' => Yii::t('app', 'Email'),
                    'default' => SORT_ASC
                ],
                'memberinfoEmail' => [
                    'asc' => ['members.Email' => SORT_ASC],
                    'desc' => ['members.Email' => SORT_DESC],
                    'label' => Yii::t('app', 'Email'),
                    'default' => SORT_ASC
                ],
                'locationName' => [
                    'asc' => ['locations.Name' => SORT_ASC],
                    'desc' => ['locations.Name' => SORT_DESC],
                    'label' => Yii::t('app', 'Lokasi Ruang'),
                    'default' => SORT_ASC
                ],
                'locationlocationLibraryName' => [
                    'asc' => ['location_library.Name' => SORT_ASC],
                    'desc' => ['location_library.Name' => SORT_DESC],
                    'label' => Yii::t('app', 'Lokasi Perpustakaan'),
                    'default' => SORT_ASC
                ],
		        'TujuanKunjungan' => [
                    'asc' => ['tujuan_kunjungan.TujuanKunjungan' => SORT_ASC],
                    'desc' => ['tujuan_kunjungan.TujuanKunjungan' => SORT_DESC],
                    'label' => Yii::t('app', 'Tujuan Kunjungan'),
                    'default' => SORT_ASC
                ],
                'Alamat',
                'CreateDate',
            ]
        ]);


		$this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

		$query->joinWith(['jenisKelamin']);
		$query->joinWith(['profesi']);

        $query->andFilterWhere([
            'ID' => $this->ID,
      			'NoAnggota' => $this->NoAnggota,
      			// 'Nama' => $this->Nama,
      			'NoPengunjung' => $this->NoPengunjung,
           // 'Status_id' => $this->Status_id,
            //'MasaBerlaku_id' => $this->MasaBerlaku_id,
            //'Profesi_id' => $this->Profesi_id,
            //'PendidikanTerakhir_id' => $this->PendidikanTerakhir_id,
            //'JenisKelamin_id' => $this->JenisKelamin_id,
            'CreateBy' => $this->CreateBy,
            // 'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
            //'LOCATIONLOANS_ID' => $this->LOCATIONLOANS_ID,
            //'Location_Id' => $this->Location_Id,
            //'TujuanKunjungan_Id' => $this->TujuanKunjungan_Id,
        ]);

        $query->andFilterWhere([
            'locations.LocationLibrary_id' => $this->libraryID,
        ]);

        $query->andFilterWhere(['like', 'NoAnggota', $this->NoAnggota])
            ->andFilterWhere(['like', 'memberguesses.Nama', $this->Nama])
            ->andFilterWhere(['like', 'Alamat', $this->Alamat])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'Deskripsi', $this->Deskripsi])
            ->andFilterWhere(['like', 'Information', $this->Information])

  			->andFilterWhere(['like', 'master_pekerjaan.Pekerjaan', $this->Profesi_id])
  			//->andFilterWhere(['like', 'members.master_pekerjaan.Pekerjaan', $this->Profesi_id])

  			->andFilterWhere(['like', 'master_pendidikan.Nama', $this->PendidikanTerakhir_id])
  			->andFilterWhere(['like', 'jenis_kelamin.Name', $this->JenisKelamin_id])
            ->andFilterWhere(['like', 'members.Fullname', $this->memberinfoFullname])
  			->andFilterWhere(['like', 'jenis_anggota.jenisanggota', $this->memberinfoJenisAnggota_id])
  			->andFilterWhere(['like', 'members.AddressNow', $this->memberinfoAddressNow])
  			->andFilterWhere(['like', 'members.Phone', $this->memberinfoPhone])
            ->andFilterWhere(['like', 'members.Email', $this->memberinfoEmail])
            ->andFilterWhere(['like', 'locations.Name', $this->locationName])
            ->andFilterWhere(['like', 'location_library.Name', $this->locationlocationLibraryName])

            ->andFilterWhere(['like', 'tujuan_kunjungan.TujuanKunjungan', $this->TujuanKunjungan])

  			->andFilterWhere(['like', "DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y %H:%i:%s')", $this->CreateDate]);

            if ($this->statusAnggota)
            {
        			$query->andFilterWhere(['IS', 'NoAnggota', (new Expression($this->statusAnggota))]);
            }


        return $dataProvider;
    }
}
