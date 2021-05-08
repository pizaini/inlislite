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

    /**
     * Form setting Settingparamaters models for akuisisi.
     * @return mixed
     */
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
            'Value12',], 'required');

        $model->Value1=Yii::$app->config->get('FacedAuthorMaxLKD');
        $model->Value2=Yii::$app->config->get('FacedAuthorMinLKD');
        $model->Value3=Yii::$app->config->get('FacedPublisherMaxLKD');
        $model->Value4=Yii::$app->config->get('FacedPublisherMinLKD');
        $model->Value5=Yii::$app->config->get('FacedPublishLocationMaxLKD');
        $model->Value6=Yii::$app->config->get('FacedPublishLocationMinLKD');
        $model->Value7=Yii::$app->config->get('FacedPublishYearMaxLKD');
        $model->Value8=Yii::$app->config->get('FacedPublishYearMinLKD');
        $model->Value9=Yii::$app->config->get('FacedSubjectMaxLKD');
        $model->Value10=Yii::$app->config->get('FacedSubjectMinLKD');
        $model->Value11=Yii::$app->config->get('FacedBahasaMaxLKD');
        $model->Value12=Yii::$app->config->get('FacedBahasaMinLKD');



        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) 
            {
            
                Yii::$app->config->set('FacedAuthorMaxLKD', Yii::$app->request->post('DynamicModel')['Value1']);
                Yii::$app->config->set('FacedAuthorMinLKD', Yii::$app->request->post('DynamicModel')['Value2']);
		        Yii::$app->config->set('FacedPublisherMaxLKD', Yii::$app->request->post('DynamicModel')['Value3']);
		        Yii::$app->config->set('FacedPublisherMinLKD', Yii::$app->request->post('DynamicModel')['Value4']);
		        Yii::$app->config->set('FacedPublishLocationMaxLKD', Yii::$app->request->post('DynamicModel')['Value5']);
		        Yii::$app->config->set('FacedPublishLocationMinLKD', Yii::$app->request->post('DynamicModel')['Value6']);
		        Yii::$app->config->set('FacedPublishYearMaxLKD', Yii::$app->request->post('DynamicModel')['Value7']);
		        Yii::$app->config->set('FacedPublishYearMinLKD', Yii::$app->request->post('DynamicModel')['Value8']);
		        Yii::$app->config->set('FacedSubjectMaxLKD', Yii::$app->request->post('DynamicModel')['Value9']);
		        Yii::$app->config->set('FacedSubjectMinLKD', Yii::$app->request->post('DynamicModel')['Value10']);
                Yii::$app->config->set('FacedBahasaMaxLKD', Yii::$app->request->post('DynamicModel')['Value11']);
                Yii::$app->config->set('FacedBahasaMinLKD', Yii::$app->request->post('DynamicModel')['Value12']);
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
