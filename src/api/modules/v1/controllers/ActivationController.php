<?php

namespace api\modules\v1\controllers;

use yii\rest\Controller;
use Yii;
use api\modules\v1\models\AppInstalled;

/**
 * Class ActivationController
 * @package rest\versions\v1\controllers
 */
class ActivationController extends Controller
{

    /**
     * This method implemented to demonstrate the receipt of the token.
     * @return string ActivationCode
     */
    public function actionIndex()
    {
        $activationCode = Yii::$app->security->generateRandomString() . '_' . time();

        $appInstalled = AppInstalled::find()->where(['ActivationCode'=>$activationCode])->one();
        if($appInstalled != ""){
            $activationCode = Yii::$app->security->generateRandomString() . '_' . time();
        }

        // Create Token
         return [
            'ActivationCode'=> $activationCode
         ];
        
    }
}