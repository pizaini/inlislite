<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\models\Collectionloans;


/**
 * PeminjamanSearch represents the model behind the search form about `common\models\Collectionloans`.
 */
class PeminjamanSearch extends Collectionloans
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'Member_id'], 'required'],
            [['CollectionCount', 'LateCount', 'ExtendCount', 'LoanCount', 'ReturnCount', 'Branch_id', 'CreateBy', 'UpdateBy'], 'integer'],
            [['Member_id'], 'number'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['ID'], 'string', 'max' => 255],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['Member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['Member_id' => 'ID']],
            [['Branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branchs::className(), 'targetAttribute' => ['Branch_id' => 'ID']],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']]
        ];
    }


    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Collectionloans::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'CollectionCount', $this->CollectionCount])
            ->andFilterWhere(['like', 'LateCount', $this->LateCount])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
