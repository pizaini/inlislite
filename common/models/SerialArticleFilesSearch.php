<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use common\models\SerialArticlefiles;

/**
 * SerialArticleFilesSearch represents the model behind the search form about `common\models\SerialArticlefiles`.
 */
class SerialArticleFilesSearch extends SerialArticlefiles
{
    public function rules()
    {
        return [
            [['ID', 'IsPublish', 'CreateBy', 'UpdateBy'], 'integer'],
            [['Articles_id', 'FileURL', 'FileFlash', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'Member_id'], 'safe'],
            [['IsFromMember'], 'boolean'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = SerialArticlefiles::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'IsPublish' => $this->IsPublish,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
            'IsFromMember' => $this->IsFromMember,
        ]);

        $query->andFilterWhere(['like', 'Articles_id', $this->Articles_id])
            ->andFilterWhere(['like', 'FileURL', $this->FileURL])
            ->andFilterWhere(['like', 'FileFlash', $this->FileFlash])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'Member_id', $this->Member_id]);

        return $dataProvider;
    }

    public function search2($params)
    {
        $articleID = $params['ArticleID'];
        $sqlSearch = "SELECT * FROM serial_articlefiles WHERE articles_id =".$articleID." ";
        $dataProvider = new SqlDataProvider([
            'sql' => $sqlSearch,
            'pagination' => false,
        ]);

        $dataProvider->setSort(false);
        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function searchByArticleID($params)
    {
        $sqlSearch = "SELECT * FROM serial_articlefiles WHERE articles_id =".$params." ";
        $dataProvider = new SqlDataProvider([
            'sql' => $sqlSearch,
            'pagination' => false,
        ]);

        $dataProvider->setSort(false);
        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
