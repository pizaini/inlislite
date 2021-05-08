<?php
/**
 * @link https://www.inlislite.perpusnas.go.id/
 * @copyright Copyright (c) 2015 Perpustakaan Nasional Republik Indonesia
 * @license https://www.inlislite.perpusnas.go.id/licences
 */


namespace backend\modules\member\controllers;

use common\models\MemberPerpanjanganSearch;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\imagine\Image;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\ErrorException;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;



//MODEL
use common\models\Members;
use common\models\Membersonline;
use common\models\MemberSearch;
use common\models\PelanggaranSearch;
use common\models\MembersForm;
use common\models\LocationLibrary;
use common\models\Collectioncategorys;
use common\models\JenisAnggota;
use common\models\Memberloanauthorizelocation;
use common\models\Memberloanauthorizecategory;
use common\models\CollectionloanSearch;
use common\models\CollectionloanitemSearch;

// Component
use common\components\EJCropper;
use common\components\MemberHelpers;
use common\components\Helpers;
use common\components\DirectoryHelpers;
use kartik\mpdf\Pdf;


use leandrogehlen\querybuilder\Translator;




/**
 * MemberController implements the CRUD actions for Members model.
 * @author Henry <alvin_vna@yahoo.com>
 */
class MemberController extends Controller
{
    public $enableCsrfValidation = false;

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
     * Lists all Members models.
     * @return mixed
     */
    public function actionIndex()
    {
        $perpage = 10;
        $getPerPage = $_GET['per-page'];
        if(!empty($getPerPage)){
            $perpage = (int)$_GET['per-page'];
        }

          /*$query = Members::find();
          $rules = Json::decode(Yii::$app->request->get('rules'));
          if ($rules) {
              $translator = new Translator($rules);
              $query
                ->andWhere($translator->where())
                ->addParams($translator->params());
          }

          $dataProvider = new ActiveDataProvider([
              'query' => $query,
          ]);

          return $this->render('index', [
              'dataProvider' => $dataProvider,
              'searchModel' => $dataProvider,
              'rules' => $rules
          ]);*/
        
        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new MemberSearch;
        $dataProvider = $searchModel->advancedSearch($rules);
        $dataProvider->pagination->pageSize=$perpage;
       return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'rules' => $rules
        ]);

        /*$dataProvider = $searchModel->searchDodot($rules);
        return $this->render('index-dodot', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'rules' => $rules
        ]);*/
    }

    /**
     * Lists all Members models.
     * @return mixed
     */
    public function actionKeranjangAnggota()
    {
        /*$queryKeranjang = \common\models\KeranjangAnggota::find()->asArray()->all();
         foreach ($queryKeranjang as $row) {
                    if ($row != null) {
                      $newItem[] = $row['Member_id'];
                    }
        }
        echo($newItem);
        die;*/

         
        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new MemberSearch;

        $dataProvider = $searchModel->advancedSearch2($rules);
        

        

        return $this->render('keranjang', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'rules' => $rules
        ]);
    }

    /**
     * Displays a single Members model.
     * @param double $id
     * @return mixed
     */
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
     * Creates a new Members model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $success = false;
        $model = new Members;
       
        $membersForm = membersForm::find()
                        ->where(['Jenis_Perpustakaan_id' => Yii::$app->config->get('JenisPerpustakaan')])
                        ->asArray()->all();
      
        $tipeNoAnggota = Yii::$app->config->get('TipeNomorAnggota');
        if($tipeNoAnggota == 'Otomatis'){
            $template = Yii::$app->config->get('TipePenomoranAnggota');
            if($template == 3){
                $memberNo = "Otomatis";
            }elseif($template < 3){
                $result = MemberHelpers::getMaxMemberNo($template);
                $memberNo = MemberHelpers::getNewMemberNo($result,$template);
            }
            elseif($template == 4)
            {
                $memberNo = "Otomatis Mengikuti NIK";
            }
        }else{
                //Manual
                $memberNo = $_POST['Members']['MemberNo'];
        }

        $jenisIdentiasNIK = MemberHelpers::getJenisIdentitasNik();

        if ($model->load(Yii::$app->request->post())) {
            //Post Data
            $memloan = $_POST['Members']['locationCategory'];
            $memloancat = $_POST['Members']['collectionCategory'];
            $trans = Yii::$app->db->beginTransaction();
            $model->Fullname = strtoupper($model->Fullname);
            $model->JenisPermohonan_id = 1; // BARU
            $model->MemberNo = $memberNo;


            if($tipeNoAnggota == 'Otomatis'){
                if ($template == 4 && empty($jenisIdentiasNIK) || is_null($jenisIdentiasNIK))
                {

                    Yii::$app->getSession()->setFlash('error', [
                                'type' => 'danger',
                                'duration' => 5000,
                                'icon' => 'fa fa-info-circle',
                                'message' => Yii::t('app','Tidak dapat generate No. Anggota dengan sistem penomoran Pilihan 4. Tidak ada setting jenis identitas yang terhubung dengan data kependudukan'),
                                'title' => 'Info',
                                'positonY' => Yii::$app->params['flashMessagePositionY'],
                                'positonX' => Yii::$app->params['flashMessagePositionX']]);

                    $success = false;
                    $model->addError('Error', 'Tidak dapat generate No. Anggota dengan sistem penomoran Pilihan 4. Tidak ada setting jenis identitas yang terhubung dengan data kependudukan');
                    $validate = false;

                }elseif ($template == 4 && $model->IdentityType_id != $jenisIdentiasNIK)
                {

                    Yii::$app->getSession()->setFlash('error', [
                                'type' => 'danger',
                                'duration' => 5000,
                                'icon' => 'fa fa-info-circle',
                                'message' => Yii::t('app','Tidak dapat generate No. Anggota dengan sistem penomoran Pilihan 4. Pilihan Jenis Identitas tidak sama dengan jenis identitas yang terhubung dengan data kependudukan'),
                                'title' => 'Info',
                                'positonY' => Yii::$app->params['flashMessagePositionY'],
                                'positonX' => Yii::$app->params['flashMessagePositionX']]);

                    $success = false;
                    $model->addError('Error', 'Tidak dapat generate No. Anggota dengan sistem penomoran Pilihan 4. Pilihan Jenis Identitas tidak sama dengan jenis identitas yang terhubung dengan data kependudukan');
                    $validate = false;
                }elseif ($template == 4 && $model->IdentityType_id != $jenisIdentiasNIK)
                {

                    Yii::$app->getSession()->setFlash('error', [
                                'type' => 'danger',
                                'duration' => 5000,
                                'icon' => 'fa fa-info-circle',
                                'message' => Yii::t('app','Tidak dapat generate No. Anggota dengan sistem penomoran Pilihan 4. Pilihan Jenis Identitas tidak sama dengan jenis identitas yang terhubung dengan data kependudukan'),
                                'title' => 'Info',
                                'positonY' => Yii::$app->params['flashMessagePositionY'],
                                'positonX' => Yii::$app->params['flashMessagePositionX']]);

                    $success = false;
                    $model->addError('Error', 'Tidak dapat generate No. Anggota dengan sistem penomoran Pilihan 4. Pilihan Jenis Identitas tidak sama dengan jenis identitas yang terhubung dengan data kependudukan');
                    $validate = false;

                }elseif ($template == 4 && empty($model->IdentityNo) || is_null($model->IdentityNo))
                {

                    Yii::$app->getSession()->setFlash('error', [
                                'type' => 'danger',
                                'duration' => 5000,
                                'icon' => 'fa fa-info-circle',
                                'message' => Yii::t('app','Tidak dapat generate No. Anggota dengan sistem penomoran Pilihan 4, dengan NIK kosong'),
                                'title' => 'Info',
                                'positonY' => Yii::$app->params['flashMessagePositionY'],
                                'positonX' => Yii::$app->params['flashMessagePositionX']]);

                    $success = false;
                    $model->addError('Error', 'Tidak dapat generate No. Anggota dengan sistem penomoran Pilihan 4, dengan NIK kosong');
                    $validate = false;

                } else{
                    $validate = true;
                }

                if($template == 4 && $validate == true){
                    $model->MemberNo = $model->IdentityNo; // Sett MemberNo = NIK.
                }

                if($template == 3){
                    $result = MemberHelpers::getMaxMemberNo($template,$model->Sex_id);
                    $model->MemberNo = MemberHelpers::getNewMemberNo($result,$template,$model->Sex_id);
                }
            } else {
                $validate = true;
            }


            if (!$model->JenisAnggota_id) {
            Yii::$app->getSession()->setFlash('error', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Jenis Anggota harus di pilih'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']]);

            $success = false;
            $model->addError('Error', 'Jenis Anggota harus di pilih');
            $validate = false;

                


            }

            $cekDuplicateNamaTTL=\common\models\Members::find()->andWhere('Fullname = :Fullname',[':Fullname' => $model->Fullname])->andWhere('DateOfBirth = :DateOfBirth',[':DateOfBirth' => $model->DateOfBirth])->andWhere('PlaceOfBirth = :PlaceOfBirth',[':PlaceOfBirth' => $model->PlaceOfBirth])->count();

            if ($cekDuplicateNamaTTL != 0 ) {
                $success = false;
                $validate = false;
                $model->addError('Error Saving', 'Nama Lengkap '.$model->Fullname.' sudah di gunakan');
                $model->addError('Error Saving', 'Tanggal Lahir '.$model->DateOfBirth.' sudah di gunakan');
                $model->addError('Error Saving', 'Tempat Lahir '.$model->PlaceOfBirth.' sudah di gunakan');
            }

            try{
                //$model->DateOfBirth = Yii::$app->request->post('TglLahir');
                if ($model->save() && $validate) {
                    $success = true;
                    $memberId = $model->getPrimaryKey();
                    echo $memberId;
                    // Jika Lokasi tidak null maka insert ke memberloanauthorizeLocaitons
                    if ($memloan != "") {
                        foreach ($memloan as $key => $value) {
                            $modelMemberLoanAuth = new Memberloanauthorizelocation();
                            $modelMemberLoanAuth->Member_id = $memberId;
                            $modelMemberLoanAuth->LocationLoan_id = $value;
                            $modelMemberLoanAuth->save();
                        }
                    }
                    // Jika Jenis Koleksi tidak null maka insert ke memberloanauthorizeCategory
                    if ($memloancat != "") {
                        foreach ($memloancat as $key => $value) {
                            $modelMemberLoanCat = new Memberloanauthorizecategory();
                            $modelMemberLoanCat->Member_id = $memberId;
                            $modelMemberLoanCat->CategoryLoan_id = $value;
                            $modelMemberLoanCat->save();
                        }
                    }

                        Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Success Save'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);

                     $trans->commit();
                }

            }catch (CDbException $e) {
                $trans->rollback();
                $success = false;
                $model->addError('Error Saving', $e->getMessage());
            }
            
        }

        if($success){
             return $this->redirect(['update','id'=>$memberId]);
             //return $this->redirect(['index']);
        }else{

            // Binding location and Collection fro default create
            $model->locationCategory = $this->getLocationDefault(1);
            $model->collectionCategory = $this->getCategoryCollectionDefault(1);
            $biaya = JenisAnggota::findOne(1);
            $pendaftaran = $biaya->BiayaPendaftaran;


            
        
            return $this->render('create', [
                'model' => $model,
                'memberNo' => $memberNo,
                'membersForm'=>$membersForm,
                'Pendaftaran'=>$pendaftaran,
            ]);
        }

    }



    /**
     * Updates an existing Members model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param double $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        
        $username = Yii::$app->user->identity->username;
        $modelUser = \common\models\UserSetting::findByUsername($username);

        $success = false;

        $model = $this->findModel($id);
        $oldMemberNo= $model->MemberNo;
        $membersForm = membersForm::find()
                        ->where(['Jenis_Perpustakaan_id' => Yii::$app->config->get('JenisPerpustakaan')])
                        ->asArray()->all();

         if ($model->load(Yii::$app->request->post())) {
            $newMemberNo= $model->MemberNo;
            $memloan = $_POST['Members']['locationCategory'];
            $memloancat = $_POST['Members']['collectionCategory'];
            $trans = Yii::$app->db->beginTransaction();
            $model->Fullname = strtoupper($model->Fullname);
            $success = true;
          
            if($model->StatusAnggota_id == 6) { // BEBAS PUSTAKA
                  // Periksa jika status bebas pustaka maka diperiksa apakah masih ada peminjaman.
                    $countPeminjaman = \common\models\Collectionloanitems::find()
                                ->andWhere('member_id = :member_id',[':member_id' => $id])
                                ->andWhere('LoanStatus = :status',[':status' => 'loan'])
                                ->count();
                     if($countPeminjaman > 0){
                        $success = false;
                        echo "Masih ada peminjamam tidak bisa status bebas pustaka.";
                        die;
                     }else{
                        // boleh diupdate
                        $success = true;
                     }
            }
            if ($oldMemberNo != $newMemberNo) {


                $modelMemberOnline = \common\models\Members::find()->andWhere('MemberNo = :MemberNo',[':MemberNo' => $newMemberNo])->count();

                if ($modelMemberOnline != 0 ) {
                    $success = false;
                    $model->addError('Error Saving', 'Nomor Anggota '.$newMemberNo.' sudah di gunakan');
                }

            }                    
           
            if($success){  
            
                try{
                 if ($model->save()) {
                    $success = true;
                    $status = "";
                    $memberId = $model->ID;
                    //echo $memberId;

                    $modelMemberOnline = \common\models\Membersonline::find(['MemberNo'=>$model->MemberNo]);
                    if (!isset($modelMemberOnline)) {
                        //var_dump($modelMemberOnline);
                        die;
                                    if($model->StatusAnggota_id = 1){
                                        $status = "ACTIVE"; 
                                    }else{
                                        $status = "NOTACTIVE";
                                    }
                                    $modelMemberOnline->Status = $status;
                                    $modelMemberOnline->save();
                    }

                    //delete dlu di memberloanauthorizeLocaitons where NoAnggota
                    $rowDeleted = \common\models\Memberloanauthorizelocation::deleteAll('Member_id = :memberId', [':memberId' => $model->ID]);

                   // Jika Lokasi tidak null maka insert ke memberloanauthorizeLocaitons
                    if ($memloan != "") {
                        foreach ($memloan as $key => $value) {
                            $modelMemberLoanAuth = new Memberloanauthorizelocation();
                            $modelMemberLoanAuth->Member_id = $memberId;
                            $modelMemberLoanAuth->LocationLoan_id = $value;
                            $modelMemberLoanAuth->save();
                        }
                    }

                     //delete dlu di memberloanauthorizeCategory where NoAnggota
                    $rowDeletedCategory = \common\models\Memberloanauthorizecategory::deleteAll('Member_id = :memberId', [':memberId' => $model->ID]);


                    // Jika Jenis Koleksi tidak null maka insert ke memberloanauthorizeCategory
                    if ($memloancat != "") {
                        foreach ($memloancat as $key => $value) {
                            $modelMemberLoanCat = new Memberloanauthorizecategory();
                            $modelMemberLoanCat->Member_id = $memberId;
                            $modelMemberLoanCat->CategoryLoan_id = $value;
                            $modelMemberLoanCat->save();
                        }
                    }

                    $trans->commit();


                     Yii::$app->getSession()->setFlash('success', [
                            'type' => 'info',
                            'duration' => 500,
                            'icon' => 'fa fa-info-circle',
                            'message' => Yii::t('app','Success Edit'),
                            'title' => 'Info',
                            'positonY' => Yii::$app->params['flashMessagePositionY'],
                            'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]);
                    return $this->redirect(['update', 'id' => $model->ID]);
                } else $success = false;

             }catch (CDbException $e) {
                $trans->rollback();
                $success = false;
                $model->addError('Error Saving', $e->getMessage());
            }
        
             
        }
           
        } 


        if(!$success){

        $model->TglLahir = \common\components\Helpers::DateTimeToViewFormat($model->DateOfBirth);
        $model->TglRegisterDate = \common\components\Helpers::DateTimeToViewFormat($model->RegisterDate);
        $model->TglEndDate = \common\components\Helpers::DateTimeToViewFormat($model->EndDate);
        

         // Binding List Pelanggaran
        $searchModelPelanggaran = new PelanggaranSearch;
        $queryParams = array_merge(array(),Yii::$app->request->getQueryParams());
        $queryParams["PelanggaranSearch"]["Member_id"] = $id ;
        $dataProviderPelanggaran = $searchModelPelanggaran->search($queryParams);

         // Binding List Peminjaman
        $searchModelPeminjaman = new CollectionloanitemSearch;
        $queryParams = array_merge(array(),Yii::$app->request->getQueryParams());
        $queryParams["CollectionloanitemSearch"]["Member_id"] = $id ;

        $searchModelPeminjaman->member_id = $id;
        $dataProviderPeminjaman = $searchModelPeminjaman->searchForMember($queryParams);


         // Binding List Perpanjangan
        $searchModelPerpanjangan = new MemberPerpanjanganSearch();
        $queryParams = array_merge(array(),Yii::$app->request->getQueryParams());
        $queryParams["MemberPerpanjanganSearch"]["Member_id"] = $id ;
        $dataProviderPerpanjangan = $searchModelPerpanjangan->search($queryParams);

            // Binding location and Collection fro default create
            $model->locationCategory = $this->getLocationDefaultByMember($model->ID);
            $model->collectionCategory = $this->getCategoryCollectionDefaultByMember($model->ID);
            $biaya = JenisAnggota::findOne(1);
            $pendaftaran = $biaya->BiayaPendaftaran;


        // Binding data sumbangan.
        $sqlSumbangan = "SELECT Jumlah,Keterangan,(SELECT COUNT(*) FROM sumbangan_koleksi WHERE sumbangan_koleksi.Sumbangan_id = sumbangan.ID) as JumlahKoleksi FROM sumbangan WHERE Member_ID = ".$model->ID;

        $resultSumbangan = Yii::$app->db->createCommand($sqlSumbangan)->queryAll();

        
            return $this->render('update', [
                'model' => $model,
                'membersForm'=>$membersForm,
                'pendaftaran'=>$pendaftaran,
                'dataProviderPelanggaran' => $dataProviderPelanggaran,
                'searchModelPelanggaran' => $searchModelPelanggaran,
                'dataProviderPeminjaman' => $dataProviderPeminjaman,
                'searchModelPeminjaman' => $searchModelPeminjaman,
                'dataProviderPerpanjangan' => $dataProviderPerpanjangan,
                'dataSumbangan' => $resultSumbangan,
                'modelUser'=>$modelUser

            ]);

        }
        
    }

    public function actionHistoriPeminjaman($id) {
        $model = $this->findModel($id);

        $username = Yii::$app->user->identity->username;
        $modelUser = \common\models\UserSetting::findByUsername($username);

        $biaya = JenisAnggota::findOne(1);
        $pendaftaran = $biaya->BiayaPendaftaran;

         // Binding List Peminjaman
        $searchModelPeminjaman = new CollectionloanitemSearch;
        $queryParams = array_merge(array(),Yii::$app->request->getQueryParams());
        $queryParams["CollectionloanitemSearch"]["Member_id"] = $id ;

        $searchModelPeminjaman->member_id = $id;
        $dataProviderPeminjaman = $searchModelPeminjaman->searchForMember($queryParams);

        return $this->render('_listPeminjaman', [
            'model' => $model,
            'modelUser'=>$modelUser,
            'pendaftaran'=>$pendaftaran,
            'dataProviderPeminjaman' => $dataProviderPeminjaman,
            'searchModelPeminjaman' => $searchModelPeminjaman,
        ]);
    }

    /**
     * Deletes an existing Members model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param double $id
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
     * Finds the Members model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param double $id
     * @return Members the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Members::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



    /**
     * To take initial location data.
     * @param  int $q [id jenis anggota]
     * @return Array Location_Library_id
     */
    public function getLocationDefault($q = null) {
        $query = new \yii\db\Query;

        $query->select('Location_Library_id')
            ->from('location_library_default')
            ->where('JenisAnggota_id =  '.$q)
            ->orderBy('Location_Library_id');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = [];
        foreach ($data as $d) {
            $out[] = $d['Location_Library_id'];
        }
        return $out;
    }

    /**
     * To take initial location data.
     * @param  int $q [id jenis anggota]
     * @return Array Location_Library_id
     */
    public function getLocationDefaultByMember($q = null) {
        $query = new \yii\db\Query;

        $query->select('LocationLoan_id')
            ->from('memberloanauthorizelocation')
            ->where('Member_id =  '.$q)
            ->orderBy('LocationLoan_id');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = [];
        foreach ($data as $d) {
            $out[] = $d['LocationLoan_id'];
        }
        return $out;
    }

    /**
     * To take initial category collection data.
     * @param  int $q [id jenis anggota]
     * @return Array Location_Library_id
     */
    public function getCategoryCollectionDefault($q = null) {
        $query = new \yii\db\Query;

        $query->select('CollectionCategory_id')
            ->from('collectioncategorysdefault')
            ->where('JenisAnggota_id =  '.$q)
            ->orderBy('CollectionCategory_id');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = [];
        foreach ($data as $d) {
            $out[] = $d['CollectionCategory_id'];
        }
        return $out;
    }


    /**
     * To take initial category collection data.
     * @param  int $memberID [id anggota]
     * @return Array Location_Library_id
     */
    public function getCategoryCollectionDefaultByMember($memberID) {
        $query = new \yii\db\Query;

        $query->select('CategoryLoan_id')
            ->from('memberloanauthorizecategory')
            ->where('Member_id = "' . $memberID .'"')
            ->orderBy('CategoryLoan_id');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = [];
        foreach ($data as $d) {
            $out[] = $d['CategoryLoan_id'];
        }
        return $out;
    }


    /**
     * Returns the data list propinsi
     */
    public function actionProvinceList($q = null) {
        $query = new \yii\db\Query;
        //SELECT NamaPropinsi FROM propinsi WHERE NamaPropinsi LIKE :propinsi
        $query->select('NamaPropinsi')
            ->from('propinsi')
            ->where('NamaPropinsi LIKE "%' . $q .'%"')
            ->orderBy('NamaPropinsi');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = [];
        foreach ($data as $d) {
            $out[] = ['value' => $d['NamaPropinsi']];
        }
        echo \yii\helpers\Json::encode($out);
    }

    /**
     * Returns the data list kabupaten
     */
    public function actionKabupatenList() {
        $res = array();
        if (isset($_GET['term']) && isset($_GET['prop'])) {
             $query = new \yii\db\Query;
           /* $qtxt = "SELECT NamaKab FROM kabupaten INNER JOIN propinsi ON (kabupaten.PropinsiID = propinsi.ID) WHERE propinsi.NamaPropinsi =:propinsi and NamaKab LIKE :kabupaten ";
            $command = Yii::app()->db->createCommand($qtxt);
            $command->bindValue(":propinsi", $_GET['prop'], PDO::PARAM_STR);
            $command->bindValue(":kabupaten", '%' . $_GET['term'] . '%', PDO::PARAM_STR);
            $res = $command->queryColumn();*/
             
             //echo $_GET['prop'];
             
            $query->select('NamaKab')
            ->from('kabupaten')
            ->leftJoin('propinsi', 'kabupaten.PropinsiID = propinsi.ID')
            ->where('NamaPropinsi LIKE "%' . $_GET['prop'] .'%"')
            ->andWhere('NamaKab LIKE "%' . $_GET['term'] .'%"')
            ->orderBy('NamaKab');
            $command = $query->createCommand();
            $data = $command->queryAll();
            //var_dump($data);
            $out = [];
            foreach ($data as $d) {
                $out[] = $d['NamaKab'];
            }
            echo \yii\helpers\Json::encode($out);

        }

    }


    /**
     * Fungsi untuk mengambil data jurusan berdasarkan fakultas.
     * @return Json DataJurusan
     */
    public function actionJurusan() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $fakultas_id = $parents[0];
                if (!empty($_POST['depdrop_params'])) {
                    $params = $_POST['depdrop_params'];
                }

                $out = \common\models\MasterJurusan::getOptionsByFakultas($fakultas_id);
                echo \yii\helpers\Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo \yii\helpers\Json::encode(['output'=>'', 'selected'=>'']);
    }


    /**
     * Fungsi untuk mengambil data program studi berdasarkan jurusan.
     * @return Json Data Program Studi
     */
    public function actionProdi() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $jurusan_id = $parents[0];
                if (!empty($_POST['depdrop_params'])) {
                    $params = $_POST['depdrop_params'];
                }

                $out = \common\models\MasterProgramStudi::getOptionsByJurusan($jurusan_id);
                echo \yii\helpers\Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo \yii\helpers\Json::encode(['output'=>'', 'selected'=>'']);
    }


    /**
     * Get Biaya Pendaftaran Value.
     * @return  int $biaya->BiayaPendaftaran
     */
    public function actionGetBiayaPendaftaran() {
         $model = new Members;
         $id = $_POST['id'];
         $biaya = JenisAnggota::findOne($id);
         echo $biaya->BiayaPendaftaran;
    }


    /**
     * Get Biaya Pendaftaran Value.
     * @return  int $biaya->BiayaPendaftaran
     */
    public function actionResetPassword() {
         $NoAnggota = Yii::$app->request->post('NoAnggota');
         $model = Membersonline::find()->where(['NoAnggota'=>$NoAnggota])->one();
        if ($model !== null) {
            $model->Password = sha1('member123');
            if($model->save()){
                return '1';
            }else{
                return '0';
            }
        } else {
            return '0';
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionSaveFoto($id) {

        $model_mem = Members::findOne($id);
        
        $pathSave = MemberHelpers::getRealPathFotoAnggota();
        $fileName = $id .'.jpg';


        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['imgBase64']));

        $filepath = Yii::getAlias('@uploaded_files/foto_anggota/'.$fileName);
        $dirpath = Yii::getAlias('@uploaded_files/foto_anggota/');

        if (isset($model_mem->PhotoUrl)) {
            $newFileName = $fileName;
        } else
        if (file_exists($filepath)) {
            $newFileName = DirectoryHelpers::getNewFileName($dirpath ,$filepath,$fileName);
        }else{
            $newFileName = $fileName;
        }
        

        // Save the image in a defined path
        file_put_contents($pathSave .$newFileName,$data);

        // move_uploaded_file($_FILES['webcam']['tmp_name'], $pathSave .$newFileName);
        $model = Members::findOne($id);
        $model->PhotoUrl=$fileName;
        $model->save(false);

        //Temp
        //copy($pathSave .$fileName, $pathSave .'temp/' .$fileName);
        //move_uploaded_file($_FILES['webcam']['tmp_name'], $pathSave .'temp/' .$fileName);
        //
        //return $this->redirect(['update','id'=>$id]);
        return true;
    }


    public function actionTesting() {
         $model = new Members;
         $id = $_POST['id'];
         $locationCategory = $this->getLocationDefault($id);
         $collectionCategory = $this->getCategoryCollectionDefault($id);

         $data = \yii\helpers\ArrayHelper::map(LocationLibrary::find()->all(),'ID','Name');

         $dataCollection = \yii\helpers\ArrayHelper::map(Collectioncategorys::find()->all(),'ID','Name');

        // Retrieve Data Location
        echo '<div class="field-members-locationcategory" style="width: 821px;">
        <label class="control-label col-md-2" for="members-locationcategory">Lokasi Pinjam</label>

        <div class="col-md-10"><input type="hidden" name="Members[locationCategory]" value="">

            <div id="members-locationcategory">';

        foreach ($data as $value => $name) {
            if(in_array($value, $locationCategory)){
                echo '<div class="checkbox"><label><input type="checkbox" name="Members[locationCategory][]" value='.$value.' checked="">'.$name . '</label></div>';
            }else{
                echo '<div class="checkbox"><label><input type="checkbox" name="Members[locationCategory][]" value='.$value.' >'.$name . '</label></div>';
            }

        }

        echo '</div></div><div class="col-md-offset-2 col-md-10"></div></div>';

        // Retrieve Data Collection Category
        echo '<div class="field-members-collectioncategory" style="width: 821px;">
        <label class="control-label col-md-2" for="members-collectioncategory">Koleksi yang dapat dipinjam</label>

        <div class="col-md-10"><input type="hidden" name="Members[collectioncategory]" value="">

            <div id="members-collectioncategory">';

        foreach ($dataCollection as $value => $name) {

            if(in_array($value, $collectionCategory)){
                echo '<div class="checkbox"><label><input type="checkbox" name="Members[collectionCategory][]" value='.$value.' checked="">'.$name . '</label></div>';
            }else{
                echo '<div class="checkbox"><label><input type="checkbox" name="Members[collectionCategory][]" value='.$value.' >'.$name . '</label></div>';
            }

        }

        echo '</div></div><div class="col-md-offset-2 col-md-10"></div></div>';

     }

    /**
     * Crops the member image
     */
    public function actionCrop() {
        //Yii::import('ext.jcrop.EJCropper');
        $id = Yii::$app->request->post('NoAnggota');
        $models = $this->loadModelAnggota($id);

        $jcropper = new EJCropper;
        //$jcropper->thumbPath = MemberHelpers::getRealPathFotoAnggotaThumb();
        $jcropper->thumbPath = MemberHelpers::getRealPathFotoAnggota();
        $jcropper->jpeg_quality = 95;
        $jcropper->png_compression = 8;
        $coords = $jcropper->getCoordsFromPost('imageId');
        //$foto = MemberHelpers::getRealPathFotoAnggota().  $id. ".jpg";
        $foto = MemberHelpers::getRealPathFotoAnggota().$models->PhotoUrl;
        $thumbnail = $jcropper->crop($foto, $coords);
        return $this->redirect(['update', 'id' => $id]);
    }

    /**
     * Crops the member image
     */
    public function actionCropProfileImage()
    {

        $model = new \backend\models\forms\CropProfileImage();
        $profileImage = new \backend\libs\ProfileImage('1');

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $profileImage->cropOriginal($model->cropX, $model->cropY, $model->cropH, $model->cropW);
            //return $this->htmlRedirect(Yii::$app->user->getModel()->getUrl());
            return $this->redirect(['update', 'id' => '1']);
        }

        return $this->renderAjax('cropProfileImage', array('model' => $model, 'profileImage' => $profileImage, 'user' => Yii::$app->user->getIdentity()));
    }
    
     public function loadModelAnggota($id)
    {
        $model = Members::findOne($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     *  Function for print member card.
     */
    public function actionKartuAnggota($id) {
        
        
        $model = $this->loadModelAnggota($id);
       
        $separator = DIRECTORY_SEPARATOR;
        $backImage = Yii::getAlias('@uploaded_files') . "{$separator}settings{$separator}kartu_anggota{$separator}bg_cardmember".Yii::$app->config->get('KartuAnggota').".png";
        $image = Yii::getAlias('@uploaded_files') . "{$separator}foto_anggota{$separator}temp{$separator}$id.jpg";
        
         if (!realpath($image)) {
		$image=Yii::getPathOfAlias('webroot') . "{$separator}foto{$separator}/nophoto.jpg";
        }
	   $data = array(
	        'backImage' => $backImage,
	        'imageMember' => $image,
	    );
        
        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('_kartuAnggota',compact('model', 'data'));

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => [220,138],
            // portrait orientation
            //'orientation' => Pdf::ORIENT_LANDSCAPE,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            'marginLeft'=>0,
            'marginRight'=>0,
            'marginTop'=>0,
            'marginBottom'=>0,
            'marginHeader'=>0,
            'marginFooter'=>0,
            'cssFile' => '',
//            'methods' => [ 
//                'SetDisplayMode'=>80, 
//            ]
            
        ]);
        
        
        
        // return the pdf output as per the destination setting
        return $pdf->render();
        
        /*$pdf = new Pdf();
        $pdf->format = ['1004px','618px'];
        $pdf->setApi();
        $mpdf = $pdf->api; // fetches mpdf api
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHtml($content); // call mpdf write html
        
        echo $mpdf->Output('filename', 'I'); // call the mpdf api output as needed*/
        
        
       

    } 



    public function actionImportAnggota() {
        //echo ini_get("session.upload_progress.name");
        //die;
        $model = new \backend\models\ImportMemberForm();
        if (Yii::$app->request->isPost) {
            $model->file = \yii\web\UploadedFile::getInstance($model, 'file');
            $err='';

            if ($model->upload()) {
                // file is uploaded successfully
               $err = $model->import();
               $kata='';
               foreach ($err as $key => $value) {
                   $kata.=$value[0];
               }

               $model->deleteFile();

               if($err){
                    Yii::$app->getSession()->setFlash('error', [
                            'type' => 'danger',
                            'duration' => 6500,
                            'icon' => 'fa fa-info-circle',
                            'message' => Yii::t('app','Data gagal diimport.'.$kata),
                            'title' => 'Info',
                            'positonY' => Yii::$app->params['flashMessagePositionY'],
                            'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]);
                    return $this->redirect('import-anggota');
               }
                    Yii::$app->getSession()->setFlash('success', [
                            'type' => 'success',
                            'duration' => 6500,
                            'icon' => 'fa fa-info-circle',
                            'message' => Yii::t('app','Data berhasil diimport'),
                            'title' => 'Info',
                            'positonY' => Yii::$app->params['flashMessagePositionY'],
                            'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]); 
                    return $this->redirect('index');

            }
           
        }

        return $this->render('import', ['model' => $model,'err' => $err]);
       
      
    }

    public function actionProgress(){
        
        $key = ini_get("session.upload_progress.prefix") . "w0";
        echo $_SESSION[$key];
        die;
        if (!empty($_SESSION[$key])) {
            $current = $_SESSION[$key]["bytes_processed"];
            $total = $_SESSION[$key]["content_length"];
            echo $current < $total ? ceil($current / $total * 100) : 100;
        }
        else {
            echo 100;
        }
    }


    /**
     * Untuk Mengambil data Kependudukan
     * @return [type] [description]
     */
    public function actionDetailKependudukan()
    {
        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        /*$searchModel = new MemberSearch;
        $dataProvider = $searchModel->advancedSearch($rules);
        

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'rules' => $rules
        ]);*/

        $searchModel = new \common\models\MasterKependudukanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        //$dataProvider = $searchModel->search($rules);
     
        return $this->renderAjax('detailPenduduk', [  // ubah ini
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'rules' => $rules
        ]);
    }

    /**
         * Fungsi untuk binding data dari modal Booking.
         *
         * @param int $id from modal Booking
         */
    public function actionBindPenduduk($id)
    {

        $model =  \common\models\MasterKependudukanSearch::findOne($id);
        
        if(!empty($model->pendidikan)){
            $posPendidikan = strpos(strtolower($model->pendidikan), '/sederajat');
            
            if ($posPendidikan) {
                 $namePendidikan = substr(strtolower($model->pendidikan),0,$posPendidikan);
                 $namePendidikan = trim(str_replace("slta","SMA",$namePendidikan));
                 $namePendidikan = trim(str_replace("tamat","",$namePendidikan));

            }else{
                 $namePendidikan = strtolower($model->pendidikan);
            }
           
            $pendidikan_id = \common\components\MemberHelpers::getIdPendidikanByName($namePendidikan);
        }

        if(!empty($model->pekerjaan)){
            $pekerjaan_id = \common\components\MemberHelpers::getIdPekerjaanByName(trim($model->pekerjaan));
        }

        if(!empty($model->sts)){
           
            $nameSts = trim(str_replace("kawin","menikah",strtolower($model->sts)));
            $statusKawin_id = \common\components\MemberHelpers::getIdStatusPerkawinanByName($nameSts);
        }

        if(!empty($model->agm)){
           
            $nameAgama = trim(str_replace("katholik","katolik",strtolower($model->agm)));
            $agama_id = \common\components\MemberHelpers::getIdAgamaByName($nameAgama);
        }

       

        if(!empty($model->jenis)){
           
            if(strtolower($model->jenis) == 'p'){
                $jk = 1;
            }else{
                $jk = 2;
            }
            
        }



        return \yii\helpers\Json::encode([
                'id' => $model->id,
                'nama' => $model->namalengkap,
                'tempatLahir' => $model->lhrtempat,
                'tglLahir' => date("m-d-Y",strtotime($model->lhrtanggal)), 
                'alamat' => $model->alamat,
                'jenisKelamin' => $jk,
                'pendidikan' => $pendidikan_id,
                'pekerjaan' => $pekerjaan_id,
                'statusKawin' => $statusKawin_id,
                'agama' => $agama_id,
                'identityNik' => \common\components\MemberHelpers::getJenisIdentitasNik(),
                'nik' => $model->nik,  
            ]);
    
    }

    /**
     * Process records which is checked
     * @return mixed
     */
    public function actionCheckboxProcess()
    {
        $post = Yii::$app->request->post(); $msg='';
        // print_r($post['action']); die;

        if($post['action']=='cetak_kartu_blakang'){
            $this->redirect(['/member/pdf/kartu-belakang-anggota']);
            // return ;            
        }
        else if(isset($post['action']) && isset($post['row_id'])){
            $actid;
            $rowid = $post['row_id'];
            

            if(isset($post['actionid']))
            {
                $actid2 = $post['actionid2'];
                //Standar Cetak Kartu Model 1 (Printer Kartu)
                if($actid2 == 'model1'){
                    // Redirect ke PDF
                    //$msg = "Data telah dicetak.";
                    $session = Yii::$app->session;
                    $session->open();
                    $session->set('cetak-bebas-pustaka', $rowid);

                    $this->redirect(['/member/pdf/cetak-bebas-pustaka/','tipe'=>'1']);
                    return ;

                }
                if($actid2 == 'model2'){
                    // Redirect ke PDF
                    //$msg = "Data telah dicetak.";
                    $session = Yii::$app->session;
                    $session->open();
                    $session->set('cetak-bebas-pustaka', $rowid);

                    $this->redirect(['/member/pdf/cetak-bebas-pustaka/','tipe'=>'2']);
                    return;
                }
            }

            if(isset($post['actionid']))
                $actid = $post['actionid'];
                switch (trim($post['action'])) {
                    case 'aktivasi':
                    foreach ($rowid as $key => $value) {
                        $model = Members::findOne($value);
                        $model->StatusAnggota_id =  3; //Aktif
                        if($model->save(false))
                        {
                            $msg =  yii::t('app','Status Anggota sudah diaktifkan.');
                        }else{
                            
                            throw new \yii\web\HttpException(404, yii::t('app','Status Anggota gagal diaktifkan.'));    
                        }
                    }
                    
                    break;
                    case 'keranjang-anggota':
                    foreach ($rowid as $key => $value) {
                        //delete dlu di KeranjangAnggota where Member_id

                        $rowDeleted = \common\models\KeranjangAnggota::deleteAll('Member_id = :memberId', [':memberId' => $value]);

                        $model = new \common\models\KeranjangAnggota;
                        $model->Member_id =  $value; //Aktif
                        if($model->save())
                        {
                            $msg = yii::t('app','Data telah dimasukan ke keranjang anggota.');

                        }else{
                            
                            throw new \yii\web\HttpException(404, yii::t('app','Data gagal dimasukan ke keranjang anggota.'));    
                        }
                    }

                    break;
                    case 'delete-bulk':
                    foreach ($rowid as $key => $value) {
                       
                        $rowDeleted = \common\models\Members::deleteAll('ID = :memberId', [':memberId' => $value]);
                        if($rowDeleted > 0)
                        {
                            $msg2 =  yii::t('app','Data telah dihapus.');
                        }else{
                            
                            throw new \yii\web\HttpException(404, yii::t('app','Data gagal dihapus.'));    
                        }
                    }
                    
                    break;
                    case 'delete-bulk-keranjang':
                    foreach ($rowid as $key => $value) {
                       
                        $rowDeleted = \common\models\KeranjangAnggota::deleteAll('Member_id = :memberId', [':memberId' => $value]);
                        if($rowDeleted > 0)
                        {
                            $msg2 =  yii::t('app','Data telah dihapus.');
                        }else{
                            
                            throw new \yii\web\HttpException(404, yii::t('app','Data gagal dihapus.'));    
                        }
                    }
                    
                    break;
                    case 'delete-all-keranjang':
                    
                       
                        $rowDeleted = \common\models\KeranjangAnggota::deleteAll();
                        if($rowDeleted > 0)
                        {
                            $msg2 =  yii::t('app','Data telah dihapus.');
                        }else{
                            
                            throw new \yii\web\HttpException(404, yii::t('app','Data gagal dihapus.'));    
                        }
                    
                    
                    break;
                    case 'cetak':
                        //Standar Cetak Kartu Model 1 (Printer Kartu)
                        if($actid == 'model1'){
                            // Redirect ke PDF
                            $msg = "Data telah dicetak.";
                            $session = Yii::$app->session;
                            $session->open();
                            $session->set('cetak-kartu-all', $rowid);
                            $this->redirect(['/member/pdf/kartu-anggota-all/','tipe'=>'1']);
                            return ;
                            

                        }
                        if($actid == 'model2'){
                            // Redirect ke PDF
                            $msg = "Data telah dicetak.";
                            $session = Yii::$app->session;
                            $session->open();
                            $session->set('cetak-kartu-all', $rowid);
                            return $this->redirect(['/member/pdf/kartu-anggota-all/','tipe'=>'2']);
                            
                        }


                    break;
                    /*case 'cetak-bebas-pustaka':
                        //Standar Cetak Kartu Model 1 (Printer Kartu)
                        if($actid == 'model1'){
                            // Redirect ke PDF
                            $msg = "Data telah dicetak.";
                            $session = Yii::$app->session;
                            $session->open();
                            $session->set('cetak-bebas-pustaka', $rowid);
                       
                            return $this->redirect(['/member/pdf/cetak-bebas-pustaka/','tipe'=>'1']);
                                    

                            

                        }
                        if($actid == 'model2'){
                            // Redirect ke PDF
                            $msg = "Data telah dicetak.";
                            $session = Yii::$app->session;
                            $session->open();
                            $session->set('cetak-bebas-pustaka', $rowid);

                            return $this->redirect(['/member/pdf/cetak-bebas-pustaka/','tipe'=>'2']);


                            
                        }


                    break;*/

                    default:
                    break;
                }
        }else{
            throw new \yii\web\HttpException(404, yii::t('app','Harap pilih anggota.'));
        }
        return $msg;
    }


    /**
     * Fungsi untuk upload foto anggita.
     * @return [type]     [description]
     */
    public function actionUploadFotoAnggota() {

        $id = Yii::$app->request->get('id');

        if (isset($_FILES['image'])) {
            $file = \yii\web\UploadedFile::getInstanceByName('image');




            $filepath = Yii::getAlias('@uploaded_files/foto_anggota/'.$file->name);
            $dirpath = Yii::getAlias('@uploaded_files/foto_anggota/');


            if (file_exists($filepath)) {
                $newFileName = DirectoryHelpers::getNewFileName($dirpath ,$filepath,$file->name);
            }else{
                $newFileName = $file->name;
            }
        
            

            $files_uploaded = Yii::getAlias('@uploaded_files/foto_anggota/'.$newFileName);
            if ($file->saveAs($files_uploaded)) {

                $mimetype=DirectoryHelpers::mimeType($files_uploaded);
                if ($mimetype) {
                    $model_mem = Members::findOne($id);
                    $model_mem->PhotoUrl=$newFileName;
                    $model_mem->save(false);
                    //resize original pict
                    Image::getImagine()->open(Yii::getAlias('@uploaded_files/foto_anggota/'.$newFileName))
                        ->resize(new Box('400', '500'))->save(Yii::getAlias('@uploaded_files/foto_anggota/'.$newFileName) , ['quality' => 90]);

                    /*Image::getImagine()->open(Yii::getAlias('@uploaded_files/foto_anggota/'. $id . '.jpg'))
                        ->resize(new Box('400', '500'))->save(Yii::getAlias('@uploaded_files/foto_anggota/temp/'. $id . '.jpg') , ['quality' => 90]);*/


                    //Now save file data to database
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app', 'Success Upload'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
                    $this->redirect(['update','id'=>$id]);
                    return true;
                }else {
                //Now save file data to database
                    Yii::$app->getSession()->setFlash('error', [
                        'type' => 'error',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app', 'Failed Upload'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
                    $this->redirect(['update','id'=>$id]);
                }
            }
        }
    }

    public function actionHapusFoto()
    {
        $id = Yii::$app->request->post('memberID');
        $model = Members::findOne($id);
        $path = Yii::getAlias('@uploaded_files/foto_anggota/'. $model->PhotoUrl);
        //$pathThumb = Yii::getAlias('@uploaded_files/foto_anggota/temp/'. $id . '.jpg');
        try {
            unlink($path);
            $model->PhotoUrl=null;
            $model->save(false);
            //unlink($pathThumb);
            return "1";
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

    }
    
    public function actionDetailHistori()
    {
       $id = Yii::$app->request->get('id'); 
       $modelHistori = \common\models\Modelhistory::find();
       
       
       //$searchModel = new \common\models\MasterKependudukanSearch();
       //$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
       
       $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $modelHistori,
        ]);
       
       $modelHistori->andWhere(['field_id' => $id]);
       $modelHistori->andWhere(['type' => '1']);
       $modelHistori->andWhere(['table' => 'members']);
       
        return $this->renderAjax('detailHistori', [  
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            //'rules' => $rules
        ]);
        
       //var_dump($modelHistori); 
       
    }

    public function actionMasaBerlaku()
    {
        $jenis = $_GET['jenis'];
        $registerDate = \common\components\Helpers::DateToMysqlFormat('-',$_GET['registerDate']);
        // loadMasaBerlaku
        $masaBerlaku = \common\models\JenisAnggota::findOne($jenis); // default 
        //$registerDate = date("d-m-Y");
        $endDate = date("d-m-Y");

        $endDate = \common\components\Helpers::addDayswithdate($registerDate,$masaBerlaku->MasaBerlakuAnggota); //RegisterDate.AddDays(Jumlah);


        $registerDate = \common\components\Helpers::DateTimeToViewFormat($registerDate);
        $endDate = \common\components\Helpers::DateTimeToViewFormat($endDate);

        //- loadMasaBerlaku
       echo $endDate;
    }

}
