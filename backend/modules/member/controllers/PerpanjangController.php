<?php

namespace backend\modules\member\controllers;

use Yii;
use common\models\MemberPerpanjangan;
use common\models\Members;
use common\models\MemberPerpanjanganSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\DynamicModel;

/**
 * PerpanjangController implements the CRUD actions for MemberPerpanjangan model.
 */
class PerpanjangController extends Controller
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
     * Lists all MemberPerpanjangan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MemberPerpanjanganSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single MemberPerpanjangan model.
     * @param double $id
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
     * Creates a new MemberPerpanjangan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $data=Yii::$app->request->post(); 
        $member = explode('-',$_POST['MemberPerpanjangan']['Member_id']);
        $memberNo = trim($member[0]);

        $modelMember = \common\models\Members::find()->where(['MemberNo'=>$memberNo])->one();
        /*echo ($modelMember->Fullname);
        die;*/
        $model = new MemberPerpanjangan;
        $modelDynamic = new \yii\base\DynamicModel(['jenisAnggota']);
        $modelDynamic->addRule('jenisAnggota', 'string',['max'=>32]);
        $modelDynamic = new Members;


        if ($model->load($data)) {
            $jenisAnggota=$data['Members']['jenisAnggota'];
            $model->Member_id = $modelMember->ID;
            $model->Tanggal = \common\components\Helpers::DateToMysqlFormat('-',$model->Tanggal);

            if($model->save()){
                $modelMember->EndDate =$model->Tanggal;
                $modelMember->save(false);
                $modelMember->JenisAnggota_id = $jenisAnggota;

                Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Success Save'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);

            } else {
                $datas = $model->getErrors();
                if ($datas) {
                Yii::$app->getSession()->setFlash('success', [
                        'type' => 'danger',
                        'duration' => 5500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app',$datas['Error Saving'][0]),
                        'title' => 'Error',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);

                }
                

            }
             
			
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'modelMember' => $modelDynamic,
            ]);
        }
    }

    /**
     * Updates an existing MemberPerpanjangan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param double $id
     * @return mixed
     */
    public function actionUpdate($id)
    {   
        $data=Yii::$app->request->post(); 
        $model = $this->findModel($id);
        $oldTGL = $model->Tanggal;
        $date = new \DateTime($model->Tanggal);
        $model->Tanggal = $date->format('Y-m-d');

        $modelDynamic = \common\models\Members::find()->where(['ID'=>$model->Member_id])->one();
        // $maskedData = Yii::$app->db->createCommand("SELECT CONCAT(CONCAT(m.MemberNo,' - '), m.Fullname) AS nama FROM member_perpanjangan mp LEFT JOIN members m ON mp.member_id = m.ID")->queryScalar();
        // $modelDynamic->Fullname = $maskedData;



       
        if ($model->load(Yii::$app->request->post()) ) {

            $model->Tanggal = \common\components\Helpers::DateToMysqlFormat('-',$model->Tanggal);
            $jenisAnggota=$data['Members']['jenisAnggota'];
            if($model->save()){
  
                $modelMember = \common\models\Members::find()->where(['ID'=>$model->Member_id])->one();
                $modelMember->EndDate =$model->Tanggal;
                $modelMember->JenisAnggota_id = $jenisAnggota;
                $modelMember->save(false);
                
                Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Success Save'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);

            } else {
                $datas = $model->getErrors();
                if ($datas) {
                Yii::$app->getSession()->setFlash('success', [
                        'type' => 'danger',
                        'duration' => 5500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app',$datas['Error Saving'][0]),
                        'title' => 'Error',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);

                }
                

            }

            return $this->redirect(['index']);
        } else {
            $date = new \DateTime($oldTGL);
            $model->Tanggal = $date->format('d-m-Y');
            return $this->render('update', [
                'model' => $model,
                'modelMember' => $modelDynamic,
            ]);
        }
    }

    /**
     * Deletes an existing MemberPerpanjangan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param double $id
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
     * Finds the MemberPerpanjangan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param double $id
     * @return MemberPerpanjangan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        
        if (($model = MemberPerpanjangan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionChangeAnggota() {
    $id = $_GET['id'];
    $modelAnggota = \common\models\JenisAnggota::findOne(['id'=>$id]);
    $exp = date('Y-m-d', strtotime("+".$modelAnggota->MasaBerlakuAnggota." days"));



    $data = [
                'Biaya' =>   $modelAnggota->BiayaPerpanjangan,
                'Expired' => \common\components\Helpers::DateTimeToViewFormat($exp),               
            ];

    return \yii\helpers\Json::encode($data);

    }
    


    public function actionDetailHistori()
    {
       $id = Yii::$app->request->get('id'); 
       $modelHistori = \common\models\Modelhistory::find();
       
       
       //$searchModel = new \common\models\MasterKependudukanSearch();
       //$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
       
       $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $modelHistori,
        ]);
       
       $modelHistori->andWhere(['field_id' => $id]);
       $modelHistori->andWhere(['type' => '1']);
       $modelHistori->andWhere(['table' => 'member_perpanjangan']);
       
        return $this->renderAjax('detailHistori', [  
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            //'rules' => $rules
        ]);
        
       //var_dump($modelHistori); 
       
    }
    public function actionJenisAnggota($id){

    $model = \common\models\Members::find()->where(['MemberNo'=>$id])->one();
    return $this->renderAjax('_jenisAnggota', [
                'modelss' => $model,
    ]);

    }

    public function actionGetMember($id){

    $model = \common\models\Members::find()->where(['MemberNo'=>$id])->all();
    return $this->renderAjax('_getMember', [
                'member' => $model,
    ]);

    }


    /**
     * Find the data members by member numbers.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  string $memberNo
     * @return json members data
     * @throws HttpException if the model cannot be found
     */
    public function actionCheckMembership($memberNo)
    {
        if (($model = \common\models\Members::findOne(['MemberNo'=>$memberNo])) !== null) {

            $modelAnggota = \common\models\JenisAnggota::findOne(['id'=>$model->JenisAnggota_id]);
            $masaBerlaku = $modelAnggota->MasaBerlakuAnggota;
            $exp = date('Y-m-d', strtotime("+".$masaBerlaku." days"));
             $data = [
                          
                          'Fullname' => $model->Fullname,
                          'EndDate' =>  \common\components\Helpers::DateTimeToViewFormat($model->EndDate),
                          'Biaya' =>   $model->jenisAnggota->BiayaPerpanjangan,
                          'Expired' => \common\components\Helpers::DateTimeToViewFormat($exp),
                          'jenisAnggota' => $model->JenisAnggota_id,

                            
                      ];

            return \yii\helpers\Json::encode($data);
        } else {
            throw new \yii\web\HttpException(404, 'No.Anggota '. $memberNo . ' tidak ada pada database kami.');
        }
    }
}
