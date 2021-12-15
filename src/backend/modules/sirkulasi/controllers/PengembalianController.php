<?php

namespace backend\modules\sirkulasi\controllers;

use common\models\base\MembersLoanreturnForm;
use Yii;
use yii\web\Session;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;


use common\models\Collectionloanitems;
use common\models\CollectionloanitemSearch;
use leandrogehlen\querybuilder\Translator;

use common\models\Pelanggaran;

class PengembalianController extends Controller
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
        // $dataProvider = $searchModel->advancedSearch('Return',$rules);
        $dataProvider = $searchModel->advancedSearchByLocation('Return',$rules,Yii::$app->location->get()); // Semua data berdasarkan lokasi login   
        

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'rules' => $rules
        ]);

    }

    public function actionView($id,$for){
            // mendapatkan data pengembalian all.
            $daftarItem = Yii::$app->sirkulasi->getItemPengembalian();
            // mendapatkan data pengembalian item safe.
            $daftarItemSafe = Yii::$app->sirkulasi->getItemPengembalianSafe();
            // mendapatkan data pengembalian all.
            $daftarItemPelanggaran = Yii::$app->sirkulasi->getItemPelanggaran();
            
            $modelCollectionLoans = \common\models\Collectionloanitems::find()
                                    ->where(['CollectionLoan_id' => $id])
                                    ->andWhere(['LoanStatus'=>'Return'])
                                    //->asArray()
                                    ->all();
            /*var_dump($modelCollectionLoans);
            die;*/
                // tambahan pengembalian epm BEGIN
                  $sql = "SELECT cli.ID as CollectionLoanItem_Id, cli.CollectionLoan_id, cli.Collection_id," .
                      " c.NomorBarcode, c.RFID, cli.Member_id, mem.Fullname, mem.MemberNo," .
                      " cat.Title, cat.Author, cat.Publisher," .
                      " cli.LoanDate, cli.DueDate, cli.ActualReturn, cli.LateDays" .
                      " FROM collectionloanitems cli" .
                      " LEFT JOIN collections c ON cli.Collection_id = c.ID" .
                      " LEFT JOIN catalogs cat ON c.Catalog_id = cat.id" .
                      " LEFT JOIN members mem ON cli.Member_id = mem.ID" .
                      " WHERE cli.CollectionLoan_id ='" .$id. "'";

                  $data = Yii::$app->db->createCommand($sql)->queryAll();

                  // $data = [
                  //         'CollectionLoanItem_id'=>trim($result[0]["CollectionLoanItem_Id"]),
                  //         'Collection_id'=>trim($result[0]["Collection_id"]),
                  //         'NomorPinjam' => trim($result[0]["CollectionLoan_id"]),
                  //         'Fullname'=>trim($result[0]["Fullname"]),
                  //         'NomorBarcode' => trim($result[0]["NomorBarcode"]),
                  //         'MemberID' => trim($result[0]["Member_id"]),
                  //         'MemberNo' => trim($result[0]["MemberNo"]),
                  //         'Title' =>  trim($result[0]["Title"]),
                  //         'Penerbit' =>  trim($result[0]["Publisher"]),
                  //         'TglPinjam'=>trim($result[0]["LoanDate"]),
                  //         'DueDate'=>trim($result[0]["DueDate"]),
                  //         'TglKembali'=>trim($TglKembali)
                  //   ];
                  // echo '<pre>'; print_r($data); echo '</pre>';
                // tambahan pengembalian epm END  

            if($modelCollectionLoans != null)
            {
              $modelPelanggaran = \common\models\Pelanggaran::find()
                                      ->where(['CollectionLoan_id' => $id])
                                      //->asArray()
                                      ->all();

              $modelAnggota            = $this->loadModelAnggota($modelCollectionLoans[0]["member_id"]);
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


            $membersLoanForm = MembersLoanreturnForm::find()
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
              return  $this->render('viewResult',
                      array(
                          'daftarItem'=>$modelCollectionLoans,
                          'daftarItemPelanggaran'=>$modelPelanggaran,
                          'tab_infoanggota'  => $tab_infoanggota,
                          'tab_loanLocation' => $tab_loanLocation,
                          'tab_loanCategory' => $tab_loanCategory,
                          'tab_historyLoan'  => $tab_historyLoan,
                          'for'  => $for,
                          'data'  => $daftarItem,
                          'n' => 1,
                          ),true);
         
           //var_dump(count($modelPelanggaran)); 
           }else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }  
    }
    public function actionCreate($for)
    {
       //REMOVE SESSION SIRKULASI PENGEMBALIAN
        Yii::$app->sirkulasi->removePengembalian();
        //REMOVE SESSION SIRKULASI PENGEMBALIAN ITEM YANG TIDAK ADA DENDA
        Yii::$app->sirkulasi->removePengembalianSafe();
        //REMOVE SESSION SIRKULASI PENGEMBALIAN ITEM YANG TERLAMBAT
        Yii::$app->sirkulasi->removePelanggaran();

        if ($for !== 'ep') {
          if ($for !== 'epm') {
            throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
          }
        }

        $model = new \yii\base\DynamicModel([
            'nomorBarcode'
        ]);

        $model->addRule(['nomorBarcode'], 'required');

        
          return $this->render('create', [
                      'model' => $model,
                      'for'=>$for
                  ]);
    }


    public function actionCreateSusulan()
    {
       //REMOVE SESSION SIRKULASI PENGEMBALIAN
        Yii::$app->sirkulasi->removePengembalian();
        //REMOVE SESSION SIRKULASI PENGEMBALIAN ITEM YANG TIDAK ADA DENDA
        Yii::$app->sirkulasi->removePengembalianSafe();
        //REMOVE SESSION SIRKULASI PENGEMBALIAN ITEM YANG TERLAMBAT
        Yii::$app->sirkulasi->removePelanggaran();

        $model = new \yii\base\DynamicModel([
            'nomorBarcode',
            'tglTransaksi'
        ]);
      $model->addRule(['nomorBarcode'], 'required');
       $model->addRule(['nomorBarcode'], 'required');

        
          return $this->render('create-susulan', [
                      'model' => $model,
                  ]);
    }

    public function actionSimpan()
    {
      $for = Yii::$app->request->post('for');
      // echo '<pre>';print_r(Yii::$app->request->post('for'));die;
         $success = false;
         $daftarItemPelanggaran = Yii::$app->sirkulasi->getItemPelanggaran();
         $daftarItemSafe = Yii::$app->sirkulasi->getItemPengembalianSafe();
         $totalItemSafe = count($daftarItemSafe);
         $totalItemPelanggaran = count($daftarItemPelanggaran);
// print_r($daftarItemPelanggaran);die;
         if($totalItemSafe > 0){
           
            //var_dump($daftarItemSafe);
            for($i=1 ; $i <= $totalItemSafe;$i++){

            // Update CollectionLoanItems LoanStatus menjadi Return.
             $modelCollectionLoanItems =  \common\models\Collectionloanitems::findOne($daftarItemSafe[$i-1]["CollectionLoanItem_id"]);
                        $modelCollectionLoanItems->LoanStatus = "Return";
                        $modelCollectionLoanItems->ActualReturn = $daftarItemSafe[$i-1]["TglKembali"];
                        if($modelCollectionLoanItems->save()){
                            $modelcollections           = \common\components\SirkulasiHelpers::loadModelKoleksiByBarcode($daftarItemSafe[$i-1]["NomorBarcode"]);
                            $modelcollections->Status_id = '1'; //Tersedia
                            $modelcollections->JumlahEksemplar = '1';
                            $modelcollections->IsVerified = '0';
                            $modelcollections->save();
                            $success = true;

                        }
                            
                            //
                            //$trans->commit();
                            Yii::$app->getSession()->setFlash('success', [
                                'type' => 'info',
                                'duration' => 500,
                                'icon' => 'fa fa-info-circle',
                                'message' => Yii::t('app','Proses Berhasil'),
                                'title' => 'Info',
                                'positonY' => Yii::$app->params['flashMessagePositionY'],
                                'positonX' => Yii::$app->params['flashMessagePositionX']
                            ]);
            //echo "dea";
          }

         
         
         }

          if($success && $totalItemPelanggaran == '0'){
            return $this->redirect(['view','id'=>$daftarItemSafe[0]["NomorPinjam"], 'for'=>$for]);
          }

         if($totalItemPelanggaran > 0){

            // Ke Form Pelanggaran
            
            // Update status collection
             for($i=1 ; $i <= $totalItemPelanggaran;$i++){

            // Update CollectionLoanItems LoanStatus menjadi Return.
             $modelCollectionLoanItems =  \common\models\Collectionloanitems::findOne($daftarItemPelanggaran[$i-1]["CollectionLoanItem_id"]);
                        $modelCollectionLoanItems->LoanStatus = "Return";
                        $modelCollectionLoanItems->ActualReturn = $daftarItemPelanggaran[$i-1]["TglKembali"];
                       if($modelCollectionLoanItems->save()){
                            $modelcollections           = \common\components\SirkulasiHelpers::loadModelKoleksiByBarcode($daftarItemPelanggaran[$i-1]["NomorBarcode"]);
                            $modelcollections->Status_id = '1'; //Trsedia
                            $modelcollections->JumlahEksemplar = '1';
                            $modelcollections->IsVerified = '0';
                            $modelcollections->save();
                            $success = true;

                        }
                        
                          
          }
            
            // Binding Detail Anggota
            $modelAnggota              = $this->loadModelAnggota($daftarItemPelanggaran[0]["MemberID"]);
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

          $membersLoanForm = MembersLoanreturnForm::find()
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


          
            return  $this->render('createPelanggaran',
                    array(
                        'daftarItem'=>$daftarItemPelanggaran,
                        'tab_infoanggota'  => $tab_infoanggota,
                        'tab_loanLocation' => $tab_loanLocation,
                        'tab_loanCategory' => $tab_loanCategory,
                        'tab_historyLoan'  => $tab_historyLoan,
                        'memberID' =>$modelAnggota->ID,
                        'for'=>$for,
                        'n' => 1,
                        ),true);
         }



    }

    public function actionSimpanPelanggaran()
    {
        $for = Yii::$app->request->post('for');
        $success = false;
        $trans = Yii::$app->db->beginTransaction();

        if (Yii::$app->request->post()) {

            try{
                $daftarItemPelanggaran = Yii::$app->sirkulasi->getItemPelanggaran();
                $totalItemPelanggaran = count($daftarItemPelanggaran);
                //var_dump($daftarItemPelanggaran);

                for($i=1 ; $i <= $totalItemPelanggaran;$i++){

                    $ddlPelanggaran = $_POST['ddlPelanggaran_'.$i];
                    $ddlDenda = $_POST['ddlDenda_'.$i];
                    $jmlDenda = $_POST['jmlDenda_'.$i];
                    $jmlSuspend = $_POST['jmlSuspend_'.$i];

                    

                    $modelPelanggaran = new \common\models\Pelanggaran();
                    $modelPelanggaran->CollectionLoan_id =  trim($daftarItemPelanggaran[$i-1]["NomorPinjam"]);
                    $modelPelanggaran->CollectionLoanItem_id =  trim($daftarItemPelanggaran[$i-1]["CollectionLoanItem_id"]);
                    $modelPelanggaran->JenisPelanggaran_id =  $ddlPelanggaran;
                    $modelPelanggaran->JenisDenda_id =  $ddlDenda;
                    $modelPelanggaran->JumlahDenda =  $jmlDenda;
                    $modelPelanggaran->JumlahSuspend =  $jmlSuspend;
                    $modelPelanggaran->Member_id =  trim($daftarItemPelanggaran[$i-1]["MemberID"]);
                    $modelPelanggaran->Collection_id =  trim($daftarItemPelanggaran[$i-1]["Collection_id"]);
                    

                    if($modelPelanggaran->save()){
                        echo "sukses";
                        $success = true;
                        // Update CollectionLoanItems LoanStatus menjadi Return.
                        $modelCollectionLoanItems =  \common\models\Collectionloanitems::findOne($modelPelanggaran->CollectionLoanItem_id);
                        $modelCollectionLoanItems->LoanStatus = "Return";
                        $modelCollectionLoanItems->ActualReturn = trim($daftarItemPelanggaran[$i-1]["TglKembali"]);


                        if($modelCollectionLoanItems->save()){
                            
                            $success = true;
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
                            $success = false;
                        }

                    }else{
                       
                        $success = false;
                    }
                    //echo trim($daftarItemPelanggaran[$i]["CollectionLoanItem_id"]);
                }//EndForeach

                if($success){
                    $trans->commit();
                    return $this->redirect(['view','id'=>$daftarItemPelanggaran[0]["NomorPinjam"], 'for'=>$for]);
                }

            }catch (CDbException $e) {
                $trans->rollback();
                $success = false;
            }
        }

    }

    public function actionViewKoleksi()
    {
      $for = Yii::$app->request->post('for');
// echo '<pre>'; print_r(Yii::$app->request->post());
// echo '</pre>';die;
        if (Yii::$app->request->post()) 
        {
            $locationLibrary = Yii::$app->location->get();
            $NomorBarcode = trim(trim($_POST["NomorBarcode"],'*'));
            //$NoAnggota = trim($_POST["NoAnggota"]);
            //$memberID = trim($_POST["memberID"]);
            $TglTransaksi = trim($_POST["TglTransaksi"]);

            // $cekKoleksi = \common\components\SirkulasiHelpers::isMemberCanReturnOnLocation($NomorBarcode,$locationLibrary);
            // $cekLokasiMember = \common\models\Memberloanauthorizelocation::find()->select('LocationLoan_id')->where(['Member_id' => $cekKoleksi[0]["Member_id"], 'LocationLoan_id' => $locationLibrary])->one();
          
            if ($TglTransaksi == ""){
                $TglKembali = date('Y-m-d');
            }else{
                $TglKembali = \common\components\Helpers::DateToMysqlFormat('-',$TglTransaksi);
            }

            if(isset($NomorBarcode) && $NomorBarcode != "")
            {
                $model              = \common\components\SirkulasiHelpers::loadModelCollectionLoanItems($NomorBarcode);

                $data = [
                      'CollectionLoanItem_id'=>trim($model[0]["CollectionLoanItem_Id"]),
                      'Collection_id'=>trim($model[0]["Collection_id"]),
                      'NomorPinjam' => trim($model[0]["CollectionLoan_id"]),
                      'Fullname'=>trim($model[0]["Fullname"]),
                      'NomorBarcode' => trim($model[0]["NomorBarcode"]),
                      'MemberID' => trim($model[0]["Member_id"]),
                      'MemberNo' => trim($model[0]["MemberNo"]),
                      'Title' =>  trim($model[0]["Title"]),
                      'Penerbit' =>  trim($model[0]["Publisher"]),
                      'TglPinjam'=>trim($model[0]["LoanDate"]),
                      'DueDate'=>trim($model[0]["DueDate"]),
                      'TglKembali'=>trim($TglKembali)
                ];

              if(!empty(\common\components\SirkulasiHelpers::isMemberCanReturnOnLocation($NomorBarcode,$locationLibrary)))
              {
                  if(Yii::$app->sirkulasi->checkItemPengembalian(trim($model[0]["NomorBarcode"])))
                  {
                    throw new \yii\web\HttpException(404, 'Item dengan No.barcode : '.trim($model[0]["NomorBarcode"]).' sudah ada di list pengembalian!');
                  }else
                      {
                        if ($for == 'epm') {
                            $late = \common\components\SirkulasiHelpers::lateDays($data['TglKembali'] ,date("Y-m-d", strtotime($data["DueDate"])));
                              // Yii::$app->sirkulasi->addItemPengembalian($data);
                            if( $late > 0)
                            {
                              throw new \yii\web\HttpException(404, 'Peminjaman melewati tanggal jatuh tempo. Harap menghubungi petugas!');
                            }else{
                              Yii::$app->sirkulasi->addItemPengembalian($data);
                            }
                        }else
                          {
                              // throw new \yii\web\HttpException(404, 'Bukan EPM');
                              if(Yii::$app->sirkulasi->checkMemberPengembalian(trim($model[0]["Member_id"]))){
                              // Jika Membernya ada berarti masih sama. Langsung masukan kedalam session.
                              // 
                              // echo '<script>alert("asdasdasd")</script>';
                               // throw new \yii\web\HttpException(404, 'Item dengan No.barcode : sudah ada di list pengembalian!',405);
                              Yii::$app->sirkulasi->addItemPengembalian($data);

                              }else{
                                  if(Yii::$app->sirkulasi->checkMemberPengembalianIdBeda(trim($model[0]["Member_id"]))){
                                  throw new \yii\web\HttpException(404, 'Koleksi: '.trim($model[0]["NomorBarcode"]).' tersebut berbeda peminjam');
                                  }else{
                                  // Jika membernya tidak ada berarti tidak sama. Kosongkan session lalu buat baru.
                                  
                                  //REMOVE SESSION SIRKULASI PENGEMBALIAN
                                  Yii::$app->sirkulasi->removePengembalian();
                                  //REMOVE SESSION SIRKULASI PENGEMBALIAN ITEM YANG TIDAK ADA DENDA
                                  Yii::$app->sirkulasi->removePengembalianSafe();
                                  //REMOVE SESSION SIRKULASI PENGEMBALIAN ITEM YANG TERLAMBAT
                                  Yii::$app->sirkulasi->removePelanggaran();


                                  // menambah data
                                  Yii::$app->sirkulasi->addItemPengembalian($data);
                                  }
                              }
                        }
                      }
              }else{
                  throw new \yii\web\HttpException(404, 'Anggota tidak mempunyai akses pengembalian dilokasi ini!');
                  } 


                  $daftarItem = Yii::$app->sirkulasi->getItemPengembalian();


                  // Binding Detail Anggota
                  $modelAnggota              = $this->loadModelAnggota($model[0]["Member_id"]);
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


                  $membersLoanForm = MembersLoanreturnForm::find()
                    ->where(['Jenis_Perpustakaan_id' => Yii::$app->config->get('JenisPerpustakaan')])
                    ->asArray()->all();

                  $tab_infoanggota    = $this->renderPartial('viewDetailDataAnggota', array('model' => $modelAnggota,'membersLoanForm' =>  $membersLoanForm,'col_id'=>$data['Collection_id']), true);
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

              return  $this->renderAjax('_listKoleksi',
              array(
                'daftarItem'=>$daftarItem,
                'tab_infoanggota'  => $tab_infoanggota,
                'tab_loanLocation' => $tab_loanLocation,
                'tab_loanCategory' => $tab_loanCategory,
                'tab_historyLoan'  => $tab_historyLoan,
                'for'  => $for,
                'n' => 1,
                ),true);                    
            }else
                {
                  throw new \yii\web\HttpException(404, yii::t('app','Nomor Barcode tidak boleh kosong.'));
                }
               // return true;
            
        }
        
       
        //return "Anggota dengan nomor : 1234 tidak terdapat dalam database.";
                      
    }


    public function actionHapusItem(){
          $for = Yii::$app->request->post('for');
            if (isset($_POST['NomorBarcode'])){
                $session = new Session();
                $session->open();

                $item = $session['sirkulasi-pengembalian'];


                //REMOVE SESSION SIRKULASI PENGEMBALIAN ITEM YANG TERLAMBAT DAN TAMBAHKAN ULANG
                Yii::$app->sirkulasi->removePelanggaran();
                //REMOVE SESSION SIRKULASI PENGEMBALIAN ITEM YANG TIDAK ADA DENDA
                Yii::$app->sirkulasi->removePengembalianSafe();



                if (count($item) > 0) {
                  $item[$_POST['index']] = null;
                  $newItem = [];
                  $newItemPelanggaran = [];
                  $newItemSafe = [];

                  foreach ($item as $row) {
                    //var_dump($row);
                    // Cek item yang terlambat lalu masukan ke session terlambat.
                    if(date('Y-m-d') > $item['TglKembali']){
                        if(!Yii::$app->sirkulasi->checkItemPelanggaran(trim($item["NomorBarcode"]))){
                          // add Item Terkena Pelanggaran
                          if ($row != null) {
                              $newItemPelanggaran[] = $row;
                          }
                        }
                    }else{
                        if(!Yii::$app->sirkulasi->checkItemPengembalianSafe(trim($item["NomorBarcode"]))){
                          // add Item Tidak Kena Pelanggaran
                          if ($row != null) {
                              $newItemSafe[] = $row;
                          }
                        }

                    }
                    // end cek
                    //die;
                    if ($row != null) {
                      $newItem[] = $row;

                    }
                  }

                  $session->set('sirkulasi-pengembalian', $newItem); // List all item
                  $session->set('sirkulasi-pelanggaran', $newItemPelanggaran); // List all item pelanggaran
                  $session->set('sirkulasi-pengembalian-safe', $newItemSafe); // List all item pelanggaran

                }
               

                $daftarItem = Yii::$app->sirkulasi->getItemPengembalian();


                if(count($daftarItem) > 0){
                 
                  // Binding Detail Anggota
                  $modelAnggota              = $this->loadModelAnggota($daftarItem[0]['MemberID']);
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
                                'sort' =>[
                                    'defaultOrder' => [
                                        'ID' => SORT_DESC
                                    ]
                                    ],
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
                  $membersLoanForm = MembersLoanreturnForm::find()
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

                return  $this->renderAjax('_listKoleksi',
                    array(
                        'daftarItem'=>$daftarItem,
                        'tab_infoanggota'  => $tab_infoanggota,
                        'tab_loanLocation' => $tab_loanLocation,
                        'tab_loanCategory' => $tab_loanCategory,
                        'tab_historyLoan'  => $tab_historyLoan,
                        'for'  => $for,
                        'n' => 1,
                        ),true);

                }else{
                    return  $this->renderAjax('_listKoleksi',
                                                array(
                                                        'daftarItem'=>$daftarItem,
                                                        'for'  => $for,
                                                        'n' => 1,
                                                    ),true);
                }


                
                 
               

               /* return  $this->renderAjax('_listKoleksi',
                                                array(
                                                        'daftarItem'=>$daftarItem,
                                                        'n' => 1,
                                                    ),true);*/
            }
    }


    public function loadModelAnggota($MemberID) {
        
        $model = \common\models\Members::findOne($MemberID);
        return $model;
    }

    public function actionDetailAnggota($MemberID){
        // Ambil data anggota
        $modelAnggota              = $this->loadModelAnggota($MemberID);
        $membersLoanForm = MembersLoanreturnForm::find()
            ->where(['Jenis_Perpustakaan_id' => Yii::$app->config->get('JenisPerpustakaan')])
            ->asArray()->all();

        echo $this->renderAjax('viewDetailDataAnggota', array('model' => $modelAnggota,'membersLoanForm' =>  $membersLoanForm), true);

    }







