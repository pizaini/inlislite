<?php

namespace backend\modules\setting\loker\controllers;

use Yii;
use common\models\MasterLoker;
use common\models\MasterLokerSearch;
use common\models\MasterUangJaminan;
use common\models\MasterUangJaminanSearch;


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\Locations;
use common\models\LocationLibrary;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
/**
*
*/
class MasterLokerController extends Controller
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

	// public function actionIndex()
	// {
	// 	return $this->render('index');
	// }




///////////////////Master Locker Area

	/**xattr_get(filename, name)
    * Lists all MasterLoker models.
    * @return mixed
    */
    public function actionIndex()
    {
        $searchModel = new MasterLokerSearch;
        // $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());


        $queryParams = Yii::$app->request->getQueryParams();
        $queryParams['MasterLokerSearch']['libraryID'] = $_SESSION['location'];
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('setting-locker', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    /**
     * Displays a single MasterLoker model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewlocker($id)
    {
        $model = $this->findModellocker($id);

        return $this->render('view-locker', ['model' => $model]);
    }

    /**
     * Creates a new MasterLoker model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreatelocker()
    {
        $model = new MasterLoker;

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->locations_id) {
                $this->getView()->registerJs('
                    swal("Lokasi Ruang loker belum terisi.");
                ');
            }

            if ($model->save()) {
    			Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Success Save'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
                return $this->redirect(['index']);
            }
			//return $this->redirect(['viewlocker', 'id' => $model->ID]);
        }
        return $this->render('create-locker', [
                'model' => $model,
            ]);
        
    }

    /**
     * Updates an existing MasterLoker model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdatelocker($id)
    {
        $model = $this->findModellocker($id);

        if ($model->load(Yii::$app->request->post()) ) {
            if (!$model->locations_id) 
            {
                $this->getView()->registerJs('
                    swal("Lokasi Ruang loker belum terisi.");
                ');
            }
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Success Save'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
                return $this->redirect(['index']);
            }
			//return $this->redirect(['viewlocker', 'id' => $model->ID]);
        } 
        return $this->render('update-locker', [
                'model' => $model,
            ]);
        
    }

    /**
     * Deletes an existing MasterLoker model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeletelocker($id)
    {
        $this->findModellocker($id)->delete();
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
     * Finds the MasterLoker model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MasterLoker the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModellocker($id)
    {
        if (($model = MasterLoker::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }




    ///////////////////Master Uang Jaminan Area

    /**
     * Lists all MasterUangJaminan models.
     * @return mixed
     */
    public function actionUangJaminan()
    {
        $searchModel = new MasterUangJaminanSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    /**
     * [actionLoadSelecterLocations description]
     * @param  [type] $idLoc [description]
     * @return [type]        [description]
     */
    public function actionLoadSelecterLocations($idLoc)
    {
        $model = new MasterLoker;


        echo '        
        <div class="col-sm-12">
            <div class="form-group field-masterloker-locations_id required">
                <label class="control-label col-md-2" for="masterloker-locations_id">'. Yii::t('app','Lokasi Ruangan').'</label>
                <div class="col-md-10">
                    '. Html::activeDropDownList($model, 'locations_id',
                        ArrayHelper::map(Locations::find()->where('LocationLibrary_id = '.$idLoc)->select(['Name', 'ID'])->orderBy('ID')->all(), 'ID', 'Name'),
                        ['prompt' => "-- Silahkan pilih lokasi --", 'class'=>'form-control']).'
                </div>
                <div class="col-md-offset-2 col-md-10"></div>
                <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
            </div>
        </div>';

        // echo Html::activeDropDownList($model, 'ID',
        //     ArrayHelper::map(Locations::find()->where('LocationLibrary_id = '.$idLoc)->select(['Name', 'ID'])->orderBy('ID')->all(), 'ID', 'Name'),
        //     ['prompt' => "-- Silahkan pilih lokasi --", 'class'=>'form-control']) ;
    }




}
