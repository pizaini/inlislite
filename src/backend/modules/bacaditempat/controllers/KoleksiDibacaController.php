<?php

namespace backend\modules\bacaditempat\controllers;

use Yii;
use common\models\Bacaditempat;
use common\models\BacaditempatSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KoleksiDibacaController implements the CRUD actions for Bacaditempat model.
 */
class KoleksiDibacaController extends Controller
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
     * Lists all Bacaditempat models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BacaditempatSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Lists all Bacaditempat models.
     * @return mixed
     */
    public function actionAnggota()
    {
        $searchModel = new BacaditempatSearch;

        $queryParams= Yii::$app->request->getQueryParams();
        $queryParams['BacaditempatSearch']['statusAnggota'] = 'not null';
        $dataProvider = $searchModel->search($queryParams);
        // $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => Yii::t('app','Anggota'),
            'status' => 'anggota',
        ]);
    }

    /**
     * Lists all Bacaditempat models.
     * @return mixed
     */
    public function actionNonanggota()
    {
        $searchModel = new BacaditempatSearch;

        $queryParams= Yii::$app->request->getQueryParams();
        $queryParams['BacaditempatSearch']['statusAnggota'] = 'null';
        $dataProvider = $searchModel->search($queryParams);
        // $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());


        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => Yii::t('app','Non Anggota'),
            'status' => 'nonanggota',
        ]);
    }

    /**
     * Displays a single Bacaditempat model.
     * @param double $id
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
     * Creates a new Bacaditempat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bacaditempat;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Bacaditempat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param double $id
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
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Bacaditempat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param double $id
     * @return mixed
     */
    public function actionDelete($id)
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
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Bacaditempat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param double $id
     * @return Bacaditempat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bacaditempat::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
