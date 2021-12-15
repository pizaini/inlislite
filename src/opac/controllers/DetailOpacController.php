<?php

namespace opac\controllers;

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

    // public $layout = 'main-sederhana';

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
        // $layout = (Yii::$app->config->get('OpacIndexer') == 1) ? 'main-search' : 'main-sederhana';

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
            OpacHelpers::LogsDownload($_GET['ID'],$noAnggota,'0');          
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
            $detailOpac = Yii::$app->db->createCommand("

                SELECT  C.Worksheet_id,
                (SELECT C.CoverURL FROM catalogs C WHERE C.ID = :catalogID) AS CoverURL , 
                (SELECT W.name FROM worksheets W WHERE W.ID = C.worksheet_id) AS JENIS_BAHAN,
                (SELECT GROUP_CONCAT(CS.Value ORDER BY CR.Tag,CS.Sequence ASC SEPARATOR ' ') FROM catalog_subruas CS LEFT JOIN catalog_ruas CR ON CS.RuasID = CR.ID WHERE CR.CatalogId = C.ID AND CR.Tag IN (245) GROUP BY CR.CatalogId) AS JUDUL,
                (SELECT GROUP_CONCAT(CS.Value ORDER BY CR.Tag,CS.Sequence ASC SEPARATOR ' ') FROM catalog_subruas CS LEFT JOIN catalog_ruas CR ON CS.RuasID = CR.ID WHERE CR.CatalogId = C.ID AND CR.Tag IN (240) GROUP BY CR.CatalogId) AS JUDUL_SERAGAM,
                (

                     SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                      FROM
                        (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR ' ') AS RTRIM1
                        FROM
                          (SELECT *
                          FROM (
                            (SELECT CS.sequence,
                              CS.Ruasid,
                              CASE
                                WHEN CS.SUBRUAS = 'e'
                                THEN CONCAT ( '(' , CS.Value , ') |' )
                                ELSE CS.Value
                              END AS VAL1
                            FROM catalog_subruas CS
                            LEFT JOIN catalog_ruas CR
                            ON CS.RuasID       = CR.ID
                            WHERE CR.CatalogId = :catalogID
                            AND CR.Tag        IN (100,110,111,700,710,711,800,810,811)
                            AND CS.Subruas    IN ('a', 'd' ,'e')
                            GROUP BY CS.Ruasid,
                              CS.SUBRUAS,
                              CS.Value,
                              CS.Sequence
                            )) X
                          ORDER BY ruasid,
                            sequence
                          ) Y
                        GROUP BY Ruasid
                        ) Z
                ) AS PENGARANG,
                (


                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = :catalogID
                        AND CR.Tag        IN (250)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z


                ) AS EDISI,
                (


                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = :catalogID
                        AND CR.Tag        IN (490)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z


                ) AS PERNYATAAN_SERI,
                (

                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR ' ') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = :catalogID
                        AND CR.Tag        IN (260,264)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z

                ) AS PENERBITAN,
                (
                SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, 
                          CASE
                            WHEN CS.SUBRUAS = '3'
                            THEN CONCAT ( '[' , CS.Value , ']' )
                            ELSE CS.Value
                          END AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = :catalogID
                        AND CR.Tag        IN (300)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        ORDER BY CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z

                ) AS DESKRIPSI_FISIK,
                (
                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR ' ') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, 
                          -- CS.Value AS VAL1
                          CASE
                            WHEN CS.SUBRUAS = '3'
                            THEN CONCAT ( '[' , CS.Value , ']' )
                            ELSE CS.Value
                          END AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = :catalogID
                        AND CR.Tag        IN (336)
                        and CS.SubRuas <> 2 
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        ORDER BY sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z
                ) AS KONTEN,
                (
                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = :catalogID
                        AND CR.Tag        IN (337)
                        and CS.SubRuas <> 2 
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z

                ) AS MEDIA,
                (
                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = :catalogID
                        AND CR.Tag        IN (338)
                        and CS.SubRuas <> 2 
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z


                ) AS PENYIMPANAN_MEDIA,
                (

                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = :catalogID
                        AND CR.Tag        IN (538)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z


                ) AS INFORMASI_TEKNIS,
                (

                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = :catalogID
                        AND CR.Tag        IN (020)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z

                ) AS ISBN,
                (

                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = :catalogID
                        AND CR.Tag        IN (022)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z

                ) AS ISSN,
                (

                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = :catalogID
                        AND CR.Tag        IN (024)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z

                ) AS ISMN,
                (
                SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid,
                            CASE
                            WHEN CS.SubRuas <> 'a'
                            THEN
                             CONCAT(' ',CS.Value)
                            ELSE CS.Value
                          END AS VAL1

                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = :catalogID
                        AND CR.Tag        IN (600,610,611,650,651)
                        GROUP BY 
                          CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z


                ) AS SUBJEK,
                (


                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = :catalogID
                        AND CR.Tag        IN (520)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z



                ) AS ABSTRAK,
                (

                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = :catalogID
                        AND CR.Tag        IN (500,501,502,504,505,533,550)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z

                ) AS CATATAN,
                (SELECT RI.Name FROM catalog_ruas CR LEFT JOIN refferenceitems RI ON TRIM(RI.Code) = SUBSTRING(CR.VALUE,36,3) WHERE CR.CatalogId = C.ID AND CR.Tag = 008 AND RI.Refference_id = 5) AS BAHASA,
                (SELECT RI.Name FROM catalog_ruas CR LEFT JOIN refferenceitems RI ON TRIM(RI.Code) = SUBSTRING(CR.VALUE,34,1) WHERE CR.CatalogId = C.ID AND CR.Tag = 008 AND RI.Refference_id = 17) AS BENTUK_KARYA,
                (SELECT RI.Name FROM catalog_ruas CR LEFT JOIN refferenceitems RI ON TRIM(RI.Code) = SUBSTRING(CR.VALUE,23,1) WHERE CR.CatalogId = C.ID AND CR.Tag = 008 AND RI.Refference_id = 2) AS TARGET_PEMBACA,
                (

                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = :catalogID
                        AND CR.Tag        IN (856)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z


                ) AS LOKASI_AKSES_ONLINE
                FROM catalogs C WHERE C.ID = :catalogID AND isopac = 1

            ");
            $detailOpac->bindValue(':catalogID', $catalogID);
            $detailOpac = $detailOpac->queryAll();
            // echo'<pre>';print_r($detailOpac);die;
            $marcOpac = Yii::$app->db->createCommand(" call marcCatalogOpac(:catalogID)");
            $marcOpac->bindValue(':catalogID', $catalogID);
            $marcOpac = $marcOpac->queryall();
            $collectionDetail = Yii::$app->db->createCommand(" call showCollectionOpac(:catalogID)");
            $collectionDetail->bindValue(':catalogID', $catalogID);
            $collectionDetail = $collectionDetail->queryall();
            $sqlCollectionList = "CALL showKontenDigital(:catalogID); ";

            $dataSub = array_values(array_filter(explode("|", $detailOpac[0]["SUBJEK"])));
            $dataAuth = array_values(array_filter(explode("|", $detailOpac[0]["PENGARANG"])));
            
            $sqlAuthor;
            $sqlSub;
            foreach ($dataAuth as $key => $value) {
              if (sizeof($dataAuth)==1)
              {
                $fil=preg_replace('#\s*\(.+\)\s*#U', ' ', $value);
                $fil=addslashes($fil);
                $sqlAuthor.="author like'%".$fil."%' ";
              } else {
                if (sizeof($dataAuth)==($key+1)) {
                  $fil=preg_replace('#\s*\(.+\)\s*#U', ' ', $value);
                  $fil=addslashes($fil);
                  $sqlAuthor.="author like'%".$fil."%' ";
                } else {
                  $fil=preg_replace('#\s*\(.+\)\s*#U', ' ', $value);
                  $fil=addslashes($fil);
                  $sqlAuthor.="author like'%".$fil."%' OR ";

                }

              }
            }
            foreach ($dataSub as $key => $value) {
              $value=addslashes($value);
              if (sizeof($dataSub)==1)
              {
                $sqlSub.="Subject like'%".$value."%' ";
              } else {
                if (sizeof($dataSub)==($key+1)) {
                  $sqlSub.="Subject like'%".$value."%' ";
                } else {                 
                  $sqlSub.="Subject like'%".$value."%' OR ";

                }

              }
            }
            if (sizeof($detailOpac[0]["PENGARANG"])!==0){
              // $similiar= Yii::$app->db->createCommand(" select * from catalogs where ( ".$sqlAuthor." ) and ID <>  ".$catalogID." limit 10")->queryall();
              $similiar= Yii::$app->db->createCommand(" select * from catalogs where ( ".$sqlAuthor." ) and ID <>  :catalogID limit 10");
              $similiar->bindValue(':catalogID', $catalogID);
              $similiar = $similiar->queryall();
            } 
            if (sizeof($detailOpac[0]["SUBJEK"])!==0){
              //echo "select * from catalogs where ( ".$sqlSub." ) and ID <>  ".$catalogID." limit 10"; die;
              // $similiar= Yii::$app->db->createCommand(" select * from catalogs where ( ".$sqlSub." ) and ID <>  ".$catalogID." limit 10")->queryall();
              $similiar= Yii::$app->db->createCommand(" select * from catalogs where ( ".$sqlSub." ) and ID <>  :catalogID limit 10");
              $similiar->bindValue(':catalogID', $catalogID);
              $similiar = $similiar->queryall();
            } 
            if (sizeof($detailOpac[0]["PENGARANG"])==0 && sizeof($detailOpac[0]["SUBJEK"]) ==0) {
              $similiar=null;
            } 
            if (sizeof($detailOpac[0]["PENGARANG"])!==0 && sizeof($detailOpac[0]["SUBJEK"]) !==0)
            {
              $similiar= Yii::$app->db->createCommand(" select * from catalogs where ( ".$sqlAuthor." or ".$sqlSub." ) and ID <>  :catalogID limit 10");
              $similiar->bindValue(':catalogID', $catalogID);
              $similiar = $similiar->queryall();
            }
           
            $KontenDigital = Yii::$app->db->createCommand( $sqlCollectionList);
            $KontenDigital->bindValue(':catalogID', $catalogID);
            $KontenDigital = $KontenDigital->queryall();

            
            //kalo ga ketemu return error(mungkin salah ID,tidak dipublish)

            if(sizeof($detailOpac)==0){

                throw new \yii\web\NotFoundHttpException("Maaf Data Yang anda cari tidak ditemukan");
            }

            $judul=explode('/', $detailOpac[0]['JUDUL']);
            //similiar Title
            //$similiarTitle = Yii::$app->db->createCommand(" SELECT ID,Title FROM catalogs WHERE MATCH (title) AGAINST ('".addslashes($judul[0])."' IN BOOLEAN MODE) and isopac=1 and ID !=".$catalogID." limit 5;")->queryall();
            //$similiarTitle = Yii::$app->db->createCommand(" SELECT ID,Title FROM catalogs WHERE title like '%".$judul[0]."%'  and isopac=1 and ID !=".$catalogID." limit 5;")->queryall();
            //$similiarTitle = Yii::$app->db->createCommand(" select levenshtein('".$judul[0]."',Title) as nilai, ID,Title from catalogs where  isopac=1 and ID !=".$catalogID." order by nilai desc limit 5")->queryall();
            $modelcite = Catalogs::findOne($catalogID);
            $cite['APA'] = $modelcite->Author . ".<i>" . $this->HurufBesarTiapKata($modelcite->Title) . " </i>." . $modelcite->PublishYear;

            $searchModelSerial = new CollectionSearchKardeks;
            $params['CatalogId'] = $catalogID;
            $dataProviderSerial = $searchModelSerial->search2($params);

            // $this->layout = (Yii::$app->config->get('OpacIndexer') == 1) ? 'main-sederhana-search' : 'main-kosong';
            $this->layout = 'main-detail';
            $indexer = (Yii::$app->config->get('OpacIndexer') == 1) ? 'search/index' : 'pencarian-sederhana';

            return $this->render('index', [
                        'detailOpac' => $detailOpac,
                        'marcOpac' => $marcOpac,
                        'collectionDetail' => $collectionDetail,
                        'KontenDigital' => $KontenDigital,
                        'similiarTitle' => $similiar,
                        'noAnggota' => $noAnggota,
                        'catalogID' => $catalogID,
                        'cite' => $cite,
                        'dataProviderSerial' => $dataProviderSerial,
                        'searchModelSerial' => $searchModelSerial,
                        'indexer' => $indexer
            ]);
        }
    }
    public function actionDownload($id,$type)
    {
        MarcHelpers::Export($id,$type);

    }


}
