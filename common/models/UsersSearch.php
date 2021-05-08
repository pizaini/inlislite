<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserSetting;

/**
 * JenisPerpustakaanSearch represents the model behind the search form about `common\models\JenisPerpustakaan`.
 */
class UsersSearch extends UserSetting
{
    public $roleName;
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['username', 'Fullname', 'EmailAddress', 'Role_id', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal','roleName'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = UserSetting::find()->leftJoin('roles', 'roles.ID = users.Role_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



		$dataProvider->setSort([
            'attributes' => [
                'ID',
                'username',
                'Fullname',
                'EmailAddress',
                'roleName' => [
                    'asc' => ['roles.Name' => SORT_ASC],
                    'desc' => ['roles.Name' => SORT_DESC],
                    'label' => Yii::t('app', 'Hak Akses'),
                    'default' => SORT_DESC
                ],
                'Alamat',
                'CreateDate',
            ]
        ]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'CreateDate' => $this->CreateDate,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'Fullname', $this->Fullname])
            ->andFilterWhere(['like', 'EmailAddress', $this->EmailAddress])
            ->andFilterWhere(['like', 'Role_id', $this->Role_id])

            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal])

            ->andFilterWhere(['like', 'roles.Name', $this->roleName]);

        return $dataProvider;
    }


}
