<?php

namespace backend\modules\setting\sms\controllers;
use Yii;
use common\models\SmslogsSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\Settingparameters;
class HistorySmsController extends Controller
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
     * Lists all KriteriaKoleksi models.
     * @return mixed
     */
    public function actionIndex()
    {

    	$searchModel = new SmslogsSearch;

        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
       	
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
        
        
    }

        protected function findModel($id)
    {
        if (($model = KriteriaKoleksi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
