<?php

/**
 * @file    KartuAnggotaController.
 * @date    21/8/2015
 * @time    12:25 AM
 * @author  Henry <alvin_vna@yahoo.com>
 * @copyright Copyright (c) 2015 Perpustakaan Nasional Republik Indonesia
 * @license
 */

namespace backend\modules\setting\member\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\base\DynamicModel;
use common\models\Settingparameters;
use common\models\SettingparameterSearch;

/**
 * KartuAnggotaController implements the CRUD actions for Settingparameters model.
 */
class KartuAnggotaController extends Controller {

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
     * Lists all Settingparameters models.
     * @return mixed
     */
    public function actionIndex() {

        // $model = new DynamicModel([
        //   'Value'
        //]);
        $model = new DynamicModel([
            'Text_BELAKANG',
        ]);
                $model2 = new DynamicModel([
            'KartuAnggota',
        ]);
        $model->addRule([
            'Text_BELAKANG',
                ], 'required');
                $model2->addRule([
            'KartuAnggota',
                ], 'required');

        /*         * $model->addRule(['Value'], 'required');
          ->addRule(['Value'], 'file', ['extensions' => 'zip']);

          $modelTextBelakang =  Settingparameters::findOne(14);
          $modelTextbelakang->addRule(['Value'], 'required');

          $modelImagesBelakang = Settingparameters::findOne(38);* */
        $model->Text_BELAKANG = Yii::$app->config->get('Text_BELAKANG');
        $model2->KartuAnggota = Yii::$app->config->get('KartuAnggota');
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                Yii::$app->config->set('Text_BELAKANG', Yii::$app->request->post('DynamicModel')['Text_BELAKANG']);
                //$modelTextBelakang->Value = Yii::$app->request->post('Settingparameters')['Value'];
                //$modelTextBelakang->save();
                return $this->refresh();
            }
        }
        
        else {
            return $this->render('index', [
                        'model' => $model,
                'model2' => $model2,
                            //'modelTextBelakang' => $modelTextBelakang,
                            //'modelImagesBelakang' => $modelImagesBelakang,
            ]);
        }
    }

    /**
     *
     * @return boolean
     */
    public function actionAmbil($id) {

        return $this->renderPartial('_formKartuAnggota');
    }

    /**
     * Fungsi untuk upload kartu anggota berdasarkan template.
     * @param  [int] $id [id kartu anggota]
     * @return [type]     [description]
     */
    public function actionUpload($id) {
        if (isset($_FILES['kartu_anggota' . $id])) {
            $file = \yii\web\UploadedFile::getInstanceByName('kartu_anggota' . $id);
            if ($file->saveAs(Yii::getAlias('@uploaded_files/settings/kartu_anggota/bg_cardmember' . $id . '.png'))) {
                //Now save file data to database
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app', 'Success Upload'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
                $this->redirect(['index']);
                return true;
            }
        }
    }

    /**
     * Detail Untuk Priview Kartu Anggota.
     * @param  [int] $id [description]
     * @return Image Kartu anggota
     */
    public function actionDetail($id) {
        $detailKartu = '<center>' . Html::img(Yii::$app->urlManager->createUrl("../uploaded_files/settings/kartu_anggota/bg_cardmember". $id . '.png'), [
                    'class' => 'template-thumbnail',
                    'width' => '90%'
                ]) . '<center>';
        return $detailKartu;
    }
        public function actionAktifkan($id) {
            $model2 = new DynamicModel([
            'KartuAnggota',
        ]);
             $model2->addRule([
            'KartuAnggota',
                ], 'required');
             
              if ($id!="") {
                  
            
                Yii::$app->config->set('KartuAnggota', $id);
                //$modelTextBelakang->Value = Yii::$app->request->post('Settingparameters')['Value'];
                //$modelTextBelakang->save();
                $this->redirect(['index']);
            
        }

    }

    /**
     * Fungsi untuk upload kartu anggota bagian belakang.
     * @return [type]     [description]
     */
    public function actionUploadKartuBelakang() {
        if (isset($_FILES['kartu_anggota_belakang'])) {
            $file = \yii\web\UploadedFile::getInstanceByName('kartu_anggota_belakang');
            if ($file->saveAs(Yii::getAlias('@uploaded_files/settings/kartu_anggota/bg_cardmemberbelakang.png'))) {
                //Now save file data to database
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app', 'Success Upload'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
                $this->redirect(['index']);
                return true;
            }
        }
    }

}
