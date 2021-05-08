<?php

namespace common\models;

use common\components\OpacHelpers;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SerialArticles;
use common\models\SerialArticlefiles;

use leandrogehlen\querybuilder\Translator;

/**
 * SerialArticlesSearch represents the model behind the search form about `common\models\SerialArticles`.
 */
class SerialArticlesSearch extends SerialArticles
{
    public function rules()
    {
        return [
            [['id', 'Article_type', 'Title', 'Creator', 'Contributor', 'Subject', 'DDC', 'Call_Number', 'EDISISERIAL', 'TANGGAL_TERBIT_EDISI_SERIAL', 'Catalog_id', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal'], 'safe'],
            [['StartPage', 'Pages', 'CreateBy', 'UpdateBy'], 'integer'],
            [['ISOPAC'], 'boolean'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = SerialArticles::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'StartPage' => $this->StartPage,
            'Pages' => $this->Pages,
            'TANGGAL_TERBIT_EDISI_SERIAL' => $this->TANGGAL_TERBIT_EDISI_SERIAL,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
            'ISOPAC' => $this->ISOPAC,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'Article_type', $this->Article_type])
            ->andFilterWhere(['like', 'Title', $this->Title])
            ->andFilterWhere(['like', 'Creator', $this->Creator])
            ->andFilterWhere(['like', 'Contributor', $this->Contributor])
            ->andFilterWhere(['like', 'Subject', $this->Subject])
            ->andFilterWhere(['like', 'DDC', $this->DDC])
            ->andFilterWhere(['like', 'Call_Number', $this->Call_Number])
            ->andFilterWhere(['like', 'EDISISERIAL', $this->EDISISERIAL])
            ->andFilterWhere(['like', 'Catalog_id', $this->Catalog_id])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }

    public function advancedSearch($rules)
    {
        $query = SerialArticles::find();

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

        $query->andFilterWhere([
            'StartPage' => $this->StartPage,
            'Pages' => $this->Pages,
            'TANGGAL_TERBIT_EDISI_SERIAL' => $this->TANGGAL_TERBIT_EDISI_SERIAL,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
            'ISOPAC' => $this->ISOPAC,
        ]);



        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'Article_type', $this->Article_type])
            ->andFilterWhere(['like', 'Title', $this->Title])
            ->andFilterWhere(['like', 'Creator', $this->Creator])
            ->andFilterWhere(['like', 'Contributor', $this->Contributor])
            ->andFilterWhere(['like', 'Subject', $this->Subject])
            ->andFilterWhere(['like', 'DDC', $this->DDC])
            ->andFilterWhere(['like', 'Call_Number', $this->Call_Number])
            ->andFilterWhere(['like', 'EDISISERIAL', $this->EDISISERIAL])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }

    public function advancedSearchByCatalogId($id,$rules)
    {
        $query = SerialArticles::find();
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

        $query->andFilterWhere([
            'StartPage' => $this->StartPage,
            'Pages' => $this->Pages,
            'TANGGAL_TERBIT_EDISI_SERIAL' => $this->TANGGAL_TERBIT_EDISI_SERIAL,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
            'ISOPAC' => $this->ISOPAC,
        ]);



        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'Article_type', $this->Article_type])
            ->andFilterWhere(['like', 'Title', $this->Title])
            ->andFilterWhere(['like', 'Creator', $this->Creator])
            ->andFilterWhere(['like', 'Contributor', $this->Contributor])
            ->andFilterWhere(['like', 'Subject', $this->Subject])
            ->andFilterWhere(['like', 'DDC', $this->DDC])
            ->andFilterWhere(['like', 'Call_Number', $this->Call_Number])
            ->andFilterWhere(['like', 'EDISISERIAL', $this->EDISISERIAL])
            ->andFilterWhere(['like', 'Catalog_id', $this->Catalog_id])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }

    public function advancedSearchWithKontenDigitalByCatalogId($id,$rules)
    {
        $subQuery = SerialArticlefiles::find()->select('Articles_id');
        $query = SerialArticles::find();
        $query->where(['Catalog_Id'=>$id]);
        $query->andwhere(['in', 'id', $subQuery]);

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

        $query->andFilterWhere([
            'StartPage' => $this->StartPage,
            'Pages' => $this->Pages,
            'TANGGAL_TERBIT_EDISI_SERIAL' => $this->TANGGAL_TERBIT_EDISI_SERIAL,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
            'ISOPAC' => $this->ISOPAC,
        ]);



        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'Article_type', $this->Article_type])
            ->andFilterWhere(['like', 'Title', $this->Title])
            ->andFilterWhere(['like', 'Creator', $this->Creator])
            ->andFilterWhere(['like', 'Contributor', $this->Contributor])
            ->andFilterWhere(['like', 'Subject', $this->Subject])
            ->andFilterWhere(['like', 'DDC', $this->DDC])
            ->andFilterWhere(['like', 'Call_Number', $this->Call_Number])
            ->andFilterWhere(['like', 'EDISISERIAL', $this->EDISISERIAL])
            ->andFilterWhere(['like', 'Catalog_id', $this->Catalog_id])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
