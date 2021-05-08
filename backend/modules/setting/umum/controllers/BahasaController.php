<?php
namespace backend\modules\setting\umum\controllers;

use Yii;
use yii\base\DynamicModel;
use common\models\Settingparameters;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use yii\httpclient\Client;

class BahasaController extends \yii\web\Controller {

    public function actionIndex() {
            $check = Settingparameters::find()->where( [ 'Name' => 'language' ] )->exists();

            $model = new DynamicModel([

                'language'

            ]);
            $model->addRule([

                'language'], 'required');

            $model->language=Yii::$app->config->get('language');

            // echo '<pre>';print_r($_POST);echo '</pre>';die;
            $post = Yii::$app->request->post('DynamicModel');
            // echo '<pre>';print_r(Yii::$app->config->get('language'));echo '</pre>';
            // echo '<pre>';print_r($model->language);echo '</pre>';
            // echo '<pre>';print_r($post['language']);echo '</pre>';

            // print_r($check);
            if (isset($post['language'])) {
                if (!empty($check)) {

                        Yii::$app->config->set('language', $post['language']);
                        $this->redirect('alert');

                        // print_r(Yii::$app->language);
                }else{
                    $command = Yii::$app->db->createCommand()->insert('settingparameters', [
                                'Name' => 'language',
                                'Value' => $post['language'],
                            ])->execute();
                        $this->redirect('alert');
                }
            }
            // $this->refresh();
                return $this->render('index',[
                            'model' => $model,]);
            

    }

    public function actionAlert() {
        $this->redirect('index');
    }

}
