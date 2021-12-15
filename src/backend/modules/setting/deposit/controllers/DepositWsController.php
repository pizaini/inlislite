<?php

namespace backend\modules\setting\deposit\controllers;

use Yii;
use common\models\DepositWs;
use common\models\DepositWsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DepositWsController implements the CRUD actions for DepositWs model.
 */
class DepositWsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all DepositWs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DepositWsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DepositWs model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DepositWs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($dep=null)
    {
        $model = new DepositWs();

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        if ($model->load(Yii::$app->request->post())) {
			// Perubahan arief
            if (empty($dep)) {
				if(Yii::$app->request->post()['dep'] == 1){
					if($model->save()){
						echo json_encode(true);
					}else{
						echo json_encode(false);
					}
				}else{
					$model->save();
					return $this->redirect(['index', 'id' => $model->ID]);
				}
				
            }else{
				$model->save();
                return $this->redirect(array('/pengkatalogan/katalog/create-deposit', 'for' => 'coll', 'rda' => '0', 'dep' => '1'));
			}
			
        } else {
            return $this->renderAjax('_form', [
				'dep' => $dep,
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DepositWs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    public function actionUpdate($id)
    {
        $modelUpdate = $this->findModel($id);

        if ($modelUpdate->load(Yii::$app->request->post()) && $modelUpdate->save()) {
             Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Edit'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            return $this->redirect(['index', 'id' => $modelUpdate->ID]);
        } else {
            return $this->renderAjax('_form', [
                'model' => $modelUpdate,
            ]);
        }
    }

    /**
     * Deletes an existing DepositWs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DepositWs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DepositWs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DepositWs::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
