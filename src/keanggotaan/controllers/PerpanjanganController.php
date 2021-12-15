<?php

namespace keanggotaan\controllers;

use Yii;
use common\models\MemberPerpanjanganMandiri;
use common\models\Members;
use common\components\OpacHelpers;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\DynamicModel;

class PerpanjanganController extends \yii\web\Controller
{

    public function actionCreate()
    {
     	$connection = Yii::$app->db;
        $data=Yii::$app->request->post(); 	

        $memberID = Members::find()->where(['MemberNo'=>Yii::$app->user->identity->NoAnggota])->one();
        $memberID->EndDate =  \common\components\Helpers::DateTimeToViewFormat($memberID->EndDate);

       	$modelAnggota = \common\models\JenisAnggota::findOne(['id'=>$memberID->JenisAnggota_id]);
        $masaBerlaku = $modelAnggota->MasaBerlakuAnggota;
        $exp = date('Y-m-d', strtotime("+".$masaBerlaku." days"));
        $Biaya = $modelAnggota->BiayaPerpanjangan;

     

        $modelMember = \common\models\Members::find()->where(['MemberNo'=>Yii::$app->user->identity->NoAnggota])->one();
 
        $model = new MemberPerpanjanganMandiri;
        $model->Biaya = $Biaya;
        $model->Keterangan = "Perpanjangan Mandiri oleh Anggota";
        $model->Tanggal   = \common\components\Helpers::DateTimeToViewFormat($exp);
        $modelDynamic = new \yii\base\DynamicModel(['jenisAnggota']);
        $modelDynamic->addRule('jenisAnggota', 'string',['max'=>32]);
        $modelDynamic = new Members;


            if ($model->load($data)) {
            	$command = $connection->createCommand("SET FOREIGN_KEY_CHECKS=0;")->execute();
                 

                $jenisAnggota=$data['Members']['jenisAnggota'];
                $model->Member_id = $modelMember->ID;
                $model->Tanggal = \common\components\Helpers::DateToMysqlFormat('-',$model->Tanggal);            
                $model->CreateDate =  new \yii\db\Expression('NOW()');
                $model->UpdateDate =  new \yii\db\Expression('NOW()');
    			$model->CreateTerminal = OpacHelpers::getIP();
    			$model->UpdateTerminal = OpacHelpers::getIP();
                
                if($model->save(false)){
                    $modelMember->EndDate =$model->Tanggal;
                    $modelMember->save(false);
                    //	$command = $connection->createCommand("SET FOREIGN_KEY_CHECKS=1;")->execute();

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
                 
    			
                return $this->redirect(['/user/index']);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'modelMember' => $memberID,
                ]);
            }
    }

}
