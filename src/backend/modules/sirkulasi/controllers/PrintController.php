<?php
namespace backend\modules\sirkulasi\controllers;

use kartik\mpdf\Pdf;
use Yii;
use yii\web\Controller;
class PrintController extends Controller
{
    

    /**
     * [actionPrintKuitansi description]
     * @return [type] [description]
     */
    public function actionPrintKuitansi(){
        $this->layout = "@app/views/layouts/print";
        $transactionID = isset($_GET['transactionID']) ? $_GET['transactionID'] : null;
        //var_dump($transactionID);
        //echo $transactionID['transactionID'];
        if($transactionID != ""){
             $model = \common\models\Collectionloans::find()->where(['ID' => $transactionID])->one();
             return $this->render('viewstruk', array(
                                'collectionLoan_id'  => $transactionID,
                                'model'            => $model,
                            )); 

        }
    }

////////////////////////////////////////////////////////////////////////////////////
    /**
     * [actionPrintStrukKuitansi description]
     * @return [type] [description]
     */
    public function actionPrintStrukKuitansi(){
        $this->layout = "@app/views/layouts/print";
        $transactionID = isset($_GET['transactionID']) ? $_GET['transactionID'] : null;
        //var_dump($transactionID);
        //echo $transactionID['transactionID'];
        if($transactionID != ""){
             $model = \common\models\Collectionloans::find()->where(['ID' => $transactionID])->one();
             return $this->renderPartial('viewStrukPeminjaman', array(
                                'collectionLoan_id'  => $transactionID,
                                'model'            => $model,
                            )); 

        }
    }
////////////////////////////////////////////////////////////////////////////////////

    /**
     * [actionCetakSlipPengembalian description]
     * @return [type] [description]
     */
    public function actionCetakSlipPengembalian(){

        $daftarItem = Yii::$app->sirkulasi->getItemPengembalian();   

        $this->layout = "@app/views/layouts/print";
        $NoPinjam = isset($_GET['NoPinjam']) ? $_GET['NoPinjam'] : null;
        $for = isset($_GET['for']) ? $_GET['for'] : null;
        //var_dump($transactionID);
        if($NoPinjam != ""){
             $model = \common\models\Collectionloanitems::find()
                        ->where(['CollectionLoan_id' => $NoPinjam])
                        ->andWhere(['LoanStatus' => 'Return'])
                        ->all();

             return $this->renderPartial('viewSlipPengembalian', array(
                                'collectionLoan_id'  => $NoPinjam,
                                'for'  => $for,
                                'model'            => $model,
                                'daftarItem'            => $daftarItem,
                            )); 
            
           

        } 
       
    }

/////////////////////////////////////////////////////////////////////////////////////

    /**
     * [actionCetakStrukPengembalian description]
     * @return [type] [description]
     */
    public function actionCetakStrukPengembalian(){

        // $daftarItem = Yii::$app->sirkulasi->getItemPengembalianSafe();
        $daftarItem = Yii::$app->sirkulasi->getItemPengembalian();

        $this->layout = "@app/views/layouts/print"; 
        $NoPinjam = isset($_GET['NoPinjam']) ? $_GET['NoPinjam'] : null;
        $for = isset($_GET['for']) ? $_GET['for'] : null;
        //var_dump($transactionID);
        //echo $transactionID['transactionID'];
        if($NoPinjam != ""){
             $model = \common\models\Collectionloanitems::find()
                        ->where(['CollectionLoan_id' => $NoPinjam])
                        ->andWhere(['LoanStatus' => 'Return'])
                        ->all();
            return $this->renderPartial('viewStrukPengembalian', array(
                'collectionLoan_id'     => $NoPinjam,
                'for'  => $for,
                'model'                 => $model,
                'daftarItem'            => $daftarItem,
                )); 
        } 
       
    }
/////////////////////////////////////////////////////////////////////////////////////


    /**
     * [actionCetakSlipPelanggaran description]
     * @return pdf
     */
    public function actionCetakSlipPelanggaran(){
        $this->layout = "@backend/views/layouts/print";
        $NoPinjam = isset($_GET['NoPinjam']) ? $_GET['NoPinjam'] : null;
        //var_dump($transactionID);
        //echo $transactionID['transactionID'];
        if($NoPinjam != ""){
             $model = \common\models\Pelanggaran::find()
                        ->where(['CollectionLoan_id' => $NoPinjam])
                        //->andWhere(['LoanStatus' => 'Return'])
                        ->all();

            return $this->renderPartial('viewSlipPelanggaran', 
                [
                    'collectionLoan_id'  => $NoPinjam,
                    'model'            => $model,
                ]);
                        

        }
       
    }




/////////////////////////////////////////////////////////////////





    /**
     * [actionCetakStrukPelanggaran description]
     * @return [type] [description]
     */
    public function actionCetakStrukPelanggaran(){
        $this->layout = "@backend/views/layouts/print";
        $NoPinjam = isset($_GET['NoPinjam']) ? $_GET['NoPinjam'] : null;
        if($NoPinjam != ""){
             $model = \common\models\Pelanggaran::find()
                        ->where(['CollectionLoan_id' => $NoPinjam])
                        ->all();
            
            return $this->renderPartial('viewStrukPelanggaran', 
                [
                    'collectionLoan_id'  => $NoPinjam,
                    'model'            => $model,
                ]);
        }
       
    }





    /**
     * [actionPrintKuitansiPerpanjangan description]
     * @return [type] [description]
     */
    public function actionPrintKuitansiPerpanjangan(){
        $this->layout = "@app/views/layouts/print";
        $transactionID = isset($_GET['transactionID']) ? $_GET['transactionID'] : null;
        //var_dump($transactionID);
        //echo $transactionID['transactionID'];
        if($transactionID != ""){
             $model = \common\models\Collectionloans::find()->where(['ID' => $transactionID])->one();
             return $this->render('viewstrukPerpanjangan', array(
                                'collectionLoan_id'  => $transactionID,
                                'model'            => $model,
                            )); 

        }



       //  $this->layout = "@app/views/layouts/print";
       //  $NoPinjam = isset($_GET['NoPinjam']) ? $_GET['NoPinjam'] : null;

       //  if($NoPinjam != ""){
       //     $model = \common\models\Collectionloanitems::find()
       //     ->where(['CollectionLoan_id' => $NoPinjam])
       //     ->andWhere(['LoanStatus' => 'Loan'])
       //     ->all();
       //     $content= $this->renderPartial('viewSlipPerpanjangan', array(
       //      'collectionLoan_id'  => $NoPinjam,
       //      'model'            => $model,
       //      )); 


       //     $pdf = new Pdf([
       //          'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
       //          'destination' => Pdf::DEST_BROWSER, 
       //          'format' => Pdf::FORMAT_A4, 
       //          'content' => $content,
       //          'options' => [
       //          'title' => 'Slip Perpanjangan',
       //          'subject' => Yii::$app->config->get('NamaPerpustakaan')
       //          ],
       //          'methods' => [ 
       //          'SetJS'=>['this.print();'], 
       //          ]
       //          ]);

       //     return $pdf->render();
       // } 









    }
/////////////////////////////////////////////////////////////////





}
