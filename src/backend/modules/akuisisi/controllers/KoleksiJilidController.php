<?php

namespace backend\modules\akuisisi\controllers;

use Yii;
use common\models\Collections;
use common\models\CollectionSearchJilid;
use common\models\Catalogs;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\web\Session;
use yii\helpers\Json;

/**
 * KoleksiKarantinaController implements the CRUD actions for Collections model.
 */
class KoleksiJilidController extends Controller
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

    public function goBackUrl()
    {
        if(Yii::$app->request->referrer){
            return $this->redirect(Yii::$app->request->referrer);
        }else{
            return $this->goHome();
        }
    }

    function removeElementWithValue($array, $key, $value){
         foreach($array as $subKey => $subArray){
              if($subArray[$key] == $value){
                   unset($array[$subKey]);
              }
         }
         return $array;
    }

    function isExistArrayKeyValue($array, $key, $val) {
        foreach ($array as $item)
            if (isset($item[$key]) && $item[$key] == $val)
                return true;
        return false;
    }

    /**
     * Fill Collections models.
     * @return mixed
     */
    public function actionDeleteSerialCollection($id)
    {
        $data = $this->removeElementWithValue(\Yii::$app->session['SessJilidKoleksiList'], "ID", $id);
        \Yii::$app->session['SessJilidKoleksiList'] = $data;
        return 'success';
    }

    /**
     * Fill Collections models.
     * @return mixed
     */
    public function actionRemoveJilid($id)
    {
        $model = $this->findModel($id);
        $check_jilid = Collections::find()
                ->select(['COUNT(ID) AS jumlah_jilid'])
                ->where(['IDJILID'=>$model->IDJILID])->asArray()
                ->One();
        // echo'<pre>';print_r($check_jilid);echo'</pre>';
        //   echo'<pre>';print_r($id);echo'</pre>';die;
        
        if($check_jilid['jumlah_jilid'] == 1){
          $this->actionRemoveJilidAll($model->IDJILID, $model->Catalog_id);
        }else{
          // echo 'ini kalo masih banyak';die;
          $model->IDJILID = null;
          $model->NOMORPANGGILJILID = null;
          $model->TGLENTRYJILID= null;
          $model->JILIDCREATEBY=null;

          $nobarcode = $model->NomorBarcode;
          if($model->save(false))
          {
            $this->goBackUrl();
          }
        }
    }

    /**
     * Fill Collections models.
     * @return mixed
     */
    public function actionRemoveJilidAll($idjilid,$idcat)
    {
        /*$model = Collections::find()->where(['Catalog_id'=>$idcat,'IDJILID'=>$idjilid]);
        $model->IDJILID = null;
        $model->NOMORPANGGILJILID = null;
        $model->TGLENTRYJILID= null;
        $model->JILIDCREATEBY=null;
        $model->save(false);*/
        $command = Yii::$app->db->createCommand('UPDATE collections SET 
          IDJILID=NULL,
          NOMORPANGGILJILID=NULL,
          TGLENTRYJILID=NULL,
          JILIDCREATEBY=NULL
          WHERE Catalog_id=:Catalog_id AND IDJILID=:IDJILID');
        $command->bindParam(':Catalog_id', $idcat);
        $command->bindParam(':IDJILID', $idjilid);
        if($command->execute())
        {
          //save history
          //beloman
          $this->actionFlashMessage('info','Data Jilid berhasil dihapus');
          return $this->redirect(['index']); 
        }
    }

    /**
     * Fill Collections models.
     * @return mixed
     */
    public function actionFillSerialCollection($id,$idjilid,$mode)
    {
        $model = new Collections;
        $modeldata = $model->getCollectionById($id);
        $data = array(
            "ID"=>$modeldata->ID,
            "NomorBarcode"=>$modeldata->NomorBarcode,
            "NoInduk"=>$modeldata->NoInduk,
            "DataBib"=>$modeldata->DataBib,
            "EDISISERIAL"=>$modeldata->EDISISERIAL,
            "TANGGAL_TERBIT_EDISI_SERIAL"=>$modeldata->TANGGAL_TERBIT_EDISI_SERIAL);
        $message='';

        if($mode=='create')
        {
          if(empty(\Yii::$app->session['SessJilidKoleksiList']))
          {
              \Yii::$app->session['SessJilidKoleksiList'] = array();
          }
          \Yii::$app->session['SessJilidKoleksiList'] = array_merge(\Yii::$app->session['SessJilidKoleksiList'], [$data]);
        }
        else if($mode=='view')
        {
          $modeljilid = Collections::find()->where(['IDJILID'=>$idjilid])->one();
          $userId =  (int)Yii::$app->user->identity->ID;
          $dateNow = new \yii\db\Expression('NOW()');
          $modelview = $this->findModel($id);
          $modelview->IDJILID = $modeljilid->IDJILID;
          $modelview->NOMORPANGGILJILID = $modeljilid->NOMORPANGGILJILID;
          $modelview->TGLENTRYJILID= $dateNow;
          $modelview->JILIDCREATEBY=$userId;
          if($modelview->save(false))
          {
            //save history
            //beloman
          }

        }
        

        return $message;
        
    }

    /**
     * Fill Collections models.
     * @return mixed
     */
    public function actionSave()
    {
        $msg = '';
        //Inisialisasi data koleksi yang akan dijilid via session
        if(empty(\Yii::$app->session['SessJilidKoleksiList']))
        {
            $data = \Yii::$app->session['SessJilidKoleksiList'] = array();
        }else{
            $data = \Yii::$app->session['SessJilidKoleksiList'];
        }
        
        if (Yii::$app->request->isAjax) 
        {
            $post = Yii::$app->request->post();
            (int)$count = Collections::find()
                         ->where(['NomorPanggilJilid'=>$post['txtNoPanggilJilid']])
                         ->count();
            if($count > 0)
            {
              $msg .= 'No. Panggil Jilid : '.$post['txtNoPanggilJilid'].' sudah ada!';
            }else{
              if(count($data) == 0)
              {
                $msg .= 'Tidak ada koleksi yang akan dijilid. Tambah koleksi terlebih dahulu';
              }else{
                //select max id jilid
                $sql = 'SELECT MAX(SUBSTR(IDJILID,1,4)) AS IDJILID FROM collections  WHERE IDJILID IS NOT NULL AND IDJILID LIKE "%-'.$post['txtTahunJilid'].'"';
                $maxId = Collections::findBySql($sql)->one()->IDJILID;
                $newId = '1';
                if($maxId)
                {
                  $lastId = (int)$maxId+1;
                }else{
                  $lastId = (int)$newId;
                }
                //set number 000x
                $newIdJilid = str_pad($lastId, 4, '0', STR_PAD_LEFT).'-'.$post['txtTahunJilid'];
                $userId =  (int)Yii::$app->user->identity->ID;
                $dateNow = new \yii\db\Expression('NOW()');
                $trans = Yii::$app->db->beginTransaction();
                try {
                  //loop update data collection
                  foreach ($data as $key => $value) {
                    $idColl = $value['ID'];
                    $model = $this->findModel($idColl);
                    $model->IDJILID = $newIdJilid;
                    if($post['txtNoPanggilJilid'])
                    {
                      $model->NOMORPANGGILJILID = $post['txtNoPanggilJilid'];
                    }
                     if($post['cbLokasi'])
                    {
                      $model->Location_id = $post['cbLokasi'];
                    }
                    $model->TGLENTRYJILID= $dateNow;
                    $model->JILIDCREATEBY=$userId;
                    if($model->save(false))
                    {
                      //save history
                      //beloman
                    }
                  }
                  $trans->commit();
                } catch (Exception $e) {
                    $trans->rollback();
                }
                
              }
            }
        }

        if($msg != '')
        {
          return '<div class="callout callout-danger">
                  '.$msg.'
                  </div>';
        }else{
          $this->actionFlashMessage('info','Data berhasil disimpan');
          return $this->redirect(['index']); 
        }
        
    }

    /**
     * Fill Collections models.
     * @return mixed
     */
    public function actionUpdate($idjilid,$idcat)
    {
        $msg = '';
        if (Yii::$app->request->isAjax) 
        {
            $post = Yii::$app->request->post();
            (int)$count = Collections::find()
                         ->where(['and','NomorPanggilJilid = \''.$post['txtNoPanggilJilid'].'\'',['not in','IDJILID',$idjilid]])
                         ->count();
            if($count > 0)
            {
              $msg .= 'No. Panggil Jilid : '.$post['txtNoPanggilJilid'].' sudah ada!';
            }else{
              /*$model = Collections::find()
                       ->where(['Catalog_id'=>$idcat,'IDJILID'=>$idjilid]);
              $model->NOMORPANGGILJILID =  $post['txtNoPanggilJilid'];
              $model->save(false);*/
              $trans = Yii::$app->db->beginTransaction();
              try {
                $update = Collections::updateAll(['NOMORPANGGILJILID' => $post['txtNoPanggilJilid'],'Location_id' => $post['Collections']['Location_id']],'IDJILID = "'.$idjilid.'"');
                if($update)
                {
                  $trans->commit();
                }
              } catch (Exception $e) {
                  $trans->rollback();
              }
              
            }
        }

        if($msg != '')
        {
          return '<div class="callout callout-danger">
                  '.$msg.'
                  </div>';
        }else{
          return '<div class="callout callout-success">
                  No. Panggil berhasil disimpan
                  </div>';
        }
        
    }

    /**
     * Lists all Collections models.
     * @return mixed
     */
    public function actionShowSerialCollection()
    {
        $searchModel = new CollectionSearchJilid;
        $notInId= array();
        if(!empty(\Yii::$app->session['SessJilidKoleksiList']))
        {
            $data = \Yii::$app->session['SessJilidKoleksiList'];
            foreach ($data as $key => $value) {
                $notInId[] = $value['ID'];
            }
        }
        $dataProvider = $searchModel->serialCollectionList(Yii::$app->request->getQueryParams(),$notInId);
        // echo '<pre>'; print_r($dataProvider);echo '</pre>';
        return $this->renderAjax('_koleksi', [
            'dataProvider' => $dataProvider,
            'mode' => 'create',
            'idjilid' => 'NULL',
            ]);
    }

    /**
     * Lists all Collections models.
     * @return mixed
     */
    public function actionShowSerialCollectionView($idjilid,$idcat)
    {
        $notInId= array();
        $searchModel = new CollectionSearchJilid;
        $data = $searchModel->collectionView($idjilid,$idcat);
        foreach ($data->models as $key => $value) {
            $notInId[] = $value['ID'];
        }
        
        $dataProvider = $searchModel->serialCollectionList(Yii::$app->request->getQueryParams(),$notInId);
        return $this->renderAjax('_koleksi', [
            'dataProvider' => $dataProvider,
            'mode' => 'view',
            'idjilid' => $idjilid,
            ]);
    }

    /**
     * Lists all Collections models.
     * @return mixed
     */
    public function actionIndex()
    {
        \Yii::$app->session['SessJilidKoleksiList'] =  NULL;

        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new CollectionSearchJilid;
        $dataProvider = $searchModel->advancedSearch($rules);

        /*$searchModel = new CollectionSearchJilid;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());*/

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'rules'=> $rules
        ]);
    }

    /**
     * Lists all Collections models.
     * @return mixed
     */
    public function actionCreate()
    {
        // $resultData = [
        // array("ID"->1,"NomorBarcode"=>1,"NoInduk"=>1,"DataBib"=>"Teesttt 1","EDISISERIAL"=>"Edisi 1","TANGGAL_TERBIT_EDISI_SERIAL"=>"1 Januari 2010"),
        // array("ID"->2,"NomorBarcode"=>2,"NoInduk"=>2,"DataBib"=>"Teesttt 2","EDISISERIAL"=>"Edisi 2","TANGGAL_TERBIT_EDISI_SERIAL"=>"2 Januari 2010"),
        // array("ID"->3,"NomorBarcode"=>3,"NoInduk"=>3,"DataBib"=>"Teesttt 3","EDISISERIAL"=>"Edisi 3","TANGGAL_TERBIT_EDISI_SERIAL"=>"3 Januari 2010")
        // ];
        if(empty(\Yii::$app->session['SessJilidKoleksiList']))
        {
            \Yii::$app->session['SessJilidKoleksiList'] = array();
        }

        //\Yii::$app->session['SessJilidKoleksiList'] = array();

        $dataProvider = new ArrayDataProvider([
                'key'=>'ID',
                'allModels' => Yii::$app->session['SessJilidKoleksiList'],
                'sort' => [
                    'attributes' => ['ID', 'NomorBarcode', 'NoInduk', 'DataBib', 'EDISISERIAL', 'TANGGAL_TERBIT_EDISI_SERIAL'],
                ],
        ]);  

        return $this->render('create', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Collections model.
     * @param double $id
     * @return mixed
     */
    public function actionView($idjilid,$idcat)
    {
        $searchModel = new CollectionSearchJilid;
        $dataProvider = $searchModel->collectionView($idjilid,$idcat);
        $model = Collections::find()
                 ->where(['Catalog_id'=>$idcat,'IDJILID'=>$idjilid])
                 ->one();
          // echo '<pre>'; print_r($model);echo'</pre>';

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'model'=>$model
        ]);
    }

    /**
     * Restore Collections model.
     * @param double $id
     * @return mixed
     */
    public function actionFlashMessage($type,$Message)
    {
        Yii::$app->getSession()->setFlash('success', [
                        'type' => $type,
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app',$Message),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]);
    }

    /**
     * Finds the Collections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param double $id
     * @return Collections the loaded model
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
