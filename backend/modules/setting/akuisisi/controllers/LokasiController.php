<?php

namespace backend\modules\setting\akuisisi\controllers;

use Yii;
use common\models\Locations;
use common\models\LocationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * LokasiController implements the CRUD actions for Locations model.
 */
class LokasiController extends Controller
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
     * Lists all Locations models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LocationSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Locations model.
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
     * Creates a new Locations model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Locations;

        if ($model->load(Yii::$app->request->post())) {
            $Logo = UploadedFile::getInstance($model, 'Logo');
            // $model->UrlLogo = "/inlislite3/backend/../uploaded_files/logo_ruangan/". $Logo->baseName . '.' . $Logo->extension;
            // $model->LocationLibrary_id = $_POST['LocationsIDini'];
            $model->save();
            $model->UrlLogo = Yii::$app->urlManager->createUrl("../uploaded_files/logo_ruangan/". $model->ID . '.' . $Logo->extension);
            //echo $model->UrlLogo;
            //die;
            if ($Logo) 
            {
                $Logo->saveAs('../uploaded_files/logo_ruangan/' . $model->ID . '.' . $Logo->extension);
            }
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

    /**
     * Updates an existing Locations model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $Logo = UploadedFile::getInstance($model, 'Logo');
            // $model->UrlLogo = "/inlislite3/backend/../uploaded_files/logo_ruangan/". $Logo->baseName . '.' . $Logo->extension;
            // echo getcwd().'/../..'.$model->UrlLogo;die;
            $gambarLama = $model->UrlLogo;
            //echo $model->UrlLogo;
            //die;
            $model->save();
            
            if (Yii::$app->request->post('pertanyaan') == 'IsVisitsDestination') 
            {
                $model->IsVisitsDestination = 1;
                $model->IsInformationSought = 0;
            } 
            elseif(Yii::$app->request->post('pertanyaan') == 'IsInformationSought')
            {
                $model->IsVisitsDestination = 0;
                $model->IsInformationSought = 1;
            }
            else
            {
                $model->IsVisitsDestination = 0;
                $model->IsInformationSought = 0;
            }

            
            if ($Logo) 
            {
                if (file_exists(getcwd().'/../..'.$gambarLama)) {
                    unlink(getcwd().'/../..'.$gambarLama);
                } 
                $Logo->saveAs('../uploaded_files/logo_ruangan/' . $id . '.' . $Logo->extension);
                $model->UrlLogo = Yii::$app->urlManager->createUrl("../uploaded_files/logo_ruangan/". $id . '.' . $Logo->extension);
            } 
            
            $model->save();

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
     * Deletes an existing Locations model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $jenis= Locations::findOne($id)->Name;
         try {
            $this->findModel($id)->delete();
            
        } 
        catch(\Exception $e){ 
            Yii::$app->getSession()->setFlash('failed', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Gagal Terhapus, lokasi '). $jenis .Yii::t('app',' terdapat koleksi'),
                        'title' => 'Warning',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
            return $this->redirect(['index']);
            // throw new \yii\web\HttpException(405, 'Error saving model'); 
        }

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
     * Finds the Locations model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Locations the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Locations::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
