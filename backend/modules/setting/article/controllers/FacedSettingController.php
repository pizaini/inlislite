<?php

namespace backend\modules\setting\article\controllers;

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

        $model->Value1=Yii::$app->config->get('FacedAuthorMaxArticle');
        $model->Value2=Yii::$app->config->get('FacedAuthorMinArticle');
        $model->Value3=Yii::$app->config->get('FacedPublisherMaxArticle');
        $model->Value4=Yii::$app->config->get('FacedPublisherMinArticle');
        $model->Value5=Yii::$app->config->get('FacedPublishLocationMaxArticle');
        $model->Value6=Yii::$app->config->get('FacedPublishLocationMinArticle');
        $model->Value7=Yii::$app->config->get('FacedPublishYearMaxArticle');
        $model->Value8=Yii::$app->config->get('FacedPublishYearMinArticle');
        $model->Value9=Yii::$app->config->get('FacedSubjectMaxArticle');
        $model->Value10=Yii::$app->config->get('FacedSubjectMinArticle');
        $model->Value11=Yii::$app->config->get('FacedBahasaMaxArticle');
        $model->Value12=Yii::$app->config->get('FacedBahasaMinArticle');



        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) 
            {
            
                Yii::$app->config->set('FacedAuthorMaxArticle', Yii::$app->request->post('DynamicModel')['Value1']);
                Yii::$app->config->set('FacedAuthorMinArticle', Yii::$app->request->post('DynamicModel')['Value2']);
		        Yii::$app->config->set('FacedPublisherMaxArticle', Yii::$app->request->post('DynamicModel')['Value3']);
		        Yii::$app->config->set('FacedPublisherMinArticle', Yii::$app->request->post('DynamicModel')['Value4']);
		        Yii::$app->config->set('FacedPublishLocationMaxArticle', Yii::$app->request->post('DynamicModel')['Value5']);
		        Yii::$app->config->set('FacedPublishLocationMinArticle', Yii::$app->request->post('DynamicModel')['Value6']);
		        Yii::$app->config->set('FacedPublishYearMaxArticle', Yii::$app->request->post('DynamicModel')['Value7']);
		        Yii::$app->config->set('FacedPublishYearMinArticle', Yii::$app->request->post('DynamicModel')['Value8']);
		        Yii::$app->config->set('FacedSubjectMaxArticle', Yii::$app->request->post('DynamicModel')['Value9']);
		        Yii::$app->config->set('FacedSubjectMinArticle', Yii::$app->request->post('DynamicModel')['Value10']);
                Yii::$app->config->set('FacedBahasaMaxArticle', Yii::$app->request->post('DynamicModel')['Value11']);
                Yii::$app->config->set('FacedBahasaMinArticle', Yii::$app->request->post('DynamicModel')['Value12']);
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
