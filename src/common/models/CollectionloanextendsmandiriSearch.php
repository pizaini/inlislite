<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Collectionloanextendsmandiri;
/////////////////
// use common\models\Collectionloanitems;
use common\models\Collectionloanitems;
/////////////////
use leandrogehlen\querybuilder\Translator;


/**
 * CollectionloanextendsSearch represents the model behind the search form about `common\models\Collectionloanextends`.
 */
class CollectionloanextendsmandiriSearch extends Collectionloanextendsmandiri
{
    public function rules()
    {
        return [
            [['id', 'CollectionLoan_id', 'CollectionLoanItem_id', 'Collection_id', 'Member_id', 'DateExtend', 'DueDateExtend','CreateDate', 'UpdateDate','CreateTerminal', 'UpdateTerminal'], 'safe'],
            [['LateDays', 'CreateBy', 'UpdateBy'], 'integer'],
        ];
    }




    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function advancedSearch($loanStatus,$rules){
        $query = Collectionloanextends::find();
        
        $query->leftJoin('collectionloanitems','collectionloanextends.CollectionLoanItem_id = collectionloanitems.ID');

        // $query->andWhere(['LoanStatus'=>$loanStatus]);

        if(strtolower($loanStatus) == 'loan')
        {
            $query->orderBy([
                'id'=>SORT_DESC,
                ]);
        }
        else
        {
            $query->orderBy([
                'DateExtend'=>SORT_DESC,
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
        // echo $query->createCommand()->sql;die;

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

	///////////////////////////////////////////////////////////////////
	
    public function advancedSearchByLocation($loanStatus,$rules,$locations){
        $query = Collectionloanextends::find();
        
        $query->leftJoin('collectionloanitems','collectionloanextends.CollectionLoanItem_id = collectionloanitems.ID');

        // $query->andWhere(['LoanStatus'=>$loanStatus]);
        $query->andWhere(['collectionloans.LocationLibrary_id'=>$locations]);

        if(strtolower($loanStatus) == 'loan')
        {
            $query->orderBy([
                'id'=>SORT_DESC,
                ]);
        }
        else
        {
            $query->orderBy([
                'DateExtend'=>SORT_DESC,
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
        // echo $query->createCommand()->sql;die;

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

	///////////////////////////////////////////////////////////////////
	
	
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

    public function advancedSearchByMember($params){
        $query = Collectionloanextends::find()->where(['collectionloanextends.Member_id' => $params]);
        
        $query->leftJoin('collectionloanitems','collectionloanextends.CollectionLoanItem_id = collectionloanitems.ID');

        $query->joinWith('member');
        $query->joinWith('collection');
        $query->joinWith('collection.catalog');
        $query->joinWith('createBy');
        $query->joinWith('collectionLoan');
        // echo $query->createCommand()->sql;die;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
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
}
