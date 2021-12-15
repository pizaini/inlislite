<?php

namespace backend\modules\setting\katalog\controllers;

use Yii;
use common\models\Worksheets;
use common\models\Worksheetfields;
use common\models\WorksheetSearch;
use \common\models\Worksheetfielditems;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\base\DynamicModel;


/**
 * LembarKerjaController implements the CRUD actions for Worksheets model.
 */
class LembarKerjaController extends Controller
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
     * Lists all Worksheets models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WorksheetSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Worksheets model.
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
     * Creates a new Worksheets model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        //echo ($_GET['copy'])?$_GET['copy']:'';
        $model = new Worksheets;
        //$model2 = [new Worksheetfields()];

        $model3 = new DynamicModel(['copyWorksheet',]);
        $model3->addRule(['copyWorksheet'], 'string');


        if ($_GET['copy']) 
        {
            if($this->findModel2($_GET['copy'])!=NULL)
            {
                $model2 = $this->findModel2($_GET['copy']);
            }
            else
            {
                $model2 = [new Worksheetfields()];
            }
        } else {
            $model2 = [new Worksheetfields()];
        }
        


        if ($model->load(Yii::$app->request->post())) {
            $model->Format_id = 1;
            if($model->save()){
                

                if(Worksheetfields::loadMultiple($model2, Yii::$app->request->post())){
                    $arr = Yii::$app->request->post('Worksheetfields', []);
                    foreach ($arr as $arra) {
                        $model2new = new Worksheetfields;
                        $model2new->Worksheet_id=$model->ID;
                        $model2new->Field_id=$arra['Field_id'];

                        $model2new->save();

                    }


                }
                
                //Management folder for digital content n cover
                
                //konten digital
                $pathKontenDigital = Yii::getAlias('@uploaded_files/dokumen_isi/'.$model->Name);

                //cover ori
                $pathCoverOriginal = Yii::getAlias('@uploaded_files/sampul_koleksi/original/'.$model->Name);

                //cover thumb
                $pathCoverThumbnail = Yii::getAlias('@uploaded_files/sampul_koleksi/thumbnail/'.$model->Name);


                if(!is_dir($pathKontenDigital))
                {
                    mkdir($pathKontenDigital , 0777);
                }

                if(!is_dir($pathCoverOriginal))
                {
                    mkdir($pathCoverOriginal , 0777);
                }

                if(!is_dir($pathCoverThumbnail))
                {
                    mkdir($pathCoverThumbnail , 0777);
                }

                //end Managament folder digital content  n cover
                
                
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
            } else {
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

    public function actionLoadSelecterKriteria($i)
    {
        return $this->renderAjax('select-kriteria',['i'=>$i]);
    }

    /**
     * Updates an existing Worksheets model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($this->findModel2($id)!=NULL)
        {
            $model2 = $this->findModel2($id);
        }
        else
        {
            $model2 = [new Worksheetfields()];
        }
        $nameOld = $model->Name;

        // echo "<pre>";
        // print_r(Yii::$app->request->post());
        // die;

        if ($model->load(Yii::$app->request->post())) 
        {
            $model->Format_id = 1;
            if($model->save())
            {
                if(Worksheetfields::loadMultiple($model2, Yii::$app->request->post()))
                {

                    $arr_worksheet = Yii::$app->request->post('Worksheetfields', []);
                    $result = implode(',',array_map(function($arr) {
                       return $arr['Field_id'];
                    }, $arr_worksheet));     

                    $sql = 'SELECT * FROM worksheetfields WHERE worksheetfields.Worksheet_id = "'.$id.'" AND worksheetfields.Field_id IN ('.$result.')';
                    $data = Yii::$app->db->createCommand($sql)->queryAll(); 

                    $result2 = array_map(function($arr2) {
                       return $arr2['Field_id'];
                    }, $data);

                    $resultx = array_map(function($arr) {
                       return $arr['Field_id'];
                    }, $arr_worksheet);

                    if($data == true ){
                        // $workshielfield = Worksheetfields::deleteAll('worksheetfields.Field_sssid NOT IN (:result2) AND worksheetfields.Worksheet_id = :Worksheet_idx )',[':Worksheet_idx' => $id, ':result2' => $inValue ]);
                        $workshielfield = Yii::$app->db->createCommand("DELETE FROM `worksheetfields` WHERE worksheetfields.Field_id NOT IN (".$result.") AND worksheetfields.Worksheet_id = '".$id."'")->execute();
                    }
                    // print_r($workshielfield);die;
                    // $resultx = array_map(function($arr) {  

                    // $e = array_chunk(array_map(null,array_diff($resultx,["\'5\'"])),1);
                    $ex = array_diff($resultx,$result2);
                    $e = array_chunk($ex,1);
                    // echo '<pre>';print_r($e);echo '<br />';echo '<pre>';print_r($arr_worksheet);echo '<br />';echo '<pre>';print_r($resultx);echo '<br />';echo '<pre>';print_r($result2);die;

                    // if($data != true ){
                        foreach ($e as $loc) {
                            $model2new = new Worksheetfields;
                            $model2new->Worksheet_id=$model->ID;
                            $model2new->Field_id=$loc['0'];
                            $model2new->save();
                        }
                    // }
                }
                
                //Management folder for digital content n cover
                
                //konten digital
                $pathKontenDigitalOld = Yii::getAlias('@uploaded_files/dokumen_isi/'.$nameOld);
                $pathKontenDigitalNew = Yii::getAlias('@uploaded_files/dokumen_isi/'.$model->Name);

                //cover ori
                $pathCoverOriginalOld = Yii::getAlias('@uploaded_files/sampul_koleksi/original/'.$nameOld);
                $pathCoverOriginalNew = Yii::getAlias('@uploaded_files/sampul_koleksi/original/'.$model->Name);

                //cover thumb
                $pathCoverThumbnailOld = Yii::getAlias('@uploaded_files/sampul_koleksi/thumbnail/'.$nameOld);
                $pathCoverThumbnailNew = Yii::getAlias('@uploaded_files/sampul_koleksi/thumbnail/'.$model->Name);


                if($nameOld != $model->Name)
                {

                    if(is_dir($pathKontenDigitalOld))
                    {
                        rename($pathKontenDigitalOld,$pathKontenDigitalNew);
                    }else{
                        mkdir($pathKontenDigitalNew , 0777);
                    }

                    if(is_dir($pathCoverOriginalOld))
                    {
                        rename($pathCoverOriginalOld,$pathCoverOriginalNew);
                    }else{
                        mkdir($pathCoverOriginalNew , 0777);
                    }

                    if(is_dir($pathCoverThumbnailOld))
                    {
                        rename($pathCoverThumbnailOld,$pathCoverThumbnailNew);
                    }else{
                        mkdir($pathCoverThumbnailNew , 0777);
                    }

                }else{
                    if(!is_dir($pathKontenDigitalNew))
                    {
                        mkdir($pathKontenDigitalNew , 0777);
                    }

                    if(!is_dir($pathCoverOriginalNew))
                    {
                        mkdir($pathCoverOriginalNew , 0777);
                    }

                    if(!is_dir($pathCoverThumbnailNew))
                    {
                        mkdir($pathCoverThumbnailNew , 0777);
                    }
                }
                //end Managament folder digital content  n cover
                

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
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'model2' => $model2,
            ]);
        }
    }

    public function actionWorksheetfieldItems($id)
    {

        $model  = \common\models\Worksheetfields::findAll(['ID' => $id]);
        $model2 = \common\models\Worksheetfielditems::findAll(['WorksheetField_id' => $id]);

        $workshetModel= \common\models\Worksheets::findOne(['ID' => $model[0]['Worksheet_id']]);
        $fieldModel   = \common\models\Fields::findOne(['ID' => $model[0]['Field_id']]);
        if (!$model2) {
            $model2 = [new Worksheetfielditems()];
        }

        return $this->renderAjax('_worksheetfieldItems', [
            'model' =>$model2,
            'wid'   => $model[0]['Worksheet_id'],
            'wfid'  => $id,
            'fieldsName'     => $fieldModel->Tag,
            'worksheetsName' => $workshetModel->Name,
            ]);

    }

    public function actionSaveWorksheetfieldItems()
    {
        $id = Yii::$app->request->post('WorksheetField_id');
        $wid = Yii::$app->request->post('Worksheet_id');
        $model = $this->findModel3($id);
        
        if (!$model) {
            $model = [new Worksheetfielditems()];
        }


        if(Worksheetfielditems::loadMultiple($model, Yii::$app->request->post()))
        {
            $arr = Yii::$app->request->post('Worksheetfielditems', []);
            $data = \common\models\Worksheetfielditems::findAll(['WorksheetField_id' => $id]);
            if ($data) {
                $workshielfield = Worksheetfielditems::deleteAll(['WorksheetField_id' => $id]);
            }

            
            foreach ($arr as $loc) {
                $model3new = new Worksheetfielditems;
                $model3new->WorksheetField_id=$id;
                $model3new->Name=$loc['Name'];
                $model3new->StartPosition=$loc['StartPosition'];
                $model3new->Length=$loc['Length'];
                $model3new->DefaultValue=$loc['DefaultValue'];
                $model3new->IdemTag=$loc['IdemTag'];
                $model3new->IdemStartPosition=$loc['IdemStartPosition'];
                // karna error pada refrence id saat save
                if($loc['Refference_id'] != '0'){
                    $model3new->RefferenceMode="Dropdown";
                    $model3new->Refference_id=$loc['Refference_id'];
                }else{
                    $model3new->RefferenceMode="None";
                    $model3new->Refference_id=null;
                }
                $model3new->save(false);
                
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

        return $this->redirect(['update', 'id' => $wid]);
    }

    /**
     * Deletes an existing Worksheets model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        // $this->findModel2($id)->delete();
        Worksheetfields::deleteAll(['Worksheet_id' => $id]);
        // $col = "DELETE FROM `worksheetfields` WHERE `Worksheet_id` = " . $id . "; ";
        
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
     * Finds the Worksheets model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Worksheets the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Worksheets::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
     protected function findModel2($id) {
        if (($model2 = Worksheetfields::findAll(['Worksheet_id' => $id]  )) !== null) {
            return $model2;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function findModel3($id) {
        if (($model3 = Worksheetfielditems::findAll(['WorksheetField_id' => $id]  )) !== null) {
            return $model3;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
}
