<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Survey;
use leandrogehlen\querybuilder\Translator;

/**
 * SurveySearch represents the model behind the search form about `common\models\Survey`.
 */
class SurveySearch extends Survey
{
    public function rules()
    {
        return [
            [['ID', 'NomorUrut', 'TargetSurvey', 'HasilSurveyShow', 'CreateBy', 'UpdateBy'], 'integer'],
            [['NamaSurvey', 'TanggalMulai', 'TanggalSelesai', 'RedaksiAwal', 'RedaksiAkhir', 'Keterangan', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal'], 'safe'],
            [['IsActive'], 'boolean'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    public function advancedSearch($keranjang,$rules)
    {
        $query = Survey::find();



        // if ($rules) {
        //       $translator = new Translator($rules);
        //       $query
        //         ->andWhere($translator->where())
        //         ->addParams($translator->params());
        //   }
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

        $query->andFilterWhere([
            'ID' => $this->ID,
            'TanggalMulai' => $this->TanggalMulai,
            'TanggalSelesai' => $this->TanggalSelesai,
            'IsActive' => $this->IsActive,
            'NomorUrut' => $this->NomorUrut,
            'TargetSurvey' => $this->TargetSurvey,
            'HasilSurveyShow' => $this->HasilSurveyShow,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
            ]);

        $query->andFilterWhere(['like', 'NamaSurvey', $this->NamaSurvey])
            ->andFilterWhere(['like', 'RedaksiAwal', $this->RedaksiAwal])
            ->andFilterWhere(['like', 'RedaksiAkhir', $this->RedaksiAkhir])
            ->andFilterWhere(['like', 'Keterangan', $this->Keterangan])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

            
        
        
        return $dataProvider;
    }

    public function search($params)
    {
        $query = Survey::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'TanggalMulai' => $this->TanggalMulai,
            'TanggalSelesai' => $this->TanggalSelesai,
            'IsActive' => $this->IsActive,
            'NomorUrut' => $this->NomorUrut,
            'TargetSurvey' => $this->TargetSurvey,
            'HasilSurveyShow' => $this->HasilSurveyShow,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'NamaSurvey', $this->NamaSurvey])
            ->andFilterWhere(['like', 'RedaksiAwal', $this->RedaksiAwal])
            ->andFilterWhere(['like', 'RedaksiAkhir', $this->RedaksiAkhir])
            ->andFilterWhere(['like', 'Keterangan', $this->Keterangan])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
}
