<?php

namespace backend\modules\setting\sirkulasi\controllers;

use Yii;
use yii\base\DynamicModel;

class SettingTransaksiController extends \yii\web\Controller {

    public function actionIndex() {

        $model = new DynamicModel([
            'IsSaturdayHoliday',
            'IsSundayHoliday',
            'IsCetakSlipPeminjaman',
            'IsCetakSlipPengembalian',
            'PeminjamanLewatJatuhTempo',
            'PerpanjanganKoleksiMandiri',
            'OtomatisInsertBukuTamu',
            ]);
        $model->addRule([
            'IsSaturdayHoliday',
            'IsSundayHoliday',
            'IsCetakSlipPeminjaman',
            'IsCetakSlipPengembalian',
            'PeminjamanLewatJatuhTempo',
            'PerpanjanganKoleksiMandiri',
            'OtomatisInsertBukuTamu',], 'required');
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

        $model->IsCetakSlipPeminjaman = Yii::$app->config->get('IsCetakSlipPeminjaman');
        $model->IsCetakSlipPengembalian = Yii::$app->config->get('IsCetakSlipPengembalian');
        $model->PeminjamanLewatJatuhTempo = Yii::$app->config->get('PeminjamanLewatJatuhTempo');
        $model->PerpanjanganKoleksiMandiri = Yii::$app->config->get('PerpanjanganKoleksiMandiri');
        $model->OtomatisInsertBukuTamu = Yii::$app->config->get('OtomatisInsertBukuTamu');




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

                Yii::$app->config->set('IsCetakSlipPeminjaman', Yii::$app->request->post('DynamicModel')['IsCetakSlipPeminjaman']);
                Yii::$app->config->set('IsCetakSlipPengembalian', Yii::$app->request->post('DynamicModel')['IsCetakSlipPengembalian']);
                Yii::$app->config->set('PeminjamanLewatJatuhTempo', Yii::$app->request->post('DynamicModel')['PeminjamanLewatJatuhTempo']);
                Yii::$app->config->set('PerpanjanganKoleksiMandiri', Yii::$app->request->post('DynamicModel')['PerpanjanganKoleksiMandiri']);
                Yii::$app->config->set('OtomatisInsertBukuTamu', Yii::$app->request->post('DynamicModel')['OtomatisInsertBukuTamu']);


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
