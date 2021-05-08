<?php

namespace backend\modules\setting\sms\controllers;


use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\ErrorException;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;
use yii\base\DynamicModel;



//MODEL
use common\models\Members;
use common\models\Outbox;
use common\models\Smslogs;
use common\models\MemberSearch;
// Component
use common\components\EJCropper;
use common\components\MemberHelpers;
use common\components\Helpers;
use kartik\mpdf\Pdf;

use leandrogehlen\querybuilder\Translator;




/**
 * MemberController implements the CRUD actions for Members model.
 * @author Henry <alvin_vna@yahoo.com>
 */
class SmsManualController extends Controller
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
     * Creates a new Members model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionIndex()
    {   
       
        $model = new Members;
        $model2 = new Outbox;
        
       /* $modelDynamic = new \yii\base\DynamicModel(['Fullname']);
        $modelDynamic->addRule(['Fullname'], 'string', ['max' => 128]);*/

        $modelDynamic = new \yii\base\DynamicModel(['Fullname']);
        $modelDynamic->addRule('Fullname', 'string',['max'=>32]);


       if ($model2->load(Yii::$app->request->post()) ) {
            
            echo"<pre>";
            $data=Yii::$app->request->post();
            $pesan=str_split($data['Outbox']['TextDecoded'], 153);
            if(Yii::$app->request->post()['all']){
                $model2->scenario = 'sendAll';
                $nohp = Yii::$app->db->createCommand('SELECT nohp FROM members WHERE nohp <> ""')->queryAll();
                $nohp = array_column($nohp, 'nohp');
            }else{
                $model2->scenario = 'sendOne';
                $nohp=$data['Outbox']['DestinationNumber'];
                $nohp = explode(',', $nohp);
            }
            // echo'<pre>';print_r($nohp);die;
            
            foreach ($nohp as $key => $nohp) {

                $jmlpesan= sizeof($pesan);
                $num_padded = sprintf('%02d', $jmlpesan);
                $udh='050003A7';
                $listudh;

                $outboxStat = Yii::$app->db->createCommand("SHOW TABLE STATUS LIKE 'outbox'")->queryAll();
                $dataAutoIncrement=$outboxStat[0]['Auto_increment'];

                if ($jmlpesan==1) {
                    $out = new Outbox; 
                    $out->DestinationNumber = trim($nohp);
                    $out->TextDecoded = $pesan;
                    $out->save(false);
                } else  {
                //pesan di pecah per 153 karkter
                //pesan pertama di insert ke outbox
                //pesan selanjutnya di insert ke outbox_multipart
                //ID pesan di outbox sama outbox multipartnya harus sama
                //UDHnya jangan sampai kebalik formatnya XXXXXX YY ZZ
                //XXXXXX itu identifier random
                //YY maximal part
                //ZZ sequence
                //misal 050003A7 02 01 jadi ada 2 pesan totalnya
                //udh pesan 1 : 050003A70201
                //udh pesan 2 : 050003A70202
                for ($i=0; $i <$jmlpesan ; $i++) { 
                $listudh[$i]=$udh.$num_padded.sprintf('%02d', $i+1);
                

                  if ($i==0) {
                    $params2 = [':nohp' => trim($nohp), ':udh' => $listudh[$i], ':pesan' => $pesan[$i]];
                    $command = Yii::$app->db->createCommand("INSERT into outbox(DestinationNumber, UDH, TextDecoded, MultiPart, CreatorID) values(:nohp,:udh,:pesan,'true','gammu')");
                    $command->bindValues($params2);
                    $command->execute();
                  } else
                  {
                    $params2 = [':udh' => $listudh[$i], ':pesan' => $pesan[$i]];
                    $command = Yii::$app->db->createCommand("INSERT into outbox_multipart(UDH, TextDecoded, ID, SequencePosition) values(:udh,:pesan,".($dataAutoIncrement).",".($i+1).")");
                    $command->bindValues($params2);
                    $command->execute();
                  }
                }
                }
            }
            // echo'<pre>';print_r($nohp);die;
            


            
           Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            return $this->redirect('index');        
        } 

            return $this->render('index', [
                'model' => $model,
                'model2' =>  $model2,
                'modelDynamic' => $modelDynamic,
                //'memberNo' => $memberNo,
            ]);
        

    }


    /**
     * Finds the Members model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param double $id
     * @return Members the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Members::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionCreate(){
      die;
    }
    


    /**
     * Untuk Mengambil data Kependudukan
     * @return [type] [description]
     */
    public function actionDetailKependudukan()
    {

        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new MemberSearch;
        $dataProvider = $searchModel->advancedSearch($rules);       

        return $this->renderAjax('detailPenduduk', [  // ubah ini
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'rules' => $rules
        ]);
    }

    /**
         * Fungsi untuk binding data dari modal Booking.
         *
         * @param int $id from modal Booking
         */
    public function actionBindPenduduk($id)
    {

        $model =  \common\models\MemberSearch::findOne($id);
    

        return \yii\helpers\Json::encode([
                'ID' => $model->ID,
                'Fullname' => $model->Fullname,
                'DestinationNumber' => $model->Phone,
                'Phone' => $model->Phone,
                'NoHp' => $model->NoHp,
            ]);
    
    }

    public function actionPilih(){
        $catID = (is_array($_POST['id']) ? $_POST['id'] : array($_POST['id']));
        $catID = str_replace('ids%5B%5D=', '', $catID);
        $catID = explode('&', $catID[0]);
        
        $cek = '';
        $hp = '';
        foreach ($catID as $key => $value) {
            $model =  \common\models\MemberSearch::findOne($value);
            $cek .= $model->Fullname.', ';
            $hp .= $model->NoHp.', ';
        }
        $cek = rtrim($cek, ', ');
        $hp = rtrim($hp, ', ');
        echo json_encode(array('nama' => $cek, 'hp' => $hp));
    }

}
