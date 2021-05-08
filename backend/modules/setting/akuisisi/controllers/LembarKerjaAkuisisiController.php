<?php

namespace backend\modules\setting\akuisisi\controllers;

use Yii;
use common\models\Worksheets;
use common\models\Worksheetfields;
use common\models\WorksheetfieldSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * LembarKerjaAkusisiController implements the CRUD actions for Collectionsources model.
 */
class LembarKerjaAkuisisiController extends Controller
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
        $model =  new Worksheets();
        $searchModel = new WorksheetfieldSearch;
        $queryParams = array_merge(array(),Yii::$app->request->getQueryParams());
        $queryParams["WorksheetfieldSearch"]["Worksheet_id"] =  (!empty(Yii::$app->request->post('Worksheets')['ID'])) ? Yii::$app->request->post('Worksheets')['ID'] : 1;
        $model->ID=Yii::$app->request->post('Worksheets')['ID'];
        $dataProvider = $searchModel->search($queryParams);
        $dataProvider->pagination=false;
        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Action update boolean value field IsAkuisisi in Worksheetfields.
     * @return NULL
     */
    public function actionIsAkuisisi($id,$checked)
    {
        $model = Worksheetfields::findOne($id);
        $stat;
        if($checked == 'true')
        {
            $stat=true;
        }else{

            $stat=false;
        }
        $model->IsAkuisisi=$stat;
        $model->save(false);
    }
}
