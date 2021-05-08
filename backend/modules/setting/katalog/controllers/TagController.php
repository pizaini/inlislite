<?php

namespace backend\modules\setting\katalog\controllers;

use Yii;
use common\models\Fields;
use common\models\Fieldindicator1s;
use common\models\Fieldindicator2s;
use common\models\Fielddatas;
use common\models\FieldSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;

/**
 * TagController implements the CRUD actions for Fields model.
 */
class TagController extends Controller
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
     * Lists all Fields models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FieldSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Fields model.
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
     * Creates a new Fields model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Fields;

        $newIndikator1 = [new Fieldindicator1s()];
        $newIndikator2 = [new Fieldindicator2s()];
        $newSubruas = [new Fielddatas()];
        if ($model->load(Yii::$app->request->post())) {
            $GroupID = ((int)$model->Tag < 10) ? 1 : 2;
            $model->Group_id = $GroupID;
                
            if($model->save()){
                $id=$model->ID;
                 if(Fieldindicator1s::loadMultiple($newIndikator1, Yii::$app->request->post())){
            $arr = Yii::$app->request->post('Fieldindicator1s', []);
            
            foreach ($arr as $arra) {
            $model2new = new Fieldindicator1s;
                $model2new->Field_id=$model->ID;
$model2new->Code=$arra['Code'];

$model2new->Name=$arra['Name'];
            $model2new->save();
       
                }
            }
            
            
             if(Fieldindicator2s::loadMultiple($newIndikator2, Yii::$app->request->post())){
            $arr = Yii::$app->request->post('Fieldindicator2s', []);
            
            foreach ($arr as $arra) {
            $model3new = new Fieldindicator2s;
                $model3new->Field_id=$model->ID;
$model3new->Code=$arra['Code'];

$model3new->Name=$arra['Name'];
            $model3new->save();
             
                }
            }

                        
 if(Fielddatas::loadMultiple($newSubruas, Yii::$app->request->post())){
           $arr = Yii::$app->request->post('Fielddatas', []);
            
            foreach ($arr as $arra) {
               $model4new = new Fielddatas;
                $model4new->Field_id=$model->ID;
$model4new->Code=$arra['Code'];

$model4new->Name=$arra['Name'];
$model4new->Delimiter=$arra['Delimiter'];
$model4new->SortNo=$arra['SortNo'];
$model4new->Repeatable=$arra['Repeatable'];
$model4new->IsShow=$arra['IsShow'];
            $model4new->save();
       
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
                'newIndikator1' => $newIndikator1,
                'newIndikator2' => $newIndikator2,
                'newSubruas' => $newSubruas,
               // 'indikator1' => $indikator1,
               // 'indikator2' => $indikator2,
             //   'subruas' => $subruas,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'newIndikator1' => $newIndikator1,
                'newIndikator2' => $newIndikator2,
                'newSubruas' => $newSubruas,
                //'indikator1' => $indikator1,
                //'indikator2' => $indikator2,
                //'subruas' => $subruas,
            ]);
        }
    }

    /**
     * Updates an existing Fields model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
              
        $model = $this->findModel($id);
        if($this->findModel2($id)!=NULL){
            $newIndikator1 = $this->findModel2($id);    
        }else{
            $newIndikator1 = [new Fieldindicator1s()];
        }
        if($this->findModel3($id)!=NULL){
            $newIndikator2 = $this->findModel3($id);    
        }else{
            $newIndikator2 = [new Fieldindicator2s()];    
        }
        if($this->findModel4($id)!=NULL){
            $newSubruas = $this->findModel4($id);    
        }else{
            $newSubruas = [new Fielddatas()];    
        }


        //var_dump($newSubruas);
       //die;
        if ($model->load(Yii::$app->request->post())) 
        {
            $GroupID = ((int)$model->Tag < 10) ? 1 : 2;
            $model->Group_id = $GroupID;
            
            if($model->save())
            {
                
                if(Fieldindicator1s::loadMultiple($newIndikator1, Yii::$app->request->post()))
                {
                    //$model2->LIBRARYID = $model->ID;
                    $arr = Yii::$app->request->post('Fieldindicator1s', []);
                    $sqldelcol = "DELETE FROM `fieldindicator1s` WHERE `Field_id`=" . $id . ";";
                    Yii::$app->db->createCommand($sqldelcol)->query();
                    foreach ($arr as $arra) {
                        $model2new = new Fieldindicator1s;
                        $model2new->Field_id=$model->ID;
                        $model2new->Code=$arra['Code'];

                        $model2new->Name=$arra['Name'];
                        $model2new->save();


                    }
                }

                if(Fieldindicator2s::loadMultiple($newIndikator2, Yii::$app->request->post()))
                {
                    //$model2->LIBRARYID = $model->ID;
                    $arr = Yii::$app->request->post('Fieldindicator2s', []);
                    $sqldelcol = "DELETE FROM `fieldindicator2s` WHERE `Field_id`=" . $id . ";";
                    Yii::$app->db->createCommand($sqldelcol)->query();
                    foreach ($arr as $arra) {
                        $model3new = new Fieldindicator2s;
                        $model3new->Field_id=$model->ID;
                        $model3new->Code=$arra['Code'];

                        $model3new->Name=$arra['Name'];
                        $model3new->save();


                    }
                }
                if(Fielddatas::loadMultiple($newSubruas, Yii::$app->request->post()))
                {
                    $arr = Yii::$app->request->post('Fielddatas', []);
                    $sqldelcol = "DELETE FROM `fielddatas` WHERE `Field_id`=" . $id . ";";
                    Yii::$app->db->createCommand($sqldelcol)->query();
                    
                    foreach ($arr as $arra) 
                    {
                        $model4new = new Fielddatas;
                        $model4new->Field_id=$model->ID;
                        $model4new->Code=$arra['Code'];

                        $model4new->Name=$arra['Name'];
                        $model4new->Delimiter=$arra['Delimiter'];
                        $model4new->SortNo=$arra['SortNo'];
                        $model4new->Repeatable=$arra['Repeatable'];
                        $model4new->IsShow=$arra['IsShow'];
                        $model4new->save();
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
            }
            else
            {

            return $this->render('update', [
                'model' => $model,
                'newIndikator1' => $newIndikator1,
                'newIndikator2' => $newIndikator2,
                'newSubruas' => $newSubruas,
//                'indikator1' => $indikator1,
//              'indikator2' => $indikator2,
//            'subruas' => $subruas,
                ]);
            }

        } 
        else 
        {
            return $this->render('update', [
                'model' => $model,
                'newIndikator1' => $newIndikator1,
                'newIndikator2' => $newIndikator2,
                'newSubruas' => $newSubruas,
      //          'indikator1' => $indikator1,
        //        'indikator2' => $indikator2,
          //      'subruas' => $subruas,
            ]);
        }
    }

    /**
     * Deletes an existing Fields model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        // $col = "DELETE FROM `fieldindicator1s` WHERE `Field_id` = " . $id . "; ";
        // Yii::$app->db->createCommand($col)->query();
        Fieldindicator1s::deleteAll(['Field_id' => $id]);

        // $col = "DELETE FROM `fieldindicator2s` WHERE `Field_id` = " . $id . "; ";
        // Yii::$app->db->createCommand($col)->query();
        Fieldindicator2s::deleteAll(['Field_id' => $id]);
        
        // $col = "DELETE FROM `fielddatas` WHERE `Field_id` = " . $id . "; ";
        // Yii::$app->db->createCommand($col)->query();
        Fielddatas::deleteAll(['Field_id' => $id]);
        
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
     * Finds the Fields model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Fields the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fields::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModel2($id) {
        if (($model2 = Fieldindicator1s::findAll(['Field_id' => $id]  )) !== null) {
            return $model2;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function findModel3($id) {
        if (($model3 = Fieldindicator2s::findAll(['Field_id' => $id]  )) !== null) {
            return $model3;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModel4($id) {
        if (($model4 = Fielddatas::findAll(['Field_id' => $id]  )) !== null) {
            return $model4;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
