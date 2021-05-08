<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LetterDetail;

/**
 * LetterDetailSearch represents the model behind the search form about `common\models\LetterDetail`.
 */
class LetterDetailSearch extends LetterDetail
{
    public function rules()
    {
        return [
            [['ID', 'QUANTITY', 'COPY', 'LETTER_ID', 'COLLECTION_TYPE_ID'], 'integer'],
            [['SUB_TYPE_COLLECTION', 'TITLE', 'PRICE', 'REMARK', 'AUTHOR', 'PUBLISHER', 'PUBLISHER_ADDRESS', 'ISBN', 'PUBLISH_YEAR', 'PUBLISHER_CITY', 'ISBN_STATUS', 'KD_PENERBIT_DTL'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = LetterDetail::find()
                ->joinWith('letter')
                ->joinWith('collectionmedias')
                // ->where(['collections.IsDeposit' => '1'])
                ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'QUANTITY' => $this->QUANTITY,
            'COPY' => $this->COPY,
            'LETTER_ID' => $this->LETTER_ID,
            'COLLECTION_TYPE_ID' => $this->COLLECTION_TYPE_ID,
        ]);

        $query->andFilterWhere(['like', 'SUB_TYPE_COLLECTION', $this->SUB_TYPE_COLLECTION])
            ->andFilterWhere(['like', 'TITLE', $this->TITLE])
            ->andFilterWhere(['like', 'PRICE', $this->PRICE])
            ->andFilterWhere(['like', 'REMARK', $this->REMARK])
            ->andFilterWhere(['like', 'AUTHOR', $this->AUTHOR])
            ->andFilterWhere(['like', 'PUBLISHER', $this->PUBLISHER])
            ->andFilterWhere(['like', 'PUBLISHER_ADDRESS', $this->PUBLISHER_ADDRESS])
            ->andFilterWhere(['like', 'ISBN', $this->ISBN])
            ->andFilterWhere(['like', 'PUBLISH_YEAR', $this->PUBLISH_YEAR])
            ->andFilterWhere(['like', 'PUBLISHER_CITY', $this->PUBLISHER_CITY])
            ->andFilterWhere(['like', 'ISBN_STATUS', $this->ISBN_STATUS])
            ->andFilterWhere(['like', 'KD_PENERBIT_DTL', $this->KD_PENERBIT_DTL]);

        return $dataProvider;
    }

    public function searchDetail($params,$id)
    {
        $query = LetterDetail::find()
                ->joinWith('letter')
                ->where(['letter_detail.LETTER_ID' => $id])
                ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'QUANTITY' => $this->QUANTITY,
            'COPY' => $this->COPY,
            'LETTER_ID' => $this->LETTER_ID,
            'COLLECTION_TYPE_ID' => $this->COLLECTION_TYPE_ID,
        ]);

        $query->andFilterWhere(['like', 'SUB_TYPE_COLLECTION', $this->SUB_TYPE_COLLECTION])
            ->andFilterWhere(['like', 'TITLE', $this->TITLE])
            ->andFilterWhere(['like', 'PRICE', $this->PRICE])
            ->andFilterWhere(['like', 'REMARK', $this->REMARK])
            ->andFilterWhere(['like', 'AUTHOR', $this->AUTHOR])
            ->andFilterWhere(['like', 'PUBLISHER', $this->PUBLISHER])
            ->andFilterWhere(['like', 'PUBLISHER_ADDRESS', $this->PUBLISHER_ADDRESS])
            ->andFilterWhere(['like', 'ISBN', $this->ISBN])
            ->andFilterWhere(['like', 'PUBLISH_YEAR', $this->PUBLISH_YEAR])
            ->andFilterWhere(['like', 'PUBLISHER_CITY', $this->PUBLISHER_CITY])
            ->andFilterWhere(['like', 'ISBN_STATUS', $this->ISBN_STATUS])
            ->andFilterWhere(['like', 'KD_PENERBIT_DTL', $this->KD_PENERBIT_DTL]);

        return $dataProvider;
    }
}
