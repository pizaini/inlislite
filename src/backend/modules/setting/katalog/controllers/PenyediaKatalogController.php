<?php

namespace backend\modules\setting\katalog\controllers;

use Yii;
use common\models\Library;
use common\models\Librarysearchcriteria;
use common\models\LibrarySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;

use yii\data\ActiveDataProvider;
use yii\db\Query;


/**
 * PenyediaKatalogController implements the CRUD actions for Library model.
 */
class PenyediaKatalogController extends Controller
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
     * Lists all Library models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LibrarySearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Library model.
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
     * Creates a new Library model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Library;
        $model2 = [new Librarysearchcriteria()];

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            if(Librarysearchcriteria::loadMultiple($model2, Yii::$app->request->post()))
            {
               $arr = Yii::$app->request->post('Librarysearchcriteria', []);
                // $model2->LIBRARYID;
                //$arr = $model2;
                 //   var_dump($arr);
                // die;
            
               foreach ($arr as $arra) 
               {
                   $model2new = new Librarysearchcriteria;
                   $model2new->LIBRARYID=$model->ID;
                    //$model2new->CRITERIANAME=$value;
                   $model2new->CRITERIANAME=$arra['CRITERIANAME'];
                    //echo $model2new->CRITERIANAME;
                   $model2new->save();
                }
            }
            //echo $model2->CRITERIANAME;

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
            return $this->render('create', [
                'model' => $model,
                'model2' => $model2,
            ]);
        }
    }

    /**
     * Updates an existing Library model.
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
            $model2 = [new Librarysearchcriteria()];
        }
        $iddatabase = $model->ID;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(Librarysearchcriteria::loadMultiple($model2, Yii::$app->request->post())){

                //$model2->LIBRARYID = $model->ID;
                $arr = Yii::$app->request->post('Librarysearchcriteria', []);
                $sqldelcol = "DELETE FROM `librarysearchcriteria` WHERE `LIBRARYID`=" . $iddatabase . ";";
                Yii::$app->db->createCommand($sqldelcol)->query();
                foreach ($arr as $arra) {
                    $model2new = new Librarysearchcriteria;
                    $model2new->LIBRARYID = $model->ID;

                    $model2new->CRITERIANAME=$arra['CRITERIANAME'];

                    $model2new->save();
                }
            }
            
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
                'model2' => $model2,
                            //    'model3' => $model3,
            ]);
        }
    }

    /**
     * Deletes an existing Library model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Librarysearchcriteria::deleteAll(['LIBRARYID' => $id]);
        // $col = "DELETE FROM `librarysearchcriteria` WHERE `LIBRARYID` = " . $id . "; ";
        // Yii::$app->db->createCommand($col)->query();
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
     * Finds the Library model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Library the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Library::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
            protected function findModel2($id) {
        if (($model2 = Librarysearchcriteria::findAll(['LIBRARYID' => $id]  )) !== null) {
            return $model2;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
}
