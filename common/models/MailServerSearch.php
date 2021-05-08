<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Mailserver;

/**
 * JenisPerpustakaanSearch represents the model behind the search form about `common\models\JenisPerpustakaan`.
 */
class MailServerSearch extends Mailserver
{
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['Modul', 'Host', 'Port', 'CredentialMail', 'CredentialPassword', 'EnableSsl', 'MailFrom', 'MailDisplayName', 'IsActive', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Mailserver::find();

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

        $query->andFilterWhere(['like', 'Modul', $this->Modul])
            ->andFilterWhere(['like', 'Host', $this->Host])
            ->andFilterWhere(['like', 'Port', $this->Port])
                ->andFilterWhere(['like', 'CredentialMail', $this->CredentialMail])
                ->andFilterWhere(['like', 'CredentialPassword', $this->CredentialPassword])
                ->andFilterWhere(['like', 'EnableSsl', $this->EnableSsl])
                ->andFilterWhere(['like', 'MailFrom', $this->MailFrom])
                ->andFilterWhere(['like', 'MailDisplayName', $this->MailDisplayName])
                ->andFilterWhere(['like', 'IsActive', $this->IsActive])
            ->andFilterWhere(['like', 'CreateBy', $this->CreateBy])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateBy', $this->UpdateBy])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }


}
