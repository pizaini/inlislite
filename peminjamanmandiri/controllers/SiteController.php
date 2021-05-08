<?php
namespace peminjamanmandiri\controllers;

use common\models\MembersLoanForm;
use Yii;
use yii\web\Session;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use common\models\Collectionloanitems;
use common\models\CollectionloanitemSearch;
use leandrogehlen\querybuilder\Translator;
use common\models\LoginForm;
use common\models\Locations;
use common\models\LocationLibrary;
use common\models\Userloclibforloan;
use common\models\Users;
use common\models\Settingparameters;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\validators\EmailValidator;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Site controller
 */
class SiteController extends Controller
{
public $enableCsrfValidation = false;
   public $layout = "buku-tamu";
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            /*'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],*/
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function cekLogin()
    {
      $cookies = Yii::$app->request->cookies;
      if ($cookies->getValue('location_peminjamanmandiri_id') !== null) {
          // go to member page
          // $this->redirect(\Yii::$app->urlManager->createUrl("site/pindai-anggota"));
      } else {
          $this->redirect(\Yii::$app->urlManager->createUrl("site/login"));
      }
    }

    /**
     * Buku tamu main Index
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Locations;
        $message = "";

        $cookies = Yii::$app->request->cookies;
        // echo '<pre>'; print_r($cookies); echo '<pre>';
        if ($cookies->getValue('location_peminjamanmandiri_id') !== null) {
            // go to member page
            $this->redirect(\Yii::$app->urlManager->createUrl("site/create"));
        } else {
            $this->redirect(\Yii::$app->urlManager->createUrl("site/login"));
        }
    }

    /**
     * Buku tamu login web
     * @return mixed
     */
    public function actionLogin()
    {
        $this->layout ="buku-tamu";

        // $this->layout ="login";

        $model = new Users;
        $message = "";

        if ($model->load(Yii::$app->request->post()))
        {
            if (Yii::$app->request->post('Users')['username'] == "" || Yii::$app->request->post('Users')['username'] == "username")
            {
                $message = yii::t('app','Nama Pengguna tidak boleh kosong!');
                //"Nama Pengguna atau Kata Sandi salah!"
            }
            else if (Yii::$app->request->post('Users')['password'] == "")
            {
                $message = yii::t('app','Kata Sandi tidak boleh kosong!');
            }
            else
            {
                $dataUsers = $this->isMatch(Yii::$app->request->post('Users')['username'], Yii::$app->request->post('Users')['password']);
                // $statusUser = Userloclibforloan::findAll(['User_id' => $dataUsers['ID']]);
                $sqltatus = "SELECT ID,Name FROM location_library WHERE ID IN (
                    SELECT LocLib_id FROM userloclibforcol WHERE User_id = ".$dataUsers['ID']."
                    UNION SELECT LocLib_id FROM userloclibforloan WHERE User_id = ".$dataUsers['ID'].")";
                // matching with user database
                if ($dataUsers)
                {
                    $statusUser = Yii::$app->db->createCommand($sqltatus)->queryAll();
                    if ($statusUser == null)
                    {
                        $message = yii::t('app','Tidak ada hak akses!');
                    }
                    else
                    {
                        $cookies = Yii::$app->response->cookies;
                        $cookies->add(new \yii\web\Cookie([
                            'name' => 'usersSetLocPmnjmanMandiri',
                            'value' => $dataUsers,
                            ]));
                        $cookies->add(new \yii\web\Cookie([
                            'name' => 'usersIDPmnjmanMandiri',
                            'value' => $dataUsers['ID'],
                            ]));

                        $this->redirect(\Yii::$app->urlManager->createUrl("site/setting-locations"));
                    }
                }
                else
                {
                    $message = yii::t('app','Nama Pengguna atau Kata Sandi salah!');
                }
            }
        }

        Yii::$app->getSession()->setFlash('message', $message);
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function isMatch($username, $password)
    {
        $passwordHash = strtoupper(sha1($password));
        // $model = Users::findAll(['username' => $username, 'password' => $passwordHash]);
        $model = Users::find()->where("username = :username AND password = :passwordHash",[':passwordHash' => $passwordHash,':username' => $username ])->select(['username', 'ID'])->one();
        // $total = Users::findBySql("SELECT * FROM users where username = '$username' AND password = '$passwordHash'")->count();

        if ($model !== null) {
            return $model;
        } else {
            return false;
        }
    }

