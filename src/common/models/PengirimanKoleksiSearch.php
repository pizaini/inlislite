<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PengirimanKoleksi;

/**
 * PengirimanKoleksiSearch represents the model behind the search form about `\common\models\PengirimanKoleksi`.
 */
class PengirimanKoleksiSearch extends PengirimanKoleksi
{
    public function rules()
    {
        return [
            [['ID', 'QUANTITY', 'CreateBy', 'UpdateBy'], 'integer'],
            [['BIBID', 'JUDUL', 'TAHUNTERBIT', 'CALLNUMBER', 'NOBARCODE', 'NOINDUK', 'TANGGALKIRIM', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        // echo'<pre>';print_r($params);die;
        $query = PengirimanKoleksi::find();

        if(isset($params['PengirimanKoleksiSearch'])){
            $query->where('PengirimanID = "'.$params['PengirimanKoleksiSearch']['PengirimanID'].'"');
            $query->andwhere('IsCheck = 1');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'QUANTITY' => $this->QUANTITY,
            'TANGGALKIRIM' => $this->TANGGALKIRIM,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'BIBID', $this->BIBID])
            ->andFilterWhere(['like', 'JUDUL', $this->JUDUL])
            ->andFilterWhere(['like', 'TAHUNTERBIT', $this->TAHUNTERBIT])
            ->andFilterWhere(['like', 'CALLNUMBER', $this->CALLNUMBER])
            ->andFilterWhere(['like', 'NOBARCODE', $this->NOBARCODE])
            ->andFilterWhere(['like', 'NOINDUK', $this->NOINDUK])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }

    public function advancedSearch($params){
        $query = PengirimanKoleksi::find();

        $Periode = "(pengiriman_koleksi.TANGGALKIRIM BETWEEN '".date("Y-m-d", strtotime($params['FromDate']) )."' AND '".date("Y-m-d", strtotime($params['EndDate']) )."') ";

        $query->where($Periode);
        $query->andWhere("PengirimanID IS NULL");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}
