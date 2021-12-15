<?php

namespace guestbook\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Members;

/**
 * MembersSearch represents the model behind the search form about `guestbook\models\Members`.
 */
class MembersSearch extends Members
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['MemberNo', 'Fullname', 'PlaceOfBirth', 'DateOfBirth', 'Address', 'AddressNow', 'Phone', 'InstitutionName', 'InstitutionAddress', 'InstitutionPhone', 'IdentityType', 'IdentityNo', 'EducationLevel', 'Religion', 'Sex', 'MaritalStatus', 'JobName', 'RegisterDate', 'EndDate', 'BarCode', 'PicPath', 'MotherMaidenName', 'Email', 'JenisPermohonan', 'JenisPermohonanName', 'JenisAnggota', 'JenisAnggotaName', 'StatusAnggota', 'StatusAnggotaName', 'Handphone', 'ParentName', 'ParentAddress', 'ParentPhone', 'ParentHandphone', 'Nationality', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal', 'AlamatDomisili', 'RT', 'RW', 'Kelurahan', 'Kecamatan', 'Kota', 'KodePos', 'NoHp', 'NamaDarurat', 'TelpDarurat', 'AlamatDarurat', 'StatusHubunganDarurat', 'City', 'Province', 'CityNow', 'ProvinceNow', 'JobNameDetail', 'namakelassiswa', 'tahunAjaran'], 'safe'],
            [['LoanReturnLateCount', 'Branch_id', 'User_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Members::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'DateOfBirth' => $this->DateOfBirth,
            'RegisterDate' => $this->RegisterDate,
            'EndDate' => $this->EndDate,
            'LoanReturnLateCount' => $this->LoanReturnLateCount,
            'Branch_id' => $this->Branch_id,
            'User_id' => $this->User_id,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
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
            ->andFilterWhere(['like', 'IdentityType', $this->IdentityType])
            ->andFilterWhere(['like', 'IdentityNo', $this->IdentityNo])
            ->andFilterWhere(['like', 'EducationLevel', $this->EducationLevel])
            ->andFilterWhere(['like', 'Religion', $this->Religion])
            ->andFilterWhere(['like', 'Sex', $this->Sex])
            ->andFilterWhere(['like', 'MaritalStatus', $this->MaritalStatus])
            ->andFilterWhere(['like', 'JobName', $this->JobName])
            ->andFilterWhere(['like', 'BarCode', $this->BarCode])
            ->andFilterWhere(['like', 'PicPath', $this->PicPath])
            ->andFilterWhere(['like', 'MotherMaidenName', $this->MotherMaidenName])
            ->andFilterWhere(['like', 'Email', $this->Email])
            ->andFilterWhere(['like', 'JenisPermohonan', $this->JenisPermohonan])
            ->andFilterWhere(['like', 'JenisPermohonanName', $this->JenisPermohonanName])
            ->andFilterWhere(['like', 'JenisAnggota', $this->JenisAnggota])
            ->andFilterWhere(['like', 'JenisAnggotaName', $this->JenisAnggotaName])
            ->andFilterWhere(['like', 'StatusAnggota', $this->StatusAnggota])
            ->andFilterWhere(['like', 'StatusAnggotaName', $this->StatusAnggotaName])
            ->andFilterWhere(['like', 'Handphone', $this->Handphone])
            ->andFilterWhere(['like', 'ParentName', $this->ParentName])
            ->andFilterWhere(['like', 'ParentAddress', $this->ParentAddress])
            ->andFilterWhere(['like', 'ParentPhone', $this->ParentPhone])
            ->andFilterWhere(['like', 'ParentHandphone', $this->ParentHandphone])
            ->andFilterWhere(['like', 'Nationality', $this->Nationality])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'AlamatDomisili', $this->AlamatDomisili])
            ->andFilterWhere(['like', 'RT', $this->RT])
            ->andFilterWhere(['like', 'RW', $this->RW])
            ->andFilterWhere(['like', 'Kelurahan', $this->Kelurahan])
            ->andFilterWhere(['like', 'Kecamatan', $this->Kecamatan])
            ->andFilterWhere(['like', 'Kota', $this->Kota])
            ->andFilterWhere(['like', 'KodePos', $this->KodePos])
            ->andFilterWhere(['like', 'NoHp', $this->NoHp])
            ->andFilterWhere(['like', 'NamaDarurat', $this->NamaDarurat])
            ->andFilterWhere(['like', 'TelpDarurat', $this->TelpDarurat])
            ->andFilterWhere(['like', 'AlamatDarurat', $this->AlamatDarurat])
            ->andFilterWhere(['like', 'StatusHubunganDarurat', $this->StatusHubunganDarurat])
            ->andFilterWhere(['like', 'City', $this->City])
            ->andFilterWhere(['like', 'Province', $this->Province])
            ->andFilterWhere(['like', 'CityNow', $this->CityNow])
            ->andFilterWhere(['like', 'ProvinceNow', $this->ProvinceNow])
            ->andFilterWhere(['like', 'JobNameDetail', $this->JobNameDetail])
            ->andFilterWhere(['like', 'namakelassiswa', $this->namakelassiswa])
            ->andFilterWhere(['like', 'tahunAjaran', $this->tahunAjaran]);

        return $dataProvider;
    }
}
