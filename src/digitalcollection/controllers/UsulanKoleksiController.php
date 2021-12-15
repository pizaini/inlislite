<?php

namespace digitalcollection\controllers;

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
            $model = new RequestcatalogOpac;
        
            if ($model->load(Yii::$app->request->post())) {
                $model->MemberID = 1;
                //$model->CreateBy = 'null';
                //$model->UpdateBy = 'null';
                $model->DateRequest = date('Y-m-d');
                
                $model->Status = 'Usulan';
                if($model->save()){
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Success Save'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
                    //echo"<berhasil>";
                    //die;
                    return $this->redirect(['/']);
                  
                }else{
                    return $this->render('create', [
                    'model' => $model,
                ]);

                    
                }
                
               
            } else /*if (Yii::$app->request->isAjax)*/ {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

        
        
    }

    
}