///////////////////////////////////////////////////////////////////////////////////////////

    public function actionDetailTransaksiPengembalian($id){

            // mendapatkan data pengembalian all.
            $daftarItem = Yii::$app->sirkulasi->getItemPengembalian();
            // mendapatkan data pengembalian item safe.
            $daftarItemSafe = Yii::$app->sirkulasi->getItemPengembalianSafe();
            // mendapatkan data pengembalian all.
            $daftarItemPelanggaran = Yii::$app->sirkulasi->getItemPelanggaran();
            
            $modelCollectionLoans = \common\models\Collectionloanitems::find()
                                    ->where(['CollectionLoan_id' => $id])
                                    ->andWhere(['LoanStatus'=>'Return'])
                                    //->asArray()
                                    ->all();
            /*var_dump($modelCollectionLoans);
            die;*/


            if($modelCollectionLoans != null)
            {
              $modelPelanggaran = \common\models\Pelanggaran::find()
                                      ->where(['CollectionLoan_id' => $id])
                                      //->asArray()
                                      ->all();

              $modelAnggota            = $this->loadModelAnggota($modelCollectionLoans[0]["member_id"]);
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


            $membersLoanForm = MembersLoanreturnForm::find()
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
              return  $this->renderAjax('modalviewResult',
                      array(
                          'daftarItem'=>$modelCollectionLoans,
                          'daftarItemPelanggaran'=>$modelPelanggaran,
                          'tab_infoanggota'  => $tab_infoanggota,
                          'tab_loanLocation' => $tab_loanLocation,
                          'tab_loanCategory' => $tab_loanCategory,
                          'tab_historyLoan'  => $tab_historyLoan,
                          'n' => 1,
                          ),true);
         
           //var_dump(count($modelPelanggaran)); 
           }else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }  
    }




