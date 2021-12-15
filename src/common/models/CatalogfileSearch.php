<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Catalogfiles;
use leandrogehlen\querybuilder\Translator;

/**
 * CollectionSearch represents the model behind the search form about `common\models\Collections`.
 */
class CatalogfileSearch extends Catalogfiles
{

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function advancedSearchByCatalogId($id,$rules)
    {
        $query = Catalogfiles::find();
        $query->where(['Catalog_Id'=>$id]);
        $query->orderBy(['catalogfiles.ID' => SORT_DESC]);

        if ($rules) {
              $translator = new Translator($rules);
              $query
                ->andWhere($translator->where())
                ->addParams($translator->params());
          }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'ID', 
                'FileURL', 
                'FileFlash',
                'createBy.Fullname' => [
                    'asc' => ['createBy.Fullname' => SORT_ASC],
                    'desc' => ['createBy.Fullname' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'CreateDate',
                'IsPublish',
                
            ]
        ]);
        
        
        return $dataProvider;
    }
}
