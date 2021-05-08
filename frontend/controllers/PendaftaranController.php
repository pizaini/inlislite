<?php
/**
 * @link https://www.inlislite.perpusnas.go.id/
 * @copyright Copyright (c) 2015 Perpustakaan Nasional Republik Indonesia
 * @license https://www.inlislite.perpusnas.go.id/licences
 */

namespace frontend\controllers;

use common\components\Helpers;
use common\models\Membersonline;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

// Model
use common\models\Memberrules;
use common\models\Members;
use common\models\MemberSearch;
use common\models\MembersForm;
use common\models\JenisAnggota;
use common\models\Memberloanauthorizelocation;
use common\models\Memberloanauthorizecategory;

// Component
use common\components\MemberHelpers;
use yii\web\Response;

/**
* PendaftaranController implements the create actions for Members model.
* @author Henry <alvin_vna@yahoo.com>
*/

class PendaftaranController extends Controller
{
	public $layout = 'pendaftaran';
    public $registerDate;
    public $endDate;



	/**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
         $model = Memberrules::find()
                        ->where(['isPublish' => 1])
                        ->all();
        return $this->render('index',['model'=>$model]);
    }


    private function getMasaBerlakuAkhir(){

        // loadMasaBerlaku
        //$masaBerlaku = \common\models\JenisAnggota::findOne(1); // default 
        //$registerDate = date("d-m-Y");
        $endDate = date("Y-m-d");

        //$endDate = date('Y-m-d', strtotime('+'.$masaBerlaku->MasaBerlakuAnggota.' days'));



        /*$masaBerlakuAnggota = Yii::$app->config->get('MasaBerlakuAnggota');
        if(isset($masaBerlakuAnggota)){
             $dataMasaBerlaku = \common\models\MasaBerlakuAnggota::findOne(['ID'=>$masaBerlakuAnggota]);
             $jumlah = $dataMasaBerlaku->jumlah;
             $satuan = $dataMasaBerlaku->satuan;

                if ($satuan == "Hari")
                {
                    $endDate = date('Y-m-d', strtotime('+'.$jumlah.' days'));
                }
                else if ($satuan == "Minggu")
                {
                    $day = 7 * $jumlah;
                    $endDate = date('Y-m-d', strtotime('+'.$day.' days'));

                }
                else if ($satuan == "Bulan")
                {
                    $endDate = date('Y-m-d', strtotime('+'.$jumlah.' months'));
                }
                else if ($satuan == "Tahun")
                {
                    $endDate = date('Y-m-d', strtotime('+'.$jumlah.' years'));
                }

        }*/
        return $endDate;
    }

    public function actionAnggotaAktif()
    {

        $model = new \yii\base\DynamicModel([
            'memberNo', 'password',
        ]);

        $model->addRule(['memberNo'], 'required',['message' => 'No.Anggota tidak boleh kosong.' ])
                ->addRule('password', 'required')
                ->addRule('password', 'string',['min'=>6]);

        $model->attributeLabels(['memberNo'=>'No.Anggota']);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $resultValidate = \yii\widgets\ActiveForm::validate($model);
        }

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (($model = Members::find()->where(['MemberNo'=>$data['NoAnggota']])->one()) !== null) {
                $modelOnline = \common\models\Membersonline::find()->where(['NoAnggota'=>$data['NoAnggota']])->one();
                if($modelOnline!== null){
                    return 'already';
                }else{

                    $modelOnline = new \common\models\Membersonline;
                    $modelOnline->NoAnggota = $model->MemberNo;
                    $modelOnline->Password = sha1($data['Password']);
                    $modelOnline->Status = 'ACTIVE';
                    $modelOnline->Email = $model->Email;
                    $modelOnline->Activation_Code = session_id();
                    /* $model->CreateDate = new \yii\db\Expression('NOW()');
                     $model->CreateTerminal =\Yii::$app->request->userIP;
                     $model->UpdateDate = new \yii\db\Expression('NOW()');
                     $model->UpdateTerminal =\Yii::$app->request->userIP;*/
                    $modelOnline->save();
                    return 'sukses';
                }

            } else {
                return 'error';
            }
        }

        return $this->render('_formAktifasi', ['model'=>$model]);
    }
    /**
     * Creates a new Members.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return No.Anggota / No.Registrasi.
     */
    public function actionAnggota()
    {
        // echo'<pre>';print_r($_SESSION);die;
        $success = false;
        $model = new Members(['scenario' => 'register']);
        $membersForm = membersForm::find()
                        ->where(['Jenis_Perpustakaan_id' => Yii::$app->config->get('JenisPerpustakaan')])
                        ->asArray()->all();


        $tipeNoAnggota = Yii::$app->config->get('TipeNomorAnggota');
        if(strtolower($tipeNoAnggota) == 'otomatis'){
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
            $model->MemberNo = $memberNo;
        }elseif (strtolower($tipeNoAnggota) == 'manual'){
            // Jika manual maka akan dibuatkan nomor registasi sementara.
            // format YYMMDD99999
            $result = MemberHelpers::getMaxMemberNo(1);
            $memberNo = MemberHelpers::getNewMemberNo($result,1);
            $model->MemberNo = $memberNo;

        }

        $jenisIdentiasNIK = MemberHelpers::getJenisIdentitasNik();

        if ($model->load(Yii::$app->request->post())) {
            //Post Data
            $memloan = $_POST['Members']['locationCategory'];
            $memloancat = $_POST['Members']['collectionCategory'];
            $trans = Yii::$app->db->beginTransaction();
            $model->Fullname = strtoupper($model->Fullname);

            $model->JenisPermohonan_id = 1; // BARU
            $model->StatusAnggota_id = 1 ; //
            //$model->MasaBerlaku_id =  Yii::$app->config->get('MasaBerlakuAnggota');
            $model->RegisterDate = date('Y-m-d');
            $model->EndDate = $this->getMasaBerlakuAkhir();
            //$model->Branch_id = '37';
            $model->CreateDate = new \yii\db\Expression('NOW()');
            $model->CreateTerminal =\Yii::$app->request->userIP;
            $model->UpdateDate = new \yii\db\Expression('NOW()');
            $model->UpdateTerminal =\Yii::$app->request->userIP;

            if(strtolower($tipeNoAnggota) == 'otomatis'){
                if ($template == 4 && empty($jenisIdentiasNIK) || is_null($jenisIdentiasNIK))
                {

                    Yii::$app->getSession()->setFlash('error', [
                                'message' => Yii::t('app','Tidak dapat generate No. Anggota dengan sistem penomoran Pilihan 4. Tidak ada setting jenis identitas yang terhubung dengan data kependudukan'),]);

                    $success = false;
                    $model->addError('Error', 'Tidak dapat generate No. Anggota dengan sistem penomoran Pilihan 4. Tidak ada setting jenis identitas yang terhubung dengan data kependudukan');
                    $validate = false;

                }elseif ($template == 4 && $model->IdentityType_id != $jenisIdentiasNIK)
                {

                    Yii::$app->getSession()->setFlash('error', [

                                'message' => Yii::t('app','Tidak dapat generate No. Anggota dengan sistem penomoran Pilihan 4. Pilihan Jenis Identitas tidak sama dengan jenis identitas yang terhubung dengan data kependudukan'),
                               ]);

                    $success = false;
                    $model->addError('Error', 'Tidak dapat generate No. Anggota dengan sistem penomoran Pilihan 4. Pilihan Jenis Identitas tidak sama dengan jenis identitas yang terhubung dengan data kependudukan');
                    $validate = false;
                }elseif ($template == 4 && empty($model->IdentityNo) || is_null($model->IdentityNo))
                {

                    Yii::$app->getSession()->setFlash('error', [
                                'message' => Yii::t('app','Tidak dapat generate No. Anggota dengan sistem penomoran Pilihan 4, dengan NIK kosong'),
                                ]);

                    $success = false;
                    $model->addError('Error', 'Tidak dapat generate No. Anggota dengan sistem penomoran Pilihan 4, dengan NIK kosong');
                    $validate = false;

                }else{
                    $validate = true;
                }

                if($template == 4 && $validate == true){
                    $model->MemberNo = $model->IdentityNo; // Sett MemberNo = NIK.
                }

                if($template == 3){
                    $result = MemberHelpers::getMaxMemberNo($template,$model->Sex_id);
                    $model->MemberNo = MemberHelpers::getNewMemberNo($result,$template,$model->Sex_id);
                }
            }else{
                $validate = true;
            }

            try{;

                if ($model->save(false) && $validate) {
                    
                    $success = true;
                    $memberId = $model->getPrimaryKey();
                    // Jika Lokasi tidak null maka insert ke memberloanauthorizeLocaitons
                    if ($memloan != "") {
                        foreach ($memloan as $key => $value) {
                            $modelMemberLoanAuth = new Memberloanauthorizelocation;
                            $modelMemberLoanAuth->Member_id = $memberId;
                            $modelMemberLoanAuth->LocationLoan_id = $value;
                            $modelMemberLoanAuth->save();
                        }
                    }
                    // Jika Jenis Koleksi tidak null maka insert ke memberloanauthorizeCategory
                    if ($memloancat != "") {
                        foreach ($memloancat as $key => $value) {
                            $modelMemberLoanCat = new Memberloanauthorizecategory;
                            $modelMemberLoanCat->Member_id = $memberId;
                            $modelMemberLoanCat->CategoryLoan_id = $value;
                            $modelMemberLoanCat->save();
                        }
                    }
                    
                    $security = Yii::$app->security;
                    // Simpan Ke MemberOnline
                    $modelOnline = new \common\models\Membersonline;
                    $modelOnline->NoAnggota = $model->MemberNo;
                    $modelOnline->Password = sha1($_POST['Members']['password']);
                    $modelOnline->Status = 'ACTIVE';
                    $modelOnline->Email = $model->Email;
                    $modelOnline->Activation_Code = session_id();
                    $model->CreateDate = new \yii\db\Expression('NOW()');
                    $model->CreateTerminal =\Yii::$app->request->userIP;
                    $model->UpdateDate = new \yii\db\Expression('NOW()');
                    $model->UpdateTerminal =\Yii::$app->request->userIP;
                    $modelOnline->save();


                    /*Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Success Save'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);*/

                     $trans->commit();
                }

            }catch (CDbException $e) {


                $trans->rollback();
                $success = false;
                $model->addError('Error Saving', $e->getMessage());

            }

        }

        //var_dump($model->getErrors());
        //die;

        if($success){
             
             return $this->redirect(['view', 'id' => $model->ID]);
        }else{
            

            // Binding location and Collection fro default create
            $model->locationCategory = $this->getLocationDefault(1);
            $model->collectionCategory = $this->getCategoryCollectionDefault(1);
            $biaya = JenisAnggota::findOne(1);
            $pendaftaran = $biaya->BiayaPendaftaran;

            return $this->render('_form', [
                'model' => $model,
                'memberNo' => $memberNo,
                'membersForm'=>$membersForm,
                'pendaftaran'=>$pendaftaran
            ]);
        }
        # code...
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
            ->where('JenisAnggota_id LIKE "%' . $q .'%"')
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
     * To take initial category collection data.
     * @param  int $q [id jenis anggota]
     * @return Array Location_Library_id
     */
    public function getCategoryCollectionDefault($q = null) {
        $query = new \yii\db\Query;

        $query->select('CollectionCategory_id')
            ->from('collectioncategorysdefault')
            ->where('JenisAnggota_id LIKE "%' . $q .'%"')
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
     * Returns the data list kabupaten
     */
   public function actionKabupatenList() {
        $res = array();
        if (isset($_GET['term']) && isset($_GET['prop'])) {
             $query = new \yii\db\Query;
          
             
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
     * Displays a single Members model.
     * @param double $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        // EMAIL
        if(!is_null($model->memberOnline->Email) || !empty($model->memberOnline->Email)){

            $redaksi = 'Terima kasih telah melakukan pendaftaran di perpustakaan, ' . Yii::$app->config->get('NamaPerpustakaan') . ' <br/>';
            $redaksi .= 'Nomor Anggota : ' . $model->MemberNo . ' <br/>';
            $redaksi .= 'Nama  Anggota : ' . $model->Fullname . ' <br/>';
            $redaksi .= 'Masa Belaku Anggota : ' . Helpers::DateTimeToViewFormat($model->EndDate ) . ' <br/>';
            $redaksi .= '<br/><br/><br/> Terima Kasih';

           /* Yii::$app->mailer->compose()
                ->setFrom('inlis.pnri@gmail.com')
                ->setTo($model->memberOnline->Email)
                ->setSubject('Pendaftaran Anggota')
                ->setTextBody('Pendaftaran Anggota')
                ->setHtmlBody($redaksi)
                ->send();*/
        }

        return $this->render('view', ['model' => $model]);
    }

    public function actionEmail(){
        return Yii::$app->mailer->compose()
            ->setFrom('inlis.pnri@gmail.com')
            ->setTo('sigitnayoan@gmail.com')
            ->setSubject('Pendaftaran Anggota')
            ->setTextBody('Pendaftaran Anggota')
            ->setHtmlBody('<b>Anda telah terdaftar diperpustakaan kami.</b>')
            ->send();

    }

    public function actionCetak(){
        $model = $this->findModel(Yii::$app->request->get('memberID'));
        return $this->renderPartial('cetak', ['model' => $model]);
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
     * Returns the data list kabupaten
     */
   public function actionGetData($id,$val) {
        /* // print_r($val);die;
        // cek apakah isNik
       
       $jenisIdentitas = \common\models\base\MasterJenisIdentitas::findOne($id);
       $jenisIdentitas->IsNIK;
       $out='';
       if($jenisIdentitas->IsNIK == 1){
           // amibil data kependudukan
           //$modelKependudukan = \common\models\MasterKependudukan::find()->where(['nik'=>trim($val)])->one();
            $out = \common\Components\Nik::getNIK($val,'http://localhost/perpus/inlislite_32/api/web/v1/kependudukan?nik=');
           
       }
       //return \yii\helpers\Json::encode($modelKependudukan);
       return \yii\helpers\Json::encode($out); */
       
       // // cek apakah isNik
       
       $jenisIdentitas = \common\models\base\MasterJenisIdentitas::findOne($id);
       $jenisIdentitas->IsNIK;
       if($jenisIdentitas->IsNIK == 1){
           // amibil data kependudukan
           $modelKependudukan = \common\models\MasterKependudukan::find()->where(['nik'=>trim($val)])->one();
           
       }
       return \yii\helpers\Json::encode($modelKependudukan);
     

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


}