///////////////////////////////////////////////////////////////////////////////////////////




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

       $model->ActualReturn = Null;
       $model->LoanStatus = 'Loan';

       if ($model->save()) {
            Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Success Delete').' '.Yii::t('app','Data dikembalikan ke peminjaman'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
       } 
       
        return $this->redirect(['index']);

    }
//////////////////////////////////////////////////////////////////////////////////////

     /**
     * Finds the Collectionloanitems model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param double $id
     * @return Collectionloanitems the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelCollection($id)
    {
        if (($model = \common\models\Collectionloanitems::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }





    ///////////////////////////////////////////////////////////////////////
    /**
     * [actionCreatePelanggaran description]
     * @return [type] [description]
     */
    public function actionCreatePelanggaran($loanID,$member,$loanItemID,$for)
    {
        // cek pelanggaran sudah ada
        $cekPelanggaranExist = Pelanggaran::find()->where(['CollectionLoan_id'=>$loanID, 'CollectionLoanItem_id'=>$loanItemID])->One();
        if ($cekPelanggaranExist) {
            Yii::$app->getSession()->setFlash('failed', [
            'type' => 'danger',
            'duration' => 500,
            'icon' => 'fa fa-info-circle',
            'message' => Yii::t('app','Pelanggaran sudah di set'),
            'title' => 'Info',
            'positonY' => Yii::$app->params['flashMessagePositionY'],
            'positonX' => Yii::$app->params['flashMessagePositionX']
            ]);
           return $this->redirect(['view', 'id' => $loanID]);
       }

        $model = new Pelanggaran;

        $modelItem = Collectionloanitems::findOne($loanItemID);
        // echo "<pre>";
        // echo $modelItem->member_id;die;
        // print_r($modelItem);die;
        if ($model->load(Yii::$app->request->post()) ) {
            $for = Yii::$app->request->post()['for'];
            // echo'<pre>';print_r($for);die;
            $model->CollectionLoan_id = $modelItem->CollectionLoan_id;
            $model->CollectionLoanItem_id = $modelItem->ID;
            $model->Member_id = $modelItem->member_id;
            $model->Collection_id = $modelItem->Collection_id;

            $model->JumlahDenda = $model->JumlahDenda !== null ? $model->JumlahDenda : 0;
            $model->JumlahSuspend = $model->JumlahSuspend !== null ? $model->JumlahSuspend : 0;

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
                return $this->redirect(['view', 'id' => $loanID, 'for' => $for]);
            }
        } 
        return $this->render('createPelanggaranNonTerlamabat', [
            'model' => $model,
            'modelItem' => $modelItem,
            'for' => $for
            ]);

    }

    ///////////////////////////////////////////////////////////////////////
    



}
