<?php

namespace opac\controllers;

use Yii;
use common\models\Collections;
use common\models\CollectionmediaSearch;
use common\models\CollectionSearch;
use common\models\CatalogsSearch;
use common\models\Favorite;
use common\models\Bookinglogs;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;
use yii\web\Session;
use yii\web\Request;
use yii\db\Expression;
use kartik\mpdf\Pdf;

$session = Yii::$app->session;
$session->open();

class KeranjangController extends \yii\web\Controller {

    function ConvertRealPath($path, $tag) {

        //jika tidak mempunyai gambar maka akan di set sampul secara default.
        if ($path == "" || $path == null) {
            $path = "../uploaded_files/sampul_koleksi/original/Monograf/tdkada.gif";
        } else {

            switch ($tag) {
                case 'Monograf':
                    $temp = $path;
                    $path = "../uploaded_files/sampul_koleksi/original/Monograf/" . $temp;
                    break;
                case 'Berkas Komputer':
                    $temp = $path;
                    $path = "../uploaded_files/sampul_koleksi/original/Berkas%20Komputer/" . $temp;
                    break;
                case 'Film':
                    $temp = $path;
                    $path = "../uploaded_files/sampul_koleksi/original/Film/" . $temp;
                    break;
                case 'Terbitan Berkala':
                    $temp = $path;
                    $path = "../uploaded_files/sampul_koleksi/original/Terbitan%20Berkala/" . $temp;
                    break;
                case 'Bahan Kartografis':
                    $temp = $path;
                    $path = "../uploaded_files/sampul_koleksi/original/Bahan%20Kartografis/" . $temp;
                    break;
                case 'Bahan Grafis':
                    $temp = $path;
                    $path = "../uploaded_files/sampul_koleksi/original/Bahan%20Grafis/" . $temp;
                    break;
                case 'Rekaman Video':
                    $temp = $path;
                    $path = "../uploaded_files/sampul_koleksi/original/Rekaman%20Video/" . $temp;
                    break;
                case 'Music':
                    $temp = $path;
                    $path = "../uploaded_files/sampul_koleksi/original/Music/" . $temp;
                    break;
                case 'Rekaman Suara':
                    $temp = $path;
                    $path = "../uploaded_files/sampul_koleksi/original/Rekaman%20Suara/" . $temp;
                    break;
                case 'Bentuk Mikro':
                    $temp = $path;
                    $path = "../uploaded_files/sampul_koleksi/original/Bentuk%20Mikro/" . $temp;
                    break;
                case 'Braile':
                    $temp = $path;
                    $path = "../uploaded_files/sampul_koleksi/original/Braile/" . $temp;
                    break;
                case 'Manuskrip':
                    $temp = $path;
                    $path = "../uploaded_files/sampul_koleksi/original/Manuskrip/" . $temp;
                    break;

                default:
                    $path = "../uploaded_files/sampul_koleksi/original/Monograf/tdkada.gif";
            }//end case
        }  //end if
        return $path;
    }

    public function actionIndex() {
        $isbooking = Yii::$app->config->get('IsBookingActivated');
        $session = Yii::$app->session;
        $request = Yii::$app->request;

        $alert = FALSE;
        if (isset($_POST['actions'])) {
            echo"<pre>";
            print_r($_POST['action']);
            echo"</pre>";
        }if (isset($_POST['action']) && $_POST['action'] === "email") {

            \Yii::$app->mail->compose()
                    ->setFrom('rico.ulul@gmail.com')
                    ->setTo($_POST['email'])
                    ->setSubject('This is a test mail ')
                    ->setTextBody('Plain text content')
                    ->setHtmlBody('<b>HTML content</b>')
                    ->send();
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'info',
                'duration' => 2500,
                'icon' => 'glyphicon glyphicon-ok-sign',
                'message' => Yii::t('app', '  Email Berhasil Di kirim'),
                'title' => 'success',
                'positonY' => Yii::$app->params['flashMessagePositionY'],
                'positonX' => Yii::$app->params['flashMessagePositionX']
            ]);

