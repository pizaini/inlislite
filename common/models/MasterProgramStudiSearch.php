<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MasterProgramStudi;

/**
 * MasterProgramStudiSearch represents the model behind the search form about `common\models\MasterProgramStudi`.
 */
class MasterProgramStudiSearch extends MasterProgramStudi
{
    public $jurusan;

    public function rules()
    {
        return [
            [['id', 'id_jurusan', 'CreateBy', 'UpdateBy'], 'integer'],
            [['jurusan','Nama', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'KIILastUploadDate'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = MasterProgramStudi::find();
        $query->innerJoin('master_jurusan',' master_jurusan.id=master_program_studi.id_jurusan');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->setSort([
          'attributes' => [
            'jurusan','Nama', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'KIILastUploadDate'
          ]
      ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_jurusan' => $this->id_jurusan,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
            'KIILastUploadDate' => $this->KIILastUploadDate,
        ]);

        $query->andFilterWhere(['like', 'master_program_studi.Nama', $this->Nama])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'master_jurusan.Nama', $this->jurusan]);

        return $dataProvider;
    }
}
