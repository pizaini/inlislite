<?php

namespace backend\modules\setting\umum\controllers;

class SmsGatewayController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
