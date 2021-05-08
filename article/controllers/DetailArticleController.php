<?php

namespace article\controllers;


use common\models\SerialArticleFilesSearch;
use Yii;
use common\models\Opaclogs;
use common\models\Catalogs;
use common\models\Bookinglogs;
use common\models\SerialArticles;
use common\models\SerialArticlesSearch;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;
use yii\web\Session;
use common\components\DirectoryHelpers;

use yii\web\Request;
use common\components\MarcHelpers;
use common\components\OpacHelpers;
$session = Yii::$app->session;
$session->open();

class DetailArticleController extends \yii\web\Controller {

    public $layout = 'main-sederhana';
    public $id;
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
            // echo'<pre>';print_r($_GET['ID']);die;
            OpacHelpers::logsDownloadArticle($_GET['ID'],$noAnggota,'0');          
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

            $id = $_GET['id'];
            $modelArticle = SerialArticles::findOne($id);
            $modelCatalog = Catalogs::findOne($modelArticle->Catalog_id);
            $modelArticleRepeatable = \common\models\SerialArticlesRepeatable::find()->where(['serial_article_ID'=>$id])->asArray()->All();
            //kalo ga ketemu return error(mungkin salah ID,tidak dipublish)
            if(sizeof($modelArticle)==0){

                throw new \yii\web\NotFoundHttpException("Maaf Data Yang anda cari tidak ditemukan");
            }
            $searchModelArticles = new SerialArticleFilesSearch();
            $dataProviderKontenDigital = $searchModelArticles->searchByArticleID($id,$rulesColl=null);
            $marcOpac = Yii::$app->db->createCommand(" call marcCatalogOpac(" . $modelArticle->Catalog_id . ")")->queryall();
            $query="
                 SELECT sa.ID,art.`Catalog_id`,sa.FileURL,sa.FileFlash,sa.IsPublish,
                (SELECT  SUBSTRING(FileURL,(LENGTH(FileURL)-LOCATE('.',REVERSE(FileURL)))+2))  AS FormatFile	
                FROM serial_articlefiles sa 
                LEFT JOIN serial_articles art ON art.id = sa.`Articles_id`	
                WHERE IsPublish <>  0 and art.ID = ".$id.";
            ";
            $dataKontenDigitalArticle = Yii::$app->db->createCommand($query)->queryAll();

            foreach($modelArticleRepeatable as $key => $item)
               {
                 $group[$item['article_field']][$key] = $item['value'];
               }

            $cite['APA'] = $modelArticle->Creator . ".<i>" . $this->HurufBesarTiapKata($modelArticle->Title) . " </i>." . $modelArticle->EDISISERIAL;
            if($modelCatalog['CoverURL'])
            {
                if(file_exists(Yii::getAlias('@uploaded_files/sampul_koleksi/original/'.DirectoryHelpers::GetDirWorksheet($modelCatalog['Worksheet_id']).'/'.$modelCatalog['CoverURL'])))
                {
                    $urlcover= '../uploaded_files/sampul_koleksi/original/'.DirectoryHelpers::GetDirWorksheet($modelCatalog['Worksheet_id']).'/'.$modelCatalog['CoverURL'];
                }
                else {
                    $urlcover= '../uploaded_files/sampul_koleksi/original/Monograf/tdkada.gif';
                }

            }else{
                $urlcover= '../uploaded_files/sampul_koleksi/original/Monograf/tdkada.gif';
            }

            return $this->render('index', [
                        'dataKontenDigitalArticle' => $dataKontenDigitalArticle,
                        'countDataKontenDigitalArticle' => sizeof($dataKontenDigitalArticle),
                        'marcOpac' => $marcOpac,
                        'urlcover' => $urlcover,
                        'modelArticle' => $modelArticle,
                        'modelArticleRepeatable' => $group,
                        'modelCatalog' => $modelCatalog,
                        'dataProviderKontenDigital' => $dataProviderKontenDigital,
                        'searchModelArticles' => $searchModelArticles,
                        'noAnggota' => $noAnggota,
                        'serialID' => $id,
                        'cite' => $cite,
            ]);
        }
    }
    public function actionDownload($id,$type)
    {
        MarcHelpers::Export($id,$type);

    }


}
