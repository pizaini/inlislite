<?php

namespace keanggotaan\controllers;

use Yii;
use common\models\Requestcatalog;
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
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Requestcatalog models.
     * @return mixed
     */
    public function actionIndex()
    {

        $NoAnggota = Yii::$app->user->identity->NoAnggota;
        $model = new \common\models\RequestcatalogOpac;
        $modelmember = \common\models\Members::find()->where(['Memberno' => $NoAnggota])->one();
        if ($model->load(Yii::$app->request->post())) {
            $model->MemberID = $modelmember->ID;
            $model->DateRequest = date('Y-m-d');
            
            $model->Status = 'Usulan';
            if($model->save()){
                Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','success Save'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
                return $this->redirect(['/']);
              
            }else{
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'danger',
                    'duration' => 2500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Gagal menyimpan usulan koleksi'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
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
