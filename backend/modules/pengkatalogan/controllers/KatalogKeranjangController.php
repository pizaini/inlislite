<?php

namespace backend\modules\pengkatalogan\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\CatalogHelpers;
use common\models\CatalogSearch;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\web\Session;
use yii\validators\Validator;

/**
 * KatalogController implements the CRUD actions for Collections model.
 */
class KatalogKeranjangController extends Controller
{
    public function behaviors()
    {
        return [
        'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
        'delete' => ['post'],
        ],
        ],
        ];
    }

    /**
     * Lists all Collections models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CatalogSearch;
        $dataProvider = $searchModel->search(1,Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            ]);
    }

    
}

?>