            /* 	return $this->renderAjax('alert', [
              ]); */
            $alert = TRUE;
        }
        if (/* $request->isAjax  && */isset($_POST['action']) && $_POST['action'] === "Hapus") {

            if (sizeof($_SESSION['catID']) <= 1) {
                $_SESSION['catIDmerge'] = null;
                $_SESSION['catID'] = null;
                echo"hahaha";
            } else {
                $hapus = array_diff($_SESSION['catID'], $_POST['catID']);
                $_SESSION['catID'] = array_values($hapus);
                $gabung = implode(",", $_SESSION['catID']);
                $_SESSION['catIDmerge'] = $gabung;
            }
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'info',
                'duration' => 2500,
                'icon' => 'glyphicon glyphicon-ok-sign',
                'message' => Yii::t('app', '  Data berhasil di hapus'),
                'title' => 'success',
                'positonY' => Yii::$app->params['flashMessagePositionY'],
                'positonX' => Yii::$app->params['flashMessagePositionX']
            ]);

            /* 	return $this->renderAjax('alert', [
              ]); */
            $alert = TRUE;
        }
        if (isset($_POST['action']) && $_POST['action'] === "Kosongkankeranjang") {
            $_SESSION['catIDmerge'] = null;
            $_SESSION['catID'] = null;
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'info',
                'duration' => 2500,
                'icon' => 'glyphicon glyphicon-ok-sign',
                'message' => Yii::t('app', '  Data berhasil di hapus'),
                'title' => 'success',
                'positonY' => Yii::$app->params['flashMessagePositionY'],
                'positonX' => Yii::$app->params['flashMessagePositionX']
            ]);
            $alert = TRUE;
        }
        $datas = $session->get('catIDmerge');

        if ($request->isAjax && $_GET['action'] === "showCollection") {

            if (Yii::$app->user->isGuest) {
                $noAnggota = null;
            } else {
                $noAnggota = \Yii::$app->user->identity->NoAnggota;
            }
            $catID = addslashes($_GET['catID']);
            $sqlCollectionList = "CALL showCollectionOpac(" . $catID . ");";
            $dataProviderCollectionList = new SqlDataProvider([
                'sql' => $sqlCollectionList,
                //'pagination'=> false,
                'pagination' => [ 'pageSize' => 20,],
            ]);

            $modelCollectionList = $dataProviderCollectionList->getModels();
            $countCollectionList = $dataProviderCollectionList->getCount();
            $temp = 1;
            foreach ($modelCollectionList as $value) {
                $dataCollectionList[$temp] = $value;
                $temp++;
            }
            if (!isset($dataCollectionList)) {
                $dataCollectionList = "";
            }

            return $this->renderAjax('_collectionlist', [
                        'dataProviderCollectionList' => $dataProviderCollectionList,
                        'countCollectionList' => $countCollectionList,
                        'dataCollectionList' => $dataCollectionList,
                        'noAnggota' => $noAnggota,
                        'catID' => $catID,
            ]);
        }
        if ($request->isAjax && $_GET['action'] === "favourite") {
            if (Yii::$app->user->isGuest) {
                return $this->redirect('/inlislite3/keanggotaan/site/login/');
            }
            $model = new favorite;
            (int) $count = favorite::find()
                    ->where(['Member_Id' => \Yii::$app->user->identity->NoAnggota, 'Catalog_Id' => $_GET['catID']])
                    ->count();

            if ($count == 0) {
                $model->Member_Id = \Yii::$app->user->identity->NoAnggota;
                $model->Catalog_Id = addslashes($_GET['catID']);
                $model->CreateDate = new Expression('NOW()');
                $model->save();

                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 2500,
                    'icon' => 'glyphicon glyphicon-ok-sign',
                    'message' => Yii::t('app', '  Telah Di Simpan ke-dalam daftar Favorite'),
                    'title' => 'success',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            } else {
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'danger',
                    'delay' => 2500,
                    'icon' => 'glyphicon glyphicon-remove',
                    'message' => Yii::t('app', ' Katalog ini sudah ada di dalam daftar Favorite anda'),
                    'title' => 'Gagal',
                    'body' => 'This is a successful growling alert.',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            }

            return $this->renderAjax('_favorite', [
                        'catID' => addslashes($_GET['catID']),
            ]);
        }

        if ($request->isAjax && $_GET['action'] === "showKontenDigital") {
            $catID = addslashes($_GET['catID']);
            $sqlCollectionList = "SELECT m.`Name` media, c.`NoInduk`, c.`CallNumber`, r.`Name` akses, l.`Name` lokasi, s.`Name` ketersediaan 
		FROM collections c 
		LEFT JOIN collectionmedias m ON c.`Media_id`=m.`ID` 
		LEFT JOIN collectionrules r ON c.`Rule_id`=r.`ID` 
		LEFT JOIN collectionstatus s ON c.`Status_id`=s.`ID`   
		LEFT JOIN collectionlocations l ON c.`Location_id`=l.`ID`
		WHERE c.`Catalog_id`=" . $catID;

            $dataProviderCollectionList = new SqlDataProvider([
                'sql' => $sqlCollectionList,
                //'pagination'=> false,
                'pagination' => [ 'pageSize' => 1,],
            ]);

            $modelCollectionList = $dataProviderCollectionList->getModels();
            $countCollectionList = $dataProviderCollectionList->getCount();
            $temp = 1;

            foreach ($modelCollectionList as $value) {
                $dataCollectionList[$temp] = $value;
                $temp++;
            }

            if (!isset($dataCollectionList)) {
                $dataCollectionList = "";
            }

            return $this->renderPartial('_collectionlist', [

                        'dataProviderCollectionList' => $dataProviderCollectionList,
                        'countCollectionList' => $countCollectionList,
                        'dataCollectionList' => $dataCollectionList,
            ]);
        }
        if ($request->isAjax && $_GET['action'] === "search") {
            $catID = addslashes($_GET['catID']);
            $sqlSearch = "
				SELECT CAT.id CatalogId,CAT.title kalimat2,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.worksheet_id, 
                (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
                (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND BookingExpiredDate < now()) JML_BUKU,
                (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
                (SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') 
                FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
			    
		FROM catalogs CAT JOIN collections col ON col.Catalog_id = CAT.ID
		 WHERE 
		CAT.isopac=1 AND
		CAT.ID=" . $catID . ";

				";
            $dataProviderSearch = new SqlDataProvider([
                'sql' => $sqlSearch,
                'pagination' => false,
            ]);

            $modelSearch = $dataProviderSearch->getModels();
            $countSearch = $dataProviderSearch->getCount();

            $temp = 1;
            foreach ($modelSearch as $value) {
                $dataSearch[$temp] = $value;
                $temp++;
            }

            return $this->renderAjax('_search', [
                        'dataResult' => $dataSearch,
            ]);
        }
        if ($request->isAjax && $_GET['action'] === "boooking") {

            if (Yii::$app->user->isGuest) {
                return $this->redirect('/inlislite3/keanggotaan/site/login/');
            }
            $colID = addslashes($_GET['colID']);

            $jmlBookMaks = Yii::$app->config->get('JumlahBookingMaksimal');
            $bookExp = Yii::$app->config->get('BookingExpired');
            //echo"jmlBookMaks = ".$jmlBookMaks;
            $noAnggota = \Yii::$app->user->identity->NoAnggota;
            $dateNow = new \DateTime("now");
            $dateAdd = new \DateTime("now");
            $dateAdd->modify("+" . $bookExp . " hours");

            $booking = Collections::find()
                    ->select([
                        'collections.BookingExpiredDate',
                        'catalogs.Title',
                    ])
                    ->leftJoin('catalogs', '`catalogs`.`ID` = `collections`.`Catalog_id`')
                    ->andWhere('BookingMemberID =' . $noAnggota)
                    ->andWhere('BookingExpiredDate >  "' . $dateNow->format("Y-m-d H:i:s") . '"')
                    ->all();
            if ($isbooking=='TRUE' && sizeof($booking) < $jmlBookMaks) {

                $modelCol = Collections::findOne($colID);
                //
                if ($modelCol->BookingExpiredDate == NULL || $modelCol->BookingExpiredDate < $dateNow->format("Y-m-d H:i:sO")) {
                    $modelLogs = new Bookinglogs;
                    $modelLogs->memberId = $noAnggota;
                    $modelLogs->collectionId = $colID;
                    $modelLogs->bookingDate = $dateNow->format("Y-m-d H:i:sO");
                    $modelLogs->bookingExpired = $dateAdd->format("Y-m-d H:i:sO");
                    $modelLogs->save();

                    $model = Collections::findOne($colID);
                    $model->BookingMemberID = $noAnggota;
                    $model->BookingExpiredDate = $dateAdd->format("Y-m-d H:i:sO");
                    $model->save(false);
                    ;
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 2500,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => Yii::t('app', 'Berhasil Booking'),
                        'title' => 'success',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
                }
            } else {
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'danger',
                    'delay' => 2500,
                    'icon' => 'glyphicon glyphicon-remove',
                    'message' => Yii::t('app', '  Gagal Booking, Maksimal booking ' . $jmlBookMaks . ' item '),
                    'title' => 'Gagal',
                    'body' => 'This is a successful growling alert.',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            }
        }

        if (isset($datas)) {
            $data = "CAT.ID IN (" . $datas . ") AND";
            $sqlKeranjang = "SELECT CAT.id,CAT.title kalimat2,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.CoverURL,CAT.Worksheet_id,
		(SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
		(SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND BookingExpiredDate < now()) JML_BUKU,
		(SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
		(SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') 
		FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
		FROM catalogs CAT WHERE " . $data . " CAT.isopac=1 ";
        } else {
            $sqlKeranjang = "SELECT * FROM catalogs WHERE 1 = 2";
            $data = "";
        }



        $dataProviderKeranjang = new SqlDataProvider([
            'sql' => $sqlKeranjang,
            'pagination' => false,
                //'pagination' => [ 'pageSize' => 20,],
        ]);

        $modelKeranjang = $dataProviderKeranjang->getModels();
        $countKeranjang = $dataProviderKeranjang->getCount();
        $temp = 1;
        foreach ($modelKeranjang as $value) {
            $dataKeranjang[$temp] = $value;
            $temp++;
        }
        if (!isset($dataKeranjang)) {
            $dataKeranjang = "";
        }

        return $this->render('index', [
                    'countKeranjang' => $countKeranjang,
                    'dataKeranjang' => $dataKeranjang,
                    'modelKeranjang' => $modelKeranjang,
                    'alert' => $alert,
        ]);
    }

    public function actionMpdfDemo1() {
        $data = "CAT.ID IN (230586,272125) AND";
        $sqlKeranjang = "SELECT CAT.id,CAT.title kalimat2,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.CoverURL,CAT.Worksheet_id,
		(SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
		(SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND BookingExpiredDate < now()) JML_BUKU,
		(SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
		(SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') 
		FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
		FROM catalogs CAT WHERE " . $data . " CAT.isopac=1 ";

        $dataProviderKeranjang = new SqlDataProvider([
            'sql' => $sqlKeranjang,
            'pagination' => false,
                //'pagination' => [ 'pageSize' => 20,],
        ]);

        $modelKeranjang = $dataProviderKeranjang->getModels();
        $countKeranjang = $dataProviderKeranjang->getCount();
        $temp = 1;
        foreach ($modelKeranjang as $value) {
            $dataKeranjang[$temp] = $value;
            $temp++;
        }

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'content' => $this->renderPartial('index', [
                'countKeranjang' => $countKeranjang,
                'dataKeranjang' => $dataKeranjang,
                'modelKeranjang' => $modelKeranjang,
                    //'alert' => $alert,
            ]),
            'options' => [
                'title' => 'Privacy Policy - Krajee.com',
                'subject' => 'Generating PDF files via yii2-mpdf extension has never been easy'
            ],
            'methods' => [
                'SetHeader' => ['Generated By: Krajee Pdf Component||Generated On: ' . date("r")],
                'SetFooter' => ['|Page {PAGENO}|'],
            ]
        ]);
        return $pdf->render();
    }

    public function actionCetak() {
        
    }

}
