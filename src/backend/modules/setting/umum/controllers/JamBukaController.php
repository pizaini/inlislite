<?php

namespace backend\modules\setting\umum\controllers;

use Yii;
use common\models\MasterJamBuka;
use common\models\MasterJamBukaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * JamBukaController implements the CRUD actions for MasterJamBuka model.
 */
class JamBukaController extends Controller
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
     * Lists all MasterJamBuka models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MasterJamBukaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single MasterJamBuka model.
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
     * Creates a new MasterJamBuka model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     $model = new MasterJamBuka;
    //
    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
		// 	Yii::$app->getSession()->setFlash('success', [
    //                 'type' => 'info',
    //                 'duration' => 500,
    //                 'icon' => 'fa fa-info-circle',
    //                 'message' => Yii::t('app','Success Save'),
    //                 'title' => 'Info',
    //                 'positonY' => Yii::$app->params['flashMessagePositionY'],
    //                 'positonX' => Yii::$app->params['flashMessagePositionX']
    //             ]);
    //         return $this->redirect(['index']);
    //     } else {
    //         return $this->render('create', [
    //             'model' => $model,
    //         ]);
    //     }
    // }

    /**
     * Updates an existing MasterJamBuka model.
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
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MasterJamBuka model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();
		// Yii::$app->getSession()->setFlash('success', [
    //                 'type' => 'info',
    //                 'duration' => 500,
    //                 'icon' => 'fa fa-info-circle',
    //                 'message' => Yii::t('app','Success Delete'),
    //                 'title' => 'Info',
    //                 'positonY' => Yii::$app->params['flashMessagePositionY'],
    //                 'positonX' => Yii::$app->params['flashMessagePositionX']
    //             ]);
    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the MasterJamBuka model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MasterJamBuka the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MasterJamBuka::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
