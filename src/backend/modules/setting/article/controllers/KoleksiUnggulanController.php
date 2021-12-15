<?php

namespace backend\modules\setting\article\controllers;
use Yii;
use common\models\KriteriaKoleksi;
use common\models\KoleksiUnggulanSearch;
use common\models\Catalogs;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\CatalogSearch;
use yii\helpers\Json;
use yii\base\DynamicModel;
use common\models\Settingparameters;

class KoleksiUnggulanController extends Controller
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
     * Lists all KriteriaKoleksi models.
     * @return mixed
     */
    public function actionIndex()
    {

    	$searchModel = new KoleksiUnggulanSearch;

        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
       


    	$model = new DynamicModel([
            'Value1',
            'Value2',
          
        ]);
        $model->addRule([
            'Value1',
            'Value2'], 'required');

        $model->Value1=Yii::$app->config->get('ShowKoleksiUnggulan');
        $model->Value2=Yii::$app->config->get('KoleksiUnggulanShow');
        //$model->Value4=Yii::$app->config->get('FormEntriKoleksi');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) 
            {
            
                Yii::$app->config->set('ShowKoleksiUnggulan', Yii::$app->request->post('DynamicModel')['Value1']);
                Yii::$app->config->set('KoleksiUnggulanShow', Yii::$app->request->post('DynamicModel')['Value2']);
                 Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            }else{

                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Failed Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            }
            return $this->redirect(['index']);
         }else{
                return $this->render('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ]);
         }
        
    }
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->ID]);
        } else {
        return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new Collectioncategorys model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KriteriaKoleksi;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Collectioncategorys model.
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

    public function actionPilihJudul()
    {
        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new CatalogSearch;
        $dataProvider = $searchModel->advancedSearch(0,$rules);

        return $this->renderAjax('_pilihJudul', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'rules'=>$rules
            ]);
    }

        public function actionTambah($id)
    {


        $model = new KriteriaKoleksi;
        (int) $count = KriteriaKoleksi::find()
                    ->where(['catalog_id' => $id,'jns_kriteria' => 'koleksi_unggul'])
                    ->count();

        if($count==0) {
        $catalog = Catalogs::find()
        ->joinWith('worksheet')
        ->where(['catalogs.ID' => $id])
        ->all();
        //echo"<pre>"; print_r($catalog); echo"</pre>"; die;
        //echo"<pre>"; print_r($catalog[0]['Title']); echo"</pre>"; die;
        $model->jns_kriteria = 'koleksi_unggul';
        $model->catalog_id = $id;
        $model->title = $catalog[0]['Title'];
        $model->author = $catalog[0]['Author'];
        $model->alamat_image = $catalog[0]['CoverURL'];
        //$model->CreateBy = ;
        //$model->CreateDate = ;
        //$model->CreateTerminal = ;
        //$model->UpdateBy = ;
        //$model->UpdateDate = ;
        //$model->UpdateTerminal = ;
        $model->PublishYear = $catalog[0]['PublishYear'];
        $model->worksheet_name = $catalog[0]['worksheet']['Name'];
        $model->save();
        //echo"<pre>"; print_r($catalog); echo"</pre>"; die;

        Yii::$app->getSession()->setFlash('success', [
            'type' => 'info',
            'duration' => 500,
            'icon' => 'fa fa-info-circle',
            'message' => Yii::t('app','Berhasil Disimpan'),
            'title' => 'Info',
            'positonY' => Yii::$app->params['flashMessagePositionY'],
            'positonX' => Yii::$app->params['flashMessagePositionX']
        ]);
        return $this->redirect(['index']);


         } else{

        Yii::$app->getSession()->setFlash('success', [
            'type' => 'danger',
            'duration' => 500,
            'icon' => 'fa fa-info-circle',
            'message' => Yii::t('app','Gagal Disimpan, Katalog sudah ada dalam daftar Unggulan'),
            'title' => 'Info',
            'positonY' => Yii::$app->params['flashMessagePositionY'],
            'positonX' => Yii::$app->params['flashMessagePositionX']
        ]);
        return $this->redirect(['index']);



         }
        
       





    }
        protected function findModel($id)
    {
        if (($model = KriteriaKoleksi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
