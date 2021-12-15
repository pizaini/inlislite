<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Fields;

/**
 * FieldSearch represents the model behind the search form about `common\models\Fields`.
 */
class FieldSearch extends Fields
{
    public function rules()
    {
        return [
            [['ID', 'Fixed', 'Enabled', 'Length', 'Repeatable', 'Mandatory', 'IsCustomable'], 'integer'],
            [['Format_id', 'Group_id','Tag', 'Name', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal', 'DEFAULTSUBTAG'], 'safe'],
            [['ISSUBSERIAL'], 'boolean'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Fields::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'Tag',
                'Name',
                'Group_id' => [
                    'asc' => ['fieldgroups.Name' => SORT_ASC],
                    'desc' => ['fieldgroups.Name' => SORT_DESC],
                    'label' => 'Group',
                    'default' => SORT_ASC
                ],
                'Format_id' => [
                    'asc' => ['formats.Name' => SORT_ASC],
                    'desc' => ['formats.Name' => SORT_DESC],
                    'label' => 'Group',
                    'default' => SORT_ASC
                ],
                'Fixed',
                'Enabled',
                'Length',
                'Repeatable',
                'Mandatory',
                'IsCustomable',
            ]
        ]);
        
        
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->joinWith(['group']);
        $query->joinWith(['format']);

        $query->andFilterWhere([
            'ID' => $this->ID,
            'Fixed' => $this->Fixed,
            'Enabled' => $this->Enabled,
            'Length' => $this->Length,
            'Repeatable' => $this->Repeatable,
            'Mandatory' => $this->Mandatory,
            'IsCustomable' => $this->IsCustomable,
            //  'IsDelete' => $this->IsDelete,
            //'formats.Name' => $this->Format_id,
            //'fieldgroups.Name' => $this->Group_id,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
            'ISSUBSERIAL' => $this->ISSUBSERIAL,
        ]);

        $query->andFilterWhere(['like', 'Tag', $this->Tag])
            ->andFilterWhere(['like', 'fields.Name', $this->Name])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])
            ->andFilterWhere(['like', 'DEFAULTSUBTAG', $this->DEFAULTSUBTAG])
            ->andFilterWhere(['like', 'fieldgroups.Name', $this->Group_id])
            ->andFilterWhere(['like', 'formats.Name', $this->Format_id]);

        return $dataProvider;
    }
}
