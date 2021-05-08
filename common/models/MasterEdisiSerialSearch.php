<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MasterEdisiSerial;

/**
 * MasterEdisiSerialSearch represents the model behind the search form about `common\models\MasterEdisiSerial`.
 */
class MasterEdisiSerialSearch extends MasterEdisiSerial
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'number'],
            [['tgl_edisi_serial'], 'safe'],
            [['no_edisi_serial', 'CreateBy', 'UpdateBy'], 'integer'],
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
        $query = MasterEdisiSerial::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tgl_edisi_serial' => $this->tgl_edisi_serial,
            'no_edisi_serial' => $this->no_edisi_serial,
            'CreateBy' => $this->CreateBy,
            'UpdateBy' => $this->UpdateBy,
        ]);

        return $dataProvider;
    }

    public function advancedSearchByCatalogId($id,$rules)
    {
        $query = MasterEdisiSerial::find();
        $query->where(['Catalog_Id'=>$id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($rules) {
            //bypass when type date
            $rules=\common\components\Helpers::changeRulesDateAdvanceSearch($rules);
            $translator = new Translator($rules);
            $query
                ->andWhere($translator->where())
                ->addParams($translator->params());
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tgl_edisi_serial' => $this->tgl_edisi_serial,
            'no_edisi_serial' => $this->no_edisi_serial,
            'CreateBy' => $this->CreateBy,
            'UpdateBy' => $this->UpdateBy,
        ]);

        return $dataProvider;
    }
}
