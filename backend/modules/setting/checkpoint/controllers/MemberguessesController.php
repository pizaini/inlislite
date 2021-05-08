<?php

namespace backend\modules\setting\checkpoint\controllers;

use Yii;
use common\models\Memberguesses;
use common\models\MemberguessesSearch;

use common\models\Groupguesses;
use common\models\GroupguessesSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\DynamicModel;

/**
 * MemberguessesController implements the CRUD actions for Memberguesses model.
 */
class MemberguessesController extends Controller
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
     * Lists all Memberguesses models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MemberguessesSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * [actionAnggota description]
     * @return [type] [description]
     */
    public function actionAnggota()
    {
        $searchModel = new MemberguessesSearch;

        $queryParams= Yii::$app->request->getQueryParams();
        $queryParams['MemberguessesSearch']['statusAnggota'] = 'not null';
        $queryParams['MemberguessesSearch']['libraryID'] = $_SESSION['location'];
        $dataProvider = $searchModel->search($queryParams);

        // $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => Yii::t('app','Anggota'),
            'listFor' => 'anggota',
        ]);
    }

    /**
     * [actionNonnggota description]
     * @return [type] [description]
     */
    public function actionNonanggota()
    {
        $searchModel = new MemberguessesSearch;

        $queryParams= Yii::$app->request->getQueryParams();
        $queryParams['MemberguessesSearch']['statusAnggota'] = 'null';
        $queryParams['MemberguessesSearch']['libraryID'] = $_SESSION['location'];
        $dataProvider = $searchModel->search($queryParams);

        // $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => Yii::t('app','Non Anggota'),
        ]);
    }

    /**
     * [actionRombongan description]
     * @return [type] [description]
     */
    public function actionRombongan()
    {
        $searchModel = new GroupguessesSearch;

        $queryParams= Yii::$app->request->getQueryParams();
        $queryParams['GroupguessesSearch']['libraryID'] = $_SESSION['location'];
        $dataProvider = $searchModel->search($queryParams);

        // $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('groupguesses', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => Yii::t('app','Rombongan'),
        ]);
    }

    /**
     * [actionRombongan description]
     * @return [type] [description]
     */
    public function actionSettingBukuTamu(){
        $model = new DynamicModel([
            'CountingBukuTamu',
            ]);
        $model->addRule([
            'CountingBukuTamu',], 'required');

        $model->CountingBukuTamu = Yii::$app->config->get('CountingBukuTamu');




        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {

                Yii::$app->config->set('CountingBukuTamu', Yii::$app->request->post('DynamicModel')['CountingBukuTamu']);


                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app', 'Success Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
            } else {

                Yii::$app->getSession()->setFlash('failed', [
                    'type' => 'error',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app', 'Failed Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
            }
            return $this->redirect(['setting-buku-tamu']);
        } else {
            return $this->render('setting-buku-tamu', [
                'model' => $model,]);
        }
    }

    /**
     * Displays a single Memberguesses model.
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
     * Creates a new Memberguesses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
   //  public function actionCreate()
   //  {
   //      $model = new Memberguesses;

   //      if ($model->load(Yii::$app->request->post()) && $model->save()) {
			// Yii::$app->getSession()->setFlash('success', [
   //                  'type' => 'info',
   //                  'duration' => 500,
   //                  'icon' => 'fa fa-info-circle',
   //                  'message' => Yii::t('app','Success Save'),
   //                  'title' => 'Info',
   //                  'positonY' => Yii::$app->params['flashMessagePositionY'],
   //                  'positonX' => Yii::$app->params['flashMessagePositionX']
   //              ]);
   //          return $this->redirect(['view', 'id' => $model->ID]);
   //      } else {
   //          return $this->render('create', [
   //              'model' => $model,
   //          ]);
   //      }
   //  }

    /**
     * Updates an existing Memberguesses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
			 // Yii::$app->getSession()->setFlash('success', [
    //                 'type' => 'info',
    //                 'duration' => 500,
    //                 'icon' => 'fa fa-info-circle',
    //                 'message' => Yii::t('app','Success Edit'),
    //                 'title' => 'Info',
    //                 'positonY' => Yii::$app->params['flashMessagePositionY'],
    //                 'positonX' => Yii::$app->params['flashMessagePositionX']
    //             ]);
    //         return $this->redirect(['view', 'id' => $model->ID]);
    //     } else {
    //         return $this->render('update', [
    //             'model' => $model,
    //         ]);
    //     }
    // }

    /**
     * Deletes an existing Memberguesses model.
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
        return $this->redirect(Yii::$app->request->referrer);
    }
    /**
     * Deletes an existing Groupguesses model.
     * If deletion is successful, the browser will be redirected to the refferer page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteGroup($id)
    {
        $this->findModelGroup($id)->delete();
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
     * Finds the Memberguesses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Memberguesses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Memberguesses::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * [findModelGroup description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    protected function findModelGroup($id)
    {
        if (($model = Groupguesses::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
