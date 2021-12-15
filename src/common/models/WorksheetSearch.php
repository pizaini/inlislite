<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Worksheets;

/**
 * WorksheetSearch represents the model behind the search form about `common\models\Worksheets`.
 */
class WorksheetSearch extends Worksheets
{
    public function rules()
    {
        return [
            [['ID', 'Format_id', 'NoUrut'], 'integer'],
            [['TotalCatalogs','Name', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal', 'DEPOSITFORMAT_CODE', 'CODE'], 'safe'],
            [['ISSERIAL'], 'boolean'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Worksheets::find();
        $queryCatalogs = Catalogs::find()
                ->select('Worksheet_id,count(ID) AS TotalCatalogs')
                ->groupby('Worksheet_id');
        $query->leftJoin(['catalogCount' => $queryCatalogs],' catalogCount.Worksheet_id = worksheets.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'Name',
                'TotalCatalogs' => [
                    'asc' => ['catalogCount.TotalCatalogs' => SORT_ASC],
                    'desc' => ['catalogCount.TotalCatalogs' => SORT_DESC],
                    'label' => Yii::t('app', 'Total Catalogs'),
                    'default' => SORT_ASC
                ],
            ]
        ]);
        
        
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'Format_id' => $this->Format_id,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
            'NoUrut' => $this->NoUrut,
            'ISSERIAL' => $this->ISSERIAL,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'DEPOSITFORMAT_CODE', $this->DEPOSITFORMAT_CODE])
            ->andFilterWhere(['like', 'CODE', $this->CODE])
            ->andFilterWhere(['like', 'catalogCount.TotalCatalogs', $this->TotalCatalogs]);;

        return $dataProvider;
    }
}
