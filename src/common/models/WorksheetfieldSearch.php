<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Worksheetfields;

/**
 * CollectionmediaSearch represents the model behind the search form about `common\models\Collectionmedias`.
 */
class WorksheetfieldSearch extends Worksheetfields
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
        $query = Worksheetfields::find();
        $query->joinWith(['field'],' fields.ID = worksheetfields.Field_id');
         
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

        $query->andFilterWhere(['=', 'Worksheet_id', $this->Worksheet_id])
        ->andFilterWhere(['like', 'fields.Tag', $this->Tag])
        ->andFilterWhere(['like', 'fields.Name', $this->Field_id]);

        return $dataProvider;
    }

    public function loadTagEntry($params)
    {
        $a='$a';
        $query = Worksheetfields::find()
                 ->addSelect(["worksheetfields.id AS ID", 
                    "Worksheet_id",
                    "Field_id AS TagID", 
                    "Tag AS TagCode", 
                    "fields.Name AS TagName",
                    "FIXED", 
                    "Enabled", 
                    "LENGTH", 
                    "REPEATABLE", 
                    "Mandatory", 
                    "IsCustomable",
                    //"'' AS OriginalValue",
                    //"'#' AS Indicator1", 
                    //"'#' AS Indicator2", 
                    "(CASE WHEN Tag < '010' THEN '' ELSE '$a ' END) AS Isi"]);
        $query->joinWith(['field'],' fields.ID = worksheetfields.Field_id');
         
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

        $query->andFilterWhere(['=', 'Worksheet_id', $this->Worksheet_id])
        ->andFilterWhere(['=', 'fields.Format_Id', $this->Format_id])
        ->andFilterWhere(['=', 'isakuisisi', $this->IsAkuisisi])
        ->andFilterWhere(['like', 'fields.Tag', $this->Tag])
        ->andFilterWhere(['like', 'fields.Name', $this->Field_id]);

        return $dataProvider;
    }
}
