<?php

namespace backend\modules\akuisisi\controllers;

use Yii;
use common\models\Requestcatalog;
use common\models\RequestcatalogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * KoleksiKarantinaController implements the CRUD actions for QuarantinedCollections model.
 */
class KoleksiUsulanController extends Controller
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
     * Lists all QuarantinedCollections models.
     * @return mixed
     */
    public function actionIndex()
    {
        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new RequestcatalogSearch;
        $dataProvider = $searchModel->advancedSearch($rules);

       /* $searchModel = new RequestcatalogSearch;
        $dataProvider = $searchModel->searchAkuisisi(Yii::$app->request->getQueryParams());*/
        if(Yii::$app->request->post('hasEditable'))
        {
            $message= '';
            $id = Yii::$app->request->post('editableKey');
            $index = Yii::$app->request->post('editableIndex');
            $model = $this->findModel($id);
            $post= [];
            $status = Yii::$app->request->post('Requestcatalog')[$index]['Status'];
            $post['Requestcatalog']['Status'] =  $status;
            if($model->load($post))
            {
                if($model->save())
                {
                    //$message = Yii::t('app','Success Save');
                }else{
                    //$message = /*print_r($model->getError())*/ 'Error save';
                }
            }else{
                //$message = 'Failed load model';
            }
            $out= Json::encode(['output'=>'','message'=>$message]);
            echo $out;
            return;
        }
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'rules'=>$rules
        ]);
    }

    /**
     * Displays a single QuarantinedCollections model.
     * @param double $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', ['model' => $model]);
        
    }


    /**
     * Finds the QuarantinedCollections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param double $id
     * @return QuarantinedCollections the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Requestcatalog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
