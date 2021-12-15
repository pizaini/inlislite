<?php

namespace backend\modules\setting\member\controllers;

use Yii;
use common\models\MasterKependudukan;
use common\models\MasterKependudukanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\Agama;

/**
 * JenisPerpustakaanController implements the CRUD actions for JenisPerpustakaan model.
 */
class KependudukanController extends Controller {

    public function behaviors() {
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
     * Lists all JenisPerpustakaan models.
     * @return mixed
     */
    public function actionIndex() {

        $model2 = new \backend\models\ImportKependudukanForm();
        $searchModel = new MasterKependudukanSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        if (Yii::$app->request->isPost) {
            $model2->file = \yii\web\UploadedFile::getInstance($model2, 'file');

            if ($model2->upload()) {
                // file is uploaded successfully
               $model2->import();
               $model2->deleteFile();
                Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Data berhasil diimport.'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
                return $this->redirect(['index']);
               // echo "file is uploaded successfully";
            }
           
        }


        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                    'model2' => $model2,
        ]);
    }

    /**
     * Displays a single JenisPerpustakaan model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new JenisPerpustakaan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MasterKependudukan;

        if ($model->load(Yii::$app->request->post())) {
            $jk = $_POST['MasterKependudukan']['jenis'];
            if ($jk == 'P') {
                $jkn = 1;
            } else {
                $jkn = 0;
            }

            $sts = $_POST['MasterKependudukan']['status'];
            if ($sts == '0') {
                $stsn = "BELUM KAWIN";
            } else {
                $stsn = "KAWIN";
            }
            $tempat = $_POST['MasterKependudukan']['lhrtempat'];
            $tanggal = $_POST['MasterKependudukan']['lhrtanggal'];
            $rt=$_POST['MasterKependudukan']['rt'];
            $rw=$_POST['MasterKependudukan']['rw'];
            $desa=$_POST['MasterKependudukan']['alamat'];
            $nama_kec=$_POST['MasterKependudukan']['nama_kec'];
            $fisik = $_POST['MasterKependudukan']['klain_fisik'];
            if($fisik==0){
                $model->klain_fisik="Tidak Ada";
            }
            else{
                $model->klain_fisik="Ada";
            }
            $model->alamat= $desa." RT"." : ".$rt." RW : ".$rw." KELURAHAN/DESA ".$desa. " Kecamatan: ". $nama_kec;
            $model->agm = Agama::findOne($model->agama);
            $model->agm = $model->agm->Name;
            // $model->al1 = $desa;

            $model->ttl = $tempat . ":" . $tanggal;
            $model->jk = $jkn;
            $model->sts = $stsn;
            if( $model->save()){
            // echo '<pre>';print_r($model);echo '</pre>';die;

            Yii::$app->getSession()->setFlash('success', [
                'type' => 'info',
                'duration' => 500,
                'icon' => 'fa fa-info-circle',
                'message' => Yii::t('app', 'Success Save'),
                'title' => 'Info',
                'positonY' => Yii::$app->params['flashMessagePositionY'],
                'positonX' => Yii::$app->params['flashMessagePositionX']
            ]);
            return $this->redirect(['index']);
            } else {
                $model->alamat='';
                return $this->render('create', [
                           'model' => $model,
                ]);

            }
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing JenisPerpustakaan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        $model->klain_fisik = strtolower($model->klain_fisik);

        $model->klain_fisik = ($model->klain_fisik == 'ada') ? true : false ;

        if ($model->load(Yii::$app->request->post())) {
          
            $jk = $_POST['MasterKependudukan']['jenis'];
            if ($jk == 'P') {
                $jkn = 1;
            } else {
                $jkn = 0;
            }

            $sts = $_POST['MasterKependudukan']['status'];
            if ($sts == '0') {
                $stsn = "BELUM KAWIN";
            } else {
                $stsn = "KAWIN";
            }
            $tempat = $_POST['MasterKependudukan']['lhrtempat'];
            $tanggal = $_POST['MasterKependudukan']['lhrtanggal'];
            $rt=$_POST['MasterKependudukan']['rt'];
            $rw=$_POST['MasterKependudukan']['rw'];
            $desa=$_POST['MasterKependudukan']['alamat'];
            $nama_kec=$_POST['MasterKependudukan']['nama_kec'];
            $fisik = $_POST['MasterKependudukan']['klain_fisik'];
            if($fisik==0){
                $model->klain_fisik="Tidak Ada";
            }
            else{
                $model->klain_fisik="Ada";
            }
            $model->alamat= $desa." RT"." : ".$rt." RW : ".$rw." KELURAHAN/DESA: ".$desa. " Kecamatan: ". $nama_kec;

            $model->agm = Agama::findOne($model->agama);
            $model->agm = $model->agm->Name;


            $model->ttl = $tempat . ":" . $tanggal;
            $model->jk = $jkn;
            $model->sts = $stsn;
            if( $model->save()){

            Yii::$app->getSession()->setFlash('success', [
                'type' => 'info',
                'duration' => 500,
                'icon' => 'fa fa-info-circle',
                'message' => Yii::t('app', 'Success Edit'),
                'title' => 'Info',
                'positonY' => Yii::$app->params['flashMessagePositionY'],
                'positonX' => Yii::$app->params['flashMessagePositionX']
            ]);
            return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing JenisPerpustakaan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', [
            'type' => 'info',
            'duration' => 500,
            'icon' => 'fa fa-info-circle',
            'message' => Yii::t('app', 'Success Delete'),
            'title' => 'Info',
            'positonY' => Yii::$app->params['flashMessagePositionY'],
            'positonX' => Yii::$app->params['flashMessagePositionX']
        ]);
        return $this->redirect(['index']);
    }

    /**
     * List For MemberField
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionCustom($id) {
        $jenis_perpus = JenisPerpustakaan::findOne($id)->Name;
        //Ambil data dari MemberFields.
        $searchModel = new MemberFieldSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->pagination = false;

        return $this->render('_listMemberFields', [
                    'jenis_perpus' => $jenis_perpus,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }

    /**
     * Finds the JenisPerpustakaan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JenisPerpustakaan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MasterKependudukan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
