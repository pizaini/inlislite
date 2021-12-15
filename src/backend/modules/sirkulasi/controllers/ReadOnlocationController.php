<?php

namespace backend\modules\sirkulasi\controllers;

use Yii;
use yii\web\Session;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ReadOnlocationController extends Controller
{
    public function actionIndex()
    {

        $searchModel = new \common\models\BacaditempatSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}
