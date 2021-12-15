<?php

namespace backend\modules\deposit\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Expression;
use common\models\Letter;
use common\models\LetterSearch;
use common\models\LetterDetail;
use common\models\LetterDetailSearch;
use kartik\mpdf\Pdf;


/**
 * TerimaKasihController implements the CRUD actions for Letter model.
 */
class TerimaKasihController extends Controller
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
     * Lists all Letter models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LetterSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Letter model.
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
     * Creates a new Letter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Letter;

        
        if ($model->load(Yii::$app->request->post())) {
            // $expression = new Expression('NOW()');
            // $now = (new \yii\db\Query)->select($expression)->scalar();  // SELECT NOW();
            // echo '<pre>';print_r(Yii::$app->request->post());
            // echo '<pre>';print_r($model->TYPE_OF_DELIVERY);
            // echo date("m.Y");die;
        $data = Letter::find()
                ->select(['(COUNT(*)+1) AS count'])->asArray()
                ->One();
        $model->LETTER_NUMBER_UT = str_pad($data['count'],3,"0", STR_PAD_LEFT).'/'.$model->TYPE_OF_DELIVERY.'/'.date("m.Y");
        // echo '<pre>';print_r($model);die;
        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($model->save(false)){
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
                echo var_dump($modelcoll->getErrors()); die;
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Letter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        // echo '<pre>';print_r($id);die;
        $searchModel = new LetterDetailSearch;
        $dataProvider = $searchModel->searchDetail(Yii::$app->request->getQueryParams(),$id);

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
            return $this->redirect(['create']);
        } else {
            return $this->render('update', [
                'dataProvider' => $dataProvider,
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Letter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id,$form=null)
    {
        if ($form) {
            LetterDetail::findOne($id)->delete();
        }else{
            Letter::findOne($id)->delete();
        }
        // $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Delete'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
        // return $this->redirect(['update']);
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionCetak($id)
    {
        // echo '<pre>';print_r($id);die;
        // $pdf = new Pdf([
        // 'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
        // 'orientation' => Pdf::ORIENT_PORTRAIT,
        // 'marginTop' => $set,
        // 'marginLeft' => 0,
        // 'marginRight' => 0,
        // 'options' => [
        // 'title' => 'Laporan Frekuensi',
        // 'subject' => 'Perpustakaan Nasional Republik Indonesia'],
        // ]);

        $sql = "SELECT letter.`ACCEPT_DATE` AS tgl_terima, 
                collectionmedias.`Name` AS nama,
                SUM(letter_detail.`QUANTITY`) AS jumlah, 
                SUM(letter_detail.`COPY`) AS jum_copy, 
                deposit_ws.`nama_penerbit` AS nama_penerbit,
                CASE
                 WHEN deposit_ws.`no_telp1` != NULL OR deposit_ws.`no_telp1` != ''
                 THEN deposit_ws.`no_telp1`
                 WHEN deposit_ws.`no_telp2` != NULL OR deposit_ws.`no_telp2` != ''
                 THEN deposit_ws.`no_telp2`
                 ELSE deposit_ws.`no_telp3`
                 END AS tlp
                FROM letter
                LEFT JOIN letter_detail ON letter_detail.`LETTER_ID` = letter.`ID`
                LEFT JOIN deposit_ws ON deposit_ws.`ID` = letter.`PUBLISHER_ID`
                LEFT JOIN collectionmedias ON collectionmedias.`ID` = letter_detail.`SUB_TYPE_COLLECTION`
                WHERE letter.`ID` = ".$id."
                ";   

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 
        
        $locID = Yii::$app->location->get('ID');
        $letter = \common\models\Letter::findOne(['ID' => $id]);
        $deposit_ws = \common\models\DepositWs::findOne(['ID' => $letter->PUBLISHER_ID]);
        $loclibrary = \common\models\LocationLibrary::findOne(['ID' => $locID]);

        $content['sql'] = $sql; 
        $content['TableLaporan'] = $data; 
        $content['letter'] = $letter; 
        $content['deposit_ws'] = $deposit_ws; 
        $content['loclibrary'] = $loclibrary; 

        // $content = 'teeeeeeeeeeeeeeest';
        $content = $this->renderPartial('pdf-terima-kasih', $content);

        $pdf = new Pdf(); // or new Pdf();
        $mpdf = $pdf->api; // fetches mpdf api
        $mpdf->WriteHtml($content); // call mpdf write html
        echo $mpdf->Output('ucapan-terima-kasih.pdf', 'D'); // call the mpdf api output as needed
    }

    /**
     * Finds the Letter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Letter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Letter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
