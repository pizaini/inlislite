<?php

namespace backend\modules\sirkulasi\controllers;

use Yii;
use common\models\Pengiriman;
use common\models\PengirimanSearch;
use common\models\PengirimanKoleksi;
use common\models\PengirimanKoleksiSearch;
use common\models\Collections;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use kartik\mpdf\Pdf;

/**
 * PenerimaanKoleksiController implements the CRUD actions for Pengiriman model.
 */
class PenerimaanKoleksiController extends Controller
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
     * Lists all Pengiriman models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PengirimanSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Pengiriman model.
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
     * Creates a new Pengiriman model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pengiriman;

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
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionDetail($id)
    {

        /*echo \Yii::$app->params['footerInfoLeft'];
        echo \Yii::$app->params['footerInfoRight'];
        die;*/

        $model = new \yii\base\DynamicModel([
            'nomorBarcode'
        ]);

        $modelPenerimaan = $this->findModel($id);
        $model->addRule(['nomorBarcode'], 'required');


        //Get data from model
        $searchModel = new PengirimanKoleksiSearch;

        $queryParams= Yii::$app->request->getQueryParams();
        $queryParams['PengirimanKoleksiSearch']['PengirimanID'] = $id;
        $dataProvider = $searchModel->search($queryParams);


        // $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        // !get data from model

        // Get Scanned collections
        // $ScannedCollections = Stockopnamedetail::find()->select('collections.NomorBarcode')->joinWith('collection')->where(['StockOpnameID'=>$id])->asArray()->all();
        // foreach ($ScannedCollections as $ScannedCollections) {
        //     $ScannedCollectionItems[] = $ScannedCollections['NomorBarcode'];
        // }
        // // print_r($ScannedCollectionItems);die;
        // // Get data for Unscanned Collections
        $sqlSelectCol = Collections::find()
                        ->addSelect(["collections.*", "CONCAT('<div style=width:300px >','<b>',catalogs.Title,'</b>','<br/>'
                                        ,(CASE WHEN worksheets.ID <> 4 AND catalogs.edition IS NOT NULL AND NOT LENGTH(catalogs.edition) = 0 THEN CONCAT('<br/>',catalogs.edition) ELSE '' END)
                                        ,'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher
                                        ,' ',catalogs.PublishYear
                                        ,(CASE WHEN catalogs.PhysicalDescription IS NOT NULL THEN CONCAT('<br/>',catalogs.PhysicalDescription) ELSE '' END)
                                        ,(CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END)
                                        ,'<br/>',worksheets.name,'</div>'
                                        ) AS DataBib"])
                        ->leftJoin('catalogs',' collections.Catalog_id = catalogs.id')
                        ->leftJoin('pengiriman_koleksi',' pengiriman_koleksi.Collection_id = collections.ID')
                        ->leftJoin('worksheets',' catalogs.Worksheet_id = worksheets.id'); 
        $sqlSelectCol->Where('PengirimanID = '.$id);
        $sqlSelectCol->andWhere('IsCheck = 0');
        if ($ScannedCollectionItems) {
            $sqlSelectCol->andWhere(['NOT IN', 'NomorBarcode', $ScannedCollectionItems]);
        } 
        
        
        $dataProviderUnscannedCollections = new yii\data\ActiveDataProvider([
                'query' => $sqlSelectCol,
                // 'totalCount' => $count,
            ]);

        
        return $this->render('createDetail', [
                    'model' => $model,
                    'modelPenerimaan'=>$modelPenerimaan,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                    'dataProviderUnscannedCollections'=>$dataProviderUnscannedCollections
                ]);
       
    }

    public function actionViewKoleksi()
    {
        if (Yii::$app->request->post()) {

            // echo'<pre>';print_r($_POST);die;
            $PengirimanID = trim($_POST["PengirimanID"]);
            $NomorBarcode = trim(trim($_POST["NomorBarcode"],'*'));
            
            $TglTransaksi = trim($_POST["TglTransaksi"]);

            if ($TglTransaksi == ""){
                $LoanDate = date('Y-m-d');
            }
            else
            {
                $LoanDate = \common\components\Helpers::DateToMysqlFormat('-',$TglTransaksi);
            }

            if(isset($NomorBarcode) && $NomorBarcode != "")
            {
                $modelPengirimanKoleksiDetail = new PengirimanKoleksi;

                $model              = \common\models\Collections::find()->where(['NomorBarcode'=>$NomorBarcode])->one();
                $modelPengirimanKoleksiCheck = PengirimanKoleksi::find()->joinWith('collection')->where(['PengirimanID'=>$PengirimanID,'collections.NomorBarcode'=>$NomorBarcode,'IsCheck'=>1])->one();

                if ($modelPengirimanKoleksiCheck) 
                {
                    throw new \yii\web\HttpException(404, 'Item dengan nomor ini Sudah ada.');
                } 
                else 
                {    
                    if ($model) {

                        // update status check di pengiriman koleksi
                        Yii::$app->db->createCommand()->update('pengiriman_koleksi',['IsCheck' => 1],
                            [
                                'NOBARCODE' => $NomorBarcode,
                                'PengirimanID' => $PengirimanID
                            ])->execute();

                        // update status ketersediaan collection
                        Yii::$app->db->createCommand(
                            'UPDATE collections AS c
                            INNER JOIN pengiriman_koleksi AS b ON b.Collection_id = c.`ID`
                            SET c.Status_id = 1
                            WHERE b.NOBARCODE = "'.$NomorBarcode.'" AND b.PengirimanID = "'.$PengirimanID.'" AND b.IsCheck = 1'
                        )->execute();
                    } else {
                        throw new \yii\web\HttpException(404, 'Nomor Barcode tidak valid.');
                    }
                    
                }
                

              
                    
            }
            else
            {
                 throw new \yii\web\HttpException(404, 'Nomor Barcode tidak boleh kosong.');
            }
            
                
               
        }
                 
    }

    /**
     * Updates an existing Pengiriman model.
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

    public function actionCetak(){
        $id = $_GET['id'];
        $get_pengiriman = Pengiriman::find()->where(['ID' => $id])->one();

        $content['judulCetak'] = $get_pengiriman['JudulKiriman'];
        $content['penanggungjawab'] = $get_pengiriman['PenanggungJawab'];
        $content['nip'] = $get_pengiriman['NipPenanggungJawab'];

        $model = PengirimanKoleksi::find()->where(['PengirimanID' => $id])->all();
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

    /**
     * Deletes an existing Pengiriman model.
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
     * Finds the Pengiriman model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pengiriman the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pengiriman::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
