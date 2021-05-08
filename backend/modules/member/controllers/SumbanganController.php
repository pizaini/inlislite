<?php

namespace backend\modules\member\controllers;

use Yii;
use common\models\Sumbangan;
use common\models\SumbanganSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Session;

/**
 * SumbanganController implements the CRUD actions for Sumbangan model.
 */
class SumbanganController extends Controller
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
     * Lists all Sumbangan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SumbanganSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Sumbangan model.
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
     * Creates a new Sumbangan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        
        $model = new Sumbangan;

        if ($model->load(Yii::$app->request->post())) {

            
            $trans = Yii::$app->db->beginTransaction();
            try{

                $member = explode('-',$_POST['Sumbangan']['MemberNo']);
                $memberNo = trim($member[0]);
                
                $modelMember = \common\models\Members::find()->where(['MemberNo'=>$memberNo])->one();

                $model->Member_id = $modelMember->ID;

                if($model->save(false)){

                    $sumbangan_id = $model->getPrimaryKey();
                    // save detail koleksi jika ada.
                    $daftarItem = Yii::$app->sirkulasi->getItemSumbangan();
                    var_dump($daftarItem);
                    if(count($daftarItem) > 0){
                          foreach ($daftarItem as $item){
                            $modelSumbanganKoleksi = new \common\models\SumbanganKoleksi;
                            $modelSumbanganKoleksi->Sumbangan_id  = $sumbangan_id;
                            $modelSumbanganKoleksi->Collection_id  = $item['ID'];
                            $modelSumbanganKoleksi->save(false);
                          }
                    }

                     // commit transaction
                    $trans->commit();

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

               
               return $this->redirect(['create']);

            }catch (CDbException $e) {
                $trans->rollback();
                $success = false;
                $model->addError('Error Saving', $e->getMessage());
            }
            
        } else {
           Yii::$app->sirkulasi->removeItemSumbangan();
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Sumbangan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $modelMember = \common\models\Members::findOne($model->Member_id);
        $model->MemberNo = $modelMember->MemberNo ." - " .$modelMember->Fullname;




        if ($model->load(Yii::$app->request->post())) {

          $trans = Yii::$app->db->beginTransaction();
          try{
                $member = explode('-',$_POST['Sumbangan']['MemberNo']);
                $memberNo = trim($member[0]);
                
                $modelMember = \common\models\Members::find()->where(['MemberNo'=>$memberNo])->one();

                $model->Member_id = $modelMember->ID;
                if($model->save(false)){

                    // Delete Sumbangan Koleksi where Sumbangan Id
                   
                    $rowDeleted = \common\models\SumbanganKoleksi::deleteAll('Sumbangan_id = :sumbangan_id', [':sumbangan_id' => $model->ID]);

                    $sumbangan_id = $model->ID;
                    // save detail koleksi jika ada.
                    $daftarItem = Yii::$app->sirkulasi->getItemSumbangan();
                    var_dump($daftarItem);
                    if(count($daftarItem) > 0){
                          foreach ($daftarItem as $item){
                            $modelSumbanganKoleksi = new \common\models\SumbanganKoleksi;
                            $modelSumbanganKoleksi->Sumbangan_id  = $sumbangan_id;
                            $modelSumbanganKoleksi->Collection_id  = $item['ID'];
                            $modelSumbanganKoleksi->save(false);
                          }
                    }


                    // commit transaction
                    $trans->commit();

                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Success Edit'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
                  return $this->redirect(['update', 'id' => $model->ID]);
                }

          }catch (CDbException $e) {
                $trans->rollback();
                $success = false;
                $model->addError('Error Saving', $e->getMessage());
          }
			 
        } else {

            Yii::$app->sirkulasi->removeItemSumbangan();

            // cek apakah ada sumbangan koleksi
            $sumbangan_koleksi = $model->sumbanganKoleksis;
            foreach ($sumbangan_koleksi as $row) {
              $modelCollection = \common\models\Collections::findOne($row['Collection_id']);

              $data = [
                        'ID' => $row['Collection_id'],
                        'NomorBarcode' =>  $modelCollection->NomorBarcode,
                        'NomorInduk' =>  $modelCollection->NoInduk,
                        'DataBib' =>  $modelCollection->catalog->Title,
                        'NomorPanggil' =>  $modelCollection->catalog->CallNumber

                    ];

              Yii::$app->sirkulasi->addItemSumbangan($data);    
            }
            

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Sumbangan model.
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
     * Finds the Sumbangan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sumbangan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sumbangan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

     /**
     * Creates a new Collections model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPilihJudul()
    {
        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new \common\models\CollectionSearch;
        $dataProvider = $searchModel->advancedSearch(0,$rules);

        return $this->renderAjax('_pilihJudul', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'rules'=>$rules
            ]);
    }



    /**
     * Creates a new Collections model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPilihJudulProses()
    {
        if (Yii::$app->request->isAjax) 
        {
            $post = Yii::$app->request->post();
            $collectionID=$post['id'];
            $model = \common\models\Collections::findOne($collectionID);

            
           $data = [
                        'ID' => $collectionID,
                        'NomorBarcode' =>  $model->NomorBarcode,
                        'NomorInduk' =>  $model->NoInduk,
                        'DataBib' =>  $model->catalog->Title,
                        'NomorPanggil' =>  $model->catalog->CallNumber

                    ];
             // periksa dahulu 
            if(Yii::$app->sirkulasi->checkItemSumbangan($model->ID)){
                // Jika ada
                throw new \yii\web\HttpException(404, 'Item dengan No.barcode : '.$model->NomorBarcode.' sudah ada di list!');
            }else{

                $countItem = count(Yii::$app->sirkulasi->getItemSumbangan());
                Yii::$app->sirkulasi->addItemSumbangan($data);                           
            }

            // mendapatkan data
            $daftarItem = Yii::$app->sirkulasi->getItemSumbangan();
            return  $this->renderAjax('_listKoleksi',
                array(
                    'daftarItem'=>$daftarItem,
                     'n' => 1,
                ),true);

           
        }
    }

    /**
     * [actionHapusItem description]
     * @return [type] [description]
     */
    public function actionHapusItem(){
            if (isset($_POST['ID'])){
                $session = new Session();
                $session->open();

                $item = $session['sumbangan'];

                if (count($item) > 0) {
                  $item[$_POST['index']] = null;
                  $newItem = [];

                  foreach ($item as $row) {
                    if ($row != null) {
                      $newItem[] = $row;
                    }
                  }

                  $session->set('sumbangan', $newItem);

                  //return $this->redirect(['addtocart', 'id' => $id]);
                }
                $daftarItem = Yii::$app->sirkulasi->getItemSumbangan();
                return  $this->renderAjax('_listKoleksi',
                    array(
                        'daftarItem'=>$daftarItem,
                         'n' => 1,
                    ),true);
            }
    }
}
