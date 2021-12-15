<?php

namespace common\models\base;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\base\PeraturanPeminjamanTanggal;

/**
 * PeraturanPeminjamanTanggalSearch represents the model behind the search form about `common\models\base\PeraturanPeminjamanTanggal`.
 */
class PeraturanPeminjamanTanggalSearch extends PeraturanPeminjamanTanggal
{
    public function rules()
    {
        return [
            [['ID', 'CreateBy', 'UpdateBy', 'MaxPinjamKoleksi', 'MaxLoanDays', 'DendaTenorMultiply', 'WarningLoanDueDay', 'DaySuspend', 'DayPerpanjang', 'CountPerpanjang'], 'integer'],
            [['TanggalAwal', 'TanggalAkhir', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'DendaTenorJumlah', 'DendaTenorSatuan'], 'safe'],
            [['DendaPerTenor'], 'number'],
            [['SuspendMember'], 'boolean'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PeraturanPeminjamanTanggal::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'CreateDate' => SORT_DESC,
                ],
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            // 'TanggalAwal' => $this->TanggalAwal,
            // 'TanggalAkhir' => $this->TanggalAkhir,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
            'MaxPinjamKoleksi' => $this->MaxPinjamKoleksi,
            'MaxLoanDays' => $this->MaxLoanDays,
            'DendaPerTenor' => $this->DendaPerTenor,
            'DendaTenorMultiply' => $this->DendaTenorMultiply,
            'SuspendMember' => $this->SuspendMember,
            'WarningLoanDueDay' => $this->WarningLoanDueDay,
            'DaySuspend' => $this->DaySuspend,
            'DayPerpanjang' => $this->DayPerpanjang,
            'CountPerpanjang' => $this->CountPerpanjang,
        ]);

        $query->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'DendaTenorJumlah', $this->DendaTenorJumlah])
            ->andFilterWhere(['like', 'DendaTenorSatuan', $this->DendaTenorSatuan])
            ->andFilterWhere(['like', "DATE_FORMAT(`TanggalAwal`,'%d-%m-%Y %H:%i:%s')", $this->TanggalAwal])
            ->andFilterWhere(['like', "DATE_FORMAT(`TanggalAkhir`,'%d-%m-%Y %H:%i:%s')", $this->TanggalAkhir])
            
            ;

        return $dataProvider;
    }
}
