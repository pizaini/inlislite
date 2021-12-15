<?php

namespace digitalcollection\controllers;

use Yii;
use common\models\Opaclogs;
use common\models\Bookinglogs;
use common\models\Favorite;
use common\models\Collections;
use common\models\Catalogs;
use common\models\Requestcatalog;
use common\models\CollectionSearchKardeks;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;
use yii\web\Session;
use yii\web\Request;
use common\components\MarcHelpers;
use common\components\OpacHelpers;
$session = Yii::$app->session;
$session->open();

class DetailOpacController extends \yii\web\Controller {

    public $layout = 'main-sederhana';

    function HurufBesarTiapKata($string) {

        $pieces = explode(" ", $string);
        for ($i = 0; $i < sizeof($pieces); $i++) {
            $temp = $pieces[$i];
            $pieces[$i] = ucfirst($temp);
        }
        $imp = implode(" ", $pieces);
        return $imp;
    }

    public function actionIndex() {
        // print_r($_GET);die;
        $isbooking = Yii::$app->config->get('IsBookingActivated');
        $jmlBookMaks = Yii::$app->config->get('JumlahBookingMaksimal');
        $bookExp = Yii::$app->config->get('BookingExpired');
        $noAnggota= (Yii::$app->user->isGuest ? null : \Yii::$app->user->identity->NoAnggota );
        $booking = OpacHelpers::jumlahBooking($noAnggota);
        $dateNow = new \DateTime("now");
        //$isHoliday = OpacHelpers::isHoliday( $dateNow->format("Y-m-d"));


        $request = Yii::$app->request;
        if ($request->isAjax && $_GET['action'] === "keranjang") {
            //di set null bila data ga ada
            if (!isset($_SESSION['catID']) || $_SESSION['catID'] == '') {
                $_SESSION['catID'] = NULL;
            };
            if (!isset($_SESSION['catIDmerge']) || $_SESSION['catIDmerge'] == '') {
                $_SESSION['catIDmerge'] = NULL;
            };
            if (!isset($_GET['catID']) || $_GET['catID'] == '') {
                $_GET['catID'] = NULL;
            };

            //cek catID
            if (isset($_GET['catID'])) {
                //cek apakah sudah ada session belum, jika belum bikin baru, jika udah di tambah
                if (isset($_SESSION['catID'])) {
                    //cek jika catID yg masu di tambah sudah di array belum, jika udah kasih pesan error.
                    if (in_array($_GET['catID'], (is_array($_SESSION['catID']) ? $_SESSION['catID'] : array($_SESSION['catID'])))) {

                        Yii::$app->getSession()->setFlash('error', [
                            'type' => 'danger',
                            'delay' => 2500,
                            'icon' => 'glyphicon glyphicon-remove',
                            'message' => Yii::t('app', ' Katalog ini sudah ada di dalam Keranjang anda'),
                            'title' => 'Gagal',
                            'body' => 'This is a successful growling alert.',
                            'positonY' => Yii::$app->params['flashMessagePositionY'],
                            'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]);
                    } else {

                        if (sizeof($_SESSION['catID'] == 1) && sizeof($_GET['catID']) == 1) {

                            $t1 = array($_SESSION['catID']);
                            $t2 = array($_GET['catID']);
                            $_SESSION['catID'] = array_unique(array_merge($t1, $t2));
                            $gabung = implode(",", $_SESSION['catID']);
                            $_SESSION['catIDmerge'] = $gabung;
                        } else {
                            $temp = (is_array($_SESSION['catID']) ? $_SESSION['catID'] : array($_SESSION['catID']));
                            $temp2 = (is_array($_GET['catID']) ? $_GET['catID'] : array($_GET['catID']));

                            $_SESSION['catID'] = array_unique(array_merge($temp, $temp2));
                            $gabung = implode(",", $_SESSION['catID']);
                            $_SESSION['catIDmerge'] = $gabung;
                        }
                    }
                } else {
                    $_SESSION['catID'] = $_GET['catID'];
                    $_SESSION['catIDmerge'] = $_GET['catID'];

                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 2500,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => Yii::t('app', '  Berhasil ditambah ke-keranjang'),
                        'title' => 'success',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
                }
            }
            return $this->renderAjax('_alert', [
            ]);
        }

        if ($request->isAjax && $_GET['action'] === "logDownload") {
            OpacHelpers::LogsDownload($_GET['ID'],$noAnggota,'1');          
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
            /*$tambahJam= explode(":",$bookExp);


            $dateAdd->modify("+".$tambahJam[0]." hours +".$tambahJam[1]." minutes +".$tambahJam[2]." seconds");*/

            if (!$cekBooking) {
                
                    $modelLogs = new Bookinglogs;
                    $modelLogs->memberId = $noAnggota;
                    $modelLogs->collectionId = $colID;
                    $modelLogs->bookingDate = $dateNow->format("Y-m-d H:i:sO");
                    $modelLogs->bookingExpired = $bookingTime->format("Y-m-d H:i:sO");
                    $modelLogs->save();
                    
                    $params2 = [':ID' => $colID, ':BookingMemberID' => $noAnggota, ':BookingExpiredDate' => $bookingTime->format("Y-m-d H:i:sO")];
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
         else {

            $catalogID = $_GET['id'];
            $detailOpac = Yii::$app->db->createCommand(" call detailCatalogOpac(" . $catalogID . ")")->queryall();
            $marcOpac = Yii::$app->db->createCommand(" call marcCatalogOpac(" . $catalogID . ")")->queryall();
            $collectionDetail = Yii::$app->db->createCommand(" call showCollectionOpac(" . $catalogID . ")")->queryall();
            $sqlCollectionList = "CALL showKontenDigital(" . $catalogID . "); ";
            $KontenDigital = Yii::$app->db->createCommand( $sqlCollectionList)->queryall();
            //kalo ga ketemu return error(mungkin salah ID,tidak dipublish)
            if(sizeof($detailOpac)==0){

                throw new \yii\web\NotFoundHttpException("Maaf Data Yang anda cari tidak ditemukan");
            }
            // echo "<pre>";
            // print_r($KontenDigital);die;

            $judul=explode('/', $detailOpac[0]['JUDUL']);
            //similiar Title
            //$similiarTitle = Yii::$app->db->createCommand(" SELECT ID,Title FROM catalogs WHERE MATCH (title) AGAINST ('".addslashes($judul[0])."' IN BOOLEAN MODE) and isopac=1 and ID !=".$catalogID." limit 5;")->queryall();
            // $similiarTitle = Yii::$app->db->createCommand(" SELECT ID,Title FROM catalogs WHERE title like '%".$judul[0]."%'  and isopac=1 and ID !=".$catalogID." limit 5;")->queryall();
            // $similiarTitle = Yii::$app->db->createCommand(" select levenshtein('".$judul[0]."',Title) as nilai, ID,Title from catalogs where  isopac=1 and ID !=".$catalogID." order by nilai desc limit 5")->queryall();
            $word = $judul[0];
            $words = array();
            for ($i = 0; $i < strlen($word); $i++) {
                // insertions
                $words[] = substr($word, 0, $i) . '_' . substr($word, $i);
                // deletions
                $words[] = substr($word, 0, $i) . substr($word, $i + 1);
                // substitutions
                $words[] = substr($word, 0, $i) . '_' . substr($word, $i + 1);
            }
            // last insertion
            $words[] = $word . '_';
            // return $words;
            foreach ($words as $key => $w) {
                $like .= ' Title LIKE "%'.$w.'%" '.'OR ';
            }
            $like = "(".rtrim($like, 'OR ').")";
            $similiarTitle = Yii::$app->db->createCommand(" select Title as nilai, ID,Title from catalogs where  isopac=1 and ID !=".$catalogID." and ".$like." order by nilai desc limit 5")->queryall();
            // echo "<pre>";
            // print_r($similiarTitle);die;
            $modelcite = Catalogs::findOne($catalogID);

            $cite['APA'] = $modelcite->Author . ".<i>" . $this->HurufBesarTiapKata($modelcite->Title) . " </i>." . $modelcite->PublishYear;



            $searchModelSerial = new CollectionSearchKardeks;
            $params['CatalogId'] = $catalogID;
            $dataProviderSerial = $searchModelSerial->search2($params);

            return $this->render('index', [

                       
                        'detailOpac' => $detailOpac,
                        'marcOpac' => $marcOpac,
                        'collectionDetail' => $collectionDetail,
                        'KontenDigital' => $KontenDigital,
                        // 'similiarTitle' => $similiarTitle,
                        'noAnggota' => $noAnggota,
                        'catalogID' => $catalogID,
                        'cite' => $cite,
                        'dataProviderSerial' => $dataProviderSerial,
                        'searchModelSerial' => $searchModelSerial,
            ]);
        }
    }
    public function actionDownload($id,$type)
    {
        MarcHelpers::Export($id,$type);

    }


}
