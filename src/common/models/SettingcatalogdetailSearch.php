<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Settingcatalogdetail;

/**
 * CollectionmediaSearch represents the model behind the search form about `common\models\Collectionmedias`.
 */
class SettingcatalogdetailSearch extends Settingcatalogdetail
{
    /*
    public function rules()
    {
        return [
            [['ID', 'IsDelete'], 'integer'],
            [['JumlahKoleksi','Worksheet_id','Code', 'Name', 'CreateBy', 'CreateDate', 'CreateTerminal', 'UpdateBy', 'UpdateDate', 'UpdateTerminal'], 'safe'],
        ];
    }
    */

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Settingcatalogdetail::find();
        $query->joinWith(['field'],' fields.ID = Settingcatalogdetail.Field_id');
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
            'defaultOrder'=>[
                'field.Tag' => SORT_ASC,
                ]
            ]
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'Tag' => [
                    'asc' => ['fields.Tag' => SORT_ASC],
                    'desc' => ['fields.Tag' => SORT_DESC],
                    'label' => 'Tag',
                    'default' => SORT_ASC
                ],
                'Field_id' => [
                    'asc' => ['fields.Name' => SORT_ASC],
                    'desc' => ['fields.Name' => SORT_DESC],
                    'label' => 'Nama'
                ],
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'fields.Tag', $this->Tag])
        ->andFilterWhere(['like', 'fields.Name', $this->Field_id]);

        return $dataProvider;
    }
}
