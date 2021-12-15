<?php

namespace backend\modules\setting\opac\controllers;

use Yii;
use common\models\Settingparameters;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\DynamicModel;


class FacedSettingController extends Controller
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


    public function actionIndex()
    {
        $model = new DynamicModel([
            'Value1',
            'Value2',
            'Value3',
            'Value4',
            'Value5',
            'Value6',
            'Value7',
            'Value8',
            'Value9',
            'Value10',
            'Value11',
            'Value12',
        ]);
        $model->addRule([
            'Value1',
            'Value2',
            'Value3',
            'Value4',
            'Value5',
            'Value6',
            'Value7',
            'Value8',
            'Value9',
            'Value10',
            'Value11',
            'Value12',
        ], 'required');

        $model->Value1=Yii::$app->config->get('FacedAuthorMax');
        $model->Value2=Yii::$app->config->get('FacedAuthorMin');
        $model->Value3=Yii::$app->config->get('FacedPublisherMax');
        $model->Value4=Yii::$app->config->get('FacedPublisherMin');
        $model->Value5=Yii::$app->config->get('FacedPublishLocationMax');
        $model->Value6=Yii::$app->config->get('FacedPublishLocationMin');
        $model->Value7=Yii::$app->config->get('FacedPublishYearMax');
        $model->Value8=Yii::$app->config->get('FacedPublishYearMin');
        $model->Value9=Yii::$app->config->get('FacedSubjectMax');
        $model->Value10=Yii::$app->config->get('FacedSubjectMin');
        $model->Value11=Yii::$app->config->get('FacedBahasaMax');
        $model->Value12=Yii::$app->config->get('FacedBahasaMin');



        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) 
            {
            
                Yii::$app->config->set('FacedAuthorMax', Yii::$app->request->post('DynamicModel')['Value1']);
                Yii::$app->config->set('FacedAuthorMin', Yii::$app->request->post('DynamicModel')['Value2']);
		        Yii::$app->config->set('FacedPublisherMax', Yii::$app->request->post('DynamicModel')['Value3']);
		        Yii::$app->config->set('FacedPublisherMin', Yii::$app->request->post('DynamicModel')['Value4']);
		        Yii::$app->config->set('FacedPublishLocationMax', Yii::$app->request->post('DynamicModel')['Value5']);
		        Yii::$app->config->set('FacedPublishLocationMin', Yii::$app->request->post('DynamicModel')['Value6']);
		        Yii::$app->config->set('FacedPublishYearMax', Yii::$app->request->post('DynamicModel')['Value7']);
		        Yii::$app->config->set('FacedPublishYearMin', Yii::$app->request->post('DynamicModel')['Value8']);
		        Yii::$app->config->set('FacedSubjectMax', Yii::$app->request->post('DynamicModel')['Value9']);
		        Yii::$app->config->set('FacedSubjectMin', Yii::$app->request->post('DynamicModel')['Value10']);
                Yii::$app->config->set('FacedBahasaMax', Yii::$app->request->post('DynamicModel')['Value11']);
                Yii::$app->config->set('FacedBahasaMin', Yii::$app->request->post('DynamicModel')['Value12']);
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
