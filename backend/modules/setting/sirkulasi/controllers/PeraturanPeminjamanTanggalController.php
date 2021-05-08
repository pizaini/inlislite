<?php

namespace backend\modules\setting\sirkulasi\controllers;

use Yii;
use common\models\PeraturanPeminjamanTanggal;
use common\models\PeraturanPeminjamanTanggalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\components\Helpers;


/**
 * PeraturanPeminjamanTanggalController implements the CRUD actions for PeraturanPeminjamanTanggal model.
 */
class PeraturanPeminjamanTanggalController extends Controller
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
     * Lists all PeraturanPeminjamanTanggal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PeraturanPeminjamanTanggalSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single PeraturanPeminjamanTanggal model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
        {
			 
            return $this->redirect(['view', 'id' => $model->ID]);
        } 
        else 
        {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new PeraturanPeminjamanTanggal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PeraturanPeminjamanTanggal;

        if ($model->load(Yii::$app->request->post()) ) {
                $model->TanggalAwal = Helpers::convertDate($model->TanggalAwal);
                $model->TanggalAkhir = Helpers::convertDate($model->TanggalAkhir);
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
                 return $this->redirect(['index']);
            } 
            else
            {
                $model->load(Yii::$app->request->post());
            }
            
        } 
        return $this->render('create', [
            'model' => $model,
        ]);
        
    }

    /**
     * Updates an existing PeraturanPeminjamanTanggal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->TanggalAwal = date('d-m-Y',strtotime($model->TanggalAwal));
        $model->TanggalAkhir = date('d-m-Y',strtotime($model->TanggalAkhir));

        if ($model->load(Yii::$app->request->post())) 
        {
            $model->TanggalAwal = Helpers::convertDate($model->TanggalAwal);
            $model->TanggalAkhir = Helpers::convertDate($model->TanggalAkhir);
            if ($model->save()) 
            {
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
            }
            else
            {
                 $model->TanggalAwal = date('d-m-Y',strtotime($model->TanggalAwal));
                 $model->TanggalAkhir = date('d-m-Y',strtotime($model->TanggalAkhir));
            } 
            
        } 
        return $this->render('update', [
            'model' => $model,
        ]);
       
    }

    /**
     * Deletes an existing PeraturanPeminjamanTanggal model.
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
     * Finds the PeraturanPeminjamanTanggal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PeraturanPeminjamanTanggal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PeraturanPeminjamanTanggal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
