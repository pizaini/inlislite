<?php

namespace article\controllers;

use Yii;
use common\models\Opaclogs;
use common\models\Bookinglogs;
use common\models\Favorite;
use common\models\Collections;
use common\models\Catalogs;
use common\models\CollectionSearchKardeks;
use yii\data\SqlDataProvider;
use yii\web\Session;
use yii\web\Request;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\components\OpacHelpers;
use common\models\OpaclogsKeyword;
class BrowseController extends \yii\web\Controller {

    public $layout = 'main';

    public function actionIndex() {
        $jmlBookMaks = Yii::$app->config->get('JumlahBookingMaksimal');
        $bookExp = Yii::$app->config->get('BookingExpired');
        $UsulanKoleksi = Yii::$app->config->get('UsulanKoleksi');
        $dateNow = new \DateTime("now");
        $noAnggota= (Yii::$app->user->isGuest ? null : \Yii::$app->user->identity->NoAnggota );
        $booking = OpacHelpers::jumlahBooking($noAnggota);
        $alert = FALSE;
        $CID = $_GET['CID'];

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
                    'isLKD' => 0,
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

                $limitAwal = ($page - 1) * $limit;


                $command = Yii::$app->db->createCommand("CALL insertTempTelusurArticle('" . $tag . "','" . $findBy . "','" . $query . "','" . $query2 . "','" . $fAuthor . "','" . $fPublisher . "','" . $fPublishLoc . "','" . $fPublishYear . "','" . $fSubject . "','" . $fBahasa . "','');");
                $command->execute();



                if ($CID){
                    $count = Yii::$app->db->createCommand("select count(1) from tempCariArticle where CatalogId=".$CID." ")->queryScalar();
                    $hasilSearch = Yii::$app->db->createCommand("select * from tempCariArticle where CatalogId=".$CID." limit 0,$limit")->queryAll();
                } else {
                    $count = Yii::$app->db->createCommand("select count(1) from tempCariArticle")->queryScalar();
                    $hasilSearch = Yii::$app->db->createCommand("select * from tempCariArticle limit 0,$limit")->queryAll();
                }

                $FacedAuthorMax = Yii::$app->config->get('FacedAuthorMax');
                $FacedPublisherMax = Yii::$app->config->get('FacedPublisherMax');
                $FacedPublishLocationMax = Yii::$app->config->get('FacedPublishLocationMax');
                $FacedPublishYearMax = Yii::$app->config->get('FacedPublishYearMax');
                $FacedSubjectMax = Yii::$app->config->get('FacedSubjectMax');


                $req=array(
                    'fAuthor' => $fAuthor,
                    'fPublisher' => $fPublisher,
                    'fPublishLoc' =>$fPublishLoc,
                    'fPublishYear' => $fPublishYear,
                    'fSubject' => $fSubject,
                    'fBahasa' => $fBahasa);
                $dataFacedAuthor = OpacHelpers::facedGenerator(OpacHelpers::facedOpac('Author',$req,'article'),'Author');
                $dataFacedPublisher = OpacHelpers::facedGenerator(OpacHelpers::facedOpac('Publisher',$req,'article'),'Publisher');
                $dataFacedPublishLocation = OpacHelpers::facedGenerator(OpacHelpers::facedOpac('PublishLocation',$req,'article'),'PublishLocation');
                $dataFacedPublishYear = OpacHelpers::facedGenerator(OpacHelpers::facedOpac('PublishYear',$req,'article'),'PublishYear');
                $dataFacedSubject = OpacHelpers::facedGenerator(OpacHelpers::facedOpac('SUBJECT',$req,'article'),'SUBJECT');
                $dataFacedBahasa = OpacHelpers::facedGenerator(OpacHelpers::facedOpac('bahasa',$req,'article'),'bahasa');


                foreach ($hasilSearch as $key => $value) {
                $dataSearch[$key] = $value;
                $dataTagRDA        = OpacHelpers::getTaginfo($dataSearch[$key]['CatalogId'],'336,338','a');
                $jenisBahanRDA     = OpacHelpers::jenisBahanRDA($dataTagRDA);
                $jenis_bahanold    = $dataSearch[$key]['worksheet'];
                $dataSearch[$key]['worksheet'] = $jenis_bahanold." ".$jenisBahanRDA;
                $dataSearch[$key]['authOriginal'] =  array_values(array_filter(explode("|",OpacHelpers::sqlDetailOpac('PENGARANG',$dataSearch[$key]['CatalogId']))));
                $dataSearch[$key]['authModif'] = preg_replace("/\([^)]+\)/","",$dataSearch[$key]['authOriginal']);
                $dataSearch[$key]['keyword']=urldecode($_GET['katakunci']);
                $dataSearch[$key]['kalimat2'] =  OpacHelpers::highlight($dataSearch[$key]['kalimat2'],$dataSearch[$key]['keyword']);

                //replace authoriginal with highlighed string
                foreach ($dataSearch[$key]['authOriginal'] as $keys => &$values){
                    $values = OpacHelpers::highlight($values,$dataSearch[$key]['keyword']);
                }

                //OpacHelpers::print__r($hasilSearch);

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
                            'countResult' => count($hasilSearch),
                            'dataResult' => $dataSearch,
                            'totalCountResult' => $count,
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
        if ($request->isAjax && $_GET['action'] === "logDownload") {
            OpacHelpers::logsDownload($_GET['ID'],$noAnggota,'0');          
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
                $dataTagRDA        = OpacHelpers::getTaginfo($dataSearch[$temp]['CatalogId'],'336,338','a');
                $jenisBahanRDA     = OpacHelpers::jenisBahanRDA($dataTagRDA);
                $jenis_bahanold    = $dataSearch[$temp]['worksheet'];
                $dataSearch[$temp]['worksheet'] = $jenis_bahanold." ".$jenisBahanRDA;
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

                $sql = "call BrowseOpac('" . addslashes($_GET['findBy']) . "','','','','');";
                $dataProvider = new SqlDataProvider([
                    'sql' => $sql,
                    'pagination' => false,
                ]);
                $model = $dataProvider->getModels();


                if (isset($_GET['query'])) {
                    $sql2 = "call BrowseOpac('" . addslashes($_GET['findBy']) . "','" . addslashes($_GET['tag']) . "','" . addslashes($_GET['query']) . "','','');";
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
