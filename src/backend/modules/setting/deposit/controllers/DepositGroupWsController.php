<?php

namespace backend\modules\setting\deposit\controllers;

use Yii;
use common\models\DepositGroupWs;
use common\models\DepositGroupWsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DepositGroupWsController implements the CRUD actions for DepositGroupWs model.
 */
class DepositGroupWsController extends Controller
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
     * Lists all DepositGroupWs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DepositGroupWsSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single DepositGroupWs model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			 
        return $this->redirect(['view', 'id' => $model->id_group]);
        } else {
        return $this->render('view', ['model' => $model]);
}
    }

    /**
     * Creates a new DepositGroupWs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DepositGroupWs;

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
            return $this->redirect(['index']);
            // return $this->redirect(['view', 'id' => $model->id_group]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionBindWs()
    {
        $model = new DepositGroupWs;

        return $this->renderAjax('_form-modal', [
                'model' => $model,
            ]);
    }

    public function actionSaveWs()
    {
        if (Yii::$app->request->isAjax) 
        {
            $post = Yii::$app->request->post();
            $group_name= $post['group_name'];

        $model = new DepositGroupWs;
        // echo '<pre>'; print_r($model->id_group);die;

            $model->group_name = $group_name;
            if($model->save())
            {
                return $model->id_group;
            }else{
                return 'modal tidak save';
            }

            
        }

    }

    /**
     * Updates an existing DepositGroupWs model.
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
            return $this->redirect(['view', 'id' => $model->id_group]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DepositGroupWs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
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
        return $this->redirect(['index']);
    }

    /**
     * Finds the DepositGroupWs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DepositGroupWs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DepositGroupWs::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
