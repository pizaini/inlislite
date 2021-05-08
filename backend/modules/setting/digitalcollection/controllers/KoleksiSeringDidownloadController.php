<?php

namespace backend\modules\setting\digitalcollection\controllers;

use Yii;
use common\models\Settingparameters;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\DynamicModel;

/**
 * SumberKoleksiController implements the CRUD actions for Collectionsources model.
 */
class KoleksiSeringDidownloadController extends Controller
{
    public function behaviors()
    {
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
     * Form setting Settingparamaters models for akuisisi.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new DynamicModel([
            'Value1',
            'Value2',
          
        ]);
        $model->addRule([
            'Value1',
            'Value2'], 'required');

        $model->Value1=Yii::$app->config->get('ShowKoleksiSeringDidownloadLKD');
        $model->Value2=Yii::$app->config->get('KoleksiSeringDidownloadShowLKD');
      

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) 
            {
            
                Yii::$app->config->set('ShowKoleksiSeringDidownloadLKD', Yii::$app->request->post('DynamicModel')['Value1']);
                Yii::$app->config->set('KoleksiSeringDidownloadShowLKD', Yii::$app->request->post('DynamicModel')['Value2']);
                 Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            }else{

                Yii::$app->getSession()->setFlash('success', [
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
         }else{
                return $this->render('index', [
                'model' => $model,
            ]);
         }

    }
}
