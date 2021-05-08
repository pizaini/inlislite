<?php

namespace backend\modules\akuisisi\controllers;

use Yii;
use common\models\Pengiriman;
use common\models\PengirimanKoleksi;
use common\models\PengirimanKoleksiSearch;

use yii\base\DynamicModel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;
use yii\validators\Validator;
use yii\helpers\Json;
use kartik\mpdf\Pdf;
use yii\db\Query;

/**
 * PengirimanKoleksiController implements the CRUD actions for PengirimanKoleksi model.
 */
class PengirimanKoleksiController extends Controller
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
     * Lists all PengirimanKoleksi models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PengirimanKoleksiSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single PengirimanKoleksi model.
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

    public function actionViewKoleksi(){
        if (Yii::$app->request->post()) {

            $NomorBarcode = trim($_POST["NOBARCODE"]);
            // print_r($NomorBarcode);die;

            if(isset($NomorBarcode) && $NomorBarcode != "")
            {
                $model              = \common\components\SirkulasiHelpers::loadModelPengirimanKoleksi($NomorBarcode);
                $data = [
                    'CollectionID' => $model['CollectionID'],
                    'NOBARCODE' => $model['NomorBarcode'],
                    'CallNumber' => $model['CallNumber'],
                    'NoInduk' => $model['NoInduk'],
                    'Title' =>  $model['Title'],
                    'TahunTerbit' =>  $model['PublishYear'],
                    'Quantity' =>  $model['jumlahEksemplar'],
                  ];
                // echo'<pre>';print_r($data);die;
                // periksa dahulu 
                if(Yii::$app->sirkulasi->checkItemPengirimanKoleksi($NomorBarcode))
                {
                    // Jika ada
                    throw new \yii\web\HttpException(404, 'Item dengan Nomor Barcode : '.$model['NOBARCODE'].' sudah ada di Keranjang!');
                }
                else
                {

                    Yii::$app->sirkulasi->addItemPengirimanKoleksi($data);
                }

                $daftarItem = Yii::$app->sirkulasi->getItemPengirimanKoleksi();
                
                return $this->renderAjax('_listKoleksi',
                    array(
                        'daftarItem'=>$daftarItem,
                        'n' => 1,
                    ),true);     
            }
            else
            {
                 throw new \yii\web\HttpException(404, 'Nomor Barcode tidak boleh kosong.');
            }
            
                
               
        }
    }

    /**
     * Creates a new PengirimanKoleksi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if($_POST){
            
            $err=[];
            // echo'<pre>';print_r($_POST);die;
            try {
                $post = $_POST['PengirimanKoleksi'];
                // echo'<pre>';print_r($post);die;
                foreach ($post as $key => $value) {
                    $model = new PengirimanKoleksi();
                    $model->Collection_id = $value['CollectionID'];
                    $model->NOBARCODE = $value['NomorBarcode'];
                    $model->JUDUL = $value['Judul'];
                    $model->TAHUNTERBIT = $value['TahunTerbit'];
                    $model->CALLNUMBER = $value['CallNumber'];
                    $model->NOINDUK = $value['NoInduk'];
                    // $model->QUANTITY = $value['Quantity'];
                    $model->TANGGALKIRIM = $value['TanggalKirim'];
                    $model->save();
                }
                Yii::$app->sirkulasi->removePengirimanKoleksi();
                echo json_encode(array('status' => 0));
                // die;
            } catch (\Exception $e) {
                if ($e->errorInfo[2]) {
                    array_push($err, $e->errorInfo[2]);
                    echo json_encode(array('status' => 1));
                }
            }
        }else{
            Yii::$app->sirkulasi->removePengirimanKoleksi();

            $model = new \yii\base\DynamicModel([
                'NOBARCODE'
            ]);

            $model->addRule(['NOBARCODE'], 'required');

            return $this->render('create',[
                'model'=>$model
            ]);
        }
    }

    public function actionPengirimanKoleksiCetak(){
        if($_POST){
            $searchModel = new PengirimanKoleksiSearch;
            $dataProvider = $searchModel->advancedSearch($_POST);
            // echo'<pre>';print_r($dataProvider);die;
            return $this->renderAjax('_listPengirimanKoleksiCetak',[
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'post' => $_POST
            ]);
        }

        return $this->render('pengiriman-koleksi-cetak');
    }

    /**
     * Updates an existing PengirimanKoleksi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			 Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Edit'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PengirimanKoleksi model.
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

    public function actionHapusItem(){
        // print_r($_POST);die;
        if (isset($_POST['NOBARCODE'])){
            $session = new Session();
            $session->open();

            $item = $session['pengiriman-koleksi'];

            if (count($item) > 0) {
              $item[$_POST['index']] = null;
              $newItem = [];

              foreach ($item as $row) {
                if ($row != null) {
                  $newItem[] = $row;
                }
              }

              $session->set('pengiriman-koleksi', $newItem);

              //return $this->redirect(['addtocart', 'id' => $id]);
            }
            $daftarItem = Yii::$app->sirkulasi->getItemPengirimanKoleksi();
            return $this->renderAjax('_listKoleksi',
                    array(
                            'daftarItem'=>$daftarItem,
                            'n' => 1,
                        ),true);
        }
    }

    public function actionDeletePengiriman($id){
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


        return $this->redirect(['pengiriman-koleksi-cetak']);
    }

    public function actionPrintPengirimanKoleksi(){
        // echo'<pre>';print_r($_GET);die;
        $itemID = implode(',', $_GET['ids']);
        if($itemID != ""){

            $pengiriman = new Pengiriman;
            $pengiriman->JudulKiriman = $_GET['judulCetak'];
            $pengiriman->PenanggungJawab = $_GET['penanggungjawab'];
            $pengiriman->NipPenanggungJawab = $_GET['nip'];
            $pengiriman->FromDate = date("Y-m-d", strtotime($_GET['from_date']));
            $pengiriman->ToDate = date("Y-m-d", strtotime($_GET['to_date']));
            if($pengiriman->save(false)){
                // print_r($pengiriman->ID);die;

                $set_cetak_id = Yii::$app->db->createCommand()->update('pengiriman_koleksi', ['PengirimanID' => $pengiriman->ID], 'ID IN ('.$itemID.')')->execute();

                $model = Yii::$app->db->createCommand('SELECT * FROM pengiriman_koleksi WHERE ID IN ('.$itemID.')')->queryAll();

                $content['judulCetak'] = $_GET['judulCetak'];
                $content['penanggungjawab'] = $_GET['penanggungjawab'];
                $content['nip'] = $_GET['nip'];
                $content['model'] = $model;

                $pdf = new Pdf([
                    'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
                    'orientation' => Pdf::ORIENT_LANDSCAPE,
                    'marginTop' => $set,
                    'marginRight' => 0,
                    'marginLeft' => 0,
                    'content' => $this->renderPartial('viewcetak', $content),
                    'options' => [
                    'title' => 'Laporan Pengiriman',
                    'subject' => 'Perpustakaan Nasional Republik Indonesia'],
                    'methods' => [ 
                        'SetHeader'=> $header,
                        'SetFooter'=>['<div class="footer" style="margin-right:60px;">Page {PAGENO}</div>'],
                    ],
                ]);
                return $pdf->render();


            }

            
        }
    }

    /**
     * Finds the PengirimanKoleksi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PengirimanKoleksi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PengirimanKoleksi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
