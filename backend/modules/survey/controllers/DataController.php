<?php

namespace backend\modules\survey\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Json;

use dosamigos\highcharts\HighCharts;

//Models
use common\models\Survey;
use common\models\SurveySearch;
use common\models\SurveyPertanyaan;
use common\models\SurveyPilihan;
use common\components\Helpers;

/**
 * SurveyController implements the CRUD actions for Survey model.
 */
class DataController extends Controller
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
     * Lists all Survey models.
     * @return mixed
     */
    public function actionIndex()
    {
        $rules = Json::decode(Yii::$app->request->get('rules'));

        $searchModel = new SurveySearch;
        $dataProvider = $searchModel->advancedSearch(0,$rules);
        // $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());


        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'rules' => $rules
        ]);
    }

    /**
     * Displays a single Survey model.
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
     * Creates a new Survey model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Survey;

        if ($model->load(Yii::$app->request->post())) {
            $model->TanggalMulai = Helpers::convertDate(Yii::$app->request->post('Survey')['TglMulai']);
            $model->TanggalSelesai = Helpers::convertDate(Yii::$app->request->post('Survey')['TglSelesai']);

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
                // return $this->redirect(['view', 'id' => $model->ID]);
                return $this->redirect(['index']);
            } 
        } 
        return $this->render('create', [
            'model' => $model,
            ]);
        
    }

    /**
     * Updates an existing Survey model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->TanggalMulai = date("d-m-Y", strtotime($model->TanggalMulai));
        $model->TanggalSelesai = date("d-m-Y", strtotime($model->TanggalSelesai));
        
        if ($model->load(Yii::$app->request->post()) ) {
            $model->TanggalMulai = Helpers::convertDate(Yii::$app->request->post('Survey')['TglMulai']);
            $model->TanggalSelesai = Helpers::convertDate(Yii::$app->request->post('Survey')['TglSelesai']);
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
                // return $this->redirect(['view', 'id' => $model->ID]);
                return $this->redirect(['index']);
            } 
            else
            {
                $model->TanggalMulai = date("d-m-Y", strtotime($model->TanggalMulai));
                $model->TanggalSelesai = date("d-m-Y", strtotime($model->TanggalSelesai));
            }
        }
        return $this->render('update', [
            'model' => $model,
            ]);
        
    }

    /**
     * Deletes an existing Survey model.
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
     * [actionHasilSurvey description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionHasilSurvey($id)
    {
        // echo "Hasil Survey";
        $model = Survey::find()->where(['ID' => $id])->asArray()->one();
       
        $pertanyaan = SurveyPertanyaan::find()->where(['Survey_id' => $id])->orderBy('NoUrut')->asArray()->all();
        
        if ($pertanyaan) {
	        foreach ($pertanyaan as $pertanyaan) 
	        {
	            $forChart = SurveyPilihan::find()->where(['Survey_Pertanyaan_id' => $pertanyaan['ID']])->asArray()->all();

	            // Variable label untuk Diagram PIE
	            $labelChart = array();

	            // Variable untuk diagram batang
	            $valueChart = array();
	            $categoriesChart = array();

	            foreach ($forChart as $forChart) 
	            {

	                array_push($labelChart, ['y' => intval($forChart['ChoosenCount']),
	                    'name' => $forChart['Pilihan']]);

	                array_push($valueChart,intval($forChart['ChoosenCount']));
	                array_push($categoriesChart,$forChart['Pilihan']);
	            }

	            if ($pertanyaan['JenisPertanyaan'] == 'Pilihan') 
	            {
	                $chartContent[] = HighCharts::widget([
	                    'id'=>'myChart'.$pertanyaan['ID'],
	                    'clientOptions' => [
	                        'chart' => [
	                                'type' => 'pie'
	                        ],
	                        'title' => [
	                             'text' => $pertanyaan['Pertanyaan']
	                             ],

	                        'tooltip' => [
	                             'pointFormat' => '{series.name}: <b>{point.percentage:.1f}%</b>'
	                             ],

	                        'plotOptions' => [
	                             'pie' => [
	                                 'allowPointSelect' => true,
	                                 'cursor' => 'pointer',
	                                 'dataLabels' => [
	                                    'enabled' => true,
	                                    'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
	                                    'style' => ['color'=> ('Highcharts.theme && Highcharts.theme.contrastTextColor') || 'black'],
	                                  ],
	                                 'showInLegend' => true,
	                                 ],
	                             ],
	                        'series' => [
	                            [
	                                'name' => 'Pilihan',
	                                'colorByPoint' => 'true',
	                                'data' => 
	                                    $labelChart
	                                ,
	                            ]
	                        ],
	                    ]
	                ]);
	            }
	        }


	        return $this->renderAjax('hasil-survey',[
	            'model' => $model,
	            'chartContent' => $chartContent,
	            ]);

        } 
        else 
        {
        	echo "<center><h3>Data Kosong</h3></center>";
        }
        

    }



    /**
     * [actionDetailHistori description]
     * @return [type] [description]
     */
    public function actionDetailHistori()
    {
       $id = Yii::$app->request->get('id'); 
       $modelHistori = \common\models\Modelhistory::find();

       $modelHistori->andWhere([
            'field_id' => $id,
            'table' => 'survey',
        ]);
       
       
       //$searchModel = new \common\models\MasterKependudukanSearch();
       //$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
       
       $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $modelHistori,
        ]);
       
       $modelHistori->andFilterWhere(['like', 'field_id', $id]);
       
        return $this->renderAjax('detailHistori', [  
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            //'rules' => $rules
        ]);
        
       //var_dump($modelHistori); 
       
    }


    /**
     * Finds the Survey model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Survey the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Survey::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
