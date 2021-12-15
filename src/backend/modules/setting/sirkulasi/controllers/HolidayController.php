<?php

namespace backend\modules\setting\sirkulasi\controllers;

use Yii;
use common\models\Holidays;
use common\models\HolidaySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\Helpers;

/**
 * HolidayController implements the CRUD actions for Holidays model.
 */
class HolidayController extends Controller
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
     * Lists all Holidays models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HolidaySearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Holidays model.
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
     * Creates a new Holidays model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Holidays;

        if ($model->load(Yii::$app->request->post())) {
            $model->Dates = Helpers::convertDate($model->Dates);
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
            
        } 

        return $this->render('create', [
            'model' => $model,
        ]);
    
    }

    public function actionCreateLiburPanjang()
    {
        $model = new Holidays;

        if ($model->load(Yii::$app->request->post())) {
            $start = Helpers::convertDate($model->Dates);
            $end = Helpers::convertDate($model->CreateDate);

            $holiday = new Holidays;
            
            $holiday->Dates = $start;
            $holiday->Names = $model->Names;
            if($holiday->save(false)){
                while ($start < $end) {
                    $start = date ("Y-m-d", strtotime("+1 day", strtotime($start)));
                    $cek1 = $start.'; ';
                    $cek2 = explode('; ', $cek1);
                    // echo '<pre>';print_r($cek2);
                    // print_r($cek[0]);
                    
                    
                    $cekTes = new Holidays;
                    if ($cekTes->load(Yii::$app->request->post())) {
                        $cekTes->Dates = Helpers::convertDate($start);
                        if ($cekTes->save()) {
                            Yii::$app->getSession()->setFlash('success', [
                                'type' => 'info',
                                'duration' => 500,
                                'icon' => 'fa fa-info-circle',
                                'message' => Yii::t('app','Success Save'),
                                'title' => 'Info',
                                'positonY' => Yii::$app->params['flashMessagePositionY'],
                                'positonX' => Yii::$app->params['flashMessagePositionX']
                            ]);
                        }
                    }
                    
                    
                }
            }
            // print_r($end);die;
            // $cek = '';
            
            // die;
            
            
            
            return $this->redirect(['index']);
            
            
        } 

        return $this->render('_liburPanjang', [
            'model' => $model,
        ]);
    
    }

    /**
     * Updates an existing Holidays model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        // $model->Dates = Helpers::convertDate($model->Dates);

        if ($model->load(Yii::$app->request->post()) ) {
            $model->Dates = Helpers::convertDate($model->Dates);
            if ($model->save()) {
    			 Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Success Edit'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
            } 
            
        return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Holidays model.
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
     * Finds the Holidays model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Holidays the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Holidays::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
