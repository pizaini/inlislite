<?php

namespace backend\modules\setting\umum\controllers;

use Yii;
use yii\console\Controller;
use yii\base\DynamicModel;
use yii\web\UploadedFile;
use yii\helpers\Json;

class HarvestTajukController extends \yii\web\Controller
{
	public function actionIndex(){
        $model = new \backend\models\ImportTajukSubjekForm();
        if($_POST){
            // $out = \common\Components\HarvestTajukSubjek::harvesttajuksubjek($_POST['tanggal'],'http://opac.perpusnas.go.id/inlis_web_service/mtom.asmx/GetAuthorityData?reqlastdate=');
            $out = \common\Components\HarvestTajukSubjek::harvesttajuksubjek('2010-11-05','http://opac.perpusnas.go.id/inlis_web_service/mtom.asmx/GetAuthorityDataTags?ReqLastDate=');
            if($out){
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app', 'Data berhasil disimpan'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
                return $this->render('index', ['model' => $model]);
            }else{
                Yii::$app->getSession()->setFlash('failed', [
                    'type' => 'error',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app', 'Data gagal disimpan'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
                return $this->render('index', ['model' => $model]);
            }
            // print_r($out);die;
        }else{

            return $this->render('index', ['model' => $model,'err' => $err]);
            // return $this->render('index');    
        }
        
    }

    public function actionImport(){
        $model = new \backend\models\ImportTajukSubjekForm();
        if (Yii::$app->request->isPost) {
            $model->file = \yii\web\UploadedFile::getInstance($model, 'file');
            $err='';

            if ($model->upload()) {
                // proses import via background
                // $path = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
                // $err = exec($path.'/yii hello/index');

                // proses import file online
                // file is uploaded successfully
               // $err = $model->import();
               $kata='';
               // foreach ($err as $key => $value) {
               //     $kata.=$value[0];
               // }
                $err = $model->import();

               //  $model->deleteFile();

               if($err){
                    Yii::$app->getSession()->setFlash('error', [
                            'type' => 'danger',
                            'duration' => 6500,
                            'icon' => 'fa fa-info-circle',
                            'message' => Yii::t('app','Data gagal diimport.'.$kata),
                            'title' => 'Info',
                            'positonY' => Yii::$app->params['flashMessagePositionY'],
                            'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]);
                    return $this->redirect('index');
               }else{
                    Yii::$app->getSession()->setFlash('success', [
                            'type' => 'success',
                            'duration' => 6500,
                            'icon' => 'fa fa-info-circle',
                            'message' => Yii::t('app','Data berhasil diimport'),
                            'title' => 'Info',
                            'positonY' => Yii::$app->params['flashMessagePositionY'],
                            'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]); 
                    return $this->redirect('index');
               }
                    

            }
           
        }

        return $this->render('index', ['model' => $model,'err' => $err]);
    }

    public function actionTes(){
        $handle = fopen("http://opac.perpusnas.go.id/inlis_web_service/mtom.asmx/GetAuthorityDataTags3", "r");
        
    }

}
