<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MasterKependudukan;
use leandrogehlen\querybuilder\Translator;
/**
 * MasterKependudukanSearch represents the model behind the search form about `common\models\MasterKependudukan`.
 */
class MasterKependudukanSearch extends MasterKependudukan
{
    public $agamas;
    public function rules()
    {
        return [
            [['id', 'jk', 'status', 'agama', 'CreateBy', 'UpdateBy'], 'integer'],
            [['nomorkk', 'nik', 'namalengkap', 'al1', 'rt', 'rw', 'kodekec', 'kodekel', 'alamat', 'lhrtempat', 'lhrtanggal', 'ttl', 'umur', 'jenis', 'sts', 'hub', 'agm', 'pendidikan', 'pekerjaan', 'klain_fisik', 'aktalhr', 'aktakawin', 'aktacerai', 'nocacat', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal','agamas'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = MasterKependudukan::find()->leftJoin('agama','master_kependudukan.agama = agama.ID');

        /* if ($rules) {
              $translator = new Translator($rules);
              $query
                ->andWhere($translator->where())
                ->addParams($translator->params());
          }*/
          
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => 
            [
                'defaultOrder' => [
                    'CreateDate' => SORT_DESC,
                ],
                    'attributes' => [
                    'nomorkk', 'nik', 'namalengkap', 'al1', 'rt', 'rw', 'kodekec', 'kodekel', 'alamat', 'lhrtempat', 'lhrtanggal', 'ttl', 'umur', 'jenis', 'sts', 'hub', 'agm', 'pendidikan', 'pekerjaan', 'klain_fisik', 'aktalhr', 'aktakawin', 'aktacerai', 'nocacat', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal',
                    'agamas' => [
                        'asc' => ['agama.Name' => SORT_ASC],
                        'desc' => ['agama.Name' => SORT_DESC],
                        'label' => Yii::t('app', 'Agama'),
                        'default' => SORT_ASC
                    ],
                ],
            ],

        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'jk' => $this->jk,
            'status' => $this->status,
            'agama' => $this->agama,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'nomorkk', $this->nomorkk])
            ->andFilterWhere(['like', 'nik', $this->nik])
            ->andFilterWhere(['like', 'namalengkap', $this->namalengkap])
            ->andFilterWhere(['like', 'al1', $this->al1])
            ->andFilterWhere(['like', 'rt', $this->rt])
            ->andFilterWhere(['like', 'rw', $this->rw])
            ->andFilterWhere(['like', 'kodekec', $this->kodekec])
            ->andFilterWhere(['like', 'kodekel', $this->kodekel])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'lhrtempat', $this->lhrtempat])
            ->andFilterWhere(['like', 'lhrtanggal', $this->lhrtanggal])
            ->andFilterWhere(['like', 'ttl', $this->ttl])
            ->andFilterWhere(['like', 'umur', $this->umur])
            ->andFilterWhere(['like', 'jenis', $this->jenis])
            ->andFilterWhere(['like', 'sts', $this->sts])
            ->andFilterWhere(['like', 'hub', $this->hub])
            ->andFilterWhere(['like', 'agm', $this->agm])
            ->andFilterWhere(['like', 'pendidikan', $this->pendidikan])
            ->andFilterWhere(['like', 'pekerjaan', $this->pekerjaan])
            ->andFilterWhere(['like', 'klain_fisik', $this->klain_fisik])
            ->andFilterWhere(['like', 'aktalhr', $this->aktalhr])
            ->andFilterWhere(['like', 'aktakawin', $this->aktakawin])
            ->andFilterWhere(['like', 'aktacerai', $this->aktacerai])
            ->andFilterWhere(['like', 'nocacat', $this->nocacat])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'agama.Name', $this->agamas]);

        return $dataProvider;
    }
}