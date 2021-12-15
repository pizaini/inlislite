<?php

namespace opac\controllers;

use Yii;
use common\models\Opaclogs;
use common\models\OpaclogsKeyword;
use common\models\Worksheets;
use common\models\Bookinglogs;
use common\models\Favorite;
use common\models\Collections;
use common\models\Catalogs;
use common\models\Requestcatalog;
use common\models\CollectionSearchKardeks;
use common\models\SerialArticlesSearch;
use common\models\Members;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;
use yii\web\Session;
use yii\web\Request;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use common\components\OpacHelpers;
$session = Yii::$app->session;
$session->open();

class PencarianSederhanaController extends \yii\web\Controller {
    public $layout = 'main-sederhana';
    public $location;
    public function actionIndex() {
        $location = Yii::$app->request->cookies->getValue('location_opac_id');
        $jmlBookMaks = Yii::$app->config->get('JumlahBookingMaksimal');
        $bookExp = Yii::$app->config->get('BookingExpired');
        $UsulanKoleksi = Yii::$app->config->get('UsulanKoleksi');
        $dateNow = new \DateTime("now");
        $noAnggota= (Yii::$app->user->isGuest ? null : \Yii::$app->user->identity->NoAnggota );
        $booking = OpacHelpers::jumlahBooking($noAnggota);

        $alert = false;
        $session = Yii::$app->session;
        $datas = $session->get('catIDmerge');
        $request = Yii::$app->request;
        $connection = Yii::$app->db;
        $url = Yii::$app->request->absoluteUrl;
        $waktu = date('m-d-Y H:i:s');
        $action = ( isset($_GET['action']) ) ? addslashes(urldecode($_GET['action'])) : "pencarianSederhana";

        $request = Yii::$app->request;
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
        if ($request->isAjax && $_GET['action'] === "requestCatalog") {
            $model = new requestcatalog;
            $model->MemberID = 1;
            $model->WorksheetID = 1;
            $model->Title = 1;
            $model->Author = 1;
            $model->PublishLocation = 1;
            $model->PublishYear = 1;
            $model->Comments = 1;
            $model->save();
        }

        if (Yii::$app->request->get() && $_GET['action'] === "pencarianSederhana") {              
            $bahan = addslashes($_GET['bahan']);
            $bahan1 = $bahan;
            if ($bahan != 'Semua Jenis Bahan') {
                $tmp = worksheets::find()
                    ->where(['id' => $bahan])
                    ->one();
                $bahan = $tmp['Name'];  
            }

   
            $Keyword = urldecode($_GET['katakunci']);
            $ruas = addslashes($_GET['ruas']);
            $dariTGL = ( isset($_GET['dariTGL']) ) ? addslashes($_GET['dariTGL']) : '';
            $sampaiTGL = ( isset($_GET['sampaiTGL']) ) ? addslashes($_GET['sampaiTGL']) : '';
            $ip = OpacHelpers::getIP();
            //catat history pencarian di session
            if (isset($_SESSION['RiwayatPencarian'])) {
                $temp = $_SESSION['RiwayatPencarian'];
                $_SESSION['RiwayatPencarian'] = array_merge($temp, array(
                    array(
                        "ip" =>  $ip,
                        "url" => $url,
                        "action" => addslashes($_GET['action']),
                        "keyword" => $ruas . " = " . $Keyword,
                        //"ruas" => $ruas,
                        "bahan" => $bahan,
                        "time" => $waktu,
                    )
                ));
            } else {
                $temp = array(
                    array(
                        "ip" =>  $ip,
                        "url" => $url,
                        "action" => addslashes($_GET['action']),
                        "keyword" => $ruas . " = " . $Keyword,
                        //"ruas" => $ruas,
                        "bahan" => $bahan,
                        "time" => $waktu,
                    )
                );
                $_SESSION['RiwayatPencarian'] = $temp;
            }


            $logs=[

                'user_id' => $noAnggota,
                'ip'      => $ip,
                'jenis_pencarian' => addslashes($_GET['action']),
                'keyword' => $ruas . " = " . $Keyword,
                'jenis_bahan' => $bahan,
                'url' => $url,
                'isLKD' => 0,
                'Field' => $ruas,
            ];
            //helper buat mencatat history pencarian di db
            OpacHelpers::opacLogs($logs);

            $page = ( isset($_GET['page']) ) ? addslashes($_GET['page']) : 1;
            $limit = ( isset($_GET['limit']) ) ? addslashes($_GET['limit']) : 10;
            $fAuthor = ( isset($_GET['fAuthor']) ) ? addslashes(urldecode($_GET['fAuthor'])) : '';
            $fPublisher = ( isset($_GET['fPublisher']) ) ? addslashes(urldecode($_GET['fPublisher'])) : '';
            $fPublishLoc = ( isset($_GET['fPublishLoc']) ) ? addslashes(urldecode($_GET['fPublishLoc'])) : '';
            $fPublishYear = ( isset($_GET['fPublishYear']) ) ? addslashes(urldecode($_GET['fPublishYear'])) : '';
            $fSubject = ( isset($_GET['fSubject']) ) ? addslashes(urldecode($_GET['fSubject'])) : '';
            $fBahasa = ( isset($_GET['fBahasa']) ) ? addslashes(urldecode($_GET['fBahasa'])) : '';


            $limitAwal = ($page - 1) * $limit;

            $Keyword = "%" . $Keyword . "%";
            $params = [':keyword' => $Keyword, ':ruas' => $ruas, ':bahan1' => $bahan1, ':fAuthor' => $fAuthor, ':fPublisher' => $fPublisher, ':fPublishLoc' => $fPublishLoc, ':fPublishYear' => $fPublishYear, ':fSubject' => $fSubject, ':fBahasa' => $fBahasa, ':dariTGL' => $dariTGL, ':sampaiTGL' => $sampaiTGL ];
            $params2 = [':keyword' => $Keyword, ':ruas' => $ruas, ':bahan1' => $bahan1, ':fAuthor' => $fAuthor, ':fPublisher' => $fPublisher, ':fPublishLoc' => $fPublishLoc, ':fPublishYear' => $fPublishYear, ':fSubject' => $fSubject, ':fBahasa' => $fBahasa, ':dariTGL' => $dariTGL, ':sampaiTGL' => $sampaiTGL, ':limitAwal' => $limitAwal, ':limit' => $limit ];
            
            //biar kalo pagging ga panggil insertTempOpacSederhana langsung nyari ke temporari

            if (!isset($_GET['page']) || (!isset($_SESSION['countSearch']))) {
                if ($location)
                {
                    $command = Yii::$app->db->createCommand("CALL insertTempSederhanaOpac(:keyword,:ruas,:bahan1,:fAuthor,:fPublisher,:fPublishLoc,:fPublishYear,:fSubject,:fBahasa,:dariTGL,:sampaiTGL,'',".$location." );");
                    $command->bindValues($params);
                    $command->execute();
                } else {
                    $command = Yii::$app->db->createCommand("CALL insertTempSederhanaOpac(:keyword,:ruas,:bahan1,:fAuthor,:fPublisher,:fPublishLoc,:fPublishYear,:fSubject,:fBahasa,:dariTGL,:sampaiTGL,'',0 );");
                    $command->bindValues($params);
                    $command->execute();
                }


            }
                else {

                if (!$location)
                {
                    $command = Yii::$app->db->createCommand("CALL insertTempSederhanaOpac0(:keyword,:ruas,:bahan1,:limitAwal,:limit,:fAuthor,:fPublisher,:fPublishLoc,:fPublishYear,:fSubject,:fBahasa,:dariTGL,:sampaiTGL,'',0 );");
                    $command->bindValues($params2);
                    $command->execute();
                } else {
                    $command = Yii::$app->db->createCommand("CALL insertTempSederhanaOpac0(:keyword,:ruas,:bahan1,:limitAwal,:limit,:fAuthor,:fPublisher,:fPublishLoc,:fPublishYear,:fSubject,:fBahasa,:dariTGL,:sampaiTGL,'',".$location." );");
                    $command->bindValues($params2);
                    $command->execute();
                }

            }

            /*$count = Yii::$app->db->createCommand("CALL countPencarianSederhanaOpac1('" . $fAuthor . "','" . $fPublisher . "','" . $fPublishLoc . "','" . $fPublishYear . "','" . $fSubject . "','" . $fBahasa . "');")->queryScalar();

            $sqlSearch = "CALL pencarianSederhanaOpacLimit1('0','" . $limit . "','" . $fAuthor . "','" . $fPublisher . "','" . $fPublishLoc . "','" . $fPublishYear . "','" . $fSubject . "','" . $fBahasa . "');";
            $dataProviderSearch = new SqlDataProvider([
                'sql' => $sqlSearch,
                'pagination' => false,
            ]);

            $modelSearch = $dataProviderSearch->getModels();
            $countSearch = $dataProviderSearch->getCount();*/

            $count = Yii::$app->db->createCommand("select count(1) from tempCariOpac")->queryScalar();
            $hasilSearch = Yii::$app->db->createCommand("select * from tempCariOpac limit 0,$limit")->queryAll();

            //get max faced
            $FacedAuthorMax = Yii::$app->config->get('FacedAuthorMax');
            $FacedPublisherMax = Yii::$app->config->get('FacedPublisherMax');
            $FacedPublishLocationMax = Yii::$app->config->get('FacedPublishLocationMax');
            $FacedPublishYearMax = Yii::$app->config->get('FacedPublishYearMax');
            $FacedSubjectMax = Yii::$app->config->get('FacedSubjectMax');
            $FacedBahasaMax = Yii::$app->config->get('FacedBahasaMax');

            //get min faced
            $FacedAuthorMin = Yii::$app->config->get('FacedAuthorMin');
            $FacedPublisherMin = Yii::$app->config->get('FacedPublisherMin');
            $FacedPublishLocationMin = Yii::$app->config->get('FacedPublishLocationMin');
            $FacedPublishYearMin = Yii::$app->config->get('FacedPublishYearMin');
            $FacedSubjectMin = Yii::$app->config->get('FacedSubjectMin');
            $FacedBahasaMin = Yii::$app->config->get('FacedBahasaMin');


            //buat generate faced

            /*$dataFacedAuthor = Yii::$app->db->createCommand("CALL facedAuthorOpac1('" . $fAuthor . "','" . $fPublisher . "','" . $fPublishLoc . "','" . $fPublishYear . "','" . $fSubject . "','" . $fBahasa . "','" . $FacedAuthorMax . "');")->queryAll();
            $dataFacedPublisher = Yii::$app->db->createCommand("CALL facedPublisherOpac1('" . $fAuthor . "','" . $fPublisher . "','" . $fPublishLoc . "','" . $fPublishYear . "','" . $fSubject . "','" . $fBahasa . "','" . $FacedPublisherMax . "');")->queryAll();
            $dataFacedPublishLocation = Yii::$app->db->createCommand("CALL facedPublishLocationOpac1('" . $fAuthor . "','" . $fPublisher . "','" . $fPublishLoc . "','" . $fPublishYear . "','" . $fSubject . "','" . $fBahasa . "','" . $FacedPublishLocationMax . "');")->queryAll();
            $dataFacedPublishYear = Yii::$app->db->createCommand("CALL facedPublishYearOpac1('" . $fAuthor . "','" . $fPublisher . "','" . $fPublishLoc . "','" . $fPublishYear . "','" . $fSubject . "','" . $fBahasa . "','" . $FacedPublishYearMax . "');")->queryAll();
            $dataFacedSubject = Yii::$app->db->createCommand("CALL facedSubjectOpac1('" . $fAuthor . "','" . $fPublisher . "','" . $fPublishLoc . "','" . $fPublishYear . "','" . $fSubject . "','" . $fBahasa . "','" . $FacedSubjectMax . "');")->queryAll();
            $dataFacedBahasa = Yii::$app->db->createCommand("CALL facedBahasaOpac1('" . $fAuthor . "','" . $fPublisher . "','" . $fPublishLoc . "','" . $fPublishYear . "','" . $fSubject . "','" . $fBahasa . "','" . $FacedBahasaMax . "');")->queryAll();


            $dataFacedAuthor = OpacHelpers::facedGenerator($dataFacedAuthor,'Author');
            $dataFacedPublisher = OpacHelpers::facedGenerator($dataFacedPublisher,'Publisher');
            $dataFacedPublishLocation = OpacHelpers::facedGenerator($dataFacedPublishLocation,'PublishLocation');
            $dataFacedPublishYear = OpacHelpers::facedGenerator($dataFacedPublishYear,'PublishYear');
            $dataFacedSubject = OpacHelpers::facedGenerator($dataFacedSubject,'SUBJECT');
            $dataFacedBahasa = OpacHelpers::facedGenerator($dataFacedBahasa,'bahasa');*/

            //faced tidak menggunakan sp lagi
            $req=array(
                'fAuthor' => $fAuthor,
                'fPublisher' => $fPublisher,
                'fPublishLoc' =>$fPublishLoc,
                'fPublishYear' => $fPublishYear,
                'fSubject' => $fSubject,
                'fBahasa' => $fBahasa);
            $dataFacedAuthor = OpacHelpers::facedGenerator(OpacHelpers::facedOpac('Author',$req),'Author');
            $dataFacedPublisher = OpacHelpers::facedGenerator(OpacHelpers::facedOpac('Publisher',$req),'Publisher');
            $dataFacedPublishLocation = OpacHelpers::facedGenerator(OpacHelpers::facedOpac('PublishLocation',$req),'PublishLocation');
            $dataFacedPublishYear = OpacHelpers::facedGenerator(OpacHelpers::facedOpac('PublishYear',$req),'PublishYear');
            $dataFacedSubject = OpacHelpers::facedGenerator(OpacHelpers::facedOpac('SUBJECT',$req),'SUBJECT');
            $dataFacedBahasa = OpacHelpers::facedGenerator(OpacHelpers::facedOpac('bahasa',$req),'bahasa');


            if (!isset($_GET['page']) || (!isset($_SESSION['countSearch']))) {
                $_SESSION['dataFacedAuthor'] = $dataFacedAuthor;
                $_SESSION['dataFacedPublisher'] = $dataFacedPublisher;
                $_SESSION['dataFacedPublishLocation'] = $dataFacedPublishLocation;
                $_SESSION['dataFacedPublishYear'] = $dataFacedPublishYear;
                $_SESSION['dataFacedSubject'] = $dataFacedSubject;
                $_SESSION['dataFacedBahasa'] = $dataFacedBahasa;
            } else {

                $dataFacedAuthor = $_SESSION['dataFacedAuthor'];
                $dataFacedPublisher = $_SESSION['dataFacedPublisher'];
                $dataFacedPublishLocation = $_SESSION['dataFacedPublishLocation'];
                $dataFacedPublishYear = $_SESSION['dataFacedPublishYear'];
                $dataFacedSubject = $_SESSION['dataFacedSubject'];
                $dataFacedBahasa = $_SESSION['dataFacedBahasa'];
            }

            //buat nyimpen total record yg dicari setiap pencarian
            //#temporary table problems fixed

            if (!isset($_GET['page']) || (!isset($_SESSION['countSearch']))) {
                $_SESSION['countSearch'] = $count;
            } else {

                $count = $_SESSION['countSearch'];
            }

            foreach ($hasilSearch as $key => $value) {
                // OpacHelpers::print__r($hasilSearch);
                $dataSearch[$key] = $value;
                $dataTagRDA        = OpacHelpers::getTaginfo($dataSearch[$key]['CatalogId'],'336,338','a');
                $jenisBahanRDA     = OpacHelpers::jenisBahanRDA($dataTagRDA);
                $jenis_bahanold    = $dataSearch[$key]['worksheet'];
                $dataSearch[$key]['worksheet'] = $jenis_bahanold." ".$jenisBahanRDA;
                $dataSearch[$key]['authOriginal'] =  array_values(array_filter(explode("|",OpacHelpers::sqlDetailOpac('PENGARANG',$dataSearch[$key]['CatalogId']))));
                $dataSearch[$key]['authModif'] = preg_replace("/\([^)]+\)/","",$dataSearch[$key]['authOriginal']);
                $dataSearch[$key]['keyword']=urldecode($_GET['katakunci']);
                $dataSearch[$key]['title'] =  OpacHelpers::highlight($dataSearch[$key]['title'],$dataSearch[$key]['keyword']);

                //replace authoriginal with highlighed string
                foreach ($dataSearch[$key]['authOriginal'] as $keys => &$values){
                    $values = OpacHelpers::highlight($values,$dataSearch[$key]['keyword']);
                }

            }

            
            



            //buat nyimpen session keranjang
            if (!isset($_SESSION['catID']) || $_SESSION['catID'] == '') {
                $_SESSION['catID'] = NULL;
            };
            if (!isset($_SESSION['catIDmerge']) || $_SESSION['catIDmerge'] == '') {
                $_SESSION['catIDmerge'] = NULL;
            };
            if (!isset($_POST['catID']) || $_POST['catID'] == '') {
                $_POST['catID'] = NULL;
            };
            if (!isset($_SESSION['catID']) || $_SESSION['catID'] == '') {
                $_SESSION['catID'] = NULL;
            };
            if (!isset($_SESSION['catIDmerge']) || $_SESSION['catIDmerge'] == '') {
                $_SESSION['catIDmerge'] = NULL;
            };
            if (!isset($_POST['catID']) || $_POST['catID'] == '') {
                $_POST['catID'] = NULL;
            };

            if (isset($_POST['action']) && $_POST['action'] == "keranjang" && isset($_POST['catID'])) {
                if (isset($_SESSION['catID'])) {

                    $temp = (is_array($_SESSION['catID']) ? $_SESSION['catID'] : array($_SESSION['catID']));
                    $duplicated = 0;
                    for ($i = 0; $i < sizeof($_POST['catID']); $i++) {
                        if (in_array($_POST['catID'][$i], $temp)) {
                            $duplicated+=1;
                        }
                    }
                    //menggabungkan catID di session dengan catID dari post//
                    $_SESSION['catID'] = array_unique(array_merge($temp, $_POST['catID']));

                    //pesan  ketika semua catalogID gagal dimasukkan ke keranjang
                    if (sizeof($_POST['catID']) == $duplicated) {
                        Yii::$app->getSession()->setFlash('error', [
                            'type' => 'danger',
                            'duration' => 3500,
                            'icon' => 'glyphicon glyphicon-ok-sign',
                            'message' => Yii::t('app', ' Katalog Gagal disimpan, Katalog sudah ada di dalam keranjang'),
                            'title' => 'Error',
                            'positonY' => Yii::$app->params['flashMessagePositionY'],
                            'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]);
                        $alert = TRUE;
                    } else
                    //pesan  ketika sebagian catalogID gagal dimasukkan ke keranjang
                    if ($duplicated != 0) {
                        Yii::$app->getSession()->setFlash('success', [
                            'type' => 'info',
                            'duration' => 2500,
                            'icon' => 'glyphicon glyphicon-ok-sign',
                            'message' => Yii::t('app', (sizeof($_POST['catID']) - $duplicated) . ' Katalog berhasil disimpan di dalam keranjang ' . $duplicated . ' Katalog gagal disimpan'),
                            'title' => 'success',
                            'positonY' => Yii::$app->params['flashMessagePositionY'],
                            'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]);
                        $alert = TRUE;
                    }
                    //pesan ketika semua catalogID berhasil di masukkan ke keranjang
                    else {
                        Yii::$app->getSession()->setFlash('success', [
                            'type' => 'info',
                            'duration' => 2500,
                            'icon' => 'glyphicon glyphicon-ok-sign',
                            'message' => Yii::t('app', sizeof($_POST['catID']) . ' Katalog berhasil disimpan di dalam keranjang'),
                            'title' => 'success',
                            'positonY' => Yii::$app->params['flashMessagePositionY'],
                            'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]);
                        $alert = TRUE;
                    }
                } else {
                    $_SESSION['catID'] = $_POST['catID'];
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 2500,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => Yii::t('app', sizeof($_POST['catID']) . ' Katalog berhasil disimpan di dalam keranjang'),
                        'title' => 'success',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
                    $alert = TRUE;
                }
                $gabung = implode(",", $_SESSION['catID']);
                $_SESSION['catIDmerge'] = $gabung;
            }
            // echo "<pre>";
            // print_r($dataSearch);
            // die;

            if (!isset($dataSearch)) {
                $dataSearch = "";
            }
            return $this->render('resultListOpac', [
                'countResult' => count($hasilSearch),
                'dataResult' => $dataSearch,
                'totalCountResult' => $count,
                'dataFacedAuthor' => $dataFacedAuthor,
                'dataFacedPublisher' => $dataFacedPublisher,
                'dataFacedPublishYear' => $dataFacedPublishYear,
                'dataFacedPublishLocation' => $dataFacedPublishLocation,
                'dataFacedSubject' => $dataFacedSubject,
                'dataFacedBahasa' => $dataFacedBahasa,
                'noAnggota' => $noAnggota,
                'alert' => $alert,
                'UsulanKoleksi' => $UsulanKoleksi,
                'booking' => $booking,
                'FacedAuthorMax' => $FacedAuthorMax,
                'FacedAuthorMin' => $FacedAuthorMin,
                'FacedPublisherMax' => $FacedPublisherMax,
                'FacedPublisherMin' => $FacedPublisherMin,
                'FacedPublishLocationMax' => $FacedPublishLocationMax,
                'FacedPublishLocationMin' => $FacedPublishLocationMin,
                'FacedPublishYearMax' => $FacedPublishYearMax,
                'FacedPublishYearMin' => $FacedPublishYearMin,
                'FacedSubjectMax' => $FacedSubjectMax,
                'FacedSubjectMin' => $FacedSubjectMin,
                'FacedBahasaMax' => $FacedBahasaMax,
                'FacedBahasaMin' => $FacedBahasaMin,
                'page' => $page,
                'limit' => $limit,
                'offset' => ceil($page / $limit),
                'fAuthor' => $fAuthor,
                'fPublisher' => $fPublisher,
                'fPublishLoc' => $fPublishLoc,
                'fPublishYear' => $fPublishYear,
                'fSubject' => $fSubject,
                'fBahasa' => $fBahasa,
                'action' => $action,
                'bases' => Yii::$app->homeUrl,
            ]);
        }
        
        if (!isset($_SESSION['catID']) || $_SESSION['catID'] == '') {
            $_SESSION['catID'] = NULL;
        };
        if (!isset($_SESSION['catIDmerge']) || $_SESSION['catIDmerge'] == '') {
            $_SESSION['catIDmerge'] = NULL;
        };
        if (!isset($_POST['catID']) || $_POST['catID'] == '') {
            $_POST['catID'] = NULL;
        };

        if (isset($_POST['catID'])) {
            if (isset($_SESSION['catID'])) {
                $temp = $_SESSION['catID'];
                //menggabungkan catID di session dengan catID dari post//
                $_SESSION['catID'] = array_unique(array_merge($temp, $_POST['catID']));
            } else {
                $_SESSION['catID'] = $_POST['catID'];
            }

            $gabung = implode(",", $_SESSION['catID']);
            $_SESSION['catIDmerge'] = $gabung;
        }
        
        if ($request->isAjax && $_GET['action'] === "showCollection") {

            $catID = $_GET['catID'];
            if ($_GET['serial'] == 1) {
                $searchModel = new CollectionSearchKardeks;
                $params['CatalogId'] = $_GET['catID'];
                $dataProvider = $searchModel->search2($params);
                return $this->renderAjax('_serial', [
                            'catID' => $catID,
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                ]);
            }


            $sqlCollectionList = "CALL showCollectionOpac(" . $catID . ");";

            $dataProviderCollectionList = new SqlDataProvider([
                'sql' => $sqlCollectionList,
                'pagination' => false,
                    //'pagination' => [ 'pageSize' => 20,],
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
                        'catID' => $catID
            ]);
        }
        if ($request->isAjax && $_GET['action'] === "showArticle") {
            /*$catID = $_GET['catID'];
            $hasilSearch = Yii::$app->db->createCommand("SELECT * FROM serial_articles WHERE Catalog_id =".$catID." ")->queryAll();

            return $this->renderAjax('_articleList', [

                'hasilSearch' => $hasilSearch,
                'noAnggota' => $noAnggota,
                'catID' => $catID
            ]);*/
            $catID = $_GET['catID'];
            $searchModel = new SerialArticlesSearch;
            $params['Catalog_id'] = $_GET['catID'];
            $dataProvider = $searchModel->advancedSearchByCatalogId($params,$rules=null);
            return $this->renderAjax('_serialArticle', [
                'catID' => $catID,
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]);
        }
        if ($request->isAjax && $_GET['action'] === "logDownload") {

            OpacHelpers::logsDownload($_GET['ID'],$noAnggota,'0');          
        }
        if ($request->isAjax && $_GET['action'] === "showKontenDigital") {
            $catID = $_GET['catID'];
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

            return $this->renderAjax('_kontendigitallist', [

                        'dataProviderCollectionList' => $dataProviderCollectionList,
                        'countCollectionList' => $countCollectionList,
                        'dataCollectionList' => $dataCollectionList,
                        'noAnggota' => $noAnggota,
                        'catID' => $catID,
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
        if ($request->isAjax && $_GET['action'] === "search") {
            $catID = $_GET['catID'];
            $pos  = $_GET['pos'];
            $sqlSearch = "
                SELECT CAT.id CatalogId,CAT.title kalimat2,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
                (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
                (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < now() || BookingExpiredDate is null)) JML_BUKU,
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
                $dataTagRDA        = OpacHelpers::getTaginfo($dataSearch[$temp]['CatalogId'],'336,338','a');
                $jenisBahanRDA     = OpacHelpers::jenisBahanRDA($dataTagRDA);
                $jenis_bahanold    = $dataSearch[$temp]['worksheet'];
                $dataSearch[$temp]['worksheet'] = $jenis_bahanold." ".$jenisBahanRDA;
                $temp++;
            }

            $dateNow = new \DateTime("now");

            return $this->renderAjax('_search', [
                        'dataResult' => $dataSearch,
                        'booking' => $booking,
                        'i' => $pos,
            ]);
        }

        return $this->render('index');
    }

    public function actionUsulan() {
        if (Yii::$app->user->isGuest) {
            $noAnggota = $_POST['formData']['NomorAnggota'];
        } else {
            $noAnggota = \Yii::$app->user->identity->NoAnggota;
        }


        $model = new requestcatalog;
        //$model->MemberID = $noAnggota;
        //$model->WorksheetID = 1;
        $model->WorksheetID = $_POST['formData']['JenisBahan'];
        $model->Title = $_POST['formData']['Judul'];
        $model->Author = $_POST['formData']['Pengarang'];
        $model->PublishLocation = $_POST['formData']['KotaTerbit'];
        $model->Publisher = $_POST['formData']['Penerbit'];
        $model->PublishYear = $_POST['formData']['TahunTerbit'];
        $model->Comments = $_POST['formData']['Keterangan'];
        $model->save(false);


        Yii::$app->getSession()->setFlash('success', [
            'type' => 'info',
            'delay' => 2500,
            'icon' => 'glyphicon glyphicon-remove',
            'message' => Yii::t('app', '  Data Berhasil Disimpan '),
            'title' => 'Sukses',
            'body' => 'This is a successful growling alert.',
            'positonY' => Yii::$app->params['flashMessagePositionY'],
            'positonX' => Yii::$app->params['flashMessagePositionX']
        ]);
        return $this->renderAjax('_usulan', [
                        
        ]);
    }


}
