<?php

namespace digitalcollection\controllers;

use Yii;
use common\models\Opaclogs;
use common\models\Bookinglogs;
use common\models\Favorite;
use common\models\Collections;
use common\models\Catalogs;
use common\models\CollectionSearchKardeks;
use common\models\SerialArticlesSearch;
use yii\data\SqlDataProvider;
use yii\web\Session;
use yii\web\Request;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\components\OpacHelpers;
use common\models\OpaclogsKeyword;

class BrowseController extends \yii\web\Controller {

    public $layout = 'main';
	public $location;
	
    public function actionIndex() {
		$location = Yii::$app->request->cookies->getValue('location_opac_id') ? Yii::$app->request->cookies->getValue('location_opac_id') : 0;
        $jmlBookMaks = Yii::$app->config->get('JumlahBookingMaksimal');
        $bookExp = Yii::$app->config->get('BookingExpired');
        $UsulanKoleksi = Yii::$app->config->get('UsulanKoleksi');
        $dateNow = new \DateTime("now");
        $noAnggota= (Yii::$app->user->isGuest ? null : \Yii::$app->user->identity->NoAnggota );
        $booking = OpacHelpers::jumlahBooking($noAnggota);
        $alert = FALSE;

        $request = Yii::$app->request;
        if (isset($_GET['tag']) && isset($_GET['findBy']) && isset($_GET['query']) && isset($_GET['query2'])) {

            $tag = addslashes($_GET['tag']);
            $findBy = addslashes($_GET['findBy']);
            $query = addslashes($_GET['query']);
            $query2 = addslashes($_GET['query2']);
            $session = Yii::$app->session;
            $datas = $session->get('catIDmerge');
            $connection = Yii::$app->db;
            $url = Yii::$app->request->absoluteUrl;
            //$waktu=$this->getTime();
            $waktu = date('m-d-Y H:i:s');
            $record = "tag = " . $tag . " & findBy = " . $findBy . " & keyword1 = " . $query . " & keyword2 = " . $query2;

            switch ($tag) {
                case 'Author':
                    $tagID='Pengarang';
                    break;
                case 'Subject':
                    $tagID='Subyek';
                    break;
                case 'Publisher':
                    $tagID='Penerbit';
                    break;
                case 'PublishLocation':
                   $tagID='Tempat Terbit';
                    break;
                case 'PublishYear':
                    $tagID='Tahun Terbit';
                    break;
                case 'Alphabetical':
                    $tagID='Alphabetical';
                    break;
            }
            switch ($findBy) {
                case 'Author':
                    $findByID='Pengarang';
                    break;
                case 'Subject':
                    $findByID='Subyek';
                    break;
                case 'Publisher':
                    $findByID='Penerbit';
                    break;
                case 'PublishLocation':
                   $findByID='Tempat Terbit';
                    break;
                case 'PublishYear':
                    $findByID='Tahun Terbit';
                    break;
                case 'Alphabetical':
                    $findByID='Alphabetical';
                    break;
            }
            $record = "tag = " . $tag . " & findBy = " . $findBy . " & keyword1 = " . $query . " & keyword2 = " . $query2;
            $record2 = $findByID." = ".$query." & ".$tagID." = ".$query2;


            if (Yii::$app->request->get() && addslashes($_GET['action']) === "browse") {

                $dariTGL = ( isset($_GET['dariTGL']) ) ? addslashes($_GET['dariTGL']) : '2011-11-11';
                $sampaiTGL = ( isset($_GET['sampaiTGL']) ) ? addslashes($_GET['sampaiTGL']) : '2011-11-11';
                $ip = OpacHelpers::getIP();

                if (isset($_SESSION['RiwayatPencarian'])) {
                    $temp = $_SESSION['RiwayatPencarian'];
                    $_SESSION['RiwayatPencarian'] = array_merge($temp, array(
                        array(
                            "ip" => $ip,
                            "url" => $url,
                            "action" => addslashes($_GET['action']),
                            "keyword" => $record2,
                            "bahan" => '',
                            "time" => $waktu,
                        )
                    ));
                } else {
                    $temp = array(
                        array(
                            "ip" => $ip,
                            "url" => $url,
                            "action" => addslashes($_GET['action']),
                            "keyword" => $record2,
                            "bahan" => '',
                            "time" => $waktu,
                        )
                    );
                    $_SESSION['RiwayatPencarian'] = $temp;
                }

                if (Yii::$app->user->isGuest) {
                    $noAnggota = null;
                } else {
                    $noAnggota = \Yii::$app->user->identity->NoAnggota;
                }

                $logs=[

                    'user_id' => $noAnggota,
                    'ip'      => $ip,
                    'jenis_pencarian' => addslashes($_GET['action']),
                    'keyword' => $record2,
                    'url' => $url,
                    'isLKD' => 1,
                    'findByID' => $findByID,
                    'tagID' => $tagID,
                    'query' => $query,
                    'query2' => $query2,
                ];
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


                $command = Yii::$app->db->createCommand("CALL insertTempTelusurOpac('" . $tag . "','" . $findBy . "','" . $query . "','" . $query2 . "','" . $fAuthor . "','" . $fPublisher . "','" . $fPublishLoc . "','" . $fPublishYear . "','" . $fSubject . "','" . $fBahasa . "','1');");
                $command->execute();
                $count = Yii::$app->db->createCommand("CALL countPencarianSederhanaOpac1('" . $fAuthor . "','" . $fPublisher . "','" . $fPublishLoc . "','" . $fPublishYear . "','" . $fSubject . "','" . $fBahasa . "');")->queryScalar();

                $sqlSearch = "CALL pencarianSederhanaOpacLimit1('0','" . $limit . "','" . $fAuthor . "','" . $fPublisher . "','" . $fPublishLoc . "','" . $fPublishYear . "','" . $fSubject . "','" . $fBahasa . "');";
                $dataProviderSearch = new SqlDataProvider([
                    'sql' => $sqlSearch,
                    'pagination' => false,
                ]);

                $modelSearch = $dataProviderSearch->getModels();
                $countSearch = $dataProviderSearch->getCount();

                //buat generate faced
                $FacedAuthorMax = Yii::$app->config->get('FacedAuthorMaxLKD');
                $FacedPublisherMax = Yii::$app->config->get('FacedPublisherMax');
                $FacedPublishLocationMax = Yii::$app->config->get('FacedPublishLocationMax');
                $FacedPublishYearMax = Yii::$app->config->get('FacedPublishYearMax');
                $FacedSubjectMax = Yii::$app->config->get('FacedSubjectMax');
                $FacedBahasaMax = Yii::$app->config->get('FacedBahasaMax');


                /*$dataFacedAuthor = Yii::$app->db->createCommand("CALL facedAuthorOpac1('" . $fAuthor . "','" . $fPublisher . "','" . $fPublishLoc . "','" . $fPublishYear . "','" . $fSubject . "','" . $fBahasa . "','" . $FacedAuthorMax . "');")->queryAll();
                $dataFacedPublisher = Yii::$app->db->createCommand("CALL facedPublisherOpac1('" . $fAuthor . "','" . $fPublisher . "','" . $fPublishLoc . "','" . $fPublishYear . "','" . $fSubject . "','" . $fBahasa . "','" . $FacedPublisherMax . "');")->queryAll();
                $dataFacedPublishLocation = Yii::$app->db->createCommand("CALL facedPublishLocationOpac1('" . $fAuthor . "','" . $fPublisher . "','" . $fPublishLoc . "','" . $fPublishYear . "','" . $fSubject . "','" . $fBahasa . "','" . $FacedPublishLocationMax . "');")->queryAll();
                $dataFacedPublishYear = Yii::$app->db->createCommand("CALL facedPublishYearOpac1('" . $fAuthor . "','" . $fPublisher . "','" . $fPublishLoc . "','" . $fPublishYear . "','" . $fSubject . "','" . $fBahasa . "','" . $FacedPublishYearMax . "');")->queryAll();
                $dataFacedSubject = Yii::$app->db->createCommand("CALL facedSubjectOpac1('" . $fAuthor . "','" . $fPublisher . "','" . $fPublishLoc . "','" . $fPublishYear . "','" . $fSubject . "','" . $fBahasa . "','" . $FacedSubjectMax . "');")->queryAll();
                $dataFacedBahasa = Yii::$app->db->createCommand("CALL facedBahasaOpac1('" . $fAuthor . "','" . $fPublisher . "','" . $fPublishLoc . "','" . $fPublishYear . "','" . $fSubject . "','" . $fBahasa . "','" . $FacedBahasaMax . "');")->queryAll();*/

                /* $dataFacedAuthor = OpacHelpers::facedGenerator($dataFacedAuthor,'Author');
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

                           
                $temp = 1;
                foreach ($modelSearch as $value) {
                    $dataSearch[$temp] = $value;
                    $temp++;
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

                if (!isset($dataSearch)) {
                    $dataSearch = "";
                }
                if (isset($_POST['actions'])) {
                    echo"<pre>";
                    print_r($_POST['action']);
                    echo"</pre>";
                }
                return $this->render('resultListOpac', [
                            //'model' => $model,
                            'dataProviderResult' => $dataProviderSearch,
                            'countResult' => $countSearch,
                            'dataResult' => $dataSearch,
                            'totalCountResult' => $count,
                            'dataFacedAuthor' => $dataFacedAuthor,
                            'dataFacedPublisher' => $dataFacedPublisher,
                            'dataFacedPublishYear' => $dataFacedPublishYear,
                            'dataFacedPublishLocation' => $dataFacedPublishLocation,
                            'dataFacedSubject' => $dataFacedSubject,
                            'dataFacedBahasa' => $dataFacedBahasa,
                            'alert' => $alert,
                ]);
            }//end if telusur
        } //endif
        if ($request->isAjax && $_GET['action'] === "favourite") {
            if (Yii::$app->user->isGuest) {
                return $this->redirect('../keanggotaan/site/login');
            }
            $model = new favorite;
            (int) $count = favorite::find()
                    ->where(['Member_Id' => \Yii::$app->user->identity->NoAnggota, 'Catalog_Id' => $_GET['catID']])
                    ->count();

            if ($count == 0) {
                $model->Member_Id = \Yii::$app->user->identity->NoAnggota;
                $model->Catalog_Id = $_GET['catID'];
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
        } //end if favorite
        if ($request->isAjax && $_GET['action'] === "showCollection") {

            if (Yii::$app->user->isGuest) {
                $noAnggota = null;
            } else {
                $noAnggota = \Yii::$app->user->identity->NoAnggota;
            }

            if ($_GET['serial'] == 'true') {
                $searchModel = new CollectionSearchKardeks;
                $params['CatalogId'] = addslashes($_GET['catID']);
                $dataProvider = $searchModel->search2(Yii::$app->request->getQueryParams());
                //echo '<pre>'; print_r(Yii::$app->request->getQueryParams()); echo '</pre>';die;
                return $this->renderAjax('_serial', [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                ]);
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
        }////////////////////////////////////////////////////////////////////////////////////////////////f
        if ($request->isAjax && $_GET['action'] === "logDownload") {
            OpacHelpers::logsDownload($_GET['ID'],$noAnggota,'1');          
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
        if ($request->isAjax && $_GET['action'] === "boooking") {

            if (Yii::$app->user->isGuest) {
                return $this->redirect('../keanggotaan/site/login');
            }
            $colID = $_GET['colID'];
            $cekBooking = OpacHelpers::cekBooking($noAnggota,$colID);       
            $noAnggota = \Yii::$app->user->identity->NoAnggota;
            $dateNow = new \DateTime("now");
            $dateAdd = new \DateTime("now");

            $tambahJam= explode(":",$bookExp);


            $dateAdd->modify("+".$tambahJam[0]." hours +".$tambahJam[1]." minutes +".$tambahJam[2]." seconds");

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
        if ($request->isAjax && $_GET['action'] === "search") {
            $catID = addslashes($_GET['catID']);
            $sqlSearch = "
		SELECT CAT.id CatalogId,CAT.title kalimat2,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
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
                        'booking' => $booking,
            ]);
        }
        if ($request->isAjax && $_GET['action'] === "showBookingDetail") {

            if (Yii::$app->user->isGuest) {
                $noAnggota = null;
                return $this->renderPartial('_bookingList', ['noAnggota' => $noAnggota,]);
            } else {
                $dateNow = new \DateTime("now");
                $noAnggota = \Yii::$app->user->identity->NoAnggota;
                $booking = Collections::find()
                        ->select([
                            'collections.BookingExpiredDate',
                            'catalogs.Title',
                        ])
                        ->leftJoin('catalogs', '`catalogs`.`ID` = `collections`.`Catalog_id`')
                        ->andWhere('BookingMemberID ="' . $noAnggota.'"')
                        ->andWhere('BookingExpiredDate >  "' . $dateNow->format("Y-m-d H:i:s") . '"')
                        ->all();
                return $this->renderPartial('_bookingList', [
                            'booking' => $booking,
                            'noAnggota' => $noAnggota,
                ]);
            }
        }
        //buat nampilin browse nya
        if (isset($_GET['tag'])) {

            if (isset($_GET['findBy'])) {

                $sql = "call BrowseOpac('" . addslashes($_GET['findBy']) . "','','','1',".$location.");";
                $dataProvider = new SqlDataProvider([
                    'sql' => $sql,
                    'pagination' => false,
                ]);
                $model = $dataProvider->getModels();


                if (isset($_GET['query'])) {
                    $sql2 = "call BrowseOpac('" . addslashes($_GET['findBy']) . "','" . addslashes($_GET['tag']) . "','" . addslashes($_GET['query']) . "','1',".$location.");";
                    $dataProvider2 = new SqlDataProvider([
                        'sql' => $sql2,
                        'pagination' => false,
                    ]);
                    $model2 = $dataProvider2->getModels();
                    return $this->render('index', [
                                'model' => $model,
                                'model2' => $model2,
                    ]);
                }
                return $this->render('index', [
                            'model' => $model,
                ]);
            }
        }
        return $this->render('index');
    }

}
