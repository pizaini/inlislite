<?php

namespace backend\modules\setting\loker\controllers;

use Yii;
use yii\base\DynamicModel;

class JaminanController extends \yii\web\Controller
{
    public function actionIndex()
    {

    	$model = new DynamicModel([
            'JaminanUangLoker',
            'JaminanIdentitasLoker',
            'CetakBuktiTransaksi',
            'CetakBuktiPelanggaran',
            'IsMemberAllowedToBorrowMultipleLocker',
          
        ]);
        $model->addRule([
            'JaminanUangLoker',
            'JaminanIdentitasLoker',
            'CetakBuktiTransaksi',
            'IsMemberAllowedToBorrowMultipleLocker',
            'CetakBuktiPelanggaran',], 'required');

        $model->JaminanUangLoker = Yii::$app->config->get('JaminanUangLoker');
        $model->JaminanIdentitasLoker = Yii::$app->config->get('JaminanIdentitasLoker');

        $model->CetakBuktiTransaksi = Yii::$app->config->get('CetakBuktiTransaksi');
        $model->CetakBuktiPelanggaran = Yii::$app->config->get('CetakBuktiPelanggaran');

        $model->IsMemberAllowedToBorrowMultipleLocker = Yii::$app->config->get('IsMemberAllowedToBorrowMultipleLocker');

        

        if ($model->load(Yii::$app->request->post())) 
        {
        	if ($model->validate()) 
        	{

        		Yii::$app->config->set('JaminanUangLoker', Yii::$app->request->post('DynamicModel')['JaminanUangLoker']);
        		Yii::$app->config->set('JaminanIdentitasLoker', Yii::$app->request->post('DynamicModel')['JaminanIdentitasLoker']);
        		
        		Yii::$app->config->set('CetakBuktiTransaksi', Yii::$app->request->post('DynamicModel')['CetakBuktiTransaksi']);
        		Yii::$app->config->set('CetakBuktiPelanggaran', Yii::$app->request->post('DynamicModel')['CetakBuktiPelanggaran']);
        		
        		Yii::$app->config->set('IsMemberAllowedToBorrowMultipleLocker', Yii::$app->request->post('DynamicModel')['IsMemberAllowedToBorrowMultipleLocker']);
        		
        		Yii::$app->getSession()->setFlash('success', [
        			'type' => 'info',
        			'duration' => 500,
        			'icon' => 'fa fa-info-circle',
        			'message' => Yii::t('app','Success Save'),
        			'title' => 'Info',
        			'positonY' => Yii::$app->params['flashMessagePositionY'],
        			'positonX' => Yii::$app->params['flashMessagePositionX']
        			]);
        	}
        	else
        	{

        		Yii::$app->getSession()->setFlash('failed', [
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
        }
        else
        {
        return $this->render('index',[
          'model' => $model,]);
        }
    }

}
