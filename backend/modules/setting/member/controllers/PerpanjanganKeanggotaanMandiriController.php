<?php

namespace backend\modules\setting\member\controllers;

use Yii;
use yii\base\DynamicModel;

class PerpanjanganKeanggotaanMandiriController extends \yii\web\Controller {

    public function actionIndex() {

        $model = new DynamicModel([
            'PerpanjanganKenggotaanMandiri',
            ]);
        $model->addRule([
            'PerpanjanganKenggotaanMandiri',], 'required');
        
        $model->PerpanjanganKenggotaanMandiri = Yii::$app->config->get('PerpanjanganKenggotaanMandiri');




        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                
                Yii::$app->config->set('PerpanjanganKenggotaanMandiri', Yii::$app->request->post('DynamicModel')['PerpanjanganKenggotaanMandiri']);


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
