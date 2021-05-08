<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Requestcatalog;
use leandrogehlen\querybuilder\Translator;

/**
 * RequestcatalogSearch represents the model behind the search form about `common\models\Requestcatalog`.
 */
class RequestcatalogSearch extends Requestcatalog
{
    public function rules()
    {
        return [
            [['ID', 'CreateBy', 'UpdateBy', 'WorksheetID'], 'integer'],
            [['Type', 'Title', 'Subject', 'Author', 'PublishLocation', 'PublishYear', 'Publisher', 'Comments', 'MemberID', 'CallNumber', 'ControlNumber', 'DateRequest', 'Status', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Requestcatalog::find()->orderby('CreateDate DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'DateRequest' => $this->DateRequest,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
            'WorksheetID' => $this->WorksheetID,
        ]);

        $query->andFilterWhere(['like', 'Type', $this->Type])
            ->andFilterWhere(['like', 'Title', $this->Title])
            ->andFilterWhere(['like', 'Subject', $this->Subject])
            ->andFilterWhere(['like', 'Author', $this->Author])
            ->andFilterWhere(['like', 'PublishLocation', $this->PublishLocation])
            ->andFilterWhere(['like', 'PublishYear', $this->PublishYear])
            ->andFilterWhere(['like', 'Publisher', $this->Publisher])
            ->andFilterWhere(['like', 'Comments', $this->Comments])
            ->andFilterWhere(['like', 'MemberID', $this->MemberID])
            ->andFilterWhere(['like', 'CallNumber', $this->CallNumber])
            ->andFilterWhere(['like', 'ControlNumber', $this->ControlNumber])
            ->andFilterWhere(['like', 'Status', $this->Status])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }

    public function advancedSearch($rules)
    {
        $query = Requestcatalog::find()
                ->addSelect(["requestcatalog.*", "CONCAT(requestcatalog.PublishLocation,' : ', requestcatalog.Publisher, ', ',requestcatalog.PublishYear) AS Publishment"]);
        if ($rules) {
            //bypass when type date
            $rules=\common\components\Helpers::changeRulesDateAdvanceSearch($rules);
            $translator = new Translator($rules);
            $query
                ->andWhere($translator->where())
                ->addParams($translator->params());
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'Title',
                'Author',
                'Publishment',
                'MemberID',
                'DateRequest',
                'Status'
                
            ]
        ]);
        
        
        return $dataProvider;
    }
}
