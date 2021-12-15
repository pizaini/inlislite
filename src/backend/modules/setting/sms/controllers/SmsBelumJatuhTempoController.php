<?php

namespace backend\modules\setting\sms\controllers;

use Yii;
use common\models\Settingparameters;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\DynamicModel;


/**
 * SmsBelumJatuhTempoController implements the CRUD actions for Collectionsources model.
 */
class SmsBelumJatuhTempoController extends Controller
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
     * Form setting Settingparamaters models for akuisisi.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new DynamicModel([
            'Value1',
            'Value2',
            'Value3',
            'Value4',
          
        ]);
        $model->addRule([
            'Value1',
            'Value2',
            'Value4',
            'Value4'], 'required');

        $model->Value1=Yii::$app->config->get('SmsBelumJatuhTempoAktif');
        $model->Value2=Yii::$app->config->get('JedaBelumJatuhTempo');
        $model->Value3=Yii::$app->config->get('SmsBelumJatuhTempoJam');
        $model->Value4=Yii::$app->config->get('SmsBelumJatuhTempoPesan');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) 
            {
            
                Yii::$app->config->set('SmsBelumJatuhTempoAktif', Yii::$app->request->post('DynamicModel')['Value1']);
                Yii::$app->config->set('JedaBelumJatuhTempo', Yii::$app->request->post('DynamicModel')['Value2']);
                Yii::$app->config->set('SmsBelumJatuhTempoJam', Yii::$app->request->post('DynamicModel')['Value3']);
                Yii::$app->config->set('SmsBelumJatuhTempoPesan', Yii::$app->request->post('DynamicModel')['Value4']);
                 Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);

                $JedaHari=Yii::$app->config->get('JedaBelumJatuhTempo');
                $waktukirim=Yii::$app->config->get('SmsBelumJatuhTempoJam');

                $Aktifasi=Yii::$app->config->get('SmsBelumJatuhTempoAktif');
                $command = Yii::$app->db->createCommand("DROP EVENT IF EXISTS SmsWarningBelumJatuhTempo;");
                $command->execute();
                
                if ($Aktifasi=='TRUE'){
                    $command = Yii::$app->db->createCommand("

                    CREATE EVENT SmsWarningBelumJatuhTempo
                       ON SCHEDULE EVERY 1 DAY STARTS '2015-11-16 ".$waktukirim.":00' ON COMPLETION NOT PRESERVE ENABLE
                        DO
                        INSERT INTO `outbox` (`DestinationNumber`,`TextDecoded`) 
                         SELECT members.NoHp, CONCAT('Yth. Anggota ',members.MemberNo,', pinjaman koleksi \" ',
                        IF(LENGTH(catalogs.title > 35),CONCAT(SUBSTR(catalogs.title,1,35),'...\"'),
                        SUBSTR(catalogs.title,1,35)),' akan jatuh tempo pada ',DATE(duedate),'. Harap segera mengembalikan ') AS IsiSMS  
                        --, DATEDIFF(duedate, NOW()) as terlambat
                        FROM collectionloanitems
                        LEFT JOIN collections ON collectionloanitems.Collection_id = collections.id
                        INNER JOIN catalogs ON collections.catalog_id = catalogs.id
                        INNER JOIN members ON collectionloanitems.member_id = members.id
                        WHERE loanstatus = 'Loan'
                        AND DATEDIFF(duedate, NOW()) BETWEEN 0 AND ".$JedaHari.";
                    ");
                    $command->execute();
                }
            }else{

                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Failed Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            }
            return $this->redirect(['index']);
         }else{
                return $this->render('index', [
                'model' => $model,
            ]);
         }

    }
}
