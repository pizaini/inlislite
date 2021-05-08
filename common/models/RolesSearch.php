<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Roles;

/**
 * JenisPerpustakaanSearch represents the model behind the search form about `common\models\JenisPerpustakaan`.
 */
class RolesSearch extends Roles
{
    public $aplikasiName;
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['Code', 'Name', 'IsActive', 'Application_id', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal','aplikasiName'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Roles::find();
        $query->leftJoin('applications', 'roles.Application_id = applications.ID');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'attributes' => [
                'Code', 'Name', 'IsActive', 'Application_id', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal',
                'aplikasiName' => [
                    'asc' => ['applications.Name' => SORT_ASC],
                    'desc' => ['applications.Name' => SORT_DESC],
                    'label' => 'Aplikasi',
                    'default' => SORT_ASC
                ],
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
            'IsActive' => $this->IsActive,
        ]);

        $query->andFilterWhere(['like', 'Code', $this->Code])
            ->andFilterWhere(['like', 'Name', $this->Name])
            // ->andFilterWhere(['like', 'IsActive', $this->IsActive])
                ->andFilterWhere(['like', 'Application_id', $this->Application_id])
                ->andFilterWhere(['like', 'applications.Name', $this->aplikasiName]);

        return $dataProvider;
    }


}
