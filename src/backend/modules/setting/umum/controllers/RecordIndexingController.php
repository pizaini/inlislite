<?php

namespace backend\modules\setting\umum\controllers;

use Yii;
use common\models\Settingparameters;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\DynamicModel;


class RecordIndexingController extends Controller
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
            //'Value2',
        ]);
        $model->addRule([
            'Value1',
            //'Value2',
        ], 'required');

        $model->Value1=Yii::$app->config->get('OpacIndexer');
        //$model->Value2=Yii::$app->config->get('BackendIndexer');



        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate())
            {

                Yii::$app->config->set('OpacIndexer', Yii::$app->request->post('DynamicModel')['Value1']);
                //Yii::$app->config->set('BackendIndexer', Yii::$app->request->post('DynamicModel')['Value2']);
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
