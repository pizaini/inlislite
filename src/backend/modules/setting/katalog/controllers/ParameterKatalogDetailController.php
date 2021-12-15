<?php

namespace backend\modules\setting\katalog\controllers;

use Yii;
use common\models\Fields;
use common\models\Settingcatalogdetail;
use common\models\SettingcatalogdetailSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * LembarKerjaAkusisiController implements the CRUD actions for Collectionsources model.
 */
class ParameterKatalogDetailController extends Controller
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
     * Form setting Worksheetfields models for akuisisi.
     * @return mixed
     */
    public function actionIndex()
    {

        $model = new Settingcatalogdetail;

        if ($model->load(Yii::$app->request->post())) {
            $input =   Yii::$app->request->post('Settingcatalogdetail')['TagInp'];
            $fieldid = $this->ActionGetFieldIdFromTag($input);
            // $fieldid= $fieldid->ID;
            if(!empty($fieldid))
            {
                $model->Field_id=$fieldid;
                if($model->save(false)){
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Success Save'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);

                }
            }else{

                Yii::$app->getSession()->setFlash('success', [
                        'type' => 'error',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Tag Not Found'),
                        'title' => 'Warning',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
            }
        } 

        $searchModel = new SettingcatalogdetailSearch;
        $queryParams = Yii::$app->request->getQueryParams();
        $dataProvider = $searchModel->search($queryParams);
        $dataProvider->pagination=false;
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model'=>$model,
        ]);
        
    }


     /**
     * Function untuk mencari m_field_id berdasarkan tag
     * dan id member field.
     *
     * @param  [string] $tag [Tag]
     * @return [int]         [Field_id]
     */
    public function ActionGetFieldIdFromTag($tag)
    {
        $var = Fields::find()->select('ID')->where(['Tag' => $tag])->one();
        return $var['ID'];

    }

    /**
     * Deletes an existing Cardformats model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Delete'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the Cardformats model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cardformats the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Settingcatalogdetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
