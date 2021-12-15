<?php

namespace backend\modules\pengkatalogan\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\CatalogHelpers;
use common\models\CatalogSearch;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\web\Session;
use yii\validators\Validator;
use yii\helpers\Json;

/**
 * KatalogController implements the CRUD actions for Collections model.
 */
class KatalogKontenDigitalController extends Controller
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
     * Lists all Collections models.
     * @return mixed
     */
    public function actionIndex()
    {
        $rules = Json::decode(Yii::$app->request->get('rules'));
        $searchModel = new CatalogSearch;
        $dataProvider = $searchModel->searchKatalogKontenDigital($rules);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'rules'=>$rules
            ]);
    }


    public function actionDelete($id)
    {
        CatalogHelpers::deleteKontenDigital($id);
        return $this->redirect('index');
        
    }

    /**
     * Process records which is checked
     * @return mixed
     */
    public function actionCheckboxProcess()
    {
        $post = Yii::$app->request->post(); $msg='';
        //echo '<pre>'; print_r($post); echo '</pre>';die;
        if(isset($post['action']) && isset($post['row_id']))
        {
            $actid;
            $rowid = $post['row_id'];

            switch ($post['action']) {
                case 'REMOVE':
                    foreach ($rowid as $key => $value) {
                        CatalogHelpers::deleteKontenDigital($value);
                    }
                    return true;
                    break;
            }
        }

    }

    /**
     * Lists all Collections models.
     * @return mixed
     */
    public function actionCreate()
    {
        /*$searchModel = new CatalogSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            ]);*/
        return $this->render('create');
    }
}

?>