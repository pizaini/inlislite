<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\base\Pelanggaran;

/**
 * PelanggaranSearch represents the model behind the search form about `common\models\base\Pelanggaran`.
 */
class PelanggaranSearch extends Pelanggaran
{
    public function rules()
    {
        return [
            [['ID', 'JenisPelanggaran_id', 'JenisDenda_id', 'CreateBy', 'UpdateBy', 'JumlahSuspend'], 'integer'],
            [['CollectionLoan_id', 'CollectionLoanItem_id', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'Member_id', 'Collection_id'], 'safe'],
            [['JumlahDenda'], 'number'],
            [['Paid'], 'boolean'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Pelanggaran::find();
		
		if($params['Member_id'])
		{
			$query->andWhere(['Member_id'=>$params['Member_id']]);
		}
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'JenisPelanggaran_id' => $this->JenisPelanggaran_id,
            'JenisDenda_id' => $this->JenisDenda_id,
            'JumlahDenda' => $this->JumlahDenda,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
            'JumlahSuspend' => $this->JumlahSuspend,
            'Paid' => $this->Paid,
        ]);

        $query->andFilterWhere(['like', 'CollectionLoan_id', $this->CollectionLoan_id])
            ->andFilterWhere(['like', 'CollectionLoanItem_id', $this->CollectionLoanItem_id])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['=', 'Member_id', $this->Member_id])
            ->andFilterWhere(['like', 'Collection_id', $this->Collection_id]);

        return $dataProvider;
    }

    public function searchMember($params)
    {
        $query = Pelanggaran::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'JenisPelanggaran_id' => $this->JenisPelanggaran_id,
            'JenisDenda_id' => $this->JenisDenda_id,
            'JumlahDenda' => $this->JumlahDenda,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
            'JumlahSuspend' => $this->JumlahSuspend,
            'Paid' => $this->Paid,
        ]);

        $query->andFilterWhere(['like', 'CollectionLoan_id', $this->CollectionLoan_id])
            ->andFilterWhere(['like', 'CollectionLoanItem_id', $this->CollectionLoanItem_id])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['=', 'Member_id', $this->Member_id])
            ->andFilterWhere(['like', 'Collection_id', $this->Collection_id]);

        return $dataProvider;
    }
}