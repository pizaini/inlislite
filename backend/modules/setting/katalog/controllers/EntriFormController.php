<?php

namespace backend\modules\setting\katalog\controllers;

use Yii;
use yii\base\DynamicModel;

class EntriFormController extends \yii\web\Controller {

    public function actionIndex() {

        $model = new DynamicModel([
            'FormEntriKatalog',
            
        ]);
        $model->addRule([
            'FormEntriKatalog',
            ], 'required');

        $model->FormEntriKatalog = Yii::$app->config->get('FormEntriKatalog');


        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {

                Yii::$app->config->set('FormEntriKatalog', Yii::$app->request->post('DynamicModel')['FormEntriKatalog']);

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
