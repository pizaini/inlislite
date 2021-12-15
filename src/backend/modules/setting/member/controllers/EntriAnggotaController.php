<?php

namespace backend\modules\setting\member\controllers;

use Yii;
use yii\base\DynamicModel;

class EntriAnggotaController extends \yii\web\Controller {

    public function actionIndex() {

        $model = new DynamicModel([
            'TipeNomorAnggota',
            'TipePenomoranAnggota',
            'MasaBerlakuAnggota',
            'IsCetakSlipPerpanjangan',
            'IsCetakSlipPelanggaran',
            'IsCetakSlipPendaftaran',
        ]);
        $model->addRule([
            'TipeNomorAnggota',
            'TipePenomoranAnggota',
            // 'MasaBerlakuAnggota',
            'IsCetakSlipPerpanjangan',
            'IsCetakSlipPelanggaran',
            'IsCetakSlipPendaftaran'
                ,], 'required');

        $model->TipeNomorAnggota = Yii::$app->config->get('TipeNomorAnggota');
        $model->TipePenomoranAnggota = Yii::$app->config->get('TipePenomoranAnggota');

        $model->MasaBerlakuAnggota = Yii::$app->config->get('MasaBerlakuAnggota');
        $model->IsCetakSlipPerpanjangan = Yii::$app->config->get('IsCetakSlipPerpanjangan');
        $model->IsCetakSlipPelanggaran = Yii::$app->config->get('IsCetakSlipPelanggaran');
        $model->IsCetakSlipPendaftaran = Yii::$app->config->get('IsCetakSlipPendaftaran');



        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {

                Yii::$app->config->set('TipeNomorAnggota', Yii::$app->request->post('DynamicModel')['TipeNomorAnggota']);
                Yii::$app->config->set('TipePenomoranAnggota', Yii::$app->request->post('DynamicModel')['TipePenomoranAnggota']);

                Yii::$app->config->set('MasaBerlakuAnggota', Yii::$app->request->post('DynamicModel')['MasaBerlakuAnggota']);
                                Yii::$app->config->set('IsCetakSlipPerpanjangan', Yii::$app->request->post('DynamicModel')['IsCetakSlipPerpanjangan']);
                Yii::$app->config->set('IsCetakSlipPelanggaran', Yii::$app->request->post('DynamicModel')['IsCetakSlipPelanggaran']);

                Yii::$app->config->set('IsCetakSlipPendaftaran', Yii::$app->request->post('DynamicModel')['IsCetakSlipPendaftaran']);


                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app', 'Success Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            } else {

                Yii::$app->getSession()->setFlash('failed', [
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
