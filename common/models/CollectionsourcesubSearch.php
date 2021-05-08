<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Collectionsourcesub;

/**
 * CollectionsourcesubSearch represents the model behind the search form about `common\models\Collectionsourcesub`.
 */
class CollectionsourcesubSearch extends Collectionsourcesub
{
    public $JumlahKoleksi;

    public function rules()
    {
        return [
            [['ID', 'Sort_ID', 'CollectionSource_ID'], 'integer'],
            [['JumlahKoleksi','Name', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Collectionsourcesub::find();
        $queryCollections = Collections::find()
                ->select('CollectionSourceSub_id,count(ID) AS JumlahKoleksi');
        $query->leftJoin(['collectionCount' => $queryCollections],' collectionCount.CollectionSourceSub_id = collectionsourcesub.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'ID',
                'Name',
                'CollectionSource_ID' => [
                    'asc' => ['collectionSource.name' => SORT_ASC],
                    'desc' => ['collectionSource.name' => SORT_DESC],
                    'label' => 'Group',
                    'default' => SORT_ASC
                ],
                'JumlahKoleksi' => [
                    'asc' => ['collectionCount.JumlahKoleksi' => SORT_ASC],
                    'desc' => ['collectionCount.JumlahKoleksi' => SORT_DESC],
                    'label' => 'Jumlah Koleksi',
                    'default' => SORT_ASC
                ],
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->joinWith(['collectionSource']);

        $query->andFilterWhere([
            'ID' => $this->ID,
            'Sort_ID' => $this->Sort_ID,
            'CollectionSource_ID' => $this->CollectionSource_ID,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
