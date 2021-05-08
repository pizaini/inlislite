<?php
/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2016
 * @version 1.0.0
 * @author Andy Kurniawan <dodot.kurniawan@gmail.com>
 */

namespace backend\modules\setting\akuisisi\controllers;

use Yii;
use common\models\Settingparameters;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\DynamicModel;

/**
 * SumberKoleksiController implements the CRUD actions for Collectionsources model.
 */
class NomorIndukController extends Controller
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
            'NomorInduk',
            //'NomorIndukTengah',
            'FormatNomorBarcode',
            'FormatNomorIndukx',
            'FormatNomorInduk',
            'FormatNomorRFID'
        ]);
        $model->addRule([
            'NomorInduk',
            //'NomorIndukTengah',
            'FormatNomorBarcode',
            'FormatNomorIndukx',
            'FormatNomorInduk',
            'FormatNomorRFID'], 'required');

        $model->NomorInduk=Yii::$app->config->get('NomorInduk');
        //$model->NomorIndukTengah=Yii::$app->config->get('NomorIndukTengah');
        $model->FormatNomorBarcode=Yii::$app->config->get('FormatNomorBarcode');
        $model->FormatNomorIndukx=Yii::$app->config->get('FormatNomorIndukx');
        $model->FormatNomorInduk=Yii::$app->config->get('FormatNomorInduk');
        $model->FormatNomorRFID=Yii::$app->config->get('FormatNomorRFID');

        // print_r($model->FormatNomorIndukx[4]);echo'--'; 
        // print_r($model->FormatNomorIndukx[12]); die;
        // if($model->FormatNomorIndukx[4]  == 1 && $model->FormatNomorIndukx[12] == 1){
        /*if($model->FormatNomorIndukx[4] && $model->FormatNomorIndukx[12] == 11){
            echo '<script>alert("Gagal");location.reload();</script>'; 

        }
        else*/ 
        if ($model->load(Yii::$app->request->post())) {
        if ($model->validate()) 
            {
                $post = Yii::$app->request->post();
                if(is_array($post['cbTemplate'])){$varcbTemplate = array_count_values($post['cbTemplate']);}

                if (strtolower($post['DynamicModel']['NomorInduk'])=='otomatis' && ((!array_key_exists("6",$varcbTemplate) OR $varcbTemplate[6] > 1) OR (!array_key_exists("7",$varcbTemplate) OR $varcbTemplate[7] > 1))) {
                    Yii::$app->getSession()->setFlash('success', [
                    'type' => 'danger',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Failed Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            return $this->redirect(['index']);
                }else{
                Yii::$app->config->set('NomorInduk', $post['DynamicModel']['NomorInduk']);
                //Yii::$app->config->set('NomorIndukTengah', Yii::$app->request->post('DynamicModel')['NomorIndukTengah']);
                Yii::$app->config->set('FormatNomorBarcode', $post['DynamicModel']['FormatNomorBarcode']);
                if(strtolower($post['DynamicModel']['NomorInduk'])=='otomatis')
                {
                    foreach ($post['cbTemplate'] as $key => $dataFormat) {
                       
                            if($key==1&&  ($key == 0 ||$key == 2 || $key == 4|| $key == 6 || $key == 8))
                            {
                                $post['cbTemplate'][$key] =  '{'.$post['cbTemplateInput'][$key].'}';
                            }
                            if($key==5&&  ($key == 0 ||$key == 2 || $key == 4|| $key == 6 || $key == 8))
                            {
                                $post['cbTemplate'][$key] =  '{'.$post['cbTemplateInput']['5'].'}';
                            }
                        
                    }
                    Yii::$app->config->set('FormatNomorIndukx', implode('|',$post['cbTemplate']));

                    foreach ($post['cbTemplate'] as $key => $dataFormat) {
                       
                            if($dataFormat==1&&  ($key == 0 ||$key == 2 || $key == 4|| $key == 6 || $key == 8))
                            {
                                $post['cbTemplate'][$key] =  '{'.$post['cbTemplateInput'][$key].'}';
                            }
                            if($dataFormat==5&&  ($key == 0))
                            {
                                $post['cbTemplate'][$key] =  '^'.$post['cbTemplateInput']['50'].'^';
                            }
                            if($dataFormat==5&&  ($key == 2))
                            {
                                $post['cbTemplate'][$key] =  '^'.$post['cbTemplateInput']['52'].'^';
                            }
                            if($dataFormat==5&&  ($key == 4))
                            {
                                $post['cbTemplate'][$key] =  '^'.$post['cbTemplateInput']['54'].'^';
                            }
                            if($dataFormat==5&&  ($key == 6))
                            {
                                $post['cbTemplate'][$key] =  '^'.$post['cbTemplateInput']['56'].'^';
                            }
                            if($dataFormat==5&&  ($key == 8))
                            {
                                $post['cbTemplate'][$key] =  '^'.$post['cbTemplateInput']['58'].'^';
                            }
                        
                    }
                    Yii::$app->config->set('FormatNomorInduk', implode('|',$post['cbTemplate']));
                }
                Yii::$app->config->set('FormatNomorRFID', $post['DynamicModel']['FormatNomorRFID']);
                 Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            return $this->redirect(['index']);
            }}
         }else{
                return $this->render('index', [
                'model' => $model,
            ]);
         }
    }
}
 