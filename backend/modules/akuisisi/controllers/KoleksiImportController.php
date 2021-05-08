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
class KoleksiImportController extends Controller
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
        $model = new \backend\models\ImportAkuisisiForm();
        return $this->render('index', ['model' => $model]);

    }

    /**
     * Lists all QuarantinedCollections models.
     * @return mixed
     */
    public function actionProses()
    {
        unset(\Yii::$app->session['SessErrorImportCollections']);
        unset(\Yii::$app->session['SessSuccessImportCollections']);
        if (Yii::$app->request->isPost) {
            $model = new \backend\models\ImportAkuisisiForm();
            $model->file = \yii\web\UploadedFile::getInstance($model, 'file');
            if($model->file)
            {
                if ($model->upload()) {

                   //$model->import();
                   $error;
                   $model->import_aacr($error);
                   if(count($error) == 0)
                   {
                        $model->deleteFile();
                        \Yii::$app->session['SessSuccessImportCollections'] = yii::t('app','Data Koleksi Berhasil Diimport');
                        return $this->redirect(['index']);
                   }else{
                       
                        $error =  implode("|",$error);
                        \Yii::$app->session['SessErrorImportCollections'] = $error;
                        return $this->redirect(['index']);
                   }
                }
            }
        }

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
