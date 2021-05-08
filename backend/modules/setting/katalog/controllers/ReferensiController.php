<?php

namespace backend\modules\setting\katalog\controllers;

use Yii;
use common\models\Refferences;
use common\models\Refferenceitems;
use common\models\RefferenceSearch;
use yii\web\Controller;
use yii\data\SqlDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\base\DynamicModel;

/**
 * ReferensiController implements the CRUD actions for Refferences model.
 */
class ReferensiController extends Controller
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
     * Lists all Refferences models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RefferenceSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Refferences model.
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
     * Creates a new Refferences model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Refferences;

        $model3 = new DynamicModel(['copyReff',]);
        $model3->addRule(['copyReff'], 'string');


        // $model2 = [new Refferenceitems()];
        if ($_GET['copy']) 
        {
            if($this->findModel2($_GET['copy'])!=NULL)
            {
                $model2 = $this->findModel2($_GET['copy']);
            }
            else
            {
                $model2 = [new Refferenceitems()];
            }
        } else {
            $model2 = [new Refferenceitems()];
        }
        



        if ($model->load(Yii::$app->request->post())) {
            $model->Format_id = 1;

            if($model->save())
            {
                if(Refferenceitems::loadMultiple($model2, Yii::$app->request->post())){
                    $arr = Yii::$app->request->post('Refferenceitems', []);
                    foreach ($arr as $arra) {
                        $model2new = new Refferenceitems;
                        $model2new->Refference_id=$model->ID;
                        $model2new->Code=$arra['Code'];

                        $model2new->Name=$arra['Name'];
                        $model2new->save();

                    }


                }
                
                
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
            }else{
                return $this->render('create', [
                    'model' => $model,
                    'model2' => $model2,
                    'model3' => $model3,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'model2' => $model2,
                'model3' => $model3,
            ]);
        }
    }

    /**
     * Updates an existing Refferences model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($this->findModel2($id)!=NULL){
        $model2 = $this->findModel2($id);    
        }else{
$model2 = [new Refferenceitems()];            
        }
        
         $iddatabase = $model->ID;
        if ($model->load(Yii::$app->request->post())) {
            $model->Format_id = 1;
            if($model->save())
            {
                 if(Refferenceitems::loadMultiple($model2, Yii::$app->request->post())){
           
              $arr = Yii::$app->request->post('Refferenceitems', []);
                    $sqldelcol = "DELETE FROM `refferenceitems` WHERE `Refference_id`=" . $iddatabase . ";";
                    Yii::$app->db->createCommand($sqldelcol)->query();
                    foreach ($arr as $loc) {
                        $model2new = new Refferenceitems;
                        $model2new->Refference_id=$model->ID;
                        $model2new->Code=$loc['Code'];
                        $model2new->Name=$loc['Name'];

                        $model2new->save();
                
                 }}
                
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
            }else{
                return $this->render('update', [
                    'model' => $model,
                    'model2' => $model2,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'model2' => $model2,
                    ]);
        }
    }

    /**
     * Deletes an existing Refferences model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        // $col = "DELETE FROM `refferenceitems` WHERE `Refference_id` = " . $id . "; ";
        // Yii::$app->db->createCommand($col)->query();
		Refferenceitems::deleteAll(['Refference_id' => $id]);

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
     * Finds the Refferences model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Refferences the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Refferences::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
      protected function findModel2($id) {
        if (($model3 = Refferenceitems::findAll(['Refference_id' => $id]  )) !== null) {
            return $model3;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    
}
