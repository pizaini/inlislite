<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace keanggotaan\controllers;

use common\models\Members;
use common\models\MembersInfoForm;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\helpers\Url;


/**
 * Description of UserController
 *
 * @author Henry <alvin_vna@yahoo.com>
 */
class UserController extends Controller{
    
    //put your code here
    
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'page' => [
                'class' => 'yii\web\ViewAction',
            ],
        ];
    }
    
   /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex(){
         if (!\Yii::$app->user->isGuest) {

             //Get Custom Field Form.
             $membersInfoForm = MembersInfoForm::find()
                 ->where(['Jenis_Perpustakaan_id' => Yii::$app->config->get('JenisPerpustakaan')])
                 ->asArray()->all();

      /*      echo "<pre>";
            print_r($membersInfoForm);
            die;
*/
            $model = $this->findModel(\Yii::$app->user->identity->NoAnggota);
            return $this->render('index',['model'=>$model,'membersInfoForm'=>$membersInfoForm]);
        }else{
            return $this->redirect(['site/login']);
        }
    }
    
    public function actionChangePassword()
    {
        $id = $_SESSION['__id'];

        $user = Yii::$app->user->identity;
        $loadedPost = $user->load(Yii::$app->request->post());

        if ($loadedPost && $user->validate()) {
            Yii::$app->db->createCommand('UPDATE membersonline SET membersonline.Password="'.sha1($user->newPassword).'", membersonline.UpdateDate=NOW() WHERE membersonline.ID="'.Yii::$app->user->identity->id.'"')->execute();
            // $user->Password = sha1($user->newPassword);
            // $user->save(false);

            Yii::$app->getSession()->setFlash('success', [
                'type' => 'info',
                'duration' => 500,
                'icon' => 'fa fa-info-circle',
                'message' => Yii::t('app', 'Password berhasil dirubah.'),
                'title' => 'Info',
                'positonY' => Yii::$app->params['flashMessagePositionY'],
                'positonX' => Yii::$app->params['flashMessagePositionX']
            ]);
            return $this->refresh();
        } 

        return $this->render('change-password', [
            'user' => $user,
            ]);

    }

    protected function findModel($nomorAnggota)
    {
        if (($model = Members::find()->where(['MemberNo'=>$nomorAnggota])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
}
