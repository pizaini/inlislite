<?php

namespace backend\modules\survey\controllers;

use Yii;
use common\models\SurveyPertanyaan;
use common\models\SurveyPertanyaanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\data\ActiveDataProvider;


/**
 * SurveyPertanyaanController implements the CRUD actions for SurveyPertanyaan model.
 */
class SurveyPertanyaanController extends Controller
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
     * Lists all SurveyPertanyaan models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $_SESSION['sur_identity'] = ['id'=>$id];
        $searchModel = new SurveyPertanyaanSearch;

        $queryParams= Yii::$app->request->getQueryParams();
        $queryParams['SurveyPertanyaanSearch']['Survey_id'] = $id;
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'id'=> $id,
        ]);
    }

    /**
     * Displays a single SurveyPertanyaan model.
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
     * Creates a new SurveyPertanyaan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new SurveyPertanyaan;

        if ($model->load(Yii::$app->request->post())) {
            $model->Survey_id = $id;
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
                return $this->redirect(['index', 'id' => $_SESSION['sur_identity']['id']]);
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
                return $this->redirect(['create', 'id' => $id]);
            }
            
        } else {
            return $this->render('create', [
                'model' => $model,
               // 'id' => $id,
            ]);
        }
    }

    /**
     * Updates an existing SurveyPertanyaan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
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
            // return $this->redirect(['view', 'id' => $model->ID]);
            return $this->redirect(['index', 'id' => $_SESSION['sur_identity']['id']]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SurveyPertanyaan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id,$sid)
    {
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
        return $this->redirect(['index','id'=>$sid]);
    }

    /**
     * Finds the SurveyPertanyaan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SurveyPertanyaan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SurveyPertanyaan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
