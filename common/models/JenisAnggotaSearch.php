<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\JenisAnggota;

/**
 * JenisAnggotaSearch represents the model behind the search form about `common\models\JenisAnggota`.
 */
class JenisAnggotaSearch extends JenisAnggota
{
    public function rules()
    {
        return [
            [['id', 'MasaBerlakuAnggota', 'CreateBy', 'UpdateBy', 'MaxPinjamKoleksi', 'MaxLoanDays', 'DendaTenorMultiply', 'WarningLoanDueDay', 'DaySuspend', 'SuspendTenorMultiply', 'DayPerpanjang', 'CountPerpanjang'], 'integer'],
            [['jenisanggota', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'DendaType', 'DendaTenorJumlah', 'DendaTenorSatuan', 'SuspendType', 'SuspendTenorJumlah', 'SuspendTenorSatuan', 'KIILastUploadDate'], 'safe'],
            [['BiayaPendaftaran', 'BiayaPerpanjangan', 'DendaPerTenor'], 'number'],
            [['UploadDokumenKeanggotaanOnline', 'SuspendMember'], 'boolean'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = JenisAnggota::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            // 'MasaBerlakuAnggota' => $this->MasaBerlakuAnggota,
            // 'CreateBy' => $this->CreateBy,
            // 'CreateDate' => $this->CreateDate,
            // 'UpdateBy' => $this->UpdateBy,
            // 'UpdateDate' => $this->UpdateDate,
            // 'BiayaPendaftaran' => $this->BiayaPendaftaran,
            // 'BiayaPerpanjangan' => $this->BiayaPerpanjangan,
            'UploadDokumenKeanggotaanOnline' => $this->UploadDokumenKeanggotaanOnline,
            // 'MaxPinjamKoleksi' => $this->MaxPinjamKoleksi,
            // 'MaxLoanDays' => $this->MaxLoanDays,
            // 'DendaPerTenor' => $this->DendaPerTenor,
            // 'DendaTenorMultiply' => $this->DendaTenorMultiply,
            // 'SuspendMember' => $this->SuspendMember,
            // 'WarningLoanDueDay' => $this->WarningLoanDueDay,
            // 'DaySuspend' => $this->DaySuspend,
            // 'SuspendTenorMultiply' => $this->SuspendTenorMultiply,
            // 'DayPerpanjang' => $this->DayPerpanjang,
            // 'CountPerpanjang' => $this->CountPerpanjang,
            // 'KIILastUploadDate' => $this->KIILastUploadDate,
        ]);

        $query->andFilterWhere(['like', 'jenisanggota', $this->jenisanggota])
           ->andFilterWhere(['like', 'MasaBerlakuAnggota', $this->MasaBerlakuAnggota])
            ->andFilterWhere(['like', 'BiayaPendaftaran', $this->BiayaPendaftaran])
            ->andFilterWhere(['like', 'BiayaPerpanjangan', $this->BiayaPerpanjangan])
            ->andFilterWhere(['like', 'MaxPinjamKoleksi', $this->MaxPinjamKoleksi])
            // ->andFilterWhere(['like', 'Upload DokumenKeanggotaanOnline', $this->UploadDokumenKeanggotaanOnline])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'SuspendTenorSatuan', $this->SuspendTenorSatuan]);

        return $dataProvider;
    }
}
