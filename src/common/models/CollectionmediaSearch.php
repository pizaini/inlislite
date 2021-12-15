<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Collectionmedias;

/**
 * CollectionmediaSearch represents the model behind the search form about `common\models\Collectionmedias`.
 */
class CollectionmediaSearch extends Collectionmedias
{
   
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['Copies','Worksheet_id','Code', 'KodeBahanPustaka', 'Name', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }


    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Collectionmedias::find();
        $queryCollections = Collections::find()
                ->select('Media_id,count(ID) AS Copies')
                ->groupby('Media_id');
        $query->leftJoin(['collectionCount' => $queryCollections],' collectionCount.Media_id = collectionmedias.id');
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'ID',
                'Code',
                'Name',
                'Worksheet_id' => [
                    'asc' => ['worksheets.name' => SORT_ASC],
                    'desc' => ['worksheets.name' => SORT_DESC],
                    'label' => 'Lembar Kerja',
                    'default' => SORT_ASC
                ],
                'Copies' => [
                    'asc' => ['collectionCount.Copies' => SORT_ASC],
                    'desc' => ['collectionCount.Copies' => SORT_DESC],
                    'label' => Yii::t('app', 'Copies'),
                    'default' => SORT_ASC
                ],
            ]
        ]);
        
        
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->joinWith(['worksheet']);
        
        $query->andFilterWhere([
            'ID' => $this->ID,
            //'Worksheet_id' => $this->Worksheet_id,
            //'IsDelete' => $this->IsDelete,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'collectionmedias.Code', $this->Code])
            ->andFilterWhere(['like', 'collectionmedias.Name', $this->Name])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'worksheets.Name', $this->Worksheet_id])
            ->andFilterWhere(['like', 'collectionCount.Copies', $this->Copies]);

        return $dataProvider;
    }
}
