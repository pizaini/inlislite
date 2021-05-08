<?php

namespace backend\modules\sirkulasi\controllers;

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

/**
 * PeminjamanController implements the CRUD actions for Collectionloanitems model.
 */
class PeminjamanController extends Controller
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
     * Lists all Collectionloanitems models.
     * @return mixed
     */
    public function actionIndex()
    {
        $rules = Json::decode(Yii::$app->request->get('rules'));

        $searchModel = new CollectionloanitemSearch;
        // $dataProvider = $searchModel->advancedSearch('Loan',$rules); // Semua data tanpa filter lokasi perpustakaan login
        $dataProvider = $searchModel->advancedSearchByLocation('Loan',$rules,Yii::$app->location->get()); // Semua data berdasarkan lokasi login       

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'rules' => $rules
        ]);

        /*$searchModel = new CollectionloanitemSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);*/
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

        if (Yii::$app->request->post()) {


            $daftarItem = Yii::$app->sirkulasi->getItem();

            $trans = Yii::$app->db->beginTransaction();
            try{
                // get session MemberID
                $MemberID = isset($_SESSION['MemberID']) ? $_SESSION['MemberID'] : null;
                $TransactionID = \common\components\SirkulasiHelpers::generateNewID(date("Y-m-d"));
                
                // print_r(Yii::$app->location->get());die;

                // get List Item
                $daftarItem = Yii::$app->sirkulasi->getItem();

                $modelCollectionLoan->ID              = $TransactionID;
                $modelCollectionLoan->Member_id       = $MemberID;
                $modelCollectionLoan->CollectionCount = count($daftarItem);
                /////////////
                $modelCollectionLoan->LocationLibrary_id = Yii::$app->location->get();
                /////////////
                
                 if ($modelCollectionLoan->save()) {
                    //Simpan Ke CollectionLoanItems
                    foreach ($daftarItem as $item){
                        $modelCollectionLoanItems   = new \common\models\Collectionloanitems;
                        $modelBookingLogs   = new \common\models\Bookinglogs;
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


                            // request baru, set null / selesai booking expired jika koleksi tersebut dipinjam
                            \Yii::$app->db->createCommand('UPDATE bookinglogs SET bookingExpired = NULL WHERE collectionId = '.$modelcollections->ID.'')->execute();
                       }
                        
                    }

                    if(Yii::$app->config->get('OtomatisInsertBukuTamu') == '1'){
                        $get_member = Yii::$app->db->createCommand('
                            SELECT members.MemberNo, members.Fullname, members.Address, collections.Location_Id FROM members
                            INNER JOIN collectionloans ON collectionloans.Member_id = members.ID
                            INNER JOIN collectionloanitems ON collectionloanitems.CollectionLoan_id = collectionloans.ID
                            INNER JOIN collections ON collections.ID = collectionloanitems.Collection_id
                            WHERE collectionloans.ID = :transactionID
                        ');
                        $get_member->bindValue(':transactionID', $TransactionID);
                        $data_member = $get_member->queryOne();

                        if(Yii::$app->config->get('CountingBukuTamu') == '1'){
                            $memberguess = new \common\models\Memberguesses;
                            $memberguess->NoAnggota = $data_member['MemberNo'];
                            $memberguess->Nama = $data_member['Fullname'];
                            $memberguess->Alamat = $data_member['Address'];
                            $memberguess->Location_Id = $data_member['Location_Id'];
                            $memberguess->save();    
                        }else{
                            $check_bukutamu = Yii::$app->db->createCommand('SELECT NoAnggota FROM memberguesses WHERE DATE(CreateDate) = DATE(NOW()) AND NoAnggota = :memberno');
                            $check_bukutamu->bindValue(':memberno', $data_member['MemberNo']);
                            $check_bukutamu = $check_bukutamu->queryOne();
                            // echo'<pre>';print_r($check_bukutamu);die;
                            if(empty($check_bukutamu)){
                                $memberguess = new \common\models\Memberguesses;
                                $memberguess->NoAnggota = $data_member['MemberNo'];
                                $memberguess->Nama = $data_member['Fullname'];
                                $memberguess->Alamat = $data_member['Address'];
                                $memberguess->Location_Id = $data_member['Location_Id'];
                                $memberguess->save(); 
                            }
                        }
                    }
                    
                    


                 }
                 // commit transaction
                $trans->commit();

                Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Transaksi peminjaman berhasil disimpan.'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);

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
    public function actionCreate()
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
            $modelAnggota              = $this->loadModelAnggota(trim($model->noAnggota,'*'));
            // echo'<pre>';print_r($modelAnggota);die;
            if ($modelAnggota === null){
                // Anggota tidak ada
                $this->getView()->registerJs('
                    // alert("Anggota dengan nomor : '.trim($model->noAnggota,'*').' tidak terdapat dalam database.");
                    $("#parent-warning-barcode").show();
                    $("#warning-scanbarcode").html("Anggota dengan nomor : '.trim($model->noAnggota,'*').' tidak terdapat dalam database.");

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
                
                $validatePelanggaran = \common\components\SirkulasiHelpers::validatePelanggaran(trim($model->noAnggota,'*'));

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

                   return $this->render('create', [
                        'model' => $model,
                    ]);

               }else{

                    
                    $isMemberSuspend = \common\components\SirkulasiHelpers::isMemberStatus(trim($model->noAnggota,'*'),'5');

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

                       return $this->render('create', [
                                'model' => $model,
                            ]);
                    }

                    $isMemberNotActive = \common\components\SirkulasiHelpers::isMemberStatus(trim($model->noAnggota,'*'),3);
                    if ($isMemberNotActive == false)
                    {
                        //StatusAnggota Not ACTIVE
                        $this->getView()->registerJs('
                            // alert("Status anggota tidak aktif!");
                            $("#parent-warning-barcode").show();
                            $("#warning-scanbarcode").html("Status anggota tidak aktif!");

                            $("#peminjamanform-noanggota").val("");
                            $("#peminjamanform-noanggota").focus();
                        ');

                       return $this->render('create', [
                                'model' => $model,
                            ]);
                    }

                    $isMemberExpired = \common\components\SirkulasiHelpers::isMemberExpired(trim($model->noAnggota,'*'));
                    if ($isMemberExpired == false)
                    {
                        //Anggota Expired
                        $this->getView()->registerJs('
                            // alert("Masa berlaku keanggotaan sudah habis!");
                            $("#parent-warning-barcode").show();
                            $("#warning-scanbarcode").html("Masa berlaku keanggotaan sudah habis!");

                            $("#peminjamanform-noanggota").val("");
                            $("#peminjamanform-noanggota").focus();
                        ');

                       return $this->render('create', [
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

                           return $this->render('create', [
                                    'model' => $model,
                                ]);
                        } 
                    }
                    
                     //Periksa member untuk hak lokasi peminjamannya
                    $isMemberCanLoanOnLocation = \common\components\SirkulasiHelpers::isMemberCanLoanOnLocation(trim($model->noAnggota,'*'),Yii::$app->user->identity->id);
                    if ($isMemberCanLoanOnLocation == false)
                    {
                         $this->getView()->registerJs('
                            // alert("Anggota tidak mempunyai akses peminjaman di lokasi ini!");
                            $("#parent-warning-barcode").show();
                            $("#warning-scanbarcode").html("Anggota tidak mempunyai akses peminjaman di lokasi ini!");

                            $("#peminjamanform-noanggota").val("");
                            $("#peminjamanform-noanggota").focus();
                            ');

                         return $this->render('create', [
                            'model' => $model,
                            ]);
                    }else{
                         //Periksa jumlah pelanggaran member
                         $jmlPelanggaran = \common\components\SirkulasiHelpers::jumlahPelanggaranAnggota(trim($model->noAnggota,'*'));
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

                             return $this->render('create', [
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
                            $session->set('NoAnggota', trim($model->noAnggota,'*'));
                            // Simpan MemberID di Session


                            return $this->render('indexInfoAnggota', array(
                                'tab_infoanggota'  => $tab_infoanggota,
                                'model'            => $modelSirkulasi,
                                'tab_loanLocation' => $tab_loanLocation,
                                'tab_loanCategory' => $tab_loanCategory,
                                'tab_historyLoan'  => $tab_historyLoan,
                                'noAnggota'        => trim($model->noAnggota,'*'),
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
            $modelAnggota              = $this->loadModelAnggota(trim($model->noAnggota,'*'));
            if ($modelAnggota === null){
                // Anggota tidak ada
                $this->getView()->registerJs('
                    // alert("Anggota dengan nomor : '.trim($model->noAnggota,'*').' tidak terdapat dalam database.");
                    $("#parent-warning-barcode").show();
                    $("#warning-scanbarcode").html("Anggota dengan nomor : '.trim($model->noAnggota,'*').' tidak terdapat dalam database.");

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
                
                $validatePelanggaran = \common\components\SirkulasiHelpers::validatePelanggaran(trim($model->noAnggota,'*'));

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

                    
                    $isMemberSuspend = \common\components\SirkulasiHelpers::isMemberStatus(trim($model->noAnggota,'*'),'5');

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

                    $isMemberNotActive = \common\components\SirkulasiHelpers::isMemberStatus(trim($model->noAnggota,'*'),3);
                    if ($isMemberNotActive == false)
                    {
                        //StatusAnggota Not ACTIVE
                        $this->getView()->registerJs('
                            // alert("Status anggota tidak aktif!");
                            $("#parent-warning-barcode").show();
                            $("#warning-scanbarcode").html("Status anggota tidak aktif!");

                            $("#peminjamanform-noanggota").val("");
                            $("#peminjamanform-noanggota").focus();
                        ');

                       return $this->render('create-susulan', [
                                'model' => $model,
                            ]);
                    }

                    $isMemberExpired = \common\components\SirkulasiHelpers::isMemberExpired(trim($model->noAnggota,'*'));
                    if ($isMemberExpired == false)
                    {
                        //Anggota Expired
                        $this->getView()->registerJs('
                            // alert("Masa berlaku keanggotaan sudah habis!");
                            $("#parent-warning-barcode").show();
                            $("#warning-scanbarcode").html("Masa berlaku keanggotaan sudah habis!");

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
                    $isMemberCanLoanOnLocation = \common\components\SirkulasiHelpers::isMemberCanLoanOnLocation(trim($model->noAnggota,'*'),Yii::$app->user->identity->id);
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
                         $jmlPelanggaran = \common\components\SirkulasiHelpers::jumlahPelanggaranAnggota(trim($model->noAnggota,'*'));
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
                            $session->set('NoAnggota', trim($model->noAnggota,'*'));
                            // Simpan MemberID di Session

                            return $this->render('indexInfoAnggotaSusulan', array(
                                'tab_infoanggota'  => $tab_infoanggota,
                                'model'            => $modelSirkulasi,
                                'tab_loanLocation' => $tab_loanLocation,
                                'tab_loanCategory' => $tab_loanCategory,
                                'tab_historyLoan'  => $tab_historyLoan,
                                'noAnggota'        => trim($model->noAnggota,'*'),
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

            $NomorBarcode = trim(trim($_POST["NomorBarcode"],'*'));
            $NoAnggota = trim($_POST["NoAnggota"]);
            $memberID = trim($_POST["memberID"]);
            $TglTransaksi = trim($_POST["TglTransaksi"]);

            // echo'<pre>';print_r(Yii::$app->config->get('PeminjamanLewatJatuhTempo'));die;
            if(Yii::$app->config->get('PeminjamanLewatJatuhTempo') == '1'){
                $cekJatuhTempo = CollectionLoanItems::find()->select('DueDate')->where(['Member_id' => $memberID, 'LoanStatus' => 'Loan'])->one();
                if($cekJatuhTempo && $cekJatuhTempo->DueDate <= date('Y-m-d H:i:s')){
                    throw new \yii\web\HttpException(404, 'Anggota ini belum mengembalikan koleksi yang telah jatuh tempo!');
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
                            throw new \yii\web\HttpException(404, 'Anggota tidak mempunyai akses peminjaman atas item ini!');
                        }
                        else
                        {
                            if($model->Status_id !=  "1")
                            { // TERSEDIA
                                throw new \yii\web\HttpException(404, 'Item ini masih dipinjam atau tidak tersedia!');
                            }
                            elseif ($model->Rule_id != "1") 
                            {
                                # code...
                                 throw new \yii\web\HttpException(404, 'Item ini tidak dapat dipinjam!');
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

                                    throw new \yii\web\HttpException(404, "Item ini tidak dapat dipijam hari ini! ");

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
                                    throw new \yii\web\HttpException(404, 'Item dengan No.barcode : '.$model->NomorBarcode.' sudah ada di Keranjang Peminjaman!');
                                }
                                else
                                {

                                    $countItem = count(Yii::$app->sirkulasi->getItem());
                                    $maksLoan = \common\components\SirkulasiHelpers::getMaksJumlahPeminjaman($memberID, $model->ID);

                                    if ($countItem >= $maksLoan)
                                    {
                                        throw new \yii\web\HttpException(404, 'Peminjaman anda melewati quota!');
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
                                                                'memberID' => $memberID
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
                         throw new \yii\web\HttpException(404, 'Nomor Barcode tidak boleh kosong.');
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
                        throw new \yii\web\HttpException(404, 'Anggota tidak mempunyai akses peminjaman atas item ini!');
                    }
                    else
                    {
                        if($model->Status_id !=  "1")
                        { // TERSEDIA
                            throw new \yii\web\HttpException(404, 'Item ini masih dipinjam atau tidak tersedia!');
                        }
                        elseif ($model->Rule_id != "1") 
                        {
                            # code...
                             throw new \yii\web\HttpException(404, 'Item ini tidak dapat dipinjam!');
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

                                throw new \yii\web\HttpException(404, "Item ini tidak dapat dipijam hari ini! ");

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
                                throw new \yii\web\HttpException(404, 'Item dengan No.barcode : '.$model->NomorBarcode.' sudah ada di Keranjang Peminjaman!');
                            }
                            else
                            {

                                $countItem = count(Yii::$app->sirkulasi->getItem());
                                $maksLoan = \common\components\SirkulasiHelpers::getMaksJumlahPeminjaman($memberID, $model->ID);

                                if ($countItem >= $maksLoan)
                                {
                                    throw new \yii\web\HttpException(404, 'Peminjaman anda melewati quota!');
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
                     throw new \yii\web\HttpException(404, 'Nomor Barcode tidak boleh kosong.');
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


///////////////////////////////////////////////////////////////////////////////////




}