<?php

namespace backend\modules\setting\member\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

// MODEL
use common\models\JenisAnggota;
use common\models\JenisAnggotaSearch;
use common\models\CollectioncategorySearch;
use common\models\Collectioncategorysdefault;
use common\models\LocationLibrarySearch;
use common\models\LocationLibraryDefault;


/**
 * JenisAnggotaController implements the CRUD actions for JenisAnggota model.
 */
class JenisAnggotaController extends Controller
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
     * Lists all JenisAnggota models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JenisAnggotaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        //$dataProvider->pagination = false;
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single JenisAnggota model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        //
        /*return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
*/
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            //echo "a";
        return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new JenisAnggota model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new JenisAnggota;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
            ]);
        }
    }

    /**
     * Updates an existing JenisAnggota model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //Yii::$app->session->setFlash('success', 'Thank you ');
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
     * Deletes an existing JenisAnggota model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $jenis= JenisAnggota::findOne($id)->jenisanggota;
         try {
            $this->findModel($id)->delete();
            
        } 
        catch(\Exception $e){ 
            Yii::$app->getSession()->setFlash('failed', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Gagal Terhapus, jenis anggota '). $jenis .Yii::t('app',' terdapat anggota'),
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
     * Finds the JenisAnggota model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return JenisAnggota the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JenisAnggota::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


     /**
     * List For Default Kategori Form
     * @param   int $id from jenis anggota.
     * @return [type]     [description]
     */
    public function actionDefaultKategori($id)
    {
        $jenis= JenisAnggota::findOne($id)->jenisanggota;
        //Ambil data dari MemberFields.
        $searchModel = new CollectioncategorySearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->pagination =false;

        return $this->render('_listKategoriKoleksi',[
            'jenis' => $jenis,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    /**
     * List For Default Lokasi Form
     * @param  int $id from jenis anggota.
     * @return [type]     [description]
     */
    public function actionDefaultLokasi($id)
    {
        $jenis= JenisAnggota::findOne($id)->jenisanggota;
        //Ambil data dari MemberFields.
        $searchModel = new LocationLibrarySearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->pagination =false;

        return $this->render('_listLokasi',[
            'jenis' => $jenis,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Creates a new Collection Category Default.
     * If creation is successful, the browser will be redirected to the '_listKategoriKoleksi' page.
     * @return mixed
     */
    
    public function actionSaveKategori()
    {

         if (Yii::$app->request->post('pk')) {
            $selection=(array)Yii::$app->request->post('pk');//typecasting
            $id_jenis = Yii::$app->request->post('id');
            if (isset($selection)) {
                    // Hapus row berdasarkan jenis anggotanya.
                    $a = Collectioncategorysdefault::deleteAll('JenisAnggota_id = :id ', [':id' => $id_jenis]);
                    foreach ($selection as $val) {
                        // Insert
                        $model = new Collectioncategorysdefault;
                        $model->CollectionCategory_id = $val;
                        $model->JenisAnggota_id = $id_jenis;
                        $model->save();
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
                    $jenis= JenisAnggota::findOne($id_jenis)->jenisanggota;
                    return $this->redirect(['default-kategori',
                            'id'=>$id_jenis,
                            'jenis' => $jenis
                        ]);
                }
         }
    }

    /**
     * Creates a new Location Library Default.
     * If creation is successful, the browser will be redirected to the '_listLokasi' page.
     * @return mixed
     */
    
    public function actionSaveLokasi()
    {

         if (Yii::$app->request->post('pk')) {
            $selection=(array)Yii::$app->request->post('pk');//typecasting
            $id_jenis = Yii::$app->request->post('id');
            if (isset($selection)) {
                    // Hapus row berdasarkan jenis anggotanya.
                    $a = LocationLibraryDefault::deleteAll('JenisAnggota_id = :id ', [':id' => $id_jenis]);
                    foreach ($selection as $val) {
                        // Insert
                        $model = new LocationLibraryDefault;
                        $model->Location_Library_id = $val;
                        $model->JenisAnggota_id = $id_jenis;
                        $model->save();
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
                    $jenis= JenisAnggota::findOne($id_jenis)->jenisanggota;
                    return $this->redirect(['default-lokasi',
                            'id'=>$id_jenis,
                            'jenis' => $jenis
                        ]);
                }
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


    public function actionTest()
    {
                                $model = new LocationLibraryDefault;
                        $model->Location_Library_id = '1';
                        $model->JenisAnggota_id = '2';
                        $model->save();
    }











    
}
