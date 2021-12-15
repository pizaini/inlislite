<?php

namespace backend\modules\setting\digitalcollection\controllers;
use Yii;
use common\models\Opaclogs;
use common\models\OpaclogsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class HistoryOpacController extends Controller
{
    public function actionIndex()
    {

    	$searchModel = new OpaclogsSearch;
        $dataProvider = $searchModel->searchLKD(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

    	
        return $this->render('index');
    }

}
