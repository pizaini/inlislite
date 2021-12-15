<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Collectionrules;

/**
 * CollectionruleSearch represents the model behind the search form about `common\models\Collectionrules`.
 */
class CollectionruleSearch extends Collectionrules
{
    public $Copies;
    public function rules()
    {
        return [
            [['ID', 'CreateBy', 'UpdateBy'], 'integer'],
            [['Copies','Name', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'KIILastUploadDate'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Collectionrules::find();
        $queryCollections = Collections::find()
                ->select('Rule_id,count(ID) AS Copies')
                ->groupby('Rule_id');
        $query->leftJoin(['collectionCount' => $queryCollections],' collectionCount.Rule_id = collectionrules.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'attributes' => [
                'Copies','Name', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'KIILastUploadDate',
                'Copies' => [
                    'asc' => ['collectionCount.Copies' => SORT_ASC],
                    'desc' => ['collectionCount.Copies' => SORT_DESC],
                    'label' => 'Jumlah Koleksi',
                    'default' => SORT_ASC
                ],
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
            'KIILastUploadDate' => $this->KIILastUploadDate,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'collectionCount.Copies', $this->Copies]);

        return $dataProvider;
    }
}
