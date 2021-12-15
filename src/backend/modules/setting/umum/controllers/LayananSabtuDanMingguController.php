<?php

namespace backend\modules\setting\umum\controllers;

use Yii;
use yii\base\DynamicModel;

class LayananSabtuDanMingguController extends \yii\web\Controller {

    public function actionIndex() {
        // echo "string";die;
        $model = new DynamicModel([
            'IsSaturdayHoliday',
            'IsSundayHoliday',
            ]);
        $model->addRule([
            'IsSaturdayHoliday',
            'IsSundayHoliday',], 'required');
        $saturday= Yii::$app->config->get('IsSaturdayHoliday');
        $sunday= Yii::$app->config->get('IsSundayHoliday');
        if($saturday=="False"){
            $saturdayn=0;
        }
        else{
            $saturdayn=1;
        }
        if($sunday=="False"){
            $sundayn=0;
        }
        else{
            $sundayn=1;
        }
        $model->IsSaturdayHoliday = $saturdayn;
        $model->IsSundayHoliday = $sundayn;


        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $saturday= Yii::$app->request->post('DynamicModel')['IsSaturdayHoliday'];
                $sunday= Yii::$app->request->post('DynamicModel')['IsSundayHoliday'];
                if($saturday==0){
                    $saturdayn="False";
                }
                else{
                    $saturdayn="True";
                }
                if($sunday==0){
                    $sundayn="False";
                }
                else{
                    $sundayn="True";
                }

                Yii::$app->config->set('IsSaturdayHoliday', $saturdayn);
                Yii::$app->config->set('IsSundayHoliday', $sundayn);


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
            return $this->render('index', ['model' => $model,]);
        }
    }

}
