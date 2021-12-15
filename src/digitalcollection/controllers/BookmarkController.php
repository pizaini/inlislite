<?php

namespace digitalcollection\controllers;

use Yii;
use common\models\Collections;
use common\models\CollectionmediaSearch;
use common\models\CollectionSearch;
use common\models\CatalogsSearch;
use common\models\Catalogs;
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
use common\components\MarcHelpers;
$session = Yii::$app->session;
$session->open();
use common\components\OpacHelpers;

class BookmarkController extends \yii\web\Controller {

    public $layout = 'main-sederhana';

    public function actionIndex() {
        $session = Yii::$app->session;
        $request = Yii::$app->request;
        $alert = FALSE;
        $jmlBookMaks = Yii::$app->config->get('JumlahBookingMaksimal');
        $bookExp = Yii::$app->config->get('BookingExpired');
        $isbooking = Yii::$app->config->get('IsBookingActivated');
        $noAnggota= (Yii::$app->user->isGuest ? null : \Yii::$app->user->identity->NoAnggota );
        $booking = OpacHelpers::jumlahBooking($noAnggota);

        if (isset($_POST['Download']) && $_POST['Download'] !='') {

            if (isset($_POST['catID'])) {
        
                switch ($_POST['Download']) {
                    case 'Format MARC Unicode/UTF-8':
                        $type="marc21";
                        break;
                    case 'Format MARC XML':
                        $type="MARCXML";
                        break;
                    case 'Format MODS':
                        $type="MODS";
                        break;
                    case 'Format Dublin Core (RDF)':
                        $type="DC_RDF";
                        break;
                    case 'Format Dublin Core (OAI)':
                        $type="DC_OAI";
                        break;
                    case 'Format Dublin Core (SRW)':
                        $type="DC_SRW";
                        break;                
                }

                
                $id=$_POST['catID'];
                if (sizeof($id)==1) {
                    MarcHelpers::Export($id[0],$type);
                } else {
                    MarcHelpers::MultipleExport($id,$type);
                }
                return $this->redirect('bookmark');
            } else
            {
                Yii::$app->getSession()->setFlash('error', [
                'type' => 'danger',
                'duration' => 2500,
                'icon' => 'glyphicon glyphicon-remove-sign',
                'message' => Yii::t('app', '  Data berhasil di hapus'),
                'title' => 'success',
                'positonY' => Yii::$app->params['flashMessagePositionY'],
                'positonX' => Yii::$app->params['flashMessagePositionX']
            ]);
            $alert = TRUE;
            return $this->redirect('bookmark');
            }
        }
        if (isset($_POST['action']) && $_POST['action'] === "email" && $_POST['Download']=='') {    

        if (!isset($_POST['email'])) {

            Yii::$app->getSession()->setFlash('error', [
                'type' => 'danger',
                'duration' => 2500,
                'icon' => 'glyphicon glyphicon-ok-sign',
                'message' => Yii::t('app', '  Alamat Email tidak boleh kosong'),
                'title' => 'Gagal',
                'positonY' => Yii::$app->params['flashMessagePositionY'],
                'positonX' => Yii::$app->params['flashMessagePositionX']
            ]);

          
            $alert = TRUE;
        } elseif (!isset($_POST['catID'])) {
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'danger',
                'duration' => 2500,
                'icon' => 'glyphicon glyphicon-ok-sign',
                'message' => Yii::t('app', '  Anda harus memilih judul buku yang akan di kirim lewat email'),
                'title' => 'Gagal',
                'positonY' => Yii::$app->params['flashMessagePositionY'],
                'positonX' => Yii::$app->params['flashMessagePositionX']
            ]);

          
            $alert = TRUE;
        } else {



            for ($i=0; $i <sizeof($_POST['catID']) ; $i++) { 
                $email = Yii::$app->urlManager->createAbsoluteUrl('detail-opac?id='.$_POST['catID'][$i]);
                $judul = Catalogs::find()->where(['ID'=>$_POST['catID'][$i]])->one();
                $konten.="<br>
                          <a href='".$email."'> ".$judul['Title']." </a>
                        <br>";
            }

            \Yii::$app->mailer->compose()
                    ->setFrom('inlis.pnri@gmail.com')
                    ->setTo($_POST['email'])
                    ->setSubject('Kirim Judul buku ke-email')
                    //->setTextBody('Plain text content')
                    ->setHtmlBody($konten)
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

          
            $alert = TRUE;
        }}
        if (/* $request->isAjax  && */isset($_POST['action']) && $_POST['action'] === "Hapus") {

            if (sizeof($_SESSION['catID']) <= 1) {
                $_SESSION['catIDmerge'] = null;
                $_SESSION['catID'] = null;
                //echo"hahaha";
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
        if (isset($_POST['action']) && $_POST['action'] === "KosongkanBookmark") {
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
                return $this->redirect('../keanggotaan/site/login');
            }
            $model = new favorite;
            (int) $count = favorite::find()
                    ->where(['Member_Id' => \Yii::$app->user->identity->NoAnggota, 'Catalog_Id' => addslashes($_GET['catID'])])
                    ->count();

            if ($count == 0) {
                $model->Member_Id = \Yii::$app->user->identity->NoAnggota;
                $model->Catalog_Id = addslashes($_GET['catID']);
                //$model->CreateDate = new Expression('NOW()');
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
            $sqlCollectionList = "CALL showKontenDigital(" . $catID . "); ";

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

            return $this->renderPartial('_kontendigitallist', [

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
                return $this->redirect('../keanggotaan/site/login');
            }
            $colID = $_GET['colID'];
            $cekBooking = OpacHelpers::cekBooking($noAnggota,$colID);       
            $noAnggota = \Yii::$app->user->identity->NoAnggota;
            $dateNow = new \DateTime("now");
            $dateAdd = new \DateTime("now");
            $bookingTime=OpacHelpers::SetBookingTime($bookExp);

            if (!$cekBooking) {
                
                    $modelLogs = new Bookinglogs;
                    $modelLogs->memberId = $noAnggota;
                    $modelLogs->collectionId = $colID;
                    $modelLogs->bookingDate = $dateNow->format("Y-m-d H:i:sO");
                    $modelLogs->bookingExpired = $dateAdd->format("Y-m-d H:i:sO");
                    $modelLogs->save();
                    
                    $params2 = [':ID' => $colID, ':BookingMemberID' => $noAnggota, ':BookingExpiredDate' => $dateAdd->format("Y-m-d H:i:sO")];
                    $command = Yii::$app->db->createCommand("UPDATE collections SET BookingMemberID=:BookingMemberID, BookingExpiredDate=:BookingExpiredDate WHERE ID=:ID;");
                    $command->bindValues($params2);
                    $command->execute();

                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 2500,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => Yii::t('app', 'Berhasil Booking'),
                        'title' => 'success',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
            } else {
                $pesan=implode(",", $cekBooking);
                    Yii::$app->getSession()->setFlash('error', [
                    'type' => 'danger',
                    'delay' => 3500,
                    'icon' => 'glyphicon glyphicon-remove',
                    'message' => Yii::t('app', '  Gagal Booking, '.$pesan),
                    'title' => 'Gagal',
                    'body' => 'This is a successful growling alert.',
                    'positonY' => 'top',
                    'positonX' => 'right'
                ]);
                
            }

            
                return $this->renderAjax('alert', [
                            'booking' => $booking,
   
                ]);
        }
        if ($request->isAjax && $_GET['action'] === "logDownload") {
            OpacHelpers::logsDownload($_GET['ID'],$noAnggota,'1');          
        }


        if (isset($datas)) {
            $data = "CAT.ID IN (" . $datas . ") AND";
            $sqlBookmark = "SELECT CAT.id,CAT.title kalimat2,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.CoverURL,CAT.worksheet_id,
		(SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
		(SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND BookingExpiredDate < now()) JML_BUKU,
		(SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
		(SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') 
		FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
		FROM catalogs CAT WHERE " . $data . " CAT.isopac=1 ";
        } else {
            $sqlBookmark = "SELECT * FROM catalogs WHERE 1 = 2";
            $data = "";
        }



        $dataProviderBookmark = new SqlDataProvider([
            'sql' => $sqlBookmark,
            'pagination' => false,
                //'pagination' => [ 'pageSize' => 20,],
        ]);

        $modelBookmark = $dataProviderBookmark->getModels();
        $countBookmark = $dataProviderBookmark->getCount();
        $temp = 1;
        foreach ($modelBookmark as $value) {
            $dataBookmark[$temp] = $value;
            $temp++;
        }
        if (!isset($dataBookmark)) {
            $dataBookmark = "";
        }

        return $this->render('index', [
                    'countBookmark' => $countBookmark,
                    'dataBookmark' => $dataBookmark,
                    'modelBookmark' => $modelBookmark,
                    'alert' => $alert,
                    'noAnggota' => $noAnggota,
        ]);
    }



    public function actionCetak() {
        
    }
    public function actionDownload($id,$type)
    {
        MarcHelpers::Export($id,$type);
        /*$id=[22,23,24];
        MarcHelpers::MultipleExport($id,$type);
*/        //MarcHelpers::MultipleExport($id,$type);
    }

}
