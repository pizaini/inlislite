<?php

namespace backend\modules\pengkatalogan\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\CatalogHelpers;
use common\models\CatalogSearch;
use common\models\Cardformats;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\web\Session;
use yii\validators\Validator;

/**
 * KatalogController implements the CRUD actions for Collections model.
 */
class KatalogCetakKartuController extends Controller
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
        $dataProvider = $searchModel->searchKatalogCetakKartu(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            ]);
    }

    /**
     * Lists all Collections models.
     * @return mixed
     */
    public function actionProses($idcardformat,array $bibids)
    {
        if(count($bibids) > 0)
        {
            CatalogHelpers::cetakKartu($idcardformat,$bibids);
        }
        
    }

    /**
     * Lists all Collections models.
     * @return mixed
     */
    public function actionCreate()
    {
        /*$searchModel = new CatalogSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            ]);*/
        return $this->render('create');
    }


    /**
     * Finds the Collections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param double $id
     * @return Collections the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cardformats::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

?>