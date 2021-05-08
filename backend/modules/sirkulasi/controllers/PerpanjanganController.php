<?php

namespace backend\modules\sirkulasi\controllers;

use common\models\base\MembersLoanForm;
use Yii;
use yii\web\Session;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;


use common\models\Collectionloanitems;
use common\models\CollectionloanitemSearch;
use leandrogehlen\querybuilder\Translator;

use common\models\CollectionloanextendsSearch;

class PerpanjanganController extends Controller
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
		
		$searchModel = new CollectionloanextendsSearch;
		$dataProvider = $searchModel->advancedSearch('Loan',$rules);
		// $dataProvider = $searchModel->advancedSearchByLocation('Loan',$rules,Yii::$app->location->get());
		

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
			'rules' => $rules
		]);

	}


//////////////////////////////////////////////////////////////////////
	/**
	 * [actionDelete description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionDelete($id)
    {
       $this->findModelCollectionloanExtends($id)->delete();
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
     * [findModelCollectionloanExtends description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    protected function findModelCollectionloanExtends($id)
    {
    	if (($model = \common\models\Collectionloanextends::findOne($id)) !== null) {
    		return $model;
    	} else {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    }
//////////////////////////////////////////////////////////////////////

    /**
     * [actionView description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
	public function actionView($id){

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



			$tab_infoanggota    = $this->renderPartial('viewDetailDataAnggota', array('model' => $modelAnggota), true);
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
						  'n' => 1,
						  ),true);
		 
		   //var_dump(count($modelPelanggaran)); 
		   }else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}  
	}
	public function actionCreate()
	{
		 //REMOVE SESSION SIRKULASI PENGEMBALIAN
		// Yii::$app->sirkulasi->removePengembalian();
		Yii::$app->sirkulasi->removePerpanjangan();
		//REMOVE SESSION SIRKULASI PENGEMBALIAN ITEM YANG TIDAK ADA DENDA
		Yii::$app->sirkulasi->removePerpanjanganSafe();
		//REMOVE SESSION SIRKULASI PENGEMBALIAN ITEM YANG TERLAMBAT
		// Yii::$app->sirkulasi->removePelanggaran();

		$model = new \yii\base\DynamicModel([
			'nomorBarcode',
			'tglTransaksi'

		]);
		$model->addRule(['nomorBarcode'], 'required');
			  // $model->addRule(['nomorBarcode'], 'required');
	   $model->addRule(['nomorBarcode'], 'required');

		
		  return $this->render('create', [
					  'model' => $model,
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












	/**
	 * [actionSimpan description]
	 * @return [type] [description]
	 */
	public function actionSimpan()
	{
		 $success = false;
		 // $daftarItemPelanggaran = Yii::$app->sirkulasi->getItemPelanggaran();
		 $daftarItemSafe = Yii::$app->sirkulasi->getItemPerpanjanganSafe();
		 $totalItemSafe = count($daftarItemSafe);



		 $modelAnggota = $this->loadModelAnggota($daftarItemSafe[0]["MemberID"]);

		 // print_r($modelAnggota);die;
		 // echo $daftarItemSafe[0]["MemberID"];die;

		 // echo "<pre>";
		 // print_r($daftarItemSafe);die;

		 // $totalItemPelanggaran = count($daftarItemPelanggaran);

		 if($totalItemSafe > 0){
		   
			//var_dump($daftarItemSafe);
			for($i=1 ; $i <= $totalItemSafe;$i++){

			// Update CollectionLoanItems DueDate menjadi DueDate perpanjang.
			 $modelCollectionLoanItems =  \common\models\Collectionloanitems::findOne($daftarItemSafe[$i-1]["CollectionLoanItem_id"]);
			 $modelCollectionLoanItems->DueDate = $daftarItemSafe[$i-1]["DueDatePerpanjang"];

			 // $modelCollectionLoanItems->LoanStatus = "Loan";
			 // $modelCollectionLoanItems->ActualReturn = $daftarItemSafe[$i-1]["TglKembali"];


			// Catat Perpanjangan
			$modelCollectionLoanExtends = new \common\models\Collectionloanextends;
			$modelCollectionLoanExtends->CollectionLoan_id = $modelCollectionLoanItems->CollectionLoan_id; 
			$modelCollectionLoanExtends->CollectionLoanItem_id = $modelCollectionLoanItems->ID; 
			$modelCollectionLoanExtends->Collection_id = $modelCollectionLoanItems->Collection_id; 
			$modelCollectionLoanExtends->Member_id = $modelCollectionLoanItems->member_id; 
			$modelCollectionLoanExtends->DateExtend = $daftarItemSafe[$i-1]["TglPerpanjang"]; 
			$modelCollectionLoanExtends->DueDateExtend = $daftarItemSafe[$i-1]["DueDatePerpanjang"]; 



			 if( $modelCollectionLoanItems->save() && $modelCollectionLoanExtends->save() )
			 {
				/////////////////////////////////////////////////////// Karena tidak ada perubahan di koleksi
				// $modelcollections = \common\components\SirkulasiHelpers::loadModelKoleksiByBarcode($daftarItemSafe[$i-1]["NomorBarcode"]);
				// $modelcollections->Status_id = '5'; //Dipinjam
				// $modelcollections->JumlahEksemplar = '1';
				// $modelcollections->IsVerified = '0';
				// $modelcollections->save();
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
			}

							//
							//$trans->commit();
			//echo "dea";
		  }

		 
		 
		 }

		  // if($success && $totalItemPelanggaran == '0'){
		 if($success){
		 	// return $this->redirect(['view','id'=>$daftarItemSafe[0]["NomorPinjam"]]);
		 	// return $this->redirect(['peminjaman/detail-peminjaman','noanggota' => $modelAnggota->MemberNo,'notransaksi'=>$daftarItemSafe[0]["NomorPinjam"]]);
		 	return $this->redirect(['detail-perpanjangan','noanggota' => $modelAnggota->MemberNo,'notransaksi'=>$daftarItemSafe[0]["NomorPinjam"],'tanggalperpanjang'=>$daftarItemSafe[0]["TglPerpanjang"]]);

		 }

		 //  if($totalItemPelanggaran > 0)
		 //  {

			// 	// Ke Form Pelanggaran
				
			// 	// Update status collection
			// for($i=1 ; $i <= $totalItemPelanggaran;$i++)
			// {

			// 	// Update CollectionLoanItems LoanStatus menjadi Return.
			// 	$modelCollectionLoanItems =  \common\models\Collectionloanitems::findOne($daftarItemPelanggaran[$i-1]["CollectionLoanItem_id"]);
			// 	$modelCollectionLoanItems->LoanStatus = "Return";
			// 	$modelCollectionLoanItems->ActualReturn = $daftarItemPelanggaran[$i-1]["TglKembali"];


			// 	if($modelCollectionLoanItems->save())
			// 	{
			// 		$modelcollections           = \common\components\SirkulasiHelpers::loadModelKoleksiByBarcode($daftarItemPelanggaran[$i-1]["NomorBarcode"]);
			// 		$modelcollections->Status_id = '1'; //Trsedia
			// 		$modelcollections->JumlahEksemplar = '1';
			// 		$modelcollections->IsVerified = '0';
			// 		$modelcollections->BookingMemberID = NULL; // agar status di opac menjadi tidak dipesan
			// 		$modelcollections->BookingExpiredDate = NULL; // agar status di opac menjadi tidak dipesan
			// 		$modelcollections->save();
			// 		$success = true;

			// 	}

			// }
			
			// 	// Binding Detail Anggota
			// 	$modelAnggota              = $this->loadModelAnggota($daftarItemPelanggaran[0]["MemberID"]);
			// 		// LoanLocation List
			// 	$queryLocation = \common\models\Memberloanauthorizelocation::find();
			// 	$queryLocation->andFilterWhere([
			// 		'Member_id' => $modelAnggota->ID,
			// 		]);
			// 	$dataProviderLocation = new \yii\data\ActiveDataProvider([
			// 		'query' => $queryLocation,
			// 		]);
			// 						// End List

			// 						// LoanCategory List
			// 	  $queryLoanCategory = \common\models\Memberloanauthorizecategory::find();
			// 	  $queryLoanCategory->andFilterWhere([
			// 		'Member_id' => $modelAnggota->ID,
			// 		]);
			// 	  $dataProviderLoanCategory = new \yii\data\ActiveDataProvider([
			// 		'query' => $queryLoanCategory,
			// 		]);
			// 						// End List

			// 						 // HistoriLoan List
			// 	  $queryHistoriLoan = \common\models\Collectionloanitems::find();
			// 	  $queryHistoriLoan->Where([
			// 		'member_id' => $modelAnggota->ID,
			// 		]);
			// 	  $queryHistoriLoan->andWhere(['not', ['ActualReturn' => null]]);
			// 	  $queryHistoriLoan->orderBy(['ID' => SORT_DESC]); 
			// 	  $queryHistoriLoan->limit(10);

			// 	  $dataProviderHistoriLoan = new \yii\data\ActiveDataProvider([
			// 		'query' => $queryHistoriLoan,
			// 		'pagination' => false,
			// 							/*'sort' =>[
			// 								'defaultOrder' => [
			// 									'ID' => SORT_DESC
			// 								]
			// 								],*/
			// 								]);


			// 					// Histori Loan Grupby Category
			//   $sql = "SELECT `collectioncategorys`.`Code` AS `Code`, `collectioncategorys`.`Name` AS `Name`, COUNT(collectioncategorys.Code) AS `Jumlah` FROM `collections` ".
			//   "INNER JOIN `collectionloanitems` ON collections.ID=collectionloanitems.Collection_id INNER JOIN `collectioncategorys` ON collections.Category_id=collectioncategorys.ID ".
			//   " WHERE (`member_id`=".$modelAnggota->ID.") AND (NOT (`ActualReturn` IS NULL)) GROUP BY `Code`, `Name`";

			//   $command = Yii::$app->db->createCommand($sql)->queryAll();

			//   $count = Yii::$app->db->createCommand("select count(*) Total from (".$sql.") as t")->queryScalar();

			//   $dataProviderHistoriCountCategory = new \yii\data\SqlDataProvider([
			// 	'sql' => $sql,
			// 	'totalCount' => $count,
			// 	]);

			// 					// End List



			//   $tab_infoanggota    = $this->renderPartial('viewDetailDataAnggota', array('model' => $modelAnggota), true);
			//   $tab_loanLocation   = $this->renderPartial('_listLokasi',
			// 	array('model'=>$dataProviderLocation,'id'=>$modelAnggota->ID),true);
			//   $tab_loanCategory   = $this->renderPartial('_listLoanCategory',
			// 	array('model'=>$dataProviderLoanCategory,'id'=>$modelAnggota->ID),true);
			//   $tab_historyLoan    = $this->renderPartial('_listHistori',
			// 	array(
			// 		'model'=>$dataProviderHistoriLoan,
			// 		'modelCountCategory'=>$dataProviderHistoriCountCategory,
			// 		'id'=>$modelAnggota->ID
			// 		),true);


		  
			// return  $this->render('createPelanggaran',
			// 		array(
			// 			'daftarItem'=>$daftarItemPelanggaran,
			// 			'tab_infoanggota'  => $tab_infoanggota,
			// 			'tab_loanLocation' => $tab_loanLocation,
			// 			'tab_loanCategory' => $tab_loanCategory,
			// 			'tab_historyLoan'  => $tab_historyLoan,
			// 			'memberID' =>$modelAnggota->ID,
			// 			'n' => 1,
			// 			),true);
	 	// 	}



	}








	/**
	 * [actionDetailPeminjaman description]
	 * @param  [type] $noanggota   [description]
	 * @param  [type] $notransaksi [description]
	 * @return [type]              [description]
	 */
    public function actionDetailPerpanjangan($noanggota,$notransaksi,$tanggalperpanjang=null)
    {
        $model                     = new \backend\models\PeminjamanForm;
        // Ambil data anggota
        $modelAnggota              = $this->loadModelAnggotaByNoanggota($noanggota);
        $modelSirkulasi             = new \backend\models\PeminjamanItemForm;

         //Memenuhi semua rule
        
        // Periksa apakah masih ada peminjaman
        
        $sqlKoleksiAnggota = "SELECT cli.CollectionLoan_id, cl.NomorBarcode, cat.Title, cat.Author, cat.Publisher, cli.LoanDate, cli.DueDate, cli.ActualReturn, cli.LateDays, cli.Collection_id" .
                            " FROM collectionloanitems cli INNER JOIN collections cl ON cli.Collection_id = cl.ID" .
                            " LEFT JOIN catalogs cat ON cl.Catalog_id = cat.ID" .
                            " LEFT JOIN collectionloanextends cle ON cli.CollectionLoan_id = cle.CollectionLoan_id" .
                            " WHERE cli.LoanStatus = 'Loan'" .
                            " AND cli.CollectionLoan_id = '" . $notransaksi."'".   // Perubahan detail peminjaman hanya ditampilkan current transaction
                            // " AND cle.DateExtend = '".$tanggalperpanjang."'" .
                            " AND cli.Member_Id ='" . $modelAnggota->ID. "' ORDER BY cli.DueDate DESC";

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
                            " AND cli.CollectionLoan_id = '" . $notransaksi."'".   // Perubahan detail peminjaman hanya ditampilkan current transaction
                            " AND cli.Member_Id ='" . $modelAnggota->ID. "' ORDER BY cli.DueDate DESC")->queryScalar();                       
        

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














	public function actionViewKoleksi()
	{
		if (Yii::$app->request->post()) {


			$NomorBarcode = trim(trim($_POST["NomorBarcode"],'*'));
			//$NoAnggota = trim($_POST["NoAnggota"]);
			//$memberID = trim($_POST["memberID"]);
			
			/////////////////////////////////////////////// Waktu sekarang + hari perpanjangan
			$TglTransaksi = trim($_POST["TglTransaksi"]);
			
			if ($TglTransaksi == ""){
				$TglKembali = date('Y-m-d');
			}else{
				$TglKembali = \common\components\Helpers::DateToMysqlFormat('-',$TglTransaksi);
			}



			if(isset($NomorBarcode) && $NomorBarcode != ""){
				$model = \common\components\SirkulasiHelpers::loadModelCollectionLoanItems($NomorBarcode);


				////////////////////////////////////////////////////// Waktu jatuh tempo sebelumnya + hari perpanjangan
				// $TglTransaksi = $model[0]["DueDate"];
				// if ($TglTransaksi == ""){
				//     $TglKembali = date('Y-m-d');
				// }
				// else
				// {
				//     // $TglKembali = \common\components\Helpers::DateToMysqlFormat('-',$TglTransaksi);
				//     $TglKembali = $TglTransaksi;
				// }


				$modelAnggota = $this->loadModelAnggota($model[0]["Member_id"]);
				// Get Tanggal Kembali
				$tglKembaliPerpanjangan = \common\components\SirkulasiHelpers::GetTanggalKembaliPerpanjangan($modelAnggota->MemberNo, $TglKembali, $NomorBarcode);
				if($tglKembaliPerpanjangan == 0){
					$tglKembaliPerpanjangan = date('Y-m-d');
				}
				/////////////////////////////////////////////////////////////////////////////////////

				$countPerpanjangan = \common\models\Collectionloanextends::find()->where(['CollectionLoan_id' => trim($model[0]["CollectionLoan_id"]) ])->andWhere(['Collection_id' => trim($model[0]["Collection_id"]) ])->count();

				// $countPerpanjangan = \common\models\Collectionloanextends::find()->where(['CollectionLoan_id' => trim($model[0]["CollectionLoan_id"])])->count();


				$TodayExtend = \common\models\Collectionloanextends::find()->where(['DateExtend' => date('Y-m-d') ])->andWhere(['Collection_id' => trim($model[0]["Collection_id"]) ])->count();
				if ($TodayExtend >= 1) {
					throw new \yii\web\HttpException(404, 'Item dengan No.barcode : '.trim($model[0]["NomorBarcode"]).' Sudah diperpanjang hari ini!');
				}



				$data = [
					'CollectionLoanItem_id'=>trim($model[0]["CollectionLoanItem_Id"]),
					'Collection_id'=>trim($model[0]["Collection_id"]),
					'NomorPinjam' => trim($model[0]["CollectionLoan_id"]),
					'NomorBarcode' => trim($model[0]["NomorBarcode"]),
					'MemberID' => trim($model[0]["Member_id"]),
					'Title' =>  trim($model[0]["Title"]),
					'Penerbit' =>  trim($model[0]["Publisher"]),
					'TglPinjam'=>trim($model[0]["LoanDate"]),
					'DueDate'=>trim($model[0]["DueDate"]),
					'TglPerpanjang'=>trim($TglKembali),
					'DueDatePerpanjang'=>trim($tglKembaliPerpanjangan),
					'CountPerpanjang'=>trim($countPerpanjangan)
				];



				////////////////////////////////
				$lateDate = trim($model[0]["DueDate"]);
				$late = \common\components\SirkulasiHelpers::lateDays($TglKembali ,date("Y-m-d", strtotime($lateDate)));

				if( $late > 0)
				{

					throw new \yii\web\HttpException(404, yii::t('app','Item dengan No.barcode : ').trim($model[0]["NomorBarcode"]).yii::t('app',' tidak dapat diperpanjang!'));
				}
				////////////////////////////////




					// periksa dahulu 
					if(Yii::$app->sirkulasi->checkItemPerpanjangan(trim($model[0]["NomorBarcode"]))){
						// Jika ada
						throw new \yii\web\HttpException(404, yii::t('app','Item dengan No.barcode : ').trim($model[0]["NomorBarcode"]).yii::t('app',' sudah ada di list perpanjangan!'));
					}else{


						///////////////////////////////////////////////////////////////////////////////
                        $countItem = count(Yii::$app->sirkulasi->getItemPerpanjangan());
                        $maksLoan = \common\components\SirkulasiHelpers::getMaksJumlahPerpanjangan( $modelAnggota->ID,trim($model[0]["Collection_id"]) );
                        if ( $maksLoan <= 0 )
                        {
                            throw new \yii\web\HttpException(404, yii::t('app','Perpanjangan anda melewati quota!'));
                        }
						///////////////////////////////////////////////////////////////////////////////




						if(Yii::$app->sirkulasi->checkMemberPerpanjangan(trim($model[0]["Member_id"]))){
							// Jika Membernya ada berarti masih sama. Langsung masukan kedalam session.
							// 
							Yii::$app->sirkulasi->addItemPerpanjangan($data);
						}else{
							// Jika membernya tidak ada berarti tidak sama. Kosongkan session lalu buat baru.
							
							//REMOVE SESSION SIRKULASI PENGEMBALIAN
							Yii::$app->sirkulasi->removePerpanjangan();
							//REMOVE SESSION SIRKULASI PENGEMBALIAN ITEM YANG TIDAK ADA DENDA
							Yii::$app->sirkulasi->removePerpanjanganSafe();
							//REMOVE SESSION SIRKULASI PENGEMBALIAN ITEM YANG TERLAMBAT
							// Yii::$app->sirkulasi->removePelanggaran();


							// menambah data
							Yii::$app->sirkulasi->addItemPerpanjangan($data);
							
						}
						
					}

				 // mendapatkan data
				  $daftarItem = Yii::$app->sirkulasi->getItemPerpanjangan();


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

				  

				 
				  return  $this->renderAjax('_listKoleksi',
					array(
						'daftarItem'=>$daftarItem,
						'tab_infoanggota'  => $tab_infoanggota,
						'tab_loanLocation' => $tab_loanLocation,
						'tab_loanCategory' => $tab_loanCategory,
						'tab_historyLoan'  => $tab_historyLoan,
						'n' => 1,
						),true);

					
				}else{
					 throw new \yii\web\HttpException(404, yii::t('app','Nomor Barcode tidak boleh kosong.'));
				}
			   // return true;
		}
		
	   
		//return "Anggota dengan nomor : 1234 tidak terdapat dalam database.";
					  
	}


	public function actionHapusItem(){
			if (isset($_POST['NomorBarcode'])){
				$session = new Session();
				$session->open();

				$item = $session['sirkulasi-perpanjangan'];


				//REMOVE SESSION SIRKULASI PENGEMBALIAN ITEM YANG TERLAMBAT DAN TAMBAHKAN ULANG
				// Yii::$app->sirkulasi->removePelanggaran();
				//REMOVE SESSION SIRKULASI PENGEMBALIAN ITEM YANG TIDAK ADA DENDA
				Yii::$app->sirkulasi->removePerpanjanganSafe();



				if (count($item) > 0) {
				  $item[$_POST['index']] = null;
				  $newItem = [];
				  $newItemPelanggaran = [];
				  $newItemSafe = [];

				  foreach ($item as $row) {
					//var_dump($row);
					// Cek item yang terlambat lalu masukan ke session terlambat.
					// if(date('Y-m-d') > $item['TglKembali']){
					// 	if(!Yii::$app->sirkulasi->checkItemPelanggaran(trim($item["NomorBarcode"]))){
					// 	  // add Item Terkena Pelanggaran
					// 	  if ($row != null) {
					// 		  $newItemPelanggaran[] = $row;
					// 	  }
					// 	}
					// }else{
						if(!Yii::$app->sirkulasi->checkItemPerpanjanganSafe(trim($item["NomorBarcode"]))){
						  // add Item Tidak Kena Pelanggaran
						  if ($row != null) {
							  $newItemSafe[] = $row;
						  }
						}

					// }
					// end cek
					//die;
					if ($row != null) {
					  $newItem[] = $row;

					}
				  }

				  $session->set('sirkulasi-perpanjangan', $newItem); // List all item
				  // $session->set('sirkulasi-pelanggaran', $newItemPelanggaran); // List all item pelanggaran
				  $session->set('sirkulasi-perpanjangan-safe', $newItemSafe); // List all item pelanggaran

				}
			   

				$daftarItem = Yii::$app->sirkulasi->getItemPerpanjangan();


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



				  $tab_infoanggota    = $this->renderPartial('viewDetailDataAnggota', array('model' => $modelAnggota), true);
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
						'n' => 1,
						),true);

				}else{
					return  $this->renderAjax('_listKoleksi',
												array(
														'daftarItem'=>$daftarItem,
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

	public function loadModelAnggotaByNoanggota($noAnggota) {
		
		$model = \common\models\Members::find()->where(['MemberNo'=>$noAnggota])->one();
		return $model;
	}

	public function actionDetailAnggota($MemberID){
		// Ambil data anggota
		$modelAnggota              = $this->loadModelAnggota($MemberID);
		$membersLoanForm = MembersLoanForm::find()
			->where(['Jenis_Perpustakaan_id' => Yii::$app->config->get('JenisPerpustakaan')])
			->asArray()->all();

		echo $this->renderAjax('viewDetailDataAnggota', array('model' => $modelAnggota,'membersLoanForm' =>  $membersLoanForm), true);

	}
}
