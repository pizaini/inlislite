<?php

namespace backend\modules\setting\umum\controllers;

use Yii;

use yii\base\DynamicModel;
use yii\web\UploadedFile;
use common\components\DirectoryHelpers;

class DataPerpustakaanController extends \yii\web\Controller
{

    public function actionIndex()
    {

        $model = new DynamicModel([
            'NamaPerpustakaan',
            //'NamaLokasiPerpustakaan',
            'JenisPerpustakaan',
            'IsUseKop',
            'logo',
            'kop',
            'image',


        ]);
        $model->addRule([
            'NamaPerpustakaan',
            //'NamaLokasiPerpustakaan',
            'JenisPerpustakaan', 'IsUseKop',], 'required'
        );

        $model->NamaPerpustakaan = Yii::$app->config->get('NamaPerpustakaan');
        // $model->NamaLokasiPerpustakaan = Yii::$app->config->get('NamaLokasiPerpustakaan');

        $model->JenisPerpustakaan = Yii::$app->config->get('JenisPerpustakaan');
        $model->IsUseKop = Yii::$app->config->get('IsUseKop');


        if ($model->load(Yii::$app->request->post())) {
            $model->logo = UploadedFile::getInstance($model, 'logo');
            // $model->kop = UploadedFile::getInstance($model, 'kop');

            $temp_logo = ($model->logo != "" ? logo : kop);

            $model->image = UploadedFile::getInstance($model, $temp_logo);
            $files_uploaded = '../uploaded_files/aplikasi/' . "temp_image" . '.' . "png";

            $model->image->saveAs($files_uploaded,false);
            
            
            $mimetype=DirectoryHelpers::mimeType($files_uploaded);
            if ($mimetype) {
                unlink($files_uploaded);
                if ($model->validate()) {
                    if ($model->image == "") {
                        goto tidak;
                    } else if ($model->logo != "") {
                        $model->image->saveAs('../uploaded_files/aplikasi/' . "logo_perpusnas_2015" . '.' . "png");                        
                    } else{
                        $model->image->saveAs('../uploaded_files/aplikasi/' . "kop" . '.' . "png");
                    }
                    tidak:
                    Yii::$app->config->set('NamaPerpustakaan', Yii::$app->request->post('DynamicModel')['NamaPerpustakaan']);
                    //Yii::$app->config->set('NamaLokasiPerpustakaan', Yii::$app->request->post('DynamicModel')['NamaLokasiPerpustakaan']);
                    Yii::$app->config->set('JenisPerpustakaan', Yii::$app->request->post('DynamicModel')['JenisPerpustakaan']);
                    Yii::$app->config->set('IsUseKop', Yii::$app->request->post('DynamicModel')['IsUseKop']);


                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app', 'Success Save'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
                } 
            }else {
                Yii::$app->getSession()->setFlash('error', [
                        'type' => 'error',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app', 'Failed Save'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);

            }

            return $this->redirect(['index']);
        } else {
            return $this->render('index', [
                'model' => $model,]);
        }
    }

}