<?php
/**
 * User: henry <alvin_vna@yahoo.com>
 * Date: 2/18/16
 * Time: 1:38 AM
 */

namespace backend\modules\sirkulasi\controllers;

use common\models\base\Collections;
use Yii;
use yii\web\Session;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;;

class KoleksiDipesanController extends Controller
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
        // $searchModel = new \common\models\CollectionSearch;
        $searchModel = new \common\models\BookinglogsSearch;
        // $dataProvider = $searchModel->searchBooking(Yii::$app->request->getQueryParams());
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Update BookingMemberID field and BookingExpiredDate field Collection models.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id,$member)
    {
        $model = $this->findModel($id);
        $model->BookingMemberID = null;
        $model->BookingExpiredDate = null;

        if($model->save()){
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'info',
                'duration' => 500,
                'icon' => 'fa fa-info-circle',
                'message' => Yii::t('app','Success Delete'),
                'title' => 'Info',
                'positonY' => Yii::$app->params['flashMessagePositionY'],
                'positonX' => Yii::$app->params['flashMessagePositionX']
            ]);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Collections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Holidays the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Collections::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}