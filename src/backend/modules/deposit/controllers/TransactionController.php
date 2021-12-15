<?php

namespace backend\modules\deposit\controllers;


use Yii;
use yii\helpers\Url;

//Widget
use yii\widgets\MaskedInput;
use kartik\widgets\Select2;
use kartik\mpdf\Pdf;
use kartik\date\DatePicker;

//Helpers
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

use common\models\Collections;
use common\models\Catalogs;
use common\models\CatalogSearch;
use common\models\CollectionSearch;


class TransactionController extends \yii\web\Controller
{
    /**
     * [actionIndex description]
     * @return [type] [description]
     */
public function actionIndex()
    {
        $model = new Collections;
        $modelcat = new Catalogs;

        // echo Yii::$app->urlManager->createUrl("/modules/deposit/controllers/Transaction/show");die;
        // $this->redirect('/pengkatalogan/katalog/create-deposit',[
        // 'for'=>'coll'
        // ]);
        return $this->redirect(array('/pengkatalogan/katalog/create-deposit', 'for' => 'coll', 'rda' => '0', 'dep' => '1'));
        // $this->redirect(['show']);

       
    }


    public function actionListDeposit(){
        $perpage = 20;
        $getPerPage = $_GET['per-page'];
        if(!empty($getPerPage)){
            $perpage = (int)$_GET['per-page'];
        }

        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new CollectionSearch;
        $dataProvider = $searchModel->advancedSearchDeposit($rules);
        $dataProvider->pagination->pageSize=$perpage;
        /*$searchModel = new CollectionSearch;
        $dataProvider = $searchModel->search(0,Yii::$app->request->getQueryParams());*/

        return $this->render('_listDeposit', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'for'=>'koleksi',
            'rules'=>$rules
            ]);
    }
    

/**
     * [fungsi saat pilih/ubah judul]
     * @return mixed
     */
public function actionPilihJudul()
    {
        // echo '<pre>'; print_r(Yii::$app->request->get());die;
        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new CatalogSearch;
        $dataProvider = $searchModel->advancedSearch(0,$rules);
        return $this->renderAjax('_pilihJudul', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'for'=>Yii::$app->request->get('for'),
            'rules'=>$rules
            ]);
    }

}
