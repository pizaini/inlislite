<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Groupguesses;


/**
 * GroupguessesSearch represents the model behind the search form about `common\models\Groupguesses`.
 */
class GroupguessesSearch extends Groupguesses
{
	public $locationName;
    public $locationlocationLibraryName;
    public $libraryID;
    public $TujuanKunjungan;
    public function rules()
    {
        return [
            [['ID', 'CountPersonel', 'CountPNS', 'CountPSwasta', 'CountPeneliti', 'CountGuru', 'CountDosen', 'CountPensiunan', 'CountTNI', 'CountWiraswasta', 'CountPelajar', 'CountMahasiswa', 'CountLainnya', 'CountSD', 'CountSMP', 'CountSMA', 'CountD1', 'CountD2', 'CountD3', 'CountS1', 'CountS2', 'CountS3', 'CountLaki', 'CountPerempuan', 'TujuanKunjungan_ID', 'CreateBy', 'UpdateBy', 'Location_ID'], 'integer'],
            [['NamaKetua', 'NomerTelponKetua', 'AsalInstansi', 'AlamatInstansi', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'TeleponInstansi', 'EmailInstansi', 'Information', 'NoPengunjung','libraryID','locationName','locationlocationLibraryName','TujuanKunjungan' ], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Groupguesses::find()
        ->leftJoin('locations', 'locations.ID = groupguesses.Location_ID')
        ->leftJoin('location_library', 'location_library.ID = locations.LocationLibrary_id')
        ->joinWith('tujuanKunjungan');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
		$dataProvider->setSort([
            'defaultOrder' => [
                'CreateDate' => SORT_DESC,
            ],
            'attributes' => [
                'NamaKetua', 'NomerTelponKetua', 'AsalInstansi', 'AlamatInstansi', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'TeleponInstansi', 'EmailInstansi', 'Information', 'NoPengunjung','libraryID','locationName','locationlocationLibraryName', 'CountPersonel',
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
            ]
        ]);

		

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'CountPersonel' => $this->CountPersonel,
            'CountPNS' => $this->CountPNS,
            'CountPSwasta' => $this->CountPSwasta,
            'CountPeneliti' => $this->CountPeneliti,
            'CountGuru' => $this->CountGuru,
            'CountDosen' => $this->CountDosen,
            'CountPensiunan' => $this->CountPensiunan,
            'CountTNI' => $this->CountTNI,
            'CountWiraswasta' => $this->CountWiraswasta,
            'CountPelajar' => $this->CountPelajar,
            'CountMahasiswa' => $this->CountMahasiswa,
            'CountLainnya' => $this->CountLainnya,
            'CountSD' => $this->CountSD,
            'CountSMP' => $this->CountSMP,
            'CountSMA' => $this->CountSMA,
            'CountD1' => $this->CountD1,
            'CountD2' => $this->CountD2,
            'CountD3' => $this->CountD3,
            'CountS1' => $this->CountS1,
            'CountS2' => $this->CountS2,
            'CountS3' => $this->CountS3,
            'CountLaki' => $this->CountLaki,
            'CountPerempuan' => $this->CountPerempuan,
            'TujuanKunjungan_ID' => $this->TujuanKunjungan_ID,
            'CreateBy' => $this->CreateBy,
            // 'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
            'Location_ID' => $this->Location_ID,
        ]);

        $query->andFilterWhere([
            'locations.LocationLibrary_id' => $this->libraryID,
        ]);

        $query->andFilterWhere(['like', 'NamaKetua', $this->NamaKetua])
            ->andFilterWhere(['like', 'NomerTelponKetua', $this->NomerTelponKetua])
            ->andFilterWhere(['like', 'AsalInstansi', $this->AsalInstansi])
            ->andFilterWhere(['like', 'AlamatInstansi', $this->AlamatInstansi])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'TeleponInstansi', $this->TeleponInstansi])
            ->andFilterWhere(['like', 'EmailInstansi', $this->EmailInstansi])
            ->andFilterWhere(['like', 'Information', $this->Information])
            ->andFilterWhere(['like', 'NoPengunjung', $this->NoPengunjung])
			->andFilterWhere(['like', 'locations.Name', $this->locationName])
			->andFilterWhere(['like', 'location_library.Name', $this->locationlocationLibraryName])

            ->andFilterWhere(['like', 'tujuan_kunjungan.TujuanKunjungan', $this->TujuanKunjungan])

            ->andFilterWhere(['like', "DATE_FORMAT(groupguesses.CreateDate,'%d-%m-%Y %H:%i:%s')", $this->CreateDate])
            ;

        return $dataProvider;
    }
}
