<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Collectionloanitemsmandiri;
use leandrogehlen\querybuilder\Translator;
/**
 * CollectionloanitemSearch represents the model behind the search form about `common\models\Collectionloanitems`.
 */
class CollectionloanitemmandiriSearch extends Collectionloanitemsmandiri
{
    public function rules()
    {
        return [
            [['ID', 'CollectionLoan_id', 'LoanDate', 'DueDate', 'ActualReturn', 'LoanStatus', 'Collection_id', 'member_id', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal'], 'safe'],
            [['LateDays', 'CreateBy', 'UpdateBy'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function advancedSearch($loanStatus,$rules){
        $query = Collectionloanitems::find();
        $query->andWhere(['LoanStatus'=>$loanStatus]);

        if(strtolower($loanStatus) == 'loan')
        {
            $query->orderBy([
                'LoanDate'=>SORT_DESC,
                ]);
        }
        else
        {
            $query->orderBy([
                'ActualReturn'=>SORT_DESC,
                ]);
        }


        // if ($rules) {
        //     $translator = new Translator($rules);
        //     $query
        //     ->andWhere($translator->where())
        //     ->addParams($translator->params());
        // }

        if ($rules) {
            //bypass when type date
            $rules=\common\components\Helpers::changeRulesDateAdvanceSearch($rules);
            $translator = new Translator($rules);
            $query
            ->andWhere($translator->where())
            ->addParams($translator->params());
        }



        
        $query->joinWith('member');
        $query->joinWith('collection');
        $query->joinWith('collection.catalog');
        $query->joinWith('createBy');
        $query->joinWith('collectionLoan');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'LoanDate' => $this->LoanDate,
            'DueDate' => $this->DueDate,
            'ActualReturn' => $this->ActualReturn,
            'LateDays' => $this->LateDays,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'ID', $this->ID])
            ->andFilterWhere(['like', 'CollectionLoan_id', $this->CollectionLoan_id])
            ->andFilterWhere(['like', 'LoanStatus', $this->LoanStatus])
            ->andFilterWhere(['like', 'Collection_id', $this->Collection_id])
            ->andFilterWhere(['like', 'member_id', $this->member_id])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);


        return $dataProvider;
    }
    public function advancedSearchMember($loanStatus,$rules,$memberno){
        $query = Collectionloanitems::find();
        $query->andWhere(['LoanStatus'=>$loanStatus]);
        $query->andWhere(['members.MemberNo'=>$memberno]);
        if(strtolower($loanStatus) == 'loan')
        {
            $query->orderBy([
                'LoanDate'=>SORT_DESC,
                ]);
        }
        else
        {
            $query->orderBy([
                'ActualReturn'=>SORT_DESC,
                ]);
        }


        // if ($rules) {
        //     $translator = new Translator($rules);
        //     $query
        //     ->andWhere($translator->where())
        //     ->addParams($translator->params());
        // }

        if ($rules) {
            //bypass when type date
            $rules=\common\components\Helpers::changeRulesDateAdvanceSearch($rules);
            $translator = new Translator($rules);
            $query
            ->andWhere($translator->where())
            ->addParams($translator->params());
        }



        
        $query->joinWith('member');
        $query->joinWith('collection');
        $query->joinWith('collection.catalog');
        $query->joinWith('createBy');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'LoanDate' => $this->LoanDate,
            'DueDate' => $this->DueDate,
            'ActualReturn' => $this->ActualReturn,
            'LateDays' => $this->LateDays,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'ID', $this->ID])
            ->andFilterWhere(['like', 'CollectionLoan_id', $this->CollectionLoan_id])
            ->andFilterWhere(['like', 'LoanStatus', $this->LoanStatus])
            ->andFilterWhere(['like', 'Collection_id', $this->Collection_id])
            ->andFilterWhere(['like', 'member_id', $this->member_id])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);


        return $dataProvider;
    }

    public function search($params)
    {
        $query = Collectionloanitems::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'LoanDate' => $this->LoanDate,
            'DueDate' => $this->DueDate,
            'ActualReturn' => $this->ActualReturn,
            'LateDays' => $this->LateDays,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'ID', $this->ID])
            ->andFilterWhere(['like', 'CollectionLoan_id', $this->CollectionLoan_id])
            ->andFilterWhere(['like', 'LoanStatus', $this->LoanStatus])
            ->andFilterWhere(['like', 'Collection_id', $this->Collection_id])
            ->andFilterWhere(['like', 'member_id', $this->member_id])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }


    public function searchForMember($params)
    {
        $query = Collectionloanitems::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

       /* if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }*/

        $query->andFilterWhere([
            'LoanDate' => $this->LoanDate,
            'DueDate' => $this->DueDate,
            'ActualReturn' => $this->ActualReturn,
            'LateDays' => $this->LateDays,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'ID', $this->ID])
            ->andFilterWhere(['like', 'CollectionLoan_id', $this->CollectionLoan_id])
            ->andFilterWhere(['like', 'LoanStatus', $this->LoanStatus])
            ->andFilterWhere(['like', 'Collection_id', $this->Collection_id])
            ->andFilterWhere(['=', 'member_id', $this->member_id])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);

        return $dataProvider;
    }
	
	///////////////////////////////////////////////////////////////////////
	public function advancedSearchByLocation($loanStatus,$rules,$locations){
        $query = Collectionloanitems::find();
        $query->andWhere(['LoanStatus'=>$loanStatus]);
		
		///////////////
        $query->andWhere(['collectionloans.LocationLibrary_id'=>$locations]);
		///////////////
		
        if(strtolower($loanStatus) == 'loan')
        {
            $query->orderBy([
                'LoanDate'=>SORT_DESC,
                ]);
        }
        else
        {
            $query->orderBy([
                'ActualReturn'=>SORT_DESC,
                ]);
        }


        // if ($rules) {
        //     $translator = new Translator($rules);
        //     $query
        //     ->andWhere($translator->where())
        //     ->addParams($translator->params());
        // }

        if ($rules) {
            //bypass when type date
            $rules=\common\components\Helpers::changeRulesDateAdvanceSearch($rules);
            $translator = new Translator($rules);
            $query
            ->andWhere($translator->where())
            ->addParams($translator->params());
        }



        
        $query->joinWith('member');
        $query->joinWith('collection');
        $query->joinWith('collection.catalog');
        $query->joinWith('createBy');
        $query->joinWith('collectionLoan');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'LoanDate' => $this->LoanDate,
            'DueDate' => $this->DueDate,
            'ActualReturn' => $this->ActualReturn,
            'LateDays' => $this->LateDays,
            'CreateBy' => $this->CreateBy,
            'CreateDate' => $this->CreateDate,
            'UpdateBy' => $this->UpdateBy,
            'UpdateDate' => $this->UpdateDate,
        ]);

        $query->andFilterWhere(['like', 'ID', $this->ID])
            ->andFilterWhere(['like', 'CollectionLoan_id', $this->CollectionLoan_id])
            ->andFilterWhere(['like', 'LoanStatus', $this->LoanStatus])
            ->andFilterWhere(['like', 'Collection_id', $this->Collection_id])
            ->andFilterWhere(['like', 'member_id', $this->member_id])
            ->andFilterWhere(['like', 'CreateTerminal', $this->CreateTerminal])
            ->andFilterWhere(['like', 'UpdateTerminal', $this->UpdateTerminal]);


        return $dataProvider;
    }
	///////////////////////////////////////////////////////////////////////
}