    /**
     * Buku tamu pilih lokasi
     * @return mixed
     */
    public function actionSettingLocations()
    {
        $this->layout ="buku-tamu";

        $model = new Locations;
        $message = "";

        if ($users = Yii::$app->request->cookies->getValue('usersSetLocPmnjmanMandiri')) 
        {

            $loclib = Yii::$app->db->createCommand("SELECT * FROM
                      (SELECT User_id, LocLib_id FROM userloclibforloan
                      WHERE User_id = ".$users['ID']."
                      UNION ALL
                      SELECT User_id, LocLib_id FROM userloclibforcol
                      WHERE User_id = ".$users['ID']."
                      ) a
                    GROUP BY a.LocLib_id ")
                    ->queryAll();

            foreach ($loclib as $loclib) {
                $loclibs[$loclib['LocLib_id']] = Yii::$app->db->createCommand("select ID, Name from location_library where ID = '".$loclib['LocLib_id']."'")
                    ->queryOne();

            }

            // print_r($loclibs);die;

            if ($model->load(Yii::$app->request->post())) {
                if (Yii::$app->request->post('Locations')['ID'] == "") {
                    $message = "Pilih Lokasi Terlebih Dahulu";
                } else {
                    // set peminjamanmandiri cookies
                    $cookies = Yii::$app->response->cookies;
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'location_peminjamanmandiri_id',
                        'value' => Yii::$app->request->post('Locations')['ID'],
                    ]));
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'location_detail_peminjamanmandiri',
                        'value' => LocationLibrary::find()->select(['Name', 'ID','Code','Address'])->where(['ID'=>Yii::$app->request->post('LocationLibrary')])->asArray()->one(),
                        // Yii::$app->request->post('LocationLibrary'),
                    ]));
                    // go to index
                    return $this->goHome();
                }
            }

            Yii::$app->getSession()->setFlash('message', $message);
            return $this->render('settinglocations', [
                'model' => $model,
                'loclibs' => $loclibs,
            ]);
        } 
        else 
        {
            $this->redirect('index');
        }
        
        // $loclib = Yii::$app->db->createCommand("select LocLib_id from userloclibforloan where User_id = '".$users['ID']."'")
        //         ->queryAll();
    }

    public function actionLoadSelecterLocations($idLoc)
    {
        // print_r(Locations::find(['LocationLibrary_id'=>$idLoc])->orderBy('ID')->asArray()->all()) ;
        $model = new Locations;
        echo Html::activeDropDownList($model, 'ID',
            // ArrayHelper::map(Locations::find(['LocationLibrary_id'=> $idLoc ])->select(['Name', 'ID'])->orderBy('ID')->all(), 'ID', 'Name'),
            ArrayHelper::map(Locations::find()->where('LocationLibrary_id = '.$idLoc)->select(['Name', 'ID'])->orderBy('ID')->all(), 'ID', 'Name'),
            ['prompt' => yii::t('app', "-- Silahkan pilih lokasi --"), 'class'=>'form-control']) ;
    }

    public function actionCreate()
    {
        $this->cekLogin();
      // echo '<pre>'; print_r(Yii::$app->request->cookies);
      // echo '<pre>'; print_r(Yii::$app->request->cookies->getValue('pengembalianmandiri'));die;
        //REMOVE SESSION SIRKULASI
        Yii::$app->sirkulasi->remove();
        unset($_SESSION['MemberID']);
        unset($_SESSION['NoAnggota']);

        $model                      = new \peminjamanmandiri\models\PeminjamanForm;
        //$modelSirkulasi             = new Collectionloanitems;
        $modelSirkulasi             = new \peminjamanmandiri\models\PeminjamanItemForm;


        if ($model->load(Yii::$app->request->post())) {
            // Ambil data anggota

// echo '<pre>'; print_r(Yii::$app->request->cookies);die;
// echo '<pre>'; print_r(Yii::$app->request->cookies->getValue('location_detail')['ID']);die;
// echo '<pre>'; print_r(Yii::$app->request->cookies->getValue('usersIDPmnjmanMandiri'));die;
// $isMemberCanLoanOnLocation = \common\components\SirkulasiHelpers::isMemberCanLoanOnLocation($model->noAnggota,Yii::$app->request->cookies->getValue('usersIDPmnjmanMandiri'));
// echo '<pre>'; print_r($isMemberCanLoanOnLocation);die;

            $modelAnggota              = $this->loadModelAnggota($model->noAnggota);
            if ($modelAnggota === null){
                // Anggota tidak ada
                $this->getView()->registerJs('
                    // alert("Anggota dengan nomor : '.$model->noAnggota.' tidak terdapat dalam database.");
                    $("#parent-warning-barcode").show();
                    $("#warning-scanbarcode").html("'.yii::t('app','Anggota dengan nomor : ').$model->noAnggota.yii::t('app',' tidak terdapat dalam database.').'");
                    $("#peminjamanform-noanggota").val("");
                    $("#peminjamanform-noanggota").focus();
                ');

                return $this->render('create', [
                    'model' => $model,
                ]);
                
            }else{
                //Jika Anggota Ada.
                
                /**
                 *  Periksa Rule Anggota.
                 *  
                 */
                
                $validatePelanggaran = \common\components\SirkulasiHelpers::validatePelanggaran($model->noAnggota);

                // Anggota terkena suspend Pelanggaran.
                if (date('Y-m-d') < date($validatePelanggaran))
                {
                    // Masih dalam masa suspend.
                    $this->getView()->registerJs('
                        // alert("Anggota masih terkena suspend, baru boleh melakukan peminjaman pada tanggal : '. \common\components\Helpers::DateTimeToViewFormat($validatePelanggaran)  .'");
                        $("#parent-warning-barcode").show();
                        $("#warning-scanbarcode").html("'.yii::t("app","Anggota masih terkena suspend, baru boleh melakukan peminjaman pada tanggal : "). \common\components\Helpers::DateTimeToViewFormat($validatePelanggaran)  .'");

                        $("#peminjamanform-noanggota").val("");
                        $("#peminjamanform-noanggota").focus();
                    ');

                   return $this->render('create', [
                        'model' => $model,
                    ]);

               }else{

                    
                    $isMemberSuspend = \common\components\SirkulasiHelpers::isMemberStatus($model->noAnggota,'5');

                    if ($isMemberSuspend == true)
                    {
                        //StatusAnggota Suspend
                        $this->getView()->registerJs('
                            // alert("Status anggota disuspend karena melakukan pelanggaran!");
                            $("#parent-warning-barcode").show();
                            $("#warning-scanbarcode").html("'.yii::t("app","Status anggota disuspend karena melakukan pelanggaran!").'");

                            $("#peminjamanform-noanggota").val("");
                            $("#peminjamanform-noanggota").focus();
                        ');

                       return $this->render('create', [
                                'model' => $model,
                            ]);
                    }

                    $isMemberNotActive = \common\components\SirkulasiHelpers::isMemberStatus($model->noAnggota,3);
                    if ($isMemberNotActive == false)
                    {
                        //StatusAnggota Not ACTIVE
                        $this->getView()->registerJs('
                            // alert("Status anggota tidak aktif!");
                            $("#parent-warning-barcode").show();
                            $("#warning-scanbarcode").html("'.yii::t("app","Status anggota tidak aktif!").'");

                            $("#peminjamanform-noanggota").val("");
                            $("#peminjamanform-noanggota").focus();
                        ');

                       return $this->render('create', [
                                'model' => $model,
                            ]);
                    }

                    $isMemberExpired = \common\components\SirkulasiHelpers::isMemberExpired($model->noAnggota);
                    if ($isMemberExpired == false)
                    {
                        //Anggota Expired
                        $this->getView()->registerJs('
                            // alert("Masa berlaku keanggotaan sudah habis!");
                            $("#parent-warning-barcode").show();
                            $("#warning-scanbarcode").html("'.yii::t("app","Masa berlaku keanggotaan sudah habis!").'");

                            $("#peminjamanform-noanggota").val("");
                            $("#peminjamanform-noanggota").focus();
                        ');

                       return $this->render('create', [
                                'model' => $model,
                            ]);
                    }

                    if(strtolower(\common\components\SirkulasiHelpers::isUserSuperAdmin(Yii::$app->request->cookies->getValue('usersIDPmnjmanMandiri'))) != 'superadmin'){
                        //Periksa user untuk hak lokasi peminjamannya
                        $isUserHasAccess = \common\components\SirkulasiHelpers::isUserHasAccess(Yii::$app->request->cookies->getValue('usersIDPmnjmanMandiri'));
                        if ($isUserHasAccess == false)
                        {
                            $this->getView()->registerJs('
                                $("#parent-warning-barcode").show();
                                $("#warning-scanbarcode").html("'.yii::t('app','Anggota '). Yii::$app->user->identity->username.yii::t('app',' tidak mempunyai akses melakukan entri peminjaman di lokasi!').'");
                                $("#peminjamanform-noanggota").val("");
                                $("#peminjamanform-noanggota").focus();
                            ');

                           return $this->render('create', [
                                    'model' => $model,
                                ]);
                        } 
                    }
                    
                     //Periksa member untuk hak lokasi peminjamannya
                    $isMemberCanLoanOnLocation = \common\components\SirkulasiHelpers::isMemberCanLoanOnLocationMandiri($model->noAnggota,Yii::$app->request->cookies->getValue('usersIDPmnjmanMandiri'));
                    if (empty($isMemberCanLoanOnLocation))
                    {
                         $this->getView()->registerJs('
                            // alert("Anggota tidak mempunyai akses peminjaman di lokasi ini!");
                            $("#parent-warning-barcode").show();
                            $("#warning-scanbarcode").html("'.yii::t("app","Anggota tidak mempunyai akses peminjaman di lokasi ini!").'");

                            $("#peminjamanform-noanggota").val("");
                            $("#peminjamanform-noanggota").focus();
                            ');

                         return $this->render('create', [
                            'model' => $model,
                            ]);
                    }else{
                         //Periksa jumlah pelanggaran member
                         $jmlPelanggaran = \common\components\SirkulasiHelpers::jumlahPelanggaranAnggota($model->noAnggota);
                         //jika dikelompok_pelanggaran ada.
                         $suspendmember = \common\components\SirkulasiHelpers::suspendAnggota($jmlPelanggaran);
                         if($suspendmember == 1){
                            // Suspend Anggota Otomatis Berdasarkan Kelompok Pelanggaran.
                            $modelAnggota->StatusAnggota_id = 5; // SUSPEND;
                            if($modelAnggota->save()){
                                 $this->getView()->registerJs('
                                    // alert("Anggota ini disuspend otomatis berdasarkan kelompok pelanggaran, dimana telah melakukan '. $jmlPelanggaran .'pelanggaran!");
                                    $("#parent-warning-barcode").show();
                                    $("#warning-scanbarcode").html("'.yii::t("app","Anggota ini disuspend otomatis berdasarkan kelompok pelanggaran, dimana telah melakukan "). $jmlPelanggaran .yii::t('app',' pelanggaran!').'");

                                    $("#peminjamanform-noanggota").val("");
                                    $("#peminjamanform-noanggota").focus();
                                ');

                             return $this->render('create', [
                                'model' => $model,
                                ]);
                            }
                         }else{
                             //Memenuhi semua rule
                            
                            // Periksa apakah masih ada peminjaman
                            
                            $sqlKoleksiAnggota = "SELECT cli.CollectionLoan_id, cl.NomorBarcode, cat.Title, cat.Author, cat.Publikasi, cli.LoanDate, cli.DueDate, cli.ActualReturn, cli.LateDays, cli.Collection_id" .
                                                " FROM collectionloanitems cli INNER JOIN collections cl ON cli.Collection_id = cl.ID" .
                                                " LEFT JOIN catalogs cat ON cl.Catalog_id = cat.ID" .
                                                " WHERE cli.LoanStatus = 'Loan'" .
                                                " AND cli.Member_Id ='" . $modelAnggota->ID. "' ORDER BY cli.DueDate DESC";
                             $countKoleksiLoanOutstanding = Yii::$app->db->createCommand("
                                SELECT count(*) " .
                                                " FROM collectionloanitems cli INNER JOIN collections cl ON cli.Collection_id = cl.ID" .
                                                " LEFT JOIN catalogs cat ON cl.Catalog_id = cat.ID" .
                                                " WHERE cli.LoanStatus = 'Loan'" .
                                                " AND cli.Member_Id ='" . $modelAnggota->ID. "' ORDER BY cli.DueDate DESC")->queryScalar();                       
                            

                            $koleksiLoanOutstanding = new \yii\data\SqlDataProvider([
                                'sql' => $sqlKoleksiAnggota,
                                //'totalCount' => $countKoleksiLoanOutstanding,
                            ]);

                             // LoanLocation List
                            $queryLocation = \common\models\Memberloanauthorizelocation::find();
                            $queryLocation->andFilterWhere([
                                'Member_id' => $modelAnggota->ID,
                            ]);
                            $dataProviderLocation = new \yii\data\ActiveDataProvider([
                                'query' => $queryLocation,
                            ]);
                            // End List
                            
                            // LoanCategory List
                            $queryLoanCategory = \common\models\Memberloanauthorizecategory::find();
                            $queryLoanCategory->andFilterWhere([
                                'Member_id' => $modelAnggota->ID,
                            ]);
                            $dataProviderLoanCategory = new \yii\data\ActiveDataProvider([
                                'query' => $queryLoanCategory,
                            ]);
                            // End List
                            
                             // HistoriLoan List
                            $queryHistoriLoan = \common\models\Collectionloanitems::find();
                            $queryHistoriLoan->Where([
                                'member_id' => $modelAnggota->ID,
                            ]);
                            $queryHistoriLoan->andWhere(['not', ['ActualReturn' => null]]);
                            $queryHistoriLoan->orderBy(['ID' => SORT_DESC]); 
                            $queryHistoriLoan->limit(10);

                            $dataProviderHistoriLoan = new \yii\data\ActiveDataProvider([
                                'query' => $queryHistoriLoan,
                                'pagination' => false,
                                /*'sort' =>[
                                    'defaultOrder' => [
                                        'ID' => SORT_DESC
                                    ]
                                ],*/
                            ]);


                            // Histori Loan Grupby Category
                            $sql = "SELECT `collectioncategorys`.`Code` AS `Code`, `collectioncategorys`.`Name` AS `Name`, COUNT(collectioncategorys.Code) AS `Jumlah` FROM `collections` ".
                                  "INNER JOIN `collectionloanitems` ON collections.ID=collectionloanitems.Collection_id INNER JOIN `collectioncategorys` ON collections.Category_id=collectioncategorys.ID ".
                                  " WHERE (`member_id`=".$modelAnggota->ID.") AND (NOT (`ActualReturn` IS NULL)) GROUP BY `Code`, `Name`";

                            $command = Yii::$app->db->createCommand($sql)->queryAll();

                            $count = Yii::$app->db->createCommand("select count(*) Total from (".$sql.") as t")->queryScalar();

                            
                            $dataProviderHistoriCountCategory = new \yii\data\SqlDataProvider([
                                'sql' => $sql,
                                'totalCount' => $count,
                            ]);

                            // End List

                             $membersLoanForm = MembersLoanForm::find()
                                 ->where(['Jenis_Perpustakaan_id' => Yii::$app->config->get('JenisPerpustakaan')])
                                 ->asArray()->all();
                            
                            $tab_infoanggota    = $this->renderPartial('viewDetailDataAnggota', array('model' => $modelAnggota,'membersLoanForm' =>  $membersLoanForm), true);
                            $tab_loanLocation   = $this->renderPartial('_listLokasi',
                                                array('model'=>$dataProviderLocation,'id'=>$modelAnggota->ID),true);
                            $tab_loanCategory   = $this->renderPartial('_listLoanCategory',
                                                array('model'=>$dataProviderLoanCategory,'id'=>$modelAnggota->ID),true);
                            $tab_historyLoan    = $this->renderPartial('_listHistori',
                                                array(
                                                        'model'=>$dataProviderHistoriLoan,
                                                        'modelCountCategory'=>$dataProviderHistoriCountCategory,
                                                        'id'=>$modelAnggota->ID
                                                    ),true);


                            // Simpan MemberID di Session
                            $session = new Session();
                            $session->open();
                            $session->set('MemberID', $modelAnggota->ID);
                            $session->set('NoAnggota', $model->noAnggota);
                            // Simpan MemberID di Session


                            return $this->render('indexInfoAnggota', array(
                                'tab_infoanggota'  => $tab_infoanggota,
                                'model'            => $modelSirkulasi,
                                'tab_loanLocation' => $tab_loanLocation,
                                'tab_loanCategory' => $tab_loanCategory,
                                'tab_historyLoan'  => $tab_historyLoan,
                                'noAnggota'        => $model->noAnggota,
                                'memberID'         => $modelAnggota->ID,
                                'koleksiLoanOutstanding' => $koleksiLoanOutstanding

                            )); 

                         }

                    }
                   
               }
               
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);

        }
    }


    /**
     * Displays a single Collectionloanitems model.
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
     * Save a new peminjaman
     * @return mixed
     */
    public function actionSimpan(){


        // Cetak Struk Peminjaman

        $isCetakSlipPeminjaman = Yii::$app->config->get('IsCetakSlipPeminjaman'); 
       /* if ((bool)$isCetakSlipPeminjaman)
        {
            // Simpan MemberID di Session
            $sessionCetak = new Session();
            $sessionCetak->open();

            $sessionCetak->set('print', ['id'=>'1','transactionID'=>'16010700001']);
            /*$sessionCetak->set('transactionID', '16010700001');

            // Simpan MemberID di Session
            //echo "string";
        }*/


        //return $this->redirect('create');
        //die;

        $modelCollectionLoan   = new \common\models\Collectionloans;
        /*$daftarItem = Yii::$app->sirkulasi->getItem();
        foreach ($daftarItem as $item){
            echo trim($item['NomorBarcode']);
        }
        die;*/
        // echo '<pre>';print_r(Yii::$app->request->post());echo '</pre>';

        if (Yii::$app->request->post()) {


            $daftarItem = Yii::$app->sirkulasi->getItem();
            $LocationLibrary_id = Yii::$app->request->cookies->getValue('location_detail_peminjamanmandiri');

            $trans = Yii::$app->db->beginTransaction();
            try{
                // get session MemberID
                $MemberID = isset($_SESSION['MemberID']) ? $_SESSION['MemberID'] : null;
                $TransactionID = \common\components\SirkulasiHelpers::generateNewID(date("Y-m-d"));
                

                // get List Item
                $daftarItem = Yii::$app->sirkulasi->getItem();

                $modelCollectionLoan->ID              = $TransactionID;
                $modelCollectionLoan->Member_id       = $MemberID;
                $modelCollectionLoan->CollectionCount = count($daftarItem);
                /////////////
                $modelCollectionLoan->LocationLibrary_id = $LocationLibrary_id['ID'];
                /////////////
                
                 if ($modelCollectionLoan->save()) {
                    //Simpan Ke CollectionLoanItems
                    foreach ($daftarItem as $item){
                        $modelCollectionLoanItems   = new \common\models\Collectionloanitems;
                        $modelcollections           = \common\components\SirkulasiHelpers::loadModelKoleksi($item['NomorBarcode']);


                        $modelCollectionLoanItems->CollectionLoan_id   = $TransactionID;
                        //$modelCollectionLoanItems->NoBarcode           = trim($item['NomorBarcode']);
                        $modelCollectionLoanItems->member_id           = $MemberID;
                        $modelCollectionLoanItems->Collection_id       = $modelcollections->ID;
                        $modelCollectionLoanItems->LoanStatus          ="Loan";

                       if (isset($item['TglPinjam'])) $modelCollectionLoanItems->LoanDate = $item['TglPinjam'];
                       if (isset($item['TglKembali'])) $modelCollectionLoanItems->DueDate = $item['TglKembali'];

                       if($modelCollectionLoanItems->save()){
                            $modelcollections->Status_id = '5'; //Dipinjam
                            $modelcollections->JumlahEksemplar = '1';
                            $modelcollections->IsVerified = '0';
                            $modelcollections->BookingMemberID = NULL; // agar status di opac menjadi tidak dipesan
                            $modelcollections->BookingExpiredDate = NULL; // agar status di opac menjadi tidak dipesan
                            $modelcollections->save(false);
                       }
                        
                    }
                 }
                 // commit transaction
                $trans->commit();

                // Yii::$app->getSession()->setFlash('success', [
                //         'type' => 'info',
                //         'duration' => 500,
                //         'icon' => 'fa fa-info-circle',
                //         'message' => Yii::t('app','Transaksi peminjaman berhasil disimpanssss.'),
                //         'title' => 'Info',
                //         'positonY' => Yii::$app->params['flashMessagePositionY'],
                //         'positonX' => Yii::$app->params['flashMessagePositionX']
                // ]);

                // Yii::$app->getSession()->setFlash('message', Html::encode($message));
                // Cetak Struk Peminjaman

                $isCetakSlipPeminjaman = Yii::$app->config->get('IsCetakSlipPeminjaman'); 
                if ((bool)$isCetakSlipPeminjaman)
                {
                    // Simpan MemberID di Session
                    $sessionCetak = new Session();
                    $sessionCetak->open();
                    $sessionCetak->set('print', ['id'=>'1','transactionID'=>$TransactionID]);

                    // Simpan MemberID di Session
                    echo "string";
                }


                return $this->redirect(['detail-peminjaman','noanggota'=>$_SESSION['NoAnggota'],'notransaksi'=>$TransactionID]);

                
            }catch (CDbException $e) {
                $trans->rollback();
                $success = false;
                $modelCollectionLoan->addError('Error Saving', $e->getMessage());
            }

        }else{
            return $this->goBack();
        }

    }

    /**
     * Creates a new Collectionloanitems model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    

    /**
     * [actionDetailPeminjaman description]
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function actionDetailPeminjaman($noanggota,$notransaksi)
    {
        $model                     = new \backend\models\PeminjamanForm;
        // Ambil data anggota
        $modelAnggota              = $this->loadModelAnggota($noanggota);
        $modelSirkulasi             = new \backend\models\PeminjamanItemForm;

         //Memenuhi semua rule
        
        // Periksa apakah masih ada peminjaman
        
        $sqlKoleksiAnggota = "SELECT cli.CollectionLoan_id, cl.NomorBarcode, cat.Title, cat.Author, cat.Publisher, cli.LoanDate, cli.DueDate, cli.ActualReturn, cli.LateDays, cli.Collection_id" .
                            " FROM collectionloanitems cli INNER JOIN collections cl ON cli.Collection_id = cl.ID" .
                            " LEFT JOIN catalogs cat ON cl.Catalog_id = cat.ID" .
                            " WHERE cli.LoanStatus = 'Loan'" .
                            " AND cli.Member_Id ='" . $modelAnggota->ID. 
                            "' AND cli.CollectionLoan_id = '" . $notransaksi.   // Perubahan detail peminjaman hanya ditampilkan current transaction
                            "' ORDER BY cli.DueDate DESC";

        // $sqlKoleksiCurrentTransaksi = "SELECT cli.CollectionLoan_id, cl.NomorBarcode, cat.Title, cat.Author, cat.Publisher, cli.LoanDate, cli.DueDate, cli.ActualReturn, cli.LateDays, cli.Collection_id" .
        //                     " FROM collectionloanitems cli INNER JOIN collections cl ON cli.Collection_id = cl.ID" .
        //                     " LEFT JOIN catalogs cat ON cl.Catalog_id = cat.ID" .
        //                     " WHERE cli.LoanStatus = 'Loan'" .
        //                     " 
        //                     AND cli.Member_Id ='" . $modelAnggota->ID. "' 
        //                     AND cli.CollectionLoan_id ='" . $notransaksi. "' 

        //                     ORDER BY cli.DueDate DESC";

         $countKoleksiLoanOutstanding = Yii::$app->db->createCommand("
            SELECT count(*) " .
                            " FROM collectionloanitems cli INNER JOIN collections cl ON cli.Collection_id = cl.ID" .
                            " LEFT JOIN catalogs cat ON cl.Catalog_id = cat.ID" .
                            " WHERE cli.LoanStatus = 'Loan'" .
                            " AND cli.Member_Id ='" . $modelAnggota->ID. 
                            "' AND cli.CollectionLoan_id = '" . $notransaksi.   // Perubahan detail peminjaman hanya ditampilkan current transaction
                            "' ORDER BY cli.DueDate DESC")->queryScalar();                       
        

        $koleksiLoanOutstanding = new \yii\data\SqlDataProvider([
            'sql' => $sqlKoleksiAnggota,
            //'totalCount' => $countKoleksiLoanOutstanding,
        ]);

        // $koleksiLoanCurrentTransaction = new \yii\data\SqlDataProvider([
        //     'sql' => $sqlKoleksiCurrentTransaksi,
        //     //'totalCount' => $countKoleksiLoanOutstanding,
        // ]);

         // LoanLocation List
        $queryLocation = \common\models\Memberloanauthorizelocation::find();
        $queryLocation->andFilterWhere([
            'Member_id' => $modelAnggota->ID,
        ]);
        $dataProviderLocation = new \yii\data\ActiveDataProvider([
            'query' => $queryLocation,
        ]);
        // End List
        
        // LoanCategory List
        $queryLoanCategory = \common\models\Memberloanauthorizecategory::find();
        $queryLoanCategory->andFilterWhere([
            'Member_id' => $modelAnggota->ID,
        ]);
        $dataProviderLoanCategory = new \yii\data\ActiveDataProvider([
            'query' => $queryLoanCategory,
        ]);
        // End List
        
         // HistoriLoan List
        $queryHistoriLoan = \common\models\Collectionloanitems::find();
        $queryHistoriLoan->Where([
            'member_id' => $modelAnggota->ID,
        ]);
        $queryHistoriLoan->andWhere(['not', ['ActualReturn' => null]]);
        $queryHistoriLoan->orderBy(['ID' => SORT_DESC]); 
        $queryHistoriLoan->limit(10);

        $dataProviderHistoriLoan = new \yii\data\ActiveDataProvider([
            'query' => $queryHistoriLoan,
            'pagination' => false,
            /*'sort' =>[
                'defaultOrder' => [
                    'ID' => SORT_DESC
                ]
            ],*/
        ]);


        // Histori Loan Grupby Category
        $sql = "SELECT `collectioncategorys`.`Code` AS `Code`, `collectioncategorys`.`Name` AS `Name`, COUNT(collectioncategorys.Code) AS `Jumlah` FROM `collections` ".
              "INNER JOIN `collectionloanitems` ON collections.ID=collectionloanitems.Collection_id INNER JOIN `collectioncategorys` ON collections.Category_id=collectioncategorys.ID ".
              " WHERE (`member_id`=".$modelAnggota->ID.") AND (NOT (`ActualReturn` IS NULL)) GROUP BY `Code`, `Name`";

        $command = Yii::$app->db->createCommand($sql)->queryAll();

        $count = Yii::$app->db->createCommand("select count(*) Total from (".$sql.") as t")->queryScalar();

        
        $dataProviderHistoriCountCategory = new \yii\data\SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
        ]);

        // End List

         $membersLoanForm = MembersLoanForm::find()
             ->where(['Jenis_Perpustakaan_id' => Yii::$app->config->get('JenisPerpustakaan')])
             ->asArray()->all();
        
        $tab_infoanggota    = $this->renderPartial('viewDetailDataAnggota', array('model' => $modelAnggota,'membersLoanForm' =>  $membersLoanForm), true);
        $tab_loanLocation   = $this->renderPartial('_listLokasi',
                            array('model'=>$dataProviderLocation,'id'=>$modelAnggota->ID),true);
        $tab_loanCategory   = $this->renderPartial('_listLoanCategory',
                            array('model'=>$dataProviderLoanCategory,'id'=>$modelAnggota->ID),true);
        $tab_historyLoan    = $this->renderPartial('_listHistori',
                            array(
                                    'model'=>$dataProviderHistoriLoan,
                                    'modelCountCategory'=>$dataProviderHistoriCountCategory,
                                    'id'=>$modelAnggota->ID
                                ),true);


        // Simpan MemberID di Session
        // $session = new Session();
        // $session->open();
        // $session->set('MemberID', $modelAnggota->ID);
        // $session->set('NoAnggota', $noanggota);
        // Simpan MemberID di Session


        return $this->render('_indexDetailPeminjaman', array(
            'tab_infoanggota'  => $tab_infoanggota,
            'model'            => $modelSirkulasi,
            'model2'            => $model,
            'tab_loanLocation' => $tab_loanLocation,
            'tab_loanCategory' => $tab_loanCategory,
            'tab_historyLoan'  => $tab_historyLoan,
            'noAnggota'        => $noanggota,
            'memberID'         => $modelAnggota->ID,
            'koleksiLoanOutstanding' => $koleksiLoanOutstanding,
            // 'koleksiLoanCurrentTransaction' => $koleksiLoanCurrentTransaction,
            'transactionID'    => $notransaksi

        )); 


    }

   /**
     * Creates a new Collectionloanitems model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateSusulan()
    {
        

        //REMOVE SESSION SIRKULASI
        Yii::$app->sirkulasi->remove();
        unset($_SESSION['MemberID']);
        unset($_SESSION['NoAnggota']);

        $model                      = new \backend\models\PeminjamanForm;
        //$modelSirkulasi             = new Collectionloanitems;
        $modelSirkulasi             = new \backend\models\PeminjamanItemForm;


        if ($model->load(Yii::$app->request->post())) {

            // Ambil data anggota
            $modelAnggota              = $this->loadModelAnggota($model->noAnggota);
            if ($modelAnggota === null){
                // Anggota tidak ada
                $this->getView()->registerJs('
                    // alert("Anggota dengan nomor : '.$model->noAnggota.' tidak terdapat dalam database.");
                    $("#parent-warning-barcode").show();
                    $("#warning-scanbarcode").html("Anggota dengan nomor : '.$model->noAnggota.' tidak terdapat dalam database.");

                    $("#peminjamanform-noanggota").val("");
                    $("#peminjamanform-noanggota").focus();
                ');

                return $this->render('create-susulan', [
                    'model' => $model,
                ]);
                
            }else{
                //Jika Anggota Ada.
                
                /**
                 *  Periksa Rule Anggota.
                 *  
                 */
                
                $validatePelanggaran = \common\components\SirkulasiHelpers::validatePelanggaran($model->noAnggota);

                // Anggota terkena suspend Pelanggaran.
                if (date('Y-m-d') < date($validatePelanggaran))
                {
                    // Masih dalam masa suspend.
                    $this->getView()->registerJs('
                        // alert("Anggota masih terkena suspend, baru boleh melakukan peminjaman pada tanggal : '. \common\components\Helpers::DateTimeToViewFormat($validatePelanggaran)  .'");
                        $("#parent-warning-barcode").show();
                        $("#warning-scanbarcode").html("Anggota masih terkena suspend, baru boleh melakukan peminjaman pada tanggal : '. \common\components\Helpers::DateTimeToViewFormat($validatePelanggaran)  .'");

                        $("#peminjamanform-noanggota").val("");
                        $("#peminjamanform-noanggota").focus();
                    ');

                   return $this->render('create-susulan', [
                        'model' => $model,
                    ]);

               }else{

                    
                    $isMemberSuspend = \common\components\SirkulasiHelpers::isMemberStatus($model->noAnggota,'5');

                    if ($isMemberSuspend == true)
                    {
                        //StatusAnggota Suspend
                        $this->getView()->registerJs('
                            // alert("Status anggota disuspend karena melakukan pelanggaran!");
                            $("#parent-warning-barcode").show();
                            $("#warning-scanbarcode").html("Status anggota disuspend karena melakukan pelanggaran!");

                            $("#peminjamanform-noanggota").val("");
                            $("#peminjamanform-noanggota").focus();
                        ');

                       return $this->render('create-susulan', [
                                'model' => $model,
                            ]);
                    }

                    $isMemberNotActive = \common\components\SirkulasiHelpers::isMemberStatus($model->noAnggota,3);
                    if ($isMemberNotActive == false)
                    {
                        //StatusAnggota Not ACTIVE
                        $this->getView()->registerJs('
                            // alert("Status anggota tidak aktif!");
                            $("#parent-warning-barcode").show();
                            $("#warning-scanbarcode").html("'.yii::t("app","Status anggota tidak aktif!").'");

                            $("#peminjamanform-noanggota").val("");
                            $("#peminjamanform-noanggota").focus();
                        ');

                       return $this->render('create-susulan', [
                                'model' => $model,
                            ]);
                    }

                    $isMemberExpired = \common\components\SirkulasiHelpers::isMemberExpired($model->noAnggota);
                    if ($isMemberExpired == false)
                    {
                        //Anggota Expired
                        $this->getView()->registerJs('
                            // alert("Masa berlaku keanggotaan sudah habis!");
                            $("#parent-warning-barcode").show();
                            $("#warning-scanbarcode").html("'.yii::t("app","Masa berlaku keanggotaan sudah habis!").'");

                            $("#peminjamanform-noanggota").val("");
                            $("#peminjamanform-noanggota").focus();
                        ');

                       return $this->render('create-susulan', [
                                'model' => $model,
                            ]);
                    }

                   

                    if(strtolower(Yii::$app->user->identity->getRoleName()) != 'superadmin'){
                        //Periksa user untuk hak lokasi peminjamannya
                        $isUserHasAccess = \common\components\SirkulasiHelpers::isUserHasAccess(Yii::$app->user->identity->id);
                        if ($isUserHasAccess == false)
                        {
                            $this->getView()->registerJs('
                                // alert("User '. Yii::$app->user->identity->username.' tidak mempunyai akses melakukan entri peminjaman di semua lokasi!");
                                $("#parent-warning-barcode").show();
                                $("#warning-scanbarcode").html("User '. Yii::$app->user->identity->username.' tidak mempunyai akses melakukan entri peminjaman di semua lokasi!");

                                $("#peminjamanform-noanggota").val("");
                                $("#peminjamanform-noanggota").focus();
                            ');

                           return $this->render('create-susulan', [
                                    'model' => $model,
                                ]);
                        } 
                    }
                    
                     //Periksa member untuk hak lokasi peminjamannya
                    $isMemberCanLoanOnLocation = \common\components\SirkulasiHelpers::isMemberCanLoanOnLocation($model->noAnggota,Yii::$app->user->identity->id);
                    if ($isMemberCanLoanOnLocation == false)
                    {
                         $this->getView()->registerJs('
                            // alert("Anggota tidak mempunyai akses peminjaman di lokasi ini!");
                            $("#parent-warning-barcode").show();
                            $("#warning-scanbarcode").html("Anggota tidak mempunyai akses peminjaman di lokasi ini!");

                            $("#peminjamanform-noanggota").val("");
                            $("#peminjamanform-noanggota").focus();
                            ');

                         return $this->render('create-susulan', [
                            'model' => $model,
                            ]);
                    }else{
                         //Periksa jumlah pelanggaran member
                         $jmlPelanggaran = \common\components\SirkulasiHelpers::jumlahPelanggaranAnggota($model->noAnggota);
                         //jika dikelompok_pelanggaran ada.
                         $suspendmember = \common\components\SirkulasiHelpers::suspendAnggota($jmlPelanggaran);
                         if($suspendmember == 1){
                            // Suspend Anggota Otomatis Berdasarkan Kelompok Pelanggaran.
                            $modelAnggota->StatusAnggota_id = 5; // SUSPEND;
                            if($modelAnggota->save()){
                                 $this->getView()->registerJs('
                                    // alert("Anggota ini disuspend otomatis berdasarkan kelompok pelanggaran, dimana telah melakukan '. $jmlPelanggaran .'pelanggaran!");
                                    $("#parent-warning-barcode").show();
                                    $("#warning-scanbarcode").html("Anggota ini disuspend otomatis berdasarkan kelompok pelanggaran, dimana telah melakukan '. $jmlPelanggaran .'pelanggaran!");
                            
                                    $("#peminjamanform-noanggota").val("");
                                    $("#peminjamanform-noanggota").focus();
                                ');

                             return $this->render('create-susulan', [
                                'model' => $model,
                                ]);
                            }
                         }else{
                             //Memenuhi semua rule
                            
                            // Periksa apakah masih ada peminjaman
                            
                            $sqlKoleksiAnggota = "SELECT cli.CollectionLoan_id, cl.NomorBarcode, cat.Title, cat.Author, cat.Publisher, cli.LoanDate, cli.DueDate, cli.ActualReturn, cli.LateDays, cli.Collection_id" .
                                                " FROM collectionloanitems cli INNER JOIN collections cl ON cli.Collection_id = cl.ID" .
                                                " LEFT JOIN catalogs cat ON cl.Catalog_id = cat.ID" .
                                                " WHERE cli.LoanStatus = 'Loan'" .
                                                " AND cli.Member_Id ='" . $modelAnggota->ID. "' ORDER BY cli.DueDate DESC";

                            $countKoleksiLoanOutstanding = Yii::$app->db->createCommand("
                                SELECT count(*) " .
                                                " FROM collectionloanitems cli INNER JOIN collections cl ON cli.Collection_id = cl.ID" .
                                                " LEFT JOIN catalogs cat ON cl.Catalog_id = cat.ID" .
                                                " WHERE cli.LoanStatus = 'Loan'" .
                                                " AND cli.Member_Id ='" . $modelAnggota->ID. "' ORDER BY cli.DueDate DESC")->queryScalar();

                            $koleksiLoanOutstanding = new \yii\data\SqlDataProvider([
                                'sql' => $sqlKoleksiAnggota,
                               // 'totalCount' => $countKoleksiLoanOutstanding,
                            ]);

                             // LoanLocation List
                            $queryLocation = \common\models\Memberloanauthorizelocation::find();
                            $queryLocation->andFilterWhere([
                                'Member_id' => $modelAnggota->ID,
                            ]);
                            $dataProviderLocation = new \yii\data\ActiveDataProvider([
                                'query' => $queryLocation,
                            ]);
                            // End List
                            
                            // LoanCategory List
                            $queryLoanCategory = \common\models\Memberloanauthorizecategory::find();
                            $queryLoanCategory->andFilterWhere([
                                'Member_id' => $modelAnggota->ID,
                            ]);
                            $dataProviderLoanCategory = new \yii\data\ActiveDataProvider([
                                'query' => $queryLoanCategory,
                            ]);
                            // End List
                            
                             // HistoriLoan List
                            $queryHistoriLoan = \common\models\Collectionloanitems::find();
                            $queryHistoriLoan->Where([
                                'member_id' => $modelAnggota->ID,
                            ]);
                            $queryHistoriLoan->andWhere(['not', ['ActualReturn' => null]]);
                            $queryHistoriLoan->orderBy(['ID' => SORT_DESC]); 
                            $queryHistoriLoan->limit(10);

                            $dataProviderHistoriLoan = new \yii\data\ActiveDataProvider([
                                'query' => $queryHistoriLoan,
                                'pagination' => false,
                                /*'sort' =>[
                                    'defaultOrder' => [
                                        'ID' => SORT_DESC
                                    ]
                                ],*/
                            ]);


                            // Histori Loan Grupby Category
                            $sql = "SELECT `collectioncategorys`.`Code` AS `Code`, `collectioncategorys`.`Name` AS `Name`, COUNT(collectioncategorys.Code) AS `Jumlah` FROM `collections` ".
                                  "INNER JOIN `collectionloanitems` ON collections.ID=collectionloanitems.Collection_id INNER JOIN `collectioncategorys` ON collections.Category_id=collectioncategorys.ID ".
                                  " WHERE (`member_id`=".$modelAnggota->ID.") AND (NOT (`ActualReturn` IS NULL)) GROUP BY `Code`, `Name`";

                            $command = Yii::$app->db->createCommand($sql)->queryAll();

                            $count = Yii::$app->db->createCommand("select count(*) Total from (".$sql.") as t")->queryScalar();

                            $dataProviderHistoriCountCategory = new \yii\data\SqlDataProvider([
                                'sql' => $sql,
                                'totalCount' => $count,
                            ]);

                            // End List
                            
                            $membersLoanForm = MembersLoanForm::find()
                            ->where(['Jenis_Perpustakaan_id' => Yii::$app->config->get('JenisPerpustakaan')])
                            ->asArray()->all();
                            
                            // $tab_infoanggota    = $this->renderPartial('viewDetailDataAnggota', array('model' => $modelAnggota), true);
                            $tab_infoanggota    = $this->renderPartial('viewDetailDataAnggota', array('model' => $modelAnggota,'membersLoanForm' =>  $membersLoanForm), true);

                            
                            $tab_loanLocation   = $this->renderPartial('_listLokasi',
                                                array('model'=>$dataProviderLocation,'id'=>$modelAnggota->ID),true);
                            $tab_loanCategory   = $this->renderPartial('_listLoanCategory',
                                                array('model'=>$dataProviderLoanCategory,'id'=>$modelAnggota->ID),true);
                            $tab_historyLoan    = $this->renderPartial('_listHistori',
                                                array(
                                                        'model'=>$dataProviderHistoriLoan,
                                                        'modelCountCategory'=>$dataProviderHistoriCountCategory,
                                                        'id'=>$modelAnggota->ID
                                                    ),true);


                            // Simpan MemberID di Session
                            $session = new Session();
                            $session->open();
                            $session->set('MemberID', $modelAnggota->ID);
                            $session->set('NoAnggota', $model->noAnggota);
                            // Simpan MemberID di Session

                            return $this->render('indexInfoAnggotaSusulan', array(
                                'tab_infoanggota'  => $tab_infoanggota,
                                'model'            => $modelSirkulasi,
                                'tab_loanLocation' => $tab_loanLocation,
                                'tab_loanCategory' => $tab_loanCategory,
                                'tab_historyLoan'  => $tab_historyLoan,
                                'noAnggota'        => $model->noAnggota,
                                'memberID'         => $modelAnggota->ID,
                                'koleksiLoanOutstanding' => $koleksiLoanOutstanding 
                            )); 

                         }

                    }
                   
               }
               
            }
        } else {
            return $this->render('create-susulan', [
                'model' => $model,
            ]);

        }
        
    }

    /**
     * Finds the Collectionloanitems model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param double $id
     * @return Collectionloanitems the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Collectionloanitems::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionPrintKuitansi(){
        $transactionID = isset($_GET['transactionID']) ? $_GET['transactionID'] : null;
        //var_dump($transactionID);
        //echo $transactionID['transactionID'];
        if($transactionID != ""){
             $model = \common\models\Collectionloans::find()->where(['ID' => $transactionID])->all();
             return $this->renderAjax('kuitansi', array(
                                'collectionLoan_id'  => $transactionID,
                                'model'            => $model,
                            )); 

        }

        //return $this->render('kuitansi'); 


       
    }

    public function actionViewKoleksi()
    {
        if (Yii::$app->request->post()) 
        {
            $NomorBarcode = trim($_POST["NomorBarcode"]);
            $NoAnggota = trim($_POST["NoAnggota"]);
            $memberID = trim($_POST["memberID"]);
            $TglTransaksi = trim($_POST["TglTransaksi"]);

            // echo'<pre>';print_r(Yii::$app->config->get('PeminjamanLewatJatuhTempo'));die;
            if(Yii::$app->config->get('PeminjamanLewatJatuhTempo') == '1'){
                $cekJatuhTempo = CollectionLoanItems::find()->select('DueDate')->where(['Member_id' => $memberID, 'LoanStatus' => 'Loan'])->one();
                if($cekJatuhTempo && $cekJatuhTempo->DueDate <= date('Y-m-d H:i:s')){
                    throw new \yii\web\HttpException(404, yii::t('app','Anggota ini masih meminjam koleksi yang sudah lewat dari jatuh tempo!'));
                }else{
                    if ($TglTransaksi == "")
                    {
                        $LoanDate = date('Y-m-d');
                    }
                    else
                    {
                        $LoanDate = \common\components\Helpers::DateToMysqlFormat('-',$TglTransaksi);
                    }
                    if(isset($NomorBarcode) && $NomorBarcode != "")
                    {
                        $model              = \common\components\SirkulasiHelpers::loadModelKoleksi($NomorBarcode);

                        $resultIsCanLoanOnLocation = \common\components\SirkulasiHelpers::IsMemberCanLoanOnItem($NoAnggota, $NomorBarcode);


                        if ((bool)$resultIsCanLoanOnLocation == false)
                        {
                            throw new \yii\web\HttpException(404, yii::t('app','Anggota tidak mempunyai akses peminjaman atas item ini!'));
                        }
                        else
                        {
                            if($model->Status_id !=  "1")
                            { // TERSEDIA
                                throw new \yii\web\HttpException(404, yii::t('app','Item ini masih dipinjam atau tidak tersedia!'));
                            }
                            elseif ($model->Rule_id != "1") 
                            {
                                # code...
                                 throw new \yii\web\HttpException(404, yii::t('app','Item ini tidak dapat dipinjam!'));
                            }
                            else
                            {

                                ///////////////////////////////////////////////////////////////////////
                                
                                $GetHari = \DateTime::createFromFormat("Y-m-d", $LoanDate);
                                // echo $GetHari->format("N");die;
                                $cekHariKatagoryDapatDipinjam = \common\models\Collectioncategorysloanhari::find()->select('Category_id')->joinWith('peraturanPeminjamanHari')->where(['peraturan_peminjaman_hari.DayIndex'=> $GetHari->format("N") ])->asArray()->all();  
                                // $isExistDipinjam = $cekHariKatagoryDapatDipinjam ? array_search($model->Category_id, $cekHariKatagoryDapatDipinjam) : null;
                                $isExistDipinjam = $cekHariKatagoryDapatDipinjam ? \common\components\SirkulasiHelpers::searchArrayByKeyAndValue($cekHariKatagoryDapatDipinjam, 'Category_id', $model->Category_id) : null;
                                if ($cekHariKatagoryDapatDipinjam && !$isExistDipinjam) {

                                    throw new \yii\web\HttpException(404, yii::t('app',"Item ini tidak dapat dipinjam hari ini!"));

                                    return;
                                }

                                ///////////////////////////////////////////////////////////////////////


                                //return $model->catalog->Title;
                                //Yii::$app->sirkulasi->remove();

                                // Get Tanggal Kembali
                                $tglKembali = \common\components\SirkulasiHelpers::GetTanggalKembali($NoAnggota, $LoanDate, $model->NomorBarcode);
                                if($tglKembali == 0)
                                {
                                    $tglKembali = date('Y-m-d');
                                }
                                
                                
                                $data = [
                                    'NomorBarcode' => $model->NomorBarcode,
                                    'Title' =>  $model->catalog->Title,
                                    'Penerbit' =>  $model->catalog->Publisher,
                                    'TglPinjam'=>$LoanDate,
                                    'TglKembali'=>$tglKembali
                                  ];

                                
                                
                                // periksa dahulu 
                                if(Yii::$app->sirkulasi->checkItem($model->NomorBarcode))
                                {
                                    // Jika ada
                                    throw new \yii\web\HttpException(404, yii::t('app','Item dengan No.barcode : ').$model->NomorBarcode.yii::t('app',' sudah ada di Keranjang Peminjaman!'));
                                }
                                else
                                {

                                    $countItem = count(Yii::$app->sirkulasi->getItem());
                                    $maksLoan = \common\components\SirkulasiHelpers::getMaksJumlahPeminjaman($memberID, $model->ID);

                                    if ($countItem >= $maksLoan)
                                    {
                                        throw new \yii\web\HttpException(404, yii::t('app','Peminjaman anda melewati quota!'));
                                    }else{
                                     // menambah data
                                     Yii::$app->sirkulasi->addItem($data);
                                    }
                                    
                                }


                               

                                // mendapatkan data
                                $daftarItem = Yii::$app->sirkulasi->getItem();
                                return  $this->renderAjax('_listKoleksi',
                                                        array(
                                                                'daftarItem'=>$daftarItem,
                                                                'n' => 1,
                                                            ),true);

                                /*foreach($daftarItem as $item){
                                    echo($item['NomorBarcode']);
                                    echo($item['Title']);
                                }*/

                            }
                        }
                       // return true;
                    }
                    else
                    {
                         throw new \yii\web\HttpException(404, yii::t('app','Nomor Barcode tidak boleh kosong.'));
                    }
                }
            }else{
                if ($TglTransaksi == "")
                {
                    $LoanDate = date('Y-m-d');
                }
                else
                {
                    $LoanDate = \common\components\Helpers::DateToMysqlFormat('-',$TglTransaksi);
                }
                if(isset($NomorBarcode) && $NomorBarcode != "")
                {
                    $model              = \common\components\SirkulasiHelpers::loadModelKoleksi($NomorBarcode);

                    $resultIsCanLoanOnLocation = \common\components\SirkulasiHelpers::IsMemberCanLoanOnItem($NoAnggota, $NomorBarcode);


                    if ((bool)$resultIsCanLoanOnLocation == false)
                    {
                        throw new \yii\web\HttpException(404, yii::t('app','Anggota tidak mempunyai akses peminjaman atas item ini!'));
                    }
                    else
                    {
                        if($model->Status_id !=  "1")
                        { // TERSEDIA
                            throw new \yii\web\HttpException(404, yii::t('app','Item ini masih dipinjam atau tidak tersedia!'));
                        }
                        elseif ($model->Rule_id != "1") 
                        {
                            # code...
                             throw new \yii\web\HttpException(404, yii::t('app','Item ini tidak dapat dipinjam!'));
                        }
                        else
                        {

                            ///////////////////////////////////////////////////////////////////////
                            
                            $GetHari = \DateTime::createFromFormat("Y-m-d", $LoanDate);
                            // echo $GetHari->format("N");die;
                            $cekHariKatagoryDapatDipinjam = \common\models\Collectioncategorysloanhari::find()->select('Category_id')->joinWith('peraturanPeminjamanHari')->where(['peraturan_peminjaman_hari.DayIndex'=> $GetHari->format("N") ])->asArray()->all();  
                            // $isExistDipinjam = $cekHariKatagoryDapatDipinjam ? array_search($model->Category_id, $cekHariKatagoryDapatDipinjam) : null;
                            $isExistDipinjam = $cekHariKatagoryDapatDipinjam ? \common\components\SirkulasiHelpers::searchArrayByKeyAndValue($cekHariKatagoryDapatDipinjam, 'Category_id', $model->Category_id) : null;
                            if ($cekHariKatagoryDapatDipinjam && !$isExistDipinjam) {

                                throw new \yii\web\HttpException(404, yii::t('app',"Item ini tidak dapat dipijam hari ini! "));

                                return;
                            }

                            ///////////////////////////////////////////////////////////////////////


                            //return $model->catalog->Title;
                            //Yii::$app->sirkulasi->remove();

                            // Get Tanggal Kembali
                            $tglKembali = \common\components\SirkulasiHelpers::GetTanggalKembali($NoAnggota, $LoanDate, $model->NomorBarcode);
                            if($tglKembali == 0)
                            {
                                $tglKembali = date('Y-m-d');
                            }
                            
                            
                            $data = [
                                'NomorBarcode' => $model->NomorBarcode,
                                'Title' =>  $model->catalog->Title,
                                'Penerbit' =>  $model->catalog->Publisher,
                                'TglPinjam'=>$LoanDate,
                                'TglKembali'=>$tglKembali
                              ];

                            
                            
                            // periksa dahulu 
                            if(Yii::$app->sirkulasi->checkItem($model->NomorBarcode))
                            {
                                // Jika ada
                                throw new \yii\web\HttpException(404, yii::t('app','Item dengan No.barcode : ').$model->NomorBarcode.yii::t('app',' sudah ada di Keranjang Peminjaman!'));
                            }
                            else
                            {

                                $countItem = count(Yii::$app->sirkulasi->getItem());
                                $maksLoan = \common\components\SirkulasiHelpers::getMaksJumlahPeminjaman($memberID, $model->ID);

                                if ($countItem >= $maksLoan)
                                {
                                    throw new \yii\web\HttpException(404, yii::t('app','Peminjaman anda melewati quota!'));
                                }else{
                                 // menambah data
                                 Yii::$app->sirkulasi->addItem($data);
                                }
                                
                            }


                           

                            // mendapatkan data
                            $daftarItem = Yii::$app->sirkulasi->getItem();
                            return  $this->renderAjax('_listKoleksi',
                                                    array(
                                                            'daftarItem'=>$daftarItem,
                                                            'n' => 1,
                                                        ),true);

                            /*foreach($daftarItem as $item){
                                echo($item['NomorBarcode']);
                                echo($item['Title']);
                            }*/

                        }
                    }
                   // return true;
                }
                else
                {
                     throw new \yii\web\HttpException(404, yii::t('app','Nomor Barcode tidak boleh kosong.'));
                }
            }

            
        }
       
        //return "Anggota dengan nomor : 1234 tidak terdapat dalam database.";
                      
    }


   

    public function actionHapusItem(){
            if (isset($_POST['NomorBarcode'])){
                $session = new Session();
                $session->open();

                $item = $session['sirkulasi'];

                if (count($item) > 0) {
                  $item[$_POST['index']] = null;
                  $newItem = [];

                  foreach ($item as $row) {
                    if ($row != null) {
                      $newItem[] = $row;
                    }
                  }

                  $session->set('sirkulasi', $newItem);

                  //return $this->redirect(['addtocart', 'id' => $id]);
                }
                $daftarItem = Yii::$app->sirkulasi->getItem();
                 return  $this->renderAjax('_listKoleksi',
                                                array(
                                                        'daftarItem'=>$daftarItem,
                                                        'n' => 1,
                                                    ),true);
            }
    }

    public function loadModelAnggota($NoAnggota) {
        
        //$params = array(':memberno' => $NoAnggota);
        $model = \common\models\Members::find()->where(['memberNo' => $NoAnggota])->one();
        //if ($model === null)
        //    throw new \yii\web\HttpException(404, 'Anggota dengan nomor tersebut tidak terdapat dalam database.');
        return $model;
    }

    public function actionDetailAnggota($memberNo){
        // Ambil data anggota
        $modelAnggota              = $this->loadModelAnggota($memberNo);
        $membersLoanForm = MembersLoanForm::find()
            ->where(['Jenis_Perpustakaan_id' => Yii::$app->config->get('JenisPerpustakaan')])
            ->asArray()->all();

        echo $this->renderAjax('viewDetailDataAnggota', array('model' => $modelAnggota,'membersLoanForm' =>  $membersLoanForm), true);

    }

    
    /**
     * Deletes an existing peminjaman model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
       // $this->findModelCollection($id)->delete();
       $model = $this->findModelCollection($id);

       $modelcollectionloanitems = \common\models\Collectionloanitems::find()->where(['CollectionLoan_id'=>$model->ID])->one();
       
       $modelcollections = \common\models\Collections::findOne($modelcollectionloanitems->Collection_id);
       $modelcollections->Status_id = '1'; //Tersedia
       $modelcollections->JumlahEksemplar = '1';
       $modelcollections->IsVerified = '0';

       if ($modelcollections->save()) {
           $model->delete();
            Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Success Delete'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
       } 
       
        return $this->redirect(['index']);

    }


     /**
     * Finds the Collectionloanitems model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param double $id
     * @return Collectionloanitems the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelCollection($id)
    {
        if (($model = \common\models\Collectionloans::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    


///////////////////////////////////////////////////////////////////////////////////

    public function actionDetailTransaksiPeminjaman($noanggota,$notransaksi)
    {
        $model                     = new \backend\models\PeminjamanForm;
        // Ambil data anggota
        $modelAnggota              = $this->loadModelAnggota($noanggota);
        $modelSirkulasi             = new \backend\models\PeminjamanItemForm;

         //Memenuhi semua rule
        
        // Periksa apakah masih ada peminjaman
        
        $sqlKoleksiAnggota = "SELECT cli.CollectionLoan_id, cl.NomorBarcode, cat.Title, cat.Author, cat.Publisher, cli.LoanDate, cli.DueDate, cli.ActualReturn, cli.LateDays, cli.Collection_id" .
                            " FROM collectionloanitems cli INNER JOIN collections cl ON cli.Collection_id = cl.ID" .
                            " LEFT JOIN catalogs cat ON cl.Catalog_id = cat.ID" .
                            " WHERE cli.LoanStatus = 'Loan'" .
                            " AND cli.Member_Id ='" . $modelAnggota->ID. 
                            "' AND cli.CollectionLoan_id = '" . $notransaksi.   // Perubahan detail peminjaman hanya ditampilkan current transaction
                            "' ORDER BY cli.DueDate DESC";

        // $sqlKoleksiCurrentTransaksi = "SELECT cli.CollectionLoan_id, cl.NomorBarcode, cat.Title, cat.Author, cat.Publisher, cli.LoanDate, cli.DueDate, cli.ActualReturn, cli.LateDays, cli.Collection_id" .
        //                     " FROM collectionloanitems cli INNER JOIN collections cl ON cli.Collection_id = cl.ID" .
        //                     " LEFT JOIN catalogs cat ON cl.Catalog_id = cat.ID" .
        //                     " WHERE cli.LoanStatus = 'Loan'" .
        //                     " 
        //                     AND cli.Member_Id ='" . $modelAnggota->ID. "' 
        //                     AND cli.CollectionLoan_id ='" . $notransaksi. "' 

        //                     ORDER BY cli.DueDate DESC";

         $countKoleksiLoanOutstanding = Yii::$app->db->createCommand("
            SELECT count(*) " .
                            " FROM collectionloanitems cli INNER JOIN collections cl ON cli.Collection_id = cl.ID" .
                            " LEFT JOIN catalogs cat ON cl.Catalog_id = cat.ID" .
                            " WHERE cli.LoanStatus = 'Loan'" .
                            " AND cli.Member_Id ='" . $modelAnggota->ID. 
                            "' AND cli.CollectionLoan_id = '" . $notransaksi.   // Perubahan detail peminjaman hanya ditampilkan current transaction
                            "' ORDER BY cli.DueDate DESC")->queryScalar();                       
        

        $koleksiLoanOutstanding = new \yii\data\SqlDataProvider([
            'sql' => $sqlKoleksiAnggota,
            //'totalCount' => $countKoleksiLoanOutstanding,
        ]);

        // $koleksiLoanCurrentTransaction = new \yii\data\SqlDataProvider([
        //     'sql' => $sqlKoleksiCurrentTransaksi,
        //     //'totalCount' => $countKoleksiLoanOutstanding,
        // ]);

         // LoanLocation List
        $queryLocation = \common\models\Memberloanauthorizelocation::find();
        $queryLocation->andFilterWhere([
            'Member_id' => $modelAnggota->ID,
        ]);
        $dataProviderLocation = new \yii\data\ActiveDataProvider([
            'query' => $queryLocation,
        ]);
        // End List
        
        // LoanCategory List
        $queryLoanCategory = \common\models\Memberloanauthorizecategory::find();
        $queryLoanCategory->andFilterWhere([
            'Member_id' => $modelAnggota->ID,
        ]);
        $dataProviderLoanCategory = new \yii\data\ActiveDataProvider([
            'query' => $queryLoanCategory,
        ]);
        // End List
        
         // HistoriLoan List
        $queryHistoriLoan = \common\models\Collectionloanitems::find();
        $queryHistoriLoan->Where([
            'member_id' => $modelAnggota->ID,
        ]);
        $queryHistoriLoan->andWhere(['not', ['ActualReturn' => null]]);
        $queryHistoriLoan->orderBy(['ID' => SORT_DESC]); 
        $queryHistoriLoan->limit(10);

        $dataProviderHistoriLoan = new \yii\data\ActiveDataProvider([
            'query' => $queryHistoriLoan,
            'pagination' => false,
            /*'sort' =>[
                'defaultOrder' => [
                    'ID' => SORT_DESC
                ]
            ],*/
        ]);


        // Histori Loan Grupby Category
        $sql = "SELECT `collectioncategorys`.`Code` AS `Code`, `collectioncategorys`.`Name` AS `Name`, COUNT(collectioncategorys.Code) AS `Jumlah` FROM `collections` ".
              "INNER JOIN `collectionloanitems` ON collections.ID=collectionloanitems.Collection_id INNER JOIN `collectioncategorys` ON collections.Category_id=collectioncategorys.ID ".
              " WHERE (`member_id`=".$modelAnggota->ID.") AND (NOT (`ActualReturn` IS NULL)) GROUP BY `Code`, `Name`";

        $command = Yii::$app->db->createCommand($sql)->queryAll();

        $count = Yii::$app->db->createCommand("select count(*) Total from (".$sql.") as t")->queryScalar();

        
        $dataProviderHistoriCountCategory = new \yii\data\SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
        ]);

        // End List

         $membersLoanForm = MembersLoanForm::find()
             ->where(['Jenis_Perpustakaan_id' => Yii::$app->config->get('JenisPerpustakaan')])
             ->asArray()->all();
        
        $tab_infoanggota    = $this->renderPartial('viewDetailDataAnggota', array('model' => $modelAnggota,'membersLoanForm' =>  $membersLoanForm), true);
        $tab_loanLocation   = $this->renderPartial('_listLokasi',
                            array('model'=>$dataProviderLocation,'id'=>$modelAnggota->ID),true);
        $tab_loanCategory   = $this->renderPartial('_listLoanCategory',
                            array('model'=>$dataProviderLoanCategory,'id'=>$modelAnggota->ID),true);
        $tab_historyLoan    = $this->renderPartial('_listHistori',
                            array(
                                    'model'=>$dataProviderHistoriLoan,
                                    'modelCountCategory'=>$dataProviderHistoriCountCategory,
                                    'id'=>$modelAnggota->ID
                                ),true);


        // Simpan MemberID di Session
        // $session = new Session();
        // $session->open();
        // $session->set('MemberID', $modelAnggota->ID);
        // $session->set('NoAnggota', $noanggota);
        // Simpan MemberID di Session


        return $this->renderAjax('_modalIndexDetailTransaksiPeminjaman', array(
            'tab_infoanggota'  => $tab_infoanggota,
            'model'            => $modelSirkulasi,
            'model2'            => $model,
            'tab_loanLocation' => $tab_loanLocation,
            'tab_loanCategory' => $tab_loanCategory,
            'tab_historyLoan'  => $tab_historyLoan,
            'noAnggota'        => $noanggota,
            'memberID'         => $modelAnggota->ID,
            'koleksiLoanOutstanding' => $koleksiLoanOutstanding,
            // 'koleksiLoanCurrentTransaction' => $koleksiLoanCurrentTransaction,
            'transactionID'    => $notransaksi

        )); 


    }



}
