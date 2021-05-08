<?php

namespace backend\modules\survey\controllers;

use Yii;
use common\models\SurveyPilihan;
use common\models\SurveyPilihanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\data\ActiveDataProvider;


/**
 * SurveyPilihanController implements the CRUD actions for SurveyPilihan model.
 */
class SurveyPilihanController extends Controller
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
     * Lists all SurveyPilihan models.
     * @return mixed
     */
    public function actionIndex($id,$sid)
    {
        $_SESSION['sur_identity'] = ['id'=>$id,'sid'=>$sid];
        $searchModel = new SurveyPilihanSearch;

        $queryParams= Yii::$app->request->getQueryParams();
        $queryParams['SurveyPilihanSearch']['Survey_Pertanyaan_id'] = $id;
        $dataProvider = $searchModel->search($queryParams);


        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'id' => $id,
            'sid' => $sid,
        ]);
    }

    /**
     * Displays a single SurveyPilihan model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

        return $this->redirect(['view', 'id' => $model->ID]);
        } else {
        return $this->render('view', ['model' => $model]);
}
    }

    /**
     * Creates a new SurveyPilihan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $sur_identity = $_SESSION['sur_identity'];
        $model = new SurveyPilihan;

        if ($model->load(Yii::$app->request->post())) {
            $model->Survey_Pertanyaan_id = $id;
            if ($model->save()) {
			           Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
                // return $this->redirect(['view', 'id' => $model->ID]);
                return $this->redirect(['index', 'id' => $sur_identity['id'],'sid'=> $sur_identity['sid'] ]);
            } else {
                Yii::$app->getSession()->setFlash('failed', [
                    'type' => 'warning',
                    'duration' => 5000,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Failed Save'),
                    'title' => 'warning',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
                // return $this->redirect(['view', 'id' => $model->ID]);
                return $this->redirect(['create', 'id' => $id]);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SurveyPilihan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $sur_identity = $_SESSION['sur_identity'];
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			 Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Edit'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            return $this->redirect(['index', 'id' => $sur_identity['id'],'sid'=> $sur_identity['sid'] ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SurveyPilihan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id,$pid)
    {
        $sur_identity = $_SESSION['sur_identity'];
        $this->findModel($id)->delete();
		Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Delete'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
        return $this->redirect(['index', 'id' => $sur_identity['id'],'sid'=> $sur_identity['sid'] ]);
    }

    /**
     * Finds the SurveyPilihan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SurveyPilihan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SurveyPilihan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
