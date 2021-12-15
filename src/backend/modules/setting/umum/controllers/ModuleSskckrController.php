<?php

namespace backend\modules\setting\umum\controllers;

use Yii;
use common\models\Settingparameters;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\DynamicModel;


class ModuleSskckrController extends Controller
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

        $model->Value1=Yii::$app->config->get('ModuleDeposit');
        //$model->Value2=Yii::$app->config->get('BackendIndexer');



        if ($model->load(Yii::$app->request->post())) {
            // print_r(Yii::$app->request->post());die;
            if ($model->validate())
            {

                Yii::$app->config->set('ModuleDeposit', Yii::$app->request->post('DynamicModel')['Value1']);
                if(Yii::$app->request->post('DynamicModel')['Value1'] == 0){
                    return $this->redirect(array('/setting/umum/setting-update/update'));
                }

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
