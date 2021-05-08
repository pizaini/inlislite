<?php

namespace article\controllers;

use Yii;
use common\models\RequestcatalogOpac;
use common\models\RequestcatalogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UsulanKoleksiController implements the CRUD actions for Requestcatalog model.
 */
class UsulanKoleksiController extends Controller
{

    /**
     * Lists all Requestcatalog models.
     * @return mixed
     */
    public function actionIndex()
    {   


        $model = new \common\models\RequestcatalogOpac;
       
        if ($model->load(Yii::$app->request->post())) {
            $modelmember = \common\models\Members::find()->where(['Memberno' => $model->noAnggota])->one();
            $model->MemberID = $modelmember->ID;
            $model->DateRequest = date('Y-m-d');
            
            $model->Status = 'Usulan';
            if($model->save()){
                \Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => \Yii::t('app','success Save'),
                        'title' => 'Info',
                        'positonY' => \Yii::$app->params['flashMessagePositionY'],
                        'positonX' => \Yii::$app->params['flashMessagePositionX']
                    ]);
                return $this->redirect(['/']);
                
            }else{
                \Yii::$app->getSession()->setFlash('error', [
                    'type' => 'danger',
                    'duration' => 2500,
                    'icon' => 'fa fa-info-circle',
                    'message' => \Yii::t('app','Gagal menyimpan usulan koleksi'),
                    'title' => 'Info',
                    'positonY' => \Yii::$app->params['flashMessagePositionY'],
                    'positonX' => \Yii::$app->params['flashMessagePositionX']
                ]);
                return $this->render('create', [
                    'model' => $model,
                ]);
                
            }

           
        } else {

            return $this->render('create', [
                'model' => $model,
            ]);
        }

        
        
    }

    
}
