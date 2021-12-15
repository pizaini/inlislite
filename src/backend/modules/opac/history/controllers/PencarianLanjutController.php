<?php

namespace backend\modules\opac\history\controllers;

use Yii;
use common\models\Opaclogs;
use common\models\OpaclogsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PencarianLanjutController implements the CRUD actions for Opaclogs model.
 */
class PencarianLanjutController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Opaclogs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OpaclogsSearch;
        $params=Yii::$app->request->getQueryParams();
        $params['pencarian']='pencarianLanjut';
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Finds the Opaclogs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Opaclogs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Opaclogs::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
