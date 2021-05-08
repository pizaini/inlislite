<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Lockers;
use yii\db\Expression;

/**
 * LockersSearch represents the model behind the search form about `common\models\Lockers`.
 */
class LockersSearch extends Lockers
{
    public $statusAnggota;
    public $kembali;
    public function rules()
    {
        return [
            [['ID',  'no_identitas'], 'integer'],
            [['No_pinjaman','no_member', 'jenis_jaminan','id_jamin_idt', 'id_jamin_uang', 'loker_id','id_pelanggaran_locker' , 'tanggal_pinjam', 'tanggal_kembali', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal','kembali','statusAnggota'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Lockers::find()
		->joinWith('jenisIdentitas')
		->joinWith('uangJaminan')
		->joinWith('loker')
		->joinWith('pelanggaran')
        ;
		//->leftJoin('master_jenis_identitas','lockers.id_jamin_idt = master_jenis_identitas.id');

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
            'no_member' => $this->no_member,
            'no_identitas' => $this->no_identitas,
           // 'id_jamin_idt' => $this->id_jamin_idt,
            //'id_jamin_uang' => $this->id_jamin_uang,
            //'loker_id' => $this->loker_id,
            // 'tanggal_pinjam' => $this->tanggal_pinjam,
            // 'tanggal_kembali' => $this->tanggal_kembali,
            //'id_pelanggaran_locker' => $this->id_pelanggaran_locker,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'No_pinjaman', $this->No_pinjaman])
          //  ->andFilterWhere(['like', 'no_member', $this->no_member])
            ->andFilterWhere(['like', 'jenis_jaminan', $this->jenis_jaminan])
            ->andFilterWhere(['like', "DATE_FORMAT(`tanggal_kembali`,'%d-%m-%Y %H:%i:%s')", $this->tanggal_kembali])
            ->andFilterWhere(['like', "DATE_FORMAT(`tanggal_pinjam`,'%d-%m-%Y %H:%i:%s')", $this->tanggal_pinjam])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
			->andFilterWhere(['like', 'master_jenis_identitas.Nama', $this->id_jamin_idt])
			->andFilterWhere(['like', 'master_uang_jaminan.Name', $this->id_jamin_uang])
			->andFilterWhere(['like', 'master_loker.Name', $this->loker_id])
			->andFilterWhere(['like', 'master_pelanggaran_locker.jenis_pelanggaran', $this->id_pelanggaran_locker])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

            if ($this->kembali)
            {
                $query->andFilterWhere(['IS', 'lockers.tanggal_kembali', (new Expression($this->kembali))]);
            }

        return $dataProvider;
    }


    public function getLoker(){

       $query = Lockers::find()
        ->joinWith('jenisIdentitas')
        ->joinWith('uangJaminan')
        ->joinWith('loker')
        ->joinWith('pelanggaran');
        //->leftJoin('master_jenis_identitas','lockers.id_jamin_idt = master_jenis_identitas.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

      
        $query->andFilterWhere([
            'ID' => $this->ID,
            'no_member' => $this->no_member,
            'no_identitas' => $this->no_identitas,
            'tanggal_pinjam' => $this->tanggal_pinjam,
            'tanggal_kembali' => $this->tanggal_kembali,
            //'id_pelanggaran_locker' => $this->id_pelanggaran_locker,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'No_pinjaman', $this->No_pinjaman])
            ->andFilterWhere(['like', 'no_member', $this->no_member])
            ->andFilterWhere(['like', 'tanggal_kembali', $this->tanggal_kembali])
            ->andFilterWhere(['like', 'jenis_jaminan', $this->jenis_jaminan])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'master_jenis_identitas.Nama', $this->id_jamin_idt])
            ->andFilterWhere(['like', 'master_uang_jaminan.Name', $this->id_jamin_uang])
            ->andFilterWhere(['like', 'master_loker.Name', $this->loker_id])
            ->andFilterWhere(['like', 'master_pelanggaran_locker.jenis_pelanggaran', $this->id_pelanggaran_locker])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);


            if ($this->statusAnggota)
            {
                $query->andFilterWhere(['IS', 'tanggal_kembali', (new Expression($this->kembali))]);
            }

        return $dataProvider;
    }
    public function getLokerPelanggaran(){

       $query = Lockers::find()
        ->where(['not',['id_pelanggaran_locker'=>null]])
        ->joinWith('jenisIdentitas')
        ->joinWith('uangJaminan')
        ->joinWith('loker')
        ->joinWith('pelanggaran');
        //->leftJoin('master_jenis_identitas','lockers.id_jamin_idt = master_jenis_identitas.id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

      
        $query->andFilterWhere([
            'ID' => $this->ID,
            'no_member' => $this->no_member,
            'no_identitas' => $this->no_identitas,
            'tanggal_pinjam' => $this->tanggal_pinjam,
            'tanggal_kembali' => $this->tanggal_kembali,
            //'id_pelanggaran_locker' => $this->id_pelanggaran_locker,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'No_pinjaman', $this->No_pinjaman])
            ->andFilterWhere(['like', 'no_member', $this->no_member])
            ->andFilterWhere(['like', 'tanggal_kembali', $this->tanggal_kembali])
            ->andFilterWhere(['like', 'jenis_jaminan', $this->jenis_jaminan])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'master_jenis_identitas.Nama', $this->id_jamin_idt])
            ->andFilterWhere(['like', 'master_uang_jaminan.Name', $this->id_jamin_uang])
            ->andFilterWhere(['like', 'master_loker.Name', $this->loker_id])
            ->andFilterWhere(['like', 'master_pelanggaran_locker.jenis_pelanggaran', $this->id_pelanggaran_locker])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);


            if ($this->statusAnggota)
            {
                $query->andFilterWhere(['IS', 'tanggal_kembali', (new Expression($this->kembali))]);
            }

        return $dataProvider;
    }
}
