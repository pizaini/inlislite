<?php

namespace backend\modules\akuisisi\controllers;

use Yii;
use common\models\Collections;
use common\models\CollectionSearchKardeks;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;


/**
 * KoleksiKarantinaController implements the CRUD actions for QuarantinedCollections model.
 */
class KardeksTerbitanBerkalaController extends Controller
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
     * Lists all QuarantinedCollections models.
     * @return mixed
     */
    public function actionIndex()
    {
        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new CollectionSearchKardeks;
        $dataProvider = $searchModel->advancedSearch($rules);

        /*$searchModel = new CollectionSearchKardeks;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());*/

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'rules'=> $rules
        ]);
    }


    /**
     * Finds the QuarantinedCollections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param double $id
     * @return QuarantinedCollections the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Collections::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